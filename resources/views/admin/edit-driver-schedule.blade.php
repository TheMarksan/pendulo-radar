    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Editar Horários do Motorista')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 800px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.drivers') }}" class="back-link">← Voltar para Motoristas</a>
        <h1 style="color: #343b71; margin-bottom: 20px;">Horários de {{ $driver->name }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <h2 style="color: #343b71; font-size: 1.2em; margin-bottom: 10px;">Adicionar Novo Horário</h2>
        <form action="{{ route('admin.drivers.updateSchedule', $driver->id) }}" method="POST" style="margin-bottom: 30px;">
            @csrf
            <input type="hidden" name="action" value="add">
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="date" style="font-weight: 600; color: #343b71;">Data</label>
                <input type="date" id="date" name="date" value="{{ old('date') }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="departure_time" style="font-weight: 600; color: #343b71;">Horário de Saída (Ida)</label>
                <input type="time" id="departure_time" name="departure_time" value="{{ old('departure_time') }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="return_time" style="font-weight: 600; color: #343b71;">Horário de Retorno <span style="color:#888;font-weight:400;">(opcional)</span></label>
                <input type="time" id="return_time" name="return_time" value="{{ old('return_time') }}" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <button type="submit" class="btn" style="padding: 12px 30px;">Adicionar Horário</button>
        </form>

        <div class="table-responsive" style="margin-bottom: 20px; max-height: 350px; overflow-x: auto; overflow-y: auto;">
            <table style="min-width: 600px; width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
                <thead style="background: #343b71; color: white; position: sticky; top: 0; z-index: 2;">
                    <tr>
                        <th style="padding: 10px;">Data</th>
                        <th style="padding: 10px;">Saída</th>
                        <th style="padding: 10px;">Retorno</th>
                        <th style="padding: 10px;">Ativo?</th>
                        <th style="padding: 10px; text-align:center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($driver->schedules as $schedule)
                    <tr>
                        <form action="{{ route('admin.drivers.updateSchedule', $driver->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                            <td style="padding: 10px;">
                                <input type="date" name="date" value="{{ $schedule->date ? $schedule->date->format('Y-m-d') : '' }}" required style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; min-width: 120px;">
                            </td>
                            <td style="padding: 10px;">
                                <input type="time" name="departure_time" value="{{ $schedule->departure_time }}" required style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; min-width: 100px;">
                            </td>
                            <td style="padding: 10px;">
                                <input type="time" name="return_time" value="{{ $schedule->return_time }}" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; min-width: 100px;">
                            </td>
                            <td style="padding: 10px; text-align:center; min-width: 70px;">
                                <input type="checkbox" name="is_active" value="1" {{ $schedule->is_active ? 'checked' : '' }}>
                            </td>
                            <td style="padding: 10px; text-align:center; min-width: 120px;">
                                <button type="submit" class="btn" style="padding: 6px 16px; font-size: 0.95em;">Salvar</button>
                        </form>
                        <form action="{{ route('admin.drivers.updateSchedule', $driver->id) }}" method="POST" style="display:inline; margin-left: 8px;">
                            @csrf
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                            <button type="submit" class="btn btn-secondary" style="padding: 6px 16px; font-size: 0.95em; background: #dc3545;">Excluir</button>
                        </form>
                            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #999;">Nenhum horário cadastrado ainda.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
