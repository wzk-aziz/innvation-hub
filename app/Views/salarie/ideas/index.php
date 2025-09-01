<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">
            <i class="icon-lightbulb"></i>
            Mes Idées d'Innovation
        </h1>
        <p class="hero-subtitle">
            Partagez vos idées créatives et suivez leur progression dans l'entreprise
        </p>
        <a href="/salarie/ideas/create" class="btn btn-primary btn-large">
            <i class="icon-plus"></i>
            Proposer une nouvelle idée
        </a>
    </div>
</div>

<div class="dashboard-container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="icon-lightbulb"></i>
            </div>
            <div class="stat-content">
                <h3><?= count($ideas) ?></h3>
                <p>Idées proposées</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="icon-clock"></i>
            </div>
            <div class="stat-content">
                <h3><?= count(array_filter($ideas, fn($idea) => $idea['status'] === 'submitted' || $idea['status'] === 'under_review')) ?></h3>
                <p>En cours d'évaluation</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="icon-check"></i>
            </div>
            <div class="stat-content">
                <h3><?= count(array_filter($ideas, fn($idea) => $idea['status'] === 'accepted')) ?></h3>
                <p>Idées acceptées</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="icon-star"></i>
            </div>
            <div class="stat-content">
                <h3><?= $averageScore ?? '-' ?></h3>
                <p>Score moyen</p>
            </div>
        </div>
    </div>

    <!-- Ideas List -->
    <div class="ideas-section">
        <div class="section-header">
            <h2>Vos Idées</h2>
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">Toutes</button>
                <button class="filter-tab" data-filter="submitted">Soumises</button>
                <button class="filter-tab" data-filter="under_review">En révision</button>
                <button class="filter-tab" data-filter="accepted">Acceptées</button>
                <button class="filter-tab" data-filter="rejected">Rejetées</button>
            </div>
        </div>

        <?php if (empty($ideas)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="icon-lightbulb"></i>
                </div>
                <h3>Aucune idée proposée</h3>
                <p>Vous n'avez pas encore soumis d'idées. Commencez par partager votre première innovation !</p>
                <a href="/salarie/ideas/create" class="btn btn-primary">
                    <i class="icon-plus"></i>
                    Proposer une idée
                </a>
            </div>
        <?php else: ?>
            <div class="ideas-grid" id="ideasGrid">
                <?php foreach ($ideas as $idea): ?>
                    <div class="idea-card" data-status="<?= $idea['status'] ?>">
                        <div class="idea-header">
                            <h3 class="idea-title">
                                <a href="/salarie/ideas/<?= $idea['id'] ?>">
                                    <?= htmlspecialchars($idea['title']) ?>
                                </a>
                            </h3>
                            <span class="status-badge status-<?= $idea['status'] ?>">
                                <?= match($idea['status']) {
                                    'submitted' => 'Soumise',
                                    'under_review' => 'En révision',
                                    'accepted' => 'Acceptée',
                                    'rejected' => 'Rejetée',
                                    default => ucfirst($idea['status'])
                                } ?>
                            </span>
                        </div>
                        
                        <div class="idea-meta">
                            <div class="meta-item">
                                <i class="icon-tag"></i>
                                <span><?= htmlspecialchars($idea['theme_name'] ?? 'Thème non défini') ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="icon-calendar"></i>
                                <span><?= date('d/m/Y', strtotime($idea['created_at'])) ?></span>
                            </div>
                            <?php if (isset($idea['avg_score']) && $idea['avg_score']): ?>
                                <div class="meta-item">
                                    <i class="icon-star"></i>
                                    <span><?= number_format($idea['avg_score'], 1) ?>/5</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="idea-description">
                            <?= nl2br(htmlspecialchars(substr($idea['description'], 0, 150))) ?>
                            <?php if (strlen($idea['description']) > 150): ?>
                                <span class="text-muted">...</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="idea-actions">
                            <a href="/salarie/ideas/<?= $idea['id'] ?>" class="btn btn-sm btn-outline">
                                <i class="icon-eye"></i>
                                Voir détails
                            </a>
                            
                            <?php if ($idea['status'] === 'submitted'): ?>
                                <a href="/salarie/ideas/<?= $idea['id'] ?>/edit" class="btn btn-sm btn-secondary">
                                    <i class="icon-edit"></i>
                                    Modifier
                                </a>
                            <?php endif; ?>
                            
                            <?php if (in_array($idea['status'], ['accepted', 'under_review'])): ?>
                                <span class="feedback-indicator">
                                    <i class="icon-message"></i>
                                    Retours disponibles
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
    margin-bottom: 2rem;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    padding: 0 1rem;
}

.hero-title {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    border-radius: 50px;
}

/* Dashboard Container */
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #2d3748;
}

.stat-content p {
    margin: 0;
    color: #718096;
    font-size: 0.9rem;
}

/* Ideas Section */
.ideas-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-header h2 {
    margin: 0;
    color: #2d3748;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 0.5rem 1rem;
    border: none;
    background: #f7fafc;
    color: #4a5568;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.filter-tab:hover {
    background: #edf2f7;
}

.filter-tab.active {
    background: #667eea;
    color: white;
}

/* Ideas Grid */
.ideas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

/* Idea Card */
.idea-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    background: #f7fafc;
    transition: all 0.2s ease;
}

.idea-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.idea-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.idea-title {
    margin: 0;
    flex: 1;
}

.idea-title a {
    color: #2d3748;
    text-decoration: none;
    font-weight: 600;
}

.idea-title a:hover {
    color: #667eea;
}

/* Status Badges */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

.status-submitted {
    background: #fed7d7;
    color: #c53030;
}

.status-under_review {
    background: #feebc8;
    color: #dd6b20;
}

.status-accepted {
    background: #c6f6d5;
    color: #38a169;
}

.status-rejected {
    background: #fed7d7;
    color: #e53e3e;
}

/* Meta Information */
.idea-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #718096;
}

.meta-item i {
    font-size: 0.9rem;
}

/* Description */
.idea-description {
    color: #4a5568;
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

/* Actions */
.idea-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.feedback-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #38a169;
    background: #c6f6d5;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #718096;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: #4a5568;
    margin-bottom: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .ideas-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-tabs {
        justify-content: center;
    }
    
    .idea-header {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const ideaCards = document.querySelectorAll('.idea-card');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Filter cards
            ideaCards.forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
