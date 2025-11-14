@extends('layouts.app')

@section('title', 'Motorista - Pendulo Radar')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 800px; margin: 0 auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #343b71; font-size: 2em;">🚗 Área do Motorista</h1>
            <p style="color: #666; margin-top: 10px;">Escolha uma opção para continuar</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
            <a href="{{ route('driver.login') }}" class="choice-card" style="background: white; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; text-decoration: none; display: block;">
                <div style="font-size: 4em; margin-bottom: 15px;">🔐</div>
                <h2 style="color: #343b71; font-size: 1.5em; margin-bottom: 10px;">Fazer Login</h2>
                <p style="color: #666;">Já tenho uma conta</p>
            </a>

            <a href="{{ route('driver.register') }}" class="choice-card" style="background: white; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; text-decoration: none; display: block;">
                <div style="font-size: 4em; margin-bottom: 15px;">📝</div>
                <h2 style="color: #343b71; font-size: 1.5em; margin-bottom: 10px;">Criar Conta</h2>
                <p style="color: #666;">Primeira vez aqui</p>
            </a>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('home') }}" class="back-link">← Voltar para Início</a>
        </div>
    </div>
</div>

<style>
    .choice-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(52, 59, 113, 0.2);
    }
</style>
@endsection
