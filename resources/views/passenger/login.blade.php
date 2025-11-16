    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Acessar Reserva')
@section('header', '')

@section('styles')
<style>
    body {
        background: #e8f0ff;
    }

    .modern-header {
        width: 100%;
        background: #343b71;
        padding: 15px 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        margin: 0;
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
        .modern-header {
            padding: 12px 15px;
        }

        .header-content {
            gap: 15px;
            padding: 0 15px;
        }

        .header-logo {
            max-height: 70px;
        }

        .header-title {
            font-size: 1.5em;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .modern-header {
            padding: 10px 15px;
        }

        .header-logo {
            max-height: 50px;
        }

        .header-title {
            font-size: 1.2em;
        }
    }

    .content-wrapper {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #343b71;
        text-decoration: none;
        font-weight: bold;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .login-card {
        max-width: 500px;
        margin: 0 auto;
    }

    h2 {
        color: #343b71;
        font-size: 2em;
        margin-bottom: 20px;
        text-align: center;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    @media (max-width: 768px) {
        .login-card {
            max-width: 100%;
        }

        h2 {
            font-size: 1.5em;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.3em;
        }
    }
</style>
@endsection

@section('content')
<div class="modern-header">
    <div class="header-content">
        <img src="{{ asset('logos/pendulo_transparent.png') }}" alt="Pendulo Radar" class="header-logo">
        <div class="header-title">
            <span class="header-divider">|</span>
            <span class="header-subtitle">Reservas</span>
        </div>
    </div>
</div>

<div class="content-wrapper">
<div class="card login-card">
    <a href="{{ route('home') }}" class="back-link">← Voltar</a>

    <h2 style="color: #343b71; margin-bottom: 20px; text-align: center;">
        🔐 Fazer Login
    </h2>

    <p style="text-align: center; color: #666; margin-bottom: 30px;">
        Use seu email e senha para acessar suas reservas
    </p>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('passenger.authenticate') }}" method="POST" id="loginForm">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                value="{{ old('email') }}"
                placeholder="seu@email.com"
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Digite sua senha"
                minlength="4"
                maxlength="8"
            >
        </div>

        <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
            Entrar
        </button>
    </form>

    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
        <p style="color: #666; margin-bottom: 10px;">Ainda não tem cadastro?</p>
        <a href="{{ route('passenger.register') }}" class="btn btn-secondary">
            Criar Acesso
        </a>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    // No special masks needed for email/password
</script>
@endsection
