@extends('admin.dashboard.index')

@section('content')
<div class="content-header">
    <div class="page-info">
        <h2>Edit Relawan</h2>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pengguna</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $relawan->user->email ?? '-' }}</p>
                        <p><strong>Nama Akun:</strong> {{ $relawan->user->nama ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>No. Telepon:</strong> {{ $relawan->user->nomor_telepon ?? '-' }}</p>
                        <p><strong>Terdaftar:</strong> {{ $relawan->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Relawan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.volunteers.update', $relawan->id_relawan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $relawan->nama_lengkap) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik', $relawan->nik) }}" maxlength="16">
                        <small class="text-muted">16 digit NIK</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="skill" class="form-label">Keahlian & Minat</label>
                        <textarea name="skill" id="skill" class="form-control" rows="4">{{ old('skill', $relawan->skill) }}</textarea>
                        <small class="text-muted">Daftar keahlian dan minat relawan</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status_verif" class="form-label">Status Verifikasi <span class="text-danger">*</span></label>
                        <select name="status_verif" id="status_verif" class="form-select" required>
                            <option value="pending" {{ $relawan->status_verif === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="verified" {{ $relawan->status_verif === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ $relawan->status_verif === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <small class="text-muted">
                            @if($relawan->status_verif === 'pending')
                                <span class="badge bg-warning mt-2">Relawan ini menunggu verifikasi</span>
                            @elseif($relawan->status_verif === 'verified')
                                <span class="badge bg-success mt-2">Relawan telah terverifikasi</span>
                            @else
                                <span class="badge bg-danger mt-2">Pendaftaran relawan ditolak</span>
                            @endif
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.volunteers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection