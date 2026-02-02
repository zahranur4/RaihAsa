<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reset-password-container {
        width: 100%;
        max-width: 450px;
    }

    .card {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 10px;
    }

    .card-body {
        padding: 40px;
    }

    .card-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 28px;
    }

    .text-muted-sm {
        color: #999;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-text {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 12px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 6px;
        width: 100%;
        cursor: pointer;
        transition: transform 0.2s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .text-danger {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .back-link {
        text-align: center;
        margin-top: 20px;
    }

    .back-link a {
        color: #667eea;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link a:hover {
        text-decoration: underline;
    }

    .alert {
        border-radius: 6px;
        margin-bottom: 20px;
        padding: 12px 15px;
        font-size: 14px;
    }

    .email-display {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
        border-left: 4px solid #667eea;
    }

    .email-display strong {
        color: #333;
    }
</style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Atur Ulang Password</h3>
            <p class="text-muted-sm">Masukkan password baru Anda</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="email-display">
                <strong>Email:</strong> {{ $email }}
            </div>

            <form action="{{ route('reset-password.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter, huruf & angka" required>
                    <small class="form-text">Password harus minimal 8 karakter dan mengandung huruf serta angka</small>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru Anda" required>
                    @error('password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Ubah Password</button>
            </form>

            <div class="back-link">
                <p><a href="{{ route('login') }}">Kembali ke login</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
