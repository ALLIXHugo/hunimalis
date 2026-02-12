<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Prestation</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }

        .modal-custom {
            background: white;
            border-radius: 4px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 40px auto;
            overflow: visible;
        }

        .modal-header-custom {
            background-color: #3b3655;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px 4px 0 0;
        }
        .modal-header-custom h2 {
            margin: 0; font-size: 1.1rem; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
        }

        .modal-body-custom { padding: 25px; }

        label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #212529;
            margin-bottom: 5px;
            display: block;
        }

        .input-group-text {
            background-color: #3b3655;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .btn-submit-container { margin-top: 25px; text-align: right; }

        .select2-container--bootstrap-5 .select2-selection {
            border-color: #ced4da;
        }

        .required-star {
            color: red; 
            margin-left: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="modal-custom">
        <div class="modal-header-custom">
            <h2><i class="fa-solid fa-pen-to-square"></i> Modifier : {{ $prestation->nomprestation }}</h2>
            <a href="{{ route('prestation.index') }}" style="color: white; text-decoration: none;">
                <i class="fa-solid fa-xmark" style="cursor:pointer; font-size: 1.2rem;"></i>
            </a>
        </div>

        <div class="modal-body-custom">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('prestation.update', $prestation->idprestation) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nom de la prestation <span class="required-star">*</span></label>
                        <input type="text" name="nomprestation" class="form-control" 
                               value="{{ old('nomprestation', $prestation->nomprestation) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Sélectionner une catégorie <span class="required-star">*</span></label>
                        <div class="input-group">
                            <select name="libellecategorie" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->libellecategorie }}"
                                        {{ $prestation->libellecategorie == $cat->libellecategorie ? 'selected' : '' }}>
                                        {{ $cat->libellecategorie }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-text"><i class="fa-solid fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Durée (min) <span class="required-star">*</span></label>
                        <input type="number" step="1" name="duree" class="form-control" 
                               value="{{ old('duree', $prestation->duree) }}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label>Prix HT (€) <span class="required-star">*</span></label>
                        <input type="number" step="0.01" name="tarifht" class="form-control" 
                               value="{{ old('tarifht', $prestation->tarifht) }}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label>Prix TTC (€)</label>
                        <input type="number" class="form-control" 
                               value="{{ number_format($prestation->tarifht * 1.20, 2, '.', '') }}" disabled>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <label>Praticiens qualifiés</label>
                    <select name="praticiens[]" class="form-select select2-enable" multiple="multiple">
                        @foreach($praticiens as $prac)
                            <option value="{{ $prac->idpracticien }}">{{ $prac->libellepracticien }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Corpulence / Taille Animal</label>
                        <select name="corpulences[]" class="form-select select2-enable" multiple="multiple">
                            @foreach($corpulences as $corp)
                                <option value="{{ $corp->idcorpulence }}">{{ $corp->libellecorpulence }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Type de poils</label>
                        <select name="tailles_pelage[]" class="form-select select2-enable" multiple="multiple">
                            @foreach($taillesPelage as $taille)
                                <option value="{{ $taille->idtaillepelage }}">{{ $taille->libelletaillepelage }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Espèces concernées <span class="required-star">*</span></label>
                    <select name="especes[]" class="form-select select2-enable" multiple="multiple" required>
                        @foreach($especes as $espece)
                            <option value="{{ $espece->idespece }}"
                                {{ $prestation->idespece == $espece->idespece ? 'selected' : '' }}>
                                {{ ucfirst($espece->libelleespece) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" class="btn btn-primary">Mettre à jour la prestation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2-enable').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    });
</script>

</body>
</html>