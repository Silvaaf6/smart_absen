@extends('layouts.admin.template')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title">Data Jabatan</h4>
                        <div class="ml-auto">
                            <div class="btn-list">
                                <button type="button" class="btn btn-link p-0" data-toggle="modal"
                                    data-target="#centermodal">
                                    <i class='fas fa-plus-circle '></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                                <tr class="border-0">
                                    <th class="border-bottom font-14 font-weight-medium text-muted">No</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Nama Jabatan</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($jabatan->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center text-muted font-14">Tidak ada data tersedia
                                        </td>
                                    </tr>
                                @else
                                    @php $no = 1 @endphp
                                    @foreach ($jabatan as $data)
                                        <tr>
                                            <td class="border-bottom text-muted font-14">{{ $no++ }}</td>
                                            <td class="border-bottom text-muted font-14">{{ $data->nama_jabatan }}</td>
                                            <td class="border-bottom text-muted font-14">
                                                <form method="POST" action="{{ route('jabatan.destroy', $data->id) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="flex items-center space-x-4 text-sm">
                                                        <button type="submit"
                                                            class="flex items-center justify-between p-2 text-sm font-medium text-red-600 rounded-lg border-0 bg-transparent"
                                                            aria-label="Delete">
                                                            <i class='fas fa-trash-alt text-danger'></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myCenterModalLabel">Tambah Data Jabatan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{route('jabatan.store')}}" enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="text-dark" for="nama_jabatan">Nama Jabatan</label>
                                                <input class="form-control" id="nama_jabatan" type="nama_jabatan"
                                                    aria-describedby="hs-input-helper-text @error('nama_jabatan') is-invalid @enderror"
                                                    name="nama_jabatan" value="{{ old('nama_jabatan') }}" required
                                                    autocomplete="nama_jabatan" autofocus
                                                    placeholder="masukkan nama jabatan">

                                                @error('nama_jabatan')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-center">
                                            <button type="submit" class="btn btn-block btn-dark">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
