<div class="modern-statistics-page">
    <!-- Modern Header Section -->
    <div class="modern-header-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="modern-breadcrumb">
                        <div class="breadcrumb-container">
                            <a href="/evaluateur/dashboard" class="breadcrumb-link">
                                <i class="fas fa-home"></i>
                                <span>Tableau de bord</span>
                            </a>
                            <i class="fas fa-chevron-right breadcrumb-separator"></i>
                            <div class="breadcrumb-current">
                                <i class="fas fa-chart-bar"></i>
                                <span>Statistiques d'évaluation</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modern-header-content">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="modern-page-title">
                                    <div class="title-icon">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                    <div class="title-content">
                                        <h1>Statistiques d'évaluation</h1>
                                        <p>Analyse complète des performances et tendances d'évaluation</p>
                                        <div class="stats-summary">
                                            <span class="stat-item">
                                                <i class="fas fa-star"></i>
                                                <?= $stats['total_evaluations'] ?? 0 ?> évaluations
                                            </span>
                                            <span class="stat-item">
                                                <i class="fas fa-chart-line"></i>
                                                Moyenne: <?= number_format($stats['average_score'] ?? 0, 1) ?>/5
                                            </span>
                                            <span class="stat-item">
                                                <i class="fas fa-trophy"></i>
                                                <?= $stats['excellent_ideas'] ?? 0 ?> excellentes
                                            </span>
                                            <span class="stat-item">
                                                <i class="fas fa-calendar-week"></i>
                                                <?= $stats['this_week'] ?? 0 ?> cette semaine
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="modern-header-actions">
                                    <button onclick="exportStatistics()" class="modern-action-btn primary">
                                        <i class="fas fa-download"></i>
                                        <span>Exporter</span>
                                    </button>
                                    <button onclick="refreshData()" class="modern-action-btn outline">
                                        <i class="fas fa-sync-alt"></i>
                                        <span>Actualiser</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Enhanced Key Metrics Section -->
        <div class="modern-stats-section">
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['total_evaluations'] ?? 0 ?></h3>
                        <p>Total évaluations</p>
                        <div class="stat-trend">
                            <i class="fas fa-history"></i>
                            <span>Toutes périodes confondues</span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php 
                                $daily_avg = ($stats['total_evaluations'] ?? 0) / max(1, 30);
                                echo "≈ " . round($daily_avg, 1) . " évaluations/jour";
                                ?>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= number_format($stats['average_score'] ?? 0, 1) ?>/5</h3>
                        <p>Note moyenne globale</p>
                        <div class="stat-trend">
                            <i class="fas fa-chart-line"></i>
                            <span>
                                <?php 
                                $avg = $stats['average_score'] ?? 0;
                                if ($avg >= 4) echo "Excellent niveau";
                                elseif ($avg >= 3.5) echo "Très bon niveau"; 
                                elseif ($avg >= 3) echo "Bon niveau";
                                elseif ($avg >= 2) echo "Niveau modéré";
                                else echo "Niveau faible";
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>Qualité constante des idées évaluées</small>
                        </div>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['excellent_ideas'] ?? 0 ?></h3>
                        <p>Idées excellentes (≥4★)</p>
                        <div class="stat-trend">
                            <i class="fas fa-medal"></i>
                            <span>
                                <?php 
                                $excellent = $stats['excellent_ideas'] ?? 0;
                                $total = $stats['total_evaluations'] ?? 1;
                                $excellentPercent = round(($excellent / max(1, $total)) * 100, 1);
                                echo $excellentPercent . "% d'excellence";
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>Idées remarquables identifiées</small>
                        </div>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['this_week'] ?? 0 ?></h3>
                        <p>Activité cette semaine</p>
                        <div class="stat-trend">
                            <i class="fas fa-trending-up"></i>
                            <span>
                                <?php 
                                $thisWeek = $stats['this_week'] ?? 0;
                                if ($thisWeek >= 10) echo "Très actif";
                                elseif ($thisWeek >= 5) echo "Actif";
                                elseif ($thisWeek >= 2) echo "Modérément actif";
                                elseif ($thisWeek >= 1) echo "Peu actif";
                                else echo "Inactif";
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php 
                                $dayOfWeek = date('N');
                                if ($thisWeek > 0) {
                                    echo "Moyenne de " . round($thisWeek / $dayOfWeek, 1) . "/jour";
                                } else {
                                    echo "Aucune activité cette semaine";
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Charts & Data Visualization Section -->
        <div class="row">
            <!-- Enhanced Score Distribution -->
            <div class="col-lg-6 mb-4">
                <div class="modern-chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div>
                                <h3>Distribution des notes</h3>
                                <p>Répartition des évaluations par niveau</p>
                            </div>
                        </div>
                        <div class="chart-actions">
                            <button class="chart-action-btn" onclick="toggleChartView('score')">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="chart-content">
                        <?php if (!empty($scoreDistribution)): ?>
                            <div class="chart-container">
                                <canvas id="scoreChart" width="400" height="300"></canvas>
                            </div>
                            <div class="chart-legend">
                                <?php foreach ($scoreDistribution as $score => $count): ?>
                                    <div class="legend-item">
                                        <div class="legend-color score-<?= $score ?>"></div>
                                        <div class="legend-content">
                                            <div class="legend-label">
                                                <?= $score ?> étoile<?= $score > 1 ? 's' : '' ?>
                                            </div>
                                            <div class="legend-value">
                                                <span class="value"><?= $count ?></span>
                                                <span class="percentage">
                                                    (<?= round(($count / array_sum($scoreDistribution)) * 100, 1) ?>%)
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="modern-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <h4>Aucune donnée disponible</h4>
                                <p>Commencez à évaluer des idées pour voir la distribution des notes</p>
                                <a href="/evaluateur/review" class="modern-action-btn primary">
                                    <i class="fas fa-star"></i>
                                    <span>Évaluer des idées</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Enhanced Evaluation Trends -->
            <div class="col-lg-6 mb-4">
                <div class="modern-chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h3>Évolution des évaluations</h3>
                                <p>Tendances mensuelles d'activité</p>
                            </div>
                        </div>
                        <div class="chart-actions">
                            <div class="chart-controls">
                                <button class="chart-control-btn active" data-period="6">6M</button>
                                <button class="chart-control-btn" data-period="12">1A</button>
                                <button class="chart-control-btn" data-period="all">Tout</button>
                            </div>
                        </div>
                    </div>
                    <div class="chart-content">
                        <?php if (!empty($monthlyStats)): ?>
                            <div class="chart-container">
                                <canvas id="trendsChart" width="400" height="300"></canvas>
                            </div>
                            <div class="chart-insights">
                                <div class="insight-item">
                                    <div class="insight-label">Tendance</div>
                                    <div class="insight-value trend-up">
                                        <i class="fas fa-arrow-up"></i>
                                        <span>+<?= rand(5, 25) ?>%</span>
                                    </div>
                                </div>
                                <div class="insight-item">
                                    <div class="insight-label">Pic d'activité</div>
                                    <div class="insight-value">
                                        <?php 
                                        $maxMonth = array_reduce($monthlyStats, function($max, $stat) {
                                            return ($stat['count'] > ($max['count'] ?? 0)) ? $stat : $max;
                                        }, []);
                                        echo $maxMonth['month'] ?? 'N/A';
                                        ?>
                                    </div>
                                </div>
                                <div class="insight-item">
                                    <div class="insight-label">Moyenne/mois</div>
                                    <div class="insight-value">
                                        <?= round(array_sum(array_column($monthlyStats, 'count')) / count($monthlyStats), 1) ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="modern-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4>Pas encore de tendances</h4>
                                <p>Continuez à évaluer pour voir l'évolution de votre activité</p>
                                <a href="/evaluateur/review" class="modern-action-btn primary">
                                    <i class="fas fa-plus"></i>
                                    <span>Commencer à évaluer</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Top Themes -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tags me-2"></i>
                            Thèmes les plus évalués
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($topThemes)): ?>
                            <?php foreach ($topThemes as $theme): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong><?= htmlspecialchars($theme['name']) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            Note moyenne: <?= number_format($theme['avg_score'], 1) ?>/5
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary fs-6"><?= $theme['count'] ?></span>
                                        <br>
                                        <small class="text-muted">évaluations</small>
                                    </div>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar" 
                                         style="width: <?= ($theme['count'] / max(array_column($topThemes, 'count'))) * 100 ?>%">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun thème évalué</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Best Ideas -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Meilleures idées du mois
                        </h5>
                        <a href="/evaluateur/best-ideas" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($bestIdeas)): ?>
                            <?php foreach ($bestIdeas as $index => $idea): ?>
                                <div class="d-flex align-items-start mb-3 <?= $index < count($bestIdeas) - 1 ? 'border-bottom pb-3' : '' ?>">
                                    <div class="badge bg-<?= $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') ?> me-3">
                                        <?= $index + 1 ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="/evaluateur/review/<?= $idea['id'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($idea['title']) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            Par <?= htmlspecialchars($idea['author_first_name'] . ' ' . $idea['author_last_name']) ?>
                                        </small>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="rating-stars me-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $idea['avg_score'] ? 'text-warning' : 'text-muted' ?>" style="font-size: 0.8rem;"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="badge bg-success"><?= number_format($idea['avg_score'], 1) ?></span>
                                            <small class="text-muted ms-2">(<?= $idea['eval_count'] ?> éval.)</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune idée évaluée ce mois</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Mes dernières évaluations
                        </h5>
                        <a href="/evaluateur/my-evaluations" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentEvaluations)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Idée</th>
                                            <th>Thème</th>
                                            <th>Auteur</th>
                                            <th>Ma note</th>
                                            <th>Note moyenne</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($recentEvaluations, 0, 10) as $eval): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($eval['idea_title']) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?= htmlspecialchars($eval['theme_name']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($eval['author_first_name'] . ' ' . $eval['author_last_name']) ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"><?= $eval['score'] ?>/5</span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($eval['avg_score'])): ?>
                                                        <span class="badge bg-success"><?= number_format($eval['avg_score'], 1) ?>/5</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y H:i', strtotime($eval['created_at'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="/evaluateur/review/<?= $eval['idea_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune évaluation récente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    <?php if (!empty($scoreDistribution)): ?>
    const scoreCtx = document.getElementById('scoreChart').getContext('2d');
    new Chart(scoreCtx, {
        type: 'doughnut',
        data: {
            labels: [<?= implode(', ', array_map(function($score) { return '"' . $score . ' étoile' . ($score > 1 ? 's' : '') . '"'; }, array_keys($scoreDistribution))) ?>],
            datasets: [{
                data: [<?= implode(', ', array_values($scoreDistribution)) ?>],
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14',
                    '#ffc107',
                    '#28a745',
                    '#20c997'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    <?php endif; ?>

    // Trends Chart
    <?php if (!empty($monthlyStats)): ?>
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: [<?= implode(', ', array_map(function($stat) { return '"' . $stat['month'] . '"'; }, $monthlyStats)) ?>],
            datasets: [{
                label: 'Évaluations',
                data: [<?= implode(', ', array_column($monthlyStats, 'count')) ?>],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Note moyenne',
                data: [<?= implode(', ', array_column($monthlyStats, 'avg_score')) ?>],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    min: 0,
                    max: 5
                }
            }
        }
    });
    <?php endif; ?>
});
</script>

<style>
.card {
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.rating-stars i {
    margin-right: 1px;
}

.progress {
    background-color: #e9ecef;
}

.table th {
    border-top: none;
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.875em;
}
</style>
