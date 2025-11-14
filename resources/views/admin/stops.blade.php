@extends('layouts.app')

@section('title', 'Gerenciar Paradas')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.routes') }}" class="back-link">← Voltar para Rotas</a>

        <h1 style="color: #343b71; margin-bottom: 10px;">📍 Paradas da Rota: {{ $route->name }}</h1>
        <p style="color: #666; margin-bottom: 30px;">Arraste e solte para reordenar as paradas</p>

        @if(session('success'))
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.stops.store', $route->id) }}" method="POST" style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            @csrf
            <h3 style="margin-bottom: 15px;">Nova Parada</h3>

            <div class="form-group">
                <label for="name">Nome da Parada *</label>
                <input type="text" id="name" name="name" required placeholder="Ex: Praça Central">
            </div>


            <div class="form-group">
                <label for="type" style="font-weight: 600; color: #343b71; margin-bottom: 6px; display: block;">Tipo *</label>
                <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <select id="type" name="type" required
                        style="min-width: 140px; max-width: 220px; width: 100%; padding: 12px 16px; border: 2px solid #343b71; border-radius: 8px; font-size: 1.05em; background: #f8f9fa; color: #343b71; font-weight: 500; box-shadow: 0 2px 8px rgba(52,59,113,0.04);">
                        <option value="outbound">➡️ Ida</option>
                        <option value="return">⬅️ Retorno</option>
                    </select>
                </div>
            </div>

            <style>
            @media (max-width: 600px) {
                #type {
                    min-width: 100px !important;
                    max-width: 100% !important;
                    font-size: 0.98em !important;
                    padding: 10px 12px !important;
                }
            }
            </style>

            <div class="form-group">
                <label for="address-input">Endereço *</label>
                <input type="text" id="address-input" placeholder="Digite o endereço" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px;">
                <div id="address-display" style="display: none; margin-top: 10px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                    <strong>✓ Endereço selecionado:</strong> <span id="address-text"></span>
                </div>
                <input type="hidden" id="address" name="address" required>
                <input type="hidden" id="latitude" name="latitude" required>
                <input type="hidden" id="longitude" name="longitude" required>
            </div>

            <div id="map" style="width: 100%; height: 300px; border-radius: 8px; margin-bottom: 15px;"></div>

            <button type="submit" class="btn">Adicionar Parada</button>
        </form>

        <h3 style="margin-bottom: 15px;">Paradas Cadastradas</h3>
        <div style="margin-bottom: 18px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <label for="stopTypeFilter" style="font-weight: 500; color: #343b71;">Filtrar por tipo:</label>
            <select id="stopTypeFilter" style="min-width: 120px; max-width: 180px; padding: 8px 12px; border: 2px solid #343b71; border-radius: 8px; font-size: 1em;">
                <option value="all">Todas</option>
                <option value="outbound">Ida</option>
                <option value="return">Retorno</option>
            </select>
        </div>
        <div id="stops-list" style="display: grid; gap: 15px;">
            @forelse($route->stops as $stop)
                <div class="stop-item" data-id="{{ $stop->id }}" data-type="{{ $stop->type }}" data-latitude="{{ $stop->latitude }}" data-longitude="{{ $stop->longitude }}" style="border: 2px solid #dee2e6; border-radius: 10px; padding: 15px; cursor: move; background: white;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <div class="stop-header" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                                <span class="stop-number" style="background: #343b71; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">?</span>
                                <h4 style="margin: 0;">{{ $stop->name }}</h4>
                                <span style="padding: 3px 10px; border-radius: 12px; font-size: 0.85em; {{ $stop->type === 'outbound' ? 'background: #d1ecf1; color: #0c5460;' : 'background: #fff3cd; color: #856404;' }}">
                                    {{ $stop->type === 'outbound' ? '➡️ Ida' : '⬅️ Retorno' }}
                                </span>
                                <span style="padding: 3px 10px; border-radius: 12px; font-size: 0.85em; {{ $stop->is_active ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' }}">
                                    {{ $stop->is_active ? '✓ Ativo' : '✗ Inativo' }}
                                </span>
                            </div>
                            <p style="color: #666; margin: 0; font-size: 0.9em;">📍 {{ $stop->address }}</p>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <form action="{{ route('admin.stops.toggle', [$route->id, $stop->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-small" style="padding: 5px 10px; font-size: 0.85em; background: {{ $stop->is_active ? '#ffc107' : '#28a745' }};">
                                    {{ $stop->is_active ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.stops.delete', [$route->id, $stop->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Excluir esta parada?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small" style="padding: 5px 10px; font-size: 0.85em; background: #dc3545;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #999; padding: 40px;">Nenhuma parada cadastrada</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let map, marker, geocoder, autocomplete, stopsPolyline = null, stopsMarkers = [];

function initMap() {
    geocoder = new google.maps.Geocoder();

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: { lat: -9.7656, lng: -36.2451 }
    });

    marker = new google.maps.Marker({
        map: map,
        draggable: true,
    });

    // Traçar linha ao iniciar
    setTimeout(drawStopsPolyline, 500);
// Função para traçar linha entre as paradas visíveis
function drawStopsPolyline() {
    // Limpa marcadores antigos
    if (Array.isArray(stopsMarkers)) {
        stopsMarkers.forEach(m => m.setMap && m.setMap(null));
        stopsMarkers = [];
    }
    if (stopsPolyline) {
        stopsPolyline.setMap(null);
        stopsPolyline = null;
    }
    // Filtra apenas as paradas do tipo selecionado
    const type = document.getElementById('stopTypeFilter').value;
    // Seleciona apenas as paradas do tipo filtrado e que estão visíveis (ordem DOM = ordem visual)
    const allStops = Array.from(document.querySelectorAll('#stops-list .stop-item'));
    let filtered = allStops.filter(el => {
        const elType = (el.getAttribute('data-type') || '').trim();
        return (type === 'all' || elType === type);
    });
    filtered = filtered.filter(el => el.style.display !== 'none');
    // Monta array de stops com numeração correta
    const visibleStops = filtered.map((el, idx) => {
        return {
            lat: parseFloat(el.getAttribute('data-latitude')),
            lng: parseFloat(el.getAttribute('data-longitude')),
            name: el.querySelector('h4') ? el.querySelector('h4').textContent : '',
            idx: idx
        };
    }).filter(s => !isNaN(s.lat) && !isNaN(s.lng));

    // Centraliza o mapa nas paradas filtradas
    if (visibleStops.length > 0 && map) {
        const bounds = new google.maps.LatLngBounds();
        visibleStops.forEach(s => bounds.extend({lat: s.lat, lng: s.lng}));
        map.fitBounds(bounds);
        if (visibleStops.length === 1) {
            map.setZoom(16);
        }
    }

    if (window.google && window.google.maps && window.google.maps.marker) {
        const { AdvancedMarkerElement } = window.google.maps.marker;
        visibleStops.forEach((stop, idx) => {
            // SVG círculo numerado igual ao passageiro/create
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
            let marker;
            if (AdvancedMarkerElement) {
                marker = new AdvancedMarkerElement({
                    map: map,
                    position: { lat: stop.lat, lng: stop.lng },
                    title: stop.name,
                    content: markerDiv
                });
            } else {
                marker = new google.maps.Marker({
                    map: map,
                    position: { lat: stop.lat, lng: stop.lng },
                    title: stop.name,
                    label: { text: String(idx+1), color: '#fff', fontWeight: 'bold', fontSize: '16px' },
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 16,
                        fillColor: '#343b71',
                        fillOpacity: 1,
                        strokeColor: '#fff',
                        strokeWeight: 3
                    }
                });
            }
            marker.stopData = stop;
            stopsMarkers.push(marker);
        });
    }
    if (visibleStops.length > 1 && window.google && window.google.maps) {
        stopsPolyline = new google.maps.Polyline({
            path: visibleStops.map(s => ({ lat: s.lat, lng: s.lng })),
            geodesic: false,
            strokeColor: '#343b71',
            strokeOpacity: 1,
            strokeWeight: 5
        });
        stopsPolyline.setMap(map);
    }
}

    const input = document.getElementById("address-input");
    autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (!place.geometry) return;

        const location = place.geometry.location;
        map.setCenter(location);
        map.setZoom(17);
        marker.setPosition(location);

        document.getElementById('address').value = place.formatted_address;
        document.getElementById('latitude').value = location.lat();
        document.getElementById('longitude').value = location.lng();

        document.getElementById('address-text').textContent = place.formatted_address;
        document.getElementById('address-display').style.display = 'block';
    });

    marker.addListener('dragend', function(event) {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();

        geocoder.geocode({ location: {lat, lng} }, function(results, status) {
            if (status === 'OK' && results[0]) {
                document.getElementById('address').value = results[0].formatted_address;
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('address-text').textContent = results[0].formatted_address;
                document.getElementById('address-display').style.display = 'block';
            }
        });
    });
}

window.initMap = initMap;

// Filtro de paradas por tipo (ida/retorno)
document.addEventListener('DOMContentLoaded', function() {
    // Função para renumerar as paradas visíveis conforme o filtro
    function renumberStops() {
        const type = stopTypeFilter.value;
        let count = 1;
        document.querySelectorAll('#stops-list .stop-item').forEach(function(el) {
            if (type === 'all' || (el.getAttribute('data-type') || '').trim() === type) {
                el.querySelector('.stop-number').textContent = count;
                count++;
            } else {
                el.querySelector('.stop-number').textContent = '';
            }
        });
    }
    const stopTypeFilter = document.getElementById('stopTypeFilter');
    const stopsList = document.getElementById('stops-list');
    function applyStopTypeFilter() {
        const type = stopTypeFilter.value;
        // Seleciona todos os elementos de parada
        const items = stopsList.querySelectorAll('.stop-item');
        items.forEach(function(el) {
            const elType = (el.getAttribute('data-type') || '').trim();
            if (type === 'all' || elType === type) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
        renumberStops();
        setTimeout(drawStopsPolyline, 0); // Garante atualização do mapa após DOM
    }
    stopTypeFilter.addEventListener('change', applyStopTypeFilter);
    // Aplica o filtro e a numeração ao carregar a página
    applyStopTypeFilter();

    // Traçar linha ao redimensionar
    window.addEventListener('resize', drawStopsPolyline);
    // Traçar linha ao alterar lista
    const polyObserver = new MutationObserver(function() {
        applyStopTypeFilter();
    });
    polyObserver.observe(stopsList, { childList: true, subtree: false });
    // Sempre que a lista de paradas mudar, reaplica o filtro e a numeração
    const observer = new MutationObserver(function() {
        applyStopTypeFilter();
    });
    observer.observe(stopsList, { childList: true, subtree: false });
});

// Sortable para reordenar paradas
const stopsList = document.getElementById('stops-list');
if (stopsList && stopsList.children.length > 0) {
    new Sortable(stopsList, {
        animation: 150,
        handle: 'div[data-id]',
        onEnd: function() {
            const stops = [];
            stopsList.querySelectorAll('[data-id]').forEach((el, index) => {
                stops.push({
                    id: el.dataset.id,
                    order: index + 1
                });
            });

            fetch('{{ route("admin.stops.order", $route->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ stops })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&libraries=places" async defer></script>
@endsection
