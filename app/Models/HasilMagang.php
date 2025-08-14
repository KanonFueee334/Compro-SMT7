<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilMagang extends Model
{
    use HasFactory;

    protected $table = 'hasil_magang';

    protected $fillable = [
        'penerimaan_id',
        'laporan_hasil_magang',
        'surat_keterangan_selesai',
        'status',
        'catatan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(\App\Models\Penerimaan::class, 'penerimaan_id');
    }
}
