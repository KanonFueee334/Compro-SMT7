<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Penelitian</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <!-- Background biru kiri -->
            <div class="col-lg-3 d-none d-lg-block" style="background-color: #385096;"></div>
            <!-- Form Tengah -->
            <div class="col-lg-6 col-12 d-flex align-items-center justify-content-center">
                <div id="auth-left" class="w-100 px-4 py-5">
                    <div class="auth-logo text-center mb-4">
                        <a href="#"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title text-center">Form Pengajuan Penelitian</h1>
                    <p class="auth-subtitle text-center mb-4">Isi data di bawah dengan lengkap dan benar.</p>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('pengajuan.penelitian.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Instansi</label>
                            <input type="text" name="instansi" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Judul Penelitian</label>
                            <input type="text" name="judul_penelitian" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Metode Penelitian</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metode" id="kuesioner" value="Kuesioner" required>
                                    <label class="form-check-label" for="kuesioner">Kuesioner</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metode" id="wawancara" value="Wawancara">
                                    <label class="form-check-label" for="wawancara">Wawancara</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metode" id="lainnya" value="Lainnya">
                                    <label class="form-check-label" for="lainnya">Lainnya</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Upload Surat Izin dan Instansi (PDF, max 2 MB)</label>
                            <input type="file" name="surat_izin" class="form-control" accept="application/pdf" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Upload Proposal Penelitian (PDF, max 5 MB)</label>
                            <input type="file" name="proposal" class="form-control" accept="application/pdf" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Upload Daftar Pertanyaan Wawancara / Kuesioner (PDF, max 2 MB)</label>
                            <input type="file" name="daftar_pertanyaan" class="form-control" accept="application/pdf" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Upload KTP (JPG/PDF, max 1 MB)</label>
                            <input type="file" name="ktp" class="form-control" accept="application/pdf,image/jpeg,image/jpg" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg">Ajukan</button>
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="btn btn-link">Kembali ke Login</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Background biru kanan -->
            <div class="col-lg-3 d-none d-lg-block" style="background-color: #385096;"></div>
        </div>
    </div>
</body>

</html> 