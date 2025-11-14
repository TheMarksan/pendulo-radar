@extends('layouts.app')

@section('title', 'Gerenciar Motoristas')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="color: #343b71; font-size: 2em; margin: 0;">🚗 Motoristas</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn" style="background: #6c757d; padding: 10px 20px; text-decoration: none; display: inline-block;">
                ← Voltar
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
                <thead style="background: #343b71; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">Nome</th>
                        <th style="padding: 15px; text-align: left;">Email</th>
                        <th style="padding: 15px; text-align: left;">Telefone</th>
                        <th style="padding: 15px; text-align: left;">Rota</th>
                        <th style="padding: 15px; text-align: left;">PIX</th>
                        <th style="padding: 15px; text-align: center;">Horários</th>
                        <th style="padding: 15px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                        <tr style="border-bottom: 1px solid #e9ecef;">
                            <td style="padding: 15px;">{{ $driver->name }}</td>
                            <td style="padding: 15px;">{{ $driver->email }}</td>
                            <td style="padding: 15px;">{{ $driver->phone }}</td>
                            <td style="padding: 15px;">
                                @if($driver->route)
                                    <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;">
                                        {{ $driver->route->name }}
                                    </span>
                                @else
                                    <span style="color: #999;">Sem rota</span>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                @if($driver->pix_key)
                                    <code style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">{{ Str::limit($driver->pix_key, 20) }}</code>
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('admin.drivers.editSchedule', $driver->id) }}" class="btn" style="padding: 8px 16px; font-size: 0.9em; text-decoration: none; display: inline-block; background: #28a745; color: white;">
                                    ⏰ Editar Horários
                                </a>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <form action="{{ route('admin.drivers.delete', $driver->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza? Isso excluirá todos os carros deste motorista.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 0.9em;">
                                        🗑️ Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #999;">
                                Nenhum motorista cadastrado ainda
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 8px; border-left: 4px solid #0066cc;">
            <strong>ℹ️ Informação:</strong> Os motoristas se cadastram através do formulário público. Aqui você gerencia apenas os horários de cada motorista.
        </div>
    </div>
</div>
@endsection
