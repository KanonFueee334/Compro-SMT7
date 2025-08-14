@extends('layout.app')
@section('title', 'Daftar Penerimaan Magang')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Daftar Penerimaan Magang</h4>
                        <small class="text-muted">Data penerimaan dibuat otomatis saat pengajuan diterima</small>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Pemohon</th>
                                    <th>Instansi</th>
                                    <th>Jurusan</th>
                                    <th>Lokasi Magang</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penerimaan as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->pengajuan)
                                            {{ $item->pengajuan->nama_pemohon }}
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->instansi_sekolah_universitas }}</td>
                                    <td>{{ $item->jurusan }}</td>
                                    <td>
                                        @if($item->lokasi)
                                            {{ $item->lokasi->nama_lokasi }}
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->mulai_magang)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($item->selesai_magang)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($item->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.penerimaan.show', $item->id) }}" 
                                               class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                                <i class="mdi mdi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('admin.penerimaan.edit', $item->id) }}" 
                                               class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Dokumen">
                                                <i class="mdi mdi-plus"></i> Dokumen
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="updateStatus({{ $item->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Status">
                                                <i class="mdi mdi-check-circle"></i> Status
                                            </button>
                                            <form action="{{ route('admin.penerimaan.destroy', $item->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                    <i class="mdi mdi-delete"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data penerimaan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Penerimaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                  placeholder="Masukkan catatan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(id) {
    const form = document.getElementById('statusForm');
    form.action = `/admin/penerimaan/${id}/update-status`;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
