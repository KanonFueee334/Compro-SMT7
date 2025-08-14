<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    
    protected $fillable = [
        'pengajuan_id',
        'peserta_magang',
        'instansi_sekolah_universitas',
        'jurusan',
        'lokasi_id',
        'mulai_magang',
        'selesai_magang',
        'surat_pengantar_izin',
        'proposal_magang',
        'ktp_peserta',
        'surat_penerimaan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'peserta_magang' => 'array',
        'mulai_magang' => 'date',
        'selesai_magang' => 'date',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function hasilMagang()
    {
        return $this->hasOne(HasilMagang::class, 'penerimaan_id');
    }
}
