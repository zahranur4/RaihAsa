@extends('admin.dashboard.index')

@section('content')
    <div class="content-header">
        <div class="page-info">
            <h2>Manajemen Kegiatan Relawan</h2>
            <p>Kelola pendaftaran dan persetujuan kegiatan relawan.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Filter by Status</option>
                            <option value="registered" {{ request('status') === 'registered' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Table -->
            @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>Nama Relawan</th>
                                <th>Kegiatan</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Tgl Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>
                                        <strong>{{ $activity->volunteer_name }}</strong><br>
                                        <small class="text-muted">{{ $activity->volunteer_email }}</small>
                                    </td>
                                    <td>{{ $activity->activity_name }}</td>
                                    <td>{{ $activity->location }}</td>
                                    <td>{{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($activity->status === 'registered')
                                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                        @elseif($activity->status === 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($activity->status === 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @elseif($activity->status === 'completed')
                                            <span class="badge bg-info">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $activity->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y H:i') }}</small>
                                    </td>
                                    <td class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-volunteer="{{ $activity->volunteer_name }}"
                                            data-email="{{ $activity->volunteer_email }}"
                                            data-phone="{{ $activity->volunteer_phone }}"
                                            data-activity="{{ $activity->activity_name }}"
                                            data-location="{{ $activity->location }}"
                                            data-date="{{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}"
                                            data-registered="{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y H:i') }}"
                                            data-motivation="{{ $activity->motivation }}"
                                            data-emergency-name="{{ $activity->emergency_contact_name }}"
                                            data-emergency-phone="{{ $activity->emergency_contact_phone }}"
                                            data-transportation="{{ $activity->transportation }}"
                                            data-status-label="{{ $activity->status === 'registered' ? 'Menunggu Verifikasi' : ($activity->status === 'approved' ? 'Disetujui' : ($activity->status === 'rejected' ? 'Ditolak' : ($activity->status === 'completed' ? 'Selesai' : ucfirst($activity->status)))) }}"
                                            onclick="showDetailFromButton(this)">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>

                                        @if($activity->status === 'registered')
                                            <form action="{{ route('admin.volunteer-activities.approve', $activity->id) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmAction('approve', this, '{{ $activity->volunteer_name }}', '{{ $activity->activity_name }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.volunteer-activities.reject', $activity->id) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmAction('reject', this, '{{ $activity->volunteer_name }}', '{{ $activity->activity_name }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                            </form>
                                        @elseif($activity->status === 'approved')
                                            <form action="{{ route('admin.volunteer-activities.complete', $activity->id) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmAction('complete', this, '{{ $activity->volunteer_name }}', '{{ $activity->activity_name }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-flag-checkered me-1"></i> Selesai
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="display: block;"></i>
                    <p class="text-muted">Belum ada pendaftaran kegiatan relawan</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmAction(action, form, volunteerName, activityName) {
            let actionText = '';
            let confirmText = '';
            let confirmButtonColor = '#28a745';

            if (action === 'approve') {
                actionText = 'Setujui';
                confirmText = `Setujui pendaftaran ${volunteerName} untuk kegiatan ${activityName}?`;
                confirmButtonColor = '#28a745';
            } else if (action === 'reject') {
                actionText = 'Tolak';
                confirmText = `Tolak pendaftaran ${volunteerName} untuk kegiatan ${activityName}?`;
                confirmButtonColor = '#dc3545';
            } else if (action === 'complete') {
                actionText = 'Selesaikan';
                confirmText = `Tandai kegiatan ${activityName} sebagai selesai?`;
                confirmButtonColor = '#17a2b8';
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: actionText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function showDetailFromButton(btn) {
            const data = {
                volunteer: btn.dataset.volunteer,
                email: btn.dataset.email,
                phone: btn.dataset.phone,
                activity: btn.dataset.activity,
                location: btn.dataset.location,
                date: btn.dataset.date,
                registered: btn.dataset.registered,
                motivation: btn.dataset.motivation,
                emergency_name: btn.dataset.emergencyName,
                emergency_phone: btn.dataset.emergencyPhone,
                transportation: btn.dataset.transportation,
                status_label: btn.dataset.statusLabel,
            };

            const transportMap = {
                motor: 'Motor',
                mobil: 'Mobil',
                ojek: 'Ojek/Online',
                umum: 'Transportasi Umum'
            };

            document.getElementById('detailVolunteer').textContent = data.volunteer;
            document.getElementById('detailEmail').textContent = data.email;
            document.getElementById('detailPhone').textContent = data.phone || '-';
            document.getElementById('detailActivity').textContent = data.activity;
            document.getElementById('detailLocation').textContent = data.location;
            document.getElementById('detailDate').textContent = data.date;
            document.getElementById('detailRegistered').textContent = data.registered;
            document.getElementById('detailStatus').textContent = data.status_label;
            document.getElementById('detailMotivation').textContent = data.motivation || '-';
            document.getElementById('detailEmergency').textContent = data.emergency_name || '-';
            document.getElementById('detailEmergencyPhone').textContent = data.emergency_phone || '-';
            document.getElementById('detailTransport').textContent = transportMap[data.transportation] || data.transportation || '-';

            const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
            modal.show();
        }
    </script>
@endsection

@push('modals')
<div class="modal fade" id="activityDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran Relawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Relawan</label>
                        <p class="mb-2" id="detailVolunteer"></p>
                        <small class="text-muted" id="detailEmail"></small><br>
                        <small class="text-muted" id="detailPhone"></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <p class="mb-0" id="detailStatus"></p>
                        <small class="text-muted">Tanggal Daftar: <span id="detailRegistered"></span></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kegiatan</label>
                        <p class="mb-1" id="detailActivity"></p>
                        <small class="text-muted" id="detailLocation"></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Kegiatan</label>
                        <p class="mb-0" id="detailDate"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Motivasi Mengikuti</label>
                        <div class="border rounded p-3 bg-light" id="detailMotivation" style="min-height:80px;"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kontak Darurat</label>
                        <p class="mb-1" id="detailEmergency"></p>
                        <small class="text-muted">No. Telepon: <span id="detailEmergencyPhone"></span></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Transportasi ke Lokasi</label>
                        <p class="mb-0" id="detailTransport"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endpush
