@extends('layouts.app')

@section('title', 'Painel Geral dos Motoristas')
@section('header', 'Painel Geral dos Motoristas')

@section('styles')
<style>
    .driver-select {
        width: 100%;
        max-width: 400px;
        margin-bottom: 25px;
        padding: 12px;
        border-radius: 8px;
        border: 2px solid #343b71;
        font-size: 1.1em;
    }
    .dashboard-section {
        margin-bottom: 40px;
    }
    @media (max-width: 600px) {
        .driver-select { max-width: 100%; font-size: 1em; }
    }
</style>

@stack('scripts')
@endsection

@section('content')
<div class="card">
    <div style="margin-bottom: 18px;">
        <a href="{{ route('admin.dashboard') }}" class="back-link" style="color: #343b71; text-decoration: none; font-weight: bold; font-size: 1.05em;">← Voltar ao Dashboard</a>
    </div>
    <h2 style="color: #343b71; font-size: 2em; margin-bottom: 20px;">Acompanhar Viagens dos Motoristas</h2>
    <form method="GET" action="" style="margin-bottom: 30px;">
        <label for="driver_id" style="font-weight: bold; color: #343b71;">Selecione o Motorista:</label>
        <select name="driver_id" id="driver_id" class="driver-select" onchange="this.form.submit()">
            <option value="">-- Escolha um motorista --</option>
            @foreach($drivers as $d)
                <option value="{{ $d->id }}" @if(request('driver_id') == $d->id) selected @endif>{{ $d->name }} ({{ $d->email }})</option>
            @endforeach
        </select>
    </form>
    @if($selectedDriver)
        <div class="dashboard-section">
            <h3 style="color: #343b71;">Painel do Motorista: {{ $selectedDriver->name }}</h3>
            @include('admin.partials.driver-dashboard', [
                'driver' => $selectedDriver,
                'passengers' => $passengers,
                'route' => $route,
                'outboundStops' => $outboundStops,
                'returnStops' => $returnStops,
                'tripProgress' => $tripProgress
            ])
        </div>
    @else
        <div style="color: #888; font-size: 1.1em; margin-top: 30px;">Selecione um motorista para visualizar o painel.</div>
    @endif
</div>
@endsection
