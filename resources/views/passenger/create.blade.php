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
    <a href="{{ route('passenger.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>
    
    <h2 style="color: #343b71; margin-bottom: 30px;">
        Nova Reserva - {{ $passenger->name }}
    </h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('passenger.store') }}" method="POST" id="passengerForm">
        @csrf

        <div class="form-group">
            <label for="address-input">Endereço de Embarque *</label>
            <input 
                type="text" 
                id="address-input" 
                placeholder="Digite o endereço ou local de embarque"
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
            >
            <div id="address-display" style="display: none; margin-top: 10px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                <strong>✓ Endereço selecionado:</strong> <span id="address-text"></span>
            </div>
            <input 
                type="hidden" 
                id="address" 
                name="address"
            >
            <div id="map"></div>
        </div>

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
        </div>

        <div class="form-group">
            <label for="scheduled_time_start">Intervalo de Horário *</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label for="scheduled_time_start" style="font-size: 0.9em; color: #666;">Das</label>
                    <input 
                        type="time" 
                        id="scheduled_time_start" 
                        name="scheduled_time_start" 
                        required 
                        value="{{ old('scheduled_time_start') }}"
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
                    >
                </div>
                <div>
                    <label for="scheduled_time_end" style="font-size: 0.9em; color: #666;">Até</label>
                    <input 
                        type="time" 
                        id="scheduled_time_end" 
                        name="scheduled_time_end" 
                        required 
                        value="{{ old('scheduled_time_end') }}"
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
                    >
                </div>
            </div>
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
    let map;
    let marker;
    let autocompleteInput;

    async function initMap() {
        // Check if API key is configured
        const apiKey = '{{ env('GOOGLE_MAPS_API_KEY', '') }}';
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

        // Initialize map
        map = new Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 4,
            mapId: 'PENDULO_RADAR_MAP',
            mapTypeControl: false,
        });

        // Initialize marker
        marker = new AdvancedMarkerElement({
            map: map,
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
            
            console.log('Place selected:', place);

            if (!place.geometry || !place.geometry.location) {
                alert('Endereço não encontrado. Por favor, selecione um endereço da lista de sugestões.');
                return;
            }

            // Update map and marker
            const location = place.geometry.location;
            map.setCenter(location);
            map.setZoom(15);
            marker.position = location;

            // Update hidden fields
            const lat = location.lat();
            const lng = location.lng();
            const address = place.formatted_address || place.name;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('address').value = address;
            
            // Show confirmation
            document.getElementById('address-display').style.display = 'block';
            document.getElementById('address-text').textContent = address;
            
            console.log('✓ Address set:', address, 'Lat:', lat, 'Lng:', lng);
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
                    
                    document.getElementById('address').value = address;
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    // Show confirmation
                    document.getElementById('address-display').style.display = 'block';
                    document.getElementById('address-text').textContent = address;
                    
                    console.log('Address set by drag:', address);
                }
            });
        });

        // Try to get user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(userLocation);
                map.setZoom(12);
                marker.position = userLocation;
            });
        }
    }

    // Validate form before submission
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        document.getElementById('passengerForm').addEventListener('submit', function(e) {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            const address = document.getElementById('address').value;

            if (!lat || !lng || !address) {
                e.preventDefault();
                alert('Por favor, selecione um endereço válido no mapa usando o campo de busca.');
                return false;
            }
        });
    });
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'YOUR_GOOGLE_MAPS_API_KEY') }}&loading=async&callback=initMap">
</script>
@endsection
