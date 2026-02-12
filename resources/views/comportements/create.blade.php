@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Créer une Fiche de Comportement </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('comportements.store') }}">
                        @csrf

                        <h5 class="mt-3">Attributs Comportementaux</h5>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hygiene" class="form-label">Niveau d'Hygiène <span class="text-danger">*</span></label>
                                <select id="hygiene" class="form-control @error('hygiene') is-invalid @enderror" name="hygiene" required>
                                    <option value="">Sélectionner</option>
                                    @foreach ($hygieneOptions as $option)
                                        <option value="{{ $option }}" {{ old('hygiene') == $option ? 'selected' : '' }}>
                                            {{ ucfirst($option) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hygiene')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="traitcaractere" class="form-label">Trait de Caractère Dominant</label>
                                <input id="traitcaractere" type="text" class="form-control @error('traitcaractere') is-invalid @enderror" 
                                       name="traitcaractere" value="{{ old('traitcaractere') }}" maxlength="100">
                                @error('traitcaractere')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mt-4">Permissions et Protections</h5>
                        <hr>

                        <div class="row mb-3">
                            @php
                                $boolFields = [
                                    'griffeurmordeur' => 'Griffeur/Mordeur',
                                    'marcheautorise' => 'Marche Autorisée',
                                    'photosautorise' => 'Photos Autorisées',
                                    'protectionparasiteinterne' => 'Protection Parasite Interne',
                                    'protectionparasiteexterne' => 'Protection Parasite Externe',
                                ];
                            @endphp
                            
                            @foreach ($boolFields as $name => $label)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label d-block">{{ $label }} <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}_oui" value="1" required {{ old($name) === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $name }}_oui">Oui</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}_non" value="0" required {{ old($name) === '0' || old($name) === null ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $name }}_non">Non</label>
                                    </div>
                                    @error($name)
                                        <span class="text-danger d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <h5 class="mt-4">Niveaux de Sociabilité</h5>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="idsociabilite" class="form-label">Sociabilité 1 (Principale) <span class="text-danger">*</span></label>
                                <select id="idsociabilite" class="form-control @error('idsociabilite') is-invalid @enderror" name="idsociabilite" required>
                                    <option value="">Sélectionner</option>
                                    @foreach ($sociabilites as $soc)
                                        <option value="{{ $soc->idsociabilite }}" {{ old('idsociabilite') == $soc->idsociabilite ? 'selected' : '' }}>
                                            {{ $soc->libellesociabilite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idsociabilite')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="soc_idsociabilite" class="form-label">Sociabilité 2 (Secondaire) <span class="text-danger">*</span></label>
                                <select id="soc_idsociabilite" class="form-control @error('soc_idsociabilite') is-invalid @enderror" name="soc_idsociabilite" required>
                                    <option value="">Sélectionner</option>
                                    @foreach ($sociabilites as $soc)
                                        <option value="{{ $soc->idsociabilite }}" {{ old('soc_idsociabilite') == $soc->idsociabilite ? 'selected' : '' }}>
                                            {{ $soc->libellesociabilite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('soc_idsociabilite')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="soc_idsociabilite2" class="form-label">Sociabilité 3 (Tertiaire) <span class="text-danger">*</span></label>
                                <select id="soc_idsociabilite2" class="form-control @error('soc_idsociabilite2') is-invalid @enderror" name="soc_idsociabilite2" required>
                                    <option value="">Sélectionner</option>
                                    @foreach ($sociabilites as $soc)
                                        <option value="{{ $soc->idsociabilite }}" {{ old('soc_idsociabilite2') == $soc->idsociabilite ? 'selected' : '' }}>
                                            {{ $soc->libellesociabilite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('soc_idsociabilite2')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>


                        <h5 class="mt-4">Peurs Associées (Optionnel)</h5>
                        <hr>
                        
                        <div class="row mb-3">
                            @error('peurs_selectionnees')
                                <div class="alert alert-danger" role="alert"><strong>{{ $message }}</strong></div>
                            @enderror
                            @foreach ($peurs as $peur)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="peurs_selectionnees[]" value="{{ $peur->idpeur }}" 
                                            id="peur_{{ $peur->idpeur }}" 
                                            {{ in_array($peur->idpeur, old('peurs_selectionnees', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="peur_{{ $peur->idpeur }}">
                                            {{ $peur->libellepeur }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                Enregistrer le Comportement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection