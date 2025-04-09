@extends('layouts.admin.template')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            @if (Auth::user()->hasRole('admin'))
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Data Kehadiran</h4>
                            <div class="ml-auto">
                                {{-- <div class="btn-list">
                                    <button type="button" class="btn btn-link p-0" data-toggle="modal"
                                        data-target="#sakitModal">
                                        <i class='fas fa-plus-circle '></i>
                                    </button>
                                </div> --}}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">No</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Nama
                                        </th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Tanggal
                                        </th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Jam
                                            Masuk</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Jam
                                            Keluar</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Status
                                        </th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Jam
                                            Kerja
                                        </th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Surat
                                            Dokter
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kehadirans as $kehadiran)
                                        <tr>
                                            <td class="border-bottom text-muted font-14 text-center">{{ $loop->iteration }}
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ $kehadiran->user->name }}</td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d-m-Y') }}
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i:s') ?? '-' }}
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                @if ($kehadiran->jam_keluar)
                                                    {{ \Carbon\Carbon::parse($kehadiran->jam_keluar)->format('H:i:s') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                @if ($kehadiran->status == 'Hadir')
                                                    <button class="btn btn-primary btn-sm rounded-pill"
                                                        disabled>Hadir</button>
                                                @elseif ($kehadiran->status == 'Terlambat')
                                                    <button class="btn btn-warning btn-sm rounded-pill"
                                                        disabled>Terlambat</button>
                                                @elseif ($kehadiran->status == 'Sakit')
                                                    <button class="btn btn-danger btn-sm rounded-pill"
                                                        disabled>Sakit</button>
                                                @else
                                                    <button class="btn btn-secondary btn-sm rounded-pill" disabled>Tidak
                                                        Diketahui</button>
                                                @endif
                                            </td>

                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ $kehadiran->jam_kerja ?? '-' }}
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                @if ($kehadiran->surat_dokter)
                                                    <a href="{{ asset('uploads/surat_dokter/' . $kehadiran->surat_dokter) }}"
                                                        target="_blank" class="btn btn-sm btn-info">Lihat Surat</a>
                                                @else
                                                    <span class="text-muted">Tidak Ada Surat</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($kehadirans->isEmpty())
                        <div class="mt-4 text-center text-gray-500">
                            <p>Absen tidak ditemukan.</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center justify-between w-full">
                            <h4 class="card-title mb-4">Absen Sekarang {{ Auth::user()->name }}</h4>
                            <div class="ml-auto mb-2">
                                <div class="btn-list">
                                    <button class="btn btn-warning waves-effect waves-light text-white" type="button"
                                        data-toggle="modal" data-target="#sakitModal"
                                        {{ $check_kehadiran && ($check_kehadiran->status == 'Sakit' || $check_kehadiran->jam_masuk) ? 'disabled' : '' }}><span
                                            class="btn-label"><i class="fas fa-plus"></i></span>
                                        Izin Sakit
                                    </button>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('kehadiran.store') }}">
                                @csrf
                                <input type="hidden" name="status"
                                    value="{{ $check_kehadiran === 'checkout' ? '' : ($check_kehadiran ? 'checkout' : 'checkin') }}">
                                @if ($check_kehadiran && $check_kehadiran->status == 'Sakit')
                                    <button class="btn btn-secondary waves-effect waves-light" type="submit" disabled>
                                        <span class="btn-label"><i class="fas fa-check"></i></span> Sakit (Sakit)
                                    </button>
                                @elseif ($check_kehadiran && $check_kehadiran->jam_keluar)
                                    <button class="btn btn-secondary waves-effect waves-light" type="submit" disabled>
                                        <span class="btn-label"><i class="fas fa-check"></i></span> Sudah Check-out
                                    </button>
                                @elseif ($check_kehadiran)
                                    <button class="btn btn-danger waves-effect waves-light" type="submit">
                                        <span class="btn-label"><i class="fas fa-times"></i></span> Check-out
                                    </button>
                                @else
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                                        <span class="btn-label"><i class="fas fa-check"></i></span> Check-in
                                    </button>
                                @endif

                            </form>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="table-responsive">
                                <table class="table no-wrap v-middle mb-0">
                                    <thead>
                                        <tr class="border-0">
                                            <th class="border-0 font-14 font-weight-medium text-muted">No</th>
                                            <th class="border-0 font-14 font-weight-medium text-muted">Nama</th>
                                            <th class="border-0 font-14 font-weight-medium text-muted">Tanggal</th>
                                            <th class="border-0 font-14 font-weight-medium text-muted">Jam Masuk</th>
                                            <th class="border-0 font-14 font-weight-medium text-muted">Jam Keluar</th>
                                            <th class="border-0 font-14 font-weight-medium text-muted">Jam Kerja</th>
                                            <th class="border-0 font-14 font-weight-medium text-muted">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kehadirans as $kehadiran)
                                            <tr>
                                                <td class="border-top-0 text-muted font-14">{{ $loop->iteration }}</td>
                                                <td class="border-top-0 text-muted font-14">{{ $kehadiran->user->name }}
                                                </td>
                                                <td class="border-top-0 text-muted font-14">
                                                    {{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d-m-Y') }}
                                                </td>
                                                <td class="border-top-0 text-muted font-14">
                                                    {{ $kehadiran->jam_masuk ?? '-' }}
                                                </td>
                                                <td class="border-top-0 text-muted font-14">
                                                    {{ $kehadiran->jam_keluar ?? '-' }}
                                                </td>
                                                <td class="border-top-0 text-muted font-14">
                                                    {{ $kehadiran->jam_kerja ?? '-' }}
                                                </td>
                                                <td class="border-top-0 text-muted font-14">
                                                    @if ($kehadiran->status == 'Hadir')
                                                        <button class="btn btn-primary btn-sm rounded-pill"
                                                            disabled>Hadir</button>
                                                    @elseif ($kehadiran->status == 'Terlambat')
                                                        <button class="btn btn-warning btn-sm rounded-pill"
                                                            disabled>Terlambat</button>
                                                    @elseif ($kehadiran->status == 'Sakit')
                                                        <button class="btn btn-danger btn-sm rounded-pill"
                                                            disabled>Sakit</button>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm rounded-pill" disabled>Tidak
                                                            Ada Keterangan</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($kehadirans->isEmpty())
                            <div class="mt-4 text-center text-gray-500">
                                <p>Absen tidak ditemukan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="sakitModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="sakitModalLabel">Masukkan Surat Dokter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kehadiran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status" value="sakit">
                        <div class="modal-body">
                            <label for="surat_dokter" class="form-label">Upload
                                Surat Dokter
                                (JPG/PNG/PDF)</label>
                            <input type="file" name="surat_dokter" class="form-control"
                                accept="image/*,application/pdf" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
