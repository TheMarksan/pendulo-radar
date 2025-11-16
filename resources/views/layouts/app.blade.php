<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pêndulo Radar')</title>
    <link rel="icon" type="image/png" href="{{ asset('logos/pendulo_back_logo.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e8f0ff;
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 30px 0;
            background: rgba(255, 255, 255, 0.95);
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: none; /* Hidden by default, pages can override */
        }

        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }

        .header h1 {
            color: #343b71;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #343b71;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-align: center;
            word-wrap: break-word;
            white-space: normal;
        }

        .btn:hover {
            background: #4a5294;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 59, 113, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #343b71;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #343b71;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Responsividade Global */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }

            .card {
                padding: 20px;
                border-radius: 12px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 1em;
                width: 100%;
                max-width: 100%;
            }

            .form-group input,
            .form-group textarea,
            .form-group select {
                padding: 10px;
                font-size: 0.95em;
            }

            .container > div {
                padding: 15px !important;
            }

            /* Fix text overflow */
            h1, h2, h3, h4, h5, h6, p, div, span {
                word-wrap: break-word;
                overflow-wrap: break-word;
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.5em;
            }

            .card {
                padding: 15px;
            }

            .btn {
                padding: 10px 15px;
                font-size: 0.95em;
                width: 100%;
            }

            .alert {
                padding: 12px;
                font-size: 0.9em;
            }
        }

        .modern-header {
            width: 100%;
            background: #343b71;
            padding: 15px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            margin: 0 0 30px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-content {
            display: flex;
            align-items: center;
            gap: 25px;
            max-width: 1200px;
            width: 100%;
            padding: 0 30px;
        }
        .header-logo {
            height: 100%;
            max-height: 100px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }
        .header-title {
            color: white;
            font-size: 2.2em;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .header-divider {
            color: rgba(255, 255, 255, 0.4);
            font-weight: 300;
            font-size: 1.2em;
        }
        .header-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
        }
        @media (max-width: 768px) {
            .modern-header { padding: 12px 15px; }
            .header-content { gap: 15px; padding: 0 15px; }
            .header-logo { max-height: 70px; }
            .header-title { font-size: 1.5em; gap: 10px; }
        }
        @media (max-width: 480px) {
            .modern-header { padding: 10px 15px; }
            .header-logo { max-height: 50px; }
            .header-title { font-size: 1.2em; }
        }
    </style>
    @yield('styles')
</head>
    <body>
    <div id="toast-container" style="position: fixed; top: 30px; right: 30px; z-index: 9999; max-width: 90vw; width: auto;"></div>
    <div class="container">
        @if(trim($__env->yieldContent('header')))
        <div class="header">
            <img src="{{ asset('logos/logo.png') }}" alt="Pendulo Radar" class="logo" onerror="this.style.display='none'">
            <h1>@yield('header')</h1>
        </div>
        @endif

        <div class="modern-header">
            <div class="header-content">
                <img src="{{ asset('logos/pendulo_transparent.png') }}" alt="Pendulo Radar" class="header-logo">
                <div class="header-title">
                    <span class="header-divider">|</span>
                    <span class="header-subtitle">Reservas</span>
                </div>
            </div>
        </div>

        <div style="padding: 20px;">


        @yield('content')
        </div>
    </div>

    <footer style="width:100%;text-align:center;padding:24px 0 12px 0;color:#343b71;font-size:1.05em;opacity:0.85;">
        <div style="margin-bottom:4px;">Powered by <b>Pendulo</b></div>
        <div style="font-size:0.95em;color:#666;">Projeto com fins acadêmicos</div>
    </footer>

    @stack('scripts')
    @yield('scripts')
    <script>
    // Toast global
    function showToast(message, type = 'success', duration = 3500) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.style.cssText = `
            min-width: 220px;
            max-width: 350px;
            margin-bottom: 16px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: #fff;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            font-size: 1.1em;
            font-weight: 500;
            opacity: 0.97;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.4s;
        `;
        toast.innerHTML = (type === 'success' ? '✅' : '⚠️') + ' ' + message;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = 0;
            setTimeout(() => toast.remove(), 500);
        }, duration);
    }
    window.showToast = showToast;
    </script>
    <style>
    @media (max-width: 600px) {
        #toast-container {
            top: 10px !important;
            right: 0 !important;
            left: 0 !important;
            margin: 0 auto !important;
            width: 98vw !important;
            max-width: 98vw !important;
            padding: 0 1vw;
        }
        .toast-message {
            min-width: 0 !important;
            max-width: 98vw !important;
            font-size: 1em !important;
            padding: 12px 10px !important;
        }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 0.97; transform: translateY(0); }
    }
    </style>
</body>
</html>
