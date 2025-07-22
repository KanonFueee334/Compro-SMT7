<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanPenelitianController extends Controller
{
    public function create()
    {
        return view('pengajuan.create_penelitian');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'judul_penelitian' => 'required|string|max:255',
            'metode' => 'required|in:Kuesioner,Wawancara,Lainnya',
            'surat_izin' => 'required|file|mimes:pdf|max:2048', // 2MB
            'proposal' => 'required|file|mimes:pdf|max:5120', // 5MB
            'daftar_pertanyaan' => 'required|file|mimes:pdf|max:2048', // 2MB
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg|max:1024', // 1MB
        ]);

        $suratIzinPath = $request->file('surat_izin')->store('penelitian/surat_izin', 'public');
        $proposalPath = $request->file('proposal')->store('penelitian/proposal', 'public');
        $daftarPertanyaanPath = $request->file('daftar_pertanyaan')->store('penelitian/daftar_pertanyaan', 'public');
        $ktpPath = $request->file('ktp')->store('penelitian/ktp', 'public');

        PengajuanPenelitian::create([
            'nama' => $request->nama,
            'instansi' => $request->instansi,
            'jurusan' => $request->jurusan,
            'judul_penelitian' => $request->judul_penelitian,
            'metode' => $request->metode,
            'surat_izin' => $suratIzinPath,
            'proposal' => $proposalPath,
            'daftar_pertanyaan' => $daftarPertanyaanPath,
            'ktp' => $ktpPath,
        ]);

        return redirect()->back()->with('success', 'Pengajuan penelitian berhasil disimpan!');
    }
} 