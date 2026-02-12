@extends('layouts.app') 

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

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
        background-color: rgba(0, 0, 0, 0.4); 
        z-index: 999;
    }

    .auth-card {
        background-color: #ffffff;
        width: 100%;
        max-width: 450px;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        margin: auto;
    }

    h2.form-title {
        margin: 0 0 25px 0;
        font-size: 24px;
        font-weight: 800;
        color: #1a202c;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #edf2f7;
        padding-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 15px;
        font-weight: 700;
        color: #4a5568;
    }

    .input-group {
        margin-bottom: 20px;
        width: 100%;
    }

    .password-container {
        position: relative;
        width: 100%; 
        display: block;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        display: block;
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 15px;
        color: #2d3748;
        background-color: #fff;
        height: 48px;
        transition: border-color 0.2s;
    }

    input:focus {
        outline: none;
        border-color: #3182ce; 
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background-color: #2c3e50; 
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background-color: #1a252f;
    }

    .btn-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px;
        background-color: white;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        color: #4a5568;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none; 
    }

    .btn-secondary:hover {
        background-color: #f7fafc;
        color: #2d3748;
    }

    .toggle-eye {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #a0aec0;
        background: none;
        border: none;
        padding: 0;
        display: flex;
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 25px 0;
        color: #718096;
        font-size: 14px;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }
    .divider span {
        padding: 0 10px;
    }

    a.link-forgot {
        color: #3182ce;
        font-size: 14px;
        text-decoration: none;
        font-weight: 500;
        display: block;
        text-align: right;
        margin-bottom: 20px;
    }
    a.link-forgot:hover {
        text-decoration: underline;
    }

    .alert-error {
        background-color: #fff5f5;
        border-left: 4px solid #f56565;
        color: #c53030;
        padding: 15px;
        border-radius: 4px;
        font-size: 0.9rem;
        margin-bottom: 20px;
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
        background-color: #ffffff;
        width: 100%;
        max-width: 450px;
        padding: 40px;
        border-radius: 12px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        margin: auto;
        position: relative;
        z-index: 1000;
    }

</style>

<div class="main-wrapper">
    <div class="auth-card">
        
        <h2 class="form-title">
            Se connecter
            <a href="{{ url('/') }}" style="text-decoration: none; color: #cbd5e0;" class="close-icon">&times;</a>
        </h2>

        @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Oups !</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="input-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                       placeholder="Saisissez votre email" value="{{ old('email') }}">
            </div>

            <div class="input-group" x-data="{ show: false }">
                <label for="password">Mot de passe</label>
                <div class="password-container">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required 
                           placeholder="Mot de passe">
                    
                    <button type="button" class="toggle-eye" @click="show = !show">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>

            <a href="{{ route('password.forgot') }}" class="link-forgot">Mot de passe oublié ?</a>

            
            <button type="submit" class="btn-submit">
                Se connecter
            </button>
        </form>

        <div class="divider">
            <span>Ou</span>
        </div>

        <a href="{{ route('register.form') }}" class="btn-secondary" style="background-color: #f7fafc;">
            Créer mon compte
        </a>

        <a href="{{ route('auth.google') }}" class="btn-secondary" style="text-decoration: none;">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width: 20px; height: 20px; margin-right: 10px;">
            Se connecter avec Google
        </a>

    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection