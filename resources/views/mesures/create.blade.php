@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nouveau Relevé de Mesures 📏</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('mesures.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="numtatouage" class="form-label">Animal <span class="text-danger">*</span></label>
                            <select id="numtatouage" class="form-control @error('numtatouage') is-invalid @enderror" name="numtatouage" required>
                                <option value="">Choisir...</option>
                                @foreach ($animaux as $animal)
                                    <option value="{{ $animal->numtatouage }}" {{ old('numtatouage') == $animal->numtatouage ? 'selected' : '' }}>
                                        {{ $animal->nom1animal }}
                                    </option>
                                @endforeach
                            </select>
                            @error('numtatouage')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_mesure" class="form-label">Date du relevé <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date_mesure') is-invalid @enderror" 
                                   id="date_mesure" name="date_mesure" 
                                   value="{{ old('date_mesure', date('Y-m-d')) }}" required> 
                            @error('date_mesure')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="poids" class="form-label">Poids (kg)</label>
                                <input type="number" step="0.01" class="form-control @error('poids') is-invalid @enderror" 
                                       id="poids" name="poids" value="{{ old('poids') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="taille" class="form-label">Taille (cm)</label>
                                <input type="number" step="0.1" class="form-control @error('taille') is-invalid @enderror" 
                                       id="taille" name="taille" value="{{ old('taille') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="temperature" class="form-label">Température (°C)</label>
                                <input type="number" step="0.1" class="form-control @error('temperature') is-invalid @enderror" 
                                       id="temperature" name="temperature" value="{{ old('temperature') }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Enregistrer la mesure</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection