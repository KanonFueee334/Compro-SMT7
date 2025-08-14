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
        // Redirect to index since we don't need store method
        return redirect()->route('admin.penerimaan.index');
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
        
        $penerimaan->delete();

        return redirect()->route('admin.penerimaan.index')->with('success', 'Data penerimaan berhasil dihapus.');
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
