<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penawaran Donasi - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/components.css'])
</head>
<body>
    <!-- Header with Navigation -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" height="40" class="me-2">
                    <span>RaihAsa</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <!-- Success Message -->
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Penawaran Donasi Berhasil Dibuat!</strong>
                        <p class="mb-0 mt-2">Panti penerima akan meninjau penawaran Anda. Anda akan menerima notifikasi ketika mereka merespons.</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Pledge Details Card -->
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i> Detail Penawaran Donasi</h4>
                        </div>
                        <div class="card-body p-5">
                            <!-- Pledge Status -->
                            @php
                                $statusMap = [
                                    'pending' => ['label' => 'Pending', 'class' => 'bg-warning', 'desc' => 'Menunggu konfirmasi dari panti'],
                                    'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-info', 'desc' => 'Panti menerima penawaran Anda'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-success', 'desc' => 'Donasi selesai diterima'],
                                    'cancelled' => ['label' => 'Ditolak', 'class' => 'bg-danger', 'desc' => 'Donasi ditolak oleh panti'],
                                ];
                                $currentStatus = $statusMap[$pledge->status] ?? ['label' => ucfirst($pledge->status), 'class' => 'bg-secondary', 'desc' => 'Status tidak dikenal'];
                            @endphp
                            <div class="alert alert-info mb-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div class="col">
                                        <strong>Status:</strong> 
                                        <span class="badge {{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
                                        <br>
                                        <small class="text-muted">{{ $currentStatus['desc'] }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Donor Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-user me-2 text-primary"></i> Informasi Donor</h5>
                                    <p>
                                        <strong>Nama:</strong><br>
                                        {{ Auth::user()->nama }}
                                    </p>
                                    <p>
                                        <strong>Email:</strong><br>
                                        {{ Auth::user()->email }}
                                    </p>
                                    <p>
                                        <strong>Telepon:</strong><br>
                                        {{ Auth::user()->nomor_telepon ?? '-' }}
                                    </p>
                                </div>

                                <!-- Donation Details -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-gift me-2 text-success"></i> Detail Donasi</h5>
                                    <p>
                                        <strong>Barang yang Ditawarkan:</strong><br>
                                        {{ $pledge->item_offered }}
                                    </p>
                                    <p>
                                        <strong>Jumlah:</strong><br>
                                        {{ $pledge->quantity_offered }} unit
                                    </p>
                                    <p>
                                        <strong>Tanggal Penawaran:</strong><br>
                                        {{ $pledge->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            @if($pledge->notes)
                            <div class="mb-4 p-3 bg-light rounded">
                                <strong><i class="fas fa-sticky-note me-2"></i> Catatan:</strong><br>
                                {{ $pledge->notes }}
                            </div>
                            @endif

                            <!-- Recipient Info -->
                            <div class="card bg-light mt-4">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-home me-2 text-info"></i> Penerima</h5>
                                    @if($panti)
                                    <p class="mb-2">
                                        <strong>{{ $panti->nama_panti }}</strong>
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i> {{ $panti->alamat_lengkap }}, {{ $panti->kota }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-phone me-2"></i> {{ $panti->no_telepon ?? '-' }}
                                    </p>
                                    @else
                                    <p class="text-muted">Informasi penerima tidak tersedia</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Wishlist Item -->
                            @if($wishlist)
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-list me-2 text-warning"></i> Kebutuhan yang Dicocokkan</h5>
                                    <p class="mb-2">
                                        <strong>Nama Barang:</strong><br>
                                        {{ $wishlist->nama_barang }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Kategori:</strong><br>
                                        <span class="badge bg-info">{{ $wishlist->kategori }}</span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Jumlah Dibutuhkan:</strong><br>
                                        {{ $wishlist->jumlah }} unit
                                    </p>
                                    <p class="mb-0">
                                        <strong>Urgensi:</strong><br>
                                        <span class="badge {{ $wishlist->urgensi === 'mendesak' || $wishlist->urgensi === 'kesehatan' ? 'bg-danger' : 'bg-warning' }}">
                                            {{ ucfirst($wishlist->urgensi) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @endif

                            <!-- Timeline -->
                            <div class="mt-5">
                                <h5 class="mb-4"><i class="fas fa-timeline me-2"></i> Tahapan Proses</h5>
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $pledge->status !== 'pending' ? 'completed' : 'active' }}">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Penawaran Dibuat</h6>
                                            <p class="text-muted small">{{ $pledge->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $pledge->status === 'cancelled' ? 'cancelled' : ($pledge->confirmed_at ? 'completed' : 'pending') }}">
                                            <i class="fas fa-hourglass-end"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Konfirmasi Panti</h6>
                                            @if($pledge->status === 'cancelled')
                                                <p class="text-muted small">Ditolak oleh panti</p>
                                            @else
                                                <p class="text-muted small">{{ $pledge->confirmed_at ? $pledge->confirmed_at->format('d M Y H:i') : 'Menunggu...' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $pledge->status === 'cancelled' ? 'cancelled' : ($pledge->completed_at ? 'completed' : 'pending') }}">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>{{ $pledge->status === 'cancelled' ? 'Donasi Dibatalkan' : 'Donasi Selesai' }}</h6>
                                            @if($pledge->status === 'cancelled')
                                                <p class="text-muted small">Ditolak oleh panti</p>
                                            @else
                                                <p class="text-muted small">{{ $pledge->completed_at ? $pledge->completed_at->format('d M Y H:i') : 'Menunggu...' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-5 d-flex gap-2">
                                <a href="{{ route('wishlist') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Wishlist
                                </a>
                                @if($pledge->status === 'pending')
                                <form action="{{ route('wishlist.pledge.confirm', $pledge->id_pledge) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i> Penuhi Sekarang
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.pledge.cancel', $pledge->id_pledge) }}" method="POST" style="display: inline;" onsubmit="event.preventDefault(); Swal.fire({title: 'Konfirmasi', text: 'Apakah Anda yakin ingin membatalkan penawaran donasi ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Batalkan!', cancelButtonText: 'Tidak'}).then((result) => {if (result.isConfirmed) {this.submit();}})">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-2"></i> Batalkan Penawaran
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('wishlist.matching') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Cari Donasi Lagi
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="text-center">
                <p>&copy; 2025 RaihAsa. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <style>
        .timeline {
            position: relative;
            padding: 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 30px;
        }

        .timeline-marker {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            flex-shrink: 0;
            margin-right: 20px;
        }

        .timeline-marker.completed {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .timeline-marker.active {
            background: #ffc107;
            animation: pulse 2s infinite;
        }

        .timeline-marker.pending {
            background: #e9ecef;
            color: #6c757d;
        }

        .timeline-marker.cancelled {
            background: #dc3545;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .timeline-content {
            padding-top: 5px;
        }

        .timeline-content h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
