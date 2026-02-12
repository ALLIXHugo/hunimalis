@extends('layouts.app')

@section('content')

<style>
    .custom-container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .custom-h2 { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; color: #007bff; font-size: 24px; }
    
    .custom-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .custom-table th, .custom-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
    .custom-table th { background-color: #f8f9fa; color: #333; font-weight: bold; }
    .custom-table tr:hover { background-color: #f1f1f1; }

    .badge-date { background-color: #e9ecef; padding: 4px 8px; border-radius: 4px; font-weight: bold; color: #495057; font-size: 0.9em; }
    
    .btn-action { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; color: white; transition: 0.3s; margin-right: 5px; }
    .btn-accept { background-color: #28a745; }
    .btn-accept:hover { background-color: #1e7e34; }
    .btn-refuse { background-color: #dc3545; }
    .btn-refuse:hover { background-color: #c82333; }

    .custom-alert-success { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px; }
    .empty-message { text-align: center; color: #6c757d; font-style: italic; padding: 20px; }
</style>

<div class="custom-container">
    <h2 class="custom-h2">Gestion des demandes de validation</h2>

    @if(session('success'))
        <div class="custom-alert-success">
            {!! session('success') !!}
        </div>
    @endif

    @if($requests->isEmpty())
        <div class="empty-message">
            Aucune demande de validation en attente pour le moment.
        </div>
    @else
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Professionnel</th>
                    <th>Date & Heure</th>
                    <th>Message</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td>
                        <strong>{{ $req->professionnel->nompro ?? 'Pro Inconnu' }} {{ $req->professionnel->prenompro ?? '' }}</strong><br>
                        <small style="color: #666;">{{ $req->professionnel->libelleetablissement ?? '' }}</small>
                    </td>
                    <td>
                        <span class="badge-date">
                            {{ \Carbon\Carbon::parse($req->date_proposee)->format('d/m/Y') }}
                        </span>
                        à {{ \Carbon\Carbon::parse($req->heure_proposee)->format('H:i') }}
                    </td>
                    <td style="color: #555; font-style: italic;">
                        {{ $req->message_pro ?? '-' }}
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 5px;">
                            <form action="{{ route('rdv.validation.accept', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-accept" onclick="return confirm('Confirmer ce rendez-vous ?')">
                                    Valider
                                </button>
                            </form>

                            <form action="{{ route('rdv.validation.refuse', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-refuse" onclick="return confirm('Refuser ce rendez-vous ?')">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection