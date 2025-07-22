<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPenelitian extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_penelitian';

    protected $fillable = [
        'nama',
        'instansi',
        'jurusan',
        'judul_penelitian',
        'metode',
        'surat_izin',
        'proposal',
        'daftar_pertanyaan',
        'ktp',
    ];
} 