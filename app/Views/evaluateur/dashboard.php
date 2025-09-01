<div class="modern-dashboard">
    <div class="container-fluid px-4 py-4">
        <!-- Modern Header Section -->
        <div class="dashboard-header mb-5">
            <div class="header-card">
                <div class="header-content">
                    <div class="welcome-text">
                        <h1 class="dashboard-title">
                            <i class="fas fa-user-check dashboard-icon"></i>
                            Tableau de bord Évaluateur
                        </h1>
                        <p class="welcome-subtitle">
                            Bienvenue, <?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>
                        </p>
                    </div>
                    <div class="header-decoration">
                        <div class="floating-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Statistics Grid -->
        <div class="stats-grid mb-5">
            <div class="stat-card stat-card-primary">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Idées à évaluer</span>
                        <span class="stat-value"><?= $stats['pending_ideas'] ?? 0 ?></span>
                    </div>
                </div>
                <div class="stat-decoration"></div>
            </div>
            
            <div class="stat-card stat-card-success">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Évaluations faites</span>
                        <span class="stat-value"><?= $stats['my_evaluations'] ?? 0 ?></span>
                    </div>
                </div>
                <div class="stat-decoration"></div>
            </div>
            
            <div class="stat-card stat-card-warning">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Note moyenne</span>
                        <span class="stat-value"><?= number_format($stats['average_score'] ?? 0, 1) ?>/5</span>
                    </div>
                </div>
                <div class="stat-decoration"></div>
            </div>
            
            <div class="stat-card stat-card-info">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Commentaires donnés</span>
                        <span class="stat-value"><?= $stats['my_feedback'] ?? 0 ?></span>
                    </div>
                </div>
                <div class="stat-decoration"></div>
            </div>
        </div>

        <!-- Modern Main Content Grid -->
        <div class="main-content-grid">
            <!-- Ideas to Review Section -->
            <div class="content-section">
                <div class="modern-card">
                    <div class="card-header-modern">
                        <div class="header-left">
                            <h3 class="section-title">
                                <i class="fas fa-lightbulb section-icon"></i>
                                Idées à évaluer
                            </h3>
                            <p class="section-subtitle">Découvrez les dernières idées soumises</p>
                        </div>
                        <div class="header-actions">
                            <a href="/evaluateur/review" class="btn-modern btn-primary">
                                <i class="fas fa-eye"></i>
                                <span>Voir toutes</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($pendingIdeas)): ?>
                            <div class="ideas-list">
                                <?php foreach (array_slice($pendingIdeas, 0, 5) as $index => $idea): ?>
                                    <div class="idea-item <?= $index === 0 ? 'featured' : '' ?>">
                                        <div class="idea-content">
                                            <div class="idea-header">
                                                <h4 class="idea-title"><?= htmlspecialchars($idea['title']) ?></h4>
                                                <span class="idea-badge"><?= htmlspecialchars($idea['theme_name'] ?? 'Aucun thème') ?></span>
                                            </div>
                                            <div class="idea-meta">
                                                <div class="author-info">
                                                    <i class="fas fa-user"></i>
                                                    <span><?= htmlspecialchars(($idea['author_first_name'] ?? '') . ' ' . ($idea['author_last_name'] ?? '')) ?: 'Auteur inconnu' ?></span>
                                                </div>
                                                <div class="date-info">
                                                    <i class="fas fa-calendar"></i>
                                                    <span><?= !empty($idea['created_at']) ? date('d/m/Y', strtotime($idea['created_at'])) : 'Date inconnue' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="idea-actions">
                                            <a href="/evaluateur/review/<?= $idea['id'] ?>" class="btn-evaluate">
                                                <i class="fas fa-star"></i>
                                                <span>Évaluer</span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h4>Excellent travail !</h4>
                                <p>Toutes les idées ont été évaluées. Revenez plus tard pour de nouvelles soumissions.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modern Sidebar -->
            <div class="sidebar-section">
                <!-- Quick Actions -->
                <div class="modern-card">
                    <div class="card-header-modern">
                        <h3 class="section-title">
                            <i class="fas fa-bolt section-icon"></i>
                            Actions rapides
                        </h3>
                        <p class="section-subtitle">Accès rapide aux fonctionnalités</p>
                    </div>
                    <div class="card-content">
                        <div class="action-grid">
                            <a href="/evaluateur/review" class="action-card action-primary">
                                <div class="action-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Évaluer des idées</span>
                                    <span class="action-desc">Examiner les nouvelles soumissions</span>
                                </div>
                            </a>
                            
                            <a href="/evaluateur/statistics" class="action-card action-info">
                                <div class="action-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Statistiques</span>
                                    <span class="action-desc">Voir les données d'évaluation</span>
                                </div>
                            </a>
                            
                            <a href="/evaluateur/my-evaluations" class="action-card action-success">
                                <div class="action-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Mes évaluations</span>
                                    <span class="action-desc">Historique de mes notes</span>
                                </div>
                            </a>
                            
                            <a href="/evaluateur/best-ideas" class="action-card action-warning">
                                <div class="action-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Meilleures idées</span>
                                    <span class="action-desc">Top des innovations</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="modern-card">
                    <div class="card-header-modern">
                        <h3 class="section-title">
                            <i class="fas fa-clock section-icon"></i>
                            Activité récente
                        </h3>
                        <p class="section-subtitle">Vos dernières évaluations</p>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($recentEvaluations)): ?>
                            <div class="activity-timeline">
                                <?php foreach (array_slice($recentEvaluations, 0, 5) as $eval): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <div class="activity-header">
                                                <h5 class="activity-title"><?= htmlspecialchars($eval['idea_title'] ?? 'Idée sans titre') ?></h5>
                                                <div class="activity-score">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= ($eval['score'] ?? 0) ? 'star-filled' : 'star-empty' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <div class="activity-meta">
                                                <span class="activity-date">
                                                    <i class="fas fa-clock"></i>
                                                    <?= !empty($eval['created_at']) ? date('d/m/Y H:i', strtotime($eval['created_at'])) : 'Date inconnue' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-mini">
                                <i class="fas fa-clock"></i>
                                <p>Aucune évaluation récente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */
.modern-dashboard {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header Section */
.dashboard-header {
    margin-bottom: 3rem;
}

.header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.welcome-text {
    color: white;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.02em;
}

.dashboard-icon {
    margin-right: 1rem;
    opacity: 0.9;
}

.welcome-subtitle {
    font-size: 1.1rem;
    opacity: 0.8;
    margin: 0;
    font-weight: 400;
}

.header-decoration {
    display: flex;
    align-items: center;
}

.floating-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.floating-icon i {
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    flex-shrink: 0;
}

.stat-card-primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card-success .stat-icon { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.stat-card-warning .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card-info .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stat-text {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.stat-decoration {
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    opacity: 0.05;
}

.stat-card-primary .stat-decoration { background: #667eea; }
.stat-card-success .stat-decoration { background: #11998e; }
.stat-card-warning .stat-decoration { background: #f093fb; }
.stat-card-info .stat-decoration { background: #4facfe; }

/* Main Content Grid */
.main-content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2.5rem;
    align-items: start;
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
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.card-content {
    padding: 0 2rem 2rem 2rem;
}

/* Ideas List */
.ideas-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.idea-item {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.idea-item:hover {
    background: #f1f5f9;
    border-color: #e2e8f0;
}

.idea-item.featured {
    background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
    border-color: #c7d2fe;
}

.idea-content {
    flex: 1;
}

.idea-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.idea-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.idea-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.idea-meta {
    display: flex;
    gap: 1.5rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.author-info, .date-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.idea-actions {
    flex-shrink: 0;
}

.btn-evaluate {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.btn-evaluate:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(240, 147, 251, 0.3);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6b7280;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem auto;
}

.empty-icon i {
    font-size: 2rem;
    color: white;
}

.empty-state h4 {
    color: #1f2937;
    margin-bottom: 0.5rem;
}

/* Sidebar Section */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Action Grid */
.action-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.action-card:hover {
    background: white;
    border-color: #e2e8f0;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
    flex-shrink: 0;
}

.action-primary .action-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.action-info .action-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.action-success .action-icon { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.action-warning .action-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.action-text {
    display: flex;
    flex-direction: column;
}

.action-title {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.action-desc {
    font-size: 0.8rem;
    color: #6b7280;
}

/* Activity Timeline */
.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    position: relative;
}

.timeline-dot {
    width: 12px;
    height: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 0.5rem;
    position: relative;
}

.timeline-dot::after {
    content: '';
    position: absolute;
    top: 12px;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: 40px;
    background: #e5e7eb;
}

.timeline-item:last-child .timeline-dot::after {
    display: none;
}

.timeline-content {
    flex: 1;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.activity-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    flex: 1;
    margin-right: 1rem;
}

.activity-score {
    display: flex;
    gap: 2px;
}

.star-filled {
    color: #fbbf24;
}

.star-empty {
    color: #e5e7eb;
}

.activity-meta {
    font-size: 0.8rem;
    color: #6b7280;
}

.activity-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.empty-state-mini {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state-mini i {
    font-size: 2rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .modern-dashboard {
        padding: 1rem;
    }
    
    .header-card {
        padding: 2rem 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .card-header-modern {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .idea-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .idea-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

