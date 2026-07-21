<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriShare — Surplus Food Redistribution Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        /* Floating abstract shapes */
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            animation: float 10s ease-in-out infinite;
        }
        .shape-1 {
            width: 400px; height: 400px;
            background: rgba(34, 197, 94, 0.3);
            border-radius: 50%;
            top: -100px; left: -100px;
        }
        .shape-2 {
            width: 500px; height: 500px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            bottom: -150px; right: -100px;
            animation-delay: -5s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }
        
        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 4rem;
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 800px;
            width: 90%;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideUpFade {
            to { transform: translateY(0); opacity: 1; }
        }
        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #4ade80, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }
        .subtitle {
            font-size: 1.25rem;
            color: #cbd5e1;
            margin-bottom: 3rem;
            line-height: 1.6;
            font-weight: 300;
        }
        .badge {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .btn {
            text-decoration: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            box-shadow: 0 10px 20px -10px rgba(34, 197, 94, 0.5);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -10px rgba(34, 197, 94, 0.6);
        }
        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    
    <div class="container">
        <h1>🌾 NutriShare</h1>
        <p class="subtitle">A stunning, modern platform designed to connect food donors with NGOs to redistribute surplus food efficiently. Join us in achieving <strong>SDG 2: Zero Hunger</strong>.</p>
        
        <div class="cta-buttons">
            <a href="/login" class="btn btn-outline">Sign In</a>
            <a href="/register" class="btn btn-primary">Join the Movement</a>
        </div>

        <span class="badge">Laravel 11 • MVC • Eloquent ORM</span>
    </div>
</body>
</html>
