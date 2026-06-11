<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventarisPro - Sistem Inventaris QR Code</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .welcome-card {
            background: white;
            max-width: 600px;
            width: 100%;
            padding: 50px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        .logo-icon {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .logo-icon i { font-size: 40px; color: #FFD700; }
        h1 { font-size: 2rem; margin-bottom: 10px; }
        .btn-login {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <div class="logo-icon"><i class="fas fa-qrcode"></i></div>
        <h1>Inventaris<span style="color:#4361ee;">Pro</span></h1>
        <p>Sistem Pendataan Inventaris Barang dengan QR Code</p>
        <p>Politeknik Negeri Batam - TA 2026</p>
        <a href="{{ route('login') }}" class="btn-login">Login ke Sistem</a>
    </div>
</body>
</html>