@extends('layout.app')
@section('title', 'Edit Pengajuan Magang')
@section('content')
<div class="container">
    <h2>Edit Pengajuan Magang</h2>
    <form method="POST" action="{{ route('admin.penerimaan.update', $pengajuan->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Pemohon</label>
            <input type="text" name="nama_pemohon" class="form-control" value="{{ old('nama_pemohon', $pengajuan->nama_pemohon) }}" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pengajuan->no_hp) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $pengajuan->email) }}">
        </div>
        <div class="mb-3">
            <label>Asal Instansi</label>
            <input type="text" name="asal_instansi" class="form-control" value="{{ old('asal_instansi', $pengajuan->asal_instansi) }}">
        </div>
        <div class="mb-3">
            <label>Jurusan</label>
            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $pengajuan->jurusan) }}">
        </div>
        <div class="mb-3">
            <label>Keahlian</label>
            <textarea name="keahlian" class="form-control">{{ old('keahlian', $pengajuan->keahlian) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Nama Anggota</label>
            <textarea name="nama_anggota" class="form-control">{{ old('nama_anggota', $pengajuan->nama_anggota) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Mulai Magang</label>
            <input type="date" name="mulai_magang" class="form-control" value="{{ old('mulai_magang', $pengajuan->mulai_magang) }}">
        </div>
        <div class="mb-3">
            <label>Selesai Magang</label>
            <input type="date" name="selesai_magang" class="form-control" value="{{ old('selesai_magang', $pengajuan->selesai_magang) }}">
        </div>
        <div class="mb-3">
            <label>Upload Surat Keterangan</label>
            <input type="file" name="file_surat" class="form-control">
            @if($pengajuan->file_surat)
                <a href="{{ asset('storage/'.$pengajuan->file_surat) }}" target="_blank">Lihat Surat</a>
            @endif
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.penerimaan.daftar') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection 