@extends('layouts.app')

@section('title', 'Painel do Motorista')
@section('header', 'Painel do Motorista')

@section('styles')
<style>
    body {
        background: #e8f0ff;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #343b71;
        text-decoration: none;
        font-weight: bold;
    }

    .back-link:hover {
    .logout-btn {
        background: #dc3545;
        padding: 8px 12px;
        text-decoration: none;
        display: inline-block;
        color: #fff;
        border-radius: 4px;
        font-size: 0.95em;
        transition: background 0.2s;
        margin-left: 10px;
    }
    .logout-btn:hover {
        background: #b52a37;
        color: #fff;
    }

    @media (max-width: 600px) {
        .logout-btn {
            padding: 7px 10px;
            font-size: 0.85em;
            position: absolute;
            right: 10px;
            top: 10px;
            margin: 0;
        }
        .header-actions {
            position: relative;
            min-height: 40px;
        }
    }
        text-decoration: underline;
    }

    .filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        align-items: end;
    }

    .filter-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 10px;
        justify-content: flex-start;
    }

    .filter-actions .btn {
        flex: 0 0 auto;
        min-width: 120px;
    }

    @media (max-width: 768px) {
        .filters {
            grid-template-columns: 1fr;
        }
    }

    #map {
        width: 100%;
        height: 500px;
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
    }

    .map-controls {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
    }

    .map-controls .btn {
        background: white;
        color: #343b71;
        border: 2px solid #343b71;
        padding: 10px 15px;
        font-size: 0.9em;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .map-controls .btn:hover {
        background: #343b71;
        color: white;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #343b71 100%);
        color: white;
        padding: 12px 15px;
        border-radius: 6px;
        text-align: center;
    }

    .stat-card h3 {
        font-size: 1.6em;
        margin-bottom: 3px;
    }

    .stat-card p {
        opacity: 0.9;
        font-size: 0.85em;
    }

    .passenger-list {
        margin-top: 15px;
    }

    .passenger-item {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 10px;
        border-left: 3px solid #343b71;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .passenger-info {
        flex: 1;
        min-width: 200px;
    }

    .passenger-info h4 {
        color: #343b71;
        margin-bottom: 8px;
        font-size: 0.95em;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .passenger-info p {
        margin: 5px 0;
        color: #666;
        line-height: 1.4;
        font-size: 0.85em;
    }

    .passenger-actions {
        display: flex;
        gap: 10px;
    }

    .btn-small {
        padding: 8px 15px;
        font-size: 0.9em;
    }

    .receipt-badge {
        display: inline-block;
        padding: 3px 8px;
        background: #28a745;
        color: white;
        border-radius: 4px;
        font-size: 0.75em;
        margin-left: 5px;
    }

    .no-receipt-badge {
        display: inline-block;
        padding: 3px 8px;
        background: #6c757d;
        color: white;
        border-radius: 4px;
        font-size: 0.75em;
        margin-left: 5px;
    }

    @media (max-width: 768px) {
        #map {
            height: 350px;
        }

        .filters {
            padding: 15px;
            grid-template-columns: 1fr;
        }

        .filter-actions {
            flex-direction: column;
        }

        .filter-actions .btn {
            width: 100%;
        }

        .filter-group {
            flex-direction: column;
        }

        .sidebar {
            max-height: 300px;
        }

        .passenger-card {
            padding: 12px;
        }

        .passenger-card h4,
        .passenger-card p {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .btn {
            width: 100%;
        }

        .passenger-item {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        #map {
            height: 300px;
        }
    }
</style>
@endsection

@section('content')

<div class="card">
    <div class="header-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; position: relative;">
        <a href="{{ route('home') }}" class="back-link" style="margin: 0;">← Voltar</a>
        <a href="{{ route('driver.logout') }}" class="logout-btn">🚪 Sair</a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <h2 style="color: #343b71; font-size: 1.5em; margin: 0;">
            👋 Seja bem-vindo, <span style="color: #007bff;">{{ $driver->name }}</span>
        </h2>
    <a href="{{ route('driver.profile.edit') }}" style="background: #f4f6fa; color: #343b71; border: 1.5px solid #cfd8e3; border-radius: 5px; padding: 6px 16px; font-size: 1em; text-decoration: none; transition: background 0.2s;">✏️ Editar Perfil</a>
    </div>

    <h2 style="color: #343b71; font-size: 2em; margin-bottom: 20px;">
        Passageiros Reservados
    </h2>

    @if(isset($noCar) && $noCar)
        <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 30px; text-align: center; margin: 40px 0;">
            <div style="font-size: 4em; margin-bottom: 15px;">⏳</div>
            <h3 style="color: #856404; margin-bottom: 15px; font-size: 1.5em;">Aguardando Configuração</h3>
            <p style="color: #856404; font-size: 1.1em; margin-bottom: 10px;">
                Seu cadastro foi realizado com sucesso!
            </p>
            <p style="color: #856404; font-size: 1em;">
                O administrador ainda não criou seu carro e configurou os horários.<br>
                Entre em contato com o administrador para concluir a configuração.
            </p>
            <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 6px;">
                <strong>📋 Seus dados:</strong><br>
                <small style="color: #666;">
                    Nome: {{ $driver->name }}<br>
                    Email: {{ $driver->email }}<br>
                    @if($driver->route)
                        Rota: {{ $driver->route->name }}
                    @endif
                </small>
            </div>
        </div>
    @else

    @php
        $pixPassengers = $passengers->where('payment_method', 'pix');
        $pixWithReceipt = $pixPassengers->where('receipt_path', '!=', null)->count();
        $pixPending = $pixPassengers->count() - $pixWithReceipt;
    @endphp

    <form method="GET" action="{{ route('driver.index') }}" class="filters">
        <div class="form-group" style="margin-bottom: 0;">
            <label for="date">Filtrar por Data</label>
            <input
                type="date"
                id="date"
                name="date"
                value="{{ request('date', date('Y-m-d')) }}"
            >
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="time_start">Intervalo de Horário</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <input
                    type="time"
                    id="time_start"
                    name="time_start"
                    value="{{ request('time_start', now()->format('H:i')) }}"
                    placeholder="Das"
                >
                <input
                    type="time"
                    id="time_end"
                    name="time_end"
                    value="{{ request('time_end') }}"
                    placeholder="Até"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn">Filtrar</button>
            <a href="{{ route('driver.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>

    @if($car && $car->driver->route && $car->driver->route->has_return)
    <div style="margin-bottom: 15px; padding: 15px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <strong style="color: #856404;">🔄 Controle de Retorno</strong>
                <p style="margin: 5px 0 0 0; color: #856404; font-size: 0.9em;">
                    Clique quando iniciar a viagem de retorno
                </p>
            </div>
            <button
                onclick="startReturn()"
                class="btn"
                style="background: #ffc107; color: #856404; border: none; padding: 10px 20px; font-weight: 600;"
            >
                🔄 Iniciar Retorno
            </button>
        </div>
    </div>
    @endif

    @if($lastBoarding)
    <div class="alert alert-info" style="background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 12px 15px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9em;">
        <strong>🚗 Último Embarque:</strong> {{ $lastBoarding->name }} embarcou há {{ $lastBoarding->boarded_at->diffForHumans() }}
    </div>
    @endif

    <div style="position: relative;">
        <div id="map" style="transition: height 0.3s, width 0.3s;"></div>
        <div class="map-controls" style="margin-top: 48px;">
            <button type="button" class="btn" onclick="fitMapToMarkers()">
                🗺️ Ver Todos
            </button>
        </div>

        {{-- Progresso de Paradas (Motorista) --}}
        @php
            $stops = $route && $route->has_return && request('direction', 'outbound') === 'return' ? $returnStops : $outboundStops;
            // Encontrar a parada do último embarque
            $lastBoardingStopId = $lastBoarding && $lastBoarding->stop ? $lastBoarding->stop->id : null;
            $lastConfirmedIndex = -1;
            foreach($stops as $idx => $stop) {
                if ($lastBoardingStopId && $stop->id == $lastBoardingStopId) {
                    $lastConfirmedIndex = $idx;
                    break;
                }
                if (!$lastBoardingStopId && in_array($stop->id, $tripProgress)) {
                    $lastConfirmedIndex = $idx;
                }
            }
        @endphp
        @if($stops && count($stops))
        <div id="driver-progress-bar-container" style="overflow-x: auto; margin: 24px 0 16px 0; padding-bottom: 8px;">
            <div id="driver-progress-bar" style="display: flex; gap: 0; min-width: 600px; border: 1px solid #bbb; border-radius: 8px; background: #fff; box-sizing: border-box; padding: 10px 0;">
                @foreach($stops as $i => $stop)
                    @php
                        $isCurrent = $i === $lastConfirmedIndex && $lastConfirmedIndex >= 0;
                        $isCompleted = $lastConfirmedIndex >= 0 && $i < $lastConfirmedIndex;
                        $confirmed = $isCompleted || $isCurrent;
                        $circleBg = $isCompleted ? '#28a745' : ($isCurrent ? '#fff3cd' : '#fff');
                        $circleColor = $isCurrent ? '#856404' : ($isCompleted ? '#fff' : 'rgba(120,120,120,0.6)');
                        $circleBorder = $isCurrent ? '#ffc107' : ($isCompleted ? '#28a745' : 'rgba(120,120,120,0.4)');
                        $lineColor = $isCompleted ? '#28a745' : ($isCurrent ? '#ffc107' : 'rgba(120,120,120,0.3)');
                    @endphp
                    <div class="progress-stop-item{{ $isCurrent ? ' current' : '' }}" id="progress-stop-{{ $stop->id }}" style="flex: 0 0 180px; display: flex; flex-direction: column; align-items: center; position: relative;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $circleBg }}; border: 2px solid {{ $circleBorder }}; display: flex; align-items: center; justify-content: center; color: {{ $circleColor }}; font-weight: bold; font-size: 1.1em;">
                            @if($isCurrent)
                                ●
                            @elseif($isCompleted)
                                ✓
                            @else
                                {{ $i+1 }}
                            @endif
                        </div>
                        <div style="margin-top: 8px; text-align: center; font-size: 0.95em; color: #343b71; font-weight: 500; max-width: 140px; white-space: normal;">
                            {{ $stop->name }}
                        </div>
                        <div style="margin-top: 2px; text-align: center; font-size: 0.8em; color: #888; max-width: 140px; white-space: normal;">
                            {{ $stop->address }}
                        </div>
                        @if(!$loop->last)
                        <div style="position: absolute; top: 16px; left: 100%; width: 44px; height: 2px; background: {{ $lineColor }}; z-index: 0;"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <script>
        // Scroll horizontal para última parada confirmada
        document.addEventListener('DOMContentLoaded', function() {
            var stops = @json($stops->pluck('id'));
            var progress = @json($tripProgress);
            if (progress.length > 0) {
                var lastId = progress[progress.length-1];
                var el = document.getElementById('progress-stop-' + lastId);
                if (el) {
                    var container = document.getElementById('driver-progress-bar-container');
                    var left = el.offsetLeft - 60;
                    container.scrollTo({ left: left > 0 ? left : 0, behavior: 'smooth' });
                }
            }
        });
        </script>
        <style>
        #driver-progress-bar-container::-webkit-scrollbar {
            height: 8px;
        }
        #driver-progress-bar-container::-webkit-scrollbar-thumb {
            background: #c3c8e6;
            border-radius: 4px;
        }
        .progress-stop-item.current > div:first-child {
            background: #fff3cd !important;
            color: #856404 !important;
            border-color: #ffc107 !important;
            font-size: 1.5em;
        }
        </style>
        @endif
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>{{ $passengers->count() }}</h3>
            <p>Total de Passageiros</p>
        </div>
        <div class="stat-card">
            <h3>{{ $pixPassengers->count() }}</h3>
            <p>Pagamento PIX</p>
        </div>
        @if($pixPending > 0)
        <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);">
            <h3>{{ $pixPending }}</h3>
            <p>⚠️ Comprovante Pendente</p>
        </div>
        @else
        <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
            <h3>✓</h3>
            <p>Todos Comprovantes OK</p>
        </div>
        @endif
    </div>

    <div class="passenger-list">
        <h3 style="color: #343b71; margin-bottom: 12px; font-size: 1.1em;">Lista de Passageiros</h3>

        <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; margin-bottom: 18px;">
            <input id="searchInput" type="text" placeholder="Pesquisar por nome ou local de parada..." style="flex: 1; min-width: 180px; max-width: 350px; padding: 10px 14px; border: 2px solid #343b71; border-radius: 8px; font-size: 1em;">
            <select id="paymentFilter" style="min-width: 160px; max-width: 200px; padding: 10px 14px; border: 2px solid #343b71; border-radius: 8px; font-size: 1em;">
                <option value="all">Todos os Pagamentos</option>
                <option value="pix">Pagamento PIX</option>
                <option value="no_pix">Sem PIX</option>
            </select>
        </div>

        <div id="passengerListScroll" style="overflow-y: auto; max-height: 480px; border-radius: 8px; border: 1.5px solid #e9ecef; padding: 8px;">
        @if($passengers->isEmpty())
            <p style="text-align: center; color: #666; padding: 40px;">
                Nenhum passageiro reservado para os filtros selecionados.
            </p>
        @else
            @foreach($passengers as $passenger)
                <div class="passenger-item"
                    data-name="{{ strtolower($passenger->name) }}"
                    data-email="{{ strtolower($passenger->email) }}"
                    data-payment="{{ $passenger->payment_method }}"
                    data-stop-name="{{ $passenger->stop ? strtolower($passenger->stop->name) : '' }}"
                    data-stop-address="{{ $passenger->stop ? strtolower($passenger->stop->address) : strtolower($passenger->address) }}">
                    <div class="passenger-info">
                        <h4>
                            {{ $passenger->name }}
                            @if($passenger->boarded)
                                <span class="receipt-badge" style="background: #28a745;">✓ Embarcado</span>
                            @endif
                            @if($passenger->payment_method === 'pix')
                                @if($passenger->receipt_path)
                                    <span class="receipt-badge">✓ Comprovante OK</span>
                                @else
                                    <span class="no-receipt-badge">⚠️ Comprovante Pendente</span>
                                @endif
                            @endif
                        </h4>
                        <p><strong>📅 Data:</strong> {{ $passenger->scheduled_time->format('d/m/Y') }}</p>
                        <p><strong>🕒 Horário:</strong> {{ $passenger->scheduled_time_start }} - {{ $passenger->scheduled_time_end }}</p>
                        <p><strong>📍 Local:</strong>
                            @if($passenger->stop)
                                {{ $passenger->stop->name }}
                            @else
                                {{ $passenger->address }}
                            @endif
                        </p>
                        <p><strong>🔢 Código:</strong> #{{ str_pad($passenger->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p><strong>💳 Pagamento:</strong>
                            @if($passenger->payment_method === 'pix')
                                💳 PIX
                            @elseif($passenger->payment_method === 'dinheiro')
                                💵 Dinheiro
                            @else
                                🎫 Vale
                            @endif
                        </p>
                    </div>
                    <div class="passenger-actions">
                        @if($passenger->payment_method === 'pix' && $passenger->receipt_path)
                            <button class="btn btn-small" onclick="openDriverReceiptModal('{{ asset('storage/' . $passenger->receipt_path) }}')">
                                Ver Comprovante
                            </button>
<!-- Modal para visualização ampliada do comprovante (motorista) -->
<div id="driverReceiptModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.8); align-items:center; justify-content:center;">
    <span onclick="closeDriverReceiptModal()" style="position:absolute; top:30px; right:40px; color:#fff; font-size:2.5em; cursor:pointer; font-weight:bold;">&times;</span>
    <img id="driverModalReceiptImg" src="" alt="Comprovante Ampliado" style="max-width:90vw; max-height:85vh; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.4); display:block; margin:auto;">
</div>
@push('scripts')
<script>
function openDriverReceiptModal(imgUrl) {
    var modal = document.getElementById('driverReceiptModal');
    var img = document.getElementById('driverModalReceiptImg');
    img.src = imgUrl;
    modal.style.display = 'flex';
}
function closeDriverReceiptModal() {
    document.getElementById('driverReceiptModal').style.display = 'none';
}
// Fechar modal ao clicar fora da imagem
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('driverReceiptModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeDriverReceiptModal();
        });
    }
});
</script>
@endpush
                        @endif
                        <button
                            onclick="focusOnMarker({{ $passenger->latitude }}, {{ $passenger->longitude }})"
                            class="btn btn-small btn-secondary">
                            Ver no Mapa
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
        </div>
    </div>
@section('scripts')
@parent
<script>
// ...restaura funções originais confirmStop e addOccurrence se necessário...
// Recarregar página automaticamente a cada 30 segundos
setInterval(() => {
    window.location.reload();
}, 30000);

const searchInput = document.getElementById('searchInput');
const paymentFilter = document.getElementById('paymentFilter');
const passengerItems = document.querySelectorAll('.passenger-item');

function filterPassengerList() {
    const search = searchInput.value.trim().toLowerCase();
    const payment = paymentFilter.value;
    passengerItems.forEach(item => {
        const name = item.getAttribute('data-name');
        const email = item.getAttribute('data-email');
        const stopName = item.getAttribute('data-stop-name') || '';
        const stopAddress = item.getAttribute('data-stop-address') || '';
        const pay = item.getAttribute('data-payment');
        let show = (
            name.includes(search) ||
            email.includes(search) ||
            stopName.includes(search) ||
            stopAddress.includes(search)
        );
        if (payment === 'pix') {
            show = show && pay === 'pix';
        } else if (payment === 'no_pix') {
            show = show && pay !== 'pix';
        }
        item.style.display = show ? '' : 'none';
    });
}

searchInput.addEventListener('input', filterPassengerList);
paymentFilter.addEventListener('change', filterPassengerList);
</script>
@endsection
</div>
@endsection

@section('scripts')
<script>
    let map;
    let markers = [];
    const passengers = @json($passengers);

    async function initMap() {
        // Check if API key is configured
        const apiKey = '{{ config('services.google_maps.key') }}';
        if (!apiKey || apiKey === 'YOUR_GOOGLE_MAPS_API_KEY') {
            document.getElementById('map').innerHTML = '<div style="padding: 20px; text-align: center; color: #dc3545;"><strong>⚠️ Chave do Google Maps não configurada!</strong><br>Configure GOOGLE_MAPS_API_KEY no arquivo .env</div>';
            return;
        }

        // Default location (Brazil center)
        const defaultLocation = { lat: -14.2350, lng: -51.9253 };

        // Import required libraries
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

        // Initialize map
        map = new Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 4,
            mapId: 'PENDULO_RADAR_MAP',
            mapTypeControl: false,
        });

        // Try to get driver's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    const driverLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Create custom pin for driver
                    const driverPin = new PinElement({
                        background: '#007bff',
                        borderColor: 'white',
                        glyphColor: 'white',
                        glyph: '🚗',
                        scale: 1.5,
                    });

                    // Add driver marker
                    const driverMarker = new AdvancedMarkerElement({
                        map: map,
                        position: driverLocation,
                        title: 'Sua Localização',
                        content: driverPin.element,
                    });

                    // Create info window for driver
                    const driverInfoContent = document.createElement('div');
                    driverInfoContent.style.padding = '8px';
                    driverInfoContent.style.fontSize = '14px';
                    driverInfoContent.innerHTML = `
                        <h4 style="margin: 0 0 5px 0; color: #007bff; font-size: 1em; font-weight: 600;">🚗 Você está aqui</h4>
                        <p style="margin: 2px 0; font-size: 0.9em; color: #666;">Localização atual do motorista</p>
                    `;

                    const driverInfoWindow = new google.maps.InfoWindow({
                        content: driverInfoContent,
                        pixelOffset: new google.maps.Size(0, -5),
                    });

                    driverMarker.addListener('click', () => {
                        markers.forEach(m => m.infoWindow?.close());
                        driverInfoWindow.open(map, driverMarker);
                    });

                    // Center map on driver location
                    map.setCenter(driverLocation);
                    map.setZoom(12);
                },
                function(error) {
                    console.log('Erro ao obter localização:', error);
                }
            );
        }

        // Add markers for each passenger
        if (passengers.length > 0) {
            const bounds = new google.maps.LatLngBounds();

            for (let index = 0; index < passengers.length; index++) {
                const passenger = passengers[index];
                const position = {
                    lat: parseFloat(passenger.latitude),
                    lng: parseFloat(passenger.longitude)
                };

                // Create pin with custom color
                const pin = new PinElement({
                    background: passenger.payment_method === 'pix'
                        ? (passenger.receipt_path ? '#28a745' : '#dc3545')
                        : '#343b71',
                    borderColor: 'white',
                    glyphColor: 'white',
                    glyph: (index + 1).toString(),
                    scale: 1.2,
                });

                // Create marker
                const marker = new AdvancedMarkerElement({
                    map: map,
                    position: position,
                    title: passenger.name,
                    content: pin.element,
                });

                // Create info window content
                const infoContent = document.createElement('div');
                infoContent.style.padding = '4px 6px';
                infoContent.style.maxWidth = '280px';
                infoContent.style.fontSize = '13px';
                infoContent.style.lineHeight = '1.3';
                infoContent.innerHTML = `
                    <h4 style="margin: 0 0 5px 0; color: #343b71; font-size: 0.95em; font-weight: 600; line-height: 1.2;">${passenger.name}</h4>
                    <p style="margin: 2px 0; font-size: 0.85em;"><strong>📅</strong> ${new Date(passenger.scheduled_time).toLocaleString('pt-BR', {timeZone: 'America/Sao_Paulo', day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                    <p style="margin: 2px 0; font-size: 0.85em;"><strong>📍</strong> ${passenger.address}</p>
                    <p style="margin: 2px 0; font-size: 0.85em;"><strong>💳</strong> ${passenger.payment_method === 'pix' ? 'PIX' : passenger.payment_method === 'dinheiro' ? 'Dinheiro' : 'Vale'}</p>
                    ${passenger.payment_method === 'pix' ? (passenger.receipt_path ? '<p style="margin: 2px 0 0 0; color: #28a745; font-size: 0.8em;"><strong>✓</strong> Comprovante anexado</p>' : '<p style="margin: 2px 0 0 0; color: #dc3545; font-size: 0.8em;"><strong>⚠️</strong> Comprovante pendente</p>') : ''}
                `;

                // Create info window with custom styling
                const infoWindow = new google.maps.InfoWindow({
                    content: infoContent,
                    pixelOffset: new google.maps.Size(0, -5),
                    disableAutoPan: false,
                    headerDisabled: true
                });

                marker.addListener('click', () => {
                    // Close all other info windows
                    markers.forEach(m => {
                        if (m.infoWindow) {
                            m.infoWindow.close();
                        }
                    });
                    infoWindow.open(map, marker);
                });

                marker.infoWindow = infoWindow;
                markers.push({ marker, position, infoWindow });
                bounds.extend(position);
            }

            // Fit map to show all markers
            map.fitBounds(bounds);
        }
    }

    function focusOnMarker(lat, lng) {
        const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
        map.setCenter(position);
        map.setZoom(15);

        // Find and click the marker
        markers.forEach(({ marker, position: markerPos, infoWindow }) => {
            if (Math.abs(markerPos.lat - position.lat) < 0.0001 &&
                Math.abs(markerPos.lng - position.lng) < 0.0001) {
                // Close all info windows
                markers.forEach(m => m.infoWindow?.close());
                // Open this one
                infoWindow.open(map, marker);
            }
        });
    }

    function fitMapToMarkers() {
        if (markers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(({ position }) => {
                bounds.extend(position);
            });
            map.fitBounds(bounds);
        }
    }

    function startReturn() {
        if (!confirm('Confirmar que está iniciando a viagem de retorno?\n\nOs passageiros passarão a ver o progresso das paradas de retorno.')) {
            return;
        }

        const date = document.querySelector('input[name="date"]')?.value || '{{ date("Y-m-d") }}';
        const timeStart = document.querySelector('input[name="time_start"]')?.value || '{{ now()->format("H:i") }}';

        fetch('{{ route("driver.start.return") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                trip_date: date,
                time_start: timeStart
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ ' + data.message);
                location.reload();
            } else {
                alert('❌ Erro: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao iniciar retorno. Tente novamente.');
        });
    }

    function confirmStop(stopId, stopName) {
        if (!confirm('Confirmar passagem pela parada:\n' + stopName + '?')) {
            return;
        }

        const date = document.querySelector('input[name="date"]')?.value || '{{ date("Y-m-d") }}';
        const timeStart = document.querySelector('input[name="time_start"]')?.value || '{{ now()->format("H:i") }}';

        fetch('{{ route("driver.confirm.stop") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                stop_id: stopId,
                trip_date: date,
                time_start: timeStart,
                direction: 'outbound' // Pode ser ajustado dinamicamente
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ ' + data.message);
                location.reload();
            } else {
                alert('❌ Erro: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao confirmar parada. Tente novamente.');
        });
    }
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&loading=async&callback=initMap">
</script>
@endif
@endsection
