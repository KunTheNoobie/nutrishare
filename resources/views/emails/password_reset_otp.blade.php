<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriShare - Password Reset OTP</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #000000;
            color: #f5f5f7;
            margin: 0;
            padding: 40px 20px;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background-color: #161618;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 36px 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
        }
        .brand-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .brand-icon {
            color: #2997ff;
        }
        h2 {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 12px 0;
            text-align: center;
            letter-spacing: -0.3px;
        }
        p {
            font-size: 14px;
            line-height: 1.5;
            color: #86868b;
            text-align: center;
            margin: 0 0 24px 0;
        }
        .user-email {
            color: #f5f5f7;
            font-weight: 600;
        }
        .otp-container {
            background-color: #1d1d1f;
            border: 2px dashed #2997ff;
            border-radius: 14px;
            padding: 22px 16px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-code {
            font-family: "SF Mono", "Consolas", "Courier New", monospace;
            font-size: 38px;
            font-weight: 700;
            letter-spacing: 12px;
            color: #2997ff;
            text-indent: 12px; /* Offset spacing */
            margin: 0;
        }
        .expiry-notice {
            font-size: 13px;
            color: #86868b;
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 12px;
            color: #6e6e73;
            text-align: center;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="brand-header">
            <div class="brand-title">
                <span class="brand-icon">🌾</span> NutriShare
            </div>
        </div>
        
        <h2>Password Reset Code</h2>
        
        <p>
            You requested to reset your password for <span class="user-email">{{ $email }}</span>.
            Enter the 6-digit verification code below to proceed:
        </p>

        <div class="otp-container">
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <div class="expiry-notice">
            This code will expire in <strong>10 minutes</strong>. If you did not request a password reset, you can safely ignore this message.
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} NutriShare — Surplus Food Redistribution Platform.<br>
            Empowering Zero Hunger (SDG 2).
        </div>
    </div>
</body>
</html>
