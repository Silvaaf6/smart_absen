@extends('layouts.admin.template')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title">Detail User</h4>
                    </div>
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <img src="{{ asset('admin/images/cover/' . auth()->user()->cover) }}"
                                            class="rounded-circle mb-3" alt="Cover" width="100">
                                        <h5 class="card-title">{{ $user->name }}</h5>
                                        <p class="text-muted">{{ $user->email }}</p>
                                        <p class="text-muted">{{ $user->jabatan->nama_jabatan }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-2"><strong>NIP</strong> <span
                                                class="float-end">{{ $user->nip }}</span></div>
                                        <hr>
                                        <div class="mb-2"><strong>Alamat</strong> <span
                                                class="float-end">{{ $user->alamat }}</span></div>
                                        <hr>
                                        <div class="mb-2"><strong>Tempat Lahir</strong> <span
                                                class="float-end">{{ $user->tempat_lahir }}</span></div>
                                        <hr>
                                        <div class="mb-2"><strong>Tanggal Lahir</strong>
                                            <span
                                                class="float-end">{{ \Carbon\Carbon::parse($user->tgl_lahir)->format('d-m-Y') }}</span>
                                        </div>
                                        <hr>
                                        <div class="mb-2"><strong>Agama</strong> <span
                                                class="float-end">{{ $user->agama }}</span></div>
                                        <hr>
                                        <div class="mb-2"><strong>Jenis Kelamin</strong> <span
                                                class="float-end">{{ $user->jenis_kelamin }}</span></div>
                                        <hr>
                                        <div class="mb-2"><strong>No Telepon</strong> <span
                                                class="float-end">{{ $user->no_telp }}</span></div>
                                        <hr>
                                        <button type="button" class="btn btn-info text-white" data-toggle="modal"
                                            data-target="#editmodal">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="editModal">Edit Data Pegawai</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pegawai.update', $user->id) }}"
                                        enctype="multipart/form-data" class="mt-2">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-dark" for="name">Nama Pegawai</label>
                                                    <input class="form-control" id="name" type="text" name="name"
                                                        value="{{ old('name', $user->name) }}" required
                                                        placeholder="Masukkan nama pegawai">
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="email">Email</label>
                                                    <input class="form-control" id="email" type="email" name="email"
                                                        value="{{ old('email', $user->email) }}" required
                                                        placeholder="Masukkan email">
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="nip">NIP</label>
                                                    <input class="form-control" id="nip" type="number" name="nip"
                                                        value="{{ old('nip', $user->nip) }}" required
                                                        placeholder="Masukkan NIP">
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark">Profile</label>
                                                    <input class="form-control" id="cover" type="file"
                                                        name="cover">
                                                    @if ($user->cover)
                                                        <p class="text-muted mt-2">Gambar saat ini: <br>
                                                            <img src="{{ asset('images/karyawan/' . $user->cover) }}"
                                                                alt="Foto Profil" width="100">
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="nama_jabatan">Jabatan</label>
                                                    <select class="form-control" id="nama_jabatan" name="id_jabatan"
                                                        required>
                                                        <option value="" disabled>Pilih Jabatan</option>
                                                        @foreach ($jabatan as $data)
                                                            <option value="{{ $data->id }}"
                                                                {{ $data->id == old('id_jabatan', $user->id_jabatan) ? 'selected' : '' }}>
                                                                {{ $data->nama_jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="text-dark" for="tempat_lahir">Tempat Lahir</label>
                                                    <input class="form-control" id="tempat_lahir" type="text"
                                                        name="tempat_lahir"
                                                        value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required
                                                        placeholder="Masukkan tempat lahir">
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="tgl_lahir">Tanggal Lahir</label>
                                                    <input class="form-control" id="tgl_lahir" type="date"
                                                        name="tgl_lahir"
                                                        value="{{ old('tgl_lahir', $user->tgl_lahir) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="alamat">Alamat</label>
                                                    <input class="form-control" id="alamat" type="text"
                                                        name="alamat" value="{{ old('alamat', $user->alamat) }}"
                                                        required placeholder="Masukkan alamat">
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="jenis_kelamin">Jenis Kelamin</label>
                                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                                        required>
                                                        <option value="" disabled>Pilih Jenis Kelamin</option>
                                                        <option value="Laki-laki"
                                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                                            Laki-laki</option>
                                                        <option value="Perempuan"
                                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                                            Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="agama">Agama</label>
                                                    <select class="form-control" id="agama" name="agama" required>
                                                        <option value="" disabled>Pilih Agama</option>
                                                        <option value="Islam"
                                                            {{ old('agama', $user->agama) == 'Islam' ? 'selected' : '' }}>
                                                            Islam</option>
                                                        <option value="Kristen"
                                                            {{ old('agama', $user->agama) == 'Kristen' ? 'selected' : '' }}>
                                                            Kristen</option>
                                                        <option value="Budha"
                                                            {{ old('agama', $user->agama) == 'Budha' ? 'selected' : '' }}>
                                                            Budha</option>
                                                        <option value="Hindu"
                                                            {{ old('agama', $user->agama) == 'Hindu' ? 'selected' : '' }}>
                                                            Hindu</option>
                                                        <option value="Katolik"
                                                            {{ old('agama', $user->agama) == 'Katolik' ? 'selected' : '' }}>
                                                            Katolik</option>
                                                        <option value="Konghucu"
                                                            {{ old('agama', $user->agama) == 'Konghucu' ? 'selected' : '' }}>
                                                            Konghucu</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-dark" for="no_telp">No Telepon</label>
                                                    <input class="form-control" id="no_telp" type="number"
                                                        name="no_telp" value="{{ old('no_telp', $user->no_telp) }}"
                                                        required placeholder="Masukkan nomor telepon">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 text-center">
                                                <button type="submit" class="btn btn-block btn-dark">Update</button>
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
    </div>
@endsection
