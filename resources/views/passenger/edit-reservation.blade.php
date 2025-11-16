    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Editar Reserva')
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
        Editar Reserva #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
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

    <form action="{{ route('passenger.reservation.update', $reservation->id) }}" method="POST" id="passengerForm">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="address-input">Endereço de Embarque *</label>
            <input
                type="text"
                id="address-input"
                placeholder="Digite o endereço ou local de embarque"
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
                value="{{ old('address', $reservation->address) }}"
            >
            <div id="address-display" style="margin-top: 10px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                <strong>✓ Endereço atual:</strong> <span id="address-text">{{ $reservation->address }}</span>
            </div>
            <input
                type="hidden"
                id="address"
                name="address"
                value="{{ old('address', $reservation->address) }}"
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
                <option value="pix" {{ old('payment_method', $reservation->payment_method) == 'pix' ? 'selected' : '' }}>💳 PIX</option>
                <option value="dinheiro" {{ old('payment_method', $reservation->payment_method) == 'dinheiro' ? 'selected' : '' }}>💵 Dinheiro</option>
                <option value="vale" {{ old('payment_method', $reservation->payment_method) == 'vale' ? 'selected' : '' }}>🎟️ Vale</option>
            </select>
        </div>

        <div class="form-group">
            <label for="scheduled_time">Data da Reserva *</label>
            <input
                type="date"
                id="scheduled_time"
                name="scheduled_time"
                required
                value="{{ old('scheduled_time', $reservation->scheduled_time ? $reservation->scheduled_time->format('Y-m-d') : '') }}"
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
                        value="{{ old('scheduled_time_start', $reservation->scheduled_time_start) }}"
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
                        value="{{ old('scheduled_time_end', $reservation->scheduled_time_end) }}"
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em;"
                    >
                </div>
            </div>
            <small style="color: #666; display: block; margin-top: 5px;">Horário aproximado em que estará no ponto</small>
        </div>

        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $reservation->latitude) }}">
        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $reservation->longitude) }}">

        <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
            Salvar Alterações
        </button>
    </form>
</div>
</div>
@endsection

@section('scripts')
<script>
    let map;
    let marker;
    let autocomplete;

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // Initial coordinates (existing reservation location)
        const initialLat = {{ $reservation->latitude }};
        const initialLng = {{ $reservation->longitude }};
        const position = { lat: initialLat, lng: initialLng };

        map = new Map(document.getElementById("map"), {
            zoom: 15,
            center: position,
            mapId: "DEMO_MAP_ID",
        });

        marker = new AdvancedMarkerElement({
            map: map,
            position: position,
            gmpDraggable: true,
            title: "{{ $reservation->address }}",
        });

        // Update coordinates when marker is dragged
        marker.addListener('dragend', async (event) => {
            const position = marker.position;
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;

            // Reverse geocode to get address
            const geocoder = new google.maps.Geocoding();
            try {
                const response = await fetch(
                    `https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.lat},${position.lng}&key={{ config('services.google_maps.api_key') }}`
                );
                const data = await response.json();
                if (data.results[0]) {
                    const address = data.results[0].formatted_address;
                    document.getElementById('address').value = address;
                    document.getElementById('address-text').textContent = address;
                    document.getElementById('address-input').value = address;
                }
            } catch (error) {
                console.error('Error reverse geocoding:', error);
            }
        });

        // Initialize autocomplete
        const input = document.getElementById('address-input');
        autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: { country: 'br' },
            fields: ['formatted_address', 'geometry', 'name']
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                console.log("No geometry for this place");
                return;
            }

            const location = place.geometry.location;
            const address = place.formatted_address || place.name;

            // Update hidden fields
            document.getElementById('address').value = address;
            document.getElementById('latitude').value = location.lat();
            document.getElementById('longitude').value = location.lng();

            // Update display
            document.getElementById('address-text').textContent = address;
            document.getElementById('address-display').style.display = 'block';

            // Update map
            map.setCenter(location);
            map.setZoom(15);
            marker.position = location;
        });
    }

    window.initMap = initMap;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initMap" async defer></script>
@endsection
