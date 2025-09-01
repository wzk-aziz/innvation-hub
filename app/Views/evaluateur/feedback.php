<?php include 'layouts/header.php'; ?>

<div class="feedback-page">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-comment me-2"></i>
                            Ajouter un commentaire détaillé
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="/evaluateur/dashboard">Tableau de bord</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="/evaluateur/review">Idées à évaluer</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="/evaluateur/review/<?= $idea['id'] ?>"><?= htmlspecialchars($idea['title']) ?></a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Commentaire
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="/evaluateur/review/<?= $idea['id'] ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour à l'idée
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Idea Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            Résumé de l'idée
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($idea['title']) ?></h6>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-tag me-1"></i>
                                Thème
                            </small>
                            <br>
                            <span class="badge bg-secondary"><?= htmlspecialchars($idea['theme_name']) ?></span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                Auteur
                            </small>
                            <br>
                            <?= htmlspecialchars($idea['author_first_name'] . ' ' . $idea['author_last_name']) ?>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Date de création
                            </small>
                            <br>
                            <?= date('d/m/Y à H:i', strtotime($idea['created_at'])) ?>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Description</small>
                            <div class="border rounded p-2 bg-light small">
                                <?= nl2br(htmlspecialchars(substr($idea['description'], 0, 200))) ?>
                                <?php if (strlen($idea['description']) > 200): ?>
                                    <span class="text-muted">...</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Current Evaluation -->
                        <?php if (!empty($currentEvaluation)): ?>
                            <div class="border rounded p-3 bg-info bg-opacity-10">
                                <h6 class="text-info mb-2">
                                    <i class="fas fa-star me-1"></i>
                                    Mon évaluation
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rating-stars me-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $currentEvaluation['score'] ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="badge bg-primary"><?= $currentEvaluation['score'] ?>/5</span>
                                </div>
                                <?php if (!empty($currentEvaluation['comment'])): ?>
                                    <small class="text-muted">
                                        <?= htmlspecialchars(substr($currentEvaluation['comment'], 0, 100)) ?>
                                        <?php if (strlen($currentEvaluation['comment']) > 100): ?>...<?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Vous n'avez pas encore évalué cette idée.
                                <a href="/evaluateur/review/<?= $idea['id'] ?>" class="alert-link">Évaluer maintenant</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-comment-dots me-2"></i>
                            <?= !empty($existingFeedback) ? 'Modifier mon commentaire' : 'Nouveau commentaire détaillé' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['errors'])): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($_SESSION['errors'] as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php unset($_SESSION['errors']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <?= htmlspecialchars($_SESSION['success']) ?>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/evaluateur/feedback">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="idea_id" value="<?= $idea['id'] ?>">
                            <?php if (!empty($existingFeedback)): ?>
                                <input type="hidden" name="feedback_id" value="<?= $existingFeedback['id'] ?>">
                            <?php endif; ?>

                            <!-- Feedback Content -->
                            <div class="mb-4">
                                <label for="feedback" class="form-label">
                                    <i class="fas fa-comment me-1"></i>
                                    Commentaire détaillé <span class="text-danger">*</span>
                                </label>
                                <textarea name="feedback" id="feedback" rows="8" class="form-control" 
                                          placeholder="Partagez votre analyse détaillée de cette idée..." required><?= isset($existingFeedback) ? htmlspecialchars($existingFeedback['feedback']) : '' ?></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ce commentaire sera visible par l'auteur de l'idée et pourra l'aider à améliorer sa proposition.
                                </div>
                            </div>

                            <!-- Feedback Categories -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="feasibility" class="form-label">
                                        <i class="fas fa-tools me-1"></i>
                                        Faisabilité (1-5)
                                    </label>
                                    <select name="feasibility" id="feasibility" class="form-select">
                                        <option value="">Non évaluée</option>
                                        <option value="1" <?= isset($existingFeedback) && $existingFeedback['feasibility'] == 1 ? 'selected' : '' ?>>1 - Très difficile</option>
                                        <option value="2" <?= isset($existingFeedback) && $existingFeedback['feasibility'] == 2 ? 'selected' : '' ?>>2 - Difficile</option>
                                        <option value="3" <?= isset($existingFeedback) && $existingFeedback['feasibility'] == 3 ? 'selected' : '' ?>>3 - Modérée</option>
                                        <option value="4" <?= isset($existingFeedback) && $existingFeedback['feasibility'] == 4 ? 'selected' : '' ?>>4 - Facile</option>
                                        <option value="5" <?= isset($existingFeedback) && $existingFeedback['feasibility'] == 5 ? 'selected' : '' ?>>5 - Très facile</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="innovation" class="form-label">
                                        <i class="fas fa-rocket me-1"></i>
                                        Innovation (1-5)
                                    </label>
                                    <select name="innovation" id="innovation" class="form-select">
                                        <option value="">Non évaluée</option>
                                        <option value="1" <?= isset($existingFeedback) && $existingFeedback['innovation'] == 1 ? 'selected' : '' ?>>1 - Peu innovante</option>
                                        <option value="2" <?= isset($existingFeedback) && $existingFeedback['innovation'] == 2 ? 'selected' : '' ?>>2 - Légèrement innovante</option>
                                        <option value="3" <?= isset($existingFeedback) && $existingFeedback['innovation'] == 3 ? 'selected' : '' ?>>3 - Modérément innovante</option>
                                        <option value="4" <?= isset($existingFeedback) && $existingFeedback['innovation'] == 4 ? 'selected' : '' ?>>4 - Très innovante</option>
                                        <option value="5" <?= isset($existingFeedback) && $existingFeedback['innovation'] == 5 ? 'selected' : '' ?>>5 - Révolutionnaire</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="impact" class="form-label">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Impact attendu (1-5)
                                    </label>
                                    <select name="impact" id="impact" class="form-select">
                                        <option value="">Non évalué</option>
                                        <option value="1" <?= isset($existingFeedback) && $existingFeedback['impact'] == 1 ? 'selected' : '' ?>>1 - Impact minimal</option>
                                        <option value="2" <?= isset($existingFeedback) && $existingFeedback['impact'] == 2 ? 'selected' : '' ?>>2 - Impact faible</option>
                                        <option value="3" <?= isset($existingFeedback) && $existingFeedback['impact'] == 3 ? 'selected' : '' ?>>3 - Impact modéré</option>
                                        <option value="4" <?= isset($existingFeedback) && $existingFeedback['impact'] == 4 ? 'selected' : '' ?>>4 - Impact élevé</option>
                                        <option value="5" <?= isset($existingFeedback) && $existingFeedback['impact'] == 5 ? 'selected' : '' ?>>5 - Impact majeur</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-exclamation me-1"></i>
                                        Priorité recommandée
                                    </label>
                                    <select name="priority" id="priority" class="form-select">
                                        <option value="">Non définie</option>
                                        <option value="low" <?= isset($existingFeedback) && $existingFeedback['priority'] == 'low' ? 'selected' : '' ?>>Basse</option>
                                        <option value="medium" <?= isset($existingFeedback) && $existingFeedback['priority'] == 'medium' ? 'selected' : '' ?>>Moyenne</option>
                                        <option value="high" <?= isset($existingFeedback) && $existingFeedback['priority'] == 'high' ? 'selected' : '' ?>>Haute</option>
                                        <option value="urgent" <?= isset($existingFeedback) && $existingFeedback['priority'] == 'urgent' ? 'selected' : '' ?>>Urgente</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Feedback Type -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-tags me-1"></i>
                                    Type de commentaire
                                </label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="feedback_type" id="feedback_positive" value="positive"
                                                   <?= isset($existingFeedback) && $existingFeedback['type'] == 'positive' ? 'checked' : '' ?>>
                                            <label class="form-check-label text-success" for="feedback_positive">
                                                <i class="fas fa-thumbs-up me-1"></i>
                                                Commentaire positif
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="feedback_type" id="feedback_constructive" value="constructive"
                                                   <?= isset($existingFeedback) && $existingFeedback['type'] == 'constructive' ? 'checked' : '' ?>>
                                            <label class="form-check-label text-warning" for="feedback_constructive">
                                                <i class="fas fa-lightbulb me-1"></i>
                                                Commentaire constructif
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="feedback_type" id="feedback_improvement" value="improvement"
                                                   <?= isset($existingFeedback) && $existingFeedback['type'] == 'improvement' ? 'checked' : '' ?>>
                                            <label class="form-check-label text-info" for="feedback_improvement">
                                                <i class="fas fa-arrow-up me-1"></i>
                                                Suggestions d'amélioration
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="feedback_type" id="feedback_concern" value="concern"
                                                   <?= isset($existingFeedback) && $existingFeedback['type'] == 'concern' ? 'checked' : '' ?>>
                                            <label class="form-check-label text-danger" for="feedback_concern">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Préoccupations/Risques
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="mb-4">
                                <label for="recommendations" class="form-label">
                                    <i class="fas fa-clipboard-list me-1"></i>
                                    Recommandations spécifiques
                                </label>
                                <textarea name="recommendations" id="recommendations" rows="4" class="form-control" 
                                          placeholder="Quelles actions recommandez-vous pour cette idée ?"><?= isset($existingFeedback) ? htmlspecialchars($existingFeedback['recommendations']) : '' ?></textarea>
                            </div>

                            <!-- Visibility Settings -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1"
                                           <?= isset($existingFeedback) && $existingFeedback['is_public'] ? 'checked' : 'checked' ?>>
                                    <label class="form-check-label" for="is_public">
                                        <i class="fas fa-eye me-1"></i>
                                        Commentaire visible publiquement
                                    </label>
                                    <div class="form-text">
                                        Si décoché, seul l'auteur de l'idée pourra voir ce commentaire
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    <?= !empty($existingFeedback) ? 'Mettre à jour' : 'Publier' ?> le commentaire
                                </button>
                                <a href="/evaluateur/review/<?= $idea['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Annuler
                                </a>
                                <?php if (!empty($existingFeedback)): ?>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFeedback(<?= $existingFeedback['id'] ?>)">
                                        <i class="fas fa-trash me-1"></i>
                                        Supprimer
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Existing Feedback -->
                <?php if (!empty($otherFeedbacks)): ?>
                    <div class="card shadow mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-comments me-2"></i>
                                Autres commentaires (<?= count($otherFeedbacks) ?>)
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($otherFeedbacks as $feedback): ?>
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= htmlspecialchars($feedback['evaluator_first_name'] . ' ' . $feedback['evaluator_last_name']) ?></strong>
                                            <?php if (!empty($feedback['type'])): ?>
                                                <span class="badge bg-<?= match($feedback['type']) {
                                                    'positive' => 'success',
                                                    'constructive' => 'warning',
                                                    'improvement' => 'info',
                                                    'concern' => 'danger',
                                                    default => 'secondary'
                                                } ?> ms-2">
                                                    <?= match($feedback['type']) {
                                                        'positive' => 'Positif',
                                                        'constructive' => 'Constructif',
                                                        'improvement' => 'Amélioration',
                                                        'concern' => 'Préoccupation',
                                                        default => 'Général'
                                                    } ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($feedback['feedback'])) ?></p>
                                    
                                    <?php if (!empty($feedback['recommendations'])): ?>
                                        <div class="mt-2 pt-2 border-top">
                                            <small class="text-muted">
                                                <i class="fas fa-clipboard-list me-1"></i>
                                                Recommandations:
                                            </small>
                                            <p class="mb-0 small"><?= nl2br(htmlspecialchars($feedback['recommendations'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteFeedback(feedbackId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
        return;
    }

    fetch(`/evaluateur/feedback/${feedbackId}/delete`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Commentaire supprimé avec succès', 'success');
            setTimeout(() => {
                window.location.href = '/evaluateur/review/<?= $idea['id'] ?>';
            }, 1000);
        } else {
            showNotification('Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de la suppression', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Character count for textarea
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('feedback');
    if (textarea) {
        function updateCharCount() {
            const count = textarea.value.length;
            const maxCount = 2000;
            
            let countElement = document.getElementById('char-count');
            if (!countElement) {
                countElement = document.createElement('div');
                countElement.id = 'char-count';
                countElement.className = 'form-text text-end';
                textarea.parentNode.appendChild(countElement);
            }
            
            countElement.textContent = `${count}/${maxCount} caractères`;
            countElement.className = `form-text text-end ${count > maxCount ? 'text-danger' : 'text-muted'}`;
        }
        
        textarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }
});
</script>

<style>
.rating-stars i {
    margin-right: 1px;
}

.form-check-label {
    cursor: pointer;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.15s ease-in-out;
}

.badge {
    font-size: 0.875em;
}

textarea {
    resize: vertical;
    min-height: 120px;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
}
</style>

<?php include 'layouts/footer.php'; ?>
