@extends('layouts.app')

@section('title', 'Primeiro Acesso - Troca de Senha')

@section('content')
<style>
    .first-access-label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #343b71;
    }
    .first-access-input {
        width: 100%;
        font-size: 1rem;
        border-radius: 5px;
        border: 1.5px solid #343b71;
        padding: 0 10px;
        margin-bottom: 18px;
        background: #e8f0ff;
        transition: border-color 0.2s;
        height: 38px;
        color: #343b71;
    }
    .first-access-input:focus {
        border-color: #0d6efd;
        outline: none;
        background: #fff;
        color: #343b71;
    }
    .first-access-btn {
        display: block;
        margin: 0 auto;
        width: 220px;
        max-width: 100%;
        padding: 10px 0;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 5px;
        background: #343b71;
        color: #fff;
        border: none;
        transition: background 0.2s;
        text-align: center;
    }
    .first-access-btn:hover {
        background: #0d6efd;
    }
    .first-access-header {
        font-size:1.4rem;
        font-weight:600;
        letter-spacing:0.5px;
        color:#343b71;
        background:#e8f0ff;
        padding: 1.2rem 1.5rem 0.8rem 1.5rem;
        text-align: left !important;
        border-bottom: 1px solid #e0e7ef;
    }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header first-access-header">
                    Primeiro acesso - Defina uma nova senha
                </div>
                <div class="card-body pt-4 pb-3 px-4">
                    <form method="POST" action="{{ route('driver.update.first.access') }}">
                        @csrf
                        <label for="password" class="first-access-label">Nova senha</label>
                        <input type="password" name="password" id="password" class="first-access-input" required minlength="4">

                        <label for="password_confirmation" class="first-access-label">Confirme a nova senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="first-access-input" required minlength="4">

                        <button type="submit" class="first-access-btn">Salvar nova senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
