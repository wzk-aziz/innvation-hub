<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="icon-reports"></i>
            Rapports et Statistiques
        </h1>
        <div class="page-actions">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle">
                    <i class="icon-download"></i>
                    Exporter
                </button>
                <div class="dropdown-menu">
                    <a href="/admin/reports/export?type=users&format=csv" class="dropdown-item">
                        <i class="icon-file"></i>
                        Utilisateurs (CSV)
                    </a>
                    <a href="/admin/reports/export?type=ideas&format=csv" class="dropdown-item">
                        <i class="icon-file"></i>
                        Idées (CSV)
                    </a>
                    <a href="/admin/reports/export?type=themes&format=csv" class="dropdown-item">
                        <i class="icon-file"></i>
                        Thématiques (CSV)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Overview -->
<div class="reports-overview">
    <div class="overview-card">
        <div class="overview-icon users">
            <i class="icon-users"></i>
        </div>
        <div class="overview-content">
            <h3>Utilisateurs</h3>
            <div class="overview-stats">
                <div class="stat-item">
                    <span class="stat-value">156</span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">12</span>
                    <span class="stat-label">Ce mois</span>
                </div>
            </div>
            <a href="/admin/reports/users" class="overview-link">
                Voir le rapport détaillé
                <i class="icon-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="overview-card">
        <div class="overview-icon ideas">
            <i class="icon-ideas"></i>
        </div>
        <div class="overview-content">
            <h3>Idées</h3>
            <div class="overview-stats">
                <div class="stat-item">
                    <span class="stat-value">342</span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">28</span>
                    <span class="stat-label">Ce mois</span>
                </div>
            </div>
            <a href="/admin/reports/ideas" class="overview-link">
                Voir le rapport détaillé
                <i class="icon-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="overview-card">
        <div class="overview-icon themes">
            <i class="icon-themes"></i>
        </div>
        <div class="overview-content">
            <h3>Thématiques</h3>
            <div class="overview-stats">
                <div class="stat-item">
                    <span class="stat-value">24</span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">18</span>
                    <span class="stat-label">Actives</span>
                </div>
            </div>
            <a href="/admin/reports/themes" class="overview-link">
                Voir le rapport détaillé
                <i class="icon-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Charts and Analytics -->
<div class="reports-grid">
    <!-- Ideas Trend Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3>Évolution des Idées</h3>
            <div class="chart-actions">
                <select class="chart-period">
                    <option value="7">7 derniers jours</option>
                    <option value="30" selected>30 derniers jours</option>
                    <option value="90">3 derniers mois</option>
                </select>
            </div>
        </div>
        <div class="chart-body">
            <div class="chart-placeholder">
                <canvas id="ideasTrendChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Status Distribution -->
    <div class="chart-card">
        <div class="chart-header">
            <h3>Répartition par Statut</h3>
        </div>
        <div class="chart-body">
            <div class="status-distribution">
                <div class="status-item">
                    <div class="status-bar">
                        <div class="status-fill submitted" style="width: 45%"></div>
                    </div>
                    <div class="status-info">
                        <span class="status-label">Soumises</span>
                        <span class="status-count">154 (45%)</span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-bar">
                        <div class="status-fill under_review" style="width: 30%"></div>
                    </div>
                    <div class="status-info">
                        <span class="status-label">En révision</span>
                        <span class="status-count">103 (30%)</span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-bar">
                        <div class="status-fill accepted" style="width: 20%"></div>
                    </div>
                    <div class="status-info">
                        <span class="status-label">Acceptées</span>
                        <span class="status-count">68 (20%)</span>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-bar">
                        <div class="status-fill rejected" style="width: 5%"></div>
                    </div>
                    <div class="status-info">
                        <span class="status-label">Rejetées</span>
                        <span class="status-count">17 (5%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Contributors -->
    <div class="chart-card">
        <div class="chart-header">
            <h3>Top Contributeurs</h3>
        </div>
        <div class="chart-body">
            <div class="contributors-list">
                <div class="contributor-item">
                    <div class="contributor-avatar">JD</div>
                    <div class="contributor-info">
                        <span class="contributor-name">Jean Dupont</span>
                        <span class="contributor-role">Salarié</span>
                    </div>
                    <div class="contributor-count">23 idées</div>
                </div>
                <div class="contributor-item">
                    <div class="contributor-avatar">MS</div>
                    <div class="contributor-info">
                        <span class="contributor-name">Marie Sophie</span>
                        <span class="contributor-role">Salarié</span>
                    </div>
                    <div class="contributor-count">18 idées</div>
                </div>
                <div class="contributor-item">
                    <div class="contributor-avatar">PL</div>
                    <div class="contributor-info">
                        <span class="contributor-name">Pierre Legrand</span>
                        <span class="contributor-role">Évaluateur</span>
                    </div>
                    <div class="contributor-count">15 idées</div>
                </div>
                <div class="contributor-item">
                    <div class="contributor-avatar">AL</div>
                    <div class="contributor-info">
                        <span class="contributor-name">Alice Laurent</span>
                        <span class="contributor-role">Salarié</span>
                    </div>
                    <div class="contributor-count">12 idées</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Popular Themes -->
    <div class="chart-card">
        <div class="chart-header">
            <h3>Thématiques Populaires</h3>
        </div>
        <div class="chart-body">
            <div class="themes-ranking">
                <div class="theme-rank-item">
                    <div class="rank-position">1</div>
                    <div class="theme-info">
                        <span class="theme-name">Innovation Technologique</span>
                        <div class="theme-bar">
                            <div class="theme-fill" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="theme-count">67 idées</div>
                </div>
                <div class="theme-rank-item">
                    <div class="rank-position">2</div>
                    <div class="theme-info">
                        <span class="theme-name">Amélioration Processus</span>
                        <div class="theme-bar">
                            <div class="theme-fill" style="width: 70%"></div>
                        </div>
                    </div>
                    <div class="theme-count">55 idées</div>
                </div>
                <div class="theme-rank-item">
                    <div class="rank-position">3</div>
                    <div class="theme-info">
                        <span class="theme-name">Développement Durable</span>
                        <div class="theme-bar">
                            <div class="theme-fill" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="theme-count">47 idées</div>
                </div>
                <div class="theme-rank-item">
                    <div class="rank-position">4</div>
                    <div class="theme-info">
                        <span class="theme-name">Ressources Humaines</span>
                        <div class="theme-bar">
                            <div class="theme-fill" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="theme-count">35 idées</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reports-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.overview-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    transition: all 0.2s ease;
}

.overview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.overview-icon {
    width: 4rem;
    height: 4rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.overview-icon.users {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.overview-icon.ideas {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.overview-icon.themes {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.overview-content {
    flex: 1;
}

.overview-content h3 {
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.overview-stats {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.overview-stats .stat-item {
    display: flex;
    flex-direction: column;
}

.overview-stats .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.overview-stats .stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.overview-link {
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: color 0.2s ease;
}

.overview-link:hover {
    color: #1d4ed8;
}

.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.chart-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.chart-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-header h3 {
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
}

.chart-period {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
}

.chart-body {
    padding: 1.5rem;
}

.chart-placeholder {
    background: #f9fafb;
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    padding: 2rem;
    text-align: center;
    color: #6b7280;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Status Distribution */
.status-distribution {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-bar {
    flex: 1;
    height: 0.75rem;
    background: #f3f4f6;
    border-radius: 0.375rem;
    overflow: hidden;
}

.status-fill {
    height: 100%;
    border-radius: 0.375rem;
    transition: width 0.3s ease;
}

.status-fill.submitted {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

.status-fill.under_review {
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

.status-fill.accepted {
    background: linear-gradient(90deg, #10b981, #059669);
}

.status-fill.rejected {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.status-info {
    display: flex;
    flex-direction: column;
    min-width: 120px;
}

.status-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.status-count {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Contributors */
.contributors-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contributor-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 0.5rem;
    transition: background 0.2s ease;
}

.contributor-item:hover {
    background: #f9fafb;
}

.contributor-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
}

.contributor-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.contributor-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.contributor-role {
    font-size: 0.75rem;
    color: #6b7280;
}

.contributor-count {
    font-size: 0.875rem;
    font-weight: 600;
    color: #3b82f6;
}

/* Theme Rankings */
.themes-ranking {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.theme-rank-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.rank-position {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
    flex-shrink: 0;
}

.theme-info {
    flex: 1;
}

.theme-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    display: block;
    margin-bottom: 0.5rem;
}

.theme-bar {
    width: 100%;
    height: 0.5rem;
    background: #f3f4f6;
    border-radius: 0.25rem;
    overflow: hidden;
}

.theme-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    border-radius: 0.25rem;
    transition: width 0.3s ease;
}

.theme-count {
    font-size: 0.875rem;
    font-weight: 600;
    color: #3b82f6;
    min-width: 60px;
    text-align: right;
}

/* Icons */
.icon-download::before { content: "⬇️"; }
.icon-file::before { content: "📄"; }

@media (max-width: 768px) {
    .reports-overview {
        grid-template-columns: 1fr;
    }
    
    .reports-grid {
        grid-template-columns: 1fr;
    }
    
    .overview-card {
        flex-direction: column;
        text-align: center;
    }
    
    .overview-stats {
        justify-content: center;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

<script>
// Mock chart data - in a real application, this would come from the backend
document.addEventListener('DOMContentLoaded', function() {
    // Simple chart placeholder - you can integrate Chart.js or similar library
    const canvas = document.getElementById('ideasTrendChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#f3f4f6';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#6b7280';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Graphique des tendances', canvas.width/2, canvas.height/2);
        ctx.fillText('(Intégrer Chart.js pour les vrais graphiques)', canvas.width/2, canvas.height/2 + 25);
    }
});
</script>
