    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Bem-vindo - Pendulo | Radar')
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

    .welcome-section {
        text-align: center;
        margin-bottom: 50px;
    }

    .welcome-section h2 {
        color: #343b71;
        font-size: 2.2em;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .welcome-section p {
        color: #666;
        font-size: 1.15em;
    }

    .content-wrapper {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .choice-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 35px;
        margin-top: 40px;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 30px 15px;
        }

        .welcome-section h2 {
            font-size: 1.8em;
        }

        .welcome-section p {
            font-size: 1em;
        }

        .choice-container {
            grid-template-columns: 1fr;
            gap: 25px;
        }
    }

    .choice-card {
        background: white;
        border-radius: 20px;
        padding: 45px 35px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(52, 59, 113, 0.12);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .choice-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #343b71, #667eea);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .choice-card:hover::before {
        transform: scaleX(1);
    }

    .choice-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 15px 40px rgba(52, 59, 113, 0.25);
        border-color: #343b71;
    }

    .choice-icon {
        font-size: 5.5em;
        margin-bottom: 25px;
        color: #343b71;
        filter: drop-shadow(0 4px 8px rgba(52, 59, 113, 0.2));
        transition: transform 0.3s ease;
    }

    .choice-card:hover .choice-icon {
        transform: scale(1.1);
    }

    .choice-card h2 {
        color: #343b71;
        font-size: 2em;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .choice-card p {
        color: #666;
        font-size: 1.05em;
        line-height: 1.7;
    }

    .feature-badge {
        display: inline-block;
        background: rgba(52, 59, 113, 0.08);
        color: #343b71;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .welcome-section h2 {
            font-size: 1.8em;
        }

        .welcome-section p {
            font-size: 1em;
        }

        .choice-container {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .choice-card {
            padding: 35px 25px;
        }

        .choice-icon {
            font-size: 4.5em;
        }

        .choice-card h2 {
            font-size: 1.7em;
        }
    }

    @media (max-width: 480px) {
        .choice-card h2 {
            font-size: 1.5em;
        }

        .choice-card p {
            font-size: 0.95em;
        }
    }
</style>
@endsection

@section('content')

<div class="content-wrapper">
    <div class="card">
        <div class="welcome-section">
            <h2>Como você deseja utilizar o sistema?</h2>
            <p>Escolha uma das opções abaixo para continuar</p>
        </div>

        <div class="choice-container">
            <a href="{{ route('passenger.index') }}" class="choice-card">
                <div class="choice-icon">🧳</div>
                <h2>Sou Passageiro</h2>
                <p>Reserve sua passagem informando nome, horário e local de embarque</p>
                <span class="feature-badge">Gestão de Reservas</span>
            </a>

            <a href="{{ route('driver.index') }}" class="choice-card">
                <div class="choice-icon">🚗</div>
                <h2>Sou Motorista</h2>
                <p>Visualize os passageiros reservados no mapa com filtros por data e horário</p>
                <span class="feature-badge">Mapa em Tempo Real</span>
            </a>
        </div>
    </div>
</div>
@endsection
