<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-ideas"></i>
            Détails de l'Idée
        </h1>
        <div class="page-actions">
            <a href="/admin/ideas" class="btn btn-secondary">
                <i class="icon-arrow-left"></i>
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="content-container">
    <!-- Idea Details Card -->
    <div class="card idea-details-card">
        <div class="card-header">
            <h2 class="card-title">
                <?= htmlspecialchars($idea['title']) ?>
                <span class="status-badge status-<?= $idea['status'] ?>">
                    <?= match($idea['status']) {
                        'submitted' => 'Soumise',
                        'under_review' => 'En révision',
                        'accepted' => 'Acceptée',
                        'rejected' => 'Rejetée',
                        default => ucfirst($idea['status'])
                    } ?>
                </span>
            </h2>
            <div class="idea-meta">
                <div class="meta-item">
                    <i class="icon-user"></i>
                    <strong>Auteur:</strong> <?= htmlspecialchars($idea['first_name'] . ' ' . $idea['last_name']) ?>
                </div>
                <div class="meta-item">
                    <i class="icon-theme"></i>
                    <strong>Thème:</strong> <?= htmlspecialchars($idea['theme_name']) ?>
                </div>
                <div class="meta-item">
                    <i class="icon-calendar"></i>
                    <strong>Soumise le:</strong> <?= date('d/m/Y à H:i', strtotime($idea['created_at'])) ?>
                </div>
                <?php if (!empty($idea['updated_at']) && $idea['updated_at'] !== $idea['created_at']): ?>
                <div class="meta-item">
                    <i class="icon-edit"></i>
                    <strong>Modifiée le:</strong> <?= date('d/m/Y à H:i', strtotime($idea['updated_at'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card-body">
            <div class="idea-description">
                <h3>Description</h3>
                <div class="description-content">
                    <?= nl2br(htmlspecialchars($idea['description'])) ?>
                </div>
            </div>
            
            <?php if (!empty($idea['attachments'])): ?>
            <div class="idea-attachments">
                <h3>Pièces jointes</h3>
                <div class="attachments-list">
                    <!-- Display attachments if any -->
                    <?= htmlspecialchars($idea['attachments']) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Evaluations Section -->
    <div class="card evaluations-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="icon-star"></i>
                Évaluations
                <?php if (!empty($evaluations)): ?>
                    <span class="average-score">
                        Score moyen: <strong><?= $average_score ?>/5</strong>
                    </span>
                <?php endif; ?>
            </h2>
        </div>
        
        <div class="card-body">
            <?php if (empty($evaluations)): ?>
                <div class="empty-state">
                    <i class="icon-star-empty"></i>
                    <p>Aucune évaluation pour cette idée</p>
                </div>
            <?php else: ?>
                <div class="evaluations-list">
                    <?php foreach ($evaluations as $evaluation): ?>
                        <div class="evaluation-item">
                            <div class="evaluation-header">
                                <div class="evaluator-info">
                                    <strong>
                                        <?= htmlspecialchars($evaluation['evaluator_first_name'] . ' ' . $evaluation['evaluator_last_name']) ?>
                                    </strong>
                                    <span class="evaluation-date">
                                        <?= date('d/m/Y à H:i', strtotime($evaluation['created_at'])) ?>
                                    </span>
                                </div>
                                <div class="evaluation-score">
                                    <span class="score-value"><?= $evaluation['score'] ?>/5</span>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?= $i <= $evaluation['score'] ? 'filled' : 'empty' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($evaluation['comments'])): ?>
                                <div class="evaluation-comments">
                                    <?= nl2br(htmlspecialchars($evaluation['comments'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="card feedback-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="icon-message"></i>
                Commentaires administrateur
            </h2>
        </div>
        
        <div class="card-body">
            <?php if (empty($feedback)): ?>
                <div class="empty-state">
                    <i class="icon-message-empty"></i>
                    <p>Aucun commentaire administrateur pour cette idée</p>
                </div>
            <?php else: ?>
                <div class="feedback-list">
                    <?php foreach ($feedback as $feedbackItem): ?>
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <div class="admin-info">
                                    <strong>
                                        <?= htmlspecialchars($feedbackItem['first_name'] . ' ' . $feedbackItem['last_name']) ?>
                                    </strong>
                                    <span class="feedback-date">
                                        <?= date('d/m/Y à H:i', strtotime($feedbackItem['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="feedback-content">
                                <?= nl2br(htmlspecialchars($feedbackItem['message'] ?? '')) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="card admin-actions-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="icon-settings"></i>
                Actions administrateur
            </h2>
        </div>
        
        <div class="card-body">
            <div class="action-buttons">
                <form method="POST" action="/admin/ideas/<?= $idea['id'] ?>/status" class="status-form">
                    <?= \App\Core\Auth::csrfField() ?>
                    <div class="status-actions">
                        <label for="status">Changer le statut:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="submitted" <?= $idea['status'] === 'submitted' ? 'selected' : '' ?>>Soumise</option>
                            <option value="under_review" <?= $idea['status'] === 'under_review' ? 'selected' : '' ?>>En révision</option>
                            <option value="accepted" <?= $idea['status'] === 'accepted' ? 'selected' : '' ?>>Acceptée</option>
                            <option value="rejected" <?= $idea['status'] === 'rejected' ? 'selected' : '' ?>>Rejetée</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-save"></i>
                            Mettre à jour
                        </button>
                    </div>
                </form>
                
                <div class="feedback-form">
                    <form method="POST" action="/admin/ideas/<?= $idea['id'] ?>/feedback">
                        <?= \App\Core\Auth::csrfField() ?>
                        <div class="form-group">
                            <label for="message">Ajouter un commentaire:</label>
                            <textarea name="message" id="message" class="form-textarea" rows="4" 
                                      placeholder="Votre commentaire..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary">
                            <i class="icon-message"></i>
                            Ajouter un commentaire
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Base text colors for better readability */
.content-container {
    color: #212529;
}

.card-title {
    color: #212529;
}

.idea-details-card {
    margin-bottom: 1.5rem;
}

.idea-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #212529;
}

.meta-item i {
    color: #495057;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    margin-left: 1rem;
}

.status-submitted {
    background: #fff3cd;
    color: #856404;
}

.status-under_review {
    background: #d1ecf1;
    color: #0c5460;
}

.status-accepted {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.description-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
    line-height: 1.6;
}

.evaluations-card,
.feedback-card,
.admin-actions-card {
    margin-bottom: 1.5rem;
}

.average-score {
    font-size: 0.9rem;
    color: #495057;
    margin-left: auto;
}

.evaluation-item,
.feedback-item {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    background: #fff;
    color: #212529;
}

.evaluation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.evaluator-info,
.admin-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.admin-info strong,
.evaluator-info strong {
    color: #212529;
    font-weight: 600;
}

.evaluation-date,
.feedback-date {
    font-size: 0.8rem;
    color: #495057;
}

.evaluation-score {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.score-value {
    font-weight: bold;
    color: #28a745;
}

.stars {
    display: flex;
    gap: 0.1rem;
}

.star {
    font-size: 1.2rem;
}

.star.filled {
    color: #ffc107;
}

.star.empty {
    color: #e9ecef;
}

.evaluation-comments,
.feedback-content {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
    line-height: 1.5;
    color: #212529;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.action-buttons {
    display: grid;
    gap: 1.5rem;
}

.status-form .status-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-select {
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-width: 150px;
}

.feedback-form .form-group {
    margin-bottom: 1rem;
}

.feedback-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    font-family: inherit;
    font-size: 0.9rem;
    resize: vertical;
}

.form-textarea:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

@media (max-width: 768px) {
    .idea-meta {
        grid-template-columns: 1fr;
    }
    
    .evaluation-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .status-form .status-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
