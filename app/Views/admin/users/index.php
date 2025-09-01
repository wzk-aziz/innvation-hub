<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-users"></i>
            Gestion des Utilisateurs
        </h1>
        <div class="page-actions">
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="icon-plus"></i>
                Nouvel Utilisateur
            </a>
        </div>
    </div>
</div>

<!-- Modern Search and Filters -->
<div class="modern-search-container">
    <form method="GET" action="/admin/users" class="modern-search-form">
        <!-- Main Search Bar -->
        <div class="search-bar-wrapper">
            <div class="search-input-container">
                <i class="search-icon"></i>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Rechercher par nom, prénom ou email..."
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
                        <label for="role">Rôle:</label>
                        <select id="role" name="role" class="modern-select">
                            <option value="">Tous les rôles</option>
                            <?php foreach (($roles ?? []) as $roleOption): ?>
                                <option value="<?= htmlspecialchars($roleOption) ?>" 
                                        <?= ($role ?? '') === $roleOption ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($roleOption) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="search-icon"></i>
                        Rechercher
                    </button>
                    <a href="/admin/users" class="btn btn-outline">
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
        <span class="stat-value"><?= $totalUsers ?? 0 ?></span>
        <span class="stat-label">Total Utilisateurs</span>
    </div>
    <div class="stat-item">
        <span class="stat-value"><?= count($users ?? []) ?></span>
        <span class="stat-label">Affichés</span>
    </div>
</div>

<!-- Users Table -->
<div class="table-card">
    <div class="table-header">
        <h3>Liste des Utilisateurs</h3>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date de Création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users ?? [])): ?>
                    <tr>
                        <td colspan="6" class="text-center no-data">
                            <i class="icon-info"></i>
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="user-id"><?= $user['id'] ?></td>
                            <td class="user-name">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                </div>
                                <div class="user-details">
                                    <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                </div>
                            </td>
                            <td class="user-email"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="user-role">
                                <span class="role-badge role-<?= strtolower($user['role']) ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td class="user-date">
                                <time datetime="<?= $user['created_at'] ?>">
                                    <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                                </time>
                            </td>
                            <td class="user-actions">
                                <div class="action-buttons">
                                    <a href="/admin/users/<?= $user['id'] ?>" 
                                       class="btn btn-sm btn-outline" 
                                       title="Voir les détails">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <a href="/admin/users/<?= $user['id'] ?>/edit" 
                                       class="btn btn-sm btn-primary" 
                                       title="Modifier">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <?php if ($user['id'] !== \App\Core\Auth::user()['id']): ?>
                                        <form method="POST" 
                                              action="/admin/users/<?= $user['id'] ?>/delete" 
                                              class="delete-form"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <?= \App\Core\Auth::csrfField() ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Supprimer">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (($totalPages ?? 0) > 1): ?>
    <div class="pagination-wrapper">
        <nav class="pagination">
            <?php if (($currentPage ?? 1) > 1): ?>
                <a href="?page=<?= ($currentPage ?? 1) - 1 ?>&search=<?= urlencode($search ?? '') ?>&role=<?= urlencode($role ?? '') ?>" 
                   class="page-link">
                    <i class="icon-arrow-left"></i>
                    Précédent
                </a>
            <?php endif; ?>
            
            <?php for ($i = max(1, ($currentPage ?? 1) - 2); $i <= min(($totalPages ?? 1), ($currentPage ?? 1) + 2); $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&role=<?= urlencode($role ?? '') ?>" 
                   class="page-link <?= $i === ($currentPage ?? 1) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                <a href="?page=<?= ($currentPage ?? 1) + 1 ?>&search=<?= urlencode($search ?? '') ?>&role=<?= urlencode($role ?? '') ?>" 
                   class="page-link">
                    Suivant
                    <i class="icon-arrow-right"></i>
                </a>
            <?php endif; ?>
        </nav>
        
        <div class="pagination-info">
            Page <?= $currentPage ?? 1 ?> sur <?= $totalPages ?? 1 ?> 
            (<?= $totalUsers ?? 0 ?> utilisateur<?= ($totalUsers ?? 0) > 1 ? 's' : '' ?> au total)
        </div>
    </div>
<?php endif; ?>

<style>
.page-header {
    background: white;
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    color: #1e293b;
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-actions {
    display: flex;
    gap: 1rem;
}

.filters-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.filters-form {
    display: flex;
    gap: 1.5rem;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 200px;
}

.filter-group label {
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

.stats-summary {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    min-width: 120px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #3b82f6;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.table-header h3 {
    margin: 0;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f9fafb;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e5e7eb;
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}

.data-table tr:hover {
    background: #f9fafb;
}

.user-name {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.role-admin {
    background: #fef3c7;
    color: #92400e;
}

.role-salarie {
    background: #dbeafe;
    color: #1d4ed8;
}

.role-evaluateur {
    background: #d1fae5;
    color: #065f46;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .delete-form {
    display: inline;
}

.no-data {
    padding: 3rem !important;
    color: #6b7280;
    font-style: italic;
}

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.page-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    text-decoration: none;
    color: #374151;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.page-link:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.page-link.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.pagination-info {
    color: #6b7280;
    font-size: 0.875rem;
}

/* Icons */
.icon-plus::before { content: "➕"; }
.icon-search::before { content: "🔍"; }
.icon-refresh::before { content: "🔄"; }
.icon-eye::before { content: "👁️"; }
.icon-edit::before { content: "✏️"; }
.icon-trash::before { content: "🗑️"; }
.icon-arrow-left::before { content: "←"; }
.icon-arrow-right::before { content: "→"; }
.icon-info::before { content: "ℹ️"; }

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
    
    .page-header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .filters-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        min-width: auto;
    }
    
    .stats-summary {
        flex-wrap: wrap;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
    }
    
    .data-table {
        font-size: 0.875rem;
    }
    
    .user-name {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Auto-search on input (debounced)
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Optional: Auto-submit after typing stops
                // this.form.submit();
            }, 500);
        });
    }
    
    // Enhanced placeholder animation
    if (searchInput) {
        const originalPlaceholder = searchInput.placeholder;
        const placeholders = [
            'Rechercher par nom...',
            'Rechercher par prénom...',
            'Rechercher par email...',
            originalPlaceholder
        ];
        let currentIndex = 0;
        
        setInterval(() => {
            if (searchInput === document.activeElement) return;
            currentIndex = (currentIndex + 1) % placeholders.length;
            searchInput.placeholder = placeholders[currentIndex];
        }, 3000);
    }
});
</script>
