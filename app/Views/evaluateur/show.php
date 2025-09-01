<div class="modern-evaluation-detail">
    <div class="container-fluid px-4 py-4">
        <!-- Modern Header with Breadcrumbs -->
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
                        <li class="breadcrumb-item">
                            <a href="/evaluateur/review">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Idées à évaluer</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <i class="fas fa-star"></i>
                            <span>Évaluation</span>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="header-content">
                <div class="header-text">
                    <h1 class="page-title">
                        <i class="fas fa-star page-icon"></i>
                        Évaluation d'idée
                    </h1>
                    <p class="page-subtitle">
                        Analysez et notez cette innovation proposée
                    </p>
                </div>
                <div class="header-actions">
                    <a href="/evaluateur/review" class="btn-modern btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour à la liste</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Main Content Grid -->
        <div class="main-content-grid">
            <!-- Idea Details Section -->
            <div class="content-section">
                <!-- Idea Information Card -->
                <div class="modern-card idea-info-card">
                    <div class="card-header-modern">
                        <div class="idea-title-section">
                            <h2 class="idea-main-title"><?= htmlspecialchars($idea['title']) ?></h2>
                            <div class="idea-meta-header">
                                <div class="author-info">
                                    <i class="fas fa-user"></i>
                                    <span>Par <?= htmlspecialchars(($idea['first_name'] ?? '') . ' ' . ($idea['last_name'] ?? '')) ?></span>
                                </div>
                                <div class="date-info">
                                    <i class="fas fa-calendar"></i>
                                    <span><?= !empty($idea['created_at']) ? date('d/m/Y à H:i', strtotime($idea['created_at'])) : 'Date inconnue' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="status-section">
                            <span class="modern-status-badge status-<?= $idea['status'] == 'accepted' ? 'success' : ($idea['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                <i class="fas fa-<?= $idea['status'] == 'accepted' ? 'check-circle' : ($idea['status'] == 'rejected' ? 'times-circle' : 'clock') ?>"></i>
                                <?= match($idea['status']) {
                                    'accepted' => 'Acceptée',
                                    'rejected' => 'Rejetée',
                                    default => 'En cours d\'évaluation'
                                } ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <div class="idea-details-grid">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-tag"></i>
                                    <span>Thème</span>
                                </div>
                                <div class="detail-value">
                                    <span class="theme-tag"><?= htmlspecialchars($idea['theme_name'] ?? 'Aucun thème') ?></span>
                                </div>
                            </div>
                            
                            <div class="detail-item full-width">
                                <div class="detail-label">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Description de l'idée</span>
                                </div>
                                <div class="detail-value">
                                    <div class="description-content">
                                        <?= nl2br(htmlspecialchars($idea['description'])) ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($idea['attachments'])): ?>
                                <div class="detail-item full-width">
                                    <div class="detail-label">
                                        <i class="fas fa-paperclip"></i>
                                        <span>Pièces jointes</span>
                                    </div>
                                    <div class="detail-value">
                                        <div class="attachments-grid">
                                            <?php foreach ($idea['attachments'] as $attachment): ?>
                                                <a href="/uploads/<?= htmlspecialchars($attachment['filename']) ?>" 
                                                   class="attachment-item" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                    <span><?= htmlspecialchars($attachment['original_name']) ?></span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Current Evaluation Display -->
                <?php if (!empty($currentEvaluation)): ?>
                    <div class="modern-card current-evaluation-card">
                        <div class="card-header-modern">
                            <div class="header-left">
                                <h3 class="section-title">
                                    <i class="fas fa-star section-icon"></i>
                                    Mon évaluation
                                </h3>
                                <p class="section-subtitle">Votre note et commentaire pour cette idée</p>
                            </div>
                            <div class="evaluation-score-badge">
                                <div class="score-display">
                                    <span class="score-number"><?= $currentEvaluation['score'] ?></span>
                                    <span class="score-separator">/</span>
                                    <span class="score-max">5</span>
                                </div>
                                <div class="stars-display">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $currentEvaluation['score'] ? 'star-filled' : 'star-empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="evaluation-info-grid">
                                <div class="evaluation-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>Évaluée le <?= !empty($currentEvaluation['created_at']) ? date('d/m/Y à H:i', strtotime($currentEvaluation['created_at'])) : 'Date inconnue' ?></span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($currentEvaluation['comment'])): ?>
                                    <div class="comment-section">
                                        <h4 class="comment-title">
                                            <i class="fas fa-comment"></i>
                                            Mon commentaire
                                        </h4>
                                        <div class="comment-content">
                                            <?= nl2br(htmlspecialchars($currentEvaluation['comment'])) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="evaluation-actions">
                                <button type="button" class="btn-modern btn-warning" data-bs-toggle="collapse" data-bs-target="#editEvaluation">
                                    <i class="fas fa-edit"></i>
                                    <span>Modifier mon évaluation</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Modern Evaluation Form -->
                <div class="modern-card evaluation-form-card <?= !empty($currentEvaluation) ? 'collapse' : '' ?>" id="<?= !empty($currentEvaluation) ? 'editEvaluation' : 'newEvaluation' ?>">
                    <div class="card-header-modern">
                        <div class="header-left">
                            <h3 class="section-title">
                                <i class="fas fa-star section-icon"></i>
                                <?= !empty($currentEvaluation) ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' ?>
                            </h3>
                            <p class="section-subtitle">
                                <?= !empty($currentEvaluation) ? 'Mettez à jour votre note et commentaire' : 'Donnez votre avis sur cette innovation' ?>
                            </p>
                        </div>
                    </div>
                    <div class="card-content">
                        <?php if (isset($_SESSION['errors'])): ?>
                            <div class="error-alert">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="alert-content">
                                    <h4>Erreurs détectées</h4>
                                    <ul>
                                        <?php foreach ($_SESSION['errors'] as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php unset($_SESSION['errors']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/evaluateur/evaluate" class="evaluation-form">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="idea_id" value="<?= $idea['id'] ?>">

                            <div class="form-group">
                                <label for="score" class="form-label">
                                    <i class="fas fa-star"></i>
                                    <span>Note d'évaluation</span>
                                    <span class="required">*</span>
                                </label>
                                <div class="rating-section">
                                    <div class="rating-input-modern">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" name="score" value="<?= $i ?>" id="star<?= $i ?>" 
                                                   <?= ($currentEvaluation && $currentEvaluation['score'] == $i) ? 'checked' : '' ?>
                                                   required class="star-input">
                                            <label for="star<?= $i ?>" class="star-label-modern">
                                                <i class="fas fa-star"></i>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="rating-labels">
                                        <span class="rating-label rating-1">Très mauvaise</span>
                                        <span class="rating-label rating-2">Mauvaise</span>
                                        <span class="rating-label rating-3">Moyenne</span>
                                        <span class="rating-label rating-4">Bonne</span>
                                        <span class="rating-label rating-5">Excellente</span>
                                    </div>
                                </div>
                                <small class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Cliquez sur les étoiles pour attribuer une note de 1 à 5
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="comment" class="form-label">
                                    <i class="fas fa-comment"></i>
                                    <span>Commentaire détaillé</span>
                                </label>
                                <textarea name="comment" id="comment" rows="6" class="modern-textarea" 
                                          placeholder="Expliquez votre évaluation en détail...&#10;&#10;• Quels sont les points forts de cette idée ?&#10;• Quels sont les axes d'amélioration ?&#10;• Quelle est sa faisabilité ?&#10;• Quel est son potentiel d'innovation ?"><?= ($currentEvaluation && $currentEvaluation['comment']) ? htmlspecialchars($currentEvaluation['comment']) : '' ?></textarea>
                                <small class="form-hint">
                                    <i class="fas fa-eye"></i>
                                    Ce commentaire sera visible par l'auteur de l'idée et les autres évaluateurs
                                </small>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-modern btn-primary btn-large">
                                    <i class="fas fa-save"></i>
                                    <span><?= !empty($currentEvaluation) ? 'Mettre à jour l\'évaluation' : 'Enregistrer l\'évaluation' ?></span>
                                </button>
                                <?php if (!empty($currentEvaluation)): ?>
                                    <button type="button" class="btn-modern btn-outline" data-bs-toggle="collapse" data-bs-target="#editEvaluation">
                                        <i class="fas fa-times"></i>
                                        <span>Annuler les modifications</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modern Sidebar -->
            <div class="sidebar-section">
                <!-- Other Evaluations Card -->
                <div class="modern-card evaluations-card">
                    <div class="card-header-modern">
                        <div class="header-left">
                            <h3 class="section-title">
                                <i class="fas fa-users section-icon"></i>
                                Autres évaluations
                            </h3>
                            <p class="section-subtitle">Avis des autres évaluateurs</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($otherEvaluations)): ?>
                            <?php 
                            $totalScore = array_sum(array_column($otherEvaluations, 'score'));
                            $avgScore = $totalScore / count($otherEvaluations);
                            ?>
                            <div class="average-score-display">
                                <div class="score-circle">
                                    <span class="avg-score"><?= number_format($avgScore, 1) ?></span>
                                    <span class="score-max">/5</span>
                                </div>
                                <div class="score-info">
                                    <div class="stars-avg">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= round($avgScore) ? 'star-filled' : 'star-empty' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="evaluation-count"><?= count($otherEvaluations) ?> évaluation<?= count($otherEvaluations) > 1 ? 's' : '' ?></p>
                                </div>
                            </div>
                            
                            <div class="evaluations-list">
                                <?php foreach ($otherEvaluations as $eval): ?>
                                    <div class="evaluation-item-modern">
                                        <div class="evaluator-header">
                                            <div class="evaluator-info">
                                                <div class="evaluator-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="evaluator-details">
                                                    <h5 class="evaluator-name"><?= htmlspecialchars(($eval['evaluator_first_name'] ?? '') . ' ' . ($eval['evaluator_last_name'] ?? '')) ?: 'Évaluateur' ?></h5>
                                                    <span class="evaluation-date">
                                                        <i class="fas fa-clock"></i>
                                                        <?= !empty($eval['created_at']) ? date('d/m/Y', strtotime($eval['created_at'])) : 'Date inconnue' ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="evaluation-score-mini">
                                                <span class="score-value"><?= $eval['score'] ?? 0 ?></span>
                                                <span class="score-separator">/</span>
                                                <span class="score-max">5</span>
                                            </div>
                                        </div>
                                        <?php if (!empty($eval['comment'])): ?>
                                            <div class="evaluation-comment">
                                                <p><?= htmlspecialchars(substr($eval['comment'], 0, 120)) ?><?= strlen($eval['comment']) > 120 ? '...' : '' ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-mini">
                                <div class="empty-icon-mini">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4>Première évaluation</h4>
                                <p>Vous êtes le premier à évaluer cette idée</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="modern-card actions-card">
                    <div class="card-header-modern">
                        <div class="header-left">
                            <h3 class="section-title">
                                <i class="fas fa-bolt section-icon"></i>
                                Actions rapides
                            </h3>
                            <p class="section-subtitle">Outils et raccourcis</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="actions-grid">
                            <a href="/evaluateur/feedback/<?= $idea['id'] ?>" class="action-item">
                                <div class="action-icon action-info">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Ajouter un commentaire</span>
                                    <span class="action-desc">Feedback supplémentaire</span>
                                </div>
                            </a>
                            
                            <a href="/salarie/idea/<?= $idea['id'] ?>" class="action-item" target="_blank">
                                <div class="action-icon action-secondary">
                                    <i class="fas fa-external-link-alt"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Vue employé</span>
                                    <span class="action-desc">Voir côté salarié</span>
                                </div>
                            </a>
                            
                            <?php if (!empty($idea['attachments'])): ?>
                                <button type="button" class="action-item" onclick="downloadAllAttachments()">
                                    <div class="action-icon action-warning">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="action-text">
                                        <span class="action-title">Télécharger tout</span>
                                        <span class="action-desc">Toutes les pièces jointes</span>
                                    </div>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced star rating interaction
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-label-modern');
    const starInputs = document.querySelectorAll('.star-input');
    const ratingLabels = document.querySelectorAll('.rating-label');
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
            showRatingLabel(index + 1);
        });
        
        star.addEventListener('click', function() {
            const input = document.getElementById(`star${index + 1}`);
            input.checked = true;
            highlightStars(index + 1);
            showRatingLabel(index + 1);
        });
    });
    
    const ratingContainer = document.querySelector('.rating-input-modern');
    if (ratingContainer) {
        ratingContainer.addEventListener('mouseleave', function() {
            const checkedInput = document.querySelector('.star-input:checked');
            if (checkedInput) {
                const value = parseInt(checkedInput.value);
                highlightStars(value);
                showRatingLabel(value);
            } else {
                highlightStars(0);
                hideAllRatingLabels();
            }
        });
    }
    
    function highlightStars(count) {
        stars.forEach((star, index) => {
            if (index < count) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    function showRatingLabel(rating) {
        hideAllRatingLabels();
        const label = document.querySelector(`.rating-${rating}`);
        if (label) {
            label.classList.add('active');
        }
    }
    
    function hideAllRatingLabels() {
        ratingLabels.forEach(label => {
            label.classList.remove('active');
        });
    }
    
    // Initialize with current selection
    const checkedInput = document.querySelector('.star-input:checked');
    if (checkedInput) {
        const value = parseInt(checkedInput.value);
        highlightStars(value);
        showRatingLabel(value);
    }
    
    // Smooth scroll to form when editing
    const editButton = document.querySelector('[data-bs-target="#editEvaluation"]');
    if (editButton) {
        editButton.addEventListener('click', function() {
            setTimeout(() => {
                const form = document.getElementById('editEvaluation');
                if (form && !form.classList.contains('collapse')) {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }, 350);
        });
    }
});

function downloadAllAttachments() {
    <?php if (!empty($idea['attachments'])): ?>
        const attachments = [
            <?php foreach ($idea['attachments'] as $attachment): ?>
                {
                    url: '/uploads/<?= htmlspecialchars($attachment['filename']) ?>',
                    name: '<?= htmlspecialchars($attachment['original_name']) ?>'
                },
            <?php endforeach; ?>
        ];
        
        attachments.forEach((attachment, index) => {
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = attachment.url;
                link.download = attachment.name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, index * 100);
        });
    <?php endif; ?>
}
</script>

<style>
/* Modern Evaluation Detail Styles */
.modern-evaluation-detail {
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
    transition: transform 0.2s ease;
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

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
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

.btn-large {
    padding: 1rem 2rem;
    font-size: 1rem;
}

/* Idea Information Card */
.idea-info-card {
    margin-bottom: 2rem;
}

.idea-title-section {
    flex: 1;
}

.idea-main-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 1rem 0;
    line-height: 1.3;
}

.idea-meta-header {
    display: flex;
    gap: 2rem;
    font-size: 0.9rem;
    color: #6b7280;
}

.author-info, .date-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-section {
    flex-shrink: 0;
}

.modern-status-badge {
    padding: 0.75rem 1.5rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
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

.status-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

.idea-details-grid {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.detail-label i {
    color: #667eea;
}

.detail-value {
    margin-left: 1.5rem;
}

.theme-tag {
    background: #e0e7ff;
    color: #4338ca;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.description-content {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    line-height: 1.6;
    color: #374151;
}

.attachments-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.attachment-item {
    background: #f3f4f6;
    color: #374151;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
}

.attachment-item:hover {
    background: #e5e7eb;
    color: #1f2937;
    transform: translateY(-1px);
}

/* Current Evaluation Card */
.current-evaluation-card {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #bae6fd;
}

.evaluation-score-badge {
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.score-display {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
}

.score-number {
    font-size: 2rem;
    font-weight: 700;
    color: #0369a1;
}

.score-separator, .score-max {
    font-size: 1.2rem;
    color: #6b7280;
}

.stars-display {
    display: flex;
    gap: 2px;
}

.star-filled {
    color: #fbbf24;
}

.star-empty {
    color: #e5e7eb;
}

.evaluation-info-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.evaluation-meta {
    display: flex;
    gap: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.9rem;
}

.comment-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e0f2fe;
}

.comment-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.comment-title i {
    color: #667eea;
}

.comment-content {
    line-height: 1.6;
    color: #374151;
}

.evaluation-actions {
    margin-top: 1.5rem;
}

/* Evaluation Form */
.evaluation-form-card {
    border: 2px solid #f3e8ff;
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
}

.error-alert {
    background: #fee2e2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    gap: 1rem;
}

.alert-icon {
    color: #dc2626;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.alert-content h4 {
    color: #991b1b;
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.alert-content ul {
    margin: 0;
    padding-left: 1rem;
    color: #dc2626;
}

.evaluation-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 1rem;
}

.form-label i {
    color: #667eea;
}

.required {
    color: #dc2626;
    font-weight: 700;
}

.rating-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.rating-input-modern {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    padding: 1rem 0;
}

.star-input {
    display: none;
}

.star-label-modern {
    font-size: 3rem;
    color: #e5e7eb;
    cursor: pointer;
    transition: all 0.2s ease;
}

.star-label-modern:hover,
.star-label-modern.active {
    color: #fbbf24;
    transform: scale(1.1);
}

.rating-labels {
    display: flex;
    justify-content: space-between;
    padding: 0 1rem;
}

.rating-label {
    font-size: 0.8rem;
    color: #6b7280;
    opacity: 0.5;
    transition: all 0.2s ease;
    font-weight: 500;
}

.rating-label.active {
    opacity: 1;
    color: #667eea;
    font-weight: 600;
    transform: scale(1.05);
}

.form-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.form-hint i {
    color: #9ca3af;
}

.modern-textarea {
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.9rem;
    line-height: 1.6;
    resize: vertical;
    min-height: 120px;
    transition: border-color 0.2s ease;
}

.modern-textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: flex-start;
    margin-top: 1rem;
}

/* Sidebar Styles */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.evaluations-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #bbf7d0;
}

.average-score-display {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border-radius: 16px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.score-circle {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 50%;
    color: white;
    flex-shrink: 0;
}

.avg-score {
    font-size: 1.8rem;
    font-weight: 700;
}

.score-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stars-avg {
    display: flex;
    gap: 2px;
}

.evaluation-count {
    color: #6b7280;
    font-size: 0.9rem;
    margin: 0;
}

.evaluations-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.evaluation-item-modern {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.evaluation-item-modern:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.evaluator-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.evaluator-info {
    display: flex;
    gap: 1rem;
    flex: 1;
}

.evaluator-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.evaluator-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.evaluator-name {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.evaluation-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #6b7280;
}

.evaluation-score-mini {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
    background: #f3f4f6;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
}

.score-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #059669;
}

.evaluation-comment {
    margin-top: 0.5rem;
}

.evaluation-comment p {
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.empty-state-mini {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-icon-mini {
    width: 60px;
    height: 60px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem auto;
    font-size: 1.5rem;
}

.empty-state-mini h4 {
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.empty-state-mini p {
    margin: 0;
    font-size: 0.9rem;
}

/* Actions Card */
.actions-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    cursor: pointer;
    border: none;
    width: 100%;
    text-align: left;
}

.action-item:hover {
    background: white;
    border-color: #e2e8f0;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: inherit;
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

.action-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.action-secondary { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
.action-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

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

/* Responsive Design */
@media (max-width: 1024px) {
    .main-content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .average-score-display {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .modern-evaluation-detail {
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
    
    .idea-meta-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .idea-main-title {
        font-size: 1.5rem;
    }
    
    .rating-input-modern {
        gap: 0.25rem;
    }
    
    .star-label-modern {
        font-size: 2.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .evaluator-info {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .evaluator-header {
        flex-direction: column;
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
