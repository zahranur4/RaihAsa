@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Edit Food Rescue</h2>
        <p>Edit item food rescue</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.food-rescue.update', $item->id_food) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="id_donatur" class="form-label">ID Donatur</label>
                <input type="text" name="id_donatur" id="id_donatur" class="form-control @error('id_donatur') is-invalid @enderror" value="{{ old('id_donatur', $item->id_donatur) }}">
                @error('id_donatur') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="nama_makanan" class="form-label">Nama Makanan</label>
                <input type="text" name="nama_makanan" id="nama_makanan" class="form-control @error('nama_makanan') is-invalid @enderror" value="{{ old('nama_makanan', $item->nama_makanan) }}">
                @error('nama_makanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="porsi" class="form-label">Porsi</label>
                <input type="number" name="porsi" id="porsi" class="form-control" value="{{ old('porsi', $item->porsi) }}">
            </div>
            <div class="mb-3">
                <label for="waktu_dibuat" class="form-label">Waktu Dibuat</label>
                <input type="datetime-local" name="waktu_dibuat" id="waktu_dibuat" class="form-control" value="{{ old('waktu_dibuat', $item->waktu_dibuat ? $item->waktu_dibuat->format('Y-m-d\TH:i') : '') }}">
            </div>
            <div class="mb-3">
                <label for="waktu_expired" class="form-label">Waktu Expired</label>
                <input type="datetime-local" name="waktu_expired" id="waktu_expired" class="form-control" value="{{ old('waktu_expired', $item->waktu_expired ? $item->waktu_expired->format('Y-m-d\TH:i') : '') }}">
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.food-rescue.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection