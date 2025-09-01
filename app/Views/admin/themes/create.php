<?php
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<div class="page-header">
    <div class="page-header-content">
        <nav class="breadcrumb">
            <a href="/admin" class="breadcrumb-item">
                <i class="icon-home"></i>
                Dashboard
            </a>
            <i class="breadcrumb-separator"></i>
            <a href="/admin/themes" class="breadcrumb-item">Thématiques</a>
            <i class="breadcrumb-separator"></i>
            <span class="breadcrumb-current">Nouvelle Thématique</span>
        </nav>
        
        <h1 class="page-title">
            <i class="icon-plus"></i>
            Créer une Nouvelle Thématique
        </h1>
    </div>
</div>

<!-- Create Form -->
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">Informations de la Thématique</h2>
            <p class="form-description">
                Remplissez les informations ci-dessous pour créer une nouvelle thématique.
            </p>
        </div>
        
        <form method="POST" action="/admin/themes" class="theme-form" id="createThemeForm">
            <?= \App\Core\Auth::csrfField() ?>
            
            <!-- Theme Name -->
            <div class="form-group">
                <label for="name" class="form-label required">
                    <i class="icon-tag"></i>
                    Nom de la thématique
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>"
                       class="form-input"
                       placeholder="Ex: Innovation Technologique, Développement Durable..."
                       required
                       maxlength="150">
                <div class="form-hint">
                    Le nom doit être unique et explicite (3-150 caractères).
                </div>
            </div>
            
            <!-- Theme Description -->
            <div class="form-group">
                <label for="description" class="form-label required">
                    <i class="icon-text"></i>
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          class="form-textarea"
                          placeholder="Décrivez cette thématique en détail..."
                          rows="4"
                          required><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
                <div class="form-hint">
                    Une description claire aide les employés à comprendre le domaine d'application.
                </div>
            </div>
            
            <!-- Active Status -->
            <div class="form-group">
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               class="checkbox-input"
                               <?= ($oldInput['is_active'] ?? true) ? 'checked' : '' ?>>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <strong>Thématique active</strong>
                            <small>Les employés pourront soumettre des idées dans cette thématique</small>
                        </span>
                    </label>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <a href="/admin/themes" class="btn btn-outline">
                    <i class="icon-arrow-left"></i>
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="icon-check"></i>
                    Créer la Thématique
                </button>
            </div>
        </form>
    </div>
    
    <!-- Help Section -->
    <div class="help-card">
        <h3 class="help-title">
            <i class="icon-info"></i>
            Conseils pour créer une thématique
        </h3>
        <ul class="help-list">
            <li>
                <strong>Nom explicite :</strong> Choisissez un nom clair qui reflète le domaine d'innovation.
            </li>
            <li>
                <strong>Description détaillée :</strong> Expliquez le type d'idées attendues dans cette thématique.
            </li>
            <li>
                <strong>Statut actif :</strong> Décochez si vous voulez créer la thématique sans l'activer immédiatement.
            </li>
            <li>
                <strong>Unicité :</strong> Assurez-vous que le nom n'existe pas déjà dans le système.
            </li>
        </ul>
        
        <div class="help-note">
            <i class="icon-lightbulb"></i>
            <strong>Astuce :</strong> Une bonne thématique encourage la participation en étant suffisamment large pour accueillir diverses idées, mais assez précise pour guider les contributeurs.
        </div>
    </div>
</div>

<style>
.form-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-card,
.help-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.form-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.form-title {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-description {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    margin: 0;
}

.theme-form {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: '*';
    color: #ef4444;
    margin-left: 0.25rem;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    font-size: 1rem;
    color: #1e293b;
    background: white;
    transition: all 0.2s ease;
    resize: vertical;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    min-height: 120px;
    font-family: inherit;
    line-height: 1.6;
}

.form-hint {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
}

.checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    background: #f8fafc;
    transition: all 0.2s ease;
    flex: 1;
}

.checkbox-label:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 0.25rem;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.checkbox-input:checked + .checkbox-custom {
    background: #3b82f6;
    border-color: #3b82f6;
}

.checkbox-input:checked + .checkbox-custom::after {
    content: '✓';
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.checkbox-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.checkbox-text strong {
    color: #1e293b;
    font-weight: 600;
}

.checkbox-text small {
    color: #64748b;
    font-size: 0.875rem;
    line-height: 1.4;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.help-card {
    align-self: start;
    position: sticky;
    top: 2rem;
}

.help-title {
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding: 1.5rem 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.help-list {
    list-style: none;
    padding: 0 1.5rem;
    margin: 0;
}

.help-list li {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
    color: #4b5563;
    line-height: 1.6;
}

.help-list li:last-child {
    border-bottom: none;
}

.help-list li strong {
    color: #1e293b;
}

.help-note {
    margin: 1rem 1.5rem 1.5rem;
    padding: 1rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    border-radius: 0.75rem;
    border: 1px solid #f59e0b;
    font-size: 0.875rem;
    line-height: 1.6;
    color: #92400e;
}

.help-note i {
    margin-right: 0.5rem;
    color: #f59e0b;
}

.help-note strong {
    color: #78350f;
}

/* Breadcrumb Styles */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.breadcrumb-item {
    color: #6b7280;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: color 0.2s ease;
}

.breadcrumb-item:hover {
    color: #3b82f6;
}

.breadcrumb-separator::before {
    content: '→';
    color: #d1d5db;
    font-size: 0.75rem;
}

.breadcrumb-current {
    color: #1e293b;
    font-weight: 500;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-outline {
    background: white;
    color: #6b7280;
    border-color: #d1d5db;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
}

/* Icons */
.icon-home::before { content: '🏠'; }
.icon-plus::before { content: '➕'; }
.icon-tag::before { content: '🏷️'; }
.icon-text::before { content: '📝'; }
.icon-check::before { content: '✅'; }
.icon-arrow-left::before { content: '←'; }
.icon-info::before { content: 'ℹ️'; }
.icon-lightbulb::before { content: '💡'; }

/* Responsive Design */
@media (max-width: 1024px) {
    .form-container {
        grid-template-columns: 1fr;
    }
    
    .help-card {
        order: -1;
        position: static;
    }
}

@media (max-width: 768px) {
    .form-header {
        padding: 1.5rem 1.5rem 1rem;
    }
    
    .theme-form {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
    
    .breadcrumb {
        flex-wrap: wrap;
    }
    
    .breadcrumb-item,
    .breadcrumb-current {
        font-size: 0.75rem;
    }
}

/* Form Validation Styles */
.form-input:invalid,
.form-textarea:invalid {
    border-color: #ef4444;
    background: #fef2f2;
}

.form-input:valid,
.form-textarea:valid {
    border-color: #10b981;
}

/* Loading State */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn:disabled:hover {
    transform: none;
    box-shadow: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createThemeForm');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Form validation
    function validateForm() {
        const name = nameInput.value.trim();
        const description = descriptionInput.value.trim();
        
        const isValid = name.length >= 3 && description.length >= 10;
        submitButton.disabled = !isValid;
        
        return isValid;
    }
    
    // Real-time validation
    nameInput.addEventListener('input', validateForm);
    descriptionInput.addEventListener('input', validateForm);
    
    // Initial validation
    validateForm();
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="loading-spinner"></i> Création en cours...';
    });
    
    // Character count for description
    const charCount = document.createElement('div');
    charCount.className = 'char-count';
    charCount.style.cssText = 'font-size: 0.75rem; color: #6b7280; text-align: right; margin-top: 0.25rem;';
    descriptionInput.parentNode.appendChild(charCount);
    
    function updateCharCount() {
        const length = descriptionInput.value.length;
        charCount.textContent = `${length} caractères`;
        charCount.style.color = length < 10 ? '#ef4444' : '#6b7280';
    }
    
    descriptionInput.addEventListener('input', updateCharCount);
    updateCharCount();
});
</script>
