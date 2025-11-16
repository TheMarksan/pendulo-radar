    @if(session('success'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
    @endif
    @if(session('error'))
        <script>window.addEventListener('DOMContentLoaded',function(){showToast(@json(session('error')),'error');});</script>
    @endif
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="color: #343b71;"> Painel Administrativo</h1>
            <a href="{{ route('admin.logout') }}" style="color: #dc3545; text-decoration: none; font-weight: bold;">Sair 🚪</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div style="background: #4285F4; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <h2 style="font-size: 3em; margin: 0;">{{ $stats['passengers'] }}</h2>
                <p style="margin: 10px 0 0 0;">Reservas</p>
            </div>
            <div style="background: #34A853; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <h2 style="font-size: 3em; margin: 0;">{{ $stats['drivers'] }}</h2>
                <p style="margin: 10px 0 0 0;">Motoristas</p>
            </div>
            <div style="background: #FBBC05; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <h2 style="font-size: 3em; margin: 0;">{{ $stats['routes'] }}</h2>
                <p style="margin: 10px 0 0 0;">Rotas</p>
            </div>
            <div style="background: #EA4335; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                <h2 style="font-size: 3em; margin: 0;">{{ $stats['active_keys'] }}</h2>
                <p style="margin: 10px 0 0 0;">Chaves Ativas</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <a href="{{ route('admin.allDriversDashboard') }}" class="btn" style="padding: 30px; text-align: center; text-decoration: none; background: #343b71; color: #fff;">
                <div style="font-size: 3em; margin-bottom: 10px;">🚌</div>
                <strong>Painel Geral dos Motoristas</strong>
            </a>
            <a href="{{ route('admin.users') }}" class="btn" style="padding: 30px; text-align: center; text-decoration: none;">
                <div style="font-size: 3em; margin-bottom: 10px;">👥</div>
                <strong>Gerenciar Usuários</strong>
            </a>

            <a href="{{ route('admin.access.keys') }}" class="btn btn-secondary" style="padding: 30px; text-align: center; text-decoration: none;">
                <div style="font-size: 3em; margin-bottom: 10px;">🔑</div>
                <strong>Chaves de Acesso</strong>
            </a>

            <a href="{{ route('admin.routes') }}" class="btn" style="padding: 30px; text-align: center; text-decoration: none; background: #28a745;">
                <div style="font-size: 3em; margin-bottom: 10px;">🗺️</div>
                <strong>Gerenciar Rotas</strong>
            </a>

            <a href="{{ route('admin.drivers') }}" class="btn" style="padding: 30px; text-align: center; text-decoration: none; background: #17a2b8;">
                <div style="font-size: 3em; margin-bottom: 10px;">🚗</div>
                <strong>Motoristas & Carros</strong>
            </a>
        </div>
    </div>
</div>
@endsection
