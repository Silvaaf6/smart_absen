@extends('layouts.admin.template')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title">Data Pegawai</h4>
                        <div class="ml-auto">
                            <div class="btn-list">
                                <button type="button" class="btn btn-link p-0" data-toggle="modal"
                                    data-target="#centermodal">
                                    <i class='fas fa-plus-circle '></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- INDEX --}}
                    <div class="table-responsive">
                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                                <tr class="border-0">
                                    <th class="border-bottom font-14 font-weight-medium text-muted">No</th>
                                    {{-- <th class="border-bottom font-14 font-weight-medium text-muted">Profile</th> --}}
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Nama</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Email</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Jabatan</th>
                                    <th class="border-bottom font-14 font-weight-medium text-muted">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1 @endphp
                                @if ($pegawai->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted font-14">Tidak ada data pegawai.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($pegawai as $data)
                                        <tr>
                                            <td class="border-bottom text-muted font-14">{{ $no++ }}</td>
                                            {{-- <td class="border-bottom text-muted font-14">
                                                <img src="{{ asset('admin/images/cover/' . auth()->user()->cover) }}"
                                                    alt="Cover" width="100">
                                            </td> --}}
                                            <td class="border-bottom text-muted font-14">{{ $data->name }}</td>
                                            <td class="border-bottom text-muted font-14">{{ $data->email }}</td>
                                            <td class="border-bottom text-muted font-14">
                                                {{ $data->jabatan ? $data->jabatan->nama_jabatan : 'Tidak ada jabatan' }}
                                            </td>
                                            <td class="border-bottom text-muted font-14">
                                                <form method="POST" action="{{ route('jabatan.destroy', $data->id) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="flex items-center space-x-4 text-sm">
                                                        <button type="submit"
                                                            class="flex items-center justify-between p-2 text-sm font-medium rounded-lg border-0 bg-transparent"
                                                            aria-label="Delete">
                                                            <i class='fas fa-trash-alt text-danger'></i>
                                                        </button>
                                                        <a href="{{ route('pegawai.show', $data->id) }}"
                                                            class="btn btn-link p-0">
                                                            <i class='icon-eye text-success'></i>
                                                        </a>
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

                {{-- modal create --}}
                <div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myCenterModalLabel">Tambah Data Pegawai</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('pegawai.store') }}" enctype="multipart/form-data"
                                    class="mt-2">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="name">Nama Pegawai</label>
                                                <input class="form-control" id="name" type="text"
                                                    aria-describedby="hs-input-helper-text @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name') }}" required autocomplete="name"
                                                    autofocus placeholder="Masukkan nama pegawai">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="email">Email</label>
                                                <input class="form-control" id="email" type="email"
                                                    aria-describedby="hs-input-helper-text @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                                    autofocus placeholder="Masukkan email">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Password</label>
                                                <input class="form-control" id="password" type="password"
                                                    aria-describedby="hs-input-helper-text @error('password') is-invalid @enderror"
                                                    name="password" value="{{ old('password') }}" required
                                                    autocomplete="password" autofocus placeholder="Masukkan password">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="nip">NIP</label>
                                                <input class="form-control" id="nip" type="number"
                                                    aria-describedby="hs-input-helper-text @error('nip') is-invalid @enderror"
                                                    name="nip" value="{{ old('nip') }}" required
                                                    autocomplete="nip" autofocus placeholder="Masukkan NIP">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark">Profile</label>
        <input type="file" class="form-control" id="cover" name="cover" {{ isset($user) ? '' : 'required' }}>


                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="nama_jabatan">Jabatan</label>
                                                <select class="form-control @error('nama_jabatan') is-invalid @enderror"
                                                    id="nama_jabatan" name="id_jabatan" required>
                                                    <option value="" disabled selected>Pilih Jabatan</option>
                                                    @foreach ($jabatan as $data)
                                                        <option value="{{ $data->id }}">{{ $data->nama_jabatan }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="tempat_lahir">Tempat Lahir</label>
                                                <input class="form-control" id="tempat_lahir" type="text"
                                                    aria-describedby="hs-input-helper-text @error('tempat_lahir') is-invalid @enderror"
                                                    name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                                                    autocomplete="tempat_lahir" autofocus
                                                    placeholder="Masukkan tempat lahir">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="tgl_lahir">Tanggal Lahir</label>
                                                <input class="form-control" id="tgl_lahir" type="date"
                                                    aria-describedby="hs-input-helper-text @error('tgl_lahir') is-invalid @enderror"
                                                    name="tgl_lahir" value="{{ old('tgl_lahir') }}" required
                                                    autocomplete="tgl_lahir" autofocus>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="alamat">Alamat</label>
                                                <input class="form-control" id="alamat" type="text-area"
                                                    aria-describedby="hs-input-helper-text @error('alamat') is-invalid @enderror"
                                                    name="alamat" value="{{ old('alamat') }}" required
                                                    autocomplete="alamat" autofocus placeholder="Masukkan alamat">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="jenis_kelamin">Jenis Kelamin</label>
                                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="agama">Agama</label>
                                                <select class="form-control @error('agama') is-invalid @enderror"
                                                    id="agama" name="agama" required>
                                                    <option value="" disabled selected>Pilih Agama</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Budha">Budha</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Katolik">Katolik</option>
                                                    <option value="Konghucu">Konghucu</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-dark" for="no_telp">No Telepon</label>
                                                <input class="form-control" id="no_telp" type="number"
                                                    aria-describedby="hs-input-helper-text @error('no_telp') is-invalid @enderror"
                                                    name="no_telp" value="{{ old('no_telp') }}" required
                                                    autocomplete="no_telp" autofocus placeholder="Masukkan nomor telepon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
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
    </div>
    </div>
    </div>
    {{-- </div>  --}}
    </div>
    </div>
    </div>
@endsection
