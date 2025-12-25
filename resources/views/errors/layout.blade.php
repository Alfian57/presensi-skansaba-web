<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | {{ config('app.name') }}</title>
    <link rel="icon" href="/img/icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('/img/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .error-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .error-icon i {
            font-size: 50px;
            color: white;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .error-code {
            font-size: 80px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 10px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .error-message {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-back {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .btn-back:hover {
            background: #667eea;
            color: white;
        }

        .school-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .school-info img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .school-info span {
            font-size: 14px;
            color: #888;
        }

        .buttons-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        /* Error type specific colors */
        .error-icon.error-401 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .error-icon.error-403 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .error-icon.error-404 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .error-icon.error-419 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .error-icon.error-429 { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .error-icon.error-500 { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); }
        .error-icon.error-503 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        @media (max-width: 576px) {
            .error-container {
                padding: 30px 20px;
            }

            .error-code {
                font-size: 60px;
            }

            .error-title {
                font-size: 20px;
            }

            .error-icon {
                width: 100px;
                height: 100px;
            }

            .error-icon i {
                font-size: 40px;
            }

            .buttons-container {
                flex-direction: column;
            }

            .btn-back {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon @yield('icon-class')">
            <i class="@yield('icon')"></i>
        </div>
        
        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        
        <div class="buttons-container">
            <a href="{{ url('/') }}" class="btn-home">
                <i class="fas fa-home"></i> Halaman Utama
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="school-info">
            <img src="/img/logo2.png" alt="Logo" onerror="this.style.display='none'">
            <span>SMKN 1 Bantul - Sistem Presensi</span>
        </div>
    </div>
</body>
</html>
