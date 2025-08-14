@extends('layout.app')
@section('title', 'Daftar Pengajuan Magang')
@section('content')
<style>
    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
<div class="container">
    <h2>Daftar Pengajuan Magang</h2>
    <p class="text-muted mb-4">Kelola status pengajuan magang - aplikasi akan tetap di halaman ini sampai status 'Diterima'</p>
    
    <!-- DEBUG INFO -->
    @if(isset($debugInfo))
        <div class="alert alert-info">
            <h5>Debug Info:</h5>
            <ul>
                <li><strong>Total Applications in Database:</strong> {{ $debugInfo['total_applications'] }}</li>
                <li><strong>Current Filter:</strong> {{ $debugInfo['filter_status'] }}</li>
                <li><strong>Query Results:</strong> {{ $debugInfo['query_results_count'] }}</li>
                <li><strong>All Statuses:</strong> {{ implode(', ', $debugInfo['all_statuses']) }}</li>
            </ul>
        </div>
    @endif
    {{-- Card Kuota Lokasi --}}
    <div class="row mb-4">
        @foreach($kuota as $k)
        <div class="col-md-3 mb-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $k['bidang'] }}<br><small>{{ $k['tim'] }}</small></h6>
                    <span class="badge bg-primary">{{ $k['terisi'] }} / {{ $k['quota'] }} terisi</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
        <div class="d-flex justify-content-between align-items-center mb-3" >
    {{-- Dropdown Filter Status --}}
    <form method="GET" action="{{ route('admin.pengajuan.daftar') }}" id="filterForm">
        <div class="d-flex align-items-center">
            <select name="status" id="status" class="form-select me-2" onchange="this.form.submit()">
                <option value="pengajuan" {{ $status=='pengajuan'?'selected':'' }}>Pengajuan</option>
                <option value="diproses" {{ $status=='diproses'?'selected':'' }}>Diproses</option>
                <option value="ditolak" {{ $status=='ditolak'?'selected':'' }}>Ditolak</option>
                <option value="all" {{ $status=='all'?'selected':'' }}>Semua</option>
            </select>
        </div>
    </form>

    {{-- Tombol Urutkan --}}
    @php
        $currentSort = request('sort', 'desc');
        $nextSort = $currentSort === 'desc' ? 'asc' : 'desc';
        $sortLabel = $currentSort === 'desc' ? 'Urutkan: Terbaru' : 'Urutkan: Lama';
    @endphp
    <a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort]) }}" class="btn btn-outline-primary ms-2">
        {{ $sortLabel }}
    </a>
</div>
    {{-- Tabel Pengajuan --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemohon</th>
                <th>No HP</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nama_pemohon }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ optional($p->lokasi)->bidang ?? '-' }}<br><small>{{ optional($p->lokasi)->tim ?? '' }}</small></td>
                <td>
                    @if($p->status == 'pengajuan')
                        <span class="badge bg-warning">Pengajuan</span>
                    @elseif($p->status == 'diproses')
                        <span class="badge bg-info">Diproses</span>
                    @elseif($p->status == 'diterima')
                        <span class="badge bg-success">Diterima</span>
                    @elseif($p->status == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                    @endif
                </td>
                <td>
                    {{ $p->created_at }}
                    @if($p->status == 'ditolak' && $p->catatan)
                        <br><small class="text-danger">Alasan: {{ $p->catatan }}</small>
                    @endif
                </td>
                <td>
                    @if($p->status == 'diterima')
                        <div class="d-flex gap-1">
                            <span class="text-success">✓ Diterima</span>
                            @php
                                $existingPenerimaan = \App\Models\Penerimaan::where('pengajuan_id', $p->id)->first();
                            @endphp
                            @if($existingPenerimaan)
                                <a href="{{ route('admin.penerimaan.show', $existingPenerimaan->id) }}" 
                                   class="btn btn-info btn-sm" title="Lihat Penerimaan">
                                    <i class="mdi mdi-eye"></i> Lihat Penerimaan
                                </a>
                            @else
                                <span class="text-muted">Penerimaan sedang diproses...</span>
                            @endif
                        </div>
                    @else
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $p->id }}">
                            @if($p->status == 'pengajuan')
                                Proses Pengajuan
                            @elseif($p->status == 'diproses')
                                Ubah Status
                            @elseif($p->status == 'ditolak')
                                Review Ulang
                            @else
                                Ubah Status
                            @endif
                        </button>
                    @endif
                    <!-- Modal ubah status -->
                    <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.pengajuan.ubah-status', $p->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalStatusLabel{{ $p->id }}">Ubah Status Pengajuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required onchange="toggleAlasan(this, {{ $p->id }})">
                                                <option value="pengajuan" {{ $p->status == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                                <option value="diproses" {{ $p->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="diterima" {{ $p->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                                <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-2" id="alasanField{{ $p->id }}" style="display:none;">
                                            <label>Alasan Penolakan</label>
                                            <textarea name="catatan" class="form-control"></textarea>
                                        </div>
                                        <!-- Surat Penerimaan field removed - will be added later in Penerimaan page -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            <script>
                function toggleAlasan(select, id) {
                    let alasan = document.getElementById('alasanField'+id);
                    
                    // Hide all fields first
                    alasan.style.display = 'none';
                    alasan.querySelector('textarea').required = false;
                    
                    if(select.value === 'ditolak') {
                        alasan.style.display = 'block';
                        alasan.querySelector('textarea').required = true;
                    }
                }
            </script>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 