@extends('layout.app')
@section('title', 'Detail Penerimaan Magang')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Detail Penerimaan Magang</h4>
                        <small class="text-muted">Data penerimaan otomatis dibuat dari pengajuan yang diterima</small>
                        <div>
                            <a href="{{ route('admin.penerimaan.edit', $penerimaan->id) }}" class="btn btn-warning">
                                <i class="mdi mdi-plus"></i> Tambah Dokumen
                            </a>
                            <a href="{{ route('admin.penerimaan.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="row mb-4">
                        <div class="col-12">
                            @if($penerimaan->status == 'pending')
                                <span class="badge bg-warning fs-6">Status: Pending</span>
                            @elseif($penerimaan->status == 'approved')
                                <span class="badge bg-success fs-6">Status: Disetujui</span>
                            @elseif($penerimaan->status == 'rejected')
                                <span class="badge bg-danger fs-6">Status: Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Pengajuan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Informasi Pengajuan</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pemohon:</label>
                            <p>{{ $penerimaan->pengajuan ? $penerimaan->pengajuan->nama_pemohon : 'Data tidak tersedia' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. HP:</label>
                            <p>{{ $penerimaan->pengajuan ? $penerimaan->pengajuan->no_hp : 'Data tidak tersedia' }}</p>
                        </div>
                    </div>

                    <!-- Peserta Magang -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Peserta Magang</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Peserta</th>
                                            <th>No. Telepon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penerimaan->peserta_magang as $index => $peserta)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $peserta['nama'] }}</td>
                                            <td>{{ $peserta['telepon'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Akademik -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Informasi Akademik</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Instansi/Sekolah/Universitas:</label>
                            <p>{{ $penerimaan->instansi_sekolah_universitas }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jurusan:</label>
                            <p>{{ $penerimaan->jurusan }}</p>
                        </div>
                    </div>

                    <!-- Lokasi dan Periode -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Lokasi dan Periode Magang</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lokasi Magang:</label>
                            <p>{{ $penerimaan->lokasi ? $penerimaan->lokasi->nama_lokasi : 'Data tidak tersedia' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Periode Magang:</label>
                            <p>{{ \Carbon\Carbon::parse($penerimaan->mulai_magang)->format('d/m/Y') }} - 
                               {{ \Carbon\Carbon::parse($penerimaan->selesai_magang)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Dokumen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Dokumen</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Surat Pengantar/Izin Magang:</label>
                            @if($penerimaan->surat_pengantar_izin)
                                <p><a href="{{ Storage::url($penerimaan->surat_pengantar_izin) }}" 
                                      target="_blank" class="btn btn-sm btn-info">
                                    <i class="mdi mdi-download"></i> Download File
                                </a></p>
                            @else
                                <p class="text-muted">File tidak tersedia</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Proposal Magang:</label>
                            @if($penerimaan->proposal_magang)
                                <p><a href="{{ Storage::url($penerimaan->proposal_magang) }}" 
                                      target="_blank" class="btn btn-sm btn-info">
                                    <i class="mdi mdi-download"></i> Download File
                                </a></p>
                            @else
                                <p class="text-muted">File tidak tersedia</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">KTP Peserta Magang:</label>
                            @if($penerimaan->ktp_peserta)
                                <p><a href="{{ Storage::url($penerimaan->ktp_peserta) }}" 
                                      target="_blank" class="btn btn-sm btn-info">
                                    <i class="mdi mdi-download"></i> Download File
                                </a></p>
                            @else
                                <p class="text-muted">File tidak tersedia</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Surat Penerimaan:</label>
                            @if($penerimaan->surat_penerimaan)
                                <p><a href="{{ Storage::url($penerimaan->surat_penerimaan) }}" 
                                      target="_blank" class="btn btn-sm btn-info">
                                    <i class="mdi mdi-download"></i> Download File
                                </a></p>
                            @else
                                <p class="text-muted">File tidak tersedia</p>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if($penerimaan->catatan)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Catatan</h5>
                            <p>{{ $penerimaan->catatan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Informasi Sistem -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Informasi Sistem</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dibuat pada:</label>
                            <p>{{ \Carbon\Carbon::parse($penerimaan->created_at)->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Terakhir diupdate:</label>
                            <p>{{ \Carbon\Carbon::parse($penerimaan->updated_at)->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
