<div class="edit-idea-container">
    <div class="page-header">
        <div class="header-content">
            <a href="/salarie/ideas/<?= $idea['id'] ?>" class="back-link">
                <i class="icon-arrow-left"></i>
                Retour aux détails
            </a>
            <h1 class="page-title">
                <i class="icon-edit"></i>
                Modifier mon idée
            </h1>
            <p class="page-subtitle">
                Améliorez votre proposition avant qu'elle ne soit évaluée
            </p>
        </div>
    </div>

    <div class="form-container">
        <form id="editIdeaForm" action="/salarie/ideas/<?= $idea['id'] ?>" method="POST" class="idea-form">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            
            <!-- Current Status Alert -->
            <div class="status-alert alert-info">
                <i class="icon-info"></i>
                <div>
                    <strong>Statut actuel:</strong> 
                    <?= match($idea['status']) {
                        'submitted' => 'Soumise - Vous pouvez encore modifier votre idée',
                        'under_review' => 'En révision - Modification limitée',
                        'accepted' => 'Acceptée - Modification non autorisée',
                        'rejected' => 'Rejetée - Modification non autorisée',
                        default => ucfirst($idea['status'])
                    } ?>
                </div>
            </div>
            
            <!-- Theme Selection -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="icon-tag"></i>
                    Thématique
                </h3>
                <div class="form-group">
                    <label for="theme_id" class="form-label required">
                        Choisissez la thématique de votre idée
                    </label>
                    <select id="theme_id" name="theme_id" class="form-select" required 
                            <?= $idea['status'] !== 'submitted' ? 'disabled' : '' ?>>
                        <option value="">Sélectionnez une thématique...</option>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?= $theme['id'] ?>" 
                                    <?= ($idea['theme_id'] == $theme['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($theme['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['theme_id'])): ?>
                        <span class="error-message"><?= $errors['theme_id'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="icon-edit"></i>
                    Informations générales
                </h3>
                
                <div class="form-group">
                    <label for="title" class="form-label required">
                        Titre de votre idée
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        class="form-input" 
                        value="<?= htmlspecialchars($idea['title']) ?>"
                        placeholder="Ex: Amélioration du système de gestion des commandes"
                        maxlength="255"
                        required
                        <?= $idea['status'] !== 'submitted' ? 'readonly' : '' ?>
                    >
                    <small class="form-help">
                        Donnez un titre clair et accrocheur à votre idée (maximum 255 caractères)
                    </small>
                    <?php if (isset($errors['title'])): ?>
                        <span class="error-message"><?= $errors['title'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label required">
                        Description détaillée
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-textarea" 
                        rows="8"
                        placeholder="Décrivez votre idée en détail..."
                        required
                        <?= $idea['status'] !== 'submitted' ? 'readonly' : '' ?>
                    ><?= htmlspecialchars($idea['description']) ?></textarea>
                    <small class="form-help">
                        Expliquez votre idée de manière claire et complète.
                    </small>
                    <?php if (isset($errors['description'])): ?>
                        <span class="error-message"><?= $errors['description'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Implementation Details -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="icon-cog"></i>
                    Détails d'implémentation
                </h3>
                
                <div class="form-group">
                    <label for="expected_impact" class="form-label">
                        Impact attendu
                    </label>
                    <textarea 
                        id="expected_impact" 
                        name="expected_impact" 
                        class="form-textarea" 
                        rows="4"
                        placeholder="Décrivez l'impact que pourrait avoir votre idée..."
                        <?= $idea['status'] !== 'submitted' ? 'readonly' : '' ?>
                    ><?= htmlspecialchars($idea['expected_impact'] ?? '') ?></textarea>
                    <small class="form-help">
                        Expliquez les bénéfices concrets que votre idée pourrait apporter.
                    </small>
                    <?php if (isset($errors['expected_impact'])): ?>
                        <span class="error-message"><?= $errors['expected_impact'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="required_resources" class="form-label">
                        Ressources nécessaires
                    </label>
                    <textarea 
                        id="required_resources" 
                        name="required_resources" 
                        class="form-textarea" 
                        rows="4"
                        placeholder="Listez les ressources qui seraient nécessaires..."
                        <?= $idea['status'] !== 'submitted' ? 'readonly' : '' ?>
                    ><?= htmlspecialchars($idea['required_resources'] ?? '') ?></textarea>
                    <small class="form-help">
                        Aidez l'équipe d'évaluation à comprendre les moyens nécessaires.
                    </small>
                    <?php if (isset($errors['required_resources'])): ?>
                        <span class="error-message"><?= $errors['required_resources'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Version History -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="icon-clock"></i>
                    Historique des modifications
                </h3>
                <div class="version-info">
                    <div class="version-item">
                        <strong>Créée le:</strong> 
                        <?= date('d/m/Y à H:i', strtotime($idea['created_at'])) ?>
                    </div>
                    <?php if (!empty($idea['updated_at']) && $idea['updated_at'] !== $idea['created_at']): ?>
                        <div class="version-item">
                            <strong>Dernière modification:</strong> 
                            <?= date('d/m/Y à H:i', strtotime($idea['updated_at'])) ?>
                        </div>
                    <?php endif; ?>
                    <div class="version-item">
                        <strong>Nombre de modifications:</strong> 
                        <?= $idea['modification_count'] ?? 0 ?>
                    </div>
                </div>
                
                <?php if ($idea['status'] !== 'submitted'): ?>
                    <div class="modification-note">
                        <i class="icon-alert-triangle"></i>
                        <p>
                            <strong>Note:</strong> Cette idée ne peut plus être modifiée car elle est en cours d'évaluation ou a déjà été évaluée. 
                            Si vous souhaitez apporter des modifications importantes, contactez l'administration.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form Actions -->
            <?php if ($idea['status'] === 'submitted'): ?>
                <div class="form-actions">
                    <div class="actions-left">
                        <button type="button" class="btn btn-outline" onclick="saveDraft()">
                            <i class="icon-save"></i>
                            Sauvegarder les modifications
                        </button>
                    </div>
                    <div class="actions-right">
                        <a href="/salarie/ideas/<?= $idea['id'] ?>" class="btn btn-secondary">
                            <i class="icon-x"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-check"></i>
                            Mettre à jour l'idée
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="form-actions">
                    <div class="actions-center">
                        <a href="/salarie/ideas/<?= $idea['id'] ?>" class="btn btn-primary">
                            <i class="icon-arrow-left"></i>
                            Retour aux détails
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Changes Preview Modal -->
<div id="changesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Aperçu des modifications</h3>
            <button type="button" class="modal-close" onclick="closeChangesModal()">
                <i class="icon-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="changesPreview"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeChangesModal()">
                Continuer l'édition
            </button>
            <button type="button" class="btn btn-primary" onclick="confirmChanges()">
                Confirmer les modifications
            </button>
        </div>
    </div>
</div>

<style>
/* Main Container */
.edit-idea-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Page Header */
.page-header {
    margin-bottom: 3rem;
}

.header-content {
    text-align: center;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #667eea;
    text-decoration: none;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.back-link:hover {
    color: #5a67d8;
}

.page-title {
    font-size: 2.5rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.page-subtitle {
    color: #718096;
    font-size: 1.1rem;
}

/* Status Alert */
.status-alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.alert-info {
    background: #ebf8ff;
    color: #2b6cb0;
    border: 1px solid #bee3f8;
}

.alert-warning {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

/* Form Container */
.form-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Form Sections */
.form-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 2rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
}

/* Form Elements */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: "*";
    color: #e53e3e;
    margin-left: 0.25rem;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
    background: white;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input[readonly],
.form-select[disabled],
.form-textarea[readonly] {
    background: #f7fafc;
    color: #718096;
    cursor: not-allowed;
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.form-help {
    display: block;
    margin-top: 0.5rem;
    color: #718096;
    font-size: 0.85rem;
    line-height: 1.4;
}

.error-message {
    display: block;
    margin-top: 0.5rem;
    color: #e53e3e;
    font-size: 0.85rem;
}

/* Version History */
.version-info {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.version-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.version-item:last-child {
    border-bottom: none;
}

.modification-note {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 8px;
    padding: 1rem;
    color: #92400e;
}

.modification-note i {
    margin-top: 0.1rem;
    flex-shrink: 0;
}

.modification-note p {
    margin: 0;
    line-height: 1.5;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 1rem;
}

.actions-left,
.actions-right,
.actions-center {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.actions-center {
    width: 100%;
    justify-content: center;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    color: #2d3748;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #718096;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
}

.modal-close:hover {
    background: #f7fafc;
    color: #2d3748;
}

.modal-body {
    padding: 1.5rem;
    max-height: 400px;
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

/* Changes Preview */
.change-item {
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #667eea;
    background: #f7fafc;
}

.change-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.change-diff {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.change-old,
.change-new {
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
    line-height: 1.4;
}

.change-old {
    background: #fed7d7;
    color: #c53030;
}

.change-new {
    background: #c6f6d5;
    color: #38a169;
}

/* Responsive Design */
@media (max-width: 768px) {
    .edit-idea-container {
        padding: 1rem;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-container {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .actions-left,
    .actions-right {
        justify-content: center;
    }
    
    .change-diff {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editIdeaForm');
    const isEditable = <?= json_encode($idea['status'] === 'submitted') ?>;
    
    if (!isEditable) {
        return; // Exit if form is not editable
    }
    
    // Store original values for comparison
    const originalValues = {
        theme_id: '<?= $idea['theme_id'] ?>',
        title: '<?= htmlspecialchars($idea['title'], ENT_QUOTES) ?>',
        description: '<?= htmlspecialchars($idea['description'], ENT_QUOTES) ?>',
        expected_impact: '<?= htmlspecialchars($idea['expected_impact'] ?? '', ENT_QUOTES) ?>',
        required_resources: '<?= htmlspecialchars($idea['required_resources'] ?? '', ENT_QUOTES) ?>'
    };
    
    // Form submission with changes preview
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const changes = detectChanges();
        if (changes.length > 0) {
            showChangesPreview(changes);
        } else {
            showNotification('Aucune modification détectée', 'info');
        }
    });
    
    function detectChanges() {
        const changes = [];
        const formData = new FormData(form);
        
        Object.keys(originalValues).forEach(field => {
            const original = originalValues[field];
            const current = formData.get(field) || '';
            
            if (original !== current) {
                changes.push({
                    field: field,
                    label: getFieldLabel(field),
                    original: original,
                    current: current
                });
            }
        });
        
        return changes;
    }
    
    function getFieldLabel(field) {
        const labels = {
            theme_id: 'Thématique',
            title: 'Titre',
            description: 'Description',
            expected_impact: 'Impact attendu',
            required_resources: 'Ressources nécessaires'
        };
        return labels[field] || field;
    }
    
    function showChangesPreview(changes) {
        const previewHtml = changes.map(change => `
            <div class="change-item">
                <div class="change-label">${change.label}</div>
                <div class="change-diff">
                    <div class="change-old">
                        <strong>Avant:</strong><br>
                        ${change.original || '<em>Vide</em>'}
                    </div>
                    <div class="change-new">
                        <strong>Après:</strong><br>
                        ${change.current || '<em>Vide</em>'}
                    </div>
                </div>
            </div>
        `).join('');
        
        document.getElementById('changesPreview').innerHTML = previewHtml;
        document.getElementById('changesModal').classList.add('show');
    }
    
    // Auto-save functionality
    let autoSaveTimer;
    const formInputs = form.querySelectorAll('input, textarea, select');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                const changes = detectChanges();
                if (changes.length > 0) {
                    saveDraft();
                }
            }, 3000);
        });
    });
});

function saveDraft() {
    const form = document.getElementById('editIdeaForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    localStorage.setItem('ideaEditDraft_<?= $idea['id'] ?>', JSON.stringify(data));
    showNotification('Brouillon sauvegardé automatiquement', 'success');
}

function closeChangesModal() {
    document.getElementById('changesModal').classList.remove('show');
}

function confirmChanges() {
    const form = document.getElementById('editIdeaForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    closeChangesModal();
    
    // Clear draft and submit
    localStorage.removeItem('ideaEditDraft_<?= $idea['id'] ?>');
    
    setTimeout(() => {
        form.submit();
    }, 500);
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
    
    .btn.loading {
        position: relative;
        color: transparent;
    }
    
    .btn.loading::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid currentColor;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }
`;
document.head.appendChild(style);
</script>
