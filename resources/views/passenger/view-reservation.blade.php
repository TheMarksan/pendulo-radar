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
        color: #343b71;
        font-size: 2em;
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
        .success-icon {
            font-size: 4em;
        }

        .info-box {
            padding: 15px;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            width: 100%;
        }

        .info-item {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .info-item strong {
            display: block;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 1.5em;
        }

        h3 {
            font-size: 1.2em;
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

    <div class="info-box">
        <h3 style="color: #343b71;">Dados da Reserva</h3>
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
            <strong>Local de Embarque:</strong> {{ $reservation->address }}
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
        <div class="info-item" style="background: #d4edda; border-left: 4px solid #28a745; padding: 10px; margin-top: 10px;">
            <strong>Status:</strong> <span style="color: #28a745;">✓ Embarcado</span><br>
            <small style="color: #666;">Embarque confirmado em {{ $reservation->boarded_at->format('d/m/Y H:i') }}</small>
        </div>
        @endif
    </div>

    <!-- Mapa -->
    <div class="info-box" style="padding: 0; overflow: hidden;">
        <div id="map" style="width: 100%; height: 400px; border-radius: 8px;"></div>
    </div>

    @if($reservation->payment_method === 'pix')
    <div class="upload-section">
        <h3 style="margin-bottom: 20px;">Anexar Comprovante PIX</h3>
        <p style="color: #666; margin-bottom: 20px;">
            Para pagamentos via PIX, é necessário anexar o comprovante
        </p>

        @if($reservation->receipt_path)h)
            <div class="alert alert-success">
                Comprovante anexado com sucesso!
            </div>
            <div class="receipt-preview">
                @if(Str::endsWith($reservation->receipt_path, '.pdf'))
                    <p>📄 Arquivo PDF anexado</p>
                    <a href="{{ asset('storage/' . $reservation->receipt_path) }}" target="_blank" class="btn btn-secondary">">
                        Ver Comprovante
                    </a>
                @else
                    <img src="{{ asset('storage/' . $reservation->receipt_path) }}" alt="Comprovante">
                @endif
            </div>
        @endif

        <form action="{{ route('passenger.upload.receipt', $reservation->id) }}" method="POST" enctype="multipart/form-data" id="receiptForm">
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
    @else
    <div class="alert alert-info" style="margin-top: 30px; background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 8px;">
        <strong>ℹ️ Informação:</strong> Comprovante não necessário para pagamento em {{ $reservation->payment_method === 'dinheiro' ? 'dinheiro' : 'vale' }}.
    </div>
    @endif

    @if(!$reservation->boarded)
    <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px;">
        <h3 style="color: #856404; margin-bottom: 15px; text-align: center;">Confirmar Embarque</h3>
        <p style="color: #856404; text-align: center; margin-bottom: 15px;">
            Clique no botão abaixo quando estiver dentro do carro
        </p>
        <button 
            onclick="confirmBoarding({{ $reservation->id }})"
            class="btn" 
            style="background: #28a745; border: none; cursor: pointer; width: 100%; font-size: 1.1em; padding: 15px;"
        >
            ✓ Confirmar Embarque
        </button>
    </div>
    @endif

    <div class="btn-group">
        <a href="{{ route('passenger.dashboard') }}" class="btn btn-secondary">
            ← Minhas Reservas
        </a>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    const reservation = @json($reservation);
    const allBoardings = @json($allBoardings ?? []);
    const lastBoarding = @json($lastBoarding);

    function initMap() {
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

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                fetch(`/passageiro/reserva/${reservationId}/confirmar-embarque`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude
                    })
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
            },
            function(error) {
                console.error('Erro ao obter localização:', error);
                alert('Erro ao obter sua localização. Verifique as permissões do navegador.');
            }
        );
    } else {
        alert('Geolocalização não é suportada pelo seu navegador.');
    }
}

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
