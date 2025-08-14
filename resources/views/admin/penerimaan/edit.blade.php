@extends('layout.app')
@section('title', 'Edit Penerimaan Magang')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Dokumen Penerimaan Magang</h4>
                    <small class="text-muted">Data dasar sudah terisi dari pengajuan magang yang diterima</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penerimaan.update', $penerimaan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Info Display (Read-only) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Informasi Dasar (Dari Pengajuan)</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Pemohon:</label>
                                <p class="form-control-plaintext">{{ $penerimaan->pengajuan ? $penerimaan->pengajuan->nama_pemohon : 'Data tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Instansi:</label>
                                <p class="form-control-plaintext">{{ $penerimaan->instansi_sekolah_universitas }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jurusan:</label>
                                <p class="form-control-plaintext">{{ $penerimaan->jurusan }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lokasi Magang:</label>
                                <p class="form-control-plaintext">{{ $penerimaan->lokasi ? $penerimaan->lokasi->nama_lokasi : 'Data tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Periode:</label>
                                <p class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($penerimaan->mulai_magang)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($penerimaan->selesai_magang)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Document Uploads -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Upload Dokumen Tambahan</h5>
                                <p class="text-muted">Upload dokumen yang diperlukan untuk melengkapi proses penerimaan</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="surat_pengantar_izin" class="form-label">
                                    Surat Pengantar / Izin Magang dari Instansi
                                </label>
                                @if($penerimaan->surat_pengantar_izin)
                                    <div class="mb-2">
                                        <small class="text-success">✓ File sudah diupload</small>
                                        <a href="{{ Storage::url($penerimaan->surat_pengantar_izin) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                            <i class="mdi mdi-eye"></i> Lihat
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('surat_pengantar_izin') is-invalid @enderror" 
                                       id="surat_pengantar_izin" name="surat_pengantar_izin" 
                                       accept=".pdf" max="2048">
                                <small class="form-text text-muted">PDF, maksimal 2MB. Kosongkan jika tidak ingin mengubah file.</small>
                                @error('surat_pengantar_izin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="proposal_magang" class="form-label">Proposal Magang</label>
                                @if($penerimaan->proposal_magang)
                                    <div class="mb-2">
                                        <small class="text-success">✓ File sudah diupload</small>
                                        <a href="{{ Storage::url($penerimaan->proposal_magang) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                            <i class="mdi mdi-eye"></i> Lihat
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('proposal_magang') is-invalid @enderror" 
                                       id="proposal_magang" name="proposal_magang" 
                                       accept=".pdf" max="5120">
                                <small class="form-text text-muted">PDF, maksimal 5MB. Kosongkan jika tidak ingin mengubah file.</small>
                                @error('proposal_magang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ktp_peserta" class="form-label">KTP Peserta Magang</label>
                                @if($penerimaan->ktp_peserta)
                                    <div class="mb-2">
                                        <small class="text-success">✓ File sudah diupload</small>
                                        <a href="{{ Storage::url($penerimaan->ktp_peserta) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                            <i class="mdi mdi-eye"></i> Lihat
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('ktp_peserta') is-invalid @enderror" 
                                       id="ktp_peserta" name="ktp_peserta" 
                                       accept=".pdf" max="5120">
                                <small class="form-text text-muted">PDF, maksimal 5MB. Kosongkan jika tidak ingin mengubah file.</small>
                                @error('ktp_peserta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="surat_penerimaan" class="form-label">
                                    <strong>Surat Penerimaan (Surat Diterima)</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if($penerimaan->surat_penerimaan)
                                    <div class="mb-2">
                                        <small class="text-success">✓ File sudah diupload</small>
                                        <a href="{{ Storage::url($penerimaan->surat_penerimaan) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                                            <i class="mdi mdi-eye"></i> Lihat
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('surat_penerimaan') is-invalid @enderror" 
                                       id="surat_penerimaan" name="surat_penerimaan" 
                                       accept=".pdf" max="2048" required>
                                <small class="form-text text-muted">PDF, maksimal 2MB. <strong>Wajib diupload untuk melengkapi penerimaan.</strong></small>
                                @error('surat_penerimaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="catatan" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                          id="catatan" name="catatan" rows="3" 
                                          placeholder="Tambahkan catatan atau instruksi khusus untuk peserta magang">{{ old('catatan', $penerimaan->catatan) }}</textarea>
                                <small class="form-text text-muted">Catatan opsional untuk melengkapi informasi penerimaan</small>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Simpan Dokumen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
