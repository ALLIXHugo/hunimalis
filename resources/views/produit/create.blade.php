@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer un nouveau produit</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('produit.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Photo du produit</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="nomproduit" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input id="nomproduit" type="text" class="form-control" name="nomproduit" value="{{ old('nomproduit') }}" required autofocus>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                                <select name="libellecategoriepro" class="form-select" required>
                                    <option value="">-- Choisir une catégorie --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->libellecategoriepro }}" {{ old('libellecategoriepro') == $cat->libellecategoriepro ? 'selected' : '' }}>
                                            {{ $cat->libellecategoriepro }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="numserie" class="form-label">N° de série </label>
                                <input id="numserie" type="text" class="form-control" name="numserie" value="{{ old('numserie') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numlot" class="form-label">N° de lot <span class="text-danger">*</span></label>
                                <input id="numlot" type="text" class="form-control" name="numlot" value="{{ old('numlot') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stocks" class="form-label">Stock Initial <span class="text-danger">*</span></label>
                                <input id="stocks" type="number" class="form-control" name="stocks" value="{{ old('stocks') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="seuilalerte" class="form-label">Seuil d'alerte <span class="text-danger">*</span></label>
                                <input id="seuilalerte" type="number" class="form-control" name="seuilalerte" value="{{ old('seuilalerte') }}" required>
                            </div>

                            <hr>

                            <h6 class="text-muted border-bottom pb-2 mb-3">Tarification</h6>
                        
                            <input type="hidden" id="taux_tva_pro" value="{{ $tauxTva }}">

                            <div class="row mb-4 bg-light p-3 rounded">
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Prix Achat HT (€) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="prixachat" id="prixachat" value="{{ old('prixachat') }}" required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Prix Vente HT (€) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="prixvente" id="prixvente" value="{{ old('prixvente') }}" required>
                                </div>

                                <div class="col-md-4">
                                    @if($tauxTva > 0)
                                        <label class="form-label text-muted">Prix TTC (TVA {{ $tauxTva * 100 }}%)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="prixttc" disabled placeholder="Calculé auto...">
                                        </div>
                                    @else
                                        <label class="form-label text-muted">Prix TTC</label>
                                        <input type="text" class="form-control" value="Non applicable (TVA 0%)" disabled style="background-color: #e9ecef; font-style: italic;">
                                    @endif
                                </div>
                            </div>

                            <script>
                                document.getElementById('prixvente').addEventListener('input', function() {
                                    
                                    let ht = parseFloat(this.value);
                                    
                                    let taux = parseFloat(document.getElementById('taux_tva_pro').value);

                                    let inputTTC = document.getElementById('prixttc');

                                    if(inputTTC && !isNaN(ht)) {
                                        let ttc = ht * (1 + taux); 
                                        inputTTC.value = ttc.toFixed(2) + ' €';
                                    } 
                                    else if (inputTTC) {
                                        inputTTC.value = '';
                                    }
                                });
                            </script>

                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer le produit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>