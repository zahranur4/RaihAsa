@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Tambah Relawan</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.volunteers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_user" class="form-label">ID User</label>
                <input type="text" name="id_user" id="id_user" class="form-control" value="{{ old('id_user') }}">
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}">
            </div>
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik') }}">
            </div>
            <div class="mb-3">
                <label for="skill" class="form-label">Skill</label>
                <textarea name="skill" id="skill" class="form-control">{{ old('skill') }}</textarea>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.volunteers.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection