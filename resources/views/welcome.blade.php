<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriShare — Zero Hunger</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --apple-bg: #000000;
            --apple-text: #f5f5f7;
            --apple-text-muted: #86868b;
            --apple-accent: #2997ff;
            --apple-accent-hover: #0071e3;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--apple-bg);
            color: var(--apple-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }
        
        /* Subtle glow in background */
        .glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(41,151,255,0.1) 0%, rgba(0,0,0,0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 900px;
            padding: 2rem;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUpFade 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideUpFade {
            to { transform: translateY(0); opacity: 1; }
        }
        
        .badge-top {
            display: inline-block;
            color: var(--apple-text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 6px 16px;
            border-radius: 980px;
            animation: fadeIn 1.5s ease forwards;
            opacity: 0;
        }
        @keyframes fadeIn { to { opacity: 1; } }

        h1 {
            font-size: 5rem;
            font-weight: 700;
            letter-spacing: -0.04em;
            line-height: 1.05;
            margin-bottom: 1.5rem;
            color: var(--apple-text);
        }
        h1 span {
            background: linear-gradient(90deg, #2997ff, #32d74b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            font-size: 1.5rem;
            color: var(--apple-text-muted);
            font-weight: 400;
            letter-spacing: -0.01em;
            line-height: 1.4;
            max-width: 600px;
            margin: 0 auto 3rem auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .btn {
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 980px;
            font-size: 1.05rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: var(--apple-text);
            color: var(--apple-bg);
        }
        .btn-primary:hover {
            background-color: #d1d1d6;
            transform: scale(1.02);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--apple-text);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            h1 { font-size: 3.5rem; }
            .subtitle { font-size: 1.25rem; }
            .cta-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="glow"></div>
    
    <div class="container">
        <div class="badge-top">SDG 2: Zero Hunger</div>
        <h1>Redistribute. <br><span>NutriShare.</span></h1>
        <p class="subtitle">A brilliantly simple platform connecting food donors with NGOs to instantly eliminate surplus food waste.</p>
        
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn btn-primary">Join the Movement</a>
            <a href="{{ route('login') }}" class="btn btn-outline">Sign In</a>
        </div>
    </div>
</body>
</html>
