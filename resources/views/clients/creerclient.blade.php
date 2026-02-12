<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un nouveau client - Hunimalis</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; padding-top: 2rem; }
        .suggestions-list {
            list-style: none; padding: 0; margin: 0;
            border: 1px solid #dee2e6;
            max-height: 200px; overflow-y: auto;
            background-color: white; position: absolute; z-index: 1050;
            width: 100%; border-radius: 0 0 0.375rem 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .suggestions-list li {
            padding: 0.5rem 1rem; cursor: pointer; color: #495057;
        }
        .suggestions-list li:hover { background-color: #e9ecef; color: #0d6efd; }
    </style>
</head>
<body>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="text-center mb-4">
                    <img src="{{ asset('img/logo.webp') }}" alt="Hunimalis Logo" style="height: 60px;">
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 text-center">Créer un nouveau client</h4>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('client.store') }}" autocomplete="off">
                            @csrf

                            <h5 class="text-primary mt-2 mb-3">Identité</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input id="nom" type="text" class="form-control" name="nom" value="{{ old('nom') }}" required autofocus>
                                </div>
                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input id="prenom" type="text" class="form-control" name="prenom" value="{{ old('prenom') }}" required>
                                </div>
                            </div>

                            <h5 class="text-primary mt-4 mb-3">Adresse</h5>
                            <div class="row mb-3">
                                <div class="col-md-8 position-relative">
                                    <label for="ville" class="form-label">Ville <span class="text-danger">*</span></label>
                                    <input type="text" id="ville" name="ville" class="form-control" value="{{ old('ville') }}" placeholder="Rechercher une commune..." required>
                                    <ul id="ville-suggestions" class="suggestions-list" style="display: none;"></ul>
                                </div>

                                <div class="col-md-4">
                                    <label for="cp" class="form-label">Code Postal <span class="text-danger">*</span></label>
                                    <input type="text" id="cp" name="cp" class="form-control bg-light" value="{{ old('cp') }}" readonly required>
                                </div>
                            </div>

                            <h5 class="text-primary mt-4 mb-3">Contact</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tel" class="form-label">Téléphone</label>
                                    <input id="tel" type="text" class="form-control" name="tel" value="{{ old('tel') }}" maxlength="10" placeholder="0612345678">
                                </div>
                                <div class="col-md-6">
                                    <label for="mail" class="form-label">Email</label>
                                    <input id="mail" type="email" class="form-control" name="mail" value="{{ old('mail') }}" placeholder="client@exemple.com">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Enregistrer le client
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-link text-decoration-none">Retour au tableau de bord</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputVille = document.getElementById('ville');
            const inputCP = document.getElementById('cp');
            const suggestionsList = document.getElementById('ville-suggestions');
            let debounceTimeout;

            function fetchSuggestions(query) {
                const apiUrl = `https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&type=municipality&limit=5`;
                
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => displaySuggestions(data.features))
                    .catch(error => console.error("Erreur API:", error));
            }

            function displaySuggestions(features) {
                suggestionsList.innerHTML = '';
                if (!features || features.length === 0) {
                    suggestionsList.style.display = 'none';
                    return;
                }
                
                features.forEach(feature => {
                    const postalCode = feature.properties.postcode;
                    const city = feature.properties.city;
                    
                    const li = document.createElement('li');
                    li.textContent = `${city} (${postalCode})`;
                    
                    li.addEventListener('click', function() {
                        inputVille.value = city;
                        inputCP.value = postalCode;
                        suggestionsList.style.display = 'none';
                    });
                    
                    suggestionsList.appendChild(li);
                });
                suggestionsList.style.display = 'block';
            }

            inputVille.addEventListener('input', function() {
                const query = this.value.trim();
                if (query.length < 3) {
                    suggestionsList.style.display = 'none';
                    return;
                }
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => { fetchSuggestions(query); }, 300);
            });

            document.addEventListener('click', function(e) {
                if (e.target !== inputVille) {
                    suggestionsList.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>