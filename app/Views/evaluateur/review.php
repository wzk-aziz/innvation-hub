<div class="modern-evaluation-page">
    <div class="container-fluid px-4 py-4">
        <!-- Modern Breadcrumb and Header -->
        <div class="page-header mb-5">
            <div class="breadcrumb-section">
                <nav aria-label="breadcrumb">
                    <ol class="modern-breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/evaluateur/dashboard">
                                <i class="fas fa-home"></i>
                                <span>Tableau de bord</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Évaluation des idées</span>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="header-content">
                <div class="header-text">
                    <h1 class="page-title">
                        <i class="fas fa-clipboard-list page-icon"></i>
                        Évaluation des idées
                    </h1>
                    <p class="page-subtitle">
                        Découvrez et évaluez les innovations proposées par les employés
                    </p>
                </div>
                <div class="header-actions">
                    <a href="/evaluateur/dashboard" class="btn-modern btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour au tableau de bord</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Filters Section -->
        <div class="filters-section mb-5">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-left">
                        <h3 class="section-title">
                            <i class="fas fa-filter section-icon"></i>
                            Filtres de recherche
                        </h3>
                        <p class="section-subtitle">Affinez votre recherche d'idées</p>
                    </div>
                </div>
                <div class="card-content">
                    <form method="GET" class="filter-form">
                        <div class="filter-grid">
                            <div class="filter-group">
                                <label for="theme_id" class="filter-label">
                                    <i class="fas fa-tag"></i>
                                    Thème
                                </label>
                                <select name="theme_id" id="theme_id" class="modern-select">
                                    <option value="">Tous les thèmes</option>
                                    <?php if (!empty($themes)): ?>
                                        <?php foreach ($themes as $theme): ?>
                                            <option value="<?= $theme['id'] ?>" 
                                                    <?= isset($_GET['theme_id']) && $_GET['theme_id'] == $theme['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($theme['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label for="status" class="filter-label">
                                    <i class="fas fa-info-circle"></i>
                                    Statut
                                </label>
                                <select name="status" id="status" class="modern-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="under_review" <?= isset($_GET['status']) && $_GET['status'] == 'under_review' ? 'selected' : '' ?>>
                                        En cours d'évaluation
                                    </option>
                                    <option value="accepted" <?= isset($_GET['status']) && $_GET['status'] == 'accepted' ? 'selected' : '' ?>>
                                        Acceptées
                                    </option>
                                </select>
                            </div>
                            
                            <div class="filter-group search-group">
                                <label for="search" class="filter-label">
                                    <i class="fas fa-search"></i>
                                    Recherche
                                </label>
                                <div class="search-input-wrapper">
                                    <input type="text" name="search" id="search" class="modern-input" 
                                           placeholder="Titre, description, auteur..." 
                                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                    <button type="submit" class="search-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="filter-actions">
                                <button type="submit" class="btn-modern btn-primary">
                                    <i class="fas fa-filter"></i>
                                    <span>Appliquer les filtres</span>
                                </button>
                                <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['status'])): ?>
                                    <a href="/evaluateur/review" class="btn-modern btn-outline">
                                        <i class="fas fa-times"></i>
                                        <span>Effacer</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modern Ideas List -->
        <div class="ideas-section">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-left">
                        <h3 class="section-title">
                            <i class="fas fa-lightbulb section-icon"></i>
                            Liste des idées
                        </h3>
                        <p class="section-subtitle">
                            <?= $pagination['total'] ?? 0 ?> idée<?= ($pagination['total'] ?? 0) > 1 ? 's' : '' ?> trouvée<?= ($pagination['total'] ?? 0) > 1 ? 's' : '' ?>
                        </p>
                    </div>
                    <div class="header-actions">
                        <div class="view-toggle">
                            <input type="radio" class="view-check" name="view" id="card-view" checked>
                            <label class="view-btn" for="card-view" title="Vue en cartes">
                                <i class="fas fa-th-large"></i>
                            </label>
                            <input type="radio" class="view-check" name="view" id="list-view">
                            <label class="view-btn" for="list-view" title="Vue en liste">
                                <i class="fas fa-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <?php if (!empty($ideas)): ?>
                        <!-- Modern Card View -->
                        <div id="cards-container" class="ideas-grid">
                            <?php foreach ($ideas as $index => $idea): ?>
                                <div class="idea-card-modern <?= $index === 0 ? 'featured' : '' ?>">
                                    <div class="idea-header">
                                        <div class="idea-status">
                                            <span class="status-badge status-<?= $idea['status'] == 'accepted' ? 'success' : 'warning' ?>">
                                                <i class="fas fa-<?= $idea['status'] == 'accepted' ? 'check-circle' : 'clock' ?>"></i>
                                                <?= $idea['status'] == 'accepted' ? 'Acceptée' : 'En cours' ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($idea['evaluation_score'])): ?>
                                            <div class="evaluation-badge">
                                                <div class="stars">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $idea['evaluation_score'] ? 'star-filled' : 'star-empty' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="score-text"><?= $idea['evaluation_score'] ?>/5</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="idea-content">
                                        <h4 class="idea-title">
                                            <?= htmlspecialchars($idea['title']) ?>
                                        </h4>
                                        <p class="idea-description">
                                            <?= htmlspecialchars(substr($idea['description'], 0, 120)) ?><?= strlen($idea['description']) > 120 ? '...' : '' ?>
                                        </p>
                                        
                                        <div class="idea-meta">
                                            <div class="meta-item">
                                                <i class="fas fa-tag"></i>
                                                <span><?= htmlspecialchars($idea['theme_name'] ?? 'Aucun thème') ?></span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-user"></i>
                                                <span><?= htmlspecialchars(($idea['author_first_name'] ?? '') . ' ' . ($idea['author_last_name'] ?? '')) ?: 'Auteur inconnu' ?></span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-calendar"></i>
                                                <span><?= !empty($idea['created_at']) ? date('d/m/Y', strtotime($idea['created_at'])) : 'Date inconnue' ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="idea-actions">
                                        <a href="/evaluateur/review/<?= $idea['id'] ?>" class="btn-evaluate-modern">
                                            <i class="fas fa-<?= !empty($idea['evaluation_score']) ? 'eye' : 'star' ?>"></i>
                                            <span><?= !empty($idea['evaluation_score']) ? 'Voir évaluation' : 'Évaluer cette idée' ?></span>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Modern List View -->
                        <div id="list-container" class="d-none">
                            <div class="modern-table-container">
                                <div class="table-responsive">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>Idée</th>
                                                <th>Thème</th>
                                                <th>Auteur</th>
                                                <th>Date</th>
                                                <th>Statut</th>
                                                <th>Évaluation</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ideas as $idea): ?>
                                                <tr class="table-row">
                                                    <td class="idea-info">
                                                        <div class="idea-details">
                                                            <h5 class="table-title"><?= htmlspecialchars($idea['title']) ?></h5>
                                                            <p class="table-description">
                                                                <?= htmlspecialchars(substr($idea['description'], 0, 80)) ?>...
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="theme-badge">
                                                            <i class="fas fa-tag"></i>
                                                            <?= htmlspecialchars($idea['theme_name'] ?? 'Aucun thème') ?>
                                                        </span>
                                                    </td>
                                                    <td class="author-cell">
                                                        <div class="author-info">
                                                            <i class="fas fa-user"></i>
                                                            <span><?= htmlspecialchars(($idea['author_first_name'] ?? '') . ' ' . ($idea['author_last_name'] ?? '')) ?: 'Auteur inconnu' ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="date-cell">
                                                        <i class="fas fa-calendar"></i>
                                                        <?= !empty($idea['created_at']) ? date('d/m/Y', strtotime($idea['created_at'])) : 'Date inconnue' ?>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge-table status-<?= $idea['status'] == 'accepted' ? 'success' : 'warning' ?>">
                                                            <i class="fas fa-<?= $idea['status'] == 'accepted' ? 'check-circle' : 'clock' ?>"></i>
                                                            <?= $idea['status'] == 'accepted' ? 'Acceptée' : 'En cours' ?>
                                                        </span>
                                                    </td>
                                                    <td class="evaluation-cell">
                                                        <?php if (!empty($idea['evaluation_score'])): ?>
                                                            <div class="evaluation-info">
                                                                <div class="stars-small">
                                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                        <i class="fas fa-star <?= $i <= $idea['evaluation_score'] ? 'star-filled' : 'star-empty' ?>"></i>
                                                                    <?php endfor; ?>
                                                                </div>
                                                                <span class="score-small"><?= $idea['evaluation_score'] ?>/5</span>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="no-evaluation">
                                                                <i class="fas fa-minus-circle"></i>
                                                                Non évaluée
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="actions-cell">
                                                        <a href="/evaluateur/review/<?= $idea['id'] ?>" class="btn-table-action">
                                                            <i class="fas fa-<?= !empty($idea['evaluation_score']) ? 'eye' : 'star' ?>"></i>
                                                            <?= !empty($idea['evaluation_score']) ? 'Voir' : 'Évaluer' ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Modern Pagination -->
                        <?php if (isset($pagination) && $pagination['pages'] > 1): ?>
                            <div class="modern-pagination">
                                <nav aria-label="Navigation des pages">
                                    <ul class="pagination-list">
                                        <?php if ($pagination['current'] > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link prev-link" href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                                    <i class="fas fa-chevron-left"></i>
                                                    <span>Précédent</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = max(1, $pagination['current'] - 2); $i <= min($pagination['pages'], $pagination['current'] + 2); $i++): ?>
                                            <li class="page-item <?= $i == $pagination['current'] ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($pagination['current'] < $pagination['pages']): ?>
                                            <li class="page-item">
                                                <a class="page-link next-link" href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                                    <span>Suivant</span>
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-search-plus"></i>
                            </div>
                            <h4>Aucune idée trouvée</h4>
                            <p class="empty-message">
                                <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['status'])): ?>
                                    Essayez de modifier vos critères de recherche pour découvrir plus d'idées.
                                <?php else: ?>
                                    Il n'y a actuellement aucune idée à évaluer. Revenez plus tard !
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['status'])): ?>
                                <a href="/evaluateur/review" class="btn-modern btn-primary">
                                    <i class="fas fa-refresh"></i>
                                    <span>Voir toutes les idées</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardView = document.getElementById('card-view');
    const listView = document.getElementById('list-view');
    const cardsContainer = document.getElementById('cards-container');
    const listContainer = document.getElementById('list-container');

    cardView.addEventListener('change', function() {
        if (this.checked) {
            cardsContainer.classList.remove('d-none');
            listContainer.classList.add('d-none');
            localStorage.setItem('evaluateur_view_preference', 'cards');
        }
    });

    listView.addEventListener('change', function() {
        if (this.checked) {
            cardsContainer.classList.add('d-none');
            listContainer.classList.remove('d-none');
            localStorage.setItem('evaluateur_view_preference', 'list');
        }
    });

    // Restore view preference
    const savedView = localStorage.getItem('evaluateur_view_preference');
    if (savedView === 'list') {
        listView.checked = true;
        listView.dispatchEvent(new Event('change'));
    }

    // Enhanced search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('.filter-form').submit();
            }
        });
    }

    // Add hover effects to idea cards
    const ideaCards = document.querySelectorAll('.idea-card-modern');
    ideaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<style>
/* Modern Evaluation Page Styles */
.modern-evaluation-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Page Header */
.page-header {
    margin-bottom: 3rem;
}

.breadcrumb-section {
    margin-bottom: 2rem;
}

.modern-breadcrumb {
    display: flex;
    align-items: center;
    gap: 1rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.9rem;
}

.modern-breadcrumb .breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modern-breadcrumb .breadcrumb-item a {
    color: #6b7280;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.2s ease;
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.modern-breadcrumb .breadcrumb-item a:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
}

.modern-breadcrumb .breadcrumb-item.active {
    color: #1f2937;
    font-weight: 500;
}

.modern-breadcrumb .breadcrumb-item:not(:last-child)::after {
    content: '/';
    color: #d1d5db;
    margin-left: 1rem;
}

.header-content {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.header-text h1.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-icon {
    color: #667eea;
}

.page-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 1.1rem;
}

.header-actions {
    flex-shrink: 0;
}

/* Modern Cards */
.modern-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.card-header-modern {
    padding: 2rem 2rem 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.header-left {
    flex: 1;
}

.section-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
}

.section-icon {
    margin-right: 0.75rem;
    color: #667eea;
}

.section-subtitle {
    color: #6b7280;
    margin: 0;
    font-size: 0.9rem;
}

.header-actions {
    flex-shrink: 0;
}

.card-content {
    padding: 0 2rem 2rem 2rem;
}

/* Modern Buttons */
.btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    border: none;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.btn-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
    color: white;
}

.btn-outline {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #4b5563;
}

/* Filters Section */
.filters-section {
    margin-bottom: 2.5rem;
}

.filter-form {
    padding: 0;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr auto;
    gap: 2rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.filter-label i {
    color: #667eea;
}

.modern-select,
.modern-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    background: white;
}

.modern-select:focus,
.modern-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-group {
    position: relative;
}

.search-input-wrapper {
    position: relative;
    display: flex;
}

.search-input-wrapper .modern-input {
    flex: 1;
    padding-right: 3rem;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.search-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: 2px solid #667eea;
    color: white;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.search-button:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}

.filter-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* View Toggle */
.view-toggle {
    display: flex;
    background: #f3f4f6;
    border-radius: 12px;
    padding: 0.25rem;
}

.view-check {
    display: none;
}

.view-btn {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    background: transparent;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.view-check:checked + .view-btn {
    background: white;
    color: #667eea;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Ideas Grid */
.ideas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 2rem;
}

.idea-card-modern {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.idea-card-modern:hover {
    border-color: #e0e7ff;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
}

.idea-card-modern.featured {
    background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
    border-color: #c7d2fe;
}

.idea-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.status-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.evaluation-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f0f9ff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: 1px solid #e0f2fe;
}

.stars {
    display: flex;
    gap: 2px;
}

.star-filled {
    color: #fbbf24;
}

.star-empty {
    color: #e5e7eb;
}

.score-text {
    font-weight: 600;
    color: #0369a1;
    font-size: 0.9rem;
}

.idea-content {
    margin-bottom: 1.5rem;
}

.idea-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1rem 0;
    line-height: 1.3;
}

.idea-description {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.idea-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    color: #6b7280;
}

.meta-item i {
    color: #9ca3af;
    width: 16px;
}

.idea-actions {
    margin-top: 1.5rem;
}

.btn-evaluate-modern {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-evaluate-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(240, 147, 251, 0.4);
    color: white;
}

/* Modern Table */
.modern-table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead th {
    background: #f8fafc;
    padding: 1.5rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.9rem;
}

.modern-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease;
}

.modern-table tbody tr:hover {
    background: #f9fafb;
}

.modern-table tbody td {
    padding: 1.5rem 1rem;
    vertical-align: top;
}

.idea-details .table-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.idea-details .table-description {
    color: #6b7280;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.4;
}

.theme-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.author-info,
.date-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.9rem;
}

.status-badge-table {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.evaluation-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars-small {
    display: flex;
    gap: 1px;
}

.stars-small i {
    font-size: 0.8rem;
}

.score-small {
    font-weight: 600;
    color: #059669;
    font-size: 0.9rem;
}

.no-evaluation {
    color: #6b7280;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-table-action {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-table-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
}

/* Modern Pagination */
.modern-pagination {
    margin-top: 2.5rem;
    display: flex;
    justify-content: center;
}

.pagination-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.page-item .page-link {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    border: 2px solid transparent;
    background: white;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-item .page-link:hover {
    background: #f3f4f6;
    color: #374151;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.prev-link,
.next-link {
    background: #f8fafc;
    border-color: #e5e7eb;
}

.prev-link:hover,
.next-link:hover {
    background: #f1f5f9;
    border-color: #d1d5db;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}

.empty-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem auto;
}

.empty-icon i {
    font-size: 3rem;
    color: #667eea;
}

.empty-state h4 {
    color: #1f2937;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.empty-message {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .filter-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .search-group {
        grid-column: 1 / -1;
    }
    
    .filter-actions {
        grid-column: 1 / -1;
        justify-content: center;
    }
    
    .ideas-grid {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .modern-evaluation-page {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
        padding: 2rem 1.5rem;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .ideas-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .idea-card-modern {
        padding: 1.5rem;
    }
    
    .idea-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .modern-breadcrumb {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .modern-breadcrumb .breadcrumb-item:not(:last-child)::after {
        display: none;
    }
}
</style>
