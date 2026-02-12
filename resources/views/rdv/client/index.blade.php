@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">
            <i class="fas fa-calendar-alt text-primary mr-2"></i>Liste des rendez-vous
        </h2>
        <a href="{{ route('rdv.employe.planning') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Calendrier de disponibilité
        </a>
        <a href="{{ route('rdv.client.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Nouveau RDV
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow border-0 rounded-lg">
        <div class="card-header bg-white py-3 border-0">
            <small class="text-muted">Cliquez sur une ligne pour voir le détail.</small>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 pl-4 border-0 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date</th>
                            <th class="py-3 border-0 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Animal</th>
                            <th class="py-3 border-0 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Client</th>
                            <th class="py-3 border-0 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Détails</th>
                            <th class="py-3 border-0 text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rdvs as $rdv)
                            <tr class="border-bottom" 
                                style="cursor: pointer; transform: scale(1); transition: background-color 0.2s;" 
                                onclick="window.location='{{ route('rdv.client.show', $rdv->idrdv) }}'">
                                
                                <td class="pl-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-dark font-weight-bold">
                                            {{ \Carbon\Carbon::parse($rdv->daterdv)->format('d/m/Y') }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="far fa-clock mr-1 text-primary"></i>
                                            {{ \Carbon\Carbon::parse($rdv->heurerdv)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 mr-2 d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px;">
                                            <i class="fas fa-paw text-dark"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark font-weight-bold text-capitalize">
                                                {{ $rdv->animal->nom1animal ?? 'Inconnu' }}
                                            </span>
                                            <span class="text-muted small" style="font-size: 0.8em;">
                                                Tatouage : {{ $rdv->numtatouage }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark font-weight-bold text-capitalize">
                                            {{ $rdv->personne->nom ?? '' }} {{ $rdv->personne->prenom ?? '' }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="fas fa-phone-alt fa-xs mr-1 text-success"></i>
                                            {{ $rdv->personne->tel ?? '--' }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="mb-1">
                                        @foreach($rdv->prestations as $presta)
                                            <span class="badge bg-light text-dark border font-weight-normal">
                                                {{ $presta->nomprestation }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="small text-muted">
                                        @foreach($rdv->practiciens as $practicien)
                                            <i class="fas fa-user-md mr-1 text-info"></i>{{ $practicien->libellepracticien }}
                                        @endforeach
                                    </div>
                                </td>

                                <td>
                                    @php
                                        $statusStyles = match($rdv->idstatut) {
                                            1 => ['class' => 'badge-info', 'icon' => 'fa-hourglass-start'],
                                            2 => ['class' => 'badge-warning', 'icon' => 'fa-spinner fa-spin'],
                                            4 => ['class' => 'badge-success', 'icon' => 'fa-check-circle'],
                                            3, 6 => ['class' => 'badge-danger', 'icon' => 'fa-times-circle'],
                                            5 => ['class' => 'badge-secondary', 'icon' => 'fa-user-slash'],
                                            default => ['class' => 'badge-light border', 'icon' => 'fa-question']
                                        };
                                    @endphp
                                    
                                    <span class="badge badge-pill {{ $statusStyles['class'] }} text-dark px-3 py-2 shadow-sm font-weight-bold">
                                        <i class="fas {{ $statusStyles['icon'] }} mr-1 text-dark"></i>
                                        {{ $rdv->statut->libellestatut ?? 'Inconnu' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted opacity-50">
                                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                        <p>Aucun rendez-vous prévu pour le moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rdvs->hasPages())
                <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                    {{ $rdvs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection