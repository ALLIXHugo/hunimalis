@extends('layouts.app')

@section('content')
<style>
    * { box-sizing: border-box; }
    body, html { height: 100%; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background-color: #f7fafc; }

    .main-wrapper {
        display: flex; justify-content: center; align-items: center; min-height: 100vh; width: 100%;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 999;
        background-color: #2d3748; 
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url("{{ asset('img/home_hero.webp') }}");
        background-size: cover; background-position: center; background-repeat: no-repeat;
    }

    .auth-card {
        background-color: white; width: 100%; max-width: 450px; padding: 40px;
        border-radius: 4px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); margin: auto; text-align: center;
    }

    h2.form-title { margin: 0 0 15px 0; font-size: 24px; font-weight: 800; color: #1a202c; text-align: left; }
    .info-text { color: #4a5568; font-size: 14px; margin-bottom: 25px; text-align: left; line-height: 1.5; }

    .code-input-visual {
        width: 100%; background-color: #e2e8f0; border: none; border-radius: 6px; padding: 15px;
        font-size: 24px; text-align: center; letter-spacing: 12px; font-weight: bold; color: #1a202c; outline: none; margin-bottom: 20px;
    }
    .code-input-visual:focus { box-shadow: 0 0 0 2px #cbd5e0; }

    .btn-submit {
        width: 100%; background-color: #2c3e50; color: white; padding: 14px;
        border: none; border-radius: 6px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background-color 0.2s;
    }
    .btn-submit:hover { background-color: #1a252f; }
    
    .alert-box { padding: 15px; border-radius: 4px; font-size: 14px; margin-bottom: 20px; text-align: left; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background-color: #fff5f5; color: #c53030; border: 1px solid #f5c6cb; }
</style>

<div class="main-wrapper">
    <div class="auth-card">
        
        <h2 class="form-title">Vérification du code</h2>

        @if (session('email'))
             <div class="alert-box alert-success">Un code a été envoyé à <strong>{{ session('email') }}</strong></div>
        @endif

        @if (session('error'))
            <div class="alert-box alert-error">{{ session('error') }}</div>
        @endif
        
        <p class="info-text">Veuillez saisir le code à 6 chiffres reçu par email.</p>

        <form action="{{ route('password.check') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

            <input type="text" name="code" class="code-input-visual" maxlength="6" placeholder="------" required autofocus>
            @error('code') <div style="color:red; font-size:12px; text-align:left; margin-bottom:10px;">{{ $message }}</div> @enderror

            <button type="submit" class="btn-submit">Vérifier le code</button>
        </form>
    </div>
</div>
@endsection