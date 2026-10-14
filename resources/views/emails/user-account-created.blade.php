<!DOCTYPE html>
<html>
<head>
    <title>Informasi Akun Login SORA</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #0284c7; margin-bottom: 5px;">SORA</h1>
        <p style="color: #64748b; margin-top: 0;">Employee Journey System</p>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px;">
        <h2 style="margin-top: 0; color: #1e293b;">Halo, {{ $user->name }}!</h2>
        
        <p>Akun SORA Anda telah berhasil dibuat. Berikut adalah informasi login Anda:</p>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 6px; border: 1px dashed #cbd5e1; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>URL Login:</strong> <a href="{{ url('/login') }}" style="color: #0284c7;">{{ url('/login') }}</a></p>
            <p style="margin: 5px 0;"><strong>NPK (Username):</strong> {{ $user->npk }}</p>
            <p style="margin: 5px 0;"><strong>Password:</strong> {{ $password }}</p>
        </div>
        
        <p style="color: #ef4444; font-size: 14px;"><strong>Penting:</strong> Jaga kerahasiaan informasi login ini. Jangan membagikan password Anda kepada siapapun.</p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/login') }}" style="background-color: #0284c7; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Login Sekarang</a>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #94a3b8; font-size: 12px;">
        <p>Email ini dikirim secara otomatis oleh sistem SORA. Harap tidak membalas email ini.</p>
    </div>

</body>
</html>
