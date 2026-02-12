<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter ou Modifier un Planning Employé</title>
    
    <style>
        body {
            font-family: sans-serif; 
            padding: 20px; 
            background-color: #f8f9fa; 
        }

        .container { 
            max-width: 800px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }

        h2 { 
            border-bottom: 2px solid #007bff; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
            color: #007bff; 
        }

        label {
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
        }
        
        input, select, .form-control { 
            width: 100%; 
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #ced4da; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }
        
        button { 
            background-color: #28a745; 
            color: white; 
            padding: 12px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            transition: background-color 0.3s; 
        }

        button:hover { background-color: #1e7e34; }
        button.mode-edit { background-color: #ffc107; color: #212529; }
        button.mode-edit:hover { background-color: #e0a800; }

        .alert-error { 
            background-color: #f8d7da; 
            color: #721c24; 
            padding: 10px; 
            margin-bottom: 20px;
            border: 1px solid #f5c6cb; 
            border-radius: 4px; 
        }

        .error-message { color: #dc3545; 
            font-size: 0.9em;
            margin-top: -10px; 
            margin-bottom: 10px; 
        }

        .alert-success { 
            background-color: #d4edda; 
            color: #155724; 
            padding: 10px; 
            margin-bottom: 20px; 
            border: 1px solid #c3e6cb; 
            border-radius: 4px;
        }

        .alert-info { 
            background-color: #fff3cd; 
            color: #856404; 
            border-color: #ffeeba; 
            padding: 10px; 
            margin-bottom: 20px;
            border-radius: 4px; 
        }

        #existing-schedule-info {
            display: none;
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 5px solid #007bff;
        }

        .days-badges { margin-top: 10px; }

        .badge {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            margin-right: 5px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .badge:hover { opacity: 0.8; }

        .badge.active-day {
            background-color: #28a745; 
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }


        #new-horaire-fields input[type="time"] { 
            width: 48%; 
            display: inline-block; 
            margin-right: 1.5%; 
        }

        #new-horaire-fields input[type="time"]:nth-child(even) { margin-right: 0; }

        .select2-container .select2-selection--single { 
            height: 42px !important; 
            padding: 6px; 
            border: 1px solid #ced4da; 
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow { top: 8px !important; }

        .select2-container { 
            margin-bottom: 15px; 
            width: 100% !important; 
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 id="form-title">Ajouter un Planning Employé</h2>

        @if(session('success')) <div class="alert-success">{!! session('success') !!}</div> @endif
        @if(session('info')) <div class="alert-info">{!! session('info') !!}</div> @endif
        @if(session('error')) <div class="alert-error">{!! session('error') !!}</div> @endif
        @if ($errors->any())
            <div class="alert-error">
                <strong>Erreurs de validation:</strong>
                <ul>@foreach ($errors->all() as $error) <li>{!! $error !!}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('planning.store') }}" method="POST" id="planningForm">
            @csrf
            
            <label for="idpersonne">Employé :</label>
            <select name="idpersonne" id="idpersonne" required> 
                <option value="">-- Sélectionner un employé --</option>
                @foreach($employes as $employe)
    <option value="{{ $employe->idemploye }}" {{ old('idpersonne') == $employe->idemploye ? 'selected' : '' }}>
        {{ $employe->personne->nom }} {{ $employe->personne->prenom }}
    </option>
@endforeach
            </select>
            @error('idpersonne') <p class="error-message">{{ $message }}</p> @enderror

            <input type="hidden" name="idemploye" id="hidden_idemploye" value="{{ old('idemploye', '') }}">

            <div id="existing-schedule-info">
                <strong>Jours déjà planifiés :</strong>
                <div class="days-badges" id="days-list"></div>
                <small style="display:block; margin-top:8px; color:#555;">
                    Cliquez sur un jour pour modifier ses horaires (remplacement).
                </small>
            </div>

            <label for="idjour">Jour :</label>
            <select name="idjour" id="idjour" required>
                <option value="">-- Sélectionner un jour --</option>
                @foreach($jours as $jour)
                    <option value="{{ $jour->idjour }}" {{ old('idjour') == $jour->idjour ? 'selected' : '' }}> 
                        {{ $jour->idjour }} 
                    </option>
                @endforeach
            </select>
            @error('idjour') <p class="error-message">{{ $message }}</p> @enderror

            <p style="font-weight: bold; margin-top: 20px;">Définir les Heures (Matin et/ou Après-midi) :</p>

            <div id="new-horaire-fields">
                <label>Heures Matin (Début / Fin) :</label>
                <input type="time" name="ouverture_matin" id="ouverture_matin" value="{{ old('ouverture_matin') }}">
                <input type="time" name="fermeture_matin" id="fermeture_matin" value="{{ old('fermeture_matin') }}">
                
                @error('ouverture_matin') <p class="error-message">{{ $message }}</p> @enderror
                @error('fermeture_matin') <p class="error-message">{{ $message }}</p> @enderror
                
                <label>Heures Après-midi (Début / Fin) :</label>
                <input type="time" name="ouverture_aprem" id="ouverture_aprem" value="{{ old('ouverture_aprem') }}">
                <input type="time" name="fermeture_aprem" id="fermeture_aprem" value="{{ old('fermeture_aprem') }}">

                @error('ouverture_aprem') <p class="error-message">{{ $message }}</p> @enderror
                @error('fermeture_aprem') <p class="error-message">{{ $message }}</p> @enderror
            </div>
            
            <br>
            <button type="submit" id="submit-btn">Enregistrer le Planning</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#idpersonne').select2({
                placeholder: "-- Rechercher un employé --",
                allowClear: true,
                language: { noResults: () => "Aucun employé trouvé" }
            });
        });

        const allPlannings = @json($existingPlannings ?? []);
        const dataMap = {};
        const formatTime = (t) => t && t.length >= 5 ? t.substring(0, 5) : "";

        if (Array.isArray(allPlannings)) {
            allPlannings.forEach(p => {
                const empId = p.idemploye; 
                const jourId = p.idjour;
                let h = p.horaire ? p.horaire : p; 

                if (!dataMap[empId]) dataMap[empId] = {};
                dataMap[empId][jourId] = {
                    matin_start: formatTime(h.heuredebutmatine),
                    matin_end:   formatTime(h.heurefinmatine),
                    aprem_start: formatTime(h.heuredebutaprem),
                    aprem_end:   formatTime(h.heurefinaprem)
                };
            });
        }

        const hiddenIdEmploye = document.getElementById('hidden_idemploye');
        const selectJour = document.getElementById('idjour');
        const infoBox = document.getElementById('existing-schedule-info');
        const daysListContainer = document.getElementById('days-list');
        const submitBtn = document.getElementById('submit-btn');
        const formTitle = document.getElementById('form-title');
        
        const inputs = {
            m_start: document.getElementById('ouverture_matin'),
            m_end:   document.getElementById('fermeture_matin'),
            a_start: document.getElementById('ouverture_aprem'),
            a_end:   document.getElementById('fermeture_aprem')
        };

        $('#idpersonne').on('change', function() {
            hiddenIdEmploye.value = $(this).val(); 
            updateExistingDaysDisplay();
            checkAndFillForm();
        });

        selectJour.addEventListener('change', function() {
            checkAndFillForm();
            highlightSelectedDayBadge();
        });

        function updateExistingDaysDisplay() {
            const empId = $('#idpersonne').val();
            daysListContainer.innerHTML = ''; 

            if (empId && dataMap[empId]) {
                infoBox.style.display = 'block';
                const days = Object.keys(dataMap[empId]);
                
                if (days.length === 0) {
                    daysListContainer.innerHTML = '<em>Aucun jour planifié.</em>';
                } else {
                    days.forEach(day => {
                        const span = document.createElement('span');
                        span.className = 'badge';
                        span.id = 'badge-' + day;
                        span.textContent = day;
                        span.onclick = function() {
                            selectJour.value = day;
                            selectJour.dispatchEvent(new Event('change'));
                        };
                        daysListContainer.appendChild(span);
                    });
                }
            } else {
                infoBox.style.display = 'none';
            }
        }

        function highlightSelectedDayBadge() {
            document.querySelectorAll('.badge').forEach(b => b.classList.remove('active-day'));
            const selectedDay = selectJour.value;
            if (selectedDay) {
                const badge = document.getElementById('badge-' + selectedDay);
                if (badge) badge.classList.add('active-day');
            }
        }

        function checkAndFillForm() {
            const empId = $('#idpersonne').val();
            const jourId = selectJour.value;

            submitBtn.textContent = "Enregistrer le Planning";
            submitBtn.classList.remove('mode-edit');
            formTitle.textContent = "Ajouter un Planning Employé";

            if (empId && jourId && dataMap[empId] && dataMap[empId][jourId]) {
                const data = dataMap[empId][jourId];
                
                inputs.m_start.value = data.matin_start;
                inputs.m_end.value = data.matin_end;
                inputs.a_start.value = data.aprem_start;
                inputs.a_end.value = data.aprem_end;

                submitBtn.textContent = "Modifier ce Planning";
                submitBtn.classList.add('mode-edit');
                formTitle.textContent = "Modifier le Planning : " + jourId;
            }
        }

        if ($('#idpersonne').val()) {
            hiddenIdEmploye.value = $('#idpersonne').val();
            updateExistingDaysDisplay();
            if (selectJour.value) {
                checkAndFillForm();
                highlightSelectedDayBadge();
            }
        }
    </script>
</body>
</html>