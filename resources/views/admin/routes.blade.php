@extends('layouts.app')

@section('title', 'Gerenciar Rotas')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>

        <h1 style="color: #343b71; margin-bottom: 30px;">🗺️ Gerenciar Rotas</h1>

        @if(session('success'))
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.routes.store') }}" method="POST" style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            @csrf
            <h3 style="margin-bottom: 15px;">Nova Rota</h3>
            <div class="form-group">
                <label for="name">Nome da Rota *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="has_return" name="has_return" value="1" style="width: 20px; height: 20px;">
                    <span>🔄 Esta rota possui viagem de retorno</span>
                </label>
                <small style="color: #666; display: block; margin-top: 5px; margin-left: 30px;">
                    Marque se esta rota tem paradas de ida e volta
                </small>
            </div>
            <button type="submit" class="btn">Criar Rota</button>
        </form>

        <div style="margin-bottom: 18px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <label for="routeDirection" style="font-weight: 600; color: #343b71;">Tipo de Viagem:</label>
            <select id="routeDirection" style="min-width: 140px; max-width: 220px; padding: 10px 16px; border: 2px solid #343b71; border-radius: 8px; font-size: 1em;">
                <option value="all">Todas</option>
                <option value="outbound">Ida</option>
                <option value="return">Retorno</option>
            </select>
        </div>
        <div style="display: grid; gap: 20px;" id="routesList">
            @forelse($routes as $route)
                <div class="route-card" data-has-return="{{ $route->has_return ? '1' : '0' }}">
                    <div style="border: 2px solid #dee2e6; border-radius: 10px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <h3 style="margin-bottom: 10px;">{{ $route->name }}</h3>
                                <p style="color: #666; margin-bottom: 10px;">{{ $route->description }}</p>
                                <p style="margin: 0;">
                                    <strong>Paradas:</strong> {{ $route->stops->count() }}
                                    @if($route->has_return)
                                        <span style="margin-left: 10px; padding: 3px 8px; background: #fff3cd; color: #856404; border-radius: 5px; font-size: 0.85em;">
                                            🔄 Com Retorno
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <span style="padding: 5px 15px; border-radius: 12px; {{ $route->is_active ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' }}">
                                {{ $route->is_active ? '✓ Ativa' : '✗ Inativa' }}
                            </span>
                        </div>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <a href="{{ route('admin.stops', $route->id) }}" class="btn btn-small">📍 Gerenciar Paradas</a>
                            <form action="{{ route('admin.routes.toggle', $route->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-small" style="background: {{ $route->is_active ? '#ffc107' : '#28a745' }};">
                                    {{ $route->is_active ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.routes.delete', $route->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Excluir esta rota e todas as paradas?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small" style="background: #dc3545;">🗑️ Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #999; padding: 40px;">Nenhuma rota cadastrada</p>
            @endforelse
        </div>
@section('scripts')
<script>
const routeDirection = document.getElementById('routeDirection');
const routesList = document.getElementById('routesList');
routeDirection.addEventListener('change', function() {
    const val = this.value;
    document.querySelectorAll('.route-card').forEach(card => {
        if (val === 'all') {
            card.style.display = '';
        } else if (val === 'outbound') {
            card.style.display = '';
        } else if (val === 'return') {
            card.style.display = card.getAttribute('data-has-return') === '1' ? '' : 'none';
        }
    });
});
</script>
@endsection
    </div>
</div>
@endsection
