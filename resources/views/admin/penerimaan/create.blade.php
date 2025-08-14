@extends('layout.app')
@section('title', 'Tambah Penerimaan Magang')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Penerimaan Magang</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penerimaan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Pengajuan Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pengajuan_id" class="form-label">Pilih Pengajuan Magang</label>
                                @if($pengajuan)
                                    <input type="text" class="form-control" 
                                           value="{{ $pengajuan->nama_pemohon }} - {{ $pengajuan->no_hp }}" readonly>
                                    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                                @else
                                    <select class="form-select @error('pengajuan_id') is-invalid @enderror" 
                                            id="pengajuan_id" name="pengajuan_id" required>
                                        <option value="">Pilih Pengajuan</option>
                                        @foreach(\App\Models\PengajuanMagang::where('status', 'diterima')->get() as $p)
                                            <option value="{{ $p->id }}" 
                                                    {{ old('pengajuan_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_pemohon }} - {{ $p->no_hp }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('pengajuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Peserta Magang -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">Peserta Magang</label>
                                <div id="peserta-container">
                                    <div class="row mb-2 peserta-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" 
                                                   name="peserta_magang[0][nama]" 
                                                   placeholder="Nama Peserta" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" 
                                                   name="peserta_magang[0][telepon]" 
                                                   placeholder="No. Telepon" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-peserta" 
                                                    onclick="removePeserta(this)">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" onclick="addPeserta()">
                                    <i class="mdi mdi-plus"></i> Tambah Peserta
                                </button>
                            </div>
                        </div>

                        <!-- Instansi dan Jurusan -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="instansi_sekolah_universitas" class="form-label">Instansi/Sekolah/Universitas</label>
                                <input type="text" class="form-control @error('instansi_sekolah_universitas') is-invalid @enderror" 
                                       id="instansi_sekolah_universitas" name="instansi_sekolah_universitas" 
                                       value="{{ old('instansi_sekolah_universitas') }}" required>
                                @error('instansi_sekolah_universitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" class="form-control @error('jurusan') is-invalid @enderror" 
                                       id="jurusan" name="jurusan" value="{{ old('jurusan') }}" required>
                                @error('jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Lokasi Magang -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lokasi_id" class="form-label">Magang pada Tim (sesuai master lokasi)</label>
                                <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                                        id="lokasi_id" name="lokasi_id" required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($lokasi as $lok)
                                        <option value="{{ $lok->id }}" 
                                                {{ old('lokasi_id') == $lok->id ? 'selected' : '' }}>
                                            {{ $lok->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lokasi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Periode Magang -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mulai_magang" class="form-label">Mulai Magang</label>
                                <input type="date" class="form-control @error('mulai_magang') is-invalid @enderror" 
                                       id="mulai_magang" name="mulai_magang" value="{{ old('mulai_magang') }}" required>
                                @error('mulai_magang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="selesai_magang" class="form-label">Selesai Magang</label>
                                <input type="date" class="form-control @error('selesai_magang') is-invalid @enderror" 
                                       id="selesai_magang" name="selesai_magang" value="{{ old('selesai_magang') }}" required>
                                @error('selesai_magang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- File Uploads -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="surat_pengantar_izin" class="form-label">
                                    Surat Pengantar / Izin Magang dari Instansi
                                </label>
                                <input type="file" class="form-control @error('surat_pengantar_izin') is-invalid @enderror" 
                                       id="surat_pengantar_izin" name="surat_pengantar_izin" 
                                       accept=".pdf" max="2048">
                                <small class="form-text text-muted">PDF, maksimal 2MB</small>
                                @error('surat_pengantar_izin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="proposal_magang" class="form-label">Proposal Magang</label>
                                <input type="file" class="form-control @error('proposal_magang') is-invalid @enderror" 
                                       id="proposal_magang" name="proposal_magang" 
                                       accept=".pdf" max="5120">
                                <small class="form-text text-muted">PDF, maksimal 5MB</small>
                                @error('proposal_magang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ktp_peserta" class="form-label">KTP Peserta Magang</label>
                                <input type="file" class="form-control @error('ktp_peserta') is-invalid @enderror" 
                                       id="ktp_peserta" name="ktp_peserta" 
                                       accept=".pdf" max="5120">
                                <small class="form-text text-muted">PDF, maksimal 5MB</small>
                                @error('ktp_peserta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="surat_penerimaan" class="form-label">Surat Penerimaan</label>
                                <input type="file" class="form-control @error('surat_penerimaan') is-invalid @enderror" 
                                       id="surat_penerimaan" name="surat_penerimaan" 
                                       accept=".pdf" max="2048">
                                <small class="form-text text-muted">PDF, maksimal 2MB</small>
                                @error('surat_penerimaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let pesertaCount = 1;

function addPeserta() {
    const container = document.getElementById('peserta-container');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 peserta-row';
    newRow.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control" 
                   name="peserta_magang[${pesertaCount}][nama]" 
                   placeholder="Nama Peserta" required>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" 
                   name="peserta_magang[${pesertaCount}][telepon]" 
                   placeholder="No. Telepon" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-peserta" 
                    onclick="removePeserta(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
    pesertaCount++;
}

function removePeserta(button) {
    if (document.querySelectorAll('.peserta-row').length > 1) {
        button.closest('.peserta-row').remove();
    }
}

// Auto-fill form when pengajuan is selected
document.addEventListener('DOMContentLoaded', function() {
    const pengajuanSelect = document.getElementById('pengajuan_id');
    if (pengajuanSelect) {
        pengajuanSelect.addEventListener('change', function() {
            const pengajuanId = this.value;
            if (pengajuanId) {
                // You can add AJAX call here to auto-fill some fields if needed
                console.log('Selected pengajuan ID:', pengajuanId);
            }
        });
    }
    
    // If pengajuan is pre-selected, auto-fill some fields
    @if($pengajuan)
        // Auto-fill some fields based on selected pengajuan
        document.getElementById('instansi_sekolah_universitas').value = '{{ $pengajuan->asal_instansi ?? "" }}';
        document.getElementById('jurusan').value = '{{ $pengajuan->jurusan ?? "" }}';
        if (document.getElementById('lokasi_id')) {
            document.getElementById('lokasi_id').value = '{{ $pengajuan->lokasi_id ?? "" }}';
        }
    @endif
});
</script>
@endsection
