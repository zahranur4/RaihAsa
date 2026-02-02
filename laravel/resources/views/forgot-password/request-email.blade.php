<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - RaihAsa</title>
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

    .forgot-password-container {
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
</style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Lupa Password?</h3>
            <p class="text-muted-sm">Masukkan email Anda untuk mengatur ulang password</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('forgot-password.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Lanjutkan</button>
            </form>

            <div class="back-link">
                <p>Sudah ingat password Anda? <a href="{{ route('login') }}">Kembali ke login</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
