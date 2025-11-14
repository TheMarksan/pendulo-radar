@extends('layouts.app')

@section('title', 'Primeiro Acesso')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 500px; margin: 0 auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #343b71; font-size: 2em;">🔑 Primeiro Acesso</h1>
            <p style="color: #666; margin-top: 10px;">Olá, {{ $admin->name }}! Defina sua nova senha.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>⚠️ Atenção:</strong> Sua senha atual é igual ao seu email. Por segurança, defina uma nova senha.
        </div>

        <form action="{{ route('admin.update.first.access') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="password">Nova Senha (mínimo 6 caracteres)</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Nova Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6">
            </div>

            <button type="submit" class="btn" style="width: 100%;">
                Atualizar Senha
            </button>
        </form>
    </div>
</div>
@endsection
