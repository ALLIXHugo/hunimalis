@extends('layouts.app')

@section('content')
<style>
    * { box-sizing: border-box; }
    body, html {
        height: 100%; margin: 0; padding: 0;
        font-family: 'Figtree', 'Segoe UI', sans-serif; 
        background-color: #f7fafc;
    }

    .main-wrapper {
        display: flex; justify-content: center; align-items: center; min-height: 100vh; width: 100%;
        position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 999;
        background-color: #2d3748; 
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url("{{ asset('img/home_hero.webp') }}");
        background-size: cover; background-position: center; background-repeat: no-repeat;
    }

    .auth-card {
        background-color: white; width: 100%; max-width: 500px; padding: 30px;
        border-radius: 4px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); margin: auto; text-align: center;
    }

    h2.form-title { margin: 0 0 25px 0; font-size: 24px; font-weight: 800; color: #1a202c; text-align: left; }

    .alert-box { padding: 15px; border-radius: 4px; font-size: 14px; margin-bottom: 20px; text-align: left; border: 1px solid transparent; }
    .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
    .alert-danger ul { margin: 0; padding-left: 20px; }

    .info-text { color: #4a5568; font-size: 14px; margin-bottom: 25px; text-align: left; line-height: 1.5; }

    .code-container { display: flex; justify-content: center; margin-bottom: 25px; }
    .code-input-visual {
        width: 100%; background-color: #e2e8f0; border: none; border-radius: 6px; padding: 15px;
        font-size: 24px; text-align: center; letter-spacing: 15px; font-weight: bold; color: #1a202c; outline: none;
    }
    .code-input-visual:focus { box-shadow: 0 0 0 2px #cbd5e0; }

    .btn-submit {
        width: 100%; background-color: #2c3e50; color: white; padding: 15px;
        border: none; border-radius: 4px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background-color 0.2s;
    }
    .btn-submit:hover { background-color: #1a252f; }
</style>

<div class="main-wrapper">
    <div class="auth-card">
        
        <h2 class="form-title">Vérifiez votre email</h2>

        @if(session('success')) 
            <div class="alert-box alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="alert-box alert-danger">
                @if(session('error'))
                    <div>{{ session('error') }}</div>
                @endif
                
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <p class="info-text">
            Un code de vérification a été envoyé à l'adresse :<br>
            <strong>{{ $professionnel->melpro }}</strong>
        </p>

        <form method="POST" action="{{ route('professionnel.verifverifmail') }}">
            @csrf
            
            <input type="hidden" name="idpro" value="{{ $professionnel->idpro }}">

            <div class="code-container">
                <input type="text" name="code" id="code" class="code-input-visual" maxlength="6" placeholder="123456" required>
            </div>

            <button type="submit" class="btn-submit">Vérifier</button>
        </form>
    </div>
</div>
@endsection