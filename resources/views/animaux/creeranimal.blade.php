<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un animal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale;  
            
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            color: #1f2937; 
        }

        .modal-container {
            background: white;
            width: 100%;
            max-width: 900px;
            border-radius: 12px; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); 
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .modal-header {
            background-color: #39324b;
            color: white;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: 0.025em; 
        }
        
        .close-btn {
            color: #9ca3af;
            text-decoration: none;
            font-size: 24px;
            transition: color 0.2s;
        }
        .close-btn:hover { color: white; }

        .modal-body { padding: 32px; }

        h2.section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-top: 24px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .col {
            flex: 1;
            min-width: 150px;
            display: flex;
            flex-direction: column;
        }

        .col-small { flex: 0.5; }
        
        label {
            color: #6bcba5; 
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
        }
        
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px; 
            font-size: 14px;
            color: #374151;
            outline: none;
            height: 42px;
            background-color: white;
            transition: all 0.2s;
        }

        input:focus, select:focus {
            border-color: #6bcba5;
            box-shadow: 0 0 0 3px rgba(107, 203, 165, 0.2); 
        }

        .input-owner {
            border: 1px solid #6bcba5 !important;
            background-color: #f0fdf4 !important; 
        }

        .age-container {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f9fafb;
            padding: 4px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .age-input {
            width: 60px !important;
            text-align: center;
            background: transparent !important;
            border: none !important;
            font-weight: bold;
            height: 32px !important;
            box-shadow: none !important;
        }

        .age-label {
            color: #6b7280;
            font-size: 13px;
            padding-right: 8px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #6bcba5;
            cursor: pointer;
        }

        .form-footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        button[type="submit"] {
            background-color: #6bcba5;
            color: white;
            border: none;
            padding: 12px 32px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(107, 203, 165, 0.4);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        button[type="submit"]::before { 
            content: '\f00c'; 
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
        }
        button[type="submit"]:hover { 
            background-color: #5bb592; 
            transform: translateY(-1px);
        }

        #liste-clients {
            border: 1px solid #e5e7eb;
            max-height: 200px;
            overflow-y: auto;       
            display: none;          
            position: absolute;     
            background: white;
            width: 100%;
            z-index: 50;
            top: 45px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .client-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #4b5563;
        }
        .client-item:hover { background-color: #f0fdf4; color: #1f2937; }
        
        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            color: #991b1b;
            font-size: 14px;
        }
        .text-red { color: #ef4444; }
    </style>
</head>
<body>

    <div class="modal-container">
        <div class="modal-header">
            <h1>
                <i class="fa-solid fa-paw" style="color: white;"></i> 
                <span>Ajouter un animal</span>
            </h1>
            <a href="{{ route('animal.index') }}" class="close-btn">&times;</a>
        </div>

        <div class="modal-body">
            @if ($errors->any())
                <div class="error-message">
                    <strong>Attention :</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('animal.store') }}">
                @csrf
                <div class="form-row">
                    <div class="col" style="position: relative;">
                        <label>Propriétaire</label>
                        
                        <input type="hidden" name="idpersonne" id="real-client-id" required value="{{ old('idpersonne') }}">

                        @php
                            $oldClientName = '';
                            if(old('idpersonne')) {
                                $foundClient = $clients->firstWhere('idpersonne', old('idpersonne'));
                                if($foundClient) {
                                    $oldClientName = $foundClient->nom . ' ' . $foundClient->prenom;
                                }
                            }
                        @endphp

                        <div style="display: flex; gap: 5px;">
                            <input type="text" id="search-client" 
                                   class="input-owner"
                                   placeholder="Tapez un nom pour rechercher..." 
                                   autocomplete="off" 
                                   value="{{ $oldClientName }}">
                            <button type="button" style="background:#39324b; color:white; border:none; width:40px; border-radius:4px; cursor:pointer;">+</button>
                        </div>
                        
                        <div id="liste-clients">
                            @foreach($clients as $client)
                                <div class="client-item" 
                                     data-id="{{ $client->idpersonne }}" 
                                     data-search="{{ strtolower($client->nom . ' ' . $client->prenom) }}">
                                    <i class="fas fa-user"></i> {{ $client->nom }} {{ $client->prenom }} ({{ $client->tel }})
                                </div>
                            @endforeach
                        </div>
                        
                        <small id="selected-client-display" style="color: #6bcba5; font-weight: bold; margin-top:5px; display: {{ old('idpersonne') ? 'block' : 'none' }};">
                            <i class="fas fa-check"></i> Sélectionné : {{ $oldClientName }}
                        </small>
                    </div>
                </div>

                <h2 class="section-title">ANIMAL</h2>

                <div class="form-row">
                    <div class="col">
                        <label>Espèce <span class="text-red">*</span></label>
                        <select name="idespece" id="select-espece" required>
                            <option value="" disabled {{ old('idespece') ? '' : 'selected' }}>Choix...</option>
                            @foreach($especes as $espece)
                                <option value="{{ $espece->idespece }}" {{ old('idespece') == $espece->idespece ? 'selected' : '' }}>
                                    {{ $espece->libelleespece }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col">
                        <label>Race <span class="text-red">*</span></label>
                        <select name="idrace" id="select-race" required {{ old('idespece') ? '' : 'disabled' }}>
                            <option value="">-- Choisir espèce --</option>
                            @foreach($races as $race)
                                <option value="{{ $race->idrace }}" 
                                        data-espece="{{ $race->idespece }}"
                                        {{ old('idrace') == $race->idrace ? 'selected' : '' }}>
                                    {{ $race->libellerace }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col col-small">
                        <label>Sexe</label>
                        <select name="sexe">
                            <option value="1" {{ old('sexe') == "1" ? 'selected' : '' }}>Mâle</option>
                            <option value="0" {{ old('sexe') == "0" ? 'selected' : '' }}>Femelle</option>
                        </select>
                    </div>

                    <div class="col">
                        <label>Nom <span class="text-red">*</span></label>
                        <input type="text" name="nom1animal" required placeholder="Nom" value="{{ old('nom1animal') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <label>Tatouage / Puce <span class="text-red">*</span></label>
                        <input type="text" name="numtatouage" required maxlength="15" placeholder="N° identification" value="{{ old('numtatouage') }}">
                    </div>
                    <div class="col">
                        <label>Race de croisement</label>
                        <select name="rac_idrace" id="select-croisement" {{ old('idespece') ? '' : 'disabled' }}>
                            <option value="">-- Aucun --</option>
                            @foreach($races as $race)
                                <option value="{{ $race->idrace }}" 
                                        data-espece="{{ $race->idespece }}"
                                        {{ old('rac_idrace') == $race->idrace ? 'selected' : '' }}>
                                    {{ $race->libellerace }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <label>Date de naissance</label>
                        <input type="date" name="datenaissance" id="datenaissance" value="{{ old('datenaissance') }}">
                    </div>
                    
                    <div class="col">
                        <label>Âge</label>
                        <div class="age-container">
                            <input type="text" id="age-ans" class="age-input" readonly placeholder="0"> 
                            <span class="age-label">an(s) et</span>
                            <input type="text" id="age-mois" class="age-input" readonly placeholder="0"> 
                            <span class="age-label">mois</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <label>Castré/stérilisé</label>
                        <div class="checkbox-wrapper">
                            <input type="checkbox" name="sterilise" id="sterilise" {{ old('sterilise') ? 'checked' : '' }}>
                            <label for="sterilise">Oui</label>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit">Créer l'animal</button>
                </div>

            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const searchInput = document.getElementById('search-client');
        const listDiv = document.getElementById('liste-clients');
        const hiddenInput = document.getElementById('real-client-id');
        const items = document.querySelectorAll('.client-item');
        const displaySelected = document.getElementById('selected-client-display');

        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            if(term.length === 0) { listDiv.style.display = 'none'; return; }
            
            listDiv.style.display = 'block';
            let hasResults = false;
            items.forEach(function(item) {
                if(item.dataset.search.includes(term)) { 
                    item.style.display = 'block'; hasResults = true; 
                } else { 
                    item.style.display = 'none'; 
                }
            });
            if(!hasResults) listDiv.style.display = 'none';
        });

        items.forEach(function(item) {
            item.addEventListener('click', function() {
                hiddenInput.value = this.dataset.id;
                searchInput.value = this.innerText; 
                listDiv.style.display = 'none';
                if(displaySelected) {
                    displaySelected.style.display = 'block';
                    displaySelected.innerHTML = '<i class="fas fa-check"></i> Sélectionné : ' + this.innerText;
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && e.target !== listDiv) listDiv.style.display = 'none';
        });

        const especeSelect = document.getElementById('select-espece');
        const raceSelect = document.getElementById('select-race');
        const croisementSelect = document.getElementById('select-croisement');

        const allRaces = Array.from(raceSelect.options);
        const allCroisements = Array.from(croisementSelect.options);

        function updateRaces() {
            const selectedEspeceId = especeSelect.value;
            const currentRaceValue = raceSelect.value; 
            const currentCroisValue = croisementSelect.value;

            raceSelect.disabled = false;
            raceSelect.innerHTML = '<option value="">-- Choisir race --</option>';
            allRaces.forEach(function(option) {
                if (option.dataset.espece === selectedEspeceId) {
                    raceSelect.appendChild(option);
                }
            });

            croisementSelect.disabled = false;
            croisementSelect.innerHTML = '<option value="">-- Aucun --</option>';
            allCroisements.forEach(function(option) {
                if (option.dataset.espece === selectedEspeceId) {
                    croisementSelect.appendChild(option);
                }
            });

            if(currentRaceValue) raceSelect.value = currentRaceValue;
            if(currentCroisValue) croisementSelect.value = currentCroisValue;

            updateCroisementVisibility();
        }

        function updateCroisementVisibility() {
            const selectedRaceId = raceSelect.value;
            Array.from(croisementSelect.options).forEach(function(option) {
                if (option.value === selectedRaceId && selectedRaceId !== "") {
                    option.style.display = 'none';
                    option.disabled = true;
                    if (croisementSelect.value === selectedRaceId) {
                        croisementSelect.value = "";
                    }
                } else {
                    option.style.display = 'block';
                    option.disabled = false;
                }
            });
        }

        especeSelect.addEventListener('change', updateRaces);
        raceSelect.addEventListener('change', updateCroisementVisibility);

        if(especeSelect.value) { updateRaces(); }

        const dateInput = document.getElementById('dateNaissance');
        const ageAnsInput = document.getElementById('age-ans');
        const ageMoisInput = document.getElementById('age-mois');

        function calculateAge() {
            if (!dateInput.value) return;
            const birthDate = new Date(dateInput.value);
            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();
            
            if (months < 0 || (months === 0 && today.getDate() < birthDate.getDate())) {
                years--;
                months += 12;
            }
            if (today.getDate() < birthDate.getDate()) {
                months--;
                if(months < 0) months = 11;
            }
            ageAnsInput.value = years;
            ageMoisInput.value = months;
        }

        dateInput.addEventListener('change', calculateAge);
        if(dateInput.value) calculateAge();
    });
    </script>
</body>
</html>