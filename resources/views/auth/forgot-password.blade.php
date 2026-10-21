<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SORA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #eef2f6;
            --shadow-light: #ffffff;
            --shadow-dark: #a6b4c8;
            --text-color: #1e293b;
            --btn-color: #0284c7;
            --btn-gradient: linear-gradient(135deg, #0284c7, #38bdf8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-color);
            padding: 20px;
        }

        .card {
            width: 100%;
            max-width: 480px;
            background-color: var(--bg-color);
            border-radius: 24px;
            box-shadow: 12px 12px 24px var(--shadow-dark), -12px -12px 24px var(--shadow-light);
            padding: 45px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--bg-color);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--btn-color);
            font-size: 32px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        p.subtitle {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        input {
            background-color: var(--bg-color);
            border: 2px solid transparent;
            padding: 14px 18px;
            margin: 10px 0 16px;
            width: 100%;
            border-radius: 12px;
            outline: none;
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            color: var(--text-color);
            font-size: 14px;
            transition: all 0.3s ease;
            text-align: center;
            letter-spacing: 0.5px;
        }

        input:focus {
            border: 2px solid var(--btn-color);
            box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
        }

        button {
            border-radius: 25px;
            border: none;
            background: var(--btn-gradient);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            padding: 14px 40px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
            box-shadow: 4px 4px 12px rgba(2, 132, 199, 0.35);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 16px rgba(2, 132, 199, 0.45);
        }

        button:active {
            transform: scale(0.98);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            margin-top: 24px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .back-link:hover {
            color: var(--btn-color);
            transform: translateX(-3px);
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.25);
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
            line-height: 1.5;
        }

        .alert-error i {
            margin-right: 6px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="icon-circle">
        <i class="fas fa-key"></i>
    </div>

    <h1>Lupa Password?</h1>
    <p class="subtitle">
        Masukkan nomor <strong>NPK</strong> Anda. Sistem akan membuatkan password baru dan langsung mengirimkannya ke alamat email yang terdaftar.
    </p>

    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" onsubmit="document.getElementById('btnSubmit').disabled=true; document.getElementById('btnSubmit').innerText='Mengirim...';">
        @csrf

        <input 
            type="text" 
            name="npk" 
            value="{{ old('npk') }}" 
            placeholder="Ketik NPK Anda (cth. 12345)" 
            required 
            autofocus 
        />

        <button type="submit" id="btnSubmit">
            <i class="fas fa-paper-plane"></i>
            <span>Kirim Password Baru</span>
        </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Halaman Login</span>
    </a>
</div>

</body>
</html>
