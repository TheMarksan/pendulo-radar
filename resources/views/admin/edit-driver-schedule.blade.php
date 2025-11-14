@extends('layouts.app')

@section('title', 'Editar Horários do Motorista')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 600px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.drivers') }}" class="back-link">← Voltar para Motoristas</a>
        <h1 style="color: #343b71; margin-bottom: 20px;">Editar Horários de {{ $driver->name }}</h1>
        <form action="{{ route('admin.drivers.updateSchedule', $driver->id) }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="departure_time" style="font-weight: 600; color: #343b71;">Horário de Saída (Ida)</label>
                <input type="time" id="departure_time" name="departure_time" value="{{ old('departure_time', $driver->departure_time) }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="return_time" style="font-weight: 600; color: #343b71;">Horário de Retorno</label>
                <input type="time" id="return_time" name="return_time" value="{{ old('return_time', $driver->return_time) }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <button type="submit" class="btn" style="padding: 12px 30px;">Salvar Horários</button>
        </form>
    </div>
</div>
@endsection
