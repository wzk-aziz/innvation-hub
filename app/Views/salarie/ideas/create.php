<div class="create-idea-container">
    <div class="page-header">
        <div class="header-content">
            <a href="/salarie/ideas" class="back-link">
                <i class="icon-arrow-left"></i>
                Retour à mes idées
            </a>
            <h1 class="page-title">
                <i class="icon-lightbulb"></i>
                Proposer une nouvelle idée
            </h1>
            <p class="page-subtitle">
                Partagez votre innovation et contribuez à l'amélioration de l'entreprise
            </p>
        </div>
    </div>

    <div class="form-container">
        <form id="createIdeaForm" action="/salarie/ideas" method="POST" class="idea-form">
            <?= csrf_field() ?>
            
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
                    <select id="theme_id" name="theme_id" class="form-select" required>
                        <option value="">Sélectionnez une thématique...</option>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?= $theme['id'] ?>" <?= old('theme_id') == $theme['id'] ? 'selected' : '' ?>>
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
                        value="<?= old('title') ?>"
                        placeholder="Ex: Amélioration du système de gestion des commandes"
                        maxlength="255"
                        required
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
                        placeholder="Décrivez votre idée en détail :
- Quel problème résout-elle ?
- Comment fonctionne-t-elle ?
- Quels sont les bénéfices attendus ?
- Comment l'implémenter ?"
                        required
                    ><?= old('description') ?></textarea>
                    <small class="form-help">
                        Expliquez votre idée de manière claire et complète. Plus vous donnez de détails, plus il sera facile pour les évaluateurs de comprendre votre proposition.
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
                        placeholder="Décrivez l'impact que pourrait avoir votre idée :
- Amélioration de l'efficacité
- Réduction des coûts
- Amélioration de la satisfaction client
- Innovation technologique..."
                    ><?= old('expected_impact') ?></textarea>
                    <small class="form-help">
                        Expliquez les bénéfices concrets que votre idée pourrait apporter à l'entreprise.
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
                        placeholder="Listez les ressources qui seraient nécessaires :
- Budget estimé
- Temps de développement
- Équipe requise
- Technologies/outils nécessaires..."
                    ><?= old('required_resources') ?></textarea>
                    <small class="form-help">
                        Aidez l'équipe d'évaluation à comprendre les moyens nécessaires pour réaliser votre idée.
                    </small>
                    <?php if (isset($errors['required_resources'])): ?>
                        <span class="error-message"><?= $errors['required_resources'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="actions-left">
                    <button type="button" class="btn btn-outline" onclick="saveDraft()">
                        <i class="icon-save"></i>
                        Sauvegarder comme brouillon
                    </button>
                </div>
                <div class="actions-right">
                    <a href="/salarie/ideas" class="btn btn-secondary">
                        <i class="icon-x"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-send"></i>
                        Soumettre l'idée
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tips Sidebar -->
<div class="tips-sidebar">
    <div class="tips-card">
        <h4>
            <i class="icon-help-circle"></i>
            Conseils pour une bonne idée
        </h4>
        <ul class="tips-list">
            <li>
                <strong>Soyez spécifique :</strong> Plus votre idée est détaillée, mieux elle sera comprise et évaluée.
            </li>
            <li>
                <strong>Pensez aux bénéfices :</strong> Expliquez clairement comment votre idée peut améliorer l'entreprise.
            </li>
            <li>
                <strong>Considérez la faisabilité :</strong> Réfléchissez aux ressources nécessaires et à la réalisation pratique.
            </li>
            <li>
                <strong>Restez ouvert :</strong> Votre idée pourra être discutée et améliorée avec les évaluateurs.
            </li>
        </ul>
    </div>

    <div class="tips-card">
        <h4>
            <i class="icon-chart"></i>
            Processus d'évaluation
        </h4>
        <div class="process-steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Soumission</strong>
                    <p>Votre idée est enregistrée</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Révision</strong>
                    <p>Les évaluateurs examinent votre proposition</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Évaluation</strong>
                    <p>Attribution d'une note et de commentaires</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <strong>Décision</strong>
                    <p>Acceptation ou suggestions d'amélioration</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Main Container */
.create-idea-container {
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
.actions-right {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* Tips Sidebar */
.tips-sidebar {
    position: fixed;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
    width: 300px;
    z-index: 100;
}

.tips-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
}

.tips-card h4 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #2d3748;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    margin-bottom: 1rem;
    font-size: 0.85rem;
    line-height: 1.4;
    color: #4a5568;
}

.tips-list li:last-child {
    margin-bottom: 0;
}

/* Process Steps */
.process-steps {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.step {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.step-number {
    width: 24px;
    height: 24px;
    background: #667eea;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content strong {
    display: block;
    color: #2d3748;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.step-content p {
    margin: 0;
    color: #718096;
    font-size: 0.8rem;
    line-height: 1.3;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .tips-sidebar {
        display: none;
    }
}

@media (max-width: 768px) {
    .create-idea-container {
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
}

/* Loading States */
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createIdeaForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            
            // Submit the form
            setTimeout(() => {
                form.submit();
            }, 500);
        }
    });
    
    function validateForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        return isValid;
    }
    
    // Character counter for title
    const titleInput = document.getElementById('title');
    const titleGroup = titleInput.closest('.form-group');
    
    function updateCharacterCount() {
        const current = titleInput.value.length;
        const max = titleInput.getAttribute('maxlength');
        
        let counter = titleGroup.querySelector('.char-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'char-counter';
            titleGroup.appendChild(counter);
        }
        
        counter.textContent = `${current}/${max} caractères`;
        counter.style.color = current > max * 0.9 ? '#e53e3e' : '#718096';
    }
    
    titleInput.addEventListener('input', updateCharacterCount);
    updateCharacterCount();
    
    // Auto-save draft functionality
    let autoSaveTimer;
    const formInputs = form.querySelectorAll('input, textarea, select');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(autoSaveDraft, 2000);
        });
    });
    
    function autoSaveDraft() {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        localStorage.setItem('ideaDraft', JSON.stringify(data));
        showNotification('Brouillon sauvegardé automatiquement', 'success');
    }
    
    // Load draft on page load
    function loadDraft() {
        const draft = localStorage.getItem('ideaDraft');
        if (draft) {
            const data = JSON.parse(draft);
            
            Object.keys(data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && !field.value) {
                    field.value = data[key];
                }
            });
        }
    }
    
    loadDraft();
});

function saveDraft() {
    const form = document.getElementById('createIdeaForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    localStorage.setItem('ideaDraft', JSON.stringify(data));
    showNotification('Brouillon sauvegardé avec succès', 'success');
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: ${type === 'success' ? '#38a169' : '#667eea'};
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
    
    .form-input.error,
    .form-select.error,
    .form-textarea.error {
        border-color: #e53e3e;
        box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
    }
`;
document.head.appendChild(style);
</script>
