<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-ideas"></i>
            Supervision des Idées
        </h1>
        <div class="page-actions">
            <div class="status-filter-pills">
                <button class="filter-pill <?= empty($status) ? 'active' : '' ?>" 
                        data-status="" 
                        data-count="<?= $totalIdeas ?? 0 ?>">
                    <span class="pill-icon">📋</span>
                    <span class="pill-text">Toutes</span>
                    <span class="pill-count"><?= $totalIdeas ?? 0 ?></span>
                </button>
                <button class="filter-pill <?= ($status ?? '') === 'submitted' ? 'active' : '' ?>" 
                        data-status="submitted" 
                        data-count="<?= $pendingIdeas ?? 0 ?>">
                    <span class="pill-icon">📝</span>
                    <span class="pill-text">Soumises</span>
                    <span class="pill-count"><?= $pendingIdeas ?? 0 ?></span>
                </button>
                <button class="filter-pill <?= ($status ?? '') === 'under_review' ? 'active' : '' ?>" 
                        data-status="under_review" 
                        data-count="<?= $underReviewIdeas ?? 0 ?>">
                    <span class="pill-icon">👀</span>
                    <span class="pill-text">En révision</span>
                    <span class="pill-count"><?= $underReviewIdeas ?? 0 ?></span>
                </button>
                <button class="filter-pill <?= ($status ?? '') === 'accepted' ? 'active' : '' ?>" 
                        data-status="accepted" 
                        data-count="0">
                    <span class="pill-icon">✅</span>
                    <span class="pill-text">Acceptées</span>
                    <span class="pill-count">0</span>
                </button>
                <button class="filter-pill <?= ($status ?? '') === 'rejected' ? 'active' : '' ?>" 
                        data-status="rejected" 
                        data-count="0">
                    <span class="pill-icon">❌</span>
                    <span class="pill-text">Rejetées</span>
                    <span class="pill-count">0</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modern Search and Filters -->
<div class="modern-search-container">
    <form method="GET" action="/admin/ideas" class="modern-search-form">
        <!-- Main Search Bar -->
        <div class="search-bar-wrapper">
            <div class="search-input-container">
                <i class="search-icon"></i>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Rechercher par titre, description ou auteur..."
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
                        <label for="theme">Thématique:</label>
                        <select id="theme" name="theme" class="modern-select">
                            <option value="">Toutes les thématiques</option>
                            <?php foreach (($themes ?? []) as $theme): ?>
                                <option value="<?= $theme['id'] ?>" 
                                        <?= ($selectedTheme ?? '') == $theme['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($theme['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status">Statut:</label>
                        <select id="status" name="status" class="modern-select">
                            <option value="">Tous les statuts</option>
                            <option value="submitted" <?= ($status ?? '') === 'submitted' ? 'selected' : '' ?>>Soumises</option>
                            <option value="under_review" <?= ($status ?? '') === 'under_review' ? 'selected' : '' ?>>En révision</option>
                            <option value="accepted" <?= ($status ?? '') === 'accepted' ? 'selected' : '' ?>>Acceptées</option>
                            <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejetées</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="button" class="btn btn-outline btn-sm reset-filters">
                        <i class="refresh-icon"></i>
                        Réinitialiser
                    </button>
                    <div class="search-status">
                        <span class="search-indicator">
                            <i class="loading-icon"></i>
                            <span class="indicator-text">Recherche automatique...</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Stats Summary -->
<div class="stats-summary">
    <div class="stat-item">
        <span class="stat-value"><?= $totalIdeas ?? 0 ?></span>
        <span class="stat-label">Total Idées</span>
    </div>
    <div class="stat-item">
        <span class="stat-value"><?= $pendingIdeas ?? 0 ?></span>
        <span class="stat-label">En attente</span>
    </div>
    <div class="stat-item">
        <span class="stat-value"><?= $underReviewIdeas ?? 0 ?></span>
        <span class="stat-label">En révision</span>
    </div>
    <div class="stat-item">
        <span class="stat-value"><?= count($ideas ?? []) ?></span>
        <span class="stat-label">Affichées</span>
    </div>
</div>

<!-- Ideas Grid -->
<div class="ideas-grid">
    <?php if (empty($ideas ?? [])): ?>
        <div class="no-data-card">
            <div class="no-data-icon">
                <i class="icon-info"></i>
            </div>
            <h3>Aucune idée trouvée</h3>
            <p>Il n'y a pas d'idées correspondant à vos critères de recherche.</p>
        </div>
    <?php else: ?>
        <?php foreach ($ideas as $idea): ?>
            <div class="idea-card status-<?= strtolower($idea['status']) ?>">
                <div class="idea-header">
                    <div class="idea-status">
                        <span class="status-badge status-<?= strtolower($idea['status']) ?>">
                            <?= ucfirst($idea['status']) ?>
                        </span>
                    </div>
                    <div class="idea-date">
                        <?= date('d/m/Y', strtotime($idea['created_at'])) ?>
                    </div>
                </div>
                
                <div class="idea-content">
                    <h3 class="idea-title"><?= htmlspecialchars($idea['title']) ?></h3>
                    <p class="idea-description">
                        <?= htmlspecialchars(substr($idea['description'], 0, 150)) ?>
                        <?= strlen($idea['description']) > 150 ? '...' : '' ?>
                    </p>
                    
                    <div class="idea-meta">
                        <div class="idea-author">
                            <i class="icon-user"></i>
                            <?= htmlspecialchars($idea['first_name'] . ' ' . $idea['last_name']) ?>
                        </div>
                        <div class="idea-theme">
                            <i class="icon-tag"></i>
                            <?= htmlspecialchars($idea['theme_name']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="idea-actions">
                    <a href="/admin/ideas/<?= $idea['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="icon-eye"></i>
                        Voir détails
                    </a>
                    
                    <?php if ($idea['status'] === 'submitted'): ?>
                        <form method="POST" action="/admin/ideas/<?= $idea['id'] ?>/status" class="status-form">
                            <?= \App\Core\Auth::csrfField() ?>
                            <input type="hidden" name="status" value="under_review">
                            <input type="hidden" name="redirect" value="list">
                            <button type="submit" class="btn btn-sm btn-info">
                                <i class="icon-review"></i>
                                Mettre en révision
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($idea['status'] === 'under_review'): ?>
                        <div class="action-group">
                            <form method="POST" action="/admin/ideas/<?= $idea['id'] ?>/status" class="status-form">
                                <?= \App\Core\Auth::csrfField() ?>
                                <input type="hidden" name="status" value="accepted">
                                <input type="hidden" name="redirect" value="list">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="icon-check"></i>
                                    Accepter
                                </button>
                            </form>
                            
                            <form method="POST" action="/admin/ideas/<?= $idea['id'] ?>/status" class="status-form">
                                <?= \App\Core\Auth::csrfField() ?>
                                <input type="hidden" name="status" value="rejected">
                                <input type="hidden" name="redirect" value="list">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="icon-close"></i>
                                    Rejeter
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (($totalPages ?? 0) > 1): ?>
    <div class="pagination-wrapper">
        <nav class="pagination">
            <?php if (($currentPage ?? 1) > 1): ?>
                <a href="?page=<?= ($currentPage ?? 1) - 1 ?>&search=<?= urlencode($search ?? '') ?>&theme=<?= urlencode($selectedTheme ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
                   class="page-link">
                    <i class="icon-arrow-left"></i>
                    Précédent
                </a>
            <?php endif; ?>
            
            <?php for ($i = max(1, ($currentPage ?? 1) - 2); $i <= min(($totalPages ?? 1), ($currentPage ?? 1) + 2); $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&theme=<?= urlencode($selectedTheme ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
                   class="page-link <?= $i === ($currentPage ?? 1) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                <a href="?page=<?= ($currentPage ?? 1) + 1 ?>&search=<?= urlencode($search ?? '') ?>&theme=<?= urlencode($selectedTheme ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
                   class="page-link">
                    Suivant
                    <i class="icon-arrow-right"></i>
                </a>
            <?php endif; ?>
        </nav>
        
        <div class="pagination-info">
            Page <?= $currentPage ?? 1 ?> sur <?= $totalPages ?? 1 ?> 
            (<?= $totalIdeas ?? 0 ?> idée<?= ($totalIdeas ?? 0) > 1 ? 's' : '' ?> au total)
        </div>
    </div>
<?php endif; ?>

<style>
.ideas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.idea-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.2s ease;
    border-left: 4px solid #e5e7eb;
}

.idea-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.idea-card.status-submitted {
    border-left-color: #f59e0b;
}

.idea-card.status-under_review {
    border-left-color: #3b82f6;
}

.idea-card.status-accepted {
    border-left-color: #10b981;
}

.idea-card.status-rejected {
    border-left-color: #ef4444;
}

.idea-header {
    padding: 1.5rem 1.5rem 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-submitted {
    background: #fef3c7;
    color: #92400e;
}

.status-under_review {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-accepted {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.idea-date {
    color: #6b7280;
    font-size: 0.75rem;
}

.idea-content {
    padding: 1.5rem;
}

.idea-title {
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.idea-description {
    color: #64748b;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.idea-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.idea-author,
.idea-theme {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.75rem;
}

.idea-actions {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.action-group {
    display: flex;
    gap: 0.5rem;
}

.status-form {
    display: inline;
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
    margin: 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Modern Filter Pills */
.status-filter-pills {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-pill {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 2rem;
    background: white;
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.filter-pill::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
    transition: left 0.5s ease;
}

.filter-pill:hover::before {
    left: 100%;
}

.filter-pill:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.filter-pill.active {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-color: #3b82f6;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.filter-pill.loading {
    pointer-events: none;
    opacity: 0.7;
}

.pill-icon {
    font-size: 1rem;
    line-height: 1;
}

.pill-text {
    font-weight: 600;
    letter-spacing: 0.025em;
}

.pill-count {
    background: rgba(255,255,255,0.2);
    padding: 0.125rem 0.5rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 700;
    min-width: 1.5rem;
    text-align: center;
}

.filter-pill.active .pill-count {
    background: rgba(255,255,255,0.3);
}

.filter-pill:not(.active) .pill-count {
    background: #f1f5f9;
    color: #475569;
}

/* Pulse animation for loading states */
@keyframes pillPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.filter-pill.loading .pill-count {
    animation: pillPulse 1.5s infinite;
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
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.search-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #10b981;
    font-size: 0.75rem;
    font-weight: 500;
    opacity: 0;
    transition: all 0.3s ease;
    transform: translateY(5px);
}

.search-indicator.active {
    opacity: 1;
    transform: translateY(0);
}

.loading-icon {
    width: 12px;
    height: 12px;
    border: 2px solid #d1fae5;
    border-top: 2px solid #10b981;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.reset-filters {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    transition: all 0.2s ease;
}

.reset-filters:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #475569;
}

.refresh-icon::before {
    content: "🔄";
    font-size: 14px;
}

/* Additional Icons */
.icon-filter::before { content: "🔽"; }
.icon-user::before { content: "👤"; }
.icon-tag::before { content: "🏷️"; }
.icon-review::before { content: "👁️"; }
.icon-check::before { content: "✅"; }
.icon-close::before { content: "❌"; }

@media (max-width: 768px) {
    .page-header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .status-filter-pills {
        justify-content: center;
        gap: 0.25rem;
    }
    
    .filter-pill {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        min-width: auto;
    }
    
    .pill-text {
        display: none;
    }
    
    .filter-pill.active .pill-text {
        display: inline;
    }
    
    .modern-search-form {
        padding: 1rem;
    }
    
    .filter-row {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .filter-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .ideas-grid {
        grid-template-columns: 1fr;
    }
    
    .idea-actions {
        flex-direction: column;
    }
    
    .action-group {
        justify-content: center;
    }
}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic Search and Filter System
    const searchInput = document.getElementById('search');
    const clearBtn = document.querySelector('.clear-search');
    const filterToggle = document.querySelector('.filter-toggle-btn');
    const filterToggleContainer = document.querySelector('.filter-toggle');
    const themeSelect = document.getElementById('theme');
    const statusSelect = document.getElementById('status');
    const filterPills = document.querySelectorAll('.filter-pill');
    const resetBtn = document.querySelector('.reset-filters');
    const searchIndicator = document.querySelector('.search-indicator');
    
    let searchTimeout;
    
    // Initialize dynamic search
    initializeDynamicSearch();
    
    function initializeDynamicSearch() {
        // Search input - dynamic search with debounce
        if (searchInput) {
            searchInput.addEventListener('input', handleSearchInput);
            searchInput.addEventListener('keydown', handleSearchKeydown);
        }
        
        // Filter pills - instant filtering
        filterPills.forEach(pill => {
            pill.addEventListener('click', handlePillClick);
        });
        
        // Theme and status selects - instant filtering
        if (themeSelect) {
            themeSelect.addEventListener('change', handleFilterChange);
        }
        
        if (statusSelect) {
            statusSelect.addEventListener('change', handleFilterChange);
        }
        
        // Reset button
        if (resetBtn) {
            resetBtn.addEventListener('click', handleReset);
        }
        
        // Clear search functionality
        if (clearBtn && searchInput) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                handleSearchInput();
                searchInput.focus();
            });
        }
        
        // Filter toggle functionality
        if (filterToggle && filterToggleContainer) {
            filterToggle.addEventListener('click', function() {
                filterToggleContainer.classList.toggle('active');
            });
        }
        
        // Enhanced placeholder animation
        setupPlaceholderAnimation();
    }
    
    function handleSearchInput() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500); // Increased to 500ms for better UX
        
        // Show search indicator
        showSearchIndicator();
    }
    
    function handleSearchKeydown(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch();
        }
        
        if (e.key === 'Escape') {
            searchInput.value = '';
            performSearch();
        }
    }
    
    function handlePillClick(e) {
        e.preventDefault();
        const pill = e.currentTarget;
        const status = pill.getAttribute('data-status');
        
        // Update active pill
        filterPills.forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        
        // Update status select
        if (statusSelect) {
            statusSelect.value = status;
        }
        
        // Perform search immediately
        performSearchImmediate();
    }
    
    function handleFilterChange() {
        // Sync status select with pills
        if (statusSelect) {
            const selectedStatus = statusSelect.value;
            filterPills.forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-status') === selectedStatus);
            });
        }
        
        performSearchImmediate();
    }
    
    function handleReset() {
        // Reset all inputs
        if (searchInput) searchInput.value = '';
        if (themeSelect) themeSelect.value = '';
        if (statusSelect) statusSelect.value = '';
        
        // Reset pills
        filterPills.forEach(pill => {
            pill.classList.toggle('active', pill.getAttribute('data-status') === '');
        });
        
        // Redirect to clean URL
        window.location.href = '/admin/ideas';
    }
    
    function showSearchIndicator() {
        if (searchIndicator) {
            const indicatorText = searchIndicator.querySelector('.indicator-text');
            if (indicatorText) {
                indicatorText.textContent = 'Recherche en cours...';
            }
            searchIndicator.classList.add('active');
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                hideSearchIndicator();
            }, 3000);
        }
    }
    
    function hideSearchIndicator() {
        if (searchIndicator) {
            const indicatorText = searchIndicator.querySelector('.indicator-text');
            if (indicatorText) {
                indicatorText.textContent = 'Recherche automatique...';
            }
            searchIndicator.classList.remove('active');
        }
    }
    
    function performSearch() {
        showSearchIndicator();
        
        // Add a small delay to show the indicator
        setTimeout(() => {
            performSearchImmediate();
        }, 100);
    }
    
    function performSearchImmediate() {
        // Build URL with current form values
        const url = new URL(window.location.origin + '/admin/ideas');
        
        // Add search parameter
        if (searchInput && searchInput.value.trim()) {
            url.searchParams.set('search', searchInput.value.trim());
        }
        
        // Add theme parameter
        if (themeSelect && themeSelect.value) {
            url.searchParams.set('theme', themeSelect.value);
        }
        
        // Add status parameter
        if (statusSelect && statusSelect.value) {
            url.searchParams.set('status', statusSelect.value);
        }
        
        // Navigate to the new URL
        window.location.href = url.toString();
    }
    
    function setupPlaceholderAnimation() {
        if (!searchInput) return;
        
        const originalPlaceholder = searchInput.placeholder;
        const placeholders = [
            'Rechercher par titre...',
            'Rechercher par description...',
            'Rechercher par auteur...',
            'Trouver une idée innovante...',
            'Explorez les propositions...',
            originalPlaceholder
        ];
        let currentIndex = 0;
        
        setInterval(() => {
            if (searchInput === document.activeElement) return;
            
            currentIndex = (currentIndex + 1) % placeholders.length;
            
            // Animate placeholder change
            searchInput.style.transition = 'opacity 0.3s ease';
            searchInput.style.opacity = '0.7';
            
            setTimeout(() => {
                searchInput.placeholder = placeholders[currentIndex];
                searchInput.style.opacity = '1';
            }, 150);
        }, 4000);
    }
    
    // Add CSS for loading states
    const style = document.createElement('style');
    style.textContent = `
        .search-indicator.active {
            opacity: 1;
        }
        
        .filter-pill.loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .filter-pill.loading .pill-count {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        @media (max-width: 768px) {
            .status-filter-pills {
                flex-wrap: wrap;
                gap: 0.25rem;
            }
            
            .filter-pill {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }
            
            .pill-text {
                display: none;
            }
            
            .filter-pill.active .pill-text {
                display: inline;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
</style>
