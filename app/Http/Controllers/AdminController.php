<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function home() { return view('admin.home'); }
    public function user() { 
        $users = User::select('id','name','status','role')
            ->orderBy('status','desc')
            ->orderBy('name','asc')
            ->get();
        return view('admin.user', ['users' => $users]); 
    }
    public function pengajuanLink() { return redirect()->route('admin.form_links.index'); }
    public function pengajuanDaftar() { return view('admin.pengajuan_daftar'); }
    public function penerimaanLink() { return view('admin.penerimaan_link'); }
    public function penerimaanDaftar() { return view('admin.penerimaan_daftar'); }
    public function pelaksanaan() { return view('admin.pelaksanaan'); }
    public function hasil() { return view('admin.hasil'); }
    public function penelitianPengajuan() { return view('admin.penelitian_pengajuan'); }
    public function penelitianPenjadwalan() { return view('admin.penelitian_penjadwalan'); }
} 