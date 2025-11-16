    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Reserva Confirmada')
@section('header', 'Reserva Confirmada!')

@section('styles')
<style>
    .success-icon {
        font-size: 5em;
        text-align: center;
        margin-bottom: 20px;
    }

    .info-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #343b71;
    }

    .info-box h3 {
        color: #343b71;
        margin-bottom: 10px;
    }

    .info-item {
        margin-bottom: 10px;
        font-size: 1.1em;
    }

    .info-item strong {
        color: #343b71;
    }

    .upload-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #e9ecef;
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
        padding: 15px;
        background: #f8f9fa;
        border: 2px dashed #343b71;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-label:hover {
        background: #e9ecef;
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
        margin-top: 30px;
    }

    .btn-group .btn {
        flex: 1;
    }

    h2 {
        text-align: center;
        color: #28a745;
        margin-bottom: 30px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    h3 {
        color: #343b71;
        margin-bottom: 20px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 1.5em;
        }

        h3 {
            font-size: 1.2em;
        }

        .info-item {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.3em;
        }

        h3 {
            font-size: 1.1em;
        }
    }
</style>
@endsection

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('home') }}" class="back-link">← Voltar ao Início</a>
        <a href="{{ route('passenger.logout') }}" style="color: #dc3545; text-decoration: none; font-weight: bold;">
            Sair 🚪
        </a>
    </div>

    <div class="success-icon">✅</div>

    <h2 style="text-align: center; color: #28a745; margin-bottom: 30px;">
        Sua reserva foi confirmada com sucesso!
    </h2>

    <div class="info-box">
        <h3>Dados da Reserva</h3>
        <div class="info-item">
            <strong>Nome:</strong> {{ $passenger->name }}
        </div>
        <div class="info-item">
            <strong>CPF:</strong> {{ $passenger->cpf }}
        </div>
        <div class="info-item">
            <strong>Data/Horário:</strong> {{ $passenger->scheduled_time->format('d/m/Y \à\s H:i') }}
        </div>
        <div class="info-item">
            <strong>Local de Embarque:</strong> {{ $passenger->address }}
        </div>
        <div class="info-item">
            <strong>Código da Reserva:</strong> #{{ str_pad($passenger->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <strong style="color: #856404;">💡 Importante:</strong>
        <p style="color: #856404; margin: 5px 0 0 0;">
            Guarde seu CPF (<strong>{{ $passenger->cpf }}</strong>) e senha. Você precisará deles para acessar esta página novamente e anexar comprovantes.
        </p>
    </div>

    <div class="upload-section">
        <h3 style="margin-bottom: 20px;">Anexar Comprovante</h3>
        <p style="color: #666; margin-bottom: 20px;">
            Você pode anexar um comprovante de pagamento ou documento (opcional)
        </p>

        @if($passenger->receipt_path)
            <div class="alert alert-success">
                Comprovante anexado com sucesso!
            </div>
            <div class="receipt-preview">
                @if(Str::endsWith($passenger->receipt_path, '.pdf'))
                    <p>📄 Arquivo PDF anexado</p>
                    <a href="{{ asset('storage/' . $passenger->receipt_path) }}" target="_blank" class="btn btn-secondary">
                        Ver Comprovante
                    </a>
                @else
                    <img src="{{ asset('storage/' . $passenger->receipt_path) }}" alt="Comprovante">
                @endif
            </div>
        @endif

        <form action="{{ route('passenger.upload.receipt', $passenger->id) }}" method="POST" enctype="multipart/form-data" id="receiptForm">
            @csrf
            <div class="form-group">
                <div class="file-upload-wrapper">
                    <label class="file-upload-label" id="fileLabel">
                        📎 Clique aqui para selecionar o arquivo<br>
                        <small style="color: #666;">Formatos aceitos: JPG, PNG, PDF (máx. 2MB)</small>
                    </label>
                    <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
            </div>

            <button type="submit" class="btn" style="width: 100%;">
                Enviar Comprovante
            </button>
        </form>
    </div>

    <div class="btn-group">
        <a href="{{ route('home') }}" class="btn btn-secondary">
            Voltar ao Início
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show selected file name
    document.getElementById('receipt').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
        document.getElementById('fileLabel').innerHTML = `
            📎 Arquivo selecionado: <strong>${fileName}</strong><br>
            <small style="color: #666;">Clique para alterar</small>
        `;
    });
</script>
@endsection
