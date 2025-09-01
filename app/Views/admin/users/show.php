<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-user"></i>
            Détails de l'utilisateur
        </h1>
        <div class="page-actions">
            <a href="/admin/users" class="btn btn-secondary">
                <i class="icon-arrow-left"></i>
                Retour à la liste
            </a>
            <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-primary">
                <i class="icon-edit"></i>
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="content-container">
    <!-- User Details Card -->
    <div class="card user-details-card">
        <div class="card-header">
            <h2 class="card-title">
                <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                <span class="status-badge status-<?= $user['status'] ?>">
                    <?= $user['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                </span>
            </h2>
            <div class="user-meta">
                <div class="meta-item">
                    <i class="icon-mail"></i>
                    <strong>Email:</strong> 
                    <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                        <?= htmlspecialchars($user['email']) ?>
                    </a>
                </div>
                <div class="meta-item">
                    <i class="icon-shield"></i>
                    <strong>Rôle:</strong> 
                    <span class="role-badge role-<?= $user['role'] ?>">
                        <?= match($user['role']) {
                            'admin' => 'Administrateur',
                            'salarie' => 'Salarié',
                            'evaluateur' => 'Évaluateur',
                            default => ucfirst($user['role'])
                        } ?>
                    </span>
                </div>
                <div class="meta-item">
                    <i class="icon-calendar"></i>
                    <strong>Inscrit le:</strong> <?= date('d/m/Y à H:i', strtotime($user['created_at'])) ?>
                </div>
                <?php if (!empty($user['updated_at'])): ?>
                <div class="meta-item">
                    <i class="icon-edit"></i>
                    <strong>Dernière modification:</strong> <?= date('d/m/Y à H:i', strtotime($user['updated_at'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="icon-lightbulb"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="ideas-count">-</h3>
                            <p class="stats-label">Idées soumises</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($user['role'] === 'evaluateur'): ?>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="icon-star"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="evaluations-count">-</h3>
                            <p class="stats-label">Évaluations données</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($user['role'] === 'admin'): ?>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="icon-message"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="feedback-count">-</h3>
                            <p class="stats-label">Commentaires donnés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Recent Activity -->
    <div class="card activity-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="icon-activity"></i>
                Activité récente
            </h2>
        </div>
        
        <div class="card-body">
            <div id="recent-activity">
                <div class="loading-state">
                    <i class="icon-spinner"></i>
                    <p>Chargement de l'activité...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions (only for admins) -->
    <?php if (\App\Core\Auth::user()['role'] === 'admin' && \App\Core\Auth::user()['id'] != $user['id']): ?>
    <div class="card admin-actions-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="icon-settings"></i>
                Actions administrateur
            </h2>
        </div>
        
        <div class="card-body">
            <div class="action-buttons">
                <form method="POST" action="/admin/users/<?= $user['id'] ?>/status" class="status-form">
                    <?= \App\Core\Auth::csrfField() ?>
                    <div class="status-actions">
                        <label for="status">Changer le statut:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                        <button type="submit" class="btn btn-warning">
                            <i class="icon-save"></i>
                            Mettre à jour le statut
                        </button>
                    </div>
                </form>
                
                <div class="danger-actions">
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="icon-trash"></i>
                        Supprimer l'utilisateur
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmer la suppression</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
            <p><strong>Cette action est irréversible.</strong></p>
            <p>Toutes les données associées (idées, évaluations, commentaires) seront également supprimées.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <form method="POST" action="/admin/users/<?= $user['id'] ?>/delete" style="display: inline;">
                <?= \App\Core\Auth::csrfField() ?>
                <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
            </form>
        </div>
    </div>
</div>

<style>
/* Base styles */
.content-container {
    color: #212529;
}

.user-details-card {
    margin-bottom: 1.5rem;
}

.user-meta {
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

.meta-item a {
    color: #007bff;
    text-decoration: none;
}

.meta-item a:hover {
    text-decoration: underline;
}

/* Status and role badges */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    margin-left: 1rem;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.role-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 0.8rem;
    font-size: 0.8rem;
    font-weight: 500;
}

.role-admin {
    background: #e7f3ff;
    color: #0066cc;
}

.role-salarie {
    background: #fff3cd;
    color: #856404;
}

.role-evaluateur {
    background: #d1ecf1;
    color: #0c5460;
}

/* Statistics cards */
.row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.stats-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.stats-info {
    flex: 1;
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: white;
}

.stats-label {
    margin: 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

/* Activity card */
.activity-card {
    margin-bottom: 1.5rem;
}

.loading-state {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.loading-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Admin actions */
.admin-actions-card {
    margin-bottom: 1.5rem;
}

.action-buttons {
    display: grid;
    gap: 2rem;
}

.status-form .status-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-select {
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-width: 150px;
}

.danger-actions {
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 0.5rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.modal-header h3 {
    margin: 0;
    color: #212529;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6c757d;
}

.modal-body {
    padding: 1rem;
    color: #212529;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #e9ecef;
}

/* Responsive design */
@media (max-width: 768px) {
    .user-meta {
        grid-template-columns: 1fr;
    }
    
    .status-form .status-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Load user statistics
document.addEventListener('DOMContentLoaded', function() {
    loadUserStats();
    loadRecentActivity();
});

function loadUserStats() {
    // This would typically make AJAX calls to get statistics
    // For now, we'll just show placeholder data
    setTimeout(() => {
        const ideasCount = document.getElementById('ideas-count');
        const evaluationsCount = document.getElementById('evaluations-count');
        const feedbackCount = document.getElementById('feedback-count');
        
        if (ideasCount) ideasCount.textContent = '0'; // This should be fetched via AJAX
        if (evaluationsCount) evaluationsCount.textContent = '0';
        if (feedbackCount) feedbackCount.textContent = '0';
    }, 500);
}

function loadRecentActivity() {
    // This would typically make AJAX calls to get recent activity
    setTimeout(() => {
        const activityContainer = document.getElementById('recent-activity');
        activityContainer.innerHTML = '<div class="empty-state"><i class="icon-clock"></i><p>Aucune activité récente</p></div>';
    }, 1000);
}

function confirmDelete() {
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
