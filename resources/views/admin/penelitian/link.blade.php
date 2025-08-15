@extends('layout.app')
@section('title', 'Kelola Form Link Penelitian')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kelola Form Link Penelitian</h3>
                <p class="text-subtitle text-muted">Kelola form link untuk pengajuan penelitian</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Form Link Penelitian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Form Link Penelitian</h4>
                <a href="{{ route('admin.penelitian.create-link') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Buat Form Link Baru
                </a>
            </div>
            <div class="card-body">
                @if($formLinks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                    <th>Kadaluarsa</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formLinks as $formLink)
                                    <tr>
                                        <td>{{ $formLink->title }}</td>
                                        <td>{{ $formLink->description ?: '-' }}</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" value="{{ $formLink->full_url }}" readonly>
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyLink('{{ $formLink->full_url }}')">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            @if($formLink->isActive())
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($formLink->expires_at)
                                                {{ $formLink->expires_at->format('d/m/Y H:i') }}
                                                @if($formLink->isExpired())
                                                    <br><small class="text-danger">(Kadaluarsa)</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>{{ $formLink->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.penelitian.toggle-status-link', $formLink->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $formLink->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $formLink->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="bi {{ $formLink->is_active ? 'bi-pause' : 'bi-play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.penelitian.destroy-link', $formLink->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus form link ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-link-45deg display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Belum ada form link penelitian</h5>
                        <p class="text-muted">Buat form link baru untuk memulai</p>
                        <a href="{{ route('admin.penelitian.create-link') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Buat Form Link Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<script>
function copyLink(link) {
    navigator.clipboard.writeText(link).then(function() {
        // Show success message
        const button = event.target;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endsection 