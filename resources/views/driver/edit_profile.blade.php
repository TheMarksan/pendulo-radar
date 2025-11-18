@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="content-wrapper" style="padding: 40px 20px; max-width: 500px; margin: 0 auto;">
    <div class="card">
        <h2 style="color: #343b71; font-size: 2em; margin-bottom: 24px;">✏️ Editar Perfil</h2>
        <form method="POST" action="{{ route('driver.profile.update') }}">
            @csrf
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="name" style="font-weight: 600;">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name', $driver->name) }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="email" style="font-weight: 600;">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $driver->email) }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="phone" style="font-weight: 600;">Telefone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $driver->phone) }}" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="pix_key" style="font-weight: 600;">Chave PIX</label>
                <input type="text" id="pix_key" name="pix_key" value="{{ old('pix_key', $driver->pix_key) }}" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
            </div>
            <button type="submit" class="btn" style="background: #343b71; color: #fff; padding: 12px 24px; border-radius: 6px; font-size: 1.1em; width: 100%;">Salvar Alterações</button>
        </form>
        <div style="margin-top: 18px; text-align: center;">
            <a href="{{ route('driver.dashboard') }}" style="color: #343b71; text-decoration: underline;">← Voltar ao painel</a>
        </div>
    </div>
</div>
@endsection
