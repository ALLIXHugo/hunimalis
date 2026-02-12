@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800"><i class="fas fa-calendar-alt text-primary mr-2"></i>Calendrier Hebdomadaire</h2>
        <a href="{{ route('planning.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-cog mr-1"></i> Gérer les horaires
        </a>
    </div>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body py-3 bg-white rounded">
            <form action="{{ route('rdv.employe.planning') }}" method="GET" class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center">
                    <label class="mr-2 font-weight-bold text-dark mb-0">Agenda de :</label>
                    <select name="idpracticien" class="form-control border-primary" onchange="this.form.submit()" style="min-width: 250px;">
                        @foreach($practiciens as $p)
                            <option value="{{ $p->idpracticien }}" {{ $currentPracticien->idpracticien == $p->idpracticien ? 'selected' : '' }}>
                                {{ $p->libellepracticien }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('pro.dashboard') }}" class="bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm flex items-center justify-center gap-2 w-full lg:w-auto h-[46px]">
                <i class="fa-solid fa-table-columns"></i> <span class="whitespace-nowrap">Espace Pro</span>
            </a>

                <div class="d-flex align-items-center mt-2 mt-md-0">
                    <a href="{{ route('rdv.employe.planning', ['idpracticien' => $currentPracticien->idpracticien, 'date' => $dateReference->copy()->subWeek()->format('Y-m-d')]) }}" class="btn btn-light border btn-sm mr-2 shadow-sm">
                        <i class="fas fa-chevron-left"></i> Précédent
                    </a>
                    <span class="font-weight-bold text-dark mx-3 h5 mb-0 text-capitalize">
                        {{ $jours[0]['readable'] }} - {{ $jours[6]['readable'] }}
                    </span>
                    <a href="{{ route('rdv.employe.planning', ['idpracticien' => $currentPracticien->idpracticien, 'date' => $dateReference->copy()->addWeek()->format('Y-m-d')]) }}" class="btn btn-light border btn-sm ml-2 shadow-sm">
                        Suivant <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row flex-nowrap overflow-auto pb-3">
        @foreach($jours as $jour)
            <div class="col" style="min-width: 240px; max-width: 14.28%;">
                <div class="card h-100 shadow-sm border-0">
                    
                    <div class="card-header text-center py-2 {{ $jour['date'] == date('Y-m-d') ? 'bg-primary text-white' : 'bg-white border-bottom' }}">
                        <strong class="d-block text-uppercase small">{{ \Carbon\Carbon::parse($jour['date'])->translatedFormat('l') }}</strong>
                        <span class="h5 font-weight-bold">{{ \Carbon\Carbon::parse($jour['date'])->format('d/m') }}</span>
                    </div>

                    <div class="card-body p-2 bg-light d-flex flex-column custom-scrollbar" style="max-height: 70vh; overflow-y: auto;">
    
                        @if($jour['horaire'])
                            @php
                                $step = 45; 
                                $periods = [];
                                if ($jour['horaire']->heuredebutmatine && $jour['horaire']->heurefinmatine) {
                                    $periods['Matin'] = ['start' => $jour['horaire']->heuredebutmatine, 'end' => $jour['horaire']->heurefinmatine];
                                }
                                if ($jour['horaire']->heuredebutaprem && $jour['horaire']->heurefinaprem) {
                                    $periods['Après-midi'] = ['start' => $jour['horaire']->heuredebutaprem, 'end' => $jour['horaire']->heurefinaprem];
                                }
                                $occupiedUntil = null; 
                                $lastRdvStatus = null; 
                            @endphp

                            @foreach($periods as $label => $period)
                                @if($loop->index > 0)
                                    <div class="text-center my-2 text-muted small"><i class="fas fa-utensils"></i> Pause</div>
                                    @php $occupiedUntil = null; @endphp
                                @endif

                                @php
                                    $current = \Carbon\Carbon::parse($period['start']);
                                    $end     = \Carbon\Carbon::parse($period['end']);
                                @endphp

                                @while($current->lt($end))
                                    @php
                                        $currentTimeStr = $current->format('H:i');
                                        $nextStepTime   = $current->copy()->addMinutes($step);
                                    @endphp

                                    <div class="mb-2 w-100">
                                        
                                        @if($occupiedUntil && $current->lt($occupiedUntil))
                                            @php
                                                $statusColor = match($lastRdvStatus) {
                                                    1 => 'primary', 4 => 'success', 3 => 'danger', 6 => 'danger', default => 'warning'
                                                };
                                            @endphp
                                            <div class="card border-0 shadow-sm opacity-50" style="border-left: 4px solid var(--{{ $statusColor }}) !important; background-color: #f8f9fa;">
                                                <div class="card-body p-2 text-center text-muted small">
                                                    <i class="fas fa-arrow-down"></i>
                                                </div>
                                            </div>

                                        @else
                                            @php
                                                $rdvFound = $jour['rdvs']->first(function($rdv) use ($currentTimeStr, $nextStepTime) {
                                                    $hRdv = substr($rdv->heurerdv, 0, 5);
                                                    return ($hRdv >= $currentTimeStr && $hRdv < $nextStepTime->format('H:i')) && $rdv->idstatut != 3;
                                                });
                                            @endphp

                                            @if($rdvFound)
                                                @php
                                                    $dureeTotale = $rdvFound->prestations->sum('duree');
                                                    $dateHeureFin = \Carbon\Carbon::parse($rdvFound->daterdv . ' ' . $rdvFound->heurerdv)->addMinutes($dureeTotale);
                                                    $estPasse = $dateHeureFin->isPast();

                                                    // --- LOGIQUE DE PRIORITÉ D'AFFICHAGE ---
                                                    if ($rdvFound->idstatut == 5) { // Absent
                                                        $statusColor = 'warning';
                                                        $texteStatut = 'Absent';
                                                        $isFinal = true;
                                                    } 
                                                    elseif (in_array($rdvFound->idstatut, [3, 6])) { // Annulé
                                                        $statusColor = 'danger';
                                                        $texteStatut = 'Annulé';
                                                        $isFinal = true;
                                                    }
                                                    elseif ($rdvFound->idstatut == 4) { // Terminé (Base)
                                                        $statusColor = 'success';
                                                        $texteStatut = 'Terminé';
                                                        $isFinal = true;
                                                    }
                                                    elseif ($estPasse) { // Terminé (Temps)
                                                        $statusColor = 'success';
                                                        $texteStatut = 'Terminé';
                                                        $isFinal = true;
                                                    } 
                                                    else { // Normal
                                                        $statusColor = match($rdvFound->idstatut) {
                                                            1 => 'primary', 2 => 'info', default => 'secondary'
                                                        };
                                                        $texteStatut = $rdvFound->statut->libellestatut ?? 'A venir';
                                                        $isFinal = false;
                                                    }

                                                    $occupiedUntil = $dateHeureFin;
                                                    $lastRdvStatus = $rdvFound->idstatut;
                                                    $labelReel = $rdvFound->statut->libellestatut ?? 'Inconnu';
                                                @endphp
                                                
                                                <div class="card border-0 shadow-sm" style="border-left: 4px solid var(--{{ $statusColor }}) !important;">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <span class="badge badge-light border text-dark font-weight-bold">
                                                                {{ \Carbon\Carbon::parse($rdvFound->heurerdv)->format('H:i') }}
                                                            </span>
                                                            <a href="{{ route('rdv.client.show', $rdvFound->idrdv) }}" class="text-secondary small">
                                                                <i class="fas fa-info-circle"></i>
                                                            </a>
                                                        </div>

                                                        <div class="font-weight-bold text-dark text-truncate small">
                                                            {{ $rdvFound->animal->nom1animal }}
                                                        </div>

                                                        <div class="mt-1">
                                                            <span class="badge badge-{{ $statusColor }}">
                                                                {{ ucfirst($texteStatut) }}
                                                            </span>
                                                        </div>

                                                        <div class="small text-primary mt-1" style="font-size: 0.7rem;">
                                                            @foreach($rdvFound->prestations as $p)
                                                                <div>- {{ $p->nomprestation }} ({{ $p->duree }}m)</div>
                                                            @endforeach
                                                            <div class="border-top mt-1 pt-1 text-muted">Total: {{ $dureeTotale }} min</div>
                                                        </div>

                                                        <div class="mt-2 text-center w-100">
                                                            @if($isFinal)
                                                                @if($texteStatut === 'Absent')
                                                                    <div class="p-1 rounded text-white font-weight-bold shadow-sm" style="background-color: #ffc107; font-size: 0.85rem;">
                                                                        <i class="fas fa-user-slash"></i> ABSENT
                                                                    </div>
                                                                @elseif($texteStatut === 'Annulé')
                                                                    <div class="p-1 rounded text-white font-weight-bold shadow-sm" style="background-color: #dc3545; font-size: 0.85rem;">
                                                                        <i class="fas fa-ban"></i> ANNULÉ
                                                                    </div>
                                                                @elseif($texteStatut === 'Terminé')
                                                                    <div class="p-1 rounded text-white font-weight-bold shadow-sm" style="background-color: #28a745; font-size: 0.85rem;">
                                                                        <i class="fas fa-check"></i> TERMINÉ
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-dark btn-block py-0" 
                                                                        style="font-size: 0.75rem;"
                                                                        data-toggle="modal" data-target="#modalEditStatus"
                                                                        data-bs-toggle="modal" data-bs-target="#modalEditStatus"
                                                                        data-rdv-id="{{ $rdvFound->idrdv }}"
                                                                        data-current-statut="{{ $rdvFound->idstatut }}"
                                                                        data-statut-label="{{ ucfirst($labelReel) }}" 
                                                                        onclick="loadRdvData(this)">
                                                                    <i class="fas fa-edit"></i> Statut
                                                                </button>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                                
                                                @else
                                                @php
                                                    // 1. On force le fuseau horaire sur Paris pour "Maintenant"
                                                    $timezone = 'Europe/Paris';
                                                    $now = \Carbon\Carbon::now($timezone);
                                                    
                                                    // 2. On crée la date du créneau en forçant aussi le fuseau Paris
                                                    // (Cela évite que "11:00" soit interprété comme du UTC)
                                                    $slotDateTime = \Carbon\Carbon::createFromFormat(
                                                        'Y-m-d H:i', 
                                                        $jour['date'] . ' ' . $currentTimeStr, 
                                                        $timezone
                                                    );
                                                    
                                                    // 3. Comparaison : Est-ce que 11:00 est > 11:51 ? -> NON
                                                    $isFuture = $slotDateTime->gt($now);
                                                @endphp

                                                @if($isFuture)
                                                    <a href="{{ route('rdv.employe.create') }}?pre_date={{ $jour['date'] }}&pre_heure={{ $currentTimeStr }}&pre_praticien={{ $currentPracticien->idpracticien }}" 
                                                    class="btn btn-outline-light text-dark btn-block border-secondary text-left d-flex justify-content-between align-items-center py-2 shadow-sm"
                                                    style="border-style: dashed !important; background-color: #f8f9fa;">
                                                        <span class="font-weight-bold">{{ $currentTimeStr }}</span>
                                                        <small class="text-success font-weight-bold"><i class="fas fa-plus-circle"></i> Dispo</small>
                                                    </a>
                                                @else
                                                    <div class="text-center text-muted small py-2 bg-light border rounded" style="opacity: 0.6; background-color: #e9ecef;">
                                                        {{ $currentTimeStr }}
                                                    </div>
                                                @endif

                                                @php $occupiedUntil = null; @endphp
                                            @endif
                                        @endif
                                    </div>
                                    @php $current->addMinutes($step); @endphp
                                @endwhile
                            @endforeach
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted flex-column opacity-50">
                                <i class="fas fa-bed fa-2x mb-2"></i>
                                <span class="small font-weight-bold">Repos</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="modalEditStatus" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="z-index: 10000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <form id="formUpdateStatus" action="" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel"><i class="fas fa-edit mr-2"></i>Modifier le statut</h5>
                    <button type="button" class="close btn-close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Fermer" style="opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4">
                    
                    <div class="mb-4 text-center p-2 bg-light rounded border">
                        <span class="text-muted small text-uppercase font-weight-bold">Statut Actuel</span><br>
                        <span id="labelStatutActuel" class="badge badge-secondary mt-1" style="font-size: 1rem;">Chargement...</span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="selectStatut" class="font-weight-bold mb-2">Sélectionnez le nouveau statut :</label>
                        <select name="idstatut" id="selectStatut" class="form-control form-select form-control-lg" required>
                            @if(isset($statuts))
                                @foreach($statuts as $statut)
                                    @if(in_array($statut->idstatut, [5, 6])) 
                                        <option value="{{ $statut->idstatut }}">{{ ucfirst($statut->libellestatut) }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="alert alert-info d-flex align-items-center small" role="alert">
                        <i class="fas fa-info-circle mr-3 me-3"></i>
                        <div>
                            Le statut "Terminé" se mettra automatiquement à jour une fois l'heure du RDV passée.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fas fa-check mr-1"></i> Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function loadRdvData(button) {
        var rdvId = button.getAttribute('data-rdv-id');
        var currentStatut = button.getAttribute('data-current-statut');
        var statutLabel = button.getAttribute('data-statut-label'); 
        
        var form = document.getElementById('formUpdateStatus');
        if(form) {
            form.action = '/rdv/' + rdvId + '/update-status';
        }
        
        var select = document.getElementById('selectStatut');
        if(select) {
            select.value = currentStatut;
        }

        var labelSpan = document.getElementById('labelStatutActuel');
        if(labelSpan) {
            labelSpan.innerText = statutLabel;
            
            labelSpan.className = 'badge mt-1 badge-secondary'; 
            if(statutLabel === 'A venir') labelSpan.classList.add('badge-primary', 'bg-primary');
            else if(statutLabel === 'En cours') labelSpan.classList.add('badge-info', 'bg-info');
            else if(statutLabel.includes('Annul')) labelSpan.classList.add('badge-danger', 'bg-danger');
            else if(statutLabel === 'Absent') labelSpan.classList.add('badge-warning', 'bg-warning');
            else labelSpan.classList.add('badge-secondary', 'bg-secondary');
        }
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
</style>

@endsection