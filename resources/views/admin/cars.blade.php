@extends('layouts.app')

@section('title', 'Gerenciar Carros')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="color: #343b71; font-size: 2em; margin: 0;">🚙 Carros de {{ $driver->name }}</h1>
                <p style="color: #666; margin-top: 5px;">
                    📧 {{ $driver->email }} | 📞 {{ $driver->phone }}
                    @if($driver->route)
                        | 🛣️ {{ $driver->route->name }}
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.drivers') }}" class="btn" style="background: #6c757d; padding: 10px 20px; text-decoration: none; display: inline-block;">
                ← Voltar
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulário para adicionar carro -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="color: #343b71; margin-bottom: 15px; font-size: 1.3em;">➕ Adicionar Novo Carro</h3>
            <form action="{{ route('admin.cars.store', $driver->id) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div class="form-group" style="margin: 0;">
                        <label for="name" style="display: block; margin-bottom: 5px; font-weight: 600;">Nome do Carro</label>
                        <input type="text" id="name" name="name" placeholder="Ex: Gol Prata" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="departure_time" style="display: block; margin-bottom: 5px; font-weight: 600;">Horário Saída *</label>
                        <input type="time" id="departure_time" name="departure_time" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label for="return_time" style="display: block; margin-bottom: 5px; font-weight: 600;">Horário Retorno *</label>
                        <input type="time" id="return_time" name="return_time" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    </div>
                </div>
                <button type="submit" class="btn" style="margin-top: 15px; padding: 10px 20px;">
                    ✅ Adicionar Carro
                </button>
            </form>
        </div>

        <!-- Lista de carros -->
        <h3 style="color: #343b71; margin-bottom: 15px; font-size: 1.3em;">📋 Carros Cadastrados</h3>
        <div class="table-responsive" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
                <thead style="background: #343b71; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">Nome</th>
                        <th style="padding: 15px; text-align: left;">Rota</th>
                        <th style="padding: 15px; text-align: center;">Horário Saída</th>
                        <th style="padding: 15px; text-align: center;">Horário Retorno</th>
                        <th style="padding: 15px; text-align: center;">Status</th>
                        <th style="padding: 15px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($driver->cars as $car)
                        <tr style="border-bottom: 1px solid #e9ecef;">
                            <td style="padding: 15px;">
                                <strong>{{ $car->name }}</strong>
                            </td>
                            <td style="padding: 15px;">
                                @if($car->route)
                                    {{ $car->route->name }}
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                {{ \Carbon\Carbon::parse($car->departure_time)->format('H:i') }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                {{ \Carbon\Carbon::parse($car->return_time)->format('H:i') }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <form action="{{ route('admin.cars.toggle', [$driver->id, $car->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: {{ $car->is_active ? '#28a745' : '#6c757d' }}; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.9em;">
                                        {{ $car->is_active ? '✓ Ativo' : '✗ Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <form action="{{ route('admin.cars.delete', [$driver->id, $car->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este carro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.9em;">
                                        🗑️ Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: #999;">
                                Nenhum carro cadastrado ainda. Use o formulário acima para adicionar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
