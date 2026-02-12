@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-calendar-plus mr-2"></i>Nouveau Rendez-vous (Secrétariat)</h4>
            <a href="{{ route('rdv.employe.planning') }}" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left"></i> Retour Planning
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

            <form action="{{ route('rdv.employe.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="font-weight-bold text-muted text-uppercase small">Animal / Client</label>
                        <select name="numtatouage" id="selectAnimal" class="form-control select2" required>
                            <option value="" selected>-- Choisir un animal --</option>
                            @foreach($clients as $client)
                                <optgroup label="{{ $client->nom }} {{ $client->prenom }}">
                                    @foreach($client->animaux as $animal)
                                        <option value="{{ $animal->numtatouage }}" data-espece="{{ $animal->idespece }}">
                                            {{ $animal->nom1animal }} 
                                            ({{ $animal->espece->libelleespece ?? 'Espèce inconnue' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="font-weight-bold text-muted text-uppercase small">Prestation</label>
                        <select name="prestations[]" id="selectPrestation" class="form-control" disabled style="background-color: #e9ecef; cursor: not-allowed;" required>
                            <option value="">-- Choisir une prestation --</option>
                            @foreach($prestations as $presta)
                                <option value="{{ $presta->idprestation }}" data-espece="{{ $presta->idespece }}">
                                    {{ $presta->nomprestation }} ({{ $presta->duree }} min) - {{ $presta->tarifht }}€
                                </option>
                            @endforeach
                        </select>
                        <small id="prestaHelp" class="text-danger font-weight-bold mt-1 d-block">
                            <i class="fas fa-lock"></i> Veuillez d'abord sélectionner un animal.
                        </small>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Praticien</label>
                        <select name="idpracticien" class="form-control" required>
                            <option value="">-- Choisir un praticien --</option>
                            @foreach($practiciens as $p)
                                <option value="{{ $p->idpracticien }}" 
                                    {{ (isset($pre_praticien_id) && $pre_praticien_id == $p->idpracticien) ? 'selected' : '' }}>
                                    {{ $p->libellepracticien }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold">Date</label>
                        <input type="date" name="date_rdv" class="form-control" 
                               value="{{ $pre_date ?? date('Y-m-d') }}" 
                               readonly 
                               style="background-color: #e9ecef; cursor: not-allowed;">
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold">Heure</label>
                        <input type="time" name="heure_rdv" class="form-control" 
                               value="{{ $pre_heure ?? '' }}" 
                               readonly 
                               style="background-color: #e9ecef; cursor: not-allowed;">
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow">
                        <i class="fas fa-check mr-2"></i> Enregistrer le RDV
                    </button>
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