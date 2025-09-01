<?php
/**
 * 403 Forbidden Error View
 */
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-code">403</div>
        <div class="error-title">Accès Interdit</div>
        <div class="error-message">
            <?= isset($message) ? htmlspecialchars($message) : 'Vous n\'avez pas l\'autorisation d\'accéder à cette ressource.' ?>
        </div>
        <div class="error-actions">
            <a href="/login" class="btn btn-primary">Se Connecter</a>
            <a href="/" class="btn btn-secondary">Retour à l'Accueil</a>
        </div>
    </div>
</div>

<style>
.error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 2rem;
}

.error-content {
    text-align: center;
    max-width: 500px;
}

.error-code {
    font-size: 6rem;
    font-weight: bold;
    color: #dc3545;
    margin-bottom: 1rem;
}

.error-title {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.error-message {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    line-height: 1.5;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
}
</style>
