<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-settings"></i>
            Paramètres du Système
        </h1>
    </div>
</div>

<!-- Settings Navigation -->
<div class="settings-nav">
    <a href="/admin/settings/general" class="settings-nav-item active">
        <i class="icon-general"></i>
        <span>Général</span>
    </a>
    <a href="/admin/settings/security" class="settings-nav-item">
        <i class="icon-security"></i>
        <span>Sécurité</span>
    </a>
    <a href="/admin/settings/email" class="settings-nav-item">
        <i class="icon-email"></i>
        <span>Email</span>
    </a>
    <a href="/admin/settings/backup" class="settings-nav-item">
        <i class="icon-backup"></i>
        <span>Sauvegarde</span>
    </a>
</div>

<!-- Settings Overview Cards -->
<div class="settings-overview">
    <div class="setting-card">
        <div class="setting-icon general">
            <i class="icon-general"></i>
        </div>
        <div class="setting-content">
            <h3>Paramètres Généraux</h3>
            <p>Configuration de base de l'application, nom, description et options générales.</p>
            <div class="setting-status">
                <span class="status-badge status-configured">Configuré</span>
            </div>
        </div>
        <div class="setting-actions">
            <a href="/admin/settings/general" class="btn btn-outline">
                <i class="icon-edit"></i>
                Modifier
            </a>
        </div>
    </div>
    
    <div class="setting-card">
        <div class="setting-icon security">
            <i class="icon-security"></i>
        </div>
        <div class="setting-content">
            <h3>Sécurité</h3>
            <p>Paramètres de sécurité, mots de passe, sessions et authentification.</p>
            <div class="setting-status">
                <span class="status-badge status-warning">À réviser</span>
            </div>
        </div>
        <div class="setting-actions">
            <a href="/admin/settings/security" class="btn btn-outline">
                <i class="icon-edit"></i>
                Modifier
            </a>
        </div>
    </div>
    
    <div class="setting-card">
        <div class="setting-icon email">
            <i class="icon-email"></i>
        </div>
        <div class="setting-content">
            <h3>Configuration Email</h3>
            <p>Paramètres SMTP pour l'envoi d'emails et notifications.</p>
            <div class="setting-status">
                <span class="status-badge status-pending">Non configuré</span>
            </div>
        </div>
        <div class="setting-actions">
            <a href="/admin/settings/email" class="btn btn-outline">
                <i class="icon-edit"></i>
                Configurer
            </a>
        </div>
    </div>
    
    <div class="setting-card">
        <div class="setting-icon backup">
            <i class="icon-backup"></i>
        </div>
        <div class="setting-content">
            <h3>Sauvegarde</h3>
            <p>Gestion des sauvegardes automatiques et restauration de données.</p>
            <div class="setting-status">
                <span class="status-badge status-configured">Actif</span>
            </div>
        </div>
        <div class="setting-actions">
            <a href="/admin/settings/backup" class="btn btn-outline">
                <i class="icon-backup"></i>
                Gérer
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <div class="quick-actions-header">
        <h2>Actions Rapides</h2>
        <p>Tâches de maintenance courantes</p>
    </div>
    
    <div class="actions-grid">
        <div class="action-item">
            <div class="action-icon">
                <i class="icon-cache"></i>
            </div>
            <div class="action-content">
                <h4>Vider le Cache</h4>
                <p>Supprime les fichiers de cache temporaires</p>
            </div>
            <button class="btn btn-secondary action-btn" onclick="clearCache()">
                <i class="icon-clear"></i>
                Vider
            </button>
        </div>
        
        <div class="action-item">
            <div class="action-icon">
                <i class="icon-backup"></i>
            </div>
            <div class="action-content">
                <h4>Sauvegarde Rapide</h4>
                <p>Créer une sauvegarde immédiate de la base de données</p>
            </div>
            <form method="POST" action="/admin/settings/backup/create" class="action-form">
                <?= \App\Core\Auth::csrfField() ?>
                <button type="submit" class="btn btn-primary action-btn">
                    <i class="icon-download"></i>
                    Sauvegarder
                </button>
            </form>
        </div>
        
        <div class="action-item">
            <div class="action-icon">
                <i class="icon-maintenance"></i>
            </div>
            <div class="action-content">
                <h4>Mode Maintenance</h4>
                <p>Activer/désactiver le mode maintenance</p>
            </div>
            <button class="btn btn-warning action-btn" onclick="toggleMaintenance()">
                <i class="icon-toggle"></i>
                Basculer
            </button>
        </div>
        
        <div class="action-item">
            <div class="action-icon">
                <i class="icon-logs"></i>
            </div>
            <div class="action-content">
                <h4>Voir les Logs</h4>
                <p>Consulter les journaux système et erreurs</p>
            </div>
            <a href="/admin/logs" class="btn btn-outline action-btn">
                <i class="icon-eye"></i>
                Consulter
            </a>
        </div>
    </div>
</div>

<!-- System Information -->
<div class="system-info">
    <div class="info-header">
        <h2>Informations Système</h2>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Version de l'Application</span>
            <span class="info-value">1.0.0</span>
        </div>
        <div class="info-item">
            <span class="info-label">Version PHP</span>
            <span class="info-value"><?= PHP_VERSION ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Serveur Web</span>
            <span class="info-value"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu' ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Base de Données</span>
            <span class="info-value">MySQL 8.0</span>
        </div>
        <div class="info-item">
            <span class="info-label">Espace Disque</span>
            <span class="info-value">2.4 GB utilisés / 10 GB</span>
        </div>
        <div class="info-item">
            <span class="info-label">Dernière Sauvegarde</span>
            <span class="info-value">Hier 23:00</span>
        </div>
    </div>
</div>

<style>
.settings-nav {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    background: white;
    padding: 0.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.settings-nav-item {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.settings-nav-item:hover {
    background: #f3f4f6;
    color: #374151;
}

.settings-nav-item.active {
    background: #3b82f6;
    color: white;
}

.settings-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.setting-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    transition: all 0.2s ease;
}

.setting-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.setting-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.setting-icon.general {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.setting-icon.security {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.setting-icon.email {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.setting-icon.backup {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.setting-content {
    flex: 1;
}

.setting-content h3 {
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.setting-content p {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.setting-status {
    margin-bottom: 0.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-configured {
    background: #d1fae5;
    color: #065f46;
}

.status-warning {
    background: #fef3c7;
    color: #92400e;
}

.status-pending {
    background: #fee2e2;
    color: #991b1b;
}

.setting-actions {
    display: flex;
    align-items: flex-end;
}

.quick-actions {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 2rem;
    margin-bottom: 2rem;
}

.quick-actions-header {
    margin-bottom: 1.5rem;
}

.quick-actions-header h2 {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.quick-actions-header p {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    transition: all 0.2s ease;
}

.action-item:hover {
    border-color: #3b82f6;
    background: #f8fafc;
}

.action-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #6b7280;
    flex-shrink: 0;
}

.action-content {
    flex: 1;
}

.action-content h4 {
    color: #1e293b;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.action-content p {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0;
}

.action-btn {
    flex-shrink: 0;
}

.action-form {
    margin: 0;
}

.system-info {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 2rem;
}

.info-header {
    margin-bottom: 1.5rem;
}

.info-header h2 {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.5rem;
    border-left: 4px solid #3b82f6;
}

.info-label {
    color: #374151;
    font-size: 0.875rem;
    font-weight: 500;
}

.info-value {
    color: #1e293b;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Icons */
.icon-general::before { content: "⚙️"; }
.icon-security::before { content: "🔒"; }
.icon-email::before { content: "📧"; }
.icon-backup::before { content: "💾"; }
.icon-cache::before { content: "🗃️"; }
.icon-clear::before { content: "🧹"; }
.icon-maintenance::before { content: "🔧"; }
.icon-toggle::before { content: "🔄"; }
.icon-logs::before { content: "📋"; }

@media (max-width: 768px) {
    .settings-nav {
        flex-direction: column;
    }
    
    .settings-overview {
        grid-template-columns: 1fr;
    }
    
    .setting-card {
        flex-direction: column;
        text-align: center;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .action-item {
        flex-direction: column;
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<script>
function clearCache() {
    if (confirm('Êtes-vous sûr de vouloir vider le cache ?')) {
        // Add AJAX call to clear cache
        alert('Cache vidé avec succès !');
    }
}

function toggleMaintenance() {
    if (confirm('Basculer le mode maintenance ?')) {
        // Add AJAX call to toggle maintenance mode
        alert('Mode maintenance basculé !');
    }
}
</script>
