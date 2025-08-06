@extends('layout.app')
@section('title', 'Buat Form Link Baru')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Buat Form Link Baru</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.form_links.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="form_type" class="form-label">Tipe Form</label>
                            <select name="form_type" id="form_type" class="form-select" required>
                                <option value="">-- Pilih Tipe Form --</option>
                                <option value="magang" {{ old('form_type') == 'magang' ? 'selected' : '' }}>Form Pengajuan Magang</option>
                                <option value="penelitian" {{ old('form_type') == 'penelitian' ? 'selected' : '' }}>Form Pengajuan Penelitian</option>
                            </select>
                            @error('form_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Form</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Tanggal Kadaluarsa (Opsional)</label>
                            <input type="datetime-local" name="expires_at" id="expires_at" class="form-control" value="{{ old('expires_at') }}">
                            <small class="text-muted">Biarkan kosong jika tidak ingin ada batas waktu</small>
                            @error('expires_at')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.form_links.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Buat Form Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 