@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #3b3655;">
                    <h5 class="mb-0">Modifier : {{ $produit->nomproduit }}</h5>
                    <a href="{{ route('produit.show', $produit->idproduit) }}" class="text-white text-decoration-none">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('produit.update', $produit->idproduit) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4 align-items-center">
                            <div class="col-md-4 text-center">
                                @if($produit->photo)
                                    <img src="{{ asset('storage/' . $produit->photo) }}" alt="Actuelle" class="img-thumbnail mb-2" style="max-height: 150px;">
                                    <p class="text-muted small">Image actuelle</p>
                                @else
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <span class="text-muted">Pas d'image</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-8">
                                <label for="image" class="form-label fw-bold">Changer la photo</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                <small class="text-muted">Laissez vide pour conserver l'image actuelle.</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nomproduit" value="{{ old('nomproduit', $produit->nomproduit) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                                <select name="libellecategoriepro" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->libellecategoriepro }}" 
                                            {{ (old('libellecategoriepro', $produit->libellecategoriepro) == $cat->libellecategoriepro) ? 'selected' : '' }}>
                                            {{ $cat->libellecategoriepro }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° de série</label>
                                <input type="text" class="form-control" name="numserie" value="{{ old('numserie', $produit->numserie) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° de lot</label>
                                <input type="text" class="form-control" name="numlot" value="{{ old('numlot', $produit->numlot) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Initial <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stocks" value="{{ old('stocks', $produit->stocks) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Seuil d'alerte <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="seuilalerte" value="{{ old('seuilalerte', $produit->seuilalerte) }}" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3">Tarification</h6>
                        
                        <input type="hidden" id="taux_tva_pro" value="{{ $tauxTva }}">

                        <div class="row mb-4 bg-light p-3 rounded">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prix Achat HT</label>
                                <input type="number" step="0.01" class="form-control" name="prixachat" value="{{ old('prixachat', $produit->prixachat) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prix Vente HT</label>
                                <input type="number" step="0.01" class="form-control" name="prixvente" id="prixvente" value="{{ old('prixvente', $produit->prixvente) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Prix TTC (Calculé)</label>
                                <input type="text" class="form-control" id="prixttc" disabled 
                                       value="{{ $tauxTva > 0 ? number_format($produit->prixvente * (1 + $tauxTva), 2) . ' €' : 'Non applicable' }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('produit.show', $produit->idproduit) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('prixvente').addEventListener('input', function() {
        let ht = parseFloat(this.value);
        let taux = parseFloat(document.getElementById('taux_tva_pro').value);
        let inputTTC = document.getElementById('prixttc');

        if(inputTTC && !isNaN(ht) && taux > 0) {
            let ttc = ht * (1 + taux); 
            inputTTC.value = ttc.toFixed(2) + ' €';
        } else if (inputTTC) {
            inputTTC.value = taux > 0 ? '' : 'Non applicable';
        }
    });
</script>
@endsection