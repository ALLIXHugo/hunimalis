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

    .input-group { margin-bottom: 20px; text-align: left; }
    .input-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; }
    
    .form-input {
        width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px;
        font-size: 15px; color: #2d3748; transition: border-color 0.2s;
    }
    .form-input:focus { outline: none; border-color: #3182ce; box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1); }

    .btn-submit {
        width: 100%; background-color: #2c3e50; color: white; padding: 14px;
        border: none; border-radius: 6px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background-color 0.2s;
    }
    .btn-submit:hover { background-color: #1a252f; }

    .back-link { display: block; margin-top: 15px; color: #718096; text-decoration: none; font-size: 14px; }
    .back-link:hover { color: #2d3748; text-decoration: underline; }

    .alert-box { padding: 15px; border-radius: 4px; font-size: 14px; margin-bottom: 20px; text-align: left; }
    .alert-error { background-color: #fff5f5; color: #c53030; border: 1px solid #f5c6cb; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
</style>

<div class="main-wrapper">
    <div class="auth-card">
        
        <h2 class="form-title">Mot de passe oublié</h2>
        <p class="info-text">Renseignez votre email pour recevoir un code de réinitialisation.</p>

        @if (session('error'))
            <div class="alert-box alert-error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert-box alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('password.send') }}" method="POST">
            @csrf
            
            <div class="input-group">
                <label for="email">Adresse Email</label>
                <input id="email" name="email" type="email" class="form-input" required placeholder="exemple@mail.com" value="{{ old('email') }}">
                @error('email') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-submit">Envoyer le code</button>
            
            <a href="{{ route('login') }}" class="back-link">Retour à la connexion</a>
        </form>
    </div>
</div>
@endsection