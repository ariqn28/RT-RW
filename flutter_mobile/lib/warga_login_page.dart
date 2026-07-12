import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

import 'warga_dashboard_page.dart';

class WargaLoginPage extends StatefulWidget {
  const WargaLoginPage({super.key});

  @override
  State<WargaLoginPage> createState() => _WargaLoginPageState();
}

class _WargaLoginPageState extends State<WargaLoginPage> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _deviceController = TextEditingController();

  bool _isLoading = false;
  String? _toastError;
  String? _toastSuccess;

  String? _token;
  Map<String, dynamic>? _user;

  static const _appName = 'Layanan Warga';

  static String get _loginUrl {
    // untuk emulator android biasanya butuh 10.0.2.2, untuk web tinggal 127.0.0.1
    if (kIsWeb) {
      return 'http://127.0.0.1:8000/api/mobile/login';
    }
    return 'http://10.0.2.2:8000/api/mobile/login';
  }

  @override
  void initState() {
    super.initState();
    _emailController.text = 'warga@gmail.com';
    _passwordController.text = '12345678';
    _deviceController.text = 'Flutter App';
    _loadStoredToken();
  }

  Future<void> _loadStoredToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token != null && token.isNotEmpty) {
      setState(() {
        _token = token;
        _toastSuccess = 'Token tersimpan ✅';
      });
    }
  }

  Future<void> _login() async {
    setState(() {
      _isLoading = true;
      _toastError = null;
      _toastSuccess = null;
      _user = null;
    });

    try {
      final email = _emailController.text.trim();
      final password = _passwordController.text;
      final deviceName = _deviceController.text.trim().isEmpty
          ? 'Flutter App'
          : _deviceController.text.trim();

      final response = await http.post(
        Uri.parse(_loginUrl),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'email': email,
          'password': password,
          'device_name': deviceName,
        }),
      );

      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200) {
        final token = data['token']?.toString();
        final user = data['user'] as Map<String, dynamic>?;

        if (token != null && token.isNotEmpty) {
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString('auth_token', token);
        }

        if (!mounted) return;
        setState(() {
          _token = token;
          _user = user;
          _toastSuccess = data['message']?.toString() ?? 'Login berhasil ✅';
        });

        if (token != null && token.isNotEmpty && user != null) {
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(
              builder: (_) => WargaDashboardPage(token: token, user: user),
            ),
          );
        }
      } else {
        setState(() {
          _toastError = data['message']?.toString() ?? 'Login gagal';
        });
      }
    } catch (_) {
      setState(() {
        _toastError =
            'Gagal terhubung ke server. Pastikan Laravel berjalan & URL benar.';
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    setState(() {
      _token = null;
      _user = null;
      _toastSuccess = 'Sesi dihapus.';
    });
  }

  @override
  Widget build(BuildContext context) {
    final colorA = Theme.of(context).colorScheme.primary;
    final colorB = Colors.green.shade600;

    return Scaffold(
      backgroundColor: Colors.grey.shade50,
      body: SafeArea(
        child: Stack(
          children: [
            Positioned.fill(
              child: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      colorA.withOpacity(0.15),
                      colorB.withOpacity(0.12),
                      Colors.white,
                    ],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
              ),
            ),
            Align(
              alignment: Alignment.center,
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(20),
                child: ConstrainedBox(
                  constraints: const BoxConstraints(maxWidth: 420),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 10),
                      _BrandHeader(appName: _appName),
                      const SizedBox(height: 18),
                      if (_toastError != null)
                        _Toast(
                          color: Colors.red.shade50,
                          borderColor: Colors.red.shade200,
                          textColor: Colors.red.shade900,
                          message: _toastError!,
                        ),
                      if (_toastSuccess != null)
                        _Toast(
                          color: Colors.green.shade50,
                          borderColor: Colors.green.shade200,
                          textColor: Colors.green.shade900,
                          message: _toastSuccess!,
                        ),
                      const SizedBox(height: 14),
                      Card(
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(18),
                          side: BorderSide(color: Colors.grey.shade200),
                        ),
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Masuk sebagai warga',
                                style: Theme.of(context).textTheme.headlineSmall
                                    ?.copyWith(fontWeight: FontWeight.w800),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                'Gunakan akun warga untuk mengakses pengajuan surat dan riwayat.',
                                style: TextStyle(
                                  color: Colors.grey.shade700,
                                  height: 1.4,
                                ),
                              ),
                              const SizedBox(height: 16),
                              _LabeledField(
                                label: 'Email',
                                hint: 'warga@example.com',
                                controller: _emailController,
                                keyboardType: TextInputType.emailAddress,
                              ),
                              const SizedBox(height: 12),
                              _LabeledField(
                                label: 'Kata sandi',
                                hint: '••••••••',
                                controller: _passwordController,
                                obscureText: true,
                              ),
                              const SizedBox(height: 12),
                              _LabeledField(
                                label: 'Device',
                                hint: 'Flutter App',
                                controller: _deviceController,
                              ),
                              const SizedBox(height: 18),
                              SizedBox(
                                width: double.infinity,
                                child: FilledButton.icon(
                                  onPressed: _isLoading ? null : _login,
                                  icon: _isLoading
                                      ? const SizedBox(
                                          width: 18,
                                          height: 18,
                                          child: CircularProgressIndicator(
                                            strokeWidth: 2,
                                            valueColor:
                                                AlwaysStoppedAnimation<Color>(
                                                  Colors.white,
                                                ),
                                          ),
                                        )
                                      : const Icon(Icons.login),
                                  label: Text(
                                    _isLoading ? 'Memproses...' : 'Masuk',
                                  ),
                                  style: FilledButton.styleFrom(
                                    padding: const EdgeInsets.symmetric(
                                      vertical: 14,
                                    ),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(14),
                                    ),
                                  ),
                                ),
                              ),
                              const SizedBox(height: 12),
                              if (_user != null)
                                Padding(
                                  padding: const EdgeInsets.only(top: 12),
                                  child: _UserCard(
                                    user: _user!,
                                    token: _token,
                                    onLogout: _logout,
                                  ),
                                ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 10),
                      Center(
                        child: Text(
                          'RT/RW Mobile • v1',
                          style: TextStyle(color: Colors.grey.shade500),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _BrandHeader extends StatelessWidget {
  const _BrandHeader({required this.appName});

  final String appName;

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Container(
          width: 46,
          height: 46,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(14),
            gradient: LinearGradient(
              colors: [
                Theme.of(context).colorScheme.primary,
                Colors.green.shade600,
              ],
            ),
          ),
          child: const Icon(Icons.home_work_outlined, color: Colors.white),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                appName,
                style: Theme.of(
                  context,
                ).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.w900),
              ),
              Text(
                'Login warga (warga)',
                style: TextStyle(color: Colors.grey.shade700, height: 1.2),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _Toast extends StatelessWidget {
  const _Toast({
    required this.color,
    required this.borderColor,
    required this.textColor,
    required this.message,
  });

  final Color color;
  final Color borderColor;
  final Color textColor;
  final String message;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: borderColor),
      ),
      child: Text(
        message,
        style: TextStyle(color: textColor, fontWeight: FontWeight.w600),
      ),
    );
  }
}

class _LabeledField extends StatelessWidget {
  const _LabeledField({
    required this.label,
    required this.hint,
    required this.controller,
    this.keyboardType,
    this.obscureText = false,
  });

  final String label;
  final String hint;
  final TextEditingController controller;
  final TextInputType? keyboardType;
  final bool obscureText;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 2, bottom: 8),
          child: Text(
            label,
            style: TextStyle(
              fontWeight: FontWeight.w700,
              color: Colors.grey.shade800,
            ),
          ),
        ),
        TextField(
          controller: controller,
          obscureText: obscureText,
          keyboardType: keyboardType,
          decoration: InputDecoration(
            hintText: hint,
            filled: true,
            fillColor: Colors.white,
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: BorderSide(color: Colors.grey.shade300),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: BorderSide(color: Colors.grey.shade300),
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
    );
  }
}

class _UserCard extends StatelessWidget {
  const _UserCard({
    required this.user,
    required this.token,
    required this.onLogout,
  });

  final Map<String, dynamic> user;
  final String? token;
  final VoidCallback onLogout;

  @override
  Widget build(BuildContext context) {
    final role = user['role']?.toString() ?? '-';
    final name = user['name']?.toString() ?? '-';
    final email = user['email']?.toString() ?? '-';

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Berhasil masuk',
          style: Theme.of(
            context,
          ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w900),
        ),
        const SizedBox(height: 10),
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(14),
            border: Border.all(color: Colors.grey.shade200),
            color: Colors.grey.shade50,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Nama: $name'),
              Text('Email: $email'),
              Text('Role: $role'),
              const SizedBox(height: 8),
              if (token != null && token!.isNotEmpty)
                const Text('Token tersimpan di perangkat.'),
            ],
          ),
        ),
        const SizedBox(height: 12),
        SizedBox(
          width: double.infinity,
          child: OutlinedButton.icon(
            onPressed: onLogout,
            icon: const Icon(Icons.logout),
            label: const Text('Logout'),
            style: OutlinedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 12),
              side: BorderSide(
                color: Theme.of(context).colorScheme.primary.withOpacity(0.6),
              ),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(14),
              ),
            ),
          ),
        ),
      ],
    );
  }
}
