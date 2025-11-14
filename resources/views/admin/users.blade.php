@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>


        <h1 style="color: #343b71; margin-bottom: 30px;">👥 Gerenciar Usuários (Passageiros)</h1>

        <!-- Barra de pesquisa e filtros removidos -->

        @if(session('success'))
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

    <div>
        <table id="passengerTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">Nome</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: left;">Pagamento</th>
                    <th style="padding: 12px; text-align: left;">Data Cadastro</th>
                    <th style="padding: 12px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($passengers as $passenger)
                    <tr data-name="{{ strtolower($passenger->name) }}" data-email="{{ strtolower($passenger->email) }}" data-payment="{{ $passenger->payment_method }}" style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $passenger->name }}</td>
                        <td style="padding: 12px;">{{ $passenger->email }}</td>
                        <td style="padding: 12px;">
                            @if($passenger->payment_method === 'pix')
                                <span style="background: #e0f7fa; color: #17a2b8; padding: 4px 12px; border-radius: 12px; font-size: 0.95em; font-weight: 600;">PIX</span>
                            @else
                                <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 0.95em; font-weight: 600;">{{ ucfirst($passenger->payment_method) }}</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ $passenger->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <form action="{{ route('admin.users.reset', $passenger->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Resetar acesso? Nova senha será o email do usuário.')">
                                @csrf
                                <button type="submit" class="btn btn-small" style="background: #ffc107; padding: 5px 10px; font-size: 0.9em;">
                                    🔄 Resetar Acesso
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center; color: #999;">Nenhum usuário encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
<!-- Scripts de busca/filtro removidos -->
    </div>
</div>
@endsection
