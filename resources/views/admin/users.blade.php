@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Voltar ao Dashboard</a>


        <h1 style="color: #343b71; margin-bottom: 30px;">👥 Gerenciar Usuários (Passageiros)</h1>

        <!-- Barra de pesquisa e filtros removidos -->
        <form method="GET" action="{{ route('admin.users') }}" style="margin-bottom: 24px; max-width: 400px;">
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Pesquisar por nome ou email..." style="width: 100%; padding: 10px 14px; border: 1.5px solid #343b71; border-radius: 6px; font-size: 1em; color: #343b71; background: #e8f0ff;">
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#passengerTable tbody tr');
            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                tableRows.forEach(row => {
                    const name = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const email = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    if (name.includes(value) || email.includes(value)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
        </script>

        @if(session('success'))
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

    <div style="overflow-x: auto; width: 100%;">
        <table id="passengerTable" style="min-width: 600px; width: 100%; border-collapse: collapse;">
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
                        <td style="padding: 12px; text-align: center; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                            <form action="{{ route('admin.users.reset', $passenger->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Resetar acesso? Nova senha será o email do usuário.')">
                                @csrf
                                <button type="submit" class="btn" style="background: #ffc107; min-width: 110px; padding: 8px 14px; font-size: 0.98em; border-radius: 8px;">
                                    🔄 Resetar Acesso
                                </button>
                            </form>
                            <form action="{{ route('admin.users.delete', $passenger->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background: #dc3545; color: white; min-width: 110px; padding: 8px 14px; font-size: 0.98em; border-radius: 8px;">
                                    🗑️ Excluir
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
