    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Detalhes da Reserva')
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

    .success-icon {
        font-size: 5em;
        text-align: center;
        margin-bottom: 20px;
    }

    .info-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #343b71;
    }

    .info-box h3 {
        color: #343b71;
        margin-bottom: 15px;
        font-size: 1.2em;
    }

    .collapsible-box {
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #343b71;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .collapsible-header {
        padding: 15px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        transition: background 0.3s ease;
        border-bottom: 1px solid transparent;
    }

    .collapsible-header:hover {
        background: #e9ecef;
    }

    .collapsible-header:active {
        background: #dee2e6;
    }

    .collapsible-header h3 {
        margin: 0;
        color: #343b71;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .collapsible-hint {
        font-size: 0.75em;
        color: #6c757d;
        font-weight: normal;
        margin-left: 5px;
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        .collapsible-hint {
            display: none;
        }
    }

    .collapse-icon {
        font-size: 1em;
        transition: transform 0.3s ease;
        color: #343b71;
        font-weight: bold;
        min-width: 20px;
        text-align: center;
    }

    .collapse-icon.collapsed {
        transform: rotate(-90deg);
    }

    .collapsible-content {
        padding: 0 20px 20px 20px;
        max-height: 2000px;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }

    .collapsible-content.collapsed {
        max-height: 0;
        padding: 0 20px;
    }

    @media (max-width: 768px) {
        .collapsible-header {
            padding: 12px 15px;
        }

        .collapsible-header h3 {
            font-size: 1.1em;
        }

        .collapsible-content {
            padding: 0 15px 15px 15px;
        }

        .collapsible-content.collapsed {
            padding: 0 15px;
        }
    }

    .info-item {
        margin-bottom: 12px;
        font-size: 1em;
        line-height: 1.6;
    }

    .info-item strong {
        color: #343b71;
        display: inline-block;
        min-width: 150px;
    }

    .pix-box {
        background: #e7f3ff;
        border: 2px solid #0c5460;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
    }

    .pix-box h3 {
        color: #0c5460;
        margin-bottom: 15px;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pix-key-display {
        background: white;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #bee5eb;
        margin: 10px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pix-key-text {
        font-family: 'Courier New', monospace;
        font-size: 1.05em;
        color: #0c5460;
        font-weight: 600;
        word-break: break-all;
        flex: 1;
    }

    .btn-copy-pix {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9em;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .btn-copy-pix:hover {
        background: #138496;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .info-item strong {
            display: block;
            min-width: auto;
            margin-bottom: 5px;
        }

        .pix-key-display {
            flex-direction: column;
            align-items: stretch;
        }

        .pix-key-text {
            font-size: 0.95em;
            text-align: center;
        }

        .btn-copy-pix {
            width: 100%;
        }

        .pix-box {
            padding: 15px;
        }
    }

    .upload-section {
        margin-top: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    .upload-section h3 {
        color: #343b71;
        margin-bottom: 15px;
        font-size: 1.2em;
    }

    @media (max-width: 768px) {
        .upload-section {
            padding: 15px;
        }
    }

    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-upload-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-label {
        display: block;
        padding: 20px;
        background: #f8f9fa;
        border: 2px dashed #343b71;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95em;
    }

    .file-upload-label:hover {
        background: #e9ecef;
        border-color: #667eea;
    }

    .file-upload-label small {
        display: block;
        margin-top: 8px;
        font-size: 0.85em;
    }

    @media (max-width: 768px) {
        .file-upload-label {
            padding: 15px;
            font-size: 0.9em;
        }
    }

    .receipt-preview {
        max-width: 300px;
        margin: 20px auto;
        text-align: center;
    }

    .receipt-preview img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-group {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-group .btn {
        flex: 1;
        padding: 12px 20px;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            gap: 10px;
        }

        .btn-group .btn {
            width: 100%;
        }
    }

    h2 {
        text-align: center;
        color: #343b71;
        font-size: 1.8em;
        margin-bottom: 25px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    h3 {
        color: #343b71;
        margin-bottom: 15px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 1.2em;
    }

    @media (max-width: 768px) {
        .success-icon {
            font-size: 3.5em;
            margin-bottom: 15px;
        }

        .info-box {
            padding: 15px;
        }

        .btn-group {
            flex-direction: column;
            gap: 10px;
        }

        .btn-group .btn {
            width: 100%;
        }

        .info-item {
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 0.95em;
        }

        .info-item strong {
            display: block;
            min-width: auto;
            margin-bottom: 5px;
        }

        .pix-key-display {
            flex-direction: column;
            align-items: stretch;
        }

        .pix-key-text {
            font-size: 0.95em;
            text-align: center;
        }

        .btn-copy-pix {
            width: 100%;
        }

        .pix-box {
            padding: 15px;
        }

        h2 {
            font-size: 1.4em;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.1em;
        }

        .content-wrapper {
            padding: 20px 15px;
        }
    }

    @media (max-width: 480px) {
        .success-icon {
            font-size: 3em;
        }

        h2 {
            font-size: 1.2em;
        }

        h3 {
            font-size: 1em;
        }

        .info-item {
            font-size: 0.9em;
        }

        .pix-key-text {
            font-size: 0.85em;
        }

        .content-wrapper {
            padding: 15px 10px;
        }
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .modal-icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .modal-title {
        color: #343b71;
        font-size: 1.5em;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .modal-body {
        color: #666;
        line-height: 1.6;
        margin-bottom: 25px;
        font-size: 0.95em;
    }

    .modal-body p {
        margin: 10px 0;
    }

    .modal-warning {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin: 20px 0;
        border-radius: 6px;
        color: #856404;
    }

    .modal-warning strong {
        display: block;
        margin-bottom: 8px;
        font-size: 1.05em;
    }

    .modal-warning p {
        margin: 0;
        line-height: 1.5;
    }

    .modal-driver-name {
        background: #e7f3ff;
        padding: 12px;
        border-radius: 6px;
        text-align: center;
        margin: 15px 0;
        font-weight: 600;
        color: #0c5460;
        font-size: 1.1em;
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .modal-buttons button {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 1em;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-modal-confirm {
        background: #28a745;
        color: white;
    }

    .btn-modal-confirm:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .btn-modal-cancel {
        background: #6c757d;
        color: white;
    }

    .btn-modal-cancel:hover {
        background: #5a6268;
    }

    @media (max-width: 480px) {
        .modal-content {
            padding: 20px;
        }

        .modal-title {
            font-size: 1.2em;
        }

        .modal-icon {
            font-size: 2.5em;
        }

        .modal-buttons {
            flex-direction: column;
        }
    }

    /* Trip Progress Styles */
    .trip-progress-box {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        border: 2px solid #e9ecef;
    }

    .trip-progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .trip-progress-header h3 {
        margin: 0;
        color: #343b71;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .direction-badge {
        padding: 10px 28px;
        border-radius: 24px;
        font-size: 1.15em;
        font-weight: 700;
        letter-spacing: 0.5px;
        min-width: 120px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(52,59,113,0.08);
    }

    .direction-outbound {
        background: linear-gradient(90deg, #4fd1c5 0%, #4299e1 100%);
        color: #fff;
        font-weight: bold;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(79,209,197,0.08);
        border: 1.5px solid #4299e1;
        text-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }

    .direction-return {
        background: #fff3cd;
        color: #856404;
    }

    .progress-timeline {
        position: relative;
        padding-left: 40px;
    }

    .progress-stop {
        position: relative;
        padding: 15px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .progress-stop::before {
        content: '';
        position: absolute;
        left: -28px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #e9ecef;
    }

    .progress-stop:last-child::before {
        display: none;
    }

    .progress-marker {
        position: absolute;
        left: -40px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7em;
        z-index: 1;
    }

    .progress-stop.completed .progress-marker {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }

    .progress-stop.completed::before {
        background: #28a745;
    }

    .progress-stop.current .progress-marker {
        background: #ffc107;
        border-color: #ffc107;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .progress-stop-info {
        flex: 1;
    }

    .progress-stop-name {
        font-weight: 600;
        color: #343b71;
        margin-bottom: 3px;
        font-size: 0.95em;
    }

    .progress-stop-address {
        color: #666;
        font-size: 0.85em;
        line-height: 1.4;
    }

    .progress-stop.completed .progress-stop-name {
        color: #28a745;
    }

    .progress-empty {
        text-align: center;
        padding: 30px;
        color: #666;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .trip-progress-box {
            padding: 15px;
        }

        .progress-timeline {
            padding-left: 30px;
        }

        .progress-marker {
            left: -30px;
            width: 20px;
            height: 20px;
        }

        .progress-stop::before {
            left: -22px;
        }

        .progress-stop-name {
            font-size: 0.9em;
        }

        .progress-stop-address {
            font-size: 0.8em;
        }
    }
</style>
@endsection

@section('content')

<div class="content-wrapper">
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('passenger.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>
        <a href="{{ route('passenger.logout') }}" style="color: #dc3545; text-decoration: none; font-weight: bold;">
            Sair 🚪
        </a>
    </div>

    <div class="success-icon">📋</div>

    <h2 style="text-align: center; color: #343b71; margin-bottom: 30px;">
        Reserva #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
    </h2>

    <div class="collapsible-box">
        <div class="collapsible-header" onclick="toggleCollapse('reservation-data')">
            <h3>
                 Dados da Reserva
                <span class="collapsible-hint">(clique para retrair)</span>
            </h3>
            <span class="collapse-icon" id="icon-reservation-data">▼</span>
        </div>
        <div class="collapsible-content" id="content-reservation-data">
            <div class="info-item">
                <strong>Nome:</strong> {{ $reservation->name }}
            </div>
            <div class="info-item">
                <strong>Email:</strong> {{ $reservation->email }}
            </div>
            <div class="info-item">
                <strong>Data:</strong> {{ $reservation->scheduled_time->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <strong>Horário:</strong> {{ $reservation->scheduled_time_start }} - {{ $reservation->scheduled_time_end }}
            </div>
            <div class="info-item">
                <strong>Local de Embarque:</strong>
                @if($reservation->stop)
                    📍 {{ $reservation->stop->name }} - {{ $reservation->address }}
                @else
                    {{ $reservation->address }}
                @endif
            </div>
            <div class="info-item">
                <strong>Forma de Pagamento:</strong>
                @if($reservation->payment_method === 'pix')
                    💳 PIX
                @elseif($reservation->payment_method === 'dinheiro')
                    💵 Dinheiro
                @else
                    🎟️ Vale
                @endif
            </div>
            <div class="info-item">
                <strong>Código da Reserva:</strong> #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
            </div>
            @if($reservation->boarded)
            <div class="info-item" style="background: #d4edda; border-left: 4px solid #28a745; padding: 12px; margin-top: 15px; border-radius: 6px;">
                <strong>Status:</strong> <span style="color: #28a745;">✓ Embarcado</span><br>
                <small style="color: #666;">Embarque confirmado em {{ $reservation->boarded_at->format('d/m/Y H:i') }}</small>
            </div>
            @endif
        </div>
    </div>

    @if($reservation->driver)
    <div class="collapsible-box">
        <div class="collapsible-header" onclick="toggleCollapse('transport-info')">
            <h3>
                 Informações do Transporte
                <span class="collapsible-hint">(clique para retrair)</span>
            </h3>
            <span class="collapse-icon" id="icon-transport-info">▼</span>
        </div>
        <div class="collapsible-content" id="content-transport-info">
            <div class="info-item">
                <strong>Motorista:</strong> {{ $reservation->driver->name }}
            </div>
        </div>
    </div>

    @if($reservation->payment_method === 'pix' && $reservation->driver->pix_key)
    <div class="pix-box">
        <h3>💳 Chave PIX para Pagamento</h3>
        <p style="color: #0c5460; margin-bottom: 15px; font-size: 0.95em;">
            Realize o pagamento usando a chave PIX abaixo:
        </p>
        <div class="pix-key-display">
            <div class="pix-key-text" id="pix-key">
                {{ $reservation->driver->pix_key }}
            </div>
            <button onclick="copyPixKey()" class="btn-copy-pix">
                📋 Copiar Chave
            </button>
        </div>
        <p style="color: #666; font-size: 0.85em; margin-top: 10px; text-align: center;">
            ⚠️ Não esqueça de anexar o comprovante de pagamento abaixo
        </p>
    </div>
    @endif
    @endif

    <!-- Mapa -->
    <div class="info-box" style="padding: 0; overflow: hidden;">
        <div id="map" style="width: 100%; height: 400px; border-radius: 8px;"></div>
    </div>

    <style>
        @media (max-width: 768px) {
            #map {
                height: 350px !important;
            }
        }

        @media (max-width: 480px) {
            #map {
                height: 300px !important;
            }
        }
    </style>

    <!-- Trip Progress -->

    {{-- Exibir progresso para todos que têm motorista e rota definida --}}
    @if($reservation->driver && $reservation->driver->route && ($outboundStops->count() > 0 || $returnStops->count() > 0))

        <div class="trip-progress-box">
        <div class="trip-progress-header">
            <h3>🚍 Progresso da Viagem</h3>
            <span class="direction-badge {{ $currentDirection === 'outbound' ? 'direction-outbound' : 'direction-return' }}">
                {{ $currentDirection === 'outbound' ? '➡️ Ida' : '⬅️ Retorno' }}
            </span>
        </div>

    <div class="progress-timeline-wrapper" style="overflow-y: auto; max-height: 400px; width: 100%;">

    @if($currentDirection === 'outbound' && $outboundStops->count() > 0)
    <div class="progress-timeline" style="width: 100%;">
            @php
                // Encontrar a última parada confirmada
                $lastConfirmedIndex = -1;
                foreach($outboundStops as $idx => $stop) {
                    if (in_array($stop->id, $tripProgress)) {
                        $lastConfirmedIndex = $idx;
                    }
                }
            @endphp
            @foreach($outboundStops as $index => $stop)
                @php
                    // Paradas completas são as ANTES da última confirmada
                    $isCompleted = $lastConfirmedIndex >= 0 && $index < $lastConfirmedIndex;
                    // A parada atual é a última confirmada (onde o carro está agora)
                    $isCurrent = $index === $lastConfirmedIndex && $lastConfirmedIndex >= 0;

                    // Debug
                    if(config('app.debug')) {
                        echo "<!-- Stop {$index}: {$stop->name} (ID: {$stop->id}) - Completed: " . ($isCompleted ? 'YES' : 'NO') . ", Current: " . ($isCurrent ? 'YES' : 'NO') . ", In Array: " . (in_array($stop->id, $tripProgress) ? 'YES' : 'NO') . " -->";
                    }
                @endphp
                <div class="progress-stop {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                    <div class="progress-marker">
                        @if($isCompleted)
                            ✓
                        @elseif($isCurrent)
                            ●
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="progress-stop-info">
                        <div class="progress-stop-name">
                            {{ $stop->name }}
                        </div>
                        <div class="progress-stop-address">
                            {{ $stop->address }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($currentDirection === 'return' && $returnStops->count() > 0)
    <div class="progress-timeline" style="width: 100%;">
            @php
                // Encontrar a última parada confirmada
                $lastConfirmedIndex = -1;
                foreach($returnStops as $idx => $stop) {
                    if (in_array($stop->id, $tripProgress)) {
                        $lastConfirmedIndex = $idx;
                    }
                }
            @endphp
            @foreach($returnStops as $index => $stop)
                @php
                    // Paradas completas são as ANTES da última confirmada
                    $isCompleted = $lastConfirmedIndex >= 0 && $index < $lastConfirmedIndex;
                    // A parada atual é a última confirmada (onde o carro está agora)
                    $isCurrent = $index === $lastConfirmedIndex && $lastConfirmedIndex >= 0;
                @endphp
                <div class="progress-stop {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                    <div class="progress-marker">
                        @if($isCompleted)
                            ✓
                        @elseif($isCurrent)
                            ●
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="progress-stop-info">
                        <div class="progress-stop-name">
                            {{ $stop->name }}
                        </div>
                        <div class="progress-stop-address">
                            {{ $stop->address }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="progress-empty">
            Nenhuma parada definida para esta direção
        </div>
        @endif
        </div> <!-- .progress-timeline-wrapper -->
    </div>
    @endif

    @if($reservation->payment_method === 'pix')
    <div class="upload-section">
        <h3>📎 Anexar Comprovante PIX</h3>
        <p style="color: #666; margin-bottom: 15px; font-size: 0.95em;">
            Para pagamentos via PIX, é necessário anexar o comprovante de pagamento
        </p>

        @if($reservation->receipt_path)
            <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px;">
                ✓ Comprovante anexado com sucesso!
            </div>
            <div class="receipt-preview">
                @if(Str::endsWith($reservation->receipt_path, '.pdf'))
                    <p style="font-size: 3em; margin-bottom: 10px;">📄</p>
                    <p style="margin-bottom: 15px;">Arquivo PDF anexado</p>
                    <a href="{{ asset('storage/' . $reservation->receipt_path) }}" target="_blank" class="btn btn-secondary" style="display: inline-block;">
                        Ver Comprovante
                    </a>
                @else
                    <img src="{{ asset('storage/' . $reservation->receipt_path) }}" alt="Comprovante" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer;" onclick="openReceiptModal()">
                @endif
<!-- Modal para visualização ampliada do comprovante -->
<div id="receiptModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.8); align-items:center; justify-content:center;">
    <span onclick="closeReceiptModal()" style="position:absolute; top:30px; right:40px; color:#fff; font-size:2.5em; cursor:pointer; font-weight:bold;">&times;</span>
    <img id="modalReceiptImg" src="{{ asset('storage/' . $reservation->receipt_path) }}" alt="Comprovante Ampliado" style="max-width:90vw; max-height:85vh; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.4); display:block; margin:auto;">
</div>
@push('scripts')
<script>
function openReceiptModal() {
    document.getElementById('receiptModal').style.display = 'flex';
}
function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
}
// Fechar modal ao clicar fora da imagem
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('receiptModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeReceiptModal();
        });
    }
});
</script>
@endpush
            </div>
        @endif

        <form action="{{ route('passenger.upload.receipt', $reservation->id) }}" method="POST" enctype="multipart/form-data" id="receiptForm" style="margin-top: 20px;">
            @csrf
            <div class="form-group">
                <div class="file-upload-wrapper">
                    <label class="file-upload-label" id="fileLabel">
                        <span style="font-size: 2em; display: block; margin-bottom: 10px;">📎</span>
                        <strong>Clique aqui para selecionar o comprovante</strong>
                        <small>Formatos aceitos: JPG, PNG, PDF (máx. 10MB)</small>
                    </label>
                    <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 15px; font-size: 1.05em;">
                ✓ Enviar Comprovante
            </button>
        </form>
    </div>
    @else
    <div class="alert alert-info" style="margin-top: 15px; background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 8px;">
        <strong>ℹ️ Informação:</strong> Comprovante não necessário para pagamento em {{ $reservation->payment_method === 'dinheiro' ? 'dinheiro' : 'vale' }}.
    </div>
    @endif

    @if(!$reservation->boarded)
        @if($isLastReservation)
        <div style="margin-top: 20px; padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px;">
            <h3 style="color: #856404; margin-bottom: 12px; text-align: center; font-size: 1.2em;">✓ Confirmar Embarque</h3>
            <p style="color: #856404; text-align: center; margin-bottom: 15px; font-size: 0.95em;">
                Clique no botão abaixo quando estiver dentro do carro
            </p>
            <button
                onclick="confirmBoarding({{ $reservation->id }})"
                class="btn"
                style="background: #28a745; border: none; cursor: pointer; width: 100%; font-size: 1.05em; padding: 15px; transition: all 0.3s ease;"
                onmouseover="this.style.background='#218838'"
                onmouseout="this.style.background='#28a745'"
            >
                ✓ Confirmar Embarque
            </button>
        </div>
        @else
        <div style="margin-top: 20px; padding: 20px; background: #f8d7da; border: 2px solid #dc3545; border-radius: 8px;">
            <h3 style="color: #721c24; margin-bottom: 12px; text-align: center; font-size: 1.2em;">⚠️ Confirmação Indisponível</h3>
            <p style="color: #721c24; text-align: center; margin-bottom: 0; font-size: 0.95em;">
                Apenas a sua reserva mais recente pode ter o embarque confirmado. Esta não é a sua última reserva.
            </p>
        </div>
        @endif
    @endif

    <div class="btn-group">
        <a href="{{ route('passenger.dashboard') }}" class="btn btn-secondary">
            ← Minhas Reservas
        </a>
    </div>
</div>
</div>

<!-- Modal de Confirmação PIX -->
<div class="modal-overlay" id="pixModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">⚠️</div>
            <h3 class="modal-title">Atenção ao Copiar Chave PIX</h3>
        </div>
        <div class="modal-body">
            <p style="text-align: center; margin-bottom: 15px;">
                Antes de fazer o pagamento, é importante verificar os dados:
            </p>

            <div class="modal-warning">
                <strong>🔍 Verifique o Nome do Destinatário</strong>
                <p style="margin: 0;">
                    Ao fazer o PIX, confirme se o nome que aparece é o mesmo do motorista:
                </p>
            </div>

            @if($reservation->driver)
            <div class="modal-driver-name">
                👤 {{ $reservation->driver->name }}
            </div>
            @endif

            <p style="text-align: center; color: #dc3545; font-weight: 500; margin-top: 15px;">
                ⚠️ Se o nome for diferente, confirme com o motorista se a chave está correta antes de pagar!
            </p>
        </div>
        <div class="modal-buttons">
            <button class="btn-modal-cancel" onclick="closePixModal()">
                Cancelar
            </button>
            <button class="btn-modal-confirm" onclick="confirmCopyPix()">
                ✓ Entendi, Copiar Chave
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const reservation = @json($reservation);
    const allBoardings = @json($allBoardings ?? []);
    const lastBoarding = @json($lastBoarding);

    // Função para expandir/retrair seções
    function toggleCollapse(sectionId) {
        const content = document.getElementById('content-' + sectionId);
        const icon = document.getElementById('icon-' + sectionId);
        const header = icon.closest('.collapsible-header');
        const hint = header.querySelector('.collapsible-hint');

        if (content.classList.contains('collapsed')) {
            // Expandir
            content.classList.remove('collapsed');
            icon.classList.remove('collapsed');
            if (hint) hint.textContent = '(clique para retrair)';
            localStorage.setItem('collapse-' + sectionId, 'expanded');
        } else {
            // Retrair
            content.classList.add('collapsed');
            icon.classList.add('collapsed');
            if (hint) hint.textContent = '(clique para expandir)';
            localStorage.setItem('collapse-' + sectionId, 'collapsed');
        }
    }

    // Restaurar estado dos collapses ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const sections = ['reservation-data', 'transport-info'];

        sections.forEach(sectionId => {
            const content = document.getElementById('content-' + sectionId);
            const icon = document.getElementById('icon-' + sectionId);

            if (content && icon) {
                const savedState = localStorage.getItem('collapse-' + sectionId);
                const header = icon.closest('.collapsible-header');
                const hint = header.querySelector('.collapsible-hint');

                // Por padrão, deixar expandido. Só retrair se foi salvo como collapsed
                if (savedState === 'collapsed') {
                    content.classList.add('collapsed');
                    icon.classList.add('collapsed');
                    if (hint) hint.textContent = '(clique para expandir)';
                }
            }
        });
    });

    async function initMap() {
        // Verificar se o Google Maps está carregado
        if (typeof google === 'undefined' || !google.maps) {
            console.error('Google Maps não carregado');
            document.getElementById('map').innerHTML = '<div style="padding: 20px; text-align: center; color: #dc3545;">Erro ao carregar o Google Maps</div>';
            return;
        }

        // Geocode do endereço do passageiro
        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: reservation.address }, function(results, status) {
            if (status === 'OK') {
                const passengerLocation = results[0].geometry.location;

                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 13,
                    center: passengerLocation,
                });

                const bounds = new google.maps.LatLngBounds();

                // Array para armazenar pontos da trilha
                const trailPath = [];

                // Adicionar todos os embarques confirmados
                allBoardings.forEach((boarding, index) => {
                    if (boarding.boarded_latitude && boarding.boarded_longitude) {
                        const boardingLocation = {
                            lat: parseFloat(boarding.boarded_latitude),
                            lng: parseFloat(boarding.boarded_longitude)
                        };

                        trailPath.push(boardingLocation);
                        bounds.extend(boardingLocation);

                        // Marcador de embarque
                        const marker = new google.maps.Marker({
                            map: map,
                            position: boardingLocation,
                            title: `${boarding.name} - Embarcou`,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 7,
                                fillColor: "#28a745",
                                fillOpacity: 0.8,
                                strokeColor: "#1e7e34",
                                strokeWeight: 2
                            }
                        });

                        // Info window
                        const infoWindow = new google.maps.InfoWindow({
                            content: `<div style="padding: 5px;"><strong>${boarding.name}</strong><br>Embarcou</div>`
                        });

                        marker.addListener('click', function() {
                            infoWindow.open(map, marker);
                        });
                    }
                });

                // Adicionar marcadores de destino (endereços cadastrados) para todos os embarques
                allBoardings.forEach((boarding) => {
                    geocoder.geocode({ address: boarding.address }, function(res, stat) {
                        if (stat === 'OK') {
                            const destLocation = res[0].geometry.location;

                            new google.maps.Marker({
                                map: map,
                                position: destLocation,
                                title: boarding.address,
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 5,
                                    fillColor: "#4285F4",
                                    fillOpacity: 0.6,
                                    strokeColor: "#1967D2",
                                    strokeWeight: 1
                                }
                            });
                        }
                    });
                });

                // Marcador do endereço do passageiro atual (maior destaque)
                const myDestMarker = new google.maps.Marker({
                    map: map,
                    position: passengerLocation,
                    title: "Meu Local de Embarque",
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: "#4285F4",
                        fillOpacity: 1,
                        strokeColor: "#1967D2",
                        strokeWeight: 2
                    },
                    label: {
                        text: "📍",
                        color: "#FFFFFF"
                    }
                });

                bounds.extend(passengerLocation);

                // Último embarque (posição atual do carro)
                if (lastBoarding && lastBoarding.boarded_latitude && lastBoarding.boarded_longitude) {
                    const carLocation = {
                        lat: parseFloat(lastBoarding.boarded_latitude),
                        lng: parseFloat(lastBoarding.boarded_longitude)
                    };

                    // Calcular tempo desde último embarque
                    const boardedAt = new Date(lastBoarding.boarded_at);
                    const now = new Date();
                    const minutesAgo = Math.floor((now - boardedAt) / 60000);

                    // Marcador do carro (última posição)
                    const carMarker = new google.maps.Marker({
                        map: map,
                        position: carLocation,
                        title: "Posição Atual do Carro",
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 12,
                            fillColor: "#FFA500",
                            fillOpacity: 1,
                            strokeColor: "#FF8C00",
                            strokeWeight: 3
                        },
                        label: {
                            text: "🚗",
                            color: "#FFFFFF"
                        },
                        zIndex: 1000
                    });

                    // Info window do carro
                    const carInfoWindow = new google.maps.InfoWindow({
                        content: `<div style="padding: 8px; text-align: center;">
                            <strong>🚗 Carro está aqui</strong><br>
                            <span style="color: #666;">Último embarque: ${lastBoarding.name}</span><br>
                            <span style="color: #FFA500; font-weight: bold;">há ${minutesAgo} minuto${minutesAgo !== 1 ? 's' : ''}</span>
                        </div>`
                    });

                    // Abrir automaticamente
                    carInfoWindow.open(map, carMarker);

                    carMarker.addListener('click', function() {
                        carInfoWindow.open(map, carMarker);
                    });
                }

                // Desenhar trilha entre todos os embarques
                if (trailPath.length > 1) {
                    const trail = new google.maps.Polyline({
                        path: trailPath,
                        geodesic: true,
                        strokeColor: "#28a745",
                        strokeOpacity: 0.7,
                        strokeWeight: 3,
                        map: map
                    });
                }

                // Ajustar mapa para mostrar todos os pontos
                map.fitBounds(bounds);
            } else {
                console.error('Geocode error: ' + status);
                document.getElementById('map').innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Não foi possível carregar o mapa para este endereço</div>';
            }
        });
    }

    // Inicializar mapa quando o script do Google Maps carregar
    window.initMap = initMap;

function confirmBoarding(reservationId) {
    if (!confirm('Confirmar que você embarcou no carro?')) {
        return;
    }

    fetch(`/passageiro/reserva/${reservationId}/confirmar-embarque`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Embarque confirmado com sucesso!');
            location.reload();
        } else {
            alert('Erro ao confirmar embarque: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao confirmar embarque. Tente novamente.');
    });
}

function copyPixKey() {
    // Abrir modal de confirmação
    document.getElementById('pixModal').classList.add('active');
}

function closePixModal() {
    document.getElementById('pixModal').classList.remove('active');
}

function confirmCopyPix() {
    const pixKey = document.getElementById('pix-key').textContent.trim();
    const button = document.querySelector('.btn-copy-pix');
    const originalText = button.innerHTML;

    navigator.clipboard.writeText(pixKey).then(function() {
        // Fechar modal
        closePixModal();

        // Feedback visual no botão
        button.innerHTML = '✓ Copiado!';
        button.style.background = '#28a745';

        // Alert de confirmação
        alert('✓ Chave PIX copiada com sucesso!\n\n' + pixKey + '\n\n⚠️ Lembre-se de verificar se o nome do destinatário corresponde ao motorista!');

        // Restaurar botão após 3 segundos
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '#17a2b8';
        }, 3000);
    }, function(err) {
        console.error('Erro ao copiar: ', err);
        closePixModal();
        alert('❌ Erro ao copiar chave PIX. Tente copiar manualmente.');
    });
}

// Fechar modal ao clicar fora
document.addEventListener('click', function(event) {
    const modal = document.getElementById('pixModal');
    if (event.target === modal) {
        closePixModal();
    }
});

// Fechar modal com tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePixModal();
    }
});

    document.getElementById('receipt')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
        document.getElementById('fileLabel').innerHTML = `
            📎 Arquivo selecionado: <strong>${fileName}</strong><br>
            <small style="color: #666;">Clique para alterar</small>
        `;
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCO5dcVyYqR04krZ79oJ0r2XhQInRZa86Y&callback=initMap&libraries=places" async defer></script>
@endsection
