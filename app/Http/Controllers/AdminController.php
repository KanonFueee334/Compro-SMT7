<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penerimaan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
	public function pelaksanaan() {
		// Ambil semua data penerimaan untuk timeline gantt
		$penerimaan = Penerimaan::with(['pengajuan', 'lokasi', 'hasilMagang'])
			->orderBy('mulai_magang', 'asc')
			->get();

		// Grouping by bidang dan tim (master lokasi)
		$groupedPemagang = $penerimaan->groupBy(function ($item) {
			if ($item->lokasi) {
				return $item->lokasi->bidang . ' - ' . $item->lokasi->tim;
			}
			return 'Lokasi Tidak Diketahui';
		});

		// Statistik
		$totalPemagang = $penerimaan->count();
		$sedangMagang = $penerimaan->filter(function ($item) {
			$now = Carbon::now();
			return $now->between($item->mulai_magang, $item->selesai_magang);
		})->count();
		$selesaiMagang = $penerimaan->filter(function ($item) {
			return Carbon::now()->gt($item->selesai_magang);
		})->count();

		// Rentang tanggal global untuk sumbu waktu gantt
		$minDate = $penerimaan->min('mulai_magang');
		$maxDate = $penerimaan->max('selesai_magang');

		return view('admin.pelaksanaan', compact('groupedPemagang', 'totalPemagang', 'sedangMagang', 'selesaiMagang', 'minDate', 'maxDate'));
	}
	public function hasil() { return view('admin.hasil'); }
	public function penelitianPengajuan() { return view('admin.penelitian_pengajuan'); }
	public function penelitianPenjadwalan() { return view('admin.penelitian_penjadwalan'); }
	
	public function selesaiMagang(Request $request, $id)
	{
		$penerimaan = Penerimaan::findOrFail($id);
		$penerimaan->status = 'selesai';
		$penerimaan->save();

		return redirect()->back()->with('success', 'Status magang berhasil diubah menjadi selesai');
	}

	public function peserta(Request $request)
	{
		$selectedLokasi = $request->get('lokasi', 'all');
		$selectedStatus = $request->get('status', 'all');
		$sort = $request->get('sort', 'desc');

		$query = Penerimaan::with(['pengajuan', 'lokasi']);

		// Filter lokasi
		if ($selectedLokasi !== 'all') {
			$query->where('lokasi_id', $selectedLokasi);
		}

		// Filter status periode magang
		if ($selectedStatus !== 'all') {
			$now = Carbon::now();
			if ($selectedStatus === 'aktif') {
				$query->whereDate('mulai_magang', '<=', $now)
					  ->whereDate('selesai_magang', '>=', $now);
			} elseif ($selectedStatus === 'selesai') {
				$query->whereDate('selesai_magang', '<', $now);
			}
		}

		// Sorting
		$peserta = $query->orderBy('created_at', $sort)->get();

		// Statistik
		$totalPeserta = Penerimaan::count();
		$now = Carbon::now();
		$sedangMagang = Penerimaan::whereDate('mulai_magang', '<=', $now)
									   ->whereDate('selesai_magang', '>=', $now)
									   ->count();
		$selesaiMagang = Penerimaan::whereDate('selesai_magang', '<', $now)->count();
		$lokasiAktif = Penerimaan::distinct('lokasi_id')->count('lokasi_id');

		// Data lokasi untuk filter
		$lokasi = Lokasi::all();

		return view('admin.peserta', compact(
			'peserta',
			'totalPeserta',
			'sedangMagang',
			'selesaiMagang',
			'lokasiAktif',
			'lokasi',
			'selectedLokasi',
			'selectedStatus'
		));
	}
} 