<!DOCTYPE html>
<html>
<head>
    <title>Notification of SORA Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #0284c7; margin-bottom: 5px;">SORA</h1>
        <p style="color: #64748b; margin-top: 0;">Employee Journey System</p>
    </div>

    <div style="background-color: #fff7ed; border: 1px solid #fdba74; border-radius: 8px; padding: 25px;">
        <h2 style="margin-top: 0; color: #9a3412;">Hello, {{ $user->name }}!</h2>
        
        <p>Your request to reset your SORA account password has been successfully processed. Below is your new login information:</p>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 6px; border: 1px dashed #fb923c; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Login URL:</strong> <a href="{{ url('/login') }}" style="color: #ea580c;">{{ url('/login') }}</a></p>
            <p style="margin: 5px 0;"><strong>NPK (Username):</strong> {{ $user->npk }}</p>
            <p style="margin: 5px 0;"><strong>New Password:</strong> {{ $newPassword }}</p>
        </div>
        
        <p style="color: #ef4444; font-size: 14px;"><strong>Security Warning:</strong> Log in immediately to ensure you can access the system. Keep this password confidential!</p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/login') }}" style="background-color: #ea580c; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Login with New Password</a>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #94a3b8; font-size: 12px;">
        <p>This email was sent automatically by the SORA system. Please do not reply to this email.</p>
    </div>

</body>
</html>
