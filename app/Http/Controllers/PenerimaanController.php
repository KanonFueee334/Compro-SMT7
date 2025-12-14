<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use App\Models\PengajuanMagang;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenerimaanController extends Controller
{
    public function index()
    {
        $penerimaan = Penerimaan::with(['pengajuan', 'lokasi'])->orderBy('created_at', 'desc')->get();
        return view('admin.penerimaan.index', compact('penerimaan'));
    }

    public function create(Request $request)
    {
        // Redirect to index since we don't need create form
        return redirect()->route('admin.penerimaan.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_magang,id',
            'peserta_nama' => 'required|array|min:1',
            'peserta_nama.*' => ['required','string','max:255'],
            'peserta_telepon' => 'required|array|min:1',
            'peserta_telepon.*' => ['required','string','max:20'],
            'instansi' => 'required|string',
            'jurusan' => 'required|string',
            'lokasi_id' => 'required|exists:lokasi,id',
            'mulai_magang' => 'required|date',
            'selesai_magang' => 'required|date|after_or_equal:mulai_magang',
            'surat_pengantar' => 'required|file|mimes:pdf|max:2048',
            'proposal_magang' => 'required|file|mimes:pdf|max:5120',
            'ktp_peserta' => 'required|file|mimes:pdf|max:5120',
        ]);

        // Build peserta array
        $pesertaMagang = [];
        $names = $request->input('peserta_nama', []);
        $phones = $request->input('peserta_telepon', []);
        for ($i = 0; $i < count($names); $i++) {
            if (!empty($names[$i]) && !empty($phones[$i])) {
                $pesertaMagang[] = [
                    'nama' => $names[$i],
                    'telepon' => $phones[$i],
                ];
            }
        }

        // Store files
        $suratPengantarPath = $request->file('surat_pengantar')->store('penerimaan/surat_pengantar', 'public');
        $proposalMagangPath = $request->file('proposal_magang')->store('penerimaan/proposal_magang', 'public');
        $ktpPesertaPath = $request->file('ktp_peserta')->store('penerimaan/ktp_peserta', 'public');

        // Create Penerimaan
        $penerimaan = Penerimaan::create([
            'pengajuan_id' => $request->pengajuan_id,
            'peserta_magang' => $pesertaMagang,
            'instansi_sekolah_universitas' => $request->instansi,
            'jurusan' => $request->jurusan,
            'lokasi_id' => $request->lokasi_id,
            'mulai_magang' => $request->mulai_magang,
            'selesai_magang' => $request->selesai_magang,
            'surat_pengantar_izin' => $suratPengantarPath,
            'proposal_magang' => $proposalMagangPath,
            'ktp_peserta' => $ktpPesertaPath,
            'status' => 'pending',
        ]);

        // Update pengajuan status
        $pengajuan = PengajuanMagang::find($request->pengajuan_id);
        if ($pengajuan) {
            $pengajuan->status = 'diterima';
            $pengajuan->save();
        }

        return redirect()->route('admin.penerimaan.index')->with('success', 'Data penerimaan berhasil dibuat.');
    }

    public function show($id)
    {
        $penerimaan = Penerimaan::with(['pengajuan', 'lokasi'])->findOrFail($id);
        return view('admin.penerimaan.show', compact('penerimaan'));
    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        $lokasi = Lokasi::all();
        return view('admin.penerimaan.edit', compact('penerimaan', 'lokasi'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        
        $request->validate([
            'surat_pengantar_izin' => 'nullable|file|mimes:pdf|max:2048',
            'proposal_magang' => 'nullable|file|mimes:pdf|max:5120',
            'ktp_peserta' => 'nullable|file|mimes:pdf|max:5120',
            'surat_penerimaan' => 'required|file|mimes:pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $data = [];
        
        // Handle file uploads
        if ($request->hasFile('surat_pengantar_izin')) {
            // Delete old file if exists
            if ($penerimaan->surat_pengantar_izin) {
                Storage::disk('public')->delete($penerimaan->surat_pengantar_izin);
            }
            $data['surat_pengantar_izin'] = $request->file('surat_pengantar_izin')->store('surat_pengantar', 'public');
        }
        
        if ($request->hasFile('proposal_magang')) {
            if ($penerimaan->proposal_magang) {
                Storage::disk('public')->delete($penerimaan->proposal_magang);
            }
            $data['proposal_magang'] = $request->file('proposal_magang')->store('proposal', 'public');
        }
        
        if ($request->hasFile('ktp_peserta')) {
            if ($penerimaan->ktp_peserta) {
                Storage::disk('public')->delete($penerimaan->ktp_peserta);
            }
            $data['ktp_peserta'] = $request->file('ktp_peserta')->store('ktp', 'public');
        }
        
        // Surat penerimaan is required
        if ($request->hasFile('surat_penerimaan')) {
            if ($penerimaan->surat_penerimaan) {
                Storage::disk('public')->delete($penerimaan->surat_penerimaan);
            }
            $data['surat_penerimaan'] = $request->file('surat_penerimaan')->store('surat_penerimaan', 'public');
        }
        
        // Add catatan
        $data['catatan'] = $request->input('catatan');

        $penerimaan->update($data);

        // Auto-approve if all required documents are present
        if ($penerimaan->surat_penerimaan && 
            $penerimaan->surat_pengantar_izin && 
            $penerimaan->proposal_magang && 
            $penerimaan->ktp_peserta) {
            $penerimaan->status = 'approved';
            $penerimaan->save();
        }

        return redirect()->route('admin.penerimaan.index')->with('success', 'Data penerimaan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        
        // Delete associated files
        if ($penerimaan->surat_pengantar_izin) {
            Storage::disk('public')->delete($penerimaan->surat_pengantar_izin);
        }
        if ($penerimaan->proposal_magang) {
            Storage::disk('public')->delete($penerimaan->proposal_magang);
        }
        if ($penerimaan->ktp_peserta) {
            Storage::disk('public')->delete($penerimaan->ktp_peserta);
        }
        if ($penerimaan->surat_penerimaan) {
            Storage::disk('public')->delete($penerimaan->surat_penerimaan);
        }
        
        // Revert status pengajuan agar kuota berkurang di daftar pengajuan
        if ($penerimaan->pengajuan_id) {
            $pengajuan = PengajuanMagang::find($penerimaan->pengajuan_id);
            if ($pengajuan) {
                $pengajuan->status = 'pengajuan';
                $pengajuan->save();
            }
        }

        $penerimaan->delete();

        return redirect()->route('admin.penerimaan.index')->with('success', 'Data penerimaan berhasil dihapus dan kuota diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        $penerimaan->status = $request->input('status');
        $penerimaan->catatan = $request->input('catatan');
        $penerimaan->save();

        return redirect()->route('admin.penerimaan.index')->with('success', 'Status penerimaan berhasil diubah.');
    }
}
