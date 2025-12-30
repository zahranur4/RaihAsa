@extends('admin.dashboard.index')

@section('content')
<div class="container mt-4">
    <h3>Edit Pengguna</h3>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ubah Password (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="kata_sandi" class="form-control">
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
