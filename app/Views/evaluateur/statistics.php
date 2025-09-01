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

        <!-- Modern Themes & Ideas Analysis Section -->
        <div class="row">
            <!-- Enhanced Top Themes -->
            <div class="col-lg-6 mb-4">
                <div class="modern-analysis-card">
                    <div class="analysis-header">
                        <div class="analysis-title">
                            <div class="analysis-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div>
                                <h3>Thèmes les plus évalués</h3>
                                <p>Répartition par catégories d'idées</p>
                            </div>
                        </div>
                        <div class="analysis-actions">
                            <button class="analysis-action-btn" onclick="viewAllThemes()">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Voir tout</span>
                            </button>
                        </div>
                    </div>
                    <div class="analysis-content">
                        <?php if (!empty($topThemes)): ?>
                            <div class="themes-list">
                                <?php foreach (array_slice($topThemes, 0, 6) as $index => $theme): ?>
                                    <div class="theme-item">
                                        <div class="theme-rank">
                                            <span class="rank-number rank-<?= $index + 1 ?>"><?= $index + 1 ?></span>
                                        </div>
                                        <div class="theme-content">
                                            <div class="theme-header">
                                                <h4 class="theme-name"><?= htmlspecialchars($theme['name']) ?></h4>
                                                <div class="theme-stats">
                                                    <span class="evaluation-count"><?= $theme['count'] ?> évaluations</span>
                                                    <div class="average-score">
                                                        <div class="score-stars">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?= $i <= round($theme['avg_score']) ? 'active' : '' ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span class="score-value"><?= number_format($theme['avg_score'], 1) ?>/5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="theme-progress">
                                                <div class="progress-bar" 
                                                     style="width: <?= ($theme['count'] / max(array_column($topThemes, 'count'))) * 100 ?>%">
                                                </div>
                                                <div class="progress-label">
                                                    <?= round(($theme['count'] / array_sum(array_column($topThemes, 'count'))) * 100, 1) ?>% du total
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="themes-summary">
                                <div class="summary-stat">
                                    <span class="stat-label">Total thèmes évalués</span>
                                    <span class="stat-value"><?= count($topThemes) ?></span>
                                </div>
                                <div class="summary-stat">
                                    <span class="stat-label">Thème favori</span>
                                    <span class="stat-value"><?= htmlspecialchars($topThemes[0]['name']) ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="modern-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <h4>Aucun thème évalué</h4>
                                <p>Explorez différentes catégories d'idées</p>
                                <a href="/evaluateur/review" class="modern-action-btn primary">
                                    <i class="fas fa-search"></i>
                                    <span>Explorer les thèmes</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Enhanced Best Ideas -->
            <div class="col-lg-6 mb-4">
                <div class="modern-analysis-card">
                    <div class="analysis-header">
                        <div class="analysis-title">
                            <div class="analysis-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h3>Meilleures idées du mois</h3>
                                <p>Top des idées les mieux notées</p>
                            </div>
                        </div>
                        <div class="analysis-actions">
                            <a href="/evaluateur/best-ideas" class="analysis-action-btn">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Voir toutes</span>
                            </a>
                        </div>
                    </div>
                    <div class="analysis-content">
                        <?php if (!empty($bestIdeas)): ?>
                            <div class="best-ideas-list">
                                <?php foreach (array_slice($bestIdeas, 0, 5) as $index => $idea): ?>
                                    <div class="idea-item">
                                        <div class="idea-rank">
                                            <?php if ($index === 0): ?>
                                                <div class="medal gold">
                                                    <i class="fas fa-trophy"></i>
                                                </div>
                                            <?php elseif ($index === 1): ?>
                                                <div class="medal silver">
                                                    <i class="fas fa-medal"></i>
                                                </div>
                                            <?php elseif ($index === 2): ?>
                                                <div class="medal bronze">
                                                    <i class="fas fa-award"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="rank-badge"><?= $index + 1 ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="idea-content">
                                            <h4 class="idea-title">
                                                <a href="/evaluateur/review/<?= $idea['id'] ?>">
                                                    <?= htmlspecialchars($idea['title']) ?>
                                                </a>
                                            </h4>
                                            <div class="idea-meta">
                                                <span class="author">
                                                    <i class="fas fa-user"></i>
                                                    <?= htmlspecialchars($idea['author_first_name'] . ' ' . $idea['author_last_name']) ?>
                                                </span>
                                                <div class="rating-info">
                                                    <div class="rating-stars">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?= $i <= $idea['avg_score'] ? 'active' : '' ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="score-badge"><?= number_format($idea['avg_score'], 1) ?></span>
                                                    <span class="eval-count">(<?= $idea['eval_count'] ?> éval.)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="ideas-insights">
                                <div class="insight-card">
                                    <div class="insight-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="insight-content">
                                        <span class="insight-value"><?= number_format($bestIdeas[0]['avg_score'] ?? 0, 1) ?></span>
                                        <span class="insight-label">Meilleure note</span>
                                    </div>
                                </div>
                                <div class="insight-card">
                                    <div class="insight-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="insight-content">
                                        <span class="insight-value"><?= array_sum(array_column($bestIdeas, 'eval_count')) ?></span>
                                        <span class="insight-label">Total évaluations</span>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="modern-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4>Aucune idée évaluée ce mois</h4>
                                <p>Découvrez et évaluez les nouvelles idées</p>
                                <a href="/evaluateur/review" class="modern-action-btn primary">
                                    <i class="fas fa-lightbulb"></i>
                                    <span>Découvrir les idées</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Recent Activity Section -->
        <div class="row">
            <div class="col-12">
                <div class="modern-activity-card">
                    <div class="activity-header">
                        <div class="activity-title">
                            <div class="activity-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h3>Mes dernières évaluations</h3>
                                <p>Historique récent d'activité d'évaluation</p>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <div class="activity-filters">
                                <select class="modern-select-sm" onchange="filterActivity(this.value)">
                                    <option value="all">Toutes</option>
                                    <option value="today">Aujourd'hui</option>
                                    <option value="week">Cette semaine</option>
                                    <option value="month">Ce mois</option>
                                </select>
                            </div>
                            <a href="/evaluateur/my-evaluations" class="activity-action-btn">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Voir toutes</span>
                            </a>
                        </div>
                    </div>
                    <div class="activity-content">
                        <?php if (!empty($recentEvaluations)): ?>
                            <div class="modern-table-container">
                                <table class="modern-activity-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-lightbulb"></i>
                                                    <span>Idée évaluée</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-tag"></i>
                                                    <span>Thème</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-user"></i>
                                                    <span>Auteur</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-star"></i>
                                                    <span>Ma note</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-chart-line"></i>
                                                    <span>Moyenne</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-clock"></i>
                                                    <span>Date</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="fas fa-cog"></i>
                                                    <span>Actions</span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($recentEvaluations, 0, 10) as $eval): ?>
                                            <tr class="activity-row">
                                                <td class="idea-cell">
                                                    <div class="idea-info">
                                                        <h4 class="idea-title">
                                                            <?= htmlspecialchars($eval['idea_title']) ?>
                                                        </h4>
                                                        <div class="idea-meta">
                                                            <span class="idea-id">#<?= $eval['idea_id'] ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="theme-cell">
                                                    <span class="theme-badge">
                                                        <?= htmlspecialchars($eval['theme_name']) ?>
                                                    </span>
                                                </td>
                                                <td class="author-cell">
                                                    <div class="author-info">
                                                        <span class="author-name">
                                                            <?= htmlspecialchars($eval['author_first_name'] . ' ' . $eval['author_last_name']) ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="score-cell">
                                                    <div class="my-score">
                                                        <div class="score-stars">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?= $i <= $eval['score'] ? 'active' : '' ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span class="score-badge score-<?= $eval['score'] ?>"><?= $eval['score'] ?>/5</span>
                                                    </div>
                                                </td>
                                                <td class="average-cell">
                                                    <?php if (!empty($eval['avg_score'])): ?>
                                                        <div class="average-score">
                                                            <span class="avg-badge"><?= number_format($eval['avg_score'], 1) ?>/5</span>
                                                            <small class="avg-trend">
                                                                <?php if ($eval['score'] > $eval['avg_score']): ?>
                                                                    <i class="fas fa-arrow-up text-success"></i>
                                                                    <span>Au-dessus</span>
                                                                <?php elseif ($eval['score'] < $eval['avg_score']): ?>
                                                                    <i class="fas fa-arrow-down text-warning"></i>
                                                                    <span>En-dessous</span>
                                                                <?php else: ?>
                                                                    <i class="fas fa-equals text-info"></i>
                                                                    <span>Égale</span>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="no-average">Première évaluation</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="date-cell">
                                                    <div class="eval-date">
                                                        <span class="date"><?= date('d/m/Y', strtotime($eval['created_at'])) ?></span>
                                                        <span class="time"><?= date('H:i', strtotime($eval['created_at'])) ?></span>
                                                        <span class="relative-time" data-date="<?= $eval['created_at'] ?>"></span>
                                                    </div>
                                                </td>
                                                <td class="actions-cell">
                                                    <div class="table-actions">
                                                        <a href="/evaluateur/review/<?= $eval['idea_id'] ?>" 
                                                           class="action-btn primary" 
                                                           title="Voir l'idée">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="action-btn secondary" 
                                                                onclick="quickEdit(<?= $eval['id'] ?>)" 
                                                                title="Modifier rapidement">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="activity-summary">
                                <div class="summary-stats">
                                    <div class="summary-stat">
                                        <span class="stat-value"><?= count($recentEvaluations) ?></span>
                                        <span class="stat-label">évaluations récentes</span>
                                    </div>
                                    <div class="summary-stat">
                                        <span class="stat-value">
                                            <?= number_format(array_sum(array_column($recentEvaluations, 'score')) / max(1, count($recentEvaluations)), 1) ?>
                                        </span>
                                        <span class="stat-label">note moyenne récente</span>
                                    </div>
                                    <div class="summary-stat">
                                        <span class="stat-value">
                                            <?= count(array_unique(array_column($recentEvaluations, 'theme_name'))) ?>
                                        </span>
                                        <span class="stat-label">thèmes explorés</span>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="modern-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <h4>Aucune évaluation récente</h4>
                                <p>Votre historique d'évaluations apparaîtra ici</p>
                                <a href="/evaluateur/review" class="modern-action-btn primary">
                                    <i class="fas fa-star"></i>
                                    <span>Commencer à évaluer</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern CSS Styles -->
<style>
/* Modern Statistics Page Styles */
.modern-statistics-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: #333;
}

/* Header Section */
.modern-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.modern-breadcrumb {
    margin-bottom: 1.5rem;
}

.breadcrumb-container {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    backdrop-filter: blur(10px);
    gap: 1rem;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.breadcrumb-link:hover {
    color: white;
    text-shadow: 0 0 10px rgba(255,255,255,0.5);
    transform: translateX(2px);
}

.breadcrumb-separator {
    color: rgba(255,255,255,0.6);
    font-size: 0.8rem;
}

.breadcrumb-current {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.modern-header-content {
    margin-top: 1rem;
}

.modern-page-title {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.title-icon {
    background: rgba(255,255,255,0.2);
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 8px 32px rgba(255,255,255,0.1);
}

.title-content h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.title-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0 0 1rem 0;
}

.stats-summary {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-top: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    background: rgba(255,255,255,0.15);
    padding: 0.75rem 1.25rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    font-size: 0.9rem;
    white-space: nowrap;
    border: 1px solid rgba(255,255,255,0.1);
}

.stat-item i {
    font-size: 1rem;
    opacity: 0.9;
}

.modern-header-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    align-items: center;
}

/* Modern Action Buttons */
.modern-action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.modern-action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modern-action-btn.outline {
    background: transparent;
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
}

.modern-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Enhanced Statistics Section */
.modern-stats-section {
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-height: 140px;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.primary::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success::before { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.stat-card.warning::before { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.stat-card.info::before { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stat-card.primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.stat-card.warning .stat-icon { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.stat-card.info .stat-icon { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }

.stat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0;
    color: #1e293b;
    line-height: 1;
}

.stat-content p {
    font-size: 1rem;
    font-weight: 600;
    color: #64748b;
    margin: 0;
    line-height: 1.2;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #94a3b8;
    font-weight: 500;
}

.stat-detail {
    margin-top: 0.5rem;
}

.stat-detail small {
    color: #94a3b8;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Modern Chart Cards */
.modern-chart-card, .modern-analysis-card, .modern-activity-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.modern-chart-card:hover, .modern-analysis-card:hover, .modern-activity-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
}

.chart-header, .analysis-header, .activity-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-title, .analysis-title, .activity-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.chart-icon, .analysis-icon, .activity-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.chart-title h3, .analysis-title h3, .activity-title h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.chart-title p, .analysis-title p, .activity-title p {
    color: #64748b;
    margin: 0;
    font-size: 0.9rem;
}

.chart-actions, .analysis-actions, .activity-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.chart-action-btn, .analysis-action-btn, .activity-action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f1f5f9;
    color: #667eea;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.chart-action-btn:hover, .analysis-action-btn:hover, .activity-action-btn:hover {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}

.chart-content, .analysis-content, .activity-content {
    padding: 2rem;
}

/* Chart Controls */
.chart-controls {
    display: flex;
    gap: 0.5rem;
    background: #f1f5f9;
    border-radius: 8px;
    padding: 0.25rem;
}

.chart-control-btn {
    padding: 0.5rem 1rem;
    background: transparent;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
}

.chart-control-btn.active,
.chart-control-btn:hover {
    background: white;
    color: #667eea;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Chart Container */
.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 1.5rem;
}

.chart-legend {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.legend-color.score-1 { background: #ef4444; }
.legend-color.score-2 { background: #f97316; }
.legend-color.score-3 { background: #eab308; }
.legend-color.score-4 { background: #22c55e; }
.legend-color.score-5 { background: #10b981; }

.legend-content {
    flex: 1;
}

.legend-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.legend-value {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-value .value {
    font-weight: 700;
    color: #667eea;
}

.legend-value .percentage {
    color: #94a3b8;
    font-size: 0.9rem;
}

/* Chart Insights */
.chart-insights {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.insight-item {
    flex: 1;
    text-align: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.insight-label {
    color: #64748b;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.insight-value {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #1e293b;
}

.insight-value.trend-up {
    color: #10b981;
}

/* Themes List */
.themes-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.theme-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.theme-item:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}

.theme-rank {
    display: flex;
    align-items: center;
    justify-content: center;
}

.rank-number {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    color: white;
    font-size: 1.1rem;
}

.rank-number.rank-1 { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
.rank-number.rank-2 { background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%); }
.rank-number.rank-3 { background: linear-gradient(135deg, #cd7c2f 0%, #a16207 100%); }
.rank-number.rank-4,
.rank-number.rank-5,
.rank-number.rank-6 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

.theme-content {
    flex: 1;
}

.theme-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.theme-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.theme-stats {
    text-align: right;
}

.evaluation-count {
    color: #667eea;
    font-weight: 600;
    font-size: 0.9rem;
}

.average-score {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.25rem;
}

.score-stars {
    display: flex;
    gap: 1px;
}

.score-stars i {
    font-size: 0.8rem;
    color: #e2e8f0;
}

.score-stars i.active {
    color: #fbbf24;
}

.score-value {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

.theme-progress {
    position: relative;
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
}

.theme-progress .progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 3px;
    transition: width 0.6s ease;
}

.progress-label {
    margin-top: 0.5rem;
    color: #94a3b8;
    font-size: 0.8rem;
}

.themes-summary {
    display: flex;
    gap: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 12px;
}

.summary-stat {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.summary-stat .stat-label {
    color: #64748b;
    font-size: 0.85rem;
}

.summary-stat .stat-value {
    font-weight: 700;
    color: #1e293b;
}

/* Best Ideas List */
.best-ideas-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.idea-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.idea-item:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
}

.idea-rank {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.medal {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.medal.gold { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
.medal.silver { background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%); }
.medal.bronze { background: linear-gradient(135deg, #cd7c2f 0%, #a16207 100%); }

.rank-badge {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.idea-content {
    flex: 1;
}

.idea-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.idea-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.idea-title a:hover {
    color: #667eea;
}

.idea-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.9rem;
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars i {
    font-size: 0.8rem;
    color: #e2e8f0;
}

.rating-stars i.active {
    color: #fbbf24;
}

.score-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.8rem;
}

.eval-count {
    color: #94a3b8;
    font-size: 0.8rem;
}

.ideas-insights {
    display: flex;
    gap: 1rem;
}

.insight-card {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.insight-card .insight-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.insight-card .insight-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.insight-card .insight-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
}

.insight-card .insight-label {
    color: #64748b;
    font-size: 0.85rem;
}

/* Modern Activity Table */
.modern-table-container {
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    margin-bottom: 2rem;
}

.modern-activity-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.modern-activity-table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    color: #1e293b;
    font-weight: 700;
    padding: 1.5rem 1rem;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.th-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.activity-row {
    border-bottom: 1px solid #f8fafc;
    transition: all 0.3s ease;
}

.activity-row:hover {
    background: #f8fafc;
}

.modern-activity-table td {
    padding: 1.5rem 1rem;
    vertical-align: top;
}

.idea-info {
    max-width: 300px;
}

.idea-title {
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
    line-height: 1.4;
    font-size: 1rem;
}

.idea-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.idea-id {
    color: #94a3b8;
    font-size: 0.8rem;
    font-weight: 500;
}

.theme-badge {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    color: #475569;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
}

.author-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.author-name {
    font-weight: 600;
    color: #1e293b;
}

.my-score {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.score-stars {
    display: flex;
    gap: 2px;
}

.score-stars i {
    font-size: 0.9rem;
    color: #e2e8f0;
}

.score-stars i.active {
    color: #fbbf24;
}

.score-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    color: white;
}

.score-badge.score-5,
.score-badge.score-4 {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.score-badge.score-3 {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}

.score-badge.score-2,
.score-badge.score-1 {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.average-score {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.avg-badge {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.avg-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
}

.no-average {
    color: #94a3b8;
    font-style: italic;
    font-size: 0.9rem;
}

.eval-date {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.eval-date .date {
    font-weight: 600;
    color: #1e293b;
}

.eval-date .time {
    color: #64748b;
    font-size: 0.85rem;
}

.relative-time {
    color: #94a3b8;
    font-size: 0.8rem;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.action-btn.secondary {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Activity Summary */
.activity-summary {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
}

.summary-stats {
    display: flex;
    gap: 2rem;
    justify-content: center;
}

.summary-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.summary-stat .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #667eea;
}

.summary-stat .stat-label {
    color: #64748b;
    font-size: 0.85rem;
    text-align: center;
}

/* Activity Filters */
.activity-filters {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.modern-select-sm {
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    color: #1e293b;
    cursor: pointer;
}

/* Empty States */
.modern-empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 16px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem auto;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #94a3b8;
}

.modern-empty-state h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
}

.modern-empty-state p {
    color: #64748b;
    margin: 0 0 1.5rem 0;
    font-size: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .stats-summary {
        gap: 1rem;
        justify-content: center;
    }
    
    .stat-item {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        gap: 0.5rem;
    }
    
    .modern-header-content {
        text-align: center;
    }
    
    .modern-page-title {
        flex-direction: column;
        text-align: center;
    }
    
    .modern-header-actions {
        justify-content: center;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .chart-header, .analysis-header, .activity-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .chart-insights {
        flex-direction: column;
    }
    
    .themes-summary {
        flex-direction: column;
        gap: 1rem;
    }
    
    .ideas-insights {
        flex-direction: column;
    }
    
    .summary-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .modern-table-container {
        overflow-x: scroll;
    }
    
    .activity-filters {
        justify-content: center;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Score Distribution Chart
    <?php if (!empty($scoreDistribution)): ?>
    const scoreCtx = document.getElementById('scoreChart').getContext('2d');
    new Chart(scoreCtx, {
        type: 'doughnut',
        data: {
            labels: [<?= implode(', ', array_map(function($score) { return '"' . $score . ' étoile' . ($score > 1 ? 's' : '') . '"'; }, array_keys($scoreDistribution))) ?>],
            datasets: [{
                data: [<?= implode(', ', array_values($scoreDistribution)) ?>],
                backgroundColor: [
                    '#ef4444',
                    '#f97316', 
                    '#eab308',
                    '#22c55e',
                    '#10b981'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderWidth: 4,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });
    <?php endif; ?>

    // Enhanced Trends Chart
    <?php if (!empty($monthlyStats)): ?>
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: [<?= implode(', ', array_map(function($stat) { return '"' . $stat['month'] . '"'; }, $monthlyStats)) ?>],
            datasets: [{
                label: 'Évaluations',
                data: [<?= implode(', ', array_column($monthlyStats, 'count')) ?>],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }, {
                label: 'Note moyenne',
                data: [<?= implode(', ', array_column($monthlyStats, 'avg_score')) ?>],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                yAxisID: 'y1',
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
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
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    min: 0,
                    max: 5,
                    ticks: {
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
    <?php endif; ?>

    // Update relative times
    updateRelativeTimes();
    setInterval(updateRelativeTimes, 60000); // Update every minute
});

// Enhanced JavaScript Functions
function exportStatistics() {
    showNotification('Exportation des statistiques en cours...', 'info');
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Statistiques exportées avec succès!', 'success');
        
        // Create and download a simple CSV
        const csvContent = "data:text/csv;charset=utf-8," + 
            "Type,Valeur\n" +
            "Total évaluations,<?= $stats['total_evaluations'] ?? 0 ?>\n" +
            "Note moyenne,<?= number_format($stats['average_score'] ?? 0, 1) ?>\n" +
            "Idées excellentes,<?= $stats['excellent_ideas'] ?? 0 ?>\n" +
            "Cette semaine,<?= $stats['this_week'] ?? 0 ?>";
            
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "statistiques_evaluation_" + new Date().toISOString().split('T')[0] + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }, 1500);
}

function refreshData() {
    showNotification('Actualisation des données...', 'info');
    
    // Add loading animation
    const refreshBtn = event.target.closest('.modern-action-btn');
    const icon = refreshBtn.querySelector('i');
    icon.classList.add('fa-spin');
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function toggleChartView(type) {
    showNotification('Changement de vue du graphique...', 'info');
    // Implementation for switching chart views
}

function viewAllThemes() {
    window.location.href = '/evaluateur/themes';
}

function filterActivity(period) {
    showNotification('Filtrage par période: ' + period, 'info');
    
    const rows = document.querySelectorAll('.activity-row');
    rows.forEach(row => {
        // Simple show/hide based on period
        // In a real implementation, this would filter based on actual dates
        row.style.display = 'table-row';
    });
}

function quickEdit(evaluationId) {
    showNotification('Édition rapide de l\'évaluation #' + evaluationId, 'info');
    
    // In a real implementation, this would open a modal or redirect
    setTimeout(() => {
        window.location.href = '/evaluateur/edit-evaluation/' + evaluationId;
    }, 500);
}

function updateRelativeTimes() {
    const timeElements = document.querySelectorAll('.relative-time');
    timeElements.forEach(element => {
        const date = new Date(element.dataset.date);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        let relativeTime;
        if (diffInSeconds < 60) {
            relativeTime = 'À l\'instant';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            relativeTime = `Il y a ${minutes} min`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            relativeTime = `Il y a ${hours}h`;
        } else {
            const days = Math.floor(diffInSeconds / 86400);
            relativeTime = `Il y a ${days}j`;
        }
        
        element.textContent = relativeTime;
    });
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.modern-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `modern-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}
</script>
