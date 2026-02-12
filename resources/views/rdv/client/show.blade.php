@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">
            <i class="fas fa-info-circle text-primary mr-2"></i>Détails du Rendez-vous 
        </h2>
        
        <div>
            @if($rdv->idstatut == 4)
                <a href="{{ route('rdv.facture.create', $rdv->idrdv) }}" class="btn btn-success rounded-pill px-4 mr-2 shadow-sm">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Générer la facture
                </a>
            @endif

            <a href="{{ route('rdv.client.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left mr-2"></i>Retour au planning
            </a>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Informations Générales</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 border-right">
                            <label class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date & Heure</label>
                            <div class="d-flex align-items-center mt-2">
                                <div class="icon icon-shape bg-light text-center rounded-circle mr-3 p-3">
                                    <i class="far fa-calendar-alt text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-dark font-weight-bold">{{ \Carbon\Carbon::parse($rdv->daterdv)->format('d/m/Y') }}</h5>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($rdv->heurerdv)->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-4">
                            <label class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Statut Actuel</label>
                            <div class="mt-2">
                                @php
                                    $statusConfig = match($rdv->idstatut) {
                                        1 => ['class' => 'badge-info', 'icon' => 'fa-hourglass-start'],
                                        2 => ['class' => 'badge-warning', 'icon' => 'fa-spinner fa-spin'],
                                        4 => ['class' => 'badge-success', 'icon' => 'fa-check-circle'],
                                        3, 6 => ['class' => 'badge-danger', 'icon' => 'fa-times-circle'],
                                        5 => ['class' => 'badge-secondary', 'icon' => 'fa-user-slash'],
                                        default => ['class' => 'badge-light border', 'icon' => 'fa-question']
                                    };
                                @endphp
                                <span class="badge {{ $statusConfig['class'] }} text-dark px-3 py-2" style="font-size: 1rem;">
                                    <i class="fas {{ $statusConfig['icon'] }} mr-2"></i>
                                    {{ $rdv->statut->libellestatut ?? 'Inconnu' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-dark font-weight-bold mb-3"><i class="fas fa-paw text-info mr-2"></i>L'Animal</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Nom :</strong> {{ $rdv->animal->nom1animal }}</li>
                                <li class="mb-2"><strong>Tatouage :</strong> {{ $rdv->numtatouage }}</li>
                                <li class="mb-2"><strong>Date de naissance :</strong> {{ \Carbon\Carbon::parse($rdv->animal->datenaissance)->format('d/m/Y') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-dark font-weight-bold mb-3"><i class="fas fa-user text-success mr-2"></i>Le Client</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Nom :</strong> {{ strtoupper($rdv->personne->nom) }} {{ $rdv->personne->prenom }}</li>
                                <li class="mb-2"><strong>Téléphone :</strong> {{ $rdv->personne->tel }}</li>
                                <li class="mb-2"><strong>Email :</strong> <a href="mailto:{{ $rdv->personne->mail }}">{{ $rdv->personne->mail }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-dark">Prestations demandées</h6>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($rdv->prestations as $presta)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block font-weight-bold">{{ $presta->nomprestation }}</span>
                                <small class="text-muted"><i class="far fa-clock mr-1"></i>{{ $presta->duree }} min</small>
                            </div>
                            <span class="badge badge-light border text-dark">{{ number_format($presta->tarifht, 2) }} €</span>
                        </li>
                    @endforeach
                    <li class="list-group-item bg-light text-right">
                        <strong>Total HT : </strong> 
                        {{ number_format($rdv->prestations->sum('tarifht'), 2) }} €
                    </li>
                </ul>
            </div>

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-dark">Praticiens assignés</h6>
                </div>
                <div class="card-body">
                    @foreach($rdv->practiciens as $pro)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark">{{ $pro->libellepracticien }}</div>
                                <div class="small text-muted">Employé ID: {{ $pro->idemploye }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection