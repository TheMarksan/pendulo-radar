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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #343b71 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-card h3 {
        font-size: 2.5em;
        margin-bottom: 5px;
    }

    .stat-card p {
        opacity: 0.9;
    }

    .passenger-list {
        margin-top: 20px;
    }

    .passenger-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #343b71;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .passenger-info {
        flex: 1;
        min-width: 200px;
    }

    .passenger-info h4 {
        color: #343b71;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .passenger-info p {
        margin: 8px 0;
        color: #666;
        line-height: 1.6;
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
        padding: 5px 10px;
        background: #28a745;
        color: white;
        border-radius: 5px;
        font-size: 0.85em;
        margin-left: 10px;
    }

    .no-receipt-badge {
        display: inline-block;
        padding: 5px 10px;
        background: #6c757d;
        color: white;
        border-radius: 5px;
        font-size: 0.85em;
        margin-left: 10px;
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
    <a href="{{ route('home') }}" class="back-link">← Voltar</a>
    
    <h2 style="color: #343b71; font-size: 2em; margin-bottom: 20px;">
        Passageiros Reservados
    </h2>

    @php
        $pixPassengers = $passengers->where('payment_method', 'pix');
        $pixWithReceipt = $pixPassengers->where('receipt_path', '!=', null)->count();
        $pixPending = $pixPassengers->count() - $pixWithReceipt;
    @endphp

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

    @if($lastBoarding)
    <div class="alert alert-info" style="background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>🚗 Último Embarque:</strong> {{ $lastBoarding->name }} embarcou há {{ $lastBoarding->boarded_at->diffForHumans() }}
    </div>
    @endif

    <div style="position: relative;">
        <div id="map"></div>
        <div class="map-controls">
            <button type="button" class="btn" onclick="fitMapToMarkers()">
                🗺️ Ver Todos
            </button>
        </div>
    </div>

    <div class="passenger-list">
        <h3 style="color: #343b71; margin-bottom: 15px;">Lista de Passageiros</h3>
        
        @if($passengers->isEmpty())
            <p style="text-align: center; color: #666; padding: 40px;">
                Nenhum passageiro reservado para os filtros selecionados.
            </p>
        @else
            @foreach($passengers as $passenger)
                <div class="passenger-item">
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
                        <p><strong>📍 Local:</strong> {{ $passenger->address }}</p>
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
                            <a href="{{ route('driver.receipt', $passenger->id) }}" 
                               target="_blank"
                               class="btn btn-small">
                                Ver Comprovante
                            </a>
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
@endsection

@section('scripts')
<script>
    let map;
    let markers = [];
    const passengers = @json($passengers);

    async function initMap() {
        // Check if API key is configured
        const apiKey = '{{ config('services.google_maps.api_key') }}';
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
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&loading=async&callback=initMap">
</script>
@endsection
