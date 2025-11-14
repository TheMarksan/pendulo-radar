@extends('layouts.app')

@section('title', 'Login Administrador')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 500px; margin: 0 auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #343b71; font-size: 2em;">🔐 Admin Login</h1>
            <p style="color: #666; margin-top: 10px;">Área do Administrador</p>
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

        <form action="{{ route('admin.authenticate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn" style="width: 100%;">
                Entrar
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('home') }}" class="back-link" style="display: inline-block; margin-top: 10px;">← Voltar para Início</a>
        </div>
    </div>
</div>
@endsection
