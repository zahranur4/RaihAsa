@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Edit Donasi</h2>
        <p>Edit donasi yang dipilih</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.donations.update', $donation->id_donasi) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="id_donatur" class="form-label">ID Donatur</label>
                <input type="text" id="id_donatur" name="id_donatur" class="form-control @error('id_donatur') is-invalid @enderror" value="{{ old('id_donatur', $donation->id_donatur) }}">
                @error('id_donatur') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $donation->nama_barang) }}">
                @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" id="kategori" name="kategori" class="form-control" value="{{ old('kategori', $donation->kategori) }}">
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" id="foto" name="foto" class="form-control">
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection