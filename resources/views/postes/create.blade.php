@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Créer un Nouveau Poste </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('postes.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="libelleposte" class="form-label">Nom du Poste <span class="text-danger">*</span></label>
                            <input id="libelleposte" type="text" class="form-control @error('libelleposte') is-invalid @enderror" 
                                   name="libelleposte" value="{{ old('libelleposte') }}" required autofocus maxlength="50">
                            @error('libelleposte')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="form-text">Ex: Vétérinaire Titulaire, ASV (Accueil), Toilettage Canin, etc.</div>
                        </div>

                        <div class="mb-3">
                            <label for="idpro" class="form-label">Rattaché au Professionnel <span class="text-danger">*</span></label>
                            <select id="idpro" class="form-control @error('idpro') is-invalid @enderror" name="idpro" required>
                                <option value="">Sélectionner une entité professionnelle</option>
                                @foreach ($professionnels as $pro)
                                    <option value="{{ $pro->idpro }}" {{ old('idpro') == $pro->idpro ? 'selected' : '' }}>
                                        {{ $pro->nompro }} ({{ $pro->libelletypeetablissement }})
                                    </option>
                                @endforeach
                            </select>
                            @error('idpro')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                Créer le Poste
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection