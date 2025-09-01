<?php 
$title = 'Modifier la thématique - ' . htmlspecialchars($theme['name']);
$currentPage = 'themes';
?>

<div class="admin-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="/admin">Tableau de bord</a>
            <span class="separator">></span>
            <a href="/admin/themes">Thématiques</a>
            <span class="separator">></span>
            <span class="current">Modifier</span>
        </nav>
        <h1><i class="icon-edit"></i> Modifier la thématique</h1>
        <p>Modifiez les informations de la thématique</p>
    </div>
</div>

<div class="admin-content">
    <div class="container">
        <div class="form-container">
            <form method="POST" action="/admin/themes/<?= $theme['id'] ?>" class="theme-form" id="editThemeForm">
                <?= \App\Core\Auth::csrfField() ?>
                
                <div class="form-group">
                    <label for="name" class="form-label required">
                        <i class="icon-tag"></i>
                        Nom de la thématique
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control" 
                        value="<?= htmlspecialchars($theme['name']) ?>"
                        required
                        maxlength="100"
                        placeholder="Ex: Développement Web, Intelligence Artificielle..."
                    >
                    <div class="form-help">
                        <span class="char-count" id="nameCharCount">
                            <?= strlen($theme['name']) ?>/100 caractères
                        </span>
                    </div>
                    <div class="invalid-feedback" id="name-error"></div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label required">
                        <i class="icon-text"></i>
                        Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control" 
                        rows="4"
                        required
                        maxlength="500"
                        placeholder="Décrivez brièvement cette thématique et son objectif..."
                    ><?= htmlspecialchars($theme['description']) ?></textarea>
                    <div class="form-help">
                        <span class="char-count" id="descCharCount">
                            <?= strlen($theme['description']) ?>/500 caractères
                        </span>
                        <span class="help-text">
                            Une description claire aide les utilisateurs à comprendre le domaine de cette thématique.
                        </span>
                    </div>
                    <div class="invalid-feedback" id="description-error"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="icon-eye"></i>
                        Statut
                    </label>
                    <div class="form-control-group">
                        <label class="radio-label">
                            <input type="radio" name="is_active" value="1" <?= $theme['is_active'] ? 'checked' : '' ?> required>
                            <span class="radio-custom"></span>
                            <span class="radio-text">
                                <strong>Actif</strong>
                                <small>Les utilisateurs peuvent proposer des idées dans cette thématique</small>
                            </span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="is_active" value="0" <?= !$theme['is_active'] ? 'checked' : '' ?> required>
                            <span class="radio-custom"></span>
                            <span class="radio-text">
                                <strong>Inactif</strong>
                                <small>Cette thématique n'accepte plus de nouvelles idées</small>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <div class="action-group">
                        <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">
                            <i class="icon-check"></i>
                            <span id="saveBtnText">Sauvegarder les modifications</span>
                        </button>
                        <a href="/admin/themes" class="btn btn-secondary btn-lg">
                            <i class="icon-arrow-left"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="info-panel">
            <div class="panel">
                <h3><i class="icon-info"></i> Informations</h3>
                <div class="info-item">
                    <strong>Créée le :</strong>
                    <?= date('d/m/Y à H:i', strtotime($theme['created_at'])) ?>
                </div>
                <?php if (isset($theme['ideas_count'])): ?>
                <div class="info-item">
                    <strong>Nombre d'idées :</strong>
                    <?= $theme['ideas_count'] ?? 0 ?> idée(s)
                </div>
                <?php endif; ?>
            </div>

            <div class="panel">
                <h3><i class="icon-lightbulb"></i> Conseils</h3>
                <ul class="help-list">
                    <li>Choisissez un nom court et explicite</li>
                    <li>La description doit aider les utilisateurs à comprendre le domaine</li>
                    <li>Une thématique inactive n'accepte plus de nouvelles idées</li>
                    <li>Vous pouvez réactiver une thématique à tout moment</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 800px;
    margin: 0 auto;
}

.theme-form {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-label.required::after {
    content: "*";
    color: #ef4444;
    margin-left: 0.25rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.15s ease-in-out;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.radio-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    cursor: pointer;
    transition: border-color 0.15s ease-in-out;
}

.radio-label:hover {
    border-color: #d1d5db;
}

.radio-label:has(input:checked) {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
    margin-top: 2px;
}

.radio-label input[type="radio"] {
    display: none;
}

.radio-label input[type="radio"]:checked + .radio-custom {
    border-color: #3b82f6;
}

.radio-label input[type="radio"]:checked + .radio-custom::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background-color: #3b82f6;
    border-radius: 50%;
}

.radio-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.radio-text strong {
    font-weight: 600;
    color: #374151;
}

.radio-text small {
    color: #6b7280;
    font-size: 0.875rem;
}

.form-help {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.char-count {
    font-weight: 500;
}

.help-text {
    font-style: italic;
}

.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.action-group {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background-color: #4b5563;
}

.info-panel {
    max-width: 800px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.panel {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.panel h3 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    color: #374151;
    font-size: 1.1rem;
}

.info-item {
    margin-bottom: 0.75rem;
    color: #6b7280;
}

.info-item strong {
    color: #374151;
}

.help-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-list li {
    padding: 0.5rem 0;
    color: #6b7280;
    border-bottom: 1px solid #f3f4f6;
}

.help-list li:last-child {
    border-bottom: none;
}

.help-list li::before {
    content: "•";
    color: #3b82f6;
    margin-right: 0.5rem;
}

.invalid-feedback {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}

@media (max-width: 768px) {
    .info-panel {
        grid-template-columns: 1fr;
    }
    
    .action-group {
        flex-direction: column;
    }
    
    .theme-form {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const nameCharCount = document.getElementById('nameCharCount');
    const descCharCount = document.getElementById('descCharCount');
    const form = document.getElementById('editThemeForm');
    const saveBtn = document.getElementById('saveBtn');
    const saveBtnText = document.getElementById('saveBtnText');

    // Character counting
    function updateCharCount(input, counter, max) {
        const current = input.value.length;
        counter.textContent = `${current}/${max} caractères`;
        
        if (current > max * 0.9) {
            counter.style.color = '#ef4444';
        } else if (current > max * 0.7) {
            counter.style.color = '#f59e0b';
        } else {
            counter.style.color = '#6b7280';
        }
    }

    nameInput.addEventListener('input', function() {
        updateCharCount(this, nameCharCount, 100);
    });

    descInput.addEventListener('input', function() {
        updateCharCount(this, descCharCount, 500);
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        saveBtn.disabled = true;
        saveBtnText.textContent = 'Sauvegarde en cours...';
        saveBtn.classList.add('loading');
    });

    // Real-time validation
    nameInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value.length < 3) {
            showError('name', 'Le nom doit contenir au moins 3 caractères');
        } else {
            hideError('name');
        }
    });

    descInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value.length < 10) {
            showError('description', 'La description doit contenir au moins 10 caractères');
        } else {
            hideError('description');
        }
    });

    function showError(field, message) {
        const errorDiv = document.getElementById(field + '-error');
        const input = document.getElementById(field);
        
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        input.style.borderColor = '#ef4444';
    }

    function hideError(field) {
        const errorDiv = document.getElementById(field + '-error');
        const input = document.getElementById(field);
        
        errorDiv.style.display = 'none';
        input.style.borderColor = '#e5e7eb';
    }
});
</script>
