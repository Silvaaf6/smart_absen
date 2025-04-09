@extends('layouts.admin.template')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="card-title mb-4">Data Kehadiran</h4>
                        <hr color="black">
                        <div class="mt-3">
                            <form method="GET" action="{{ route('laporan.index') }}" class="mt-2" id="filterForm">
                                <div class="row d-flex align-items-end g-3">
                                    <div class="col-md-4">
                                        <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                            value="{{ request('tanggal_mulai') }}" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tanggal_selesai" class="form-label">Sampai Tanggal</label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                            value="{{ request('tanggal_selesai') }}" class="form-control">
                                    </div>
                                    @if (Auth::user()->hasRole('admin'))
                                        <div class="col-md-4">
                                            <label for="id_user" class="form-label">Pilih Karyawan</label>
                                            <select name="id_user" id="id_user" class="form-control w-100">
                                                <option value="">Pilih Karyawan</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $user->id == request('id_user') ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </form>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const form = document.getElementById("filterForm");
                                    const inputs = form.querySelectorAll("input, select");
                                    inputs.forEach(input => {
                                        input.addEventListener("change", function() {
                                            form.submit();
                                        });
                                    });
                                });
                            </script>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('laporan.exportPdf', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai'), 'id_user' => request('id_user')]) }}"
                                class="btn btn-sm btn-info"> <i class="icon-printer"></i>
                                Export PDF
                            </a>
                        </div>
                    </div>

                    @if ($kehadirans->isEmpty())
                        <div class="text-center text-gray-500 mt-4">
                            <p>Tidak Ada Data Kehadiran</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">No</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Nama</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Tanggal</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Jam Masuk</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Jam Keluar</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Status</th>
                                        <th class="border-bottom font-14 text-center font-weight-medium text-muted">Jam Kerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kehadirans as $kehadiran)
                                        <tr>
                                            <td class="border-bottom text-muted font-14 text-center">{{ $loop->iteration }}</td>
                                            <td class="border-bottom text-muted font-14 text-center">{{ $kehadiran->user->name }}</td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d-m-Y') }}
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ $kehadiran->status === 'Sakit' ? '-' : $kehadiran->jam_masuk }}
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                @if ($kehadiran->jam_keluar)
                                                    {{ \Carbon\Carbon::parse($kehadiran->jam_keluar)->format('H:i:s') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="border-bottom text-muted font-14 text-center">{{ ucfirst($kehadiran->status) }}</td>
                                            <td class="border-bottom text-muted font-14 text-center">
                                                {{ $kehadiran->status !== 'Sakit' ? $kehadiran->jam_kerja ?? '-' : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
