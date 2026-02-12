<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Prestations</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f9fafb; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 30px;
        }

        .table-container {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            overflow: hidden; 
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .custom-table thead th {
            background-color: #ffffff;
            color: #6b7280; 
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: none; 
            padding: 12px 16px;
            border-bottom: 2px solid #e5e7eb;
            text-align: left;
            cursor: pointer; 
        }

        .sort-icon {
            float: right;
            color: #d1d5db; 
            font-size: 0.8rem;
        }

        .custom-table tbody tr {
            background-color: white;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }

        .custom-table tbody tr:hover {
            background-color: #f9fafb; 
        }

        .custom-table tbody tr:last-child {
            border-bottom: none;
        }

        .custom-table tbody td {
            padding: 14px 16px;
            font-size: 0.9rem;
            color: #374151;
            vertical-align: middle;
        }

        .table-link {
            color: #2563eb; 
            text-decoration: underline;
            font-weight: 500;
        }
        .table-link:hover {
            color: #1d4ed8;
        }

        .text-category {
            color: #4b5563;
        }
        .sub-category {
            color: #6b7280;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-size: 1.5rem; color: #111827; font-weight: 600;">Catalogue</h2>
            <a href="{{ route('prestation.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nouveau
            </a>
        </div>

        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body p-2">
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">Nom</span>
                            <input type="text" class="form-control border-start-0 ps-0 filter-input" data-col="0" placeholder="Rechercher...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">Catégorie</span>
                            <input type="text" class="form-control border-start-0 ps-0 filter-input" data-col="1" placeholder="Rechercher...">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">Prix HT</span>
                            <input type="text" class="form-control border-start-0 ps-0 filter-input" data-col="2" placeholder="0.00">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">Prix TTC</span>
                            <input type="text" class="form-control border-start-0 ps-0 filter-input" data-col="3" placeholder="0.00">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">Durée</span>
                            <input type="text" class="form-control border-start-0 ps-0 filter-input" data-col="4" placeholder="Min">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nom <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Catégorie <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Prix de vente HT <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Prix de vente TTC <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Durée <i class="fa-solid fa-sort sort-icon"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestations as $prestation)
                        <tr>
                            <td>
                                <a href="{{ route('prestation.edit', $prestation->idprestation) }}" class="table-link">
                                    {{ $prestation->nomprestation }}
                                </a>
                            </td>

                            <td>
                                <span class="text-category">{{ $prestation->libellecategorie }}</span>
                                @if($prestation->categorie && $prestation->categorie->cat_libellecategorie)
                                    <span class="sub-category">({{ $prestation->categorie->cat_libellecategorie }})</span>
                                @endif
                            </td>

                            <td>
                                {{ number_format($prestation->tarifht, 2, ',', ' ') }}€
                            </td>

                            <td>
                                @php
                                    $ttc = $prestation->tarifht * 1.20; 
                                @endphp
                                {{ number_format($ttc, 2, ',', ' ') }}€
                            </td>

                            <td>
                                {{ $prestation->duree }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Aucune donnée disponible.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.filter-input').on('keyup', function() {
                
                $('table tbody tr').each(function() {
                    var showRow = true; 

                    $('.filter-input').each(function() {
                        var inputVal = $(this).val().toLowerCase(); 
                        var colIndex = $(this).data('col'); 
                        
                        if (inputVal) {
                            var cellText = $(this).closest('.container-fluid')
                                                .find('table tbody tr').eq($(this).parent().parent().parent().parent().index()) // Petite astuce pour cibler la ligne courante dans la boucle each principale
                            
                            var cellText = $(this).parent().parent().parent().parent().parent().parent().parent().find('table tbody tr').eq(0).find('td').eq(colIndex).text(); 
                        }
                    });
                    
                    
                    var $row = $(this); 
                    
                    $('.filter-input').each(function() {
                        var $input = $(this);
                        var searchText = $input.val().toLowerCase();
                        var colIndex = $input.data('col'); 

                        if (searchText !== '') {
                            var cellText = $row.find('td').eq(colIndex).text().toLowerCase();
                            
                            if (cellText.indexOf(searchText) === -1) {
                                showRow = false; 
                            }
                        }
                    });

                    if (showRow) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });
            });
        });
    </script>
</body>
</html>