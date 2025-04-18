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
                        <div class="col-md-4">
                            <div class="card text-center border-0">
                                <div class="card-body">
                                    <img src="{{ asset('admin/images/cover/' . auth()->user()->cover) }}"
                                        class="rounded-circle shadow mb-3" alt="Cover" width="120">
                                    <h5 class="card-title mb-0">{{ $user->name }}</h5>
                                    <div class="text-muted">{{ $user->email }}</div>
                                    <div class="badge bg-primary mt-2">{{ $user->jabatan->nama_jabatan }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>NIP</strong><span>{{ $user->nip }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Alamat</strong><span>{{ $user->alamat }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Tempat Lahir</strong><span>{{ $user->tempat_lahir }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Tanggal Lahir</strong><span>{{ \Carbon\Carbon::parse($user->tgl_lahir)->format('d-m-Y') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Agama</strong><span>{{ $user->agama }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Jenis Kelamin</strong><span>{{ $user->jenis_kelamin }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>No Telepon</strong><span>{{ $user->no_telp }}</span>
                                        </li>
                                    </ul>
                                    @role('admin')
                                    <div class="mt-3 text-end">
                                        <button type="button" class="btn btn-info text-white" data-toggle="modal" data-target="#editmodal">
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
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pegawai.update', $user->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Pegawai</label>
                                                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>NIP</label>
                                                    <input type="number" class="form-control" name="nip" value="{{ old('nip', $user->nip) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Foto</label>
                                                    <input type="file" class="form-control" name="cover">
                                                    @if ($user->cover)
                                                        <img src="{{ asset('images/karyawan/' . $user->cover) }}" width="100" class="mt-2">
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>Jabatan</label>
                                                    <select class="form-control" name="id_jabatan" required>
                                                        <option value="" disabled>Pilih Jabatan</option>
                                                        @foreach ($jabatan as $data)
                                                            <option value="{{ $data->id }}" {{ $data->id == old('id_jabatan', $user->id_jabatan) ? 'selected' : '' }}>
                                                                {{ $data->nama_jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tempat Lahir</label>
                                                    <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Lahir</label>
                                                    <input type="date" class="form-control" name="tgl_lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <input type="text" class="form-control" name="alamat" value="{{ old('alamat', $user->alamat) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <select class="form-control" name="jenis_kelamin" required>
                                                        <option value="" disabled>Pilih Jenis Kelamin</option>
                                                        <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                        <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Agama</label>
                                                    <select class="form-control" name="agama" required>
                                                        <option value="" disabled>Pilih Agama</option>
                                                        <option value="Islam" {{ old('agama', $user->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                        <option value="Kristen" {{ old('agama', $user->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                        <option value="Budha" {{ old('agama', $user->agama) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                                        <option value="Hindu" {{ old('agama', $user->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                        <option value="Katolik" {{ old('agama', $user->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                        <option value="Konghucu" {{ old('agama', $user->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>No Telepon</label>
                                                    <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" required>
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
