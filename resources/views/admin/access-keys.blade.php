@extends('layouts.app')

@section('title', 'Chaves de Acesso')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>

        <h1 style="color: #343b71; margin-bottom: 30px;">🔑 Chaves de Acesso para Motoristas</h1>

        @if(session('success'))
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.access.keys.store') }}" method="POST" style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            @csrf
            <h3 style="margin-bottom: 15px;">Nova Chave de Acesso</h3>
            <div class="form-group">
                <label for="description">Descrição (Opcional)</label>
                <input type="text" id="description" name="description" placeholder="Ex: Chave para motoristas de janeiro">
            </div>
            <button type="submit" class="btn">Gerar Nova Chave</button>
        </form>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">Chave</th>
                    <th style="padding: 12px; text-align: left;">Descrição</th>
                    <th style="padding: 12px; text-align: center;">Usos</th>
                    <th style="padding: 12px; text-align: center;">Status</th>
                    <th style="padding: 12px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keys as $key)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;"><code style="background: #e9ecef; padding: 5px 10px; border-radius: 4px;">{{ $key->key }}</code></td>
                        <td style="padding: 12px;">{{ $key->description ?? '-' }}</td>
                        <td style="padding: 12px; text-align: center;">{{ $key->usage_count }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="padding: 5px 10px; border-radius: 12px; font-size: 0.85em; {{ $key->is_active ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' }}">
                                {{ $key->is_active ? '✓ Ativa' : '✗ Inativa' }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <form action="{{ route('admin.access.keys.toggle', $key->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-small" style="padding: 5px 10px; font-size: 0.9em; background: {{ $key->is_active ? '#ffc107' : '#28a745' }};">
                                    {{ $key->is_active ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.access.keys.delete', $key->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Excluir esta chave?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small" style="padding: 5px 10px; font-size: 0.9em; background: #dc3545;">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center; color: #999;">Nenhuma chave cadastrada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
