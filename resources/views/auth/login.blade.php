<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SPPG Punggur Besar</title>
    
    <!-- Impor Google Fonts untuk Tampilan Premium -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border: 3px solid #0091ff; /* Crisp blue border matching screenshot */
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            padding: 45px 35px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .logo-circle {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 12px;
            color: #1f2937;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 22px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            font-size: 14px;
            color: #1f2937;
            background-color: #fafafa;
            border: 1px solid #d1c4c4; /* Soft reddish-grey border matching screenshot */
            border-radius: 8px;
            outline: none;
            transition: all 0.25s ease;
        }

        .form-input::placeholder {
            color: #9ca3af;
            font-style: normal;
        }

        .form-input:focus {
            border-color: #0091ff;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 145, 255, 0.15);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            font-weight: 500;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #1d2130; /* Dark charcoal/navy button matching screenshot */
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #11141e;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29, 33, 48, 0.2);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: left;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo Badan Gizi Nasional -->
        <div class="logo-container">
            <div class="logo-circle">
                <img src="{{ asset('img/logo-badan-gizi.jpg') }}" alt="Logo Badan Gizi Nasional" class="logo-img">
            </div>
        </div>

        <!-- Judul & Subjudul -->
        <h1 class="title">Selamat Datang</h1>
        <p class="subtitle">SPPG Punggur Besar</p>

        <!-- Session Status (Misal setelah logout atau reset password) -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- General Errors (misal rate limiting) -->
        @if ($errors->has('throttle'))
            <div class="alert alert-danger">
                {{ $errors->first('throttle') }}
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Input Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') is-invalid @enderror" 
                    placeholder="Masukkan Email anda" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="email"
                >
                @error('email')
                    <div class="error-message">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="display:inline-block; vertical-align:middle;">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Input Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') is-invalid @enderror" 
                    placeholder="Masukkan password anda" 
                    required 
                    autocomplete="current-password"
                >
                @error('password')
                    <div class="error-message">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="display:inline-block; vertical-align:middle;">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="submit-btn">Masuk</button>
        </form>
    </div>

</body>
</html>
