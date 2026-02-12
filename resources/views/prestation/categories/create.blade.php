<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --border-color: #cbd5e1;
        --bg-page: #f8fafc;
        --focus-ring: 0 0 0 3px rgba(79, 70, 229, 0.15); 
    }

    body {
        background-color: var(--bg-page);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-dark);
        line-height: 1.6;
    }

    .container-form {
        max-width: 600px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .form-card {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .form-header {
        margin-bottom: 30px;
        text-align: center;
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text-dark);
    }

    .form-subtitle {
        color: var(--text-gray);
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        font-size: 0.95rem;
        border: 1px solid var(--border-color); 
        border-radius: 8px;
        color: var(--text-dark);
        background-color: #fff;
        transition: all 0.2s ease-in-out;
        box-sizing: border-box; 
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: var(--focus-ring);
        outline: none;
    }

    select.form-control {
        appearance: none; 
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .form-text {
        color: var(--text-gray);
        font-size: 0.85em;
        margin-top: 8px;
        line-height: 1.4;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        align-items: center;
        flex-direction: row-reverse; 
        justify-content: flex-start;
    }

    .btn-submit {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 8px; 
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }

    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }
    
    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-cancel {
        color: var(--text-gray);
        text-decoration: none;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background-color: #f1f5f9;
        color: var(--text-dark);
    }
</style>
<div class="container container-form">

    <form action='/categories' method="POST" class="form-card">
        @csrf

        <div class="form-header">
            <h2 class="form-title">Nouvelle Catégorie</h2>
            <p class="form-subtitle">Ajoutez une catégorie pour organiser vos prestations.</p>
        </div>

        <div class="form-group">
            <label for="libelle" class="form-label">Nom de la catégorie <span style="color:red">*</span></label>
            <input type="text" 
                   name="libelle" 
                   id="libelle" 
                   class="form-control" 
                   placeholder="Ex: Soins, Tonte, Alimentation..." 
                   required>
        </div>

        <div class="form-group">
            <label for="parent_id" class="form-label">Catégorie Parente</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">-- C'est une catégorie principale --</option>
                
                @foreach($categoriesPrincipales as $cat)
                    <option value="{{ $cat->id }}"> 
                        {{ $cat->libellecategorie }}
                    </option>
                @endforeach
            </select>
            
            <small class="form-text">
                <i class="fas fa-info-circle"></i> 
                Sélectionnez une catégorie existante pour créer une sous-catégorie, ou laissez vide pour créer une nouvelle famille principale.
            </small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Créer la catégorie</button>
            <a href="/categories" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>