    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Passageiro')
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

    .choice-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .choice-card {
        background: white;
        border-radius: 15px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 3px solid transparent;
    }

    .choice-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(52, 59, 113, 0.3);
        border-color: #343b71;
    }

    .choice-icon {
        font-size: 5em;
        margin-bottom: 20px;
        color: #343b71;
    }

    .choice-card h2 {
        color: #343b71;
        font-size: 2em;
        margin-bottom: 15px;
    }

    .choice-card p {
        color: #666;
        font-size: 1.1em;
        line-height: 1.6;
    }

    h2 {
        text-align: center;
        color: #343b71;
        font-size: 2em;
        margin-bottom: 20px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    @media (max-width: 768px) {
        .choice-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .choice-card {
            padding: 30px 25px;
        }

        .choice-icon {
            font-size: 4em;
        }

        .choice-card h2 {
            font-size: 1.7em;
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
<div class="card">
    <a href="{{ route('home') }}" class="back-link">← Voltar</a>

    <h2 style="text-align: center; color: #343b71; margin-bottom: 20px;">
        O que você deseja fazer?
    </h2>
    <p style="text-align: center; color: #666; font-size: 1.2em; margin-bottom: 40px;">
        Escolha uma das opções abaixo
    </p>

    <div class="choice-container">
        <a href="{{ route('passenger.register') }}" class="choice-card">
            <div class="choice-icon">➕</div>
            <h2>Criar Acesso</h2>
            <p>Cadastrar E-mail e senha para fazer reservas</p>
        </a>

        <a href="{{ route('passenger.login') }}" class="choice-card">
            <div class="choice-icon">🔐</div>
            <h2>Entrar</h2>
            <p>Já tenho cadastro - fazer login</p>
        </a>
    </div>
</div>
</div>
@endsection
