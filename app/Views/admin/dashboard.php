<div class="dashboard-header">
    <h1>Tableau de Bord Administrateur</h1>
    <p>Bienvenue, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="icon-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['total_users'] ?></div>
            <div class="stat-label">Utilisateurs</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="icon-themes"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['total_themes'] ?></div>
            <div class="stat-label">Thématiques</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="icon-ideas"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['total_ideas'] ?></div>
            <div class="stat-label">Idées</div>
        </div>
    </div>
    
    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="icon-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['pending_ideas'] ?></div>
            <div class="stat-label">Soumises</div>
        </div>
    </div>
    
    <div class="stat-card review">
        <div class="stat-icon">
            <i class="icon-eye"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['under_review_ideas'] ?></div>
            <div class="stat-label">En Révision</div>
        </div>
    </div>
    
    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="icon-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['approved_ideas'] ?></div>
            <div class="stat-label">Acceptées</div>
        </div>
    </div>
    
    <div class="stat-card rejected">
        <div class="stat-icon">
            <i class="icon-times"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?= $stats['rejected_ideas'] ?></div>
            <div class="stat-label">Rejetées</div>
        </div>
    </div>
</div>

<div class="dashboard-content">
    <div class="content-section">
        <h2>Idées Récentes</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Thématique</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_ideas)): ?>
                        <?php foreach ($recent_ideas as $idea): ?>
                        <tr>
                            <td>
                                <a href="/admin/ideas/<?= $idea['id'] ?>" class="idea-title">
                                    <?= htmlspecialchars($idea['title']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($idea['user_name'] ?? 'Utilisateur supprimé') ?></td>
                            <td><?= htmlspecialchars($idea['theme_name'] ?? 'N/A') ?></td>
                            <td>
                                <span class="status-badge status-<?= $idea['status'] ?>">
                                    <?= ucfirst($idea['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($idea['created_at'])) ?></td>
                            <td>
                                <a href="/admin/ideas/<?= $idea['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucune idée récente</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="/admin/ideas" class="btn btn-primary">Voir toutes les idées</a>
        </div>
    </div>
    
    <div class="content-section">
        <h2>Utilisateurs Récents</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_users)): ?>
                        <?php foreach ($recent_users as $recentUser): ?>
                        <tr>
                            <td><?= htmlspecialchars($recentUser['first_name'] . ' ' . $recentUser['last_name']) ?></td>
                            <td><?= htmlspecialchars($recentUser['email']) ?></td>
                            <td>
                                <span class="role-badge role-<?= $recentUser['role'] ?>">
                                    <?= ucfirst($recentUser['role']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($recentUser['created_at'])) ?></td>
                            <td>
                                <a href="/admin/users/<?= $recentUser['id'] ?>/edit" class="btn btn-sm btn-outline-primary">Modifier</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucun utilisateur récent</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="/admin/users" class="btn btn-primary">Voir tous les utilisateurs</a>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.dashboard-header p {
    color: #7f8c8d;
    margin: 0;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    border-left: 4px solid #3498db;
}

.stat-card.pending {
    border-left-color: #f39c12;
}

.stat-card.approved {
    border-left-color: #27ae60;
}

.stat-card.rejected {
    border-left-color: #e74c3c;
}

.stat-icon {
    font-size: 2rem;
    margin-right: 1rem;
    color: #7f8c8d;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.dashboard-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.content-section {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.content-section h2 {
    margin-bottom: 1rem;
    color: #2c3e50;
    border-bottom: 2px solid #ecf0f1;
    padding-bottom: 0.5rem;
}

.idea-title {
    text-decoration: none;
    color: #3498db;
    font-weight: 500;
}

.idea-title:hover {
    text-decoration: underline;
}

.status-badge, .role-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-approved {
    background-color: #d4edda;
    color: #155724;
}

.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.role-admin {
    background-color: #f8d7da;
    color: #721c24;
}

.role-evaluateur {
    background-color: #fff3cd;
    color: #856404;
}

.role-salarie {
    background-color: #cce5ff;
    color: #004085;
}

/* Icons using CSS (can be replaced with icon font) */
.stat-icon i::before {
    font-style: normal;
}

.icon-users::before { content: "👥"; }
.icon-themes::before { content: "🏷️"; }
.icon-ideas::before { content: "💡"; }
.icon-clock::before { content: "⏰"; }
.icon-check::before { content: "✅"; }
.icon-times::before { content: "❌"; }

@media (max-width: 768px) {
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }
}
</style>
