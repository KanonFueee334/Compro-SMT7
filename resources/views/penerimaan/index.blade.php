@extends('layout.app')
@section('title', 'Daftar Penerimaan Magang')
@section('content')
<div class="container">
    <h2>Daftar Penerimaan Magang</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemohon</th>
                <th>No HP</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>File Surat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nama_pemohon }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->catatan }}</td>
                <td>
                    @if($p->file_surat)
                        <a href="{{ asset('storage/'.$p->file_surat) }}" target="_blank">Lihat Surat</a>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.penerimaan.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <!-- Tombol aksi status -->
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $p->id }}">Ubah Status</button>
                    @if($p->status === 'ditolak')
                    <form action="{{ route('admin.penerimaan.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus pengajuan ini?')">Hapus</button>
                    </form>
                    @endif
                    <!-- Modal ubah status -->
                    <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.penerimaan.ubah-status', $p->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalStatusLabel{{ $p->id }}">Ubah Status Pengajuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required onchange="toggleSuratCatatan(this, {{ $p->id }})">
                                                <option value="diterima">Diterima</option>
                                                <option value="direvisi">Direvisi</option>
                                                <option value="ditolak">Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-2" id="catatanField{{ $p->id }}" style="display:none;">
                                            <label>Catatan</label>
                                            <textarea name="catatan" class="form-control"></textarea>
                                        </div>
                                        <div class="mb-2" id="suratField{{ $p->id }}" style="display:none;">
                                            <label>Upload Surat Keterangan</label>
                                            <input type="file" name="file_surat" class="form-control">
                                        </div>
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
                function toggleSuratCatatan(select, id) {
                    let catatan = document.getElementById('catatanField'+id);
                    let surat = document.getElementById('suratField'+id);
                    if(select.value === 'diterima') {
                        catatan.style.display = 'none';
                        surat.style.display = 'block';
                    } else {
                        catatan.style.display = 'block';
                        surat.style.display = 'none';
                    }
                }
            </script>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 