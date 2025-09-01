<div class="hero-section">
    <div class="hero-content">
        <h1>Bienvenue sur la Plateforme d'Idées</h1>
        <p class="hero-subtitle">
            Partagez vos idées, collaborez avec vos collègues et contribuez à l'innovation de votre entreprise
        </p>
        
        <?php if (\App\Core\Auth::isAuthenticated()): ?>
            <div class="user-welcome">
                <h2>Bonjour, <?= htmlspecialchars(\App\Core\Auth::user()['first_name']) ?> !</h2>
                <p>Vous êtes connecté en tant que <span class="role-badge role-<?= \App\Core\Auth::user()['role'] ?>"><?= ucfirst(\App\Core\Auth::user()['role']) ?></span></p>
                
                <div class="quick-actions">
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                        <a href="/admin" class="btn btn-primary">Tableau de Bord Admin</a>
                        <a href="/admin/users" class="btn btn-outline-primary">Gérer les Utilisateurs</a>
                        <a href="/admin/themes" class="btn btn-outline-primary">Gérer les Thématiques</a>
                    <?php elseif (\App\Core\Auth::hasRole('salarie')): ?>
                        <a href="/salarie/ideas" class="btn btn-primary">Mes Idées</a>
                        <a href="/salarie/ideas/create" class="btn btn-success">Proposer une Idée</a>
                    <?php elseif (\App\Core\Auth::hasRole('evaluateur')): ?>
                        <a href="/evaluateur/ideas" class="btn btn-primary">Idées à Évaluer</a>
                        <a href="/evaluateur/evaluations" class="btn btn-outline-primary">Mes Évaluations</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-actions">
                <a href="/login" class="btn btn-primary btn-lg">Se Connecter</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="features-section">
    <div class="container">
        <h2>Fonctionnalités de la Plateforme</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">💡</div>
                <h3>Proposer des Idées</h3>
                <p>Soumettez vos idées innovantes et contribuez à l'amélioration de l'entreprise</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⭐</div>
                <h3>Évaluer les Idées</h3>
                <p>Participez à l'évaluation des idées proposées par vos collègues</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Suivi et Statistiques</h3>
                <p>Suivez le statut de vos idées et consultez les statistiques détaillées</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Gestion Sécurisée</h3>
                <p>Interface d'administration pour gérer les utilisateurs et les thématiques</p>
            </div>
        </div>
    </div>
</div>

<?php if (\App\Core\Auth::isAuthenticated()): ?>
<div class="stats-section">
    <div class="container">
        <h2>Aperçu Général</h2>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">
                    <?php
                    $ideaModel = new App\Models\Idea();
                    echo $ideaModel->count();
                    ?>
                </div>
                <div class="stat-label">Idées Totales</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">
                    <?php echo $ideaModel->countByStatus('pending'); ?>
                </div>
                <div class="stat-label">En Attente</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">
                    <?php echo $ideaModel->countByStatus('approved'); ?>
                </div>
                <div class="stat-label">Approuvées</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">
                    <?php
                    $themeModel = new App\Models\Theme();
                    echo $themeModel->count();
                    ?>
                </div>
                <div class="stat-label">Thématiques</div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    line-height: 1.6;
}

.user-welcome {
    background: rgba(255, 255, 255, 0.1);
    padding: 2rem;
    border-radius: 12px;
    margin: 2rem 0;
    backdrop-filter: blur(10px);
}

.user-welcome h2 {
    margin-bottom: 0.5rem;
    font-size: 1.8rem;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.role-admin {
    background-color: #dc3545;
    color: white;
}

.role-evaluateur {
    background-color: #ffc107;
    color: #212529;
}

.role-salarie {
    background-color: #007bff;
    color: white;
}

.quick-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 1.5rem;
}

.auth-actions {
    margin-top: 2rem;
}

.features-section {
    padding: 4rem 0;
    background: #f8f9fa;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.features-section h2 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #2c3e50;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.feature-card h3 {
    margin-bottom: 1rem;
    color: #2c3e50;
    font-size: 1.3rem;
}

.feature-card p {
    color: #6c757d;
    line-height: 1.6;
}

.stats-section {
    padding: 4rem 0;
    background: white;
}

.stats-section h2 {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #2c3e50;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.stat-item {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-block;
    cursor: pointer;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #1e7e34;
    transform: translateY(-2px);
}

.btn-outline-primary {
    background: transparent;
    color: #007bff;
    border: 2px solid #007bff;
}

.btn-outline-primary:hover {
    background: #007bff;
    color: white;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .quick-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .quick-actions .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .hero-content {
        padding: 0 1rem;
    }
    
    .container {
        padding: 0 1rem;
    }
}
</style>
