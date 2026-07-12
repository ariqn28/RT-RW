<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanStatusHistory;
use App\Models\User;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat',
        'nama',
        'nik',
        'alamat',
        'alasan',
        'status',
        'file_path',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(PengajuanStatusHistory::class)->orderByDesc('created_at');
    }
}
