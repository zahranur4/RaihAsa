@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Edit Panti</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.recipients.update', $panti->id_panti) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama_panti" class="form-label">Nama Panti</label>
                <input type="text" name="nama_panti" id="nama_panti" class="form-control" value="{{ old('nama_panti', $panti->nama_panti) }}" required>
            </div>
            <div class="mb-3">
                <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control" required>{{ old('alamat_lengkap', $panti->alamat_lengkap) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="kota" class="form-label">Kota</label>
                <input type="text" name="kota" id="kota" class="form-control" value="{{ old('kota', $panti->kota) }}" required>
            </div>
            <div class="mb-3">
                <label for="no_sk" class="form-label">Nomor SK</label>
                <input type="text" name="no_sk" id="no_sk" class="form-control" value="{{ old('no_sk', $panti->no_sk) }}">
            </div>
            <div class="mb-3">
                <label for="status_verif" class="form-label">Status Verifikasi</label>
                <select name="status_verif" id="status_verif" class="form-select" required>
                    <option value="pending" {{ (old('status_verif', $panti->status_verif) == 'pending') ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ (old('status_verif', $panti->status_verif) == 'verified') ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="rejected" {{ (old('status_verif', $panti->status_verif) == 'rejected') ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            @if($panti->user)
            <div class="mb-3">
                <label class="form-label">Informasi User</label>
                <p class="form-text">Email: {{ $panti->user->email }}</p>
                <p class="form-text">Nama: {{ $panti->user->nama }}</p>
            </div>
            @endif
            
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.recipients.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection