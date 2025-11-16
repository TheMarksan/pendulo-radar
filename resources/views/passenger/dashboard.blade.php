    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Dashboard')
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

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .welcome-text {
        color: #343b71;
        font-size: 1.5em;
        font-weight: bold;
    }

    .logout-link {
        color: #dc3545;
        text-decoration: none;
        font-weight: bold;
        padding: 10px 20px;
        border: 2px solid #dc3545;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .logout-link:hover {
        background: #dc3545;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .reservations-grid {
        display: grid;
        gap: 20px;
    }

    .reservation-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-left: 5px solid #343b71;
    }

    .reservation-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .reservation-id {
        font-size: 1.2em;
        color: #343b71;
        font-weight: bold;
    }

    .payment-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: bold;
    }

    .payment-pix {
        background: #00C9A7;
        color: white;
    }

    .payment-dinheiro {
        background: #28a745;
        color: white;
    }

    .payment-vale {
        background: #6f42c1;
        color: white;
    }

    .reservation-details {
        margin: 15px 0;
    }

    .reservation-detail {
        margin: 8px 0;
        color: #666;
    }

    .reservation-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .btn-small {
        padding: 8px 15px;
        font-size: 0.9em;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 5em;
        margin-bottom: 20px;
    }

    h3 {
        color: #343b71;
        margin-bottom: 20px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    @media (max-width: 768px) {
        .reservations-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .header-bar {
            flex-direction: column;
            align-items: flex-start;
        }

        .welcome-text {
            font-size: 1.3em;
        }

        h3 {
            font-size: 1.2em;
        }
    }

    @media (max-width: 480px) {
        h3 {
            font-size: 1.1em;
        }
    }

    @media (max-width: 480px) {
        .reservation-actions {
            flex-direction: column;
            gap: 8px;
        }

        .btn-small {
            width: 100%;
        }

        .reservation-detail {
            font-size: 0.9em;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')

<div class="content-wrapper">
<div class="card">
    <div class="header-bar">
        <div class="welcome-text">
            👋 Olá, {{ $passenger->name }}!
        </div>
        <a href="{{ route('passenger.logout') }}" class="logout-link">
            Sair 🚪
        </a>
    </div>

    <div class="action-buttons">
        <a href="{{ route('passenger.create') }}" class="btn">
            ➞ Nova Reserva
        </a>
    </div>

    @php
        $pendingReceipts = $reservations->filter(function($r) {
            return $r->payment_method === 'pix' && !$r->receipt_path;
        })->count();
    @endphp

    @if($pendingReceipts > 0)
    <div class="alert alert-warning" style="background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>⚠️ Atenção:</strong> Você tem {{ $pendingReceipts }} {{ $pendingReceipts === 1 ? 'reserva com pagamento PIX pendente' : 'reservas com pagamento PIX pendentes' }} de comprovante. Clique em "Anexar Comprovante" para enviar.
    </div>
    @endif

    <h3 style="margin-bottom: 20px;">Minhas Reservas</h3>

    @if($reservations->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3 style="color: #999;">Você ainda não tem reservas</h3>
            <p style="margin-top: 10px;">Clique no botão "Nova Reserva" acima para fazer sua primeira reserva</p>
        </div>
    @else
        <div class="reservations-grid">
            @foreach($reservations as $reservation)
                <div class="reservation-card">
                    <div class="reservation-header">
                        <div class="reservation-id">
                            Reserva #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
                            @if($reservation->boarded)
                                <span style="display: inline-block; background: #28a745; color: white; padding: 3px 10px; border-radius: 12px; font-size: 0.75em; margin-left: 5px;">✓ Embarcado</span>
                            @endif
                        </div>
                        <span class="payment-badge payment-{{ $reservation->payment_method }}">
                            @if($reservation->payment_method === 'pix')
                                💳 PIX
                            @elseif($reservation->payment_method === 'dinheiro')
                                💵 Dinheiro
                            @else
                                🎟️ Vale
                            @endif
                        </span>
                    </div>

                    <div class="reservation-details">
                        <div class="reservation-detail">
                            <strong>📅 Data:</strong> {{ $reservation->scheduled_time->format('d/m/Y') }}
                        </div>
                        <div class="reservation-detail">
                            <strong>🕒 Horário:</strong> {{ $reservation->scheduled_time_start }} - {{ $reservation->scheduled_time_end }}
                        </div>
                        <div class="reservation-detail">
                            <strong>📍 Local:</strong>
                            @if($reservation->stop)
                                {{ $reservation->stop->name }} - {{ $reservation->address }}
                            @else
                                {{ $reservation->address }}
                            @endif
                        </div>
                        <div class="reservation-detail">
                            <strong>📄 Comprovante:</strong>
                            @if($reservation->payment_method === 'pix')
                                @if($reservation->receipt_path)
                                    <span style="color: #28a745;">✓ Anexado</span>
                                @else
                                    <span style="color: #dc3545; font-weight: bold;">⚠️ Pendente</span>
                                @endif
                            @else
                                <span style="color: #6c757d;">Não necessário</span>
                            @endif
                        </div>
                    </div>

                    <div class="reservation-actions">
                        <a href="{{ route('passenger.reservation.view', $reservation->id) }}" class="btn btn-small">
                            👁️ Ver Detalhes
                        </a>
                        <a href="{{ route('passenger.reservation.edit', $reservation->id) }}" class="btn btn-small btn-secondary">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('passenger.reservation.delete', $reservation->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta reserva?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small" style="background-color: #dc3545; border: none; cursor: pointer;">
                                🗑️ Excluir
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection

@section('scripts')
<script>
function confirmBoarding(reservationId) {
    if (!confirm('Confirmar que você embarcou no carro?')) {
        return;
    }

    fetch(`/passageiro/reserva/${reservationId}/confirmar-embarque`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Embarque confirmado com sucesso!');
            window.location.reload();
        } else {
            alert('Erro ao confirmar embarque: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao confirmar embarque. Tente novamente.');
    });
}
</script>
@endsection
