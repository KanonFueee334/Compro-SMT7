<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Penelitian</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; min-height: 100vh; }
        #auth { min-height: 100vh; }

        .auth-header {
            background: linear-gradient(135deg, #385096 0%, #4a6bdf 100%);
            position: sticky;
            top: 0;
            z-index: 1000;
            overflow: hidden;
            padding: 24px 0;
            text-align: center;
        }
        .auth-header .auth-logo img { width: 80px; height: 80px; object-fit: contain; filter: brightness(0) invert(1); }

        /* Sembunyikan judul/deskripsi form */
        .auth-title, .auth-subtitle { display: none !important; }

        .form-container { background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin: 20px auto; max-width: 600px; width: 100%; }
        .form-group { margin-bottom: 25px; }
        .form-label, label { font-weight: 600; color: #2d3748; margin-bottom: 8px; display: block; font-size: 0.95rem; }
        .form-control { border: 2px solid #e2e8f0; border-radius: 12px; padding: 15px 18px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc; }
        .form-control:focus { border-color: #385096; box-shadow: 0 0 0 3px rgba(56,80,150,0.1); background: #fff; outline: none; }
        .form-control::placeholder { color: #a0aec0; }
        .form-select { border: 2px solid #e2e8f0; border-radius: 12px; padding: 15px 18px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc; }
        .form-select:focus { border-color: #385096; box-shadow: 0 0 0 3px rgba(56,80,150,0.1); background: #fff; }
        textarea.form-control { min-height: 100px; resize: vertical; }

        .btn-primary { background: linear-gradient(135deg, #385096 0%, #4a6bdf 100%); border: none; border-radius: 12px; padding: 16px 32px; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(56,80,150,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(56,80,150,0.4); }

        .alert { border-radius: 12px; border: none; padding: 16px 20px; margin-bottom: 25px; }
        .alert-success { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: #fff; }
        .alert-danger { background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: #fff; }
        .alert ul { margin: 0; padding-left: 20px; }

        @media (max-width: 768px) {
            .form-container { margin: 10px; padding: 20px; }
            .auth-header { padding: 18px 0; }
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">

            <!-- Header logo sticky -->
            <div class="col-12">
                <div class="auth-header">
                    <div class="auth-logo text-center">
                        <a href="{{ route('mg.home') }}"><img src="{{ asset('images/logo/komdigi-logo.png') }}" alt="Logo" style="width: 120px; height: auto;"></a>
                    </div>
                </div>
            </div>

            <!-- Form Tengah -->
            <div class="col-12 d-flex align-items-start justify-content-center mt-4">
                <div class="form-container">
                    <!-- judul & deskripsi disembunyikan via CSS -->

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
                    <input type="hidden" name="form_link_id" value="{{ $formLink->id }}">

                    <div class="form-group mb-3">
                        <label>Nama Peneliti</label>
                        <input type="text" name="nama" class="form-control only-letters" value="{{ old('nama') }}" required pattern="^[A-Za-zÀ-ÿ\s]+$" title="Hanya huruf dan spasi">
                    </div>

                    <div class="form-group mb-3">
                        <label>Instansi</label>
                        <input type="text" name="instansi" class="form-control only-letters" value="{{ old('instansi') }}" required pattern="^[A-Za-zÀ-ÿ\s]+$" title="Hanya huruf dan spasi">
                    </div>

                    <div class="form-group mb-3">
                        <label>Jurusan</label>
                        <input type="text" name="jurusan" class="form-control only-letters" value="{{ old('jurusan') }}" required pattern="^[A-Za-zÀ-ÿ\s]+$" title="Hanya huruf dan spasi">
                    </div>

                    <div class="form-group mb-3">
                        <label>Judul Penelitian</label>
                        <textarea name="judul_penelitian" class="form-control" rows="3" required>{{ old('judul_penelitian') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Metode Penelitian</label>
                        <select name="metode" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Kuesioner" {{ old('metode') == 'Kuesioner' ? 'selected' : '' }}>Kuesioner</option>
                            <option value="Wawancara" {{ old('metode') == 'Wawancara' ? 'selected' : '' }}>Wawancara</option>
                            <option value="Lainnya" {{ old('metode') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Surat Izin Penelitian (PDF, Max 2MB)</label>
                        <input type="file" name="surat_izin" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF (Max 2MB)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Proposal Penelitian (PDF, Max 5MB)</label>
                        <input type="file" name="proposal" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF (Max 5MB)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Daftar Pertanyaan (PDF, Max 2MB)</label>
                        <input type="file" name="daftar_pertanyaan" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Format: PDF (Max 2MB)</small>
                    </div>

                    <div class="form-group mb-4">
                        <label>KTP (PDF/JPG, Max 1MB)</label>
                        <input type="file" name="ktp" class="form-control" accept=".pdf,.jpg,.jpeg" required>
                        <small class="text-muted">Format: PDF, JPG, JPEG (Max 1MB)</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg">Ajukan Penelitian</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html> 