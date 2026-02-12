@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-check-circle mr-2"></i>Finaliser ma réservation</h4>
            <a href="{{ route('client.pro.details', ['id' => $pro->idpro, 'date' => $date]) }}" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left"></i> Changer l'horaire
            </a>
        </div>
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rdv.client.store_confirm') }}" method="POST">
                @csrf

                <input type="hidden" name="idpro" value="{{ $pro->idpro }}">
                <input type="hidden" name="daterdv" value="{{ $date }}">
                <input type="hidden" name="heurerdv" value="{{ $heure }}">

                <div class="row">
                    <div class="col-md-5">
                        <div class="alert alert-info shadow-sm">
                            <h5 class="alert-heading font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Récapitulatif</h5>
                            <hr>
                            <p class="mb-1"><strong>Etablissement :</strong> {{ $pro->libelleetablissement }}</p>
                            <p class="mb-1"><strong>Date :</strong> {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}</p>
                            
                            <p class="mb-1"><strong>Heure :</strong> {{ $heure }}</p>
                            
                            <p class="mb-0 text-muted small mt-2">
                                <em>Le praticien sera attribué automatiquement.</em>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-uppercase text-muted small">Pour quel animal ?</label>
                            <select name="numtatouage" id="selectAnimal" class="form-control form-control-lg border-primary" required>
                                <option value="" selected>-- Sélectionner --</option>
                                @foreach($clients as $client)
                                    @foreach($client->animaux as $animal)
                                        <option value="{{ $animal->numtatouage }}" data-espece="{{ $animal->idespece }}">
                                            {{ $animal->nom1animal }} 
                                            ({{ $animal->espece->libelleespece ?? 'Espèce inconnue' }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            <small class="text-right d-block mt-1">
                                <a href="{{ route('clients.animals', ['open' => 1, 'redirect' => url()->full()]) }}" 
                                    class="text-[#2b90c7] hover:underline">
                                    <i class="fas fa-plus"></i> Créer une fiche animal
                                </a>                            
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-uppercase text-muted small">Prestation souhaitée</label>
                            
                            <select name="idprestation" id="selectPrestation" class="form-control form-control-lg" disabled style="background-color: #e9ecef; cursor: not-allowed;" required>
                                <option value="">-- Choisir une prestation --</option>
                                @foreach($prestations as $presta)
                                    <option value="{{ $presta->idprestation }}" data-espece="{{ $presta->idespece }}">
                                        {{ $presta->nomprestation }} ({{ $presta->duree }} min) - {{ number_format($presta->tarifht, 2) }}€
                                    </option>
                                @endforeach
                            </select>

                            <small id="prestaHelp" class="text-danger mt-2 d-block font-weight-bold">
                                <i class="fas fa-lock mr-1"></i> Veuillez d'abord sélectionner votre animal.
                            </small>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-success btn-lg btn-block shadow rounded-pill font-weight-bold">
                            Confirmer ce Rendez-vous <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const animalSelect = document.getElementById('selectAnimal');
    const prestationSelect = document.getElementById('selectPrestation');
    const helpText = document.getElementById('prestaHelp');
    
    const allPrestations = Array.from(prestationSelect.querySelectorAll('option'));

    animalSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const especeId = selectedOption.getAttribute('data-espece');

        prestationSelect.value = "";

        if (!especeId) {
            prestationSelect.disabled = true;
            prestationSelect.style.backgroundColor = "#e9ecef";
            prestationSelect.style.cursor = "not-allowed";
            helpText.style.display = "block";
        } else {
            prestationSelect.disabled = false;
            prestationSelect.style.backgroundColor = "#fff";
            prestationSelect.style.cursor = "pointer";
            helpText.style.display = "none";

            allPrestations.forEach(option => {
                if (option.value === "") return;

                const prestaEspece = option.getAttribute('data-espece');

                if (prestaEspece == especeId || !prestaEspece) {
                    option.style.display = 'block'; 
                    option.disabled = false; 
                } else {
                    option.style.display = 'none';  
                    option.disabled = true;         
                }
            });
        }
    });
});
</script>
@endsection