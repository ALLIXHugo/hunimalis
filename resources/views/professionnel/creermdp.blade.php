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

    .alert-box { padding: 15px; border-radius: 4px; font-size: 14px; margin-bottom: 20px; text-align: left; }
    .alert-error { background-color: #fff5f5; color: #c53030; border: 1px solid #f5c6cb; }
    .alert-error ul { margin: 0; padding-left: 20px; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
</style>

<div class="main-wrapper">
    <div class="auth-card">
        
        <h2 class="form-title">Sécurisez votre compte</h2>
        
        <div class="alert-box alert-success" style="display:flex; align-items:center; gap:10px;">
            <i class="fas fa-check-circle"></i> 
            <span>Votre téléphone est vérifié.</span>
        </div>

        <p class="info-text">Veuillez choisir un mot de passe pour finaliser votre inscription.</p>

        @if ($errors->any())
            <div class="alert-box alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-box alert-error">
                <strong>Erreur technique :</strong> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('professionnel.enregistre') }}">
            @csrf
            
            <input type="hidden" name="idpro" value="{{ $pro->idpro }}">
            <input type="hidden" name="code" value="{{ $code }}">

            <div class="input-group">
                <label for="motdepasse">Mot de passe</label>
                <input type="password" id="motdepasse" name="motdepasse" class="form-input" required minlength="8" placeholder="Minimum 8 caractères">
            </div>

            <div class="input-group">
                <label for="motdepasse_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="motdepasse_confirmation" name="motdepasse_confirmation" class="form-input" required minlength="8" placeholder="Répétez le mot de passe">
            </div>

            <button type="submit" class="btn-submit">Terminer l'inscription</button>
        </form>
    </div>
</div>
@endsection