<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>NutriShare - Password Reset OTP</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }
        .brand {
            font-size: 24px;
            font-weight: 700;
            color: #38bdf8;
            text-align: center;
            margin-bottom: 24px;
        }
        h2 {
            font-size: 20px;
            font-weight: 600;
            color: #f1f5f9;
            margin-top: 0;
            text-align: center;
        }
        p {
            font-size: 14px;
            line-height: 1.6;
            color: #94a3b8;
            text-align: center;
        }
        .otp-box {
            background: #0f172a;
            border: 2px dashed #38bdf8;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #38bdf8;
            margin: 28px 0;
            font-family: monospace;
        }
        .footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="brand">🌾 NutriShare</div>
        <h2>Password Reset Verification Code</h2>
        <p>You requested to reset your password for account <strong>{{ $email }}</strong>. Use the 6-digit verification code below to complete your reset request:</p>
        
        <div class="otp-box">{{ $otp }}</div>
        
        <p>This code will expire in <strong>10 minutes</strong>. If you did not request a password reset, please ignore this email.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} NutriShare. Secure Food Sharing Platform.
        </div>
    </div>
</body>
</html>
