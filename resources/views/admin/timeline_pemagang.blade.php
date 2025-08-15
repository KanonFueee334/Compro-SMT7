@extends('layout.app')

@section('title', 'Timeline Pemagang')

@section('content')
<div class="page-content">
    <section class="row">
        <!-- Statistik Cards -->
        <div class="col-12">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldUser"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Pemagang</h6>
                                    <h6 class="font-extrabold mb-0">{{ $totalPemagang }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Sedang Magang</h6>
                                    <h6 class="font-extrabold mb-0">{{ $sedangMagang }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldTick-Square"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Selesai Magang</h6>
                                    <h6 class="font-extrabold mb-0">{{ $selesaiMagang }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Bidang & Tim</h6>
                                    <h6 class="font-extrabold mb-0">{{ count($groupedPemagang) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Gantt Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Timeline Pemagang</h4>
                    <p class="card-text">Daftar pemagang dikelompokkan berdasarkan bidang dan tim</p>
                </div>
                <div class="card-body">
                    @if(count($groupedPemagang) > 0)
                        @foreach($groupedPemagang as $groupName => $pemagangList)
                            <div class="timeline-group mb-4 @if($groupName === 'Lokasi Tidak Diketahui') warning-group @endif">
                                <div class="timeline-group-header">
                                    <h5 class="@if($groupName === 'Lokasi Tidak Diketahui') text-warning @else text-primary @endif mb-3">
                                        @if($groupName === 'Lokasi Tidak Diketahui')
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                        @else
                                            <i class="bi bi-building me-2"></i>
                                        @endif
                                        {{ $groupName }}
                                        <span class="badge @if($groupName === 'Lokasi Tidak Diketahui') bg-warning @else bg-primary @endif ms-2">{{ count($pemagangList) }} pemagang</span>
                                    </h5>
                                </div>
                                
                                <div class="timeline-container" data-min="{{ \Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" data-max="{{ \Carbon\Carbon::parse($maxDate)->format('Y-m-d') }}">
                                    @foreach($pemagangList as $pemagang)
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $mulai = \Carbon\Carbon::parse($pemagang->mulai_magang);
                                            $selesai = \Carbon\Carbon::parse($pemagang->selesai_magang);
                                            $progress = 0;
                                            $statusClass = 'secondary';
                                            
                                            if ($now->lt($mulai)) {
                                                $statusClass = 'warning';
                                                $statusText = 'Belum Mulai';
                                            } elseif ($now->between($mulai, $selesai)) {
                                                $totalSpanDays = max(1, $mulai->diffInDays($selesai));
                                                $elapsedDays = $mulai->diffInDays($now);
                                                $progress = min(100, ($elapsedDays / $totalSpanDays) * 100);
                                                $statusClass = 'primary';
                                                $statusText = 'Sedang Berlangsung';
                                            } else {
                                                $progress = 100;
                                                $statusClass = 'success';
                                                $statusText = 'Selesai';
                                            }

                                            if (!empty($pemagang->status) && $pemagang->status === 'selesai') {
                                                $progress = 100;
                                                $statusClass = 'success';
                                                $statusText = 'Selesai';
                                            }
                                            
                                            $pesertaNames = 'N/A';
if (is_array($pemagang->peserta_magang)) {
    $names = array_map(function($item) {
        if (is_array($item)) {
            return $item['nama'] ?? $item['name'] ?? '';
        }
        return (string) $item;
    }, $pemagang->peserta_magang);
    $names = array_filter($names, function($v){ return trim((string)$v) !== ''; });
    $pesertaNames = count($names) ? implode(', ', $names) : 'N/A';
} elseif (is_string($pemagang->peserta_magang)) {
    $pesertaNames = $pemagang->peserta_magang;
}
                                        @endphp
                                        
                                        <div class="timeline-item mb-3">
                                            <div class="card border-{{ $statusClass }}">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="gantt-col col-12 mb-2">
                                                            @php
                                                                $rangeStart = \Carbon\Carbon::parse($minDate);
                                                                $rangeEnd = \Carbon\Carbon::parse($maxDate);
                                                                $totalDays = max(1, $rangeStart->diffInDays($rangeEnd));
                                                                $offsetDays = max(0, $rangeStart->diffInDays($mulai));
                                                                $durationDays = max(1, $mulai->diffInDays($selesai));
                                                                $leftPercent = min(100, ($offsetDays / $totalDays) * 100);
                                                                $widthPercent = min(100, ($durationDays / $totalDays) * 100);
                                                            @endphp
                                                            <div class="gantt-bar-wrapper">
                                                                <div class="gantt-bar bg-{{ $statusClass }}" style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%">
                                                                    <span class="gantt-label">{{ $mulai->format('d M') }} - {{ $selesai->format('d M') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h6 class="card-title mb-1">{{ $pesertaNames }}</h6>
                                                            <small class="text-muted">{{ $pemagang->instansi_sekolah_universitas ?: 'N/A' }}</small>
                                                            <br>
                                                            <small class="text-muted">{{ $pemagang->jurusan ?: 'N/A' }}</small>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="timeline-dates">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <small class="text-muted">Mulai:</small>
                                                                        <br>
                                                                        <strong>{{ $mulai->format('d M Y') }}</strong>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted">Selesai:</small>
                                                                        <br>
                                                                        <strong>{{ $selesai->format('d M Y') }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                            <div class="progress mb-2" style="height: 8px;">
                                                                <div class="progress-bar bg-{{ $statusClass }}" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $progress }}%"
                                                                     aria-valuenow="{{ $progress }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                                            @if($progress > 0 && $progress < 100)
                                                                <small class="text-muted ms-2">{{ number_format($progress, 1) }}%</small>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="col-md-2">
                                                            <div class="btn-group-vertical w-100" role="group">
                                                                <a href="{{ route('mg.recap', ['start' => $mulai->format('Y-m-d'), 'end' => $selesai->format('Y-m-d')]) }}" 
                                                                   class="btn btn-sm btn-outline-info mb-1" 
                                                                   title="Laporan Absen"
                                                                   target="_blank">
                                                                    <i class="bi bi-calendar-check me-1"></i>
                                                                    Absen
                                                                </a>
                                                                
                                                                @if($pemagang->hasilMagang)
                                                                    <a href="{{ route('admin.hasil') }}" 
                                                                       class="btn btn-sm btn-outline-success mb-1"
                                                                       title="Laporan Progres"
                                                                       target="_blank">
                                                                        <i class="bi bi-file-earmark-text me-1"></i>
                                                                        Progres
                                                                    </a>
                                                                @else
                                                                    <button class="btn btn-sm btn-outline-secondary mb-1" disabled
                                                                            title="Belum ada laporan progres">
                                                                        <i class="bi bi-file-earmark-text me-1"></i>
                                                                        Progres
                                                                    </button>
                                                                @endif
                                                                
                                                                @if($statusClass !== 'success')
                                                                    <form action="{{ route('admin.pelaksanaan.selesai', $pemagang->id) }}" 
                                                                          method="POST" 
                                                                          class="d-inline"
                                                                          onsubmit="return confirm('Apakah Anda yakin ingin mengubah status magang menjadi selesai?')">
                                                                        @csrf
                                                                        <button type="submit" 
                                                                                class="btn btn-sm btn-outline-warning w-100"
                                                                                title="Tandai Selesai">
                                                                            <i class="bi bi-check-circle me-1"></i>
                                                                            Selesai
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button class="btn btn-sm btn-success w-100" disabled>
                                                                        <i class="bi bi-check-circle me-1"></i>
                                                                        Selesai
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Belum ada data pemagang</h5>
                            <p class="text-muted">Data pemagang akan muncul di sini setelah ada penerimaan magang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.timeline-group {
    border-left: 3px solid #435ebe;
    padding-left: 20px;
    margin-left: 10px;
}

.timeline-group.warning-group {
    border-left: 3px solid #ffc107;
}

.timeline-group-header {
    margin-bottom: 20px;
}

.timeline-item {
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 20px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #435ebe;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #435ebe;
}

.warning-group .timeline-item::before {
    background: #ffc107;
    box-shadow: 0 0 0 2px #ffc107;
}

.timeline-container {
    position: relative;
}

.gantt-col .gantt-bar-wrapper {
    position: relative;
    height: 28px;
    background: #f1f3f5;
    border-radius: 4px;
    overflow: hidden;
}

.gantt-col .gantt-bar {
    position: absolute;
    top: 4px;
    bottom: 4px;
    left: 0;
    height: 20px;
    border-radius: 4px;
}

.gantt-col .gantt-bar .gantt-label {
    position: absolute;
    right: 6px;
    top: -18px;
    font-size: 0.72rem;
    color: #6c757d;
}


.progress {
    background-color: #e9ecef;
}

.btn-group-vertical .btn {
    text-align: left;
    font-size: 0.8rem;
}

.card.border-primary {
    border-left: 4px solid #435ebe !important;
}

.card.border-success {
    border-left: 4px solid #198754 !important;
}

.card.border-warning {
    border-left: 4px solid #ffc107 !important;
}

.card.border-secondary {
    border-left: 4px solid #6c757d !important;
}
</style>
@endsection 