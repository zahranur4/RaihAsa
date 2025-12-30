@extends('admin.dashboard.index')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="page-info">
            <h2>Daftar Pengguna</h2>
            <p>Kelola semua pengguna yang terdaftar di platform RaihAsa</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="roleFilter" class="form-label">Filter berdasarkan peran</label>
                    <select class="form-select" id="roleFilter" name="role">
                        <option value="">Semua Peran</option>
                        <option value="donatur">Donatur</option>
                        <option value="relawan">Relawan</option>
                        <option value="penerima">Penerima</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Filter berdasarkan status</label>
                    <select class="form-select" id="statusFilter" name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                        <option value="pending">Menunggu Verifikasi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateFilter" class="form-label">Filter berdasarkan tanggal</label>
                    <input type="date" class="form-control" id="dateFilter" name="date">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Terapkan</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5>Daftar Pengguna</h5>
            <div class="table-actions">
                <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download"></i> Export</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=0D6EFD&color=fff" alt="User" class="user-avatar"></td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nomor_telepon ?? '-' }}</td>
                            <td>{{ Str::limit($user->alamat, 50) }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengguna</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal form can be implemented to create users -->
                    <p>Fitur tambah pengguna melalui modal belum diimplementasikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection