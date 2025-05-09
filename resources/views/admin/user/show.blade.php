@extends('layouts.admin.template')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title">Detail User</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-4 d-flex">
                            <div class="card text-center border-0 w-100 h-100">
                                <div class="card-body d-flex flex-column justify-content-center">
                                    <img src="{{ asset($pegawai->cover) }}" alt="Foto Profil"
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                        class="rounded-circle shadow mb-4 mx-auto">
                                    <h5 class="card-title mb-0">{{ $pegawai->name }}</h5>
                                    <div class="text-muted">{{ $pegawai->email }}</div>
                                    <div class="mt-2">
                                        <span class="badge bg-primary text-white text-wrap px-3">
                                            {{ $pegawai->jabatan->nama_jabatan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 d-flex">
                            <div class="card w-100 h-100">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="far fa-address-book text-primary" style="margin-right: 16px;"></i>
                                                <strong class="fs-6">NIP</strong>
                                            </div>
                                            <span class="ms-2">{{ $pegawai->nip }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-primary"
                                                    style="margin-right: 16px;"></i>
                                                <strong class="fs-6">Alamat</strong>
                                            </div>
                                            <span class="ms-2">{{ $pegawai->alamat }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-home text-primary" style="margin-right: 16px;"></i>
                                                <strong class="fs-6">Tempat Lahir</strong>
                                            </div>
                                            <span class="ms-2">{{ $pegawai->tempat_lahir }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt text-primary" style="margin-right: 16px;"></i>
                                                <strong class="fs-6">Tanggal Lahir</strong>
                                            </div>
                                            <span
                                                class="ms-2">{{ \Carbon\Carbon::parse($pegawai->tgl_lahir)->format('d-m-Y') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-moon text-primary" style="margin-right: 16px;"></i>
                                                <strong class="fs-6">Agama</strong>
                                            </div>
                                            <span class="ms-2">{{ $pegawai->agama }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-mars-double text-primary" style="margin-right: 16px;"></i>
                                                <strong class="fs-6">Jenis Kelamin</strong>
                                            </div>
                                            <span class="ms-2">{{ $pegawai->jenis_kelamin }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-phone text-primary" style="margin-right: 16px;"></i>
                                                <strong class="fs-6">No Telepon</strong>
                                            </div>
                                            <span>{{ $pegawai->no_telp }}</span>
                                        </li>
                                    </ul>
                                    @role('admin')
                                        <div class="mt-3 text-end">
                                            <button type="button" class="btn btn-info text-white" data-toggle="modal"
                                                data-target="#editmodal">
                                                Edit Profil
                                            </button>
                                        </div>
                                    @endrole
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Data Pegawai</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Pegawai</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ old('name', $pegawai->name) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email', $pegawai->email) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>NIP</label>
                                                    <input type="number" class="form-control" name="nip"
                                                        value="{{ old('nip', $pegawai->nip) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cover" class="text-dark">Foto</label>
                                                    <input type="file" class="form-control" name="cover"
                                                        id="cover" accept="image/*">

                                                    @if (isset($pegawai) && $pegawai->cover)
                                                        <div class="mt-3">
                                                            <img src="{{ asset($pegawai->cover) }}" alt="Foto Profil"
                                                                width="150" class="rounded shadow">
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    <label>Jabatan</label>
                                                    <select class="form-control" name="id_jabatan" required>
                                                        <option value="" disabled>Pilih Jabatan</option>
                                                        @foreach ($jabatan as $data)
                                                            <option value="{{ $data->id }}"
                                                                {{ $data->id == old('id_jabatan', $pegawai->id_jabatan) ? 'selected' : '' }}>
                                                                {{ $data->nama_jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tempat Lahir</label>
                                                    <input type="text" class="form-control" name="tempat_lahir"
                                                        value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Lahir</label>
                                                    <input type="date" class="form-control" name="tgl_lahir"
                                                        value="{{ old('tgl_lahir', $pegawai->tgl_lahir) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <input type="text" class="form-control" name="alamat"
                                                        value="{{ old('alamat', $pegawai->alamat) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <select class="form-control" name="jenis_kelamin" required>
                                                        <option value="" disabled>Pilih Jenis Kelamin</option>
                                                        <option value="Laki-laki"
                                                            {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                                            Laki-laki</option>
                                                        <option value="Perempuan"
                                                            {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                                            Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Agama</label>
                                                    <select class="form-control" name="agama" required>
                                                        <option value="" disabled>Pilih Agama</option>
                                                        <option value="Islam"
                                                            {{ old('agama', $pegawai->agama) == 'Islam' ? 'selected' : '' }}>
                                                            Islam</option>
                                                        <option value="Kristen"
                                                            {{ old('agama', $pegawai->agama) == 'Kristen' ? 'selected' : '' }}>
                                                            Kristen</option>
                                                        <option value="Budha"
                                                            {{ old('agama', $pegawai->agama) == 'Budha' ? 'selected' : '' }}>
                                                            Budha</option>
                                                        <option value="Hindu"
                                                            {{ old('agama', $pegawai->agama) == 'Hindu' ? 'selected' : '' }}>
                                                            Hindu</option>
                                                        <option value="Katolik"
                                                            {{ old('agama', $pegawai->agama) == 'Katolik' ? 'selected' : '' }}>
                                                            Katolik</option>
                                                        <option value="Konghucu"
                                                            {{ old('agama', $pegawai->agama) == 'Konghucu' ? 'selected' : '' }}>
                                                            Konghucu</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>No Telepon</label>
                                                    <input type="number" class="form-control" name="no_telp"
                                                        value="{{ old('no_telp', $pegawai->no_telp) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                                        </div>
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
