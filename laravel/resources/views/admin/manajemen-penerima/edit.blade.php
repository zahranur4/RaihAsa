@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Edit Panti</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.recipients.update', $panti->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Panti</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $panti->nama) }}">
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <input type="text" name="jenis" id="jenis" class="form-control" value="{{ old('jenis', $panti->jenis) }}">
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" class="form-control">{{ old('alamat', $panti->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="kode_pos" class="form-label">Kode Pos</label>
                <input type="text" name="kode_pos" id="kode_pos" class="form-control" value="{{ old('kode_pos', $panti->kode_pos) }}">
            </div>
            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $panti->nomor_telepon) }}">
            </div>
            <div class="mb-3">
                <label for="kapasitas" class="form-label">Kapasitas</label>
                <input type="number" name="kapasitas" id="kapasitas" class="form-control" value="{{ old('kapasitas', $panti->kapasitas) }}">
            </div>
            <div class="mb-3">
                <label for="status_verifikasi_legalitas" class="form-label">Status Verifikasi</label>
                <select name="status_verifikasi_legalitas" id="status_verifikasi_legalitas" class="form-select">
                    <option value="pending" {{ (old('status_verifikasi_legalitas', $panti->status_verifikasi_legalitas) == 'pending') ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="terverifikasi" {{ (old('status_verifikasi_legalitas', $panti->status_verifikasi_legalitas) == 'terverifikasi') ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="ditolak" {{ (old('status_verifikasi_legalitas', $panti->status_verifikasi_legalitas) == 'ditolak') ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.recipients.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection