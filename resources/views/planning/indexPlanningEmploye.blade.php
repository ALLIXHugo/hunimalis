<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Plannings Employés</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f8f9fa; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; color: #007bff; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px; }
        .add-button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 10px; text-align: left; }
        th { background-color: #e9ecef; }
        .empty-message { text-align: center; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
    <h2>Plannings Employés Enregistrés</h2>

        @if(session('success'))
            <div class="alert-success">
                {!! session('success') !!}
            </div>
        @endif
        
        <a href="{{ route('planning.create') }}" class="add-button">Ajouter un Planning</a>

        @if(empty($planningDetails) || $planningDetails->isEmpty())
            <p class="empty-message">Aucun planning enregistré pour l'instant.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Jour</th>
                        <th>Détail Horaire</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planningDetails as $detail)
                        <tr>
                            <td>
                                @if($detail->personne)
                                    <strong>{{ $detail->personne->nom }} {{ $detail->personne->prenom }}</strong>
                                @else
                                    (Employé Inconnu)
                                @endif
                            </td>
                            <td>
                                {{ $detail->jour->idjour ?? '(Jour Inconnu)' }}
                            </td>
                            <td>
                                @if($detail->horaire)
                                    @php
                                        $h = $detail->horaire;
                                        $details = [];
                                        if ($h->heuredebutmatine && $h->heurefinmatine) {
                                            $details[] = 'Matin: ' . substr($h->heuredebutmatine, 0, 5) . ' - ' . substr($h->heurefinmatine, 0, 5);
                                        }
                                        if ($h->heuredebutaprem && $h->heurefinaprem) {
                                            $details[] = 'Après-midi: ' . substr($h->heuredebutaprem, 0, 5) . ' - ' . substr($h->heurefinaprem, 0, 5);
                                        }
                                    @endphp
                                    {{ implode(' | ', $details) }}
                                    @if(empty($details))
                                        (Horaires non spécifiés pour cet ID)
                                    @endif
                                @else
                                    (Détails non disponibles)
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>