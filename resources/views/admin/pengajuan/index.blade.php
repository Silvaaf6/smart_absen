@extends('layouts.admin.template')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">
                            @if (Auth::user()->hasRole('admin'))
                                Pengajuan Cuti
                            @else
                                Pengajuan Cuti Saya
                            @endif
                        </h4>
                        @if (!Auth::user()->hasRole('admin'))
                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#cuti">
                                Ajukan Cuti
                            </button>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">No</th>
                                    @if (Auth::user()->hasRole('admin'))
                                        <th class="border-bottom font-14 font-weight-medium text-muted">Nama
                                            User</th>
                                    @endif
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Kategori
                                    </th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Tanggal
                                        Pengajuan</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Tanggal
                                        Mulai</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Tanggal
                                        Selesai</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Alasan</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Status</th>
                                    @if (Auth::user()->hasRole('admin'))
                                        <th class="border-bottom font-14 font-weight-medium text-muted">Aksi
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan_cuti->isEmpty())
                                    <tr>
                                        <td colspan="{{ Auth::user()->hasRole('admin') ? 8 : 7 }}"
                                            class="text-center text-muted">
                                            Belum ada pengajuan cuti.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($pengajuan_cuti as $index => $cuti)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            @if (Auth::user()->hasRole('admin'))
                                                <td>{{ $cuti->user->name }}</td>
                                            @endif
                                            <td>{{ ucfirst($cuti->kategori_cuti) }}</td>
                                            <td>{{ $cuti->tgl_pengajuan }}</td>

                                            <td>{{ $cuti->tgl_mulai }}</td>
                                            <td>{{ $cuti->tgl_selesai }}</td>
                                            <td>{{ $cuti->alasan }}</td>
                                            <td>
                                                @if ($cuti->status == 'diizinkan')
                                                    <button class="btn btn-success rounded-pill" disabled>Disetujui</button>
                                                @elseif($cuti->status == 'tidak diizinkan')
                                                    <button class="btn btn-danger rounded-pill" disabled>Ditolak</button>
                                                @else
                                                    <button class="btn btn-secondary rounded-pill" disabled>Menunggu
                                                        Konfirmasi</button>
                                                @endif

                                            </td>
                                            @if (Auth::user()->hasRole('admin'))
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn dropdown-toggle" type="button"
                                                            id="dropdownMenuButton{{ $cuti->id }}"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton{{ $cuti->id }}">
                                                            <form method="POST"
                                                                action="{{ route('pengajuan_cuti.update', $cuti->id) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button name="status" value="diizinkan"
                                                                    class="dropdown-item text-success"
                                                                    {{ $cuti->status !== 'menunggu konfirmasi' ? 'disabled' : '' }}>Approve</button>
                                                                <button name="status" value="tidak diizinkan"
                                                                    class="dropdown-item text-danger"
                                                                    {{ $cuti->status !== 'menunggu konfirmasi' ? 'disabled' : '' }}>Tolak</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="cuti" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Ajukan Cuti</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pengajuan_cuti.store') }}">
                                        @csrf
                                        <label>Kategori Cuti</label>
                                        <select class="form-control" name="kategori_cuti" required>
                                            <option value="izin">Izin</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="cuti">Cuti</option>
                                        </select>

                                        <label>Tanggal Mulai</label>
                                        <input type="date" class="form-control" name="tgl_mulai" required>

                                        <label>Tanggal Selesai</label>
                                        <input type="date" class="form-control" name="tgl_selesai" required>

                                        <label>Alasan</label>
                                        <textarea class="form-control" name="alasan" required></textarea>

                                        <button type="submit" class="btn btn-dark mt-3">Ajukan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
