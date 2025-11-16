<style>
	.trip-progress-box {
		background: #fff;
		border-radius: 8px;
		padding: 20px;
		margin-bottom: 15px;
		border: 2px solid #e9ecef;
		box-shadow: 0 2px 8px rgba(52,59,113,0.06);
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
		min-width: 120px;
		text-align: center;
		box-shadow: 0 2px 8px rgba(52,59,113,0.08);
	}
	.direction-outbound {
		background: linear-gradient(90deg, #4fd1c5 0%, #4299e1 100%);
		color: #fff;
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
		font-weight: bold;
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
		color: #343b71;
	}
	.progress-stop-info {
		display: flex;
		flex-direction: column;
		gap: 2px;
	}
	.progress-stop-name {
		font-weight: 600;
		color: #343b71;
		font-size: 1em;
	}
	.progress-stop-address {
		color: #666;
		font-size: 0.95em;
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
		box-shadow: 0 1px 4px rgba(52,59,113,0.04);
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
	.stat-card {
		background: linear-gradient(135deg, #667eea 0%, #343b71 100%);
		color: white;
		padding: 12px 15px;
		border-radius: 6px;
		text-align: center;
		box-shadow: 0 2px 8px rgba(52,59,113,0.08);
	}
	.stat-card.comprovante {
		background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
		color: #fff;
		font-weight: bold;
		border: 2px solid #dc3545;
		box-shadow: 0 2px 8px rgba(220,53,69,0.10);
	}
	.stat-card.comprovante-ok {
		background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
		color: #fff;
		font-weight: bold;
		border: 2px solid #28a745;
		box-shadow: 0 2px 8px rgba(40,167,69,0.10);
	}
</style>
@php
	// Variáveis esperadas: $driver, $passengers, $route, $outboundStops, $returnStops, $tripProgress
	$pixPassengers = $passengers->where('payment_method', 'pix');
	$pixWithReceipt = $pixPassengers->where('receipt_path', '!=', null)->count();
	$pixPending = $pixPassengers->count() - $pixWithReceipt;
	$currentDirection = $route && $route->has_return && isset($tripProgress['direction']) ? $tripProgress['direction'] : 'outbound';
	$outboundStops = $outboundStops ?? collect();
	$returnStops = $returnStops ?? collect();
	$tripProgressArr = $tripProgress['stops'] ?? [];
@endphp

<div class="stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-bottom: 15px;">
	<div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #343b71 100%); color: white; padding: 12px 15px; border-radius: 6px; text-align: center;">
		<h3>{{ $passengers->count() }}</h3>
		<p>Total de Passageiros</p>
	</div>
	<div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; padding: 12px 15px; border-radius: 6px; text-align: center;">
		<h3>{{ $pixPassengers->count() }}</h3>
		<p>Pagamento PIX</p>
	</div>
	@if($pixPending > 0)
	<div class="stat-card comprovante">
		<h3>{{ $pixPending }}</h3>
		<p>⚠️ Comprovante Pendente</p>
	</div>
	@else
	<div class="stat-card comprovante-ok">
		<h3>✓</h3>
		<p>Todos Comprovantes OK</p>
	</div>
	@endif
</div>

<div style="margin-bottom: 25px; position: relative;">
	<div id="map" style="width: 100%; height: 400px; border-radius: 8px; margin-bottom: 20px; position: relative;"></div>
	<div class="map-controls" style="position: absolute; top: 18px; left: 18px; z-index: 1000;">
		<button type="button" class="btn" id="fitMarkersBtn" style="background: white; color: #343b71; border: 2px solid #343b71; padding: 10px 15px; font-size: 0.9em; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
			🗺️ Mostrar Todos
		</button>
	</div>
</div>

@if($route && ($outboundStops->count() > 0 || $returnStops->count() > 0))
	<div class="trip-progress-box" style="background: white; border-radius: 8px; padding: 20px; margin-bottom: 15px; border: 2px solid #e9ecef;">
		<div class="trip-progress-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #e9ecef;">
			<h3>🚍 Progresso da Viagem</h3>
			<span class="direction-badge {{ $currentDirection === 'outbound' ? 'direction-outbound' : 'direction-return' }}">
				{{ $currentDirection === 'outbound' ? '➡️ Ida' : '⬅️ Retorno' }}
			</span>
		</div>
		<div class="progress-timeline-wrapper" style="overflow-y: auto; max-height: 400px; width: 100%;">
			@if($currentDirection === 'outbound' && $outboundStops->count() > 0)
				<div class="progress-timeline" style="width: 100%;">
					@php
						$lastConfirmedIndex = -1;
						foreach($outboundStops as $idx => $stop) {
							if (in_array($stop->id, $tripProgressArr)) {
								$lastConfirmedIndex = $idx;
							}
						}
					@endphp
					@foreach($outboundStops as $index => $stop)
						@php
							$isCompleted = $lastConfirmedIndex >= 0 && $index < $lastConfirmedIndex;
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
								<div class="progress-stop-name">{{ $stop->name }}</div>
								<div class="progress-stop-address">{{ $stop->address }}</div>
							</div>
						</div>
					@endforeach
				</div>
			@elseif($currentDirection === 'return' && $returnStops->count() > 0)
				<div class="progress-timeline" style="width: 100%;">
					@php
						$lastConfirmedIndex = -1;
						foreach($returnStops as $idx => $stop) {
							if (in_array($stop->id, $tripProgressArr)) {
								$lastConfirmedIndex = $idx;
							}
						}
					@endphp
					@foreach($returnStops as $index => $stop)
						@php
							$isCompleted = $lastConfirmedIndex >= 0 && $index < $lastConfirmedIndex;
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
								<div class="progress-stop-name">{{ $stop->name }}</div>
								<div class="progress-stop-address">{{ $stop->address }}</div>
							</div>
						</div>
					@endforeach
				</div>
			@else
				<div class="progress-empty" style="text-align: center; padding: 30px; color: #666; font-style: italic;">Nenhuma parada definida para esta direção</div>
			@endif
		</div>
	</div>
@endif

<div class="passenger-list" style="margin-top: 15px;">
	<h3 style="color: #343b71; margin-bottom: 12px; font-size: 1.1em;">Lista de Passageiros</h3>
	<div id="passengerListScroll" style="overflow-y: auto; max-height: 480px; border-radius: 8px; border: 1.5px solid #e9ecef; padding: 8px;">
		@if($passengers->isEmpty())
			<p style="text-align: center; color: #666; padding: 40px;">Nenhum passageiro reservado para os filtros selecionados.</p>
		@else
			@foreach($passengers as $passenger)
				<div class="passenger-item" data-name="{{ strtolower($passenger->name) }}" data-email="{{ strtolower($passenger->email) }}" data-payment="{{ $passenger->payment_method }}">
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
						<button class="btn btn-small btn-secondary" onclick="focusOnMarker({{ $passenger->latitude }}, {{ $passenger->longitude }})">Mostrar no mapa</button>
					</div>
				</div>
			@endforeach
		@endif
	</div>
</div>

@push('scripts')
<script>
	// Mapa para admin (apenas visualização)
	const passengers = @json($passengers);
	let map, markers = [];
	async function initMap() {
		const apiKey = '{{ config('services.google_maps.key') }}';
		if (!apiKey || apiKey === 'YOUR_GOOGLE_MAPS_API_KEY') {
			document.getElementById('map').innerHTML = '<div style="padding: 20px; text-align: center; color: #dc3545;"><strong>⚠️ Chave do Google Maps não configurada!</strong><br>Configure GOOGLE_MAPS_API_KEY no arquivo .env</div>';
			return;
		}
		const defaultLocation = { lat: -14.2350, lng: -51.9253 };
		const { Map } = await google.maps.importLibrary("maps");
		const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
		map = new Map(document.getElementById('map'), {
			center: defaultLocation,
			zoom: 4,
			mapId: 'PENDULO_RADAR_MAP',
			mapTypeControl: false,
		});
		markers = [];
		if (passengers.length > 0) {
			const bounds = new google.maps.LatLngBounds();
			for (let index = 0; index < passengers.length; index++) {
				const passenger = passengers[index];
				const position = {
					lat: parseFloat(passenger.latitude),
					lng: parseFloat(passenger.longitude)
				};
				const pin = new PinElement({
					background: passenger.payment_method === 'pix'
						? (passenger.receipt_path ? '#28a745' : '#dc3545')
						: '#343b71',
					borderColor: 'white',
					glyphColor: 'white',
					glyph: (index + 1).toString(),
					scale: 1.2,
				});
				const marker = new AdvancedMarkerElement({
					map: map,
					position: position,
					title: passenger.name,
					content: pin.element,
				});
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
				const infoWindow = new google.maps.InfoWindow({
					content: infoContent,
					pixelOffset: new google.maps.Size(0, -5),
					disableAutoPan: false,
					headerDisabled: true
				});
				marker.addListener('click', () => {
					markers.forEach(m => m.infoWindow?.close());
					infoWindow.open(map, marker);
				});
				marker.infoWindow = infoWindow;
				markers.push({ marker, position, infoWindow });
				bounds.extend(position);
			}
			map.fitBounds(bounds);
		}
		// Botão mostrar todos
		document.getElementById('fitMarkersBtn').onclick = function() {
			if (markers.length > 0) {
				const bounds = new google.maps.LatLngBounds();
				markers.forEach(({ position }) => {
					bounds.extend(position);
				});
				map.fitBounds(bounds);
			}
		};
	}
	window.initMap = initMap;

	// Focar marcador ao clicar em "Mostrar no mapa"
	window.focusOnMarker = function(lat, lng) {
		const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
		map.setCenter(position);
		map.setZoom(15);
		markers.forEach(({ marker, position: markerPos, infoWindow }) => {
			if (Math.abs(markerPos.lat - position.lat) < 0.0001 && Math.abs(markerPos.lng - position.lng) < 0.0001) {
				markers.forEach(m => m.infoWindow?.close());
				infoWindow.open(map, marker);
			}
		});
	}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&loading=async&callback=initMap"></script>
@endpush
