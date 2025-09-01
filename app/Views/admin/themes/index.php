<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-themes"></i>
            Gestion des Thématiques
        </h1>
        <div class="page-actions">
            <a href="/admin/themes/create" class="btn btn-primary">
                <i class="icon-plus"></i>
                Nouvelle Thématique
            </a>
        </div>
    </div>
</div>

<!-- Modern Search and Filters -->
<div class="modern-search-container">
    <form method="GET" action="/admin/themes" class="modern-search-form">
        <!-- Main Search Bar -->
        <div class="search-bar-wrapper">
            <div class="search-input-container">
                <i class="search-icon"></i>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Rechercher par nom ou description..."
                       class="modern-search-input">
                <button type="button" class="clear-search" title="Effacer la recherche">
                    <i class="clear-icon"></i>
                </button>
            </div>
        </div>
        
        <!-- Advanced Filters -->
        <div class="advanced-filters">
            <div class="filter-toggle">
                <button type="button" class="filter-toggle-btn">
                    <i class="filter-icon"></i>
                    <span>Filtres avancés</span>
                    <i class="chevron-icon"></i>
                </button>
            </div>
            
            <div class="filter-panel">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="status">Statut:</label>
                        <select id="status" name="status" class="modern-select">
                            <option value="">Tous les statuts</option>
                            <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="search-icon"></i>
                        Rechercher
                    </button>
                    <a href="/admin/themes" class="btn btn-outline">
                        <i class="refresh-icon"></i>
                        Réinitialiser
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Stats Summary -->
<div class="stats-summary">
    <div class="stat-item">
        <span class="stat-value"><?= $totalThemes ?? 0 ?></span>
        <span class="stat-label">Total Thématiques</span>
    </div>
    <div class="stat-item">
        <span class="stat-value"><?= $activeThemes ?? 0 ?></span>
        <span class="stat-label">Actives</span>
    </div>
    <div class="stat-item">
        <span class="stat-value"><?= count($themes ?? []) ?></span>
        <span class="stat-label">Affichées</span>
    </div>
</div>

<!-- Themes Grid -->
<div class="themes-grid">
    <?php if (empty($themes)): ?>
        <div class="no-data-card">
            <div class="no-data-icon">
                <i class="icon-info"></i>
            </div>
            <h3>Aucune thématique trouvée</h3>
            <p>Commencez par créer votre première thématique pour organiser les idées.</p>
            <a href="/admin/themes/create" class="btn btn-primary">
                <i class="icon-plus"></i>
                Créer une thématique
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($themes as $theme): ?>
            <div class="theme-card <?= $theme['is_active'] ? 'active' : 'inactive' ?>" data-theme-id="<?= $theme['id'] ?>">
                <div class="theme-header">
                    <div class="theme-status">
                        <span class="status-badge <?= $theme['is_active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $theme['is_active'] ? 'Actif' : 'Inactif' ?>
                        </span>
                    </div>
                    <div class="theme-actions">
                        <a href="/admin/themes/<?= $theme['id'] ?>/edit" class="btn btn-sm btn-primary">
                            <i class="icon-edit"></i>
                            Modifier
                        </a>
                        <form method="POST" action="/admin/themes/<?= $theme['id'] ?>/delete" class="delete-form" style="display: inline;">
                            <?= \App\Core\Auth::csrfField() ?>
                            <button type="submit" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette thématique ? Cette action est irréversible.')">
                                <i class="icon-trash"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Display Mode -->
                <div class="theme-content display-mode">
                    <h3 class="theme-title"><?= htmlspecialchars($theme['name']) ?></h3>
                    <p class="theme-description">
                        <?= htmlspecialchars($theme['description']) ?>
                    </p>
                </div>
                
                <!-- Edit Mode (Hidden by default) -->
                <div class="theme-content edit-mode" style="display: none;">
                    <form class="edit-form" data-theme-id="<?= $theme['id'] ?>">
                        <?= \App\Core\Auth::csrfField() ?>
                        <div class="edit-group">
                            <label for="edit_name_<?= $theme['id'] ?>" class="edit-label">Nom:</label>
                            <input type="text" 
                                   id="edit_name_<?= $theme['id'] ?>" 
                                   name="name" 
                                   value="<?= htmlspecialchars($theme['name']) ?>"
                                   class="edit-input"
                                   required>
                        </div>
                        <div class="edit-group">
                            <label for="edit_description_<?= $theme['id'] ?>" class="edit-label">Description:</label>
                            <textarea id="edit_description_<?= $theme['id'] ?>" 
                                      name="description" 
                                      class="edit-textarea"
                                      rows="3"
                                      required><?= htmlspecialchars($theme['description']) ?></textarea>
                        </div>
                        <div class="edit-group">
                            <label class="edit-checkbox">
                                <input type="checkbox" 
                                       name="is_active" 
                                       <?= $theme['is_active'] ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Thématique active
                            </label>
                        </div>
                        <div class="edit-actions">
                            <button type="button" class="btn btn-sm btn-outline cancel-edit">
                                <i class="icon-close"></i>
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary save-edit">
                                <i class="icon-check"></i>
                                Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="theme-stats">
                    <div class="stat-item">
                        <span class="stat-value"><?= $theme['ideas_count'] ?? 0 ?></span>
                        <span class="stat-label">Idées</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?= $theme['pending_ideas'] ?? 0 ?></span>
                        <span class="stat-label">En attente</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?= $theme['accepted_ideas'] ?? 0 ?></span>
                        <span class="stat-label">Acceptées</span>
                    </div>
                </div>
                
                <div class="theme-footer">
                    <span class="theme-date">
                        Créée le <?= date('d/m/Y', strtotime($theme['created_at'])) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (($totalPages ?? 0) > 1): ?>
    <div class="pagination-wrapper">
        <nav class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
                   class="page-link">
                    <i class="icon-arrow-left"></i>
                    Précédent
                </a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
                   class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
                   class="page-link">
                    Suivant
                    <i class="icon-arrow-right"></i>
                </a>
            <?php endif; ?>
        </nav>
        
        <div class="pagination-info">
            Page <?= $currentPage ?> sur <?= $totalPages ?> 
            (<?= $totalThemes ?> thématique<?= $totalThemes > 1 ? 's' : '' ?> au total)
        </div>
    </div>
<?php endif; ?>

<style>
.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.theme-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: visible;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    position: relative;
}

.theme-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.theme-card.active {
    border-color: #10b981;
}

.theme-card.inactive {
    border-color: #f59e0b;
    opacity: 0.8;
}

.theme-header {
    padding: 1.5rem 1.5rem 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.theme-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.375rem 0.75rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    background: transparent;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
}

.btn-danger {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.btn-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
    transform: translateY(-1px);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fef3c7;
    color: #92400e;
}

.theme-content {
    padding: 1.5rem;
}

.theme-title {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.theme-description {
    color: #64748b;
    font-size: 0.875rem;
    line-height: 1.6;
    margin: 0;
}

.theme-stats {
    display: flex;
    justify-content: space-around;
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.theme-stats .stat-item {
    text-align: center;
}

.theme-stats .stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #3b82f6;
    display: block;
}

.theme-stats .stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.theme-footer {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.theme-date {
    color: #6b7280;
    font-size: 0.75rem;
}

.no-data-card {
    grid-column: 1 / -1;
    background: white;
    padding: 4rem 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    text-align: center;
}

.no-data-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-data-card h3 {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.no-data-card p {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Dropdown Styles */
.dropdown {
    position: relative;
}

.dropdown-toggle {
    border: none;
    background: none;
    padding: 0.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: background 0.2s ease;
}

.dropdown-toggle:hover {
    background: #f3f4f6;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    min-width: 160px;
    z-index: 1000;
    display: none;
    overflow: hidden;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    transition: background 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.dropdown-item:hover {
    background: #f3f4f6;
}

.dropdown-item.text-danger {
    color: #dc2626;
}

.dropdown-item.text-danger:hover {
    background: #fef2f2;
}

.dropdown-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 0.5rem 0;
}

.dropdown-form {
    margin: 0;
}

/* Modern Search Bar Styles */
.modern-search-container {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow: hidden;
}

.modern-search-form {
    padding: 1.5rem;
}

.search-bar-wrapper {
    margin-bottom: 1rem;
}

.search-input-container {
    position: relative;
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.search-input-container:focus-within {
    background: white;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
    width: 20px;
    height: 20px;
    margin-right: 0.75rem;
    color: #6b7280;
    flex-shrink: 0;
}

.search-icon::before {
    content: "🔍";
    font-size: 16px;
}

.modern-search-input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #1e293b;
    outline: none;
    padding: 0.25rem 0;
}

.modern-search-input::placeholder {
    color: #9ca3af;
    font-weight: 400;
}

.clear-search {
    background: none;
    border: none;
    padding: 0.25rem;
    cursor: pointer;
    color: #6b7280;
    opacity: 0;
    transition: opacity 0.2s ease;
    border-radius: 0.25rem;
}

.search-input-container:focus-within .clear-search,
.modern-search-input:not(:placeholder-shown) + .clear-search {
    opacity: 1;
}

.clear-search:hover {
    background: #f3f4f6;
    color: #374151;
}

.clear-icon::before {
    content: "✕";
    font-size: 14px;
}

.advanced-filters {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.filter-toggle-btn {
    background: none;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    padding: 0.5rem 0;
    transition: color 0.2s ease;
}

.filter-toggle-btn:hover {
    color: #374151;
}

.filter-icon::before {
    content: "⚙️";
    font-size: 14px;
}

.chevron-icon::before {
    content: "▼";
    font-size: 12px;
    transition: transform 0.2s ease;
}

.filter-toggle.active .chevron-icon::before {
    transform: rotate(180deg);
}

.filter-panel {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.filter-toggle.active + .filter-panel {
    max-height: 200px;
    margin-top: 1rem;
}

.filter-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group {
    flex: 1;
}

.filter-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.modern-select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.5rem;
    background: white;
    font-size: 0.875rem;
    color: #1e293b;
    transition: border-color 0.2s ease;
}

.modern-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.refresh-icon::before {
    content: "🔄";
    font-size: 14px;
}

/* Additional Icons */
.icon-more::before { content: "⋯"; }
.icon-themes::before { content: "🎯"; }
.icon-plus::before { content: "➕"; }
.icon-eye::before { content: "👁️"; }
.icon-edit::before { content: "✏️"; }
.icon-trash::before { content: "🗑️"; }
.icon-check::before { content: "✅"; }
.icon-close::before { content: "✖️"; }
.icon-arrow-left::before { content: "←"; }
.icon-arrow-right::before { content: "→"; }
.icon-info::before { content: "ℹ️"; }
.icon-more::before { content: "⋮"; }

@media (max-width: 768px) {
    .modern-search-form {
        padding: 1rem;
    }
    
    .filter-row {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .themes-grid {
        grid-template-columns: 1fr;
    }
    
    .theme-card {
        margin: 0 1rem;
    }
    
    .theme-stats {
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }
    
    .theme-actions {
        flex-direction: column;
        gap: 0.25rem;
        align-items: stretch;
    }
    
    .theme-actions .btn {
        justify-content: center;
        font-size: 0.7rem;
    }
}

/* Inline Edit Styles */
.theme-content.edit-mode {
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.5rem;
    margin: 0 1rem 1rem;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.edit-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.edit-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.edit-input,
.edit-textarea {
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    color: #1e293b;
    background: white;
    transition: all 0.2s ease;
    font-family: inherit;
}

.edit-input:focus,
.edit-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.edit-textarea {
    resize: vertical;
    min-height: 80px;
    line-height: 1.5;
}

.edit-checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.5rem 0;
}

.edit-checkbox input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 0.25rem;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.edit-checkbox input[type="checkbox"]:checked + .checkmark {
    background: #3b82f6;
    border-color: #3b82f6;
}

.edit-checkbox input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.edit-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.theme-card.editing {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border: 2px solid #3b82f6;
}

.theme-card.editing .theme-stats,
.theme-card.editing .theme-footer {
    display: none;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    z-index: 10;
}

.loading-spinner {
    width: 32px;
    height: 32px;
    border: 3px solid #e2e8f0;
    border-top: 3px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Mobile responsive for edit mode */
@media (max-width: 768px) {
    .edit-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .edit-actions .btn {
        justify-content: center;
    }
    
    .theme-content.edit-mode {
        margin: 0 0.5rem 1rem;
        padding: 1rem;
    }
}

/* Dropdown Styles */
.dropdown {
    position: relative;
    display: inline-block;
}

.btn-ghost {
    background: transparent;
    border: 1px solid transparent;
    color: #6b7280;
    padding: 0.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 32px;
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-ghost:hover {
    background: #f3f4f6;
    color: #374151;
    border-color: #d1d5db;
}

.btn-sm {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}

.dropdown-toggle {
    position: relative;
    z-index: 10;
    pointer-events: auto;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 160px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    margin-top: 0.25rem;
    padding: 0.5rem 0;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.dropdown-item.text-danger {
    color: #dc2626;
}

.dropdown-item.text-danger:hover {
    background: #fef2f2;
    color: #b91c1c;
}

.dropdown-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 0.25rem 0;
}

.dropdown-form {
    margin: 0;
}

/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    padding: 1rem 1.5rem;
    max-width: 400px;
    z-index: 9999;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.notification.show {
    opacity: 1;
    transform: translateX(0);
}

.notification-success {
    border-left: 4px solid #10b981;
}

.notification-error {
    border-left: 4px solid #ef4444;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-success .notification-icon::before {
    content: '✅';
    font-size: 18px;
}

.notification-error .notification-icon::before {
    content: '❌';
    font-size: 18px;
}

.notification-close {
    position: absolute;
    top: 0.5rem;
    right: 0.75rem;
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.25rem;
    line-height: 1;
}

.notification-close:hover {
    color: #6b7280;
}

<script>
console.log('Script tag loaded!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up theme management...');
    
    // Modern search functionality
    const searchInput = document.getElementById('search');
    const clearBtn = document.querySelector('.clear-search');
    const filterToggle = document.querySelector('.filter-toggle-btn');
    const filterToggleContainer = document.querySelector('.filter-toggle');
    
    // Clear search functionality
    if (clearBtn && searchInput) {
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
        });
    }
    
    // Filter toggle functionality
    if (filterToggle && filterToggleContainer) {
        filterToggle.addEventListener('click', function() {
            filterToggleContainer.classList.toggle('active');
        });
    }
    
    // Enhanced placeholder animation for themes
    if (searchInput) {
        const originalPlaceholder = searchInput.placeholder;
        const placeholders = [
            'Rechercher par nom...',
            'Rechercher par description...',
            'Trouver une thématique...',
            originalPlaceholder
        ];
        let currentIndex = 0;
        
        setInterval(() => {
            if (searchInput === document.activeElement) return;
            currentIndex = (currentIndex + 1) % placeholders.length;
            searchInput.placeholder = placeholders[currentIndex];
        }, 3000);
    }

    // Inline editing functionality
    setupInlineEditing();
});

function setupInlineEditing() {
    console.log('Setting up inline editing...');
    
    // Edit button click handlers
    document.querySelectorAll('.edit-theme').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Edit button clicked');
            const themeId = this.getAttribute('data-theme-id');
            toggleEditMode(themeId, true);
        });
    });

    // Cancel edit button handlers
    document.querySelectorAll('.cancel-edit').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.edit-form');
            const themeId = form.getAttribute('data-theme-id');
            toggleEditMode(themeId, false);
        });
    });

    // Save edit form handlers
    document.querySelectorAll('.edit-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const themeId = this.getAttribute('data-theme-id');
            saveThemeEdit(themeId, this);
        });
    });
}

function toggleEditMode(themeId, isEditing) {
    const themeCard = document.querySelector(`[data-theme-id="${themeId}"]`);
    const displayMode = themeCard.querySelector('.display-mode');
    const editMode = themeCard.querySelector('.edit-mode');
    
    if (isEditing) {
        // Close any other edit modes
        document.querySelectorAll('.theme-card.editing').forEach(card => {
            if (card !== themeCard) {
                const otherDisplayMode = card.querySelector('.display-mode');
                const otherEditMode = card.querySelector('.edit-mode');
                card.classList.remove('editing');
                otherDisplayMode.style.display = 'block';
                otherEditMode.style.display = 'none';
            }
        });
        
        // Enable edit mode
        themeCard.classList.add('editing');
        displayMode.style.display = 'none';
        editMode.style.display = 'block';
        
        // Focus on the first input
        const firstInput = editMode.querySelector('.edit-input');
        if (firstInput) {
            firstInput.focus();
            firstInput.select();
        }
        
        // Close dropdown
        const dropdown = themeCard.querySelector('.dropdown-menu');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    } else {
        // Disable edit mode
        themeCard.classList.remove('editing');
        displayMode.style.display = 'block';
        editMode.style.display = 'none';
        
        // Reset form to original values
        resetEditForm(themeId);
    }
}

function resetEditForm(themeId) {
    const themeCard = document.querySelector(`[data-theme-id="${themeId}"]`);
    const form = themeCard.querySelector('.edit-form');
    
    // Get original values from display mode
    const originalName = themeCard.querySelector('.theme-title').textContent.trim();
    const originalDescription = themeCard.querySelector('.theme-description').textContent.trim();
    const isActive = themeCard.classList.contains('active');
    
    // Reset form fields
    form.querySelector('input[name="name"]').value = originalName;
    form.querySelector('textarea[name="description"]').value = originalDescription;
    form.querySelector('input[name="is_active"]').checked = isActive;
}

function saveThemeEdit(themeId, form) {
    const themeCard = document.querySelector(`[data-theme-id="${themeId}"]`);
    const saveButton = form.querySelector('.save-edit');
    const originalText = saveButton.innerHTML;
    
    // Show loading state
    saveButton.disabled = true;
    saveButton.innerHTML = '<div class="loading-spinner"></div> Sauvegarde...';
    
    // Prepare form data
    const formData = new FormData(form);
    formData.append('_method', 'POST');
    
    // Send AJAX request
    fetch(`/admin/themes/${themeId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update display mode with new values
            updateThemeDisplay(themeId, {
                name: formData.get('name'),
                description: formData.get('description'),
                is_active: formData.has('is_active')
            });
            
            // Exit edit mode
            toggleEditMode(themeId, false);
            
            // Show success message
            showNotification('Thématique modifiée avec succès', 'success');
            
            // Update status badge
            updateStatusBadge(themeId, formData.has('is_active'));
        } else {
            showNotification(data.message || 'Erreur lors de la modification', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur de connexion', 'error');
    })
    .finally(() => {
        // Reset button
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    });
}

function updateThemeDisplay(themeId, data) {
    const themeCard = document.querySelector(`[data-theme-id="${themeId}"]`);
    
    // Update title
    themeCard.querySelector('.theme-title').textContent = data.name;
    
    // Update description
    themeCard.querySelector('.theme-description').textContent = data.description;
    
    // Update card classes
    if (data.is_active) {
        themeCard.classList.remove('inactive');
        themeCard.classList.add('active');
    } else {
        themeCard.classList.remove('active');
        themeCard.classList.add('inactive');
    }
}

function updateStatusBadge(themeId, isActive) {
    const themeCard = document.querySelector(`[data-theme-id="${themeId}"]`);
    const statusBadge = themeCard.querySelector('.status-badge');
    
    if (isActive) {
        statusBadge.className = 'status-badge status-active';
        statusBadge.textContent = 'Actif';
    } else {
        statusBadge.className = 'status-badge status-inactive';
        statusBadge.textContent = 'Inactif';
    }
}

function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="notification-icon"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">&times;</button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
    
    // Manual close button
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    });
}
</script>
</style>
