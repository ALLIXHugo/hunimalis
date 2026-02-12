<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des Animaux</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-dark: #39324b;  
            --primary-green: #6bcba5; 
            --bg-color: #f4f6f8;      
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            margin: 0;
            padding: 40px;
            -webkit-font-smoothing: antialiased;
        }

        .logo{
            width: 30px;
            height: 30px;
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-create {
            background-color: var(--primary-green);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(107, 203, 165, 0.4);
        }
        .btn-create:hover {
            background-color: #5bb592;
            transform: translateY(-2px);
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .card-body { padding: 25px; }

        .filter-form {
            display: flex;
            gap: 20px;
            align-items: flex-end; 
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            height: 42px;
            background-color: white;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(107, 203, 165, 0.2);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            padding-bottom: 2px; 
        }

        .btn-filter {
            background-color: var(--primary-dark);
            color: white;
            border: none;
            height: 42px;
            padding: 0 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-filter:hover { background-color: #2d263b; }

        .btn-reset {
            background-color: white;
            color: var(--text-light);
            border: 1px solid #d1d5db;
            height: 42px;
            padding: 0 15px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-reset:hover { background-color: #f9fafb; color: var(--text-dark); border-color: #9ca3af; }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead {
            background-color: #f9fafb;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            padding: 15px 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-light);
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 14px;
            color: var(--text-dark);
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .animal-name {
            font-weight: 700;
            color: var(--primary-dark);
            text-decoration: none;
            font-size: 15px;
        }
        .animal-name:hover { color: var(--primary-green); }

        .badge-sexe {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .male { background-color: #e0f2fe; color: #0369a1; }
        .female { background-color: #fce7f3; color: #be185d; }

        .sub-text {
            display: block;
            font-size: 12px;
            color: var(--text-light);
            margin-top: 2px;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
        }
    </style>
</head>
<body>

    <div class="page-container">
        
        <div class="page-header">
            <h1 class="page-title">
                <img src="{{ asset('img/favicon.webp') }}" class="logo" />
                &nbsp;Dossiers Animaux
            </h1>
            <a href="{{ route('animal.create') }}" class="btn-create">
                <i class="fa-solid fa-plus"></i> Nouvel Animal
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="filter-form">
                    
                    <div class="form-group">
                        <label><i class="fa-solid fa-magnifying-glass"></i> Rechercher par nom</label>
                        <input type="text" id="filter-nom" class="form-control live-search" placeholder="Ex: Rex...">
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-filter"></i> Filtrer par espèce</label>
                        <select id="filter-espece" class="form-control live-search">
                            <option value="">-- Toutes les espèces --</option>
                            @foreach($especes as $espece)
                                <option value="{{ strtolower($espece->libelleespece) }}">
                                    {{ $espece->libelleespece }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-user"></i> Propriétaire</label>
                        <input type="text" id="filter-proprio" class="form-control live-search" placeholder="Nom du client...">
                    </div>

                    <div class="filter-actions">
                        <button type="button" id="btn-reset" class="btn-reset" title="Réinitialiser" style="width: 100%;">
                            <i class="fa-solid fa-rotate-right"></i> Effacer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID (Puce)</th>
                            <th>Animal</th>
                            <th>Espèce / Race</th>
                            <th>Sexe</th>
                            <th>Propriétaire</th>
                            <th>Naissance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($animaux as $animal)
                        <tr>
                            <td style="font-family: monospace; color: var(--text-light);">
                                #{{ $animal->numtatouage }}
                            </td>

                            <td>
                                <a href="{{ route('animal.show', $animal->numtatouage) }}" class="animal-name">
                                    {{ $animal->nom1animal }}
                                </a>
                            </td>

                            <td>
                                <div>
                                    <span style="font-weight: 600;">{{ $animal->espece ? $animal->espece->libelleespece : '?' }}</span>
                                    <span class="sub-text">{{ $animal->race ? $animal->race->libellerace : 'Non racé' }}</span>
                                </div>
                            </td>
                            
                            <td>
                                @if($animal->sexe == 1) 
                                    <span class="badge-sexe male"><i class="fa-solid fa-mars"></i> Mâle</span>
                                @else 
                                    <span class="badge-sexe female"><i class="fa-solid fa-venus"></i> Femelle</span>
                                @endif
                            </td>
                            
                            <td>
                                @if($animal->personne)
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <div style="background:#eef2ff; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#4f46e5; font-size:12px;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div>
                                            <span style="font-weight:600; font-size:13px;">{{ $animal->personne->nom }} {{ $animal->personne->prenom }}</span>
                                            <span class="sub-text">{{ $animal->personne->tel }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #9ca3af; font-style: italic;">Non lié</span>
                                @endif
                            </td>
                            
                            <td>
                                {{ \Carbon\Carbon::parse($animal->datenaissance)->format('d/m/Y') }}
                                <span class="sub-text">
                                    {{ \Carbon\Carbon::parse($animal->datenaissance)->age }} ans
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-paw" style="font-size: 48px; color: #e5e7eb; margin-bottom: 15px;"></i>
                                <p>Aucun animal ne correspond à votre recherche.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        
        function filterTable() {
            var nomSearch = $('#filter-nom').val().toLowerCase();
            var especeSearch = $('#filter-espece').val().toLowerCase();
            var proprioSearch = $('#filter-proprio').val().toLowerCase();

            $('table tbody tr').each(function() {
                var row = $(this);
                var nomText = row.find('td').eq(1).text().toLowerCase();
                var especeText = row.find('td').eq(2).text().toLowerCase();
                var proprioText = row.find('td').eq(4).text().toLowerCase();

                var matchNom = nomText.indexOf(nomSearch) > -1;
                var matchEspece = (especeSearch === "") || (especeText.indexOf(especeSearch) > -1);
                var matchProprio = proprioText.indexOf(proprioSearch) > -1;

                if (matchNom && matchEspece && matchProprio) {
                    row.show();
                } else {
                    row.hide();
                }
            });

            var visibleRows = $('table tbody tr:visible').length;
            if (visibleRows === 0) {
            }
        }

        $('.live-search').on('keyup change', function() {
            filterTable();
        });

        $('#btn-reset').on('click', function() {
            $('.live-search').val(''); 
            filterTable(); 
        });
    });
</script>
</body>
</html>