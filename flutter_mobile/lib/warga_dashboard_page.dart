import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class WargaDashboardPage extends StatefulWidget {
  final String token;
  final Map<String, dynamic> user;

  const WargaDashboardPage({
    super.key,
    required this.token,
    required this.user,
  });

  @override
  State<WargaDashboardPage> createState() => _WargaDashboardPageState();
}

class _WargaDashboardPageState extends State<WargaDashboardPage> {
  bool _loading = false;
  String? _error;
  Map<String, dynamic>? _profile;
  List<dynamic> _pengajuans = [];

  // UI counts (disederhanakan dari data status terbaru yang tersedia)
  int get _pendingCount =>
      _pengajuans.where((p) => p['status'] == 'baru').length;
  int get _approvedCount => _pengajuans
      .where((p) => p['status'] == 'disetujui_rt' || p['status'] == 'diterima')
      .length;
  int get _rejectedCount =>
      _pengajuans.where((p) => p['status'] == 'ditolak').length;

  int _page = 1;
  bool _hasMore = true;

  String get _apiBase => 'http://127.0.0.1:8000';

  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer ${widget.token}',
  };

  @override
  void initState() {
    super.initState();
    _loadAll();
  }

  Future<void> _loadAll() async {
    await _fetchProfile();
    await _fetchPengajuans(reset: true);
  }

  Future<void> _fetchProfile() async {
    setState(() => _error = null);
    try {
      final res = await http.get(
        Uri.parse('$_apiBase/api/profile'),
        headers: _headers,
      );
      if (res.statusCode == 200) {
        final data = jsonDecode(res.body) as Map<String, dynamic>;
        setState(() => _profile = data['data'] ?? data);
      } else {
        setState(() => _error = 'Gagal load profile (${res.statusCode})');
      }
    } catch (_) {
      setState(() => _error = 'Tidak bisa terhubung ke server (profile).');
    }
  }

  Future<void> _fetchPengajuans({required bool reset}) async {
    if (_loading) return;

    setState(() {
      _loading = true;
      _error = null;
    });

    try {
      final page = reset ? 1 : _page;
      final uri = Uri.parse('$_apiBase/api/pengajuan?page=$page');
      final res = await http.get(uri, headers: _headers);

      if (res.statusCode != 200) {
        setState(() => _error = 'Gagal load pengajuan (${res.statusCode})');
        return;
      }

      final data = jsonDecode(res.body) as Map<String, dynamic>;
      final items = (data['data'] as List<dynamic>?) ?? [];
      final pagination = (data['pagination'] as Map<String, dynamic>?) ?? {};
      final lastPage = (pagination['last_page'] as num?)?.toInt() ?? 1;

      setState(() {
        if (reset) {
          _pengajuans = items;
          _page = 2;
        } else {
          _pengajuans.addAll(items);
          _page++;
        }
        _hasMore = page < lastPage;
      });
    } catch (_) {
      setState(() => _error = 'Tidak bisa terhubung ke server (pengajuan).');
    } finally {
      setState(() => _loading = false);
    }
  }

  Future<void> _refresh() async {
    await _fetchPengajuans(reset: true);
    await _fetchProfile();
  }

  @override
  Widget build(BuildContext context) {
    final name = widget.user['name']?.toString() ?? '-';
    final role = widget.user['role']?.toString() ?? '-';

    // Render mirip dashboard web (card-panel, badge, header, CTA)
    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard Warga'),
        actions: [
          IconButton(
            tooltip: 'Refresh',
            onPressed: _refresh,
            icon: const Icon(Icons.refresh),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refresh,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            const SizedBox(height: 8),
            _HeroWelcome(name: name),
            const SizedBox(height: 14),
            _RolePill(role: role),
            const SizedBox(height: 16),

            if (_error != null) _ErrorBox(message: _error!),

            const SizedBox(height: 12),

            // Row rekap (4 kotak seperti web)
            _RekapRow(
              pending: _pendingCount,
              approved: _approvedCount,
              rejected: _rejectedCount,
              total: _pengajuans.length,
            ),

            const SizedBox(height: 16),

            // CTA (Ajukan Surat Baru)
            Card(
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              child: Padding(
                padding: const EdgeInsets.all(14),
                child: Row(
                  children: [
                    const Icon(Icons.add_circle_outline, size: 28),
                    const SizedBox(width: 10),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text(
                            'Ajukan Surat Baru',
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 16,
                            ),
                          ),
                          SizedBox(height: 2),
                          Text(
                            'Ajukan pengajuan surat dengan cepat dari mobile.',
                            style: TextStyle(color: Colors.black54),
                          ),
                        ],
                      ),
                    ),
                    FilledButton.icon(
                      onPressed: () {
                        Navigator.of(context).push(
                          MaterialPageRoute(
                            builder: (_) => const WargaPengajuanFormPage(),
                          ),
                        );
                      },
                      icon: const Icon(Icons.send),
                      label: const Text('Ajukan'),
                      style: FilledButton.styleFrom(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 14,
                          vertical: 12,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 14),

            // Header status terbaru
            _SectionHeader(
              title: 'Status Pengajuan Terbaru',
              subtitle: 'Periksa dan kelola pengajuan langsung di sini.',
              total: _pengajuans.length,
            ),

            const SizedBox(height: 12),

            if (_pengajuans.isEmpty)
              _EmptyState(
                title: 'Belum ada pengajuan',
                subtitle: 'Mulai ajukan surat untuk melihat daftar di sini.',
              )
            else
              ..._pengajuans.map((p) => _PengajuanTile(p: p)).toList(),

            if (_loading)
              const Padding(
                padding: EdgeInsets.symmetric(vertical: 16),
                child: Center(child: CircularProgressIndicator()),
              )
            else if (_hasMore)
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 16),
                child: Center(
                  child: OutlinedButton.icon(
                    onPressed: () => _fetchPengajuans(reset: false),
                    icon: const Icon(Icons.more_horiz),
                    label: const Text('Muat lebih banyak'),
                  ),
                ),
              ),

            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }
}

class _HeroWelcome extends StatelessWidget {
  const _HeroWelcome({required this.name});

  final String name;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Selamat datang, $name',
          style: Theme.of(
            context,
          ).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.w900),
        ),
        const SizedBox(height: 4),
        const Text(
          'Status pengajuan Anda:',
          style: TextStyle(color: Colors.black54),
        ),
      ],
    );
  }
}

class _RolePill extends StatelessWidget {
  const _RolePill({required this.role});

  final String role;

  @override
  Widget build(BuildContext context) {
    final c = role == 'warga'
        ? Colors.green
        : role == 'rt'
        ? Colors.orange
        : role == 'rw'
        ? Colors.purple
        : Colors.blueGrey;

    return Row(
      children: [
        Chip(
          label: Text('Role: $role'),
          backgroundColor: c.withOpacity(0.12),
          labelStyle: TextStyle(color: c, fontWeight: FontWeight.w700),
        ),
      ],
    );
  }
}

class _RekapRow extends StatelessWidget {
  const _RekapRow({
    required this.pending,
    required this.approved,
    required this.rejected,
    required this.total,
  });

  final int pending;
  final int approved;
  final int rejected;
  final int total;

  @override
  Widget build(BuildContext context) {
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      mainAxisSpacing: 12,
      crossAxisSpacing: 12,
      childAspectRatio: 3.0,
      children: [
        _RekapCard(
          color: Colors.green,
          label: 'Pengajuan Baru',
          value: pending,
          hint: 'Menunggu',
          icon: Icons.hourglass_bottom,
        ),
        _RekapCard(
          color: Colors.blue,
          label: 'Disetujui / Diterima',
          value: approved,
          hint: 'Surat selesai',
          icon: Icons.check_circle,
        ),
        _RekapCard(
          color: Colors.red,
          label: 'Ditolak',
          value: rejected,
          hint: 'Pengajuan gagal',
          icon: Icons.cancel,
        ),
        _RekapCard(
          color: Colors.indigo,
          label: 'Total Pengajuan',
          value: total,
          hint: 'Seluruh pengajuan',
          icon: Icons.list_alt,
        ),
      ],
    );
  }
}

class _RekapCard extends StatelessWidget {
  const _RekapCard({
    required this.color,
    required this.label,
    required this.value,
    required this.hint,
    required this.icon,
  });

  final Color color;
  final String label;
  final int value;
  final String hint;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        color: color.withOpacity(0.06),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, size: 18, color: color),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  label,
                  style: TextStyle(fontWeight: FontWeight.w700, color: color),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            '$value',
            style: TextStyle(
              fontSize: 26,
              fontWeight: FontWeight.w900,
              color: color,
            ),
          ),
          const SizedBox(height: 2),
          Text(hint, style: TextStyle(color: Colors.black54, fontSize: 12)),
        ],
      ),
    );
  }
}

class _SectionHeader extends StatelessWidget {
  const _SectionHeader({
    required this.title,
    required this.subtitle,
    required this.total,
  });

  final String title;
  final String subtitle;
  final int total;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w900,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                subtitle,
                style: TextStyle(color: Colors.grey.shade600, fontSize: 12),
              ),
            ],
          ),
        ),
        Chip(
          backgroundColor: Colors.green.withOpacity(0.12),
          labelStyle: const TextStyle(
            color: Colors.green,
            fontWeight: FontWeight.w800,
          ),
          label: Text('$total baris'),
        ),
      ],
    );
  }
}

class _ErrorBox extends StatelessWidget {
  const _ErrorBox({required this.message});

  final String message;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.red.shade50,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: Colors.red.shade200),
      ),
      child: Text(
        message,
        style: TextStyle(
          color: Colors.red.shade900,
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }
}

class _EmptyState extends StatelessWidget {
  const _EmptyState({required this.title, required this.subtitle});

  final String title;
  final String subtitle;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 26),
      child: Column(
        children: [
          Icon(Icons.inbox_outlined, size: 56, color: Colors.grey.shade400),
          const SizedBox(height: 10),
          Text(title, style: const TextStyle(fontWeight: FontWeight.w900)),
          const SizedBox(height: 6),
          Text(
            subtitle,
            textAlign: TextAlign.center,
            style: TextStyle(color: Colors.grey.shade600),
          ),
        ],
      ),
    );
  }
}

class _PengajuanTile extends StatelessWidget {
  final dynamic p;

  const _PengajuanTile({required this.p});

  @override
  Widget build(BuildContext context) {
    final id = p['id']?.toString() ?? '-';
    final jenis = p['jenis_surat']?.toString() ?? '-';
    final status = p['status']?.toString() ?? '-';
    final alasan = p['alasan']?.toString() ?? '';
    final createdAt = p['created_at']?.toString();

    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      elevation: 0,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    'Pengajuan #$id',
                    style: const TextStyle(
                      fontWeight: FontWeight.w900,
                      fontSize: 15,
                    ),
                  ),
                ),
                _StatusChip(status: status),
              ],
            ),
            const SizedBox(height: 10),
            _KeyValueRow(label: 'Jenis', value: jenis),
            _KeyValueRow(label: 'NIK', value: p['nik']?.toString() ?? '-'),
            const SizedBox(height: 8),
            if (alasan.isNotEmpty)
              Text(
                'Alasan: ${alasan.length > 120 ? alasan.substring(0, 120) + '…' : alasan}',
                style: TextStyle(color: Colors.grey.shade700),
              ),
            const SizedBox(height: 8),
            if (createdAt != null && createdAt.isNotEmpty)
              Text(
                'Dibuat: $createdAt',
                style: TextStyle(color: Colors.grey.shade500, fontSize: 12),
              ),
          ],
        ),
      ),
    );
  }
}

class _KeyValueRow extends StatelessWidget {
  final String label;
  final String value;

  const _KeyValueRow({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        children: [
          SizedBox(
            width: 70,
            child: Text(
              '$label:',
              style: TextStyle(
                color: Colors.grey.shade600,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(fontWeight: FontWeight.w700),
            ),
          ),
        ],
      ),
    );
  }
}

class _StatusChip extends StatelessWidget {
  final String status;

  const _StatusChip({required this.status});

  @override
  Widget build(BuildContext context) {
    Color c;
    switch (status) {
      case 'baru':
        c = Colors.blue.shade600;
        break;
      case 'disetujui_rt':
        c = Colors.orange.shade700;
        break;
      case 'diterima':
        c = Colors.green.shade700;
        break;
      case 'ditolak':
        c = Colors.red.shade700;
        break;
      default:
        c = Colors.grey.shade700;
    }

    return Chip(
      label: Text(status.replaceAll('_', ' ')),
      backgroundColor: c.withOpacity(0.12),
      labelStyle: TextStyle(color: c, fontWeight: FontWeight.w800),
    );
  }
}

class WargaPengajuanFormPage extends StatefulWidget {
  const WargaPengajuanFormPage({super.key});

  @override
  State<WargaPengajuanFormPage> createState() => _WargaPengajuanFormPageState();
}

class _WargaPengajuanFormPageState extends State<WargaPengajuanFormPage> {
  final _jenisController = TextEditingController();
  final _namaController = TextEditingController();
  final _nikController = TextEditingController();
  final _alamatController = TextEditingController();
  final _alasanController = TextEditingController();

  bool _loading = false;
  String? _message;
  String? _error;

  String get _apiBase => 'http://127.0.0.1:8000';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Ajukan Surat (Mobile)')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          if (_message != null)
            _MessageBox(message: _message!, isSuccess: true),
          if (_error != null) _MessageBox(message: _error!, isSuccess: false),

          const SizedBox(height: 12),

          Card(
            elevation: 0,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
            ),
            child: Padding(
              padding: const EdgeInsets.all(14),
              child: Column(
                children: [
                  DropdownButtonFormField<String>(
                    value: _jenisController.text.isEmpty
                        ? null
                        : _jenisController.text,
                    decoration: const InputDecoration(
                      contentPadding: EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 12,
                      ),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.all(Radius.circular(14)),
                      ),
                    ),
                    hint: const Text('Pilih Jenis Surat'),
                    isExpanded: true,
                    items:
                        const [
                          'Pengajuan KTP',
                          'Pengajuan Surat Pengantar',
                          'Pengajuan Surat Domisili',
                          'Pengajuan Kartu Keluarga (KK)',
                          'Surat Keterangan Tidak Mampu (SKTM)',
                          'Surat Keterangan Usaha (SKU)',
                          'Surat Keterangan Pindah / Datang',
                          'Surat Keterangan Kematian',
                        ].map((e) {
                          return DropdownMenuItem<String>(
                            value: e,
                            child: Text(e),
                          );
                        }).toList(),
                    onChanged: (v) {
                      _jenisController.text = v ?? '';
                    },
                  ),
                  _Field(label: 'Nama', controller: _namaController),
                  _Field(
                    label: 'NIK',
                    controller: _nikController,
                    keyboardType: TextInputType.number,
                  ),
                  _Field(label: 'Alamat', controller: _alamatController),
                  _Field(
                    label: 'Alasan',
                    controller: _alasanController,
                    maxLines: 4,
                  ),
                  const SizedBox(height: 14),
                  SizedBox(
                    width: double.infinity,
                    child: FilledButton.icon(
                      onPressed: _loading
                          ? null
                          : () async {
                              setState(() {
                                _loading = true;
                                _error = null;
                                _message = null;
                              });

                              try {
                                final prefs =
                                    await SharedPreferences.getInstance();
                                final token = prefs.getString('auth_token');
                                if (token == null || token.isEmpty) {
                                  setState(
                                    () => _error =
                                        'Token tidak ditemukan. Silakan login ulang.',
                                  );
                                  return;
                                }

                                final payload = {
                                  'jenis_surat': _jenisController.text.trim(),
                                  'nama': _namaController.text.trim(),
                                  'nik': _nikController.text.trim(),
                                  'alamat': _alamatController.text.trim(),
                                  'alasan': _alasanController.text.trim(),
                                };

                                final uri = Uri.parse(
                                  '$_apiBase/api/pengajuan',
                                );
                                final res = await http.post(
                                  uri,
                                  headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer $token',
                                  },
                                  body: jsonEncode(payload),
                                );

                                final body =
                                    jsonDecode(res.body)
                                        as Map<String, dynamic>;

                                if (res.statusCode == 201 ||
                                    res.statusCode == 200) {
                                  setState(
                                    () => _message =
                                        body['message']?.toString() ??
                                        'Pengajuan berhasil dikirim ✅',
                                  );
                                } else {
                                  setState(
                                    () => _error =
                                        body['message']?.toString() ??
                                        'Gagal kirim pengajuan (${res.statusCode}).',
                                  );
                                }
                              } catch (_) {
                                setState(
                                  () => _error =
                                      'Tidak bisa terhubung ke server pengajuan.',
                                );
                              } finally {
                                setState(() => _loading = false);
                              }
                            },
                      icon: _loading
                          ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(strokeWidth: 2),
                            )
                          : const Icon(Icons.send),
                      label: Text(_loading ? 'Mengirim...' : 'Kirim Pengajuan'),
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'Catatan: versi ini fokus dashboard & form ajukan. Tahap berikutnya bisa ditambahkan upload file multipart sesuai web.',
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _Field extends StatelessWidget {
  final String label;
  final TextEditingController controller;
  final TextInputType keyboardType;
  final int maxLines;

  const _Field({
    required this.label,
    required this.controller,
    this.keyboardType = TextInputType.text,
    this.maxLines = 1,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontWeight: FontWeight.w900)),
          const SizedBox(height: 6),
          TextField(
            controller: controller,
            keyboardType: keyboardType,
            maxLines: maxLines,
            decoration: InputDecoration(
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 12,
                vertical: 12,
              ),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(14),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(14),
                borderSide: BorderSide(
                  color: Theme.of(context).colorScheme.primary,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _MessageBox extends StatelessWidget {
  final String message;
  final bool isSuccess;

  const _MessageBox({required this.message, required this.isSuccess});

  @override
  Widget build(BuildContext context) {
    final bg = isSuccess ? Colors.green.shade50 : Colors.red.shade50;
    final border = isSuccess ? Colors.green.shade200 : Colors.red.shade200;
    final fg = isSuccess ? Colors.green.shade900 : Colors.red.shade900;

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: border),
      ),
      child: Text(
        message,
        style: TextStyle(color: fg, fontWeight: FontWeight.w700),
      ),
    );
  }
}
