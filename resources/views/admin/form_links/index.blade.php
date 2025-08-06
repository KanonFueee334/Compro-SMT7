@extends('layout.app')
@section('title', 'Kelola Form Links')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Form Links</h2>
        <a href="{{ route('admin.form_links.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Buat Form Link Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tipe Form</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Kadaluarsa</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($formLinks as $link)
                            <tr>
                                <td>{{ $link->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $link->form_type === 'magang' ? 'primary' : 'success' }}">
                                        {{ ucfirst($link->form_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" value="{{ $link->full_url }}" readonly>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('{{ $link->full_url }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    @if($link->isActive())
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($link->expires_at)
                                        {{ $link->expires_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>{{ $link->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ $link->full_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.form_links.toggle-status', $link->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $link->is_active ? 'warning' : 'success' }}">
                                                <i class="bi bi-{{ $link->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.form_links.destroy', $link->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus form link ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada form link yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 1000);
    });
}
</script>
@endsection 