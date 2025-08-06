<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $formLink->title }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        #auth {
            min-height: 100vh;
        }
        
        .auth-left {
            background: linear-gradient(135deg, #385096 0%, #4a6bdf 100%);
            position: relative;
            overflow: hidden;
        }
        
        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .auth-logo {
            position: relative;
            z-index: 2;
        }
        
        .auth-logo img {
            width: 80px;
            height: 80px;
            filter: brightness(0) invert(1);
        }
        
        .auth-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .auth-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.9);
            font-weight: 300;
            position: relative;
            z-index: 2;
        }
        
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 20px;
            max-width: 500px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            display: block;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #385096;
            box-shadow: 0 0 0 3px rgba(56, 80, 150, 0.1);
            background: white;
            outline: none;
        }
        
        .form-control::placeholder {
            color: #a0aec0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #385096 0%, #4a6bdf 100%);
            border: none;
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(56, 80, 150, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(56, 80, 150, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid #385096;
            color: #385096;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: #385096;
            color: white;
            transform: translateY(-1px);
        }
        
        .btn-outline-danger {
            border: 2px solid #e53e3e;
            color: #e53e3e;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-danger:hover {
            background: #e53e3e;
            color: white;
        }
        
        .anggota-item {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .anggota-item:hover {
            border-color: #385096;
            box-shadow: 0 2px 8px rgba(56, 80, 150, 0.1);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }
        
        .alert ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-select:focus {
            border-color: #385096;
            box-shadow: 0 0 0 3px rgba(56, 80, 150, 0.1);
            background: white;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .form-footer a {
            color: #385096;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .form-footer a:hover {
            color: #4a6bdf;
        }
        
        @media (max-width: 768px) {
            .form-container {
                margin: 10px;
                padding: 20px;
            }
            
            .auth-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">

            <!-- Background biru kiri -->
            <div class="col-lg-3 d-none d-lg-block auth-left">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center">
                        <div class="auth-logo mb-4">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo">
                        </div>
                        <h1 class="auth-title">{{ $formLink->title }}</h1>
                        @if($formLink->description)
                            <p class="auth-subtitle">{{ $formLink->description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Tengah -->
            <div class="col-lg-6 col-12 d-flex align-items-center justify-content-center">
                <div class="form-container">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengajuan.store') }}">
                        @csrf
                        <input type="hidden" name="form_link_id" value="{{ $formLink->id }}">

                        <div class="form-group">
                            <label class="form-label">Nama Pemohon</label>
                            <input type="text" name="nama_pemohon" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">No HP Pemohon</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Anggota Kelompok</label>
                            <div id="anggota-container">
                                <div class="anggota-item">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="anggota_nama[]" class="form-control" placeholder="Nama Anggota" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="anggota_hp[]" class="form-control" placeholder="No HP Anggota" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary" onclick="addAnggota()">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Anggota
                            </button>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Asal Instansi</label>
                            <input type="text" name="asal_instansi" class="form-control" placeholder="Nama universitas/sekolah" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Teknik Informatika" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keahlian yang Dipelajari</label>
                            <textarea name="keahlian" class="form-control" rows="4" placeholder="Jelaskan keahlian yang ingin dipelajari selama magang" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Divisi Magang</label>
                            <select name="lokasi_id" class="form-select" required>
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($lokasi as $item)
                                    <option value="{{ $item->id }}">{{ $item->bidang }} - {{ $item->tim }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mulai Magang</label>
                                    <input type="date" name="mulai_magang" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Selesai Magang</label>
                                    <input type="date" name="selesai_magang" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send me-2"></i>Ajukan Pengajuan
                            </button>
                        </div>
                    </form>

                    <div class="form-footer">
                        <a href="{{ route('login') }}">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>

            <!-- Background biru kanan -->
            <div class="col-lg-3 d-none d-lg-block auth-left"></div>
        </div>
    </div>

    <script>
        function addAnggota() {
            const container = document.getElementById('anggota-container');
            const newItem = document.createElement('div');
            newItem.className = 'anggota-item';
            newItem.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="anggota_nama[]" class="form-control" placeholder="Nama Anggota" required>
                                        </div>
                    <div class="col-md-5">
                        <input type="text" name="anggota_hp[]" class="form-control" placeholder="No HP Anggota" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger" onclick="removeAnggota(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        }

        function removeAnggota(button) {
            button.closest('.anggota-item').remove();
        }
    </script>
</body>

</html> 