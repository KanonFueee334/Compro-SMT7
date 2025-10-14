@extends('layout.app')

@section('title', 'Beranda')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</h4>
                        <span id="server-time-context" style="display:none"
                              data-tz="+07:00"
                              data-today="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}"></span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary text-center">
                            <h5>Klik Untuk Absensi</h5>
                            <div class="d-flex gap-2 justify-content-center">
                                <form method="post" action="{{ route('mg.absen.masuk') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success rounded-pill btn-lg">ABSEN MASUK</button>
                                </form>
                                
                                <form method="post" action="{{ route('mg.absen.pulang') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger rounded-pill btn-lg">ABSEN PULANG</button>
                                </form>
                            </div>
                        </div>
                            <!-- Display success message -->
                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif

                            <!-- Display error message -->
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <!-- Display Validation Errors -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    Gagal menyimpan absensi
                                </div>
                            @endif  
                        <div>
                            <table class="table table-sm" style="font-size: 1.25rem; font-weight: 700;">
                               
                                <tr>
                                    <td>
                                        Absen Masuk
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        @if($absensiToday && $absensiToday->masuk)
                                            <span class="localize-time-today"
                                                  data-date="{{ (isset($absensiToday->tgl) && $absensiToday->tgl) ? \Carbon\Carbon::createFromFormat('d-m-Y', $absensiToday->tgl)->format('Y-m-d') : \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}"
                                                  data-time="{{ $absensiToday->masuk }}">{{ $absensiToday->masuk }}</span>
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        Absen Pulang
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        @if($absensiToday && $absensiToday->pulang)
                                            <span class="localize-time-today"
                                                  data-date="{{ (isset($absensiToday->tgl) && $absensiToday->tgl) ? \Carbon\Carbon::createFromFormat('d-m-Y', $absensiToday->tgl)->format('Y-m-d') : \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}"
                                                  data-time="{{ $absensiToday->pulang }}">{{ $absensiToday->pulang }}</span>
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Riwayat Absensi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-lg">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TANGGAL</th>
                                        <th>WAKTU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @for ($i = 0; $i < count($absenList); $i++)
                                   <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $absenList[$i]->tgl }}</td>
                                    <td>
                                        <span class="localize-time-row"
                                              data-date="{{ \Carbon\Carbon::createFromFormat('d-m-Y', $absenList[$i]->tgl)->format('Y-m-d') }}"
                                              data-time="{{ $absenList[$i]->waktu }}">{{ $absenList[$i]->waktu }}</span>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <h5 class="text-center"><a href="{{ route('mg.absen.history') }}">Lihat selengkapnya ...</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection