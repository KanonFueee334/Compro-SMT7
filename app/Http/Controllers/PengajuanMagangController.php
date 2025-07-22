<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanMagang::whereIn('status', ['diproses', 'diterima'])->orderBy('created_at', 'desc')->get();
        return view('penerimaan.index', compact('pengajuan'));
    }

    public function edit($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        return view('penerimaan.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $data = $request->all();
        // Handle file upload
        if ($request->hasFile('file_surat')) {
            $data['file_surat'] = $request->file('file_surat')->store('surat_penerimaan', 'public');
        }
        $pengajuan->update($data);
        return redirect()->route('admin.penerimaan.daftar')->with('success', 'Data pengajuan berhasil diupdate.');
    }

    public function ubahStatus(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $status = $request->input('status');
        $catatan = $request->input('catatan');
        $pengajuan->status = $status;
        $pengajuan->catatan = $catatan;
        // Jika diterima, buat akun user magang dan upload surat
        if ($status === 'diterima') {
            // Cek jika user sudah ada
            $user = User::where('username', $pengajuan->no_hp)->first();
            if (!$user) {
                $user = User::create([
                    'username' => $pengajuan->no_hp,
                    'password' => Hash::make('magang123'),
                    'name' => $pengajuan->nama_pemohon,
                    'role' => 'magang',
                    'status' => 1,
                ]);
            }
            if ($request->hasFile('file_surat')) {
                $pengajuan->file_surat = $request->file('file_surat')->store('surat_penerimaan', 'public');
            }
        }
        $pengajuan->save();
        return redirect()->route('admin.penerimaan.daftar')->with('success', 'Status pengajuan berhasil diubah.');
    }

    public function create()
    {
        // Ambil data lokasi untuk dropdown
        $lokasi = \App\Models\Lokasi::all();
        return view('pengajuan.create', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'nama_anggota' => 'required|string',
            'asal_instansi' => 'required|string',
            'jurusan' => 'required|string',
            'keahlian' => 'required|string',
            'lokasi_id' => 'required|exists:lokasi,id',
            'mulai_magang' => 'required|date',
            'selesai_magang' => 'required|date|after_or_equal:mulai_magang',
        ]);
        $data = $request->all();
        $data['status'] = 'pengajuan';
        PengajuanMagang::create($data);
        return redirect()->back()->with('success', 'Pengajuan magang berhasil dikirim!');
    }

    public function daftarPengajuan(Request $request)
    {
        $status = $request->get('status', 'pengajuan');
        $query = PengajuanMagang::query();
        // Hanya tampilkan pengajuan, diproses, ditolak
        if ($status !== 'all') {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pengajuan', 'diproses', 'ditolak']);
        }
        $pengajuan = $query->orderBy('created_at', 'desc')->get();

        // Hitung keterisian kuota lokasi berdasarkan anggota, hanya status 'diterima'
        $lokasi = \App\Models\Lokasi::all();
        $kuota = [];
        foreach ($lokasi as $l) {
            $terisi = PengajuanMagang::where('lokasi_id', $l->id)
                ->where('status', 'diterima')
                ->get()
                ->sum(function($p) {
                    return 1 + count(array_filter(array_map('trim', explode(';', $p->nama_anggota))));
                });
            $kuota[] = [
                'id' => $l->id,
                'bidang' => $l->bidang,
                'tim' => $l->tim,
                'quota' => $l->quota,
                'terisi' => $terisi,
            ];
        }
        return view('admin.pengajuan_daftar', compact('pengajuan', 'kuota', 'status'));
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        if ($pengajuan->status !== 'ditolak') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang ditolak yang bisa dihapus.');
        }
        $pengajuan->delete();
        return redirect()->route('admin.penerimaan.daftar')->with('success', 'Pengajuan magang berhasil dihapus.');
    }
}
