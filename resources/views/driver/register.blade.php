    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Cadastro de Motorista')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #343b71; font-size: 2em;">🚗 Cadastro de Motorista</h1>
            <p style="color: #666; margin-top: 10px;">Preencha os dados para criar sua conta</p>
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

        <form action="{{ route('driver.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nome Completo *</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="phone">Telefone *</label>
                <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}" placeholder="(82) 99999-9999">
            </div>

            <div class="form-group">
                <label for="pix_key">Chave PIX</label>
                <input type="text" id="pix_key" name="pix_key" value="{{ old('pix_key') }}" placeholder="Sua chave PIX (CPF, email, telefone ou aleatória)">
                <small style="color: #666; display: block; margin-top: 5px;">Será exibida para passageiros que escolherem pagamento via PIX</small>
            </div>

            <div class="form-group">
                <label for="password">Senha * (4-8 caracteres)</label>
                <input type="password" id="password" name="password" required minlength="4" maxlength="8">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Senha *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="4" maxlength="8">
            </div>

            <div class="form-group">
                <label for="access_key">Chave de Acesso do Admin *</label>
                <input type="text" id="access_key" name="access_key" required value="{{ old('access_key') }}" placeholder="Chave fornecida pelo administrador">
                <small style="color: #666; display: block; margin-top: 5px;">Entre em contato com o administrador para obter esta chave</small>
            </div>

            <div class="form-group">
                <label for="route_id">Rota *</label>
                <select id="route_id" name="route_id" required>
                    <option value="">Selecione uma rota</option>
                    @foreach(\App\Models\Route::where('is_active', true)->get() as $route)
                        <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                            {{ $route->name }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">O administrador irá criar e configurar seu carro/horários</small>
            </div>

            <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
                Criar Conta
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #666;">
                Já tem uma conta?
                <a href="{{ route('driver.login') }}" style="color: #343b71; font-weight: bold;">Fazer Login</a>
            </p>
            <a href="{{ route('home') }}" class="back-link" style="display: inline-block; margin-top: 10px;">← Voltar para Início</a>
        </div>
    </div>
</div>
@endsection
