@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Tambah Panti</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.recipients.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_user" class="form-label">ID User</label>
                <input type="text" name="id_user" id="id_user" class="form-control" value="{{ old('id_user') }}">
            </div>
            <div class="mb-3">
                <label for="nama_panti" class="form-label">Nama Panti</label>
                <input type="text" name="nama_panti" id="nama_panti" class="form-control" value="{{ old('nama_panti') }}">
            </div>
            <div class="mb-3">
                <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control">{{ old('alamat_lengkap') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="kota" class="form-label">Kota</label>
                <input type="text" name="kota" id="kota" class="form-control" value="{{ old('kota') }}">
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.recipients.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection