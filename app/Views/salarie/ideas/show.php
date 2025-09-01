<div class="idea-detail-container">
    <!-- Header -->
    <div class="idea-header">
        <div class="header-nav">
            <a href="/salarie/ideas" class="back-link">
                <i class="icon-arrow-left"></i>
                Retour à mes idées
            </a>
            <div class="header-actions">
                <?php if ($idea['status'] === 'submitted'): ?>
                    <a href="/salarie/ideas/<?= $idea['id'] ?>/edit" class="btn btn-secondary">
                        <i class="icon-edit"></i>
                        Modifier
                    </a>
                <?php endif; ?>
                <button onclick="shareIdea()" class="btn btn-outline">
                    <i class="icon-share"></i>
                    Partager
                </button>
            </div>
        </div>
        
        <div class="idea-title-section">
            <h1 class="idea-title"><?= htmlspecialchars($idea['title']) ?></h1>
            <div class="idea-meta">
                <span class="status-badge status-<?= $idea['status'] ?>">
                    <?= match($idea['status']) {
                        'submitted' => 'Soumise',
                        'under_review' => 'En révision',
                        'accepted' => 'Acceptée',
                        'rejected' => 'Rejetée',
                        default => ucfirst($idea['status'])
                    } ?>
                </span>
                <div class="meta-item">
                    <i class="icon-tag"></i>
                    <span><?= htmlspecialchars($idea['theme_name'] ?? 'Thème non défini') ?></span>
                </div>
                <div class="meta-item">
                    <i class="icon-calendar"></i>
                    <span>Soumise le <?= date('d/m/Y à H:i', strtotime($idea['created_at'])) ?></span>
                </div>
                <?php if (!empty($idea['updated_at']) && $idea['updated_at'] !== $idea['created_at']): ?>
                    <div class="meta-item">
                        <i class="icon-clock"></i>
                        <span>Modifiée le <?= date('d/m/Y à H:i', strtotime($idea['updated_at'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="idea-content">
        <!-- Main Content -->
        <div class="content-main">
            <!-- Description -->
            <div class="content-section">
                <h3 class="section-title">
                    <i class="icon-file-text"></i>
                    Description de l'idée
                </h3>
                <div class="section-content">
                    <?= nl2br(htmlspecialchars($idea['description'])) ?>
                </div>
            </div>

            <!-- Expected Impact -->
            <?php if (!empty($idea['expected_impact'])): ?>
                <div class="content-section">
                    <h3 class="section-title">
                        <i class="icon-target"></i>
                        Impact attendu
                    </h3>
                    <div class="section-content">
                        <?= nl2br(htmlspecialchars($idea['expected_impact'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Required Resources -->
            <?php if (!empty($idea['required_resources'])): ?>
                <div class="content-section">
                    <h3 class="section-title">
                        <i class="icon-tool"></i>
                        Ressources nécessaires
                    </h3>
                    <div class="section-content">
                        <?= nl2br(htmlspecialchars($idea['required_resources'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Evaluations -->
            <?php if (!empty($evaluations)): ?>
                <div class="content-section">
                    <h3 class="section-title">
                        <i class="icon-star"></i>
                        Évaluations
                        <span class="average-score">
                            Moyenne: <?= $averageScore !== null ? number_format($averageScore, 1) . '/5' : 'N/A' ?>
                        </span>
                    </h3>
                    <div class="evaluations-list">
                        <?php foreach ($evaluations as $evaluation): ?>
                            <div class="evaluation-card">
                                <div class="evaluation-header">
                                    <div class="evaluator-info">
                                        <strong><?= htmlspecialchars($evaluation['evaluator_first_name'] . ' ' . $evaluation['evaluator_last_name']) ?></strong>
                                        <span class="evaluator-role">Évaluateur</span>
                                    </div>
                                    <div class="evaluation-score">
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="icon-star <?= $i <= $evaluation['score'] ? 'filled' : 'empty' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="score-text"><?= $evaluation['score'] ?>/5</span>
                                    </div>
                                </div>
                                
                                <div class="evaluation-date">
                                    Évaluée le <?= date('d/m/Y à H:i', strtotime($evaluation['created_at'])) ?>
                                </div>
                                
                                <?php if (!empty($evaluation['comments'])): ?>
                                    <div class="evaluation-comments">
                                        <h5>Commentaires:</h5>
                                        <p><?= nl2br(htmlspecialchars($evaluation['comments'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Admin Feedback -->
            <?php if (!empty($feedback)): ?>
                <div class="content-section">
                    <h3 class="section-title">
                        <i class="icon-message-circle"></i>
                        Retours de l'administration
                    </h3>
                    <div class="feedback-list">
                        <?php foreach ($feedback as $feedbackItem): ?>
                            <div class="feedback-card">
                                <div class="feedback-header">
                                    <div class="feedback-author">
                                        <strong><?= htmlspecialchars($feedbackItem['first_name'] . ' ' . $feedbackItem['last_name']) ?></strong>
                                        <span class="feedback-role">Administrateur</span>
                                    </div>
                                    <div class="feedback-date">
                                        <?= date('d/m/Y à H:i', strtotime($feedbackItem['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="feedback-content">
                                    <?= nl2br(htmlspecialchars($feedbackItem['message'] ?? '')) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="content-sidebar">
            <!-- Status Progress -->
            <div class="sidebar-card">
                <h4 class="card-title">
                    <i class="icon-activity"></i>
                    Progression
                </h4>
                <div class="progress-timeline">
                    <div class="timeline-item <?= in_array($idea['status'], ['submitted', 'under_review', 'accepted', 'rejected']) ? 'completed' : '' ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Soumise</strong>
                            <span><?= date('d/m/Y', strtotime($idea['created_at'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="timeline-item <?= in_array($idea['status'], ['under_review', 'accepted', 'rejected']) ? 'completed' : '' ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>En révision</strong>
                            <?php if ($idea['status'] === 'under_review'): ?>
                                <span>En cours...</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($evaluations)): ?>
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Évaluée</strong>
                                <span><?= count($evaluations) ?> évaluation(s)</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($idea['status'] === 'accepted'): ?>
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Acceptée</strong>
                                <span>Félicitations !</span>
                            </div>
                        </div>
                    <?php elseif ($idea['status'] === 'rejected'): ?>
                        <div class="timeline-item rejected">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Non retenue</strong>
                                <span>Voir les commentaires</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics -->
            <div class="sidebar-card">
                <h4 class="card-title">
                    <i class="icon-bar-chart"></i>
                    Statistiques
                </h4>
                <div class="stats-list">
                    <div class="stat-item">
                        <span class="stat-label">Évaluations</span>
                        <span class="stat-value"><?= count($evaluations) ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Score moyen</span>
                        <span class="stat-value"><?= isset($averageScore) ? number_format($averageScore, 1) . '/5' : 'N/A' ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Retours admin</span>
                        <span class="stat-value"><?= count($feedback ?? []) ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Thématique</span>
                        <span class="stat-value"><?= htmlspecialchars($idea['theme_name'] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="sidebar-card">
                <h4 class="card-title">
                    <i class="icon-settings"></i>
                    Actions
                </h4>
                <div class="action-buttons">
                    <?php if ($idea['status'] === 'submitted'): ?>
                        <a href="/salarie/ideas/<?= $idea['id'] ?>/edit" class="action-btn edit">
                            <i class="icon-edit"></i>
                            Modifier l'idée
                        </a>
                    <?php endif; ?>
                    
                    <button onclick="downloadPDF()" class="action-btn download">
                        <i class="icon-download"></i>
                        Télécharger PDF
                    </button>
                    
                    <button onclick="shareIdea()" class="action-btn share">
                        <i class="icon-share"></i>
                        Partager
                    </button>
                    
                    <?php if ($idea['status'] === 'submitted'): ?>
                        <button onclick="deleteIdea()" class="action-btn delete">
                            <i class="icon-trash"></i>
                            Supprimer
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Main Container */
.idea-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Header */
.idea-header {
    margin-bottom: 3rem;
}

.header-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.back-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #667eea;
    text-decoration: none;
}

.back-link:hover {
    color: #5a67d8;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.idea-title-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.idea-title {
    font-size: 2.5rem;
    color: #2d3748;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.idea-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #718096;
    font-size: 0.9rem;
}

/* Content Layout */
.idea-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

/* Main Content */
.content-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.content-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.content-section:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #2d3748;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.average-score {
    margin-left: auto;
    font-size: 0.9rem;
    color: #667eea;
    font-weight: normal;
}

.section-content {
    color: #4a5568;
    line-height: 1.6;
    font-size: 1rem;
}

/* Status Badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
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

/* Evaluations */
.evaluations-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.evaluation-card {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #667eea;
}

.evaluation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.evaluator-info strong {
    color: #2d3748;
}

.evaluator-role {
    color: #718096;
    font-size: 0.8rem;
    margin-left: 0.5rem;
}

.evaluation-score {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars {
    display: flex;
    gap: 0.1rem;
}

.stars .icon-star.filled {
    color: #f6e05e;
}

.stars .icon-star.empty {
    color: #e2e8f0;
}

.score-text {
    font-weight: 600;
    color: #2d3748;
}

.evaluation-date {
    color: #718096;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}

.evaluation-comments h5 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.evaluation-comments p {
    color: #4a5568;
    line-height: 1.5;
    margin: 0;
}

/* Feedback */
.feedback-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.feedback-card {
    background: #f0fff4;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #38a169;
}

.feedback-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.feedback-author strong {
    color: #2d3748;
}

.feedback-role {
    color: #718096;
    font-size: 0.8rem;
    margin-left: 0.5rem;
}

.feedback-date {
    color: #718096;
    font-size: 0.8rem;
}

.feedback-content {
    color: #2d3748;
    line-height: 1.5;
}

/* Sidebar */
.content-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.sidebar-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #2d3748;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

/* Progress Timeline */
.progress-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.timeline-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: "";
    position: absolute;
    left: 10px;
    top: 25px;
    width: 2px;
    height: 20px;
    background: #e2e8f0;
}

.timeline-item.completed::after {
    background: #38a169;
}

.timeline-marker {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e2e8f0;
    border: 3px solid white;
    flex-shrink: 0;
}

.timeline-item.completed .timeline-marker {
    background: #38a169;
}

.timeline-item.rejected .timeline-marker {
    background: #e53e3e;
}

.timeline-content {
    flex: 1;
}

.timeline-content strong {
    display: block;
    color: #2d3748;
    font-size: 0.9rem;
}

.timeline-content span {
    color: #718096;
    font-size: 0.8rem;
}

/* Statistics */
.stats-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: #718096;
    font-size: 0.9rem;
}

.stat-value {
    color: #2d3748;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    text-align: left;
}

.action-btn.edit {
    background: #edf2f7;
    color: #4a5568;
}

.action-btn.edit:hover {
    background: #e2e8f0;
}

.action-btn.download {
    background: #e6fffa;
    color: #319795;
}

.action-btn.download:hover {
    background: #b2f5ea;
}

.action-btn.share {
    background: #ebf8ff;
    color: #3182ce;
}

.action-btn.share:hover {
    background: #bee3f8;
}

.action-btn.delete {
    background: #fed7d7;
    color: #c53030;
}

.action-btn.delete:hover {
    background: #feb2b2;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .idea-content {
        grid-template-columns: 1fr;
    }
    
    .content-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .idea-detail-container {
        padding: 1rem;
    }
    
    .idea-title {
        font-size: 2rem;
    }
    
    .header-nav {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .evaluation-header,
    .feedback-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .idea-meta {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
function shareIdea() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($idea['title']) ?>',
            text: 'Découvrez cette idée innovation : <?= htmlspecialchars(substr($idea['description'], 0, 100)) ?>...',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Lien copié dans le presse-papiers', 'success');
        });
    }
}

function downloadPDF() {
    // Redirect to PDF download endpoint
    window.location.href = `/salarie/ideas/<?= $idea['id'] ?>/pdf`;
}

function deleteIdea() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette idée ? Cette action est irréversible.')) {
        fetch(`/salarie/ideas/<?= $idea['id'] ?>`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': '<?= csrf_token() ?>',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Idée supprimée avec succès', 'success');
                setTimeout(() => {
                    window.location.href = '/salarie/ideas';
                }, 1000);
            } else {
                showNotification('Erreur lors de la suppression', 'error');
            }
        })
        .catch(() => {
            showNotification('Erreur de connexion', 'error');
        });
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: ${type === 'success' ? '#38a169' : type === 'error' ? '#e53e3e' : '#667eea'};
        color: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
