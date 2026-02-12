@extends('layouts.app') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@section('content')
<style>
    * { box-sizing: border-box; }
    body, html {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f7fafc;
    }

    .main-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        width: 100%;
        padding: 20px;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 999;
        background-image: 
            linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
            url("{{ asset('img/home_hero.webp') }}");
        
        background-size: cover;      
        background-position: center; 
        background-repeat: no-repeat;
        background-attachment: fixed; 
    }

    .auth-card {
        background-color: white;
        width: 100%;
        max-width: 500px;
        padding: 30px;
        border-radius: 4px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        margin: auto;
        position: relative;
    }


    h2.form-title {
        margin: 0 0 25px 0;
        font-size: 24px; 
        font-weight: 800;
        color: #1a202c;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 24px;
        color: #a0aec0;
        cursor: pointer;
        line-height: 1;
    }

    .alert-info {
        background-color: #daeaff; 
        color: #2b6cb0;
        padding: 15px;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
        border: 1px solid #bee3f8;
    }
    .alert-info svg {
        margin-right: 10px;
        flex-shrink: 0;
        width: 18px; 
        height: 18px;
    }

    .code-container {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 25px;
    }

    .code-input-visual {
        width: 100%;
        background-color: #e2e8f0;
        border: none;
        border-radius: 6px;
        padding: 15px;
        font-size: 24px;
        text-align: center;
        letter-spacing: 15px; 
        font-weight: bold;
        color: #1a202c;
        outline: none;
    }
    .code-input-visual:focus {
        box-shadow: 0 0 0 2px #cbd5e0;
    }

    .merged-group {
        display: flex;
        border: 1px solid #cbd5e0; 
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 15px;
        background-color: white;
    }
    
    .merged-label {
        background-color: #edf2f7; 
        color: #718096;
        padding: 0 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        min-width: 90px; 
        border-right: 1px solid #cbd5e0;
    }

    .merged-input {
        flex: 1;
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        color: #2d3748;
        outline: none;
        height: 42px;
    }
    
    .merged-group:focus-within {
        border-color: #a0aec0;
    }

    .icons-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
    }
    .animal-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #718096;
        font-size: 12px;
    }
    .icon-circle {
        width: 40px; 
        height: 40px;
        background-color: #fff; 
        border-radius: 50%;
        display: flex; 
        align-items: center; 
        justify-content: center;
        margin-bottom: 5px;
    }
    .icon-circle img {
        width: 30px; height: 30px;
    }

    .gender-container {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 10px;
        margin-bottom: 30px;
    }
    .gender-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
    }
    .gender-circle {
        width: 50px; 
        height: 50px;
        border: 1px solid #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
        transition: all 0.2s;
    }

    input[type="radio"]:checked + .gender-circle {
        border-color: #2c3e50;
        background-color: #edf2f7;
        color: #2c3e50;
    }

    .hidden-radio {
        display: none;
    }
    .gender-label {
        font-size: 12px;
        color: #718096;
    }

    .btn-submit {
        width: 100%;
        background-color: #2c3e50; 
        color: white;
        padding: 15px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-submit:hover {
        background-color: #1a252f;
    }

    .flag-icon {
        font-size: 16px;
        margin-right: 5px;
    }
    
    .error-list {
        background: #fff5f5; 
        color: #c53030; 
        padding: 10px; 
        margin-bottom: 15px; 
        font-size: 13px;
        border-radius: 4px;
    }
</style>

<div class="main-wrapper">
    <div class="auth-card">
        
        <h2 class="form-title">S'inscrire
        <a href="{{ url('/') }}" style="text-decoration: none; color: #cbd5e0;" class="close-icon">&times;</a>

        </h2> 
        <div class="alert-info">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
            </svg>
            <div>
                Renseignez ci-dessous le <strong>code reçu par e-mail</strong> pour valider votre compte.
            </div>
        </div>

        <form action="{{ route('utilisateur.validercode') }}" method="POST">
            @csrf
            <input type="hidden" name="idutilisateur" value="{{ $utilisateur->idutilisateur }}">

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="code-container">
                <input type="text" name="code" class="code-input-visual" maxlength="6" value="" placeholder="------">
            </div>

            <div class="merged-group">
                <div class="merged-label">Nom</div>
                <input type="text" name="nom" class="merged-input" placeholder="Dupont" value="{{ old('nom') }}">
            </div>

            <div class="merged-group">
                <div class="merged-label">Prénom</div>
                <input type="text" name="prenom" class="merged-input" placeholder="Jean" value="{{ old('prenom') }}">
            </div>

            <div class="merged-group">
                <div class="merged-label">
                    <span class="flag-icon">Telephone </span> 
                </div>
                <input type="text" name="tel" class="merged-input" placeholder="0612345678" value="{{ old('tel') }}">
            </div>

            <hr style="border: 0; border-top: 1px solid #edf2f7; margin: 25px 0;">


            <button type="submit" class="btn-submit">Valider</button>
        </form>
    </div>
</div>
@endsection

