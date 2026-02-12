<style>
    :root {
        --primary-color: #4f46e5; 
        --primary-light: #eef2ff;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --bg-page: #f8fafc;
        --card-bg: #ffffff;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-hover: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: var(--bg-page);
        color: var(--text-dark);
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; 
        line-height: 1.6;
    }

    .container-categories {
        max-width: 850px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .header-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-title {
        font-size: 1.8rem;
        color: var(--text-dark);
        font-weight: 700;
        margin: 0;
        position: relative;
    }
    
    .page-title::after {
        content: '';
        display: block;
        width: 400px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
        margin-top: 8px;
        border-radius: 2px;
    }

    .btn-add-category {
        background-color: var(--primary-color);
        color: white;
        padding: 10px 24px;
        border-radius: 8px; 
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-category:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 6px 10px -1px rgba(79, 70, 229, 0.3);
        color: white;
        text-decoration: none;
    }

    .category-list {
        padding: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .category-card {
        background: var(--card-bg);
        border: 1px solid rgba(226, 232, 240, 0.8); 
        border-radius: 16px;
        padding: 0; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .category-card:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .category-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        background: white;
    }

    .category-title {
        font-size: 1.15rem;
        color: var(--text-dark);
        margin: 0;
        font-weight: 600;
        letter-spacing: -0.01em;
    }

    .subcategory-list {
        list-style: none;
        padding: 10px 0;
        margin: 0;
    }

    .subcategory-item {
        padding: 12px 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--text-gray);
        transition: background-color 0.2s, padding-left 0.2s;
        border-left: 3px solid transparent;
        cursor: default;
    }

    .subcategory-item:hover {
        background-color: var(--primary-light);
        color: var(--primary-color);
        border-left-color: var(--primary-color);
    }

    .subcategory-name {
        font-weight: 500;
    }

    .subcategory-icon {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.2s ease;
        color: var(--primary-color);
        font-size: 0.9em;
    }

    .subcategory-item:hover .subcategory-icon {
        opacity: 1;
        transform: translateX(0);
    }

    .empty-message {
        padding: 20px 25px;
        color: #94a3b8;
        font-size: 0.9rem;
        background: #fcfcfc;
    }
</style>
<div class="container container-categories">
    
    <div class="header-wrapper">
        <h1 class="page-title">Catégories de Prestations</h1>
        <a href="/categorie/create" class="btn btn-add-category">
            <span>+</span> Ajouter une catégorie
        </a>
    </div>

    <ul class="category-list">
        @forelse($categories as $categorie)
            <li class="category-card">
                <div class="category-header">
                    <div class="category-title">{{ $categorie->libellecategorie }}</div>
                </div>

                @if($categorie->enfants->isNotEmpty())
                    <ul class="subcategory-list">
                        @foreach($categorie->enfants as $enfant)
                            <li class="subcategory-item">
                                <span class="subcategory-name">{{ $enfant->libellecategorie }}</span>
                                
                                <span class="subcategory-icon">
                                    <i class="fas fa-arrow-right"></i> &rarr;
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-message">
                        Aucune sous-catégorie associée
                    </div>
                @endif
            </li>
        @empty
            <li class="category-card text-center py-5">
                <p class="text-muted mb-0">Aucune catégorie enregistrée pour le moment.</p>
            </li>
        @endforelse
    </ul>
</div>