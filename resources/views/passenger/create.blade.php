@if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Reservar Passagem')
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

    /* Botões e selects responsivos */
    .location-choice-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .stop-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    select, input[type="text"], input[type="date"], input[type="time"] {
        width: 100% !important;
        padding: 12px !important;
        border: 2px solid #ddd !important;
        border-radius: 8px !important;
        font-size: 1em !important;
        box-sizing: border-box !important;
    }

    select:focus, input:focus {
        outline: none;
        border-color: #343b71 !important;
        box-shadow: 0 0 0 3px rgba(52, 59, 113, 0.1);
    }

    /* Responsividade para grid de horários */
    @media (max-width: 600px) {
        .time-grid {
            grid-template-columns: 1fr !important;
        }
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

        .location-choice-btn {
            font-size: 0.9em !important;
            padding: 10px 15px !important;
        }

        #stops-list {
            grid-template-columns: 1fr !important;
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

    #map {
        width: 100%;
        height: 300px;
        border-radius: 8px;
        margin-top: 10px;
    }

    select {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1em;
        background: white;
        cursor: pointer;
    }

    h2 {
        color: #343b71;
        font-size: 2em;
        margin-bottom: 30px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    @media (max-width: 768px) {
        #map {
            height: 250px;
        }

        select {
            font-size: 0.95em;
            padding: 10px;
        }

        h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        #address-display,
        #address-text {
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 0.9em;
        }

        .btn {
            width: 100%;
            font-size: 1em;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.3em;
        }

        select {
            font-size: 0.9em;
        }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
<div class="card">
    <a href="{{ route('passenger.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>

    <h2 style="color: #343b71; margin-bottom: 30px;">
        Nova Reserva - {{ $passenger->name }}
    </h2>

    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
    @if($errors->any())
        <script>window.addEventListener('DOMContentLoaded',function(){showToast('Preencha todos os campos obrigatórios.','error');});</script>
    @endif

    <form action="{{ route('passenger.store') }}" method="POST" id="passengerForm">
        @csrf


        <div class="form-group">
            <label for="route_id">Escolha a Rota *</label>
            <select
                id="route_id"
                required
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
            >
                <option value="">Selecione uma rota</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" data-has-return="{{ $route->has_return ? '1' : '0' }}">{{ $route->name }}</option>
                @endforeach
            </select>
            <small style="color: #666; display: block; margin-top: 5px;">
                Primeiro escolha a rota
            </small>
        </div>

        <div class="form-group" id="date-time-group" style="display: none;">
            <label for="scheduled_time">Data da Reserva *</label>
            <input
                type="date"
                id="scheduled_time"
                name="scheduled_time"
                required
                value="{{ old('scheduled_time') }}"
                min="{{ date('Y-m-d') }}"
                max="{{ date('Y-m-d', strtotime('+7 days')) }}"
            >
            <small style="color: #666; display: block; margin-top: 5px;">Reservas permitidas até 7 dias</small>

            <label for="scheduled_time_start" style="margin-top: 15px;">Horário desejado *</label>
            <input
                type="time"
                id="scheduled_time_start"
                name="scheduled_time_start"
                required
                value="{{ old('scheduled_time_start') }}"
            >
            <small style="color: #666; display: block; margin-top: 5px;">Apenas motoristas com saída a partir desse horário aparecerão</small>
        </div>

        <div class="form-group" id="driver-group" style="display: none;">
            <label for="schedule_id">Escolha o Motorista *</label>
            <select
                id="schedule_id"
                name="schedule_id"
                required
                disabled
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
            >
                <option value="">Selecione data e horário primeiro</option>
            </select>
            <small style="color: #666; display: block; margin-top: 5px;">
                Os motoristas disponíveis aparecerão após selecionar data e horário
            </small>
        </div>

        <div class="form-group" id="stop-selection-group" style="display: none;">
            <label>Local de Embarque *</label>
            <div style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="button" id="use-stop-btn" class="location-choice-btn active" style="flex: 1; min-width: 150px; padding: 12px 20px; border: 2px solid #28a745; background: #28a745; color: white; border-radius: 8px; cursor: pointer; font-size: 1em; font-weight: 600; transition: all 0.3s;">
                    📍 Escolher Parada
                </button>
                <button type="button" id="use-custom-btn" class="location-choice-btn" style="flex: 1; min-width: 150px; padding: 12px 20px; border: 2px solid #ddd; background: white; color: #333; border-radius: 8px; cursor: pointer; font-size: 1em; font-weight: 600; transition: all 0.3s;">
                    🔍 Digitar Endereço
                </button>
            </div>

            <div id="stops-container" style="margin-top: 15px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                    <input id="stopSearchInput" type="text" placeholder="Buscar parada..." style="flex: 1; min-width: 140px; max-width: 350px; padding: 10px 14px; border: 2px solid #343b71; border-radius: 8px; font-size: 1em;">
                    <select id="direction" name="direction" required style="min-width: 120px; max-width: 180px; padding: 10px 14px; border: 2px solid #343b71; border-radius: 8px; font-size: 1em;">
                        <option value="outbound">Ida</option>
                        <option value="return">Retorno</option>
                    </select>
                </div>
                <div id="stops-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-bottom: 15px; max-height: 320px; overflow-y: auto; border-radius: 8px; border: 1.5px solid #e9ecef; background: #fff;">
                </div>
                <div id="map" style="height: 400px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
            </div>

            <div id="custom-address-container" style="display: none; margin-top: 15px;">
                <input
                    type="text"
                    id="address-input"
                    placeholder="Digite o endereço ou local de embarque"
                    style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em; margin-bottom: 15px;"
                >
                <div id="map-custom" style="height: 400px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
            </div>

            <input type="hidden" id="stop_id" name="stop_id">
        </div>

        <input type="hidden" id="address" name="address">

        <div class="form-group">
            <label for="payment_method">Forma de Pagamento *</label>
            <select
                id="payment_method"
                name="payment_method"
                required
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
            >
                <option value="">Selecione a forma de pagamento</option>
                <option value="pix">💳 PIX</option>
                <option value="dinheiro">💵 Dinheiro</option>
                <option value="vale">🎟️ Vale</option>
            </select>
        </div>

        <div class="form-group">
            <label for="scheduled_time_end">Horário Final *</label>
            <input
                type="time"
                id="scheduled_time_end"
                name="scheduled_time_end"
                required
                value="{{ old('scheduled_time_end') }}"
            >
            <small style="color: #666; display: block; margin-top: 5px;">Horário aproximado em que estará no ponto</small>
        </div>

        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
            Confirmar Reserva
        </button>
    </form>
</div>
</div>
@endsection

@section('scripts')
<script>
// Lógica para exibir campos de data/horário após rota, e buscar motoristas após data/horário
document.addEventListener('DOMContentLoaded', function() {
    const routeSelect = document.getElementById('route_id');
    const dateTimeGroup = document.getElementById('date-time-group');
    const driverGroup = document.getElementById('driver-group');
    const dateInput = document.getElementById('scheduled_time');
    const timeInput = document.getElementById('scheduled_time_start');
    const driverSelect = document.getElementById('schedule_id');

    function resetDrivers() {
        driverSelect.innerHTML = '<option value="">Selecione data e horário primeiro</option>';
        driverSelect.disabled = true;
        driverGroup.style.display = 'none';
        // Esconde também o grupo de parada/endereço
        const stopGroup = document.getElementById('stop-selection-group');
        if (stopGroup) stopGroup.style.display = 'none';
    }

    routeSelect.addEventListener('change', function() {
        if (this.value) {
            dateTimeGroup.style.display = '';
        } else {
            dateTimeGroup.style.display = 'none';
            resetDrivers();
        }
    });

    function fetchDrivers() {
        const routeId = routeSelect.value;
        const date = dateInput.value;
        const time = timeInput.value;
        if (!routeId || !date || !time) {
            resetDrivers();
            return;
        }
        driverSelect.innerHTML = '<option value="">Carregando...</option>';
        driverSelect.disabled = true;
        driverGroup.style.display = '';
        fetch(`/api/rotas/${routeId}/motoristas?date=${date}&time=${time}`)
            .then(res => res.json())
            .then(drivers => {
                if (!drivers.length) {
                    driverSelect.innerHTML = '<option value="">Nenhum motorista disponível para este horário</option>';
                    driverSelect.disabled = true;
                    return;
                }
                driverSelect.innerHTML = '<option value="">Selecione o motorista e horário</option>';
                drivers.forEach(sch => {
                    const opt = document.createElement('option');
                    opt.value = sch.schedule_id;
                    opt.textContent = `${sch.driver_name} (Saída: ${sch.departure_time}${sch.return_time ? ', Retorno: ' + sch.return_time : ''})`;
                    driverSelect.appendChild(opt);
                });
                driverSelect.disabled = false;
            })
            .catch(() => {
                driverSelect.innerHTML = '<option value="">Erro ao buscar motoristas</option>';
                driverSelect.disabled = true;
            });
    }

    dateInput.addEventListener('change', fetchDrivers);
    timeInput.addEventListener('change', fetchDrivers);

    // Ao selecionar motorista (schedule_id), buscar paradas da rota e exibir grupo de parada/endereço
    // Garante que clearAddress está no escopo
    function clearAddress() {
        document.getElementById('stop_id').value = '';
        document.getElementById('address').value = '';
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        if (typeof selectedStopData !== 'undefined') selectedStopData = null;
    }

    driverSelect.addEventListener('change', async function() {
        const stopGroup = document.getElementById('stop-selection-group');
        const stopsList = document.getElementById('stops-list');
        const stopSearchInput = document.getElementById('stopSearchInput');
        const routeId = routeSelect.value;
        clearAddress();
        // Sempre mostrar o grupo de parada/endereço ao selecionar motorista
        stopGroup.style.display = 'block';
        if (!this.value || !routeId) {
            // Esconde o grupo se não houver seleção válida
            stopGroup.style.display = 'none';
            return;
        }
        try {
            const response = await fetch(`/api/rotas/${routeId}/paradas`);
            let stops = await response.json();
            // Filtro por tipo de viagem (ida/retorno)
            const directionSelect = document.getElementById('direction');
            function filterStopsByDirection(stopsArr) {
                const direction = directionSelect.value;
                return stopsArr.filter(stop => !stop.type || stop.type === direction);
            }
            let allStops = filterStopsByDirection(stops);
            function renderStops(filter = '') {
                stopsList.innerHTML = '';
                const filtered = allStops.filter(stop =>
                    stop.name.toLowerCase().includes(filter) ||
                    stop.address.toLowerCase().includes(filter)
                );
                filtered.forEach(stop => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'stop-btn';
                    const idx = allStops.indexOf(stop);
                    btn.innerHTML = `<span style=\"display:inline-block;width:28px;height:28px;background:#343b71;color:#fff;border-radius:50%;text-align:center;line-height:28px;font-weight:bold;margin-right:8px;\">${idx+1}</span> <strong>${stop.name}</strong><br><small>${stop.address}</small>`;
                    btn.style.cssText = 'padding: 12px; border: 2px solid #ddd; background: white; border-radius: 8px; cursor: pointer; text-align: left; transition: all 0.3s;';
                    btn.addEventListener('click', function() {
                        selectStop(stop);
                        document.querySelectorAll('.stop-btn').forEach(b => {
                            b.style.border = '2px solid #ddd';
                            b.style.background = 'white';
                        });
                        this.style.border = '2px solid #28a745';
                        this.style.background = '#d4edda';
                    });
                    stopsList.appendChild(btn);
                });
            }
            renderStops();
            // Remover listeners antigos para evitar múltiplas execuções
            stopSearchInput.oninput = function() {
                renderStops(this.value.trim().toLowerCase());
            };
            directionSelect.onchange = function() {
                allStops = filterStopsByDirection(stops);
                renderStops(stopSearchInput.value.trim().toLowerCase());
                displayStopsOnMap(allStops);
            };
            displayStopsOnMap(allStops);
            // Sempre mostrar seleção de parada/endereço ao trocar motorista
            showStopSelection();
            // Corrigir alternância de endereço customizado
            document.getElementById('use-stop-btn').onclick = showStopSelection;
            document.getElementById('use-custom-btn').onclick = showCustomAddress;
        } catch (error) {
            // Mesmo em erro, mostrar o grupo para permitir digitar endereço
            stopGroup.style.display = 'block';
            showStopSelection();
        }
    });
});
    let map;
    let mapCustom;
    let marker;
    let stopsMarkers = [];
    let autocompleteInput;

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
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        const { Autocomplete } = await google.maps.importLibrary("places");

        // Initialize map for stops
        map = new Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 12,
            mapId: 'PENDULO_RADAR_MAP',
            mapTypeControl: false,
        });

        // Initialize map for custom address
        mapCustom = new Map(document.getElementById('map-custom'), {
            center: defaultLocation,
            zoom: 4,
            mapId: 'PENDULO_RADAR_MAP_CUSTOM',
            mapTypeControl: false,
        });

        // Initialize marker for custom address
        marker = new AdvancedMarkerElement({
            map: mapCustom,
            position: defaultLocation,
            gmpDraggable: true,
        });

        // Initialize autocomplete on input field
        const input = document.getElementById('address-input');
        const autocomplete = new Autocomplete(input, {
            componentRestrictions: { country: 'br' },
            fields: ['formatted_address', 'geometry', 'name']
        });

        // Handle place selection
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();


            if (!place.geometry || !place.geometry.location) {
                return;
            }

            // Update map and marker
            const location = place.geometry.location;
            mapCustom.setCenter(location);
            mapCustom.setZoom(15);
            marker.position = location;

            // Update hidden fields
            const lat = location.lat();
            const lng = location.lng();
            const address = place.formatted_address || place.name;

            // Clear stop_id since we're using custom address
            document.getElementById('stop_id').value = '';
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('address').value = address;

        });

        // Allow manual marker placement
        marker.addListener('dragend', async (event) => {
            const { Geocoder } = await google.maps.importLibrary("geocoding");
            const geocoder = new Geocoder();

            geocoder.geocode({ location: event.latLng }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const address = results[0].formatted_address;
                    const lat = event.latLng.lat();
                    const lng = event.latLng.lng();

                    // Clear stop_id since we're using custom address
                    document.getElementById('stop_id').value = '';
                    document.getElementById('address').value = address;
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                }
            });
        });

        // Try to get user's current location for custom map
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                mapCustom.setCenter(userLocation);
                mapCustom.setZoom(12);
                marker.position = userLocation;
            });
        }

        // Also get location for stops map
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(userLocation);
                map.setZoom(12);
            });
        }
    }

    // Function to display stops on map
    async function displayStopsOnMap(stops) {
        // Clear existing markers and polylines
        stopsMarkers.forEach(m => m.setMap(null));
        stopsMarkers = [];
        if (window.stopsPolyline) {
            window.stopsPolyline.setMap(null);
            window.stopsPolyline = null;
        }

        if (!stops || stops.length === 0) return;

        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        const bounds = new google.maps.LatLngBounds();
        const path = [];

        stops.forEach((stop, idx) => {
            const position = { lat: parseFloat(stop.latitude), lng: parseFloat(stop.longitude) };
            path.push(position);

            // SVG círculo numerado (como string, para usar como innerHTML)
            const svg = `
                <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="20" cy="20" r="16" fill="#343b71" stroke="#fff" stroke-width="3" />
                  <text x="20" y="27" text-anchor="middle" font-size="18" font-family="Arial" fill="#fff">${idx+1}</text>
                </svg>
            `;
            const markerDiv = document.createElement('div');
            markerDiv.style.width = '40px';
            markerDiv.style.height = '40px';
            markerDiv.innerHTML = svg;

            const marker = new AdvancedMarkerElement({
                map: map,
                position: position,
                title: stop.name,
                content: markerDiv
            });

            marker.stopData = stop;
            stopsMarkers.push(marker);
            bounds.extend(position);
        });

        // Traçar linha sólida entre as paradas
        if (path.length > 1 && window.google && window.google.maps) {
            window.stopsPolyline = new google.maps.Polyline({
                path: path,
                geodesic: false,
                strokeColor: '#343b71',
                strokeOpacity: 1,
                strokeWeight: 5
            });
            window.stopsPolyline.setMap(map);
        }

        // Ajustar o mapa para mostrar todas as paradas
        if (stops.length > 0) {
            map.fitBounds(bounds);
        }
    }

    // Function to focus on a specific stop on the map
    function focusOnStop(stop) {
        if (!map) return;

        const position = { lat: parseFloat(stop.latitude), lng: parseFloat(stop.longitude) };
        map.setCenter(position);
        map.setZoom(16);
    }

    // Cascading selection logic
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrar rotas conforme tipo de viagem
        const directionSelect = document.getElementById('direction');
        const routeSelect = document.getElementById('route_id');
        function filterRoutesByDirection() {
            const direction = directionSelect.value;
            Array.from(routeSelect.options).forEach(opt => {
                if (!opt.value) return;
                // Se for retorno, só mostra rotas com has_return=1
                if (direction === 'return' && opt.getAttribute('data-has-return') !== '1') {
                    opt.style.display = 'none';
                } else {
                    opt.style.display = '';
                }
            });
            // Resetar seleção
            routeSelect.value = '';
        }
        directionSelect.addEventListener('change', filterRoutesByDirection);
        filterRoutesByDirection();
        let selectedStopData = null;
        let isUsingStop = true;

        // Route selection handler
        document.getElementById('route_id').addEventListener('change', async function() {
            const routeId = this.value;
            const driverSelect = document.getElementById('driver_id');
            const stopGroup = document.getElementById('stop-selection-group');

            // Reset
            driverSelect.innerHTML = '<option value="">Carregando...</option>';
            driverSelect.disabled = true;
            stopGroup.style.display = 'none';
            clearAddress();

            if (!routeId) {
                driverSelect.innerHTML = '<option value="">Selecione uma rota primeiro</option>';
                return;
            }

            try {
                const response = await fetch(`/api/rotas/${routeId}/motoristas`);
                const drivers = await response.json();

                driverSelect.innerHTML = '<option value="">Selecione um motorista</option>';
                drivers.forEach(driver => {
                    const option = document.createElement('option');
                    option.value = driver.id;
                    option.textContent = `${driver.name} (Saída: ${driver.departure_time}${driver.return_time ? ", Retorno: " + driver.return_time : ""})`;
                    driverSelect.appendChild(option);
                });

                driverSelect.disabled = false;
            } catch (error) {
                driverSelect.innerHTML = '<option value="">Erro ao carregar motoristas</option>';
            }
        });

        // Driver selection handler
        // (Removido handler antigo de driver_id, todo o fluxo usa schedule_id)

        // Toggle between stop and custom address
        document.getElementById('use-stop-btn').addEventListener('click', function() {
            showStopSelection();
        });

        document.getElementById('use-custom-btn').addEventListener('click', function() {
            showCustomAddress();
        });


        // Torna as funções globais para uso em listeners dinâmicos
        window.showStopSelection = function() {
            isUsingStop = true;
            document.getElementById('stops-container').style.display = 'block';
            document.getElementById('custom-address-container').style.display = 'none';
            document.getElementById('use-stop-btn').classList.add('active');
            document.getElementById('use-custom-btn').classList.remove('active');
            document.getElementById('use-stop-btn').style.background = '#28a745';
            document.getElementById('use-stop-btn').style.color = 'white';
            document.getElementById('use-stop-btn').style.borderColor = '#28a745';
            document.getElementById('use-custom-btn').style.background = 'white';
            document.getElementById('use-custom-btn').style.color = '#333';
            document.getElementById('use-custom-btn').style.borderColor = '#ddd';

            // Clear custom address if switching
            document.getElementById('address-input').value = '';
            if (selectedStopData) {
                window.selectStop(selectedStopData);
            }
            // Forçar resize do mapa para garantir exibição correta
            if (window.google && window.google.maps && typeof map !== 'undefined' && map) {
                setTimeout(function() {
                    google.maps.event.trigger(map, 'resize');
                }, 200);
            }
        }

        window.showCustomAddress = function() {
            isUsingStop = false;
            document.getElementById('stops-container').style.display = 'none';
            document.getElementById('custom-address-container').style.display = 'block';
            document.getElementById('use-custom-btn').classList.add('active');
            document.getElementById('use-stop-btn').classList.remove('active');
            document.getElementById('use-custom-btn').style.background = '#28a745';
            document.getElementById('use-custom-btn').style.color = 'white';
            document.getElementById('use-custom-btn').style.borderColor = '#28a745';
            document.getElementById('use-stop-btn').style.background = 'white';
            document.getElementById('use-stop-btn').style.color = '#333';
            document.getElementById('use-stop-btn').style.borderColor = '#ddd';

            // Clear stop selection
            document.getElementById('stop_id').value = '';
            selectedStopData = null;
            clearAddress();
        }

        window.selectStop = function(stop) {
            selectedStopData = stop;
            document.getElementById('stop_id').value = stop.id;
            document.getElementById('address').value = stop.address;
            document.getElementById('latitude').value = stop.latitude;
            document.getElementById('longitude').value = stop.longitude;

            // Focus map on the selected stop
            focusOnStop(stop);
        }

        function clearAddress() {
            document.getElementById('stop_id').value = '';
            document.getElementById('address').value = '';
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            selectedStopData = null;
        }

        // Form validation
        document.getElementById('passengerForm').addEventListener('submit', function(e) {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            const address = document.getElementById('address').value;

            if (!lat || !lng || !address) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&loading=async&callback=initMap">
</script>
@endsection
