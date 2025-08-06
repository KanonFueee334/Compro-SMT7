<?php

namespace App\Http\Controllers;

use App\Models\FormLink;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormLinkController extends Controller
{
    public function index()
    {
        $formLinks = FormLink::orderBy('created_at', 'desc')->get();
        return view('admin.form_links.index', compact('formLinks'));
    }

    public function create()
    {
        return view('admin.form_links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_type' => 'required|in:magang,penelitian',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        FormLink::create([
            'form_type' => $request->form_type,
            'title' => $request->title,
            'description' => $request->description,
            'expires_at' => $request->expires_at,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.form_links.index')->with('success', 'Form link berhasil dibuat!');
    }

    public function show($token)
    {
        $formLink = FormLink::where('token', $token)->firstOrFail();
        
        if (!$formLink->isActive()) {
            abort(404, 'Form link tidak aktif atau sudah kadaluarsa.');
        }

        if ($formLink->form_type === 'magang') {
            $lokasi = Lokasi::all();
            return view('forms.magang', compact('formLink', 'lokasi'));
        } elseif ($formLink->form_type === 'penelitian') {
            return view('forms.penelitian', compact('formLink'));
        }

        abort(404, 'Tipe form tidak valid.');
    }

    public function destroy($id)
    {
        $formLink = FormLink::findOrFail($id);
        $formLink->delete();
        
        return redirect()->route('admin.form_links.index')->with('success', 'Form link berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $formLink = FormLink::findOrFail($id);
        $formLink->is_active = !$formLink->is_active;
        $formLink->save();
        
        return redirect()->route('admin.form_links.index')->with('success', 'Status form link berhasil diubah!');
    }
}
