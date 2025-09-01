<div class="modern-best-ideas-page">
    <!-- Modern Header Section -->
    <div class="modern-header-section">
        <div class="container-fluid">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="modern-breadcrumb">
                <div class="breadcrumb-container">
                    <a href="/evaluateur/dashboard" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <span class="breadcrumb-separator">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                    <span class="breadcrumb-current">
                        <i class="fas fa-trophy"></i>
                        <span>Meilleures Idées</span>
                    </span>
                </div>
            </nav>

            <!-- Header Content -->
            <div class="modern-header-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="modern-page-title">
                            <div class="title-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="title-content">
                                <h1>Suivi des meilleures idées</h1>
                                <p>Découvrez et suivez l'évolution des idées les mieux notées</p>
                                <div class="stats-summary">
                                    <span class="stat-item">
                                        <i class="fas fa-star text-warning"></i>
                                        <?= count($bestIdeas ?? []) ?> idées sélectionnées
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-chart-line text-success"></i>
                                        Classement actualisé
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="modern-header-actions">
                            <button onclick="exportData()" class="modern-action-btn primary">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>
                            </button>
                            <button onclick="toggleView()" class="modern-action-btn secondary">
                                <i class="fas fa-th-large"></i>
                                <span>Changer vue</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Modern Filters Section -->
        <div class="modern-filters-section">
            <div class="modern-filters-card">
                <div class="filters-header">
                    <h3>
                        <i class="fas fa-filter"></i>
                        Filtres et recherche
                    </h3>
                    <p>Personnalisez votre recherche des meilleures idées</p>
                </div>
                
                <form method="GET" class="modern-filters-form">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="period" class="filter-label">
                                <i class="fas fa-calendar-alt"></i>
                                Période
                            </label>
                            <select name="period" id="period" class="modern-select">
                                <option value="all" <?= ($_GET['period'] ?? 'all') == 'all' ? 'selected' : '' ?>>
                                    Toute période
                                </option>
                                <option value="week" <?= ($_GET['period'] ?? '') == 'week' ? 'selected' : '' ?>>
                                    Cette semaine
                                </option>
                                <option value="month" <?= ($_GET['period'] ?? '') == 'month' ? 'selected' : '' ?>>
                                    Ce mois
                                </option>
                                <option value="quarter" <?= ($_GET['period'] ?? '') == 'quarter' ? 'selected' : '' ?>>
                                    Ce trimestre
                                </option>
                                <option value="year" <?= ($_GET['period'] ?? '') == 'year' ? 'selected' : '' ?>>
                                    Cette année
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="theme_id" class="filter-label">
                                <i class="fas fa-tags"></i>
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
                            <label for="min_score" class="filter-label">
                                <i class="fas fa-star"></i>
                                Note minimum
                            </label>
                            <select name="min_score" id="min_score" class="modern-select">
                                <option value="3" <?= ($_GET['min_score'] ?? '3') == '3' ? 'selected' : '' ?>>
                                    3+ étoiles
                                </option>
                                <option value="3.5" <?= ($_GET['min_score'] ?? '') == '3.5' ? 'selected' : '' ?>>
                                    3.5+ étoiles
                                </option>
                                <option value="4" <?= ($_GET['min_score'] ?? '') == '4' ? 'selected' : '' ?>>
                                    4+ étoiles
                                </option>
                                <option value="4.5" <?= ($_GET['min_score'] ?? '') == '4.5' ? 'selected' : '' ?>>
                                    4.5+ étoiles
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sort" class="filter-label">
                                <i class="fas fa-sort"></i>
                                Trier par
                            </label>
                            <select name="sort" id="sort" class="modern-select">
                                <option value="score" <?= ($_GET['sort'] ?? 'score') == 'score' ? 'selected' : '' ?>>
                                    Note moyenne
                                </option>
                                <option value="evaluations" <?= ($_GET['sort'] ?? '') == 'evaluations' ? 'selected' : '' ?>>
                                    Nombre d'évaluations
                                </option>
                                <option value="recent" <?= ($_GET['sort'] ?? '') == 'recent' ? 'selected' : '' ?>>
                                    Plus récentes
                                </option>
                                <option value="evolution" <?= ($_GET['sort'] ?? '') == 'evolution' ? 'selected' : '' ?>>
                                    Évolution positive
                                </option>
                            </select>
                        </div>

                        <div class="filter-group search-group">
                            <label for="search" class="filter-label">
                                <i class="fas fa-search"></i>
                                Recherche
                            </label>
                            <div class="search-input-wrapper">
                                <input type="text" name="search" id="search" class="modern-search-input" 
                                       placeholder="Titre, description, auteur..." 
                                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button type="submit" class="search-submit-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="filters-actions">
                        <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['period']) || ($_GET['min_score'] ?? '3') != '3' || ($_GET['sort'] ?? 'score') != 'score'): ?>
                            <a href="/evaluateur/best-ideas" class="modern-action-btn outline">
                                <i class="fas fa-times"></i>
                                <span>Réinitialiser</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modern Results Section -->
        <div class="modern-results-section">
            <div class="modern-results-header">
                <div class="results-title">
                    <h3>
                        <i class="fas fa-trophy"></i>
                        Meilleures idées
                        <span class="results-count"><?= count($bestIdeas) ?> résultats</span>
                    </h3>
                    <p>Classement basé sur les évaluations et l'engagement</p>
                </div>
                <div class="results-actions">
                    <button type="button" class="modern-action-btn outline" onclick="exportData()">
                        <i class="fas fa-download"></i>
                        <span>Exporter</span>
                    </button>
                    <button type="button" class="modern-action-btn secondary" onclick="toggleView()">
                        <i class="fas fa-th-list"></i>
                        <span>Vue</span>
                    </button>
                </div>
            </div>

            <div class="modern-results-content">
                <?php if (!empty($bestIdeas)): ?>
                    <!-- Modern Card View -->
                    <div id="card-view" class="modern-ideas-grid">
                        <?php foreach ($bestIdeas as $index => $idea): ?>
                            <div class="modern-idea-card">
                                <!-- Ranking Badge -->
                                <div class="ranking-badge ranking-<?= $index + 1 ?>">
                                    <span class="rank-number">#<?= $index + 1 ?></span>
                                    <?php if ($index < 3): ?>
                                        <i class="fas fa-trophy rank-icon"></i>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Excellence Badge -->
                                <?php if ($idea['avg_score'] >= 4.5): ?>
                                    <div class="excellence-badge excellence">
                                        <i class="fas fa-gem"></i>
                                        <span>Excellence</span>
                                    </div>
                                <?php elseif ($idea['avg_score'] >= 4): ?>
                                    <div class="excellence-badge top">
                                        <i class="fas fa-star"></i>
                                        <span>Top idée</span>
                                    </div>
                                <?php endif; ?>

                                <div class="card-header">
                                    <h4 class="idea-title" title="<?= htmlspecialchars($idea['title']) ?>">
                                        <?= htmlspecialchars(substr($idea['title'], 0, 60)) ?><?= strlen($idea['title']) > 60 ? '...' : '' ?>
                                    </h4>
                                    <div class="rating-section">
                                        <div class="star-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= round($idea['avg_score']) ? 'active' : '' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="rating-info">
                                            <span class="score-badge"><?= number_format($idea['avg_score'], 1) ?></span>
                                            <span class="eval-count"><?= $idea['evaluation_count'] ?> évaluations</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-content">
                                    <p class="idea-description">
                                        <?= htmlspecialchars(substr($idea['description'], 0, 120)) ?><?= strlen($idea['description']) > 120 ? '...' : '' ?>
                                    </p>
                                    
                                    <div class="idea-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-tag"></i>
                                            <span><?= htmlspecialchars($idea['theme_name']) ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-user"></i>
                                            <span><?= htmlspecialchars($idea['author_first_name'] . ' ' . $idea['author_last_name']) ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?= date('d/m/Y', strtotime($idea['created_at'])) ?></span>
                                        </div>
                                    </div>

                                    <!-- Evolution Indicator -->
                                    <?php if (isset($idea['score_evolution'])): ?>
                                        <div class="evolution-indicator">
                                            <?php if ($idea['score_evolution'] > 0): ?>
                                                <div class="evolution positive">
                                                    <i class="fas fa-arrow-up"></i>
                                                    <span>+<?= number_format($idea['score_evolution'], 1) ?></span>
                                                    <small>cette semaine</small>
                                                </div>
                                            <?php elseif ($idea['score_evolution'] < 0): ?>
                                                <div class="evolution negative">
                                                    <i class="fas fa-arrow-down"></i>
                                                    <span><?= number_format($idea['score_evolution'], 1) ?></span>
                                                    <small>cette semaine</small>
                                                </div>
                                            <?php else: ?>
                                                <div class="evolution stable">
                                                    <i class="fas fa-minus"></i>
                                                    <span>Stable</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Status Badge -->
                                    <div class="status-badge status-<?= $idea['status'] ?>">
                                        <i class="fas fa-<?= $idea['status'] == 'accepted' ? 'check' : ($idea['status'] == 'rejected' ? 'times' : 'clock') ?>"></i>
                                        <span><?= match($idea['status']) {
                                            'accepted' => 'Acceptée',
                                            'rejected' => 'Rejetée',
                                            default => 'En cours'
                                        } ?></span>
                                    </div>
                                </div>
                                
                                <div class="card-actions">
                                    <a href="/evaluateur/review/<?= $idea['id'] ?>" class="primary-action">
                                        <i class="fas fa-eye"></i>
                                        <span>Voir détails</span>
                                    </a>
                                    <button type="button" class="secondary-action" onclick="trackIdea(<?= $idea['id'] ?>)">
                                        <i class="fas fa-bookmark"></i>
                                        <span>Suivre</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Modern Table View (hidden by default) -->
                    <div id="table-view" class="modern-table-view d-none">
                        <div class="modern-table-container">
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th class="rank-column">
                                                <i class="fas fa-trophy"></i>
                                                Rang
                                            </th>
                                            <th class="idea-column">
                                                <i class="fas fa-lightbulb"></i>
                                                Idée
                                            </th>
                                            <th class="theme-column">
                                                <i class="fas fa-tag"></i>
                                                Thème
                                            </th>
                                            <th class="author-column">
                                                <i class="fas fa-user"></i>
                                                Auteur
                                            </th>
                                            <th class="score-column">
                                                <i class="fas fa-star"></i>
                                                Note
                                            </th>
                                            <th class="evaluations-column">
                                                <i class="fas fa-chart-bar"></i>
                                                Évaluations
                                            </th>
                                            <th class="evolution-column">
                                                <i class="fas fa-chart-line"></i>
                                                Évolution
                                            </th>
                                            <th class="status-column">
                                                <i class="fas fa-flag"></i>
                                                Statut
                                            </th>
                                            <th class="actions-column">
                                                <i class="fas fa-cog"></i>
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bestIdeas as $index => $idea): ?>
                                            <tr class="table-row" data-ranking="<?= $index + 1 ?>">
                                                <td class="rank-cell">
                                                    <div class="rank-display rank-<?= $index + 1 ?>">
                                                        <span class="rank-number">#<?= $index + 1 ?></span>
                                                        <?php if ($index < 3): ?>
                                                            <i class="fas fa-trophy rank-trophy"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="idea-cell">
                                                    <div class="idea-info">
                                                        <h5 class="idea-title">
                                                            <?= htmlspecialchars($idea['title']) ?>
                                                            <?php if ($idea['avg_score'] >= 4.5): ?>
                                                                <span class="excellence-tag excellence">Excellence</span>
                                                            <?php elseif ($idea['avg_score'] >= 4): ?>
                                                                <span class="excellence-tag top">Top</span>
                                                            <?php endif; ?>
                                                        </h5>
                                                        <p class="idea-excerpt">
                                                            <?= htmlspecialchars(substr($idea['description'], 0, 90)) ?>...
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="theme-cell">
                                                    <span class="theme-tag">
                                                        <?= htmlspecialchars($idea['theme_name']) ?>
                                                    </span>
                                                </td>
                                                <td class="author-cell">
                                                    <div class="author-info">
                                                        <span class="author-name">
                                                            <?= htmlspecialchars($idea['author_first_name'] . ' ' . $idea['author_last_name']) ?>
                                                        </span>
                                                        <small class="submission-date">
                                                            <?= date('d/m/Y', strtotime($idea['created_at'])) ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="score-cell">
                                                    <div class="rating-display">
                                                        <div class="star-rating small">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?= $i <= round($idea['avg_score']) ? 'active' : '' ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span class="score-value"><?= number_format($idea['avg_score'], 1) ?></span>
                                                    </div>
                                                </td>
                                                <td class="evaluations-cell">
                                                    <span class="evaluation-count"><?= $idea['evaluation_count'] ?></span>
                                                </td>
                                                <td class="evolution-cell">
                                                    <?php if (isset($idea['score_evolution'])): ?>
                                                        <?php if ($idea['score_evolution'] > 0): ?>
                                                            <div class="evolution-indicator positive">
                                                                <i class="fas fa-arrow-up"></i>
                                                                <span>+<?= number_format($idea['score_evolution'], 1) ?></span>
                                                            </div>
                                                        <?php elseif ($idea['score_evolution'] < 0): ?>
                                                            <div class="evolution-indicator negative">
                                                                <i class="fas fa-arrow-down"></i>
                                                                <span><?= number_format($idea['score_evolution'], 1) ?></span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="evolution-indicator stable">
                                                                <i class="fas fa-minus"></i>
                                                                <span>Stable</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="status-cell">
                                                    <span class="status-indicator status-<?= $idea['status'] ?>">
                                                        <i class="fas fa-<?= $idea['status'] == 'accepted' ? 'check' : ($idea['status'] == 'rejected' ? 'times' : 'clock') ?>"></i>
                                                        <?= match($idea['status']) {
                                                            'accepted' => 'Acceptée',
                                                            'rejected' => 'Rejetée',
                                                            default => 'En cours'
                                                        } ?>
                                                    </span>
                                                </td>
                                                <td class="actions-cell">
                                                    <div class="table-actions">
                                                        <a href="/evaluateur/review/<?= $idea['id'] ?>" class="action-btn primary" title="Voir détails">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="action-btn secondary" 
                                                                onclick="trackIdea(<?= $idea['id'] ?>)" title="Suivre">
                                                            <i class="fas fa-bookmark"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Modern Empty State -->
                    <div class="modern-empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Aucune idée trouvée</h3>
                        <p>
                            <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['period'])): ?>
                                Essayez de modifier vos critères de recherche pour découvrir plus d'idées.
                            <?php else: ?>
                                Il n'y a pas encore d'idées répondant aux critères minimum de qualité.
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['period'])): ?>
                            <a href="/evaluateur/best-ideas" class="modern-action-btn primary">
                                <i class="fas fa-refresh"></i>
                                <span>Réinitialiser les filtres</span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modern JavaScript -->
<script>
// View Toggle Functionality
function toggleView() {
    const cardView = document.getElementById('card-view');
    const tableView = document.getElementById('table-view');
    const button = event.target.closest('.modern-action-btn');
    
    if (cardView.classList.contains('d-none')) {
        // Switch to card view
        cardView.classList.remove('d-none');
        tableView.classList.add('d-none');
        button.querySelector('i').className = 'fas fa-th-large';
        button.querySelector('span').textContent = 'Vue cartes';
    } else {
        // Switch to table view
        cardView.classList.add('d-none');
        tableView.classList.remove('d-none');
        button.querySelector('i').className = 'fas fa-table';
        button.querySelector('span').textContent = 'Vue tableau';
    }
}

// Track Idea Functionality
function trackIdea(ideaId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch('/evaluateur/track-idea', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?? '' ?>'
        },
        body: JSON.stringify({ idea_id: ideaId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Idée ajoutée au suivi avec succès', 'success');
            button.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 2000);
        } else {
            showNotification('Erreur lors de l\'ajout au suivi', 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur de connexion', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

// Export Data Functionality
function exportData() {
    const button = event.target.closest('.modern-action-btn');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Export...</span>';
    button.disabled = true;
    
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    
    // Create download link
    const link = document.createElement('a');
    link.href = '/evaluateur/best-ideas?' + params.toString();
    link.download = 'meilleures-idees-' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
        showNotification('Export terminé', 'success');
    }, 1000);
}

// Modern Notification System
function showNotification(message, type) {
    // Remove existing notifications
    const existing = document.querySelectorAll('.modern-notification');
    existing.forEach(notification => notification.remove());
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `modern-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Toggle view with 'V' key
        if (e.key === 'v' || e.key === 'V') {
            if (!e.target.matches('input, textarea, select')) {
                toggleView();
            }
        }
        
        // Export with 'E' key
        if (e.key === 'e' || e.key === 'E') {
            if (!e.target.matches('input, textarea, select')) {
                exportData();
            }
        }
    });
});
</script>
<!-- Modern CSS Styles -->
<style>
/* Modern Best Ideas Page Styles */
.modern-best-ideas-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: #333;
}

/* Header Section */
.modern-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.modern-breadcrumb {
    margin-bottom: 1.5rem;
}

.breadcrumb-container {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    backdrop-filter: blur(10px);
    gap: 1rem;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.breadcrumb-link:hover {
    color: white;
    text-shadow: 0 0 10px rgba(255,255,255,0.5);
    transform: translateX(2px);
}

.breadcrumb-separator {
    color: rgba(255,255,255,0.6);
    font-size: 0.8rem;
}

.breadcrumb-current {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.modern-header-content {
    margin-top: 1rem;
}

.modern-page-title {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.title-icon {
    background: rgba(255,255,255,0.2);
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 8px 32px rgba(255,255,255,0.1);
}

.title-content h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.title-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0 0 1rem 0;
}

.stats-summary {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.modern-header-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    align-items: center;
}

/* Modern Action Buttons */
.modern-action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.modern-action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modern-action-btn.secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.modern-action-btn.outline {
    background: transparent;
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
}

.modern-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Filters Section */
.modern-filters-section {
    margin-bottom: 2rem;
}

.modern-filters-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
}

.filters-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 0.5rem 0;
}

.filters-header p {
    color: #666;
    margin: 0 0 1.5rem 0;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.search-group {
    grid-column: span 2;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.modern-select, .modern-search-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.modern-select:focus, .modern-search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.search-input-wrapper {
    position: relative;
    display: flex;
}

.search-submit-btn {
    position: absolute;
    right: 4px;
    top: 4px;
    bottom: 4px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-submit-btn:hover {
    background: #5a67d8;
}

.filters-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Results Section */
.modern-results-section {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.modern-results-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.results-title h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 0.5rem 0;
}

.results-count {
    background: #667eea;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-left: 1rem;
}

.results-title p {
    color: #666;
    margin: 0;
}

.results-actions {
    display: flex;
    gap: 1rem;
}

.modern-results-content {
    padding: 2rem;
}

/* Card Grid */
.modern-ideas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
}

.modern-idea-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    border: 1px solid #f1f5f9;
}

.modern-idea-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.ranking-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.ranking-1, .ranking-2, .ranking-3 {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.ranking-badge:not(.ranking-1):not(.ranking-2):not(.ranking-3) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.excellence-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.excellence-badge.excellence {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.excellence-badge.top {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.card-header {
    padding: 2rem 2rem 1rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.idea-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
    line-height: 1.4;
}

.rating-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.star-rating {
    display: flex;
    gap: 2px;
}

.star-rating i {
    font-size: 1rem;
    color: #e2e8f0;
    transition: all 0.3s ease;
}

.star-rating i.active {
    color: #fbbf24;
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.score-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
}

.eval-count {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 500;
}

.card-content {
    padding: 0 2rem 1rem 2rem;
}

.idea-description {
    color: #64748b;
    line-height: 1.6;
    margin: 0 0 1.5rem 0;
}

.idea-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #64748b;
    font-size: 0.9rem;
}

.meta-item i {
    width: 16px;
    color: #94a3b8;
}

.evolution-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.evolution.positive {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
}

.evolution.negative {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.evolution.stable {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #475569;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-badge.status-accepted {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
}

.status-badge.status-rejected {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

.status-badge.status-pending {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.card-actions {
    padding: 1rem 2rem 2rem 2rem;
    display: flex;
    gap: 1rem;
}

.primary-action, .secondary-action {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.primary-action {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.secondary-action {
    background: transparent;
    color: #667eea;
    border: 2px solid #e2e8f0;
}

.primary-action:hover, .secondary-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Table Styles */
.modern-table-view {
    padding: 0;
}

.modern-table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.modern-table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    color: #1e293b;
    font-weight: 700;
    padding: 1.5rem 1rem;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.001);
}

.modern-table td {
    padding: 1.5rem 1rem;
    vertical-align: top;
}

.rank-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
}

.rank-1, .rank-2, .rank-3 {
    color: #f59e0b;
}

.idea-info {
    max-width: 300px;
}

.idea-title {
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.idea-excerpt {
    color: #64748b;
    margin: 0;
    line-height: 1.5;
}

.excellence-tag {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.excellence-tag.excellence {
    background: #dcfce7;
    color: #166534;
}

.excellence-tag.top {
    background: #fef3c7;
    color: #92400e;
}

.theme-tag {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    color: #475569;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
}

.author-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.author-name {
    font-weight: 600;
    color: #1e293b;
}

.submission-date {
    color: #64748b;
    font-size: 0.8rem;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.star-rating.small i {
    font-size: 0.8rem;
}

.score-value {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.evaluation-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.evolution-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.85rem;
}

.evolution-indicator.positive {
    color: #166534;
}

.evolution-indicator.negative {
    color: #dc2626;
}

.evolution-indicator.stable {
    color: #64748b;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.action-btn.secondary {
    background: #f1f5f9;
    color: #64748b;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Empty State */
.modern-empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 20px;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem auto;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #94a3b8;
}

.modern-empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
}

.modern-empty-state p {
    color: #64748b;
    font-size: 1.1rem;
    margin: 0 0 2rem 0;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Notification System */
.modern-notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    z-index: 9999;
    min-width: 300px;
    animation: slideInRight 0.3s ease;
}

.modern-notification.success {
    border-left: 4px solid #10b981;
}

.modern-notification.error {
    border-left: 4px solid #ef4444;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-content i {
    font-size: 1.2rem;
}

.modern-notification.success .notification-content i {
    color: #10b981;
}

.modern-notification.error .notification-content i {
    color: #ef4444;
}

.notification-close {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.notification-close:hover {
    background: #f1f5f9;
    color: #64748b;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-ideas-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .search-group {
        grid-column: span 1;
    }
    
    .modern-header-content {
        text-align: center;
    }
    
    .modern-page-title {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-summary {
        justify-content: center;
    }
    
    .modern-header-actions {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .modern-results-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .modern-table-container {
        overflow-x: auto;
    }
    
    .modern-notification {
        left: 1rem;
        right: 1rem;
        min-width: auto;
    }
}

/* Print Styles */
@media print {
    .modern-header-section,
    .modern-filters-section,
    .card-actions,
    .table-actions,
    .modern-notification {
        display: none !important;
    }
    
    .modern-best-ideas-page {
        background: white !important;
    }
    
    .modern-idea-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #e2e8f0 !important;
    }
}
</style>
