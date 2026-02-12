@extends('layouts.app')

@section('content')

<style>
    .custom-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .custom-h2 { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; color: #007bff; font-size: 24px; }
    .custom-label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
    .custom-input, .custom-select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; font-family: sans-serif; }
    
    .custom-textarea { 
        width: 100%; 
        padding: 10px; 
        margin-bottom: 15px; 
        border: 1px solid #ced4da; 
        border-radius: 4px; 
        box-sizing: border-box; 
        font-family: sans-serif; 
        resize: none; 
    }

    .custom-button { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; width: 100%; }
    .custom-button:hover { background-color: #1e7e34; }
    .custom-alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px; }
    .custom-error-message { color: #dc3545; font-size: 0.9em; margin-top: -10px; margin-bottom: 10px; }
    .custom-alert-success { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px; }
    .cancel-link { display: block; text-align: center; margin-top: 15px; color: #6c757d; text-decoration: none; }
    .cancel-link:hover { text-decoration: underline; }
</style>

<div class="custom-container">
    <h2 class="custom-h2">Validation de votre société</h2>

    <p style="margin-bottom: 20px; color: #666;">
        Bonjour, pour finaliser l'inscription de votre activité sur <strong>Hunimalis</strong>, 
        nous avons besoin de convenir d'un rendez-vous d'audit.
    </p>

    @if(session('success'))
        <div class="custom-alert-success">
            {!! session('success') !!}
        </div>
    @endif

    @if ($errors->any())
        <div class="custom-alert-error">
            <strong>Erreurs de validation:</strong>
            <ul style="margin-top: 5px; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pro.validation.store') }}" method="POST">
        @csrf

        <label for="date_proposee" class="custom-label">Date souhaitée :</label>
        <input 
            type="date" 
            name="date_proposee" 
            id="date_proposee" 
            class="custom-input"
            value="{{ old('date_proposee') }}" 
            required
        >
        @error('date_proposee')
            <p class="custom-error-message">{{ $message }}</p>
        @enderror

        <label for="heure_proposee" class="custom-label">Heure souhaitée :</label>
        <input 
            type="time" 
            name="heure_proposee" 
            id="heure_proposee" 
            class="custom-input"
            value="{{ old('heure_proposee') }}" 
            required
        >
        @error('heure_proposee')
            <p class="custom-error-message">{{ $message }}</p>
        @enderror

        <label for="message_pro" class="custom-label">Message ou précisions (optionnel) :</label>
        <textarea 
            name="message_pro" 
            id="message_pro" 
            rows="4" 
            class="custom-textarea"
            placeholder="Ex: Je suis disponible uniquement le matin..."
        >{{ old('message_pro') }}</textarea>
        @error('message_pro')
            <p class="custom-error-message">{{ $message }}</p>
        @enderror

        <button type="submit" class="custom-button">Envoyer la proposition</button>
        
        <a href="/" class="cancel-link">Annuler</a>
    </form>
</div>

@endsection