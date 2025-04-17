@extends('layouts.admin.template')

@section('content')
    <div class="page-wrapper">
        @if (Auth::user()->hasRole('admin'))
            <div class="page-breadcrumb">
                <div class="row mb-4">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning
                            {{ Auth::user()->name }}!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="card-group">
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <h2 class="text-dark mb-1 font-weight-medium">{{ $jumlahPegawai }}</h2>
                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pegawai</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                                class="set-doller"></sup>{{ $jumlahJabatan }}</h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Jabatan
                                        </h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <h2 class="text-dark mb-1 font-weight-medium">{{ $jumlahHadir }}</h2>
                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Kehadiran Hari
                                            Ini</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <h2 class="text-dark mb-1 font-weight-medium">{{ $jumlahCuti }}</h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengajuan Cuti
                                        </h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="globe"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Grafik</h4>
                                    <canvas id="grafik-kehadiran" height="150"></canvas>
                                    <p class="text-muted text-center font-italic mt-3 mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
@else
    <div class="container mt-4">
        <div id="userDashboardCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#userDashboardCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#userDashboardCarousel" data-slide-to="1"></li>
                <li data-target="#userDashboardCarousel" data-slide-to="2"></li>
            </ol>

            <div class="carousel-inner rounded shadow">
                <div class="carousel-item active">
                    <img src="{{ asset('images/slide1.jpg') }}" class="d-block w-100"
                        style="height:200px; object-fit: cover; border-radius: 15px;" alt="Slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Selamat Datang, {{ Auth::user()->name }}</h5>
                        <p>Ini adalah dashboard utama kamu. Cek aktivitas dan informasi penting di sini.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide2.jpg') }}" class="d-block w-100"
                        style="height:200px; object-fit: cover; border-radius: 15px;" alt="Slide 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Status Kehadiran</h5>
                        <p>Pastikan kamu selalu update absensi kamu setiap hari.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide3.jpg') }}" class="d-block w-100"
                        style="height:200px; object-fit: cover; border-radius: 15px;" alt="Slide 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Ajukan Cuti</h5>
                        <p>Kamu bisa mengajukan cuti langsung dari dashboard ini.</p>
                    </div>
                </div>
            </div>

            <a class="carousel-control-prev" href="#userDashboardCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="sr-only">Sebelumnya</span>
            </a>
            <a class="carousel-control-next" href="#userDashboardCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="sr-only">Selanjutnya</span>
            </a>
        </div>

        <div class="mt-4">
            @if (!$absenHariIni)
                <div class="alert alert-warning rounded shadow-sm d-flex align-items-center" role="alert">
                    <i class="feather icon-alert-circle mr-2"></i>
                    <div>
                        <strong>Pengingat:</strong> Kamu belum absen hari ini. Jangan lupa ya!
                        <a href="{{ route('kehadiran.index') }}" class="btn btn-sm btn-primary ml-3">Absen Sekarang</a>
                    </div>
                </div>
            @else
                <div class="alert alert-success rounded shadow-sm d-flex align-items-center" role="alert">
                    <i class="feather icon-check-circle mr-2"></i>
                    <div>
                        Kamu sudah absen hari ini. 👍
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("grafik-kehadiran").getContext("2d");

            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Pegawai', 'Jabatan', 'Kehadiran', 'Pengajuan Cuti'],
                    datasets: [{
                        label: 'Statistik',
                        data: [{{ $jumlahPegawai }}, {{ $jumlahJabatan }}, {{ $jumlahHadir }},
                            {{ $jumlahCuti }}
                        ],
                        borderColor: '#5f76e8',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
