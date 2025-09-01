<div class="modern-my-evaluations-page">
    <!-- Modern Header Section -->
    <div class="modern-header-section">
        <div class="container-fluid">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="modern-breadcrumb">
                <div class="breadcrumb-container">
                    <a href="/evaluateur/dashboard" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <span class="breadcrumb-separator">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                    <span class="breadcrumb-current">
                        <i class="fas fa-history"></i>
                        <span>Mes évaluations</span>
                    </span>
                </div>
            </nav>

            <!-- Header Content -->
            <div class="modern-header-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="modern-page-title">
                            <div class="title-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="title-content">
                                <h1>Mes évaluations</h1>
                                <p>Historique complet de toutes mes évaluations d'idées avec statistiques et analyses</p>
                                <div class="stats-summary">
                                    <span class="stat-item">
                                        <i class="fas fa-clipboard-check"></i>
                                        <?= $stats['total'] ?? 0 ?> évaluations
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-star"></i>
                                        Moyenne: <?= number_format($stats['average_score'] ?? 0, 1) ?>/5
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-calendar"></i>
                                        <?= $stats['this_month'] ?? 0 ?> ce mois
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-trophy"></i>
                                        <?= $stats['excellent_given'] ?? 0 ?> excellentes
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="modern-header-actions">
                            <button onclick="exportEvaluations()" class="modern-action-btn primary">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Enhanced Modern Statistics Section -->
        <div class="modern-stats-section">
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['total'] ?? 0 ?></h3>
                        <p>Total évaluations</p>
                        <div class="stat-trend">
                            <i class="fas fa-history"></i>
                            <span>Depuis le début</span>
                        </div>
                        <div class="stat-detail">
                            <?php if (($stats['total'] ?? 0) > 0): ?>
                                <small>
                                    <?php 
                                    $participation = round((($stats['total'] ?? 0) / max(1, ($stats['total_ideas'] ?? 1))) * 100, 1);
                                    echo $participation . "% des idées évaluées";
                                    ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= number_format($stats['average_score'] ?? 0, 1) ?>/5</h3>
                        <p>Note moyenne donnée</p>
                        <div class="stat-trend">
                            <i class="fas fa-chart-line"></i>
                            <span>
                                <?php 
                                $avg = $stats['average_score'] ?? 0;
                                if ($avg >= 4) echo "Très positif";
                                elseif ($avg >= 3) echo "Positif"; 
                                elseif ($avg >= 2) echo "Modéré";
                                else echo "Sévère";
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php
                                $distribution = [
                                    5 => $stats['score_5'] ?? 0,
                                    4 => $stats['score_4'] ?? 0, 
                                    3 => $stats['score_3'] ?? 0,
                                    2 => $stats['score_2'] ?? 0,
                                    1 => $stats['score_1'] ?? 0
                                ];
                                $most_given = array_keys($distribution, max($distribution))[0];
                                echo "Note la plus donnée: " . $most_given . "/5";
                                ?>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['this_month'] ?? 0 ?></h3>
                        <p>Évaluations ce mois</p>
                        <div class="stat-trend">
                            <i class="fas fa-trending-up"></i>
                            <span>
                                <?php 
                                $thisMonth = $stats['this_month'] ?? 0;
                                $lastMonth = $stats['last_month'] ?? 0;
                                if ($thisMonth > $lastMonth) {
                                    echo "+" . ($thisMonth - $lastMonth) . " vs le mois dernier";
                                } elseif ($thisMonth < $lastMonth) {
                                    echo "-" . ($lastMonth - $thisMonth) . " vs le mois dernier";
                                } else {
                                    echo "Stable par rapport au mois dernier";
                                }
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php if ($thisMonth > 0): ?>
                                    Moyenne de <?= round($thisMonth / max(1, date('j')), 1) ?> par jour
                                <?php else: ?>
                                    Aucune activité ce mois
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['excellent_given'] ?? 0 ?></h3>
                        <p>Notes excellentes (4-5★)</p>
                        <div class="stat-trend">
                            <i class="fas fa-trophy"></i>
                            <span>
                                <?php 
                                $excellent = $stats['excellent_given'] ?? 0;
                                $total = $stats['total'] ?? 1;
                                $excellentPercent = round(($excellent / max(1, $total)) * 100, 1);
                                echo $excellentPercent . "% d'excellence";
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php 
                                $poor = ($stats['score_1'] ?? 0) + ($stats['score_2'] ?? 0);
                                echo $poor . " note(s) faible(s) données";
                                ?>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Additional Advanced Statistics Cards -->
                <div class="stat-card secondary">
                    <div class="stat-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['with_comments'] ?? 0 ?></h3>
                        <p>Avec commentaires</p>
                        <div class="stat-trend">
                            <i class="fas fa-comment-dots"></i>
                            <span>
                                <?php 
                                $withComments = $stats['with_comments'] ?? 0;
                                $total = $stats['total'] ?? 1;
                                $commentPercent = round(($withComments / max(1, $total)) * 100, 1);
                                echo $commentPercent . "% détaillées";
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php 
                                $avgLength = $stats['avg_comment_length'] ?? 0;
                                if ($avgLength > 0) {
                                    echo "Commentaires de " . round($avgLength) . " caractères en moyenne";
                                } else {
                                    echo "Ajoutez des commentaires pour plus d'impact";
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="stat-card accent">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['this_week'] ?? 0 ?></h3>
                        <p>Cette semaine</p>
                        <div class="stat-trend">
                            <i class="fas fa-calendar-day"></i>
                            <span>
                                <?php 
                                $thisWeek = $stats['this_week'] ?? 0;
                                if ($thisWeek > 0) {
                                    echo "Actif cette semaine";
                                } else {
                                    echo "Aucune activité";
                                }
                                ?>
                            </span>
                        </div>
                        <div class="stat-detail">
                            <small>
                                <?php 
                                $dayOfWeek = date('N'); // 1 = Monday, 7 = Sunday
                                if ($thisWeek > 0) {
                                    echo "Moyenne de " . round($thisWeek / $dayOfWeek, 1) . " par jour";
                                } else {
                                    echo "Commencez à évaluer dès maintenant";
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Statistics Summary Bar -->
            <div class="stats-summary-bar">
                <div class="summary-item">
                    <div class="summary-label">Niveau d'engagement</div>
                    <div class="summary-progress">
                        <div class="progress-bar" style="width: <?= min(100, (($stats['total'] ?? 0) / max(1, ($stats['total_ideas'] ?? 1))) * 100) ?>%"></div>
                    </div>
                    <div class="summary-value">
                        <?= round((($stats['total'] ?? 0) / max(1, ($stats['total_ideas'] ?? 1))) * 100, 1) ?>%
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Qualité moyenne</div>
                    <div class="summary-progress">
                        <div class="progress-bar quality" style="width: <?= (($stats['average_score'] ?? 0) / 5) * 100 ?>%"></div>
                    </div>
                    <div class="summary-value">
                        <?= number_format($stats['average_score'] ?? 0, 1) ?>/5
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Régularité mensuelle</div>
                    <div class="summary-progress">
                        <div class="progress-bar activity" style="width: <?= min(100, (($stats['this_month'] ?? 0) / max(1, 10)) * 100) ?>%"></div>
                    </div>
                    <div class="summary-value">
                        <?= $stats['this_month'] ?? 0 ?>/10
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Filters Section -->
        <div class="modern-filters-section">
            <div class="modern-filters-card">
                <div class="filters-header">
                    <h3>
                        <i class="fas fa-filter"></i>
                        Filtres de recherche
                    </h3>
                    <p>Affinez votre recherche dans vos évaluations</p>
                </div>
                
                <form method="GET" class="modern-filters-form">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="score" class="filter-label">
                                <i class="fas fa-star"></i>
                                Note donnée
                            </label>
                            <select name="score" id="score" class="modern-select">
                                <option value="">Toutes les notes</option>
                                <option value="5" <?= ($_GET['score'] ?? '') == '5' ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ 5 étoiles</option>
                                <option value="4" <?= ($_GET['score'] ?? '') == '4' ? 'selected' : '' ?>>⭐⭐⭐⭐ 4 étoiles</option>
                                <option value="3" <?= ($_GET['score'] ?? '') == '3' ? 'selected' : '' ?>>⭐⭐⭐ 3 étoiles</option>
                                <option value="2" <?= ($_GET['score'] ?? '') == '2' ? 'selected' : '' ?>>⭐⭐ 2 étoiles</option>
                                <option value="1" <?= ($_GET['score'] ?? '') == '1' ? 'selected' : '' ?>>⭐ 1 étoile</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="theme_id" class="filter-label">
                                <i class="fas fa-tags"></i>
                                Thème
                            </label>
                            <select name="theme_id" id="theme_id" class="modern-select">
                                <option value="">Tous les thèmes</option>
                                <?php if (!empty($themes)): ?>
                                    <?php foreach ($themes as $theme): ?>
                                        <option value="<?= $theme['id'] ?>" 
                                                <?= isset($_GET['theme_id']) && $_GET['theme_id'] == $theme['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($theme['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="period" class="filter-label">
                                <i class="fas fa-calendar-alt"></i>
                                Période
                            </label>
                            <select name="period" id="period" class="modern-select">
                                <option value="">Toute période</option>
                                <option value="today" <?= ($_GET['period'] ?? '') == 'today' ? 'selected' : '' ?>>Aujourd'hui</option>
                                <option value="week" <?= ($_GET['period'] ?? '') == 'week' ? 'selected' : '' ?>>Cette semaine</option>
                                <option value="month" <?= ($_GET['period'] ?? '') == 'month' ? 'selected' : '' ?>>Ce mois</option>
                                <option value="quarter" <?= ($_GET['period'] ?? '') == 'quarter' ? 'selected' : '' ?>>Ce trimestre</option>
                            </select>
                        </div>

                        <div class="filter-group search-group">
                            <label for="search" class="filter-label">
                                <i class="fas fa-search"></i>
                                Recherche
                            </label>
                            <div class="search-input-wrapper">
                                <input type="text" name="search" id="search" class="modern-search-input" 
                                       placeholder="Titre d'idée, auteur..." 
                                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button type="submit" class="search-submit-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="filters-actions">
                        <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['score']) || !empty($_GET['period'])): ?>
                            <a href="/evaluateur/my-evaluations" class="modern-action-btn outline">
                                <i class="fas fa-times"></i>
                                <span>Réinitialiser</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modern Evaluations List -->
        <div class="modern-evaluations-section">
            <div class="modern-evaluations-header">
                <div class="evaluations-title">
                    <h3>
                        <i class="fas fa-list"></i>
                        Mes évaluations
                        <span class="results-count"><?= $pagination['total'] ?? 0 ?> résultats</span>
                    </h3>
                    <p>Historique détaillé de toutes vos évaluations</p>
                </div>
                <div class="evaluations-actions">
                    <button type="button" class="modern-action-btn outline" onclick="exportEvaluations()">
                        <i class="fas fa-download"></i>
                        <span>Exporter</span>
                    </button>
                    <div class="sort-dropdown">
                        <button class="modern-action-btn secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown">
                            <i class="fas fa-sort"></i>
                            <span>Trier</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="?sort=date_desc<?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                <i class="fas fa-arrow-down"></i> Plus récentes
                            </a>
                            <a class="dropdown-item" href="?sort=date_asc<?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                <i class="fas fa-arrow-up"></i> Plus anciennes
                            </a>
                            <a class="dropdown-item" href="?sort=score_desc<?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                <i class="fas fa-star"></i> Note la plus haute
                            </a>
                            <a class="dropdown-item" href="?sort=score_asc<?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                <i class="fas fa-star-half-alt"></i> Note la plus basse
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-evaluations-content">
                <?php if (!empty($evaluations)): ?>
                    <div class="modern-evaluations-table">
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th class="idea-column">
                                            <i class="fas fa-lightbulb"></i>
                                            Idée évaluée
                                        </th>
                                        <th class="theme-column">
                                            <i class="fas fa-tag"></i>
                                            Thème
                                        </th>
                                        <th class="author-column">
                                            <i class="fas fa-user"></i>
                                            Auteur
                                        </th>
                                        <th class="score-column">
                                            <i class="fas fa-star"></i>
                                            Ma note
                                        </th>
                                        <th class="average-column">
                                            <i class="fas fa-chart-bar"></i>
                                            Note moyenne
                                        </th>
                                        <th class="comment-column">
                                            <i class="fas fa-comment"></i>
                                            Mon commentaire
                                        </th>
                                        <th class="date-column">
                                            <i class="fas fa-calendar"></i>
                                            Date
                                        </th>
                                        <th class="actions-column">
                                            <i class="fas fa-cog"></i>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evaluations as $eval): ?>
                                        <tr class="evaluation-row">
                                            <td class="idea-cell">
                                                <div class="idea-info">
                                                    <h5 class="idea-title">
                                                        <?= htmlspecialchars($eval['idea_title']) ?>
                                                    </h5>
                                                    <p class="idea-excerpt">
                                                        <?= htmlspecialchars(substr($eval['idea_description'] ?? '', 0, 80)) ?><?= strlen($eval['idea_description'] ?? '') > 80 ? '...' : '' ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="theme-cell">
                                                <span class="theme-tag">
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
                                                <div class="my-rating">
                                                    <div class="star-rating">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?= $i <= $eval['score'] ? 'active' : '' ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="score-badge score-<?= $eval['score'] >= 4 ? 'high' : ($eval['score'] >= 3 ? 'medium' : 'low') ?>">
                                                        <?= $eval['score'] ?>/5
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="average-cell">
                                                <?php if (!empty($eval['avg_score'])): ?>
                                                    <div class="average-rating">
                                                        <span class="average-score"><?= number_format($eval['avg_score'], 1) ?>/5</span>
                                                        <small class="evaluations-count">(<?= $eval['total_evaluations'] ?> éval.)</small>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="single-evaluation">Seule évaluation</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="comment-cell">
                                                <?php if (!empty($eval['comment'])): ?>
                                                    <div class="comment-preview">
                                                        <span class="comment-text" title="<?= htmlspecialchars($eval['comment']) ?>">
                                                            <?= htmlspecialchars(substr($eval['comment'], 0, 60)) ?><?= strlen($eval['comment']) > 60 ? '...' : '' ?>
                                                        </span>
                                                        <?php if (strlen($eval['comment']) > 60): ?>
                                                            <button type="button" class="expand-comment-btn" 
                                                                    onclick="showFullComment('<?= htmlspecialchars($eval['comment']) ?>')">
                                                                <i class="fas fa-expand-alt"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="no-comment">Aucun commentaire</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="date-cell">
                                                <div class="evaluation-date">
                                                    <span class="date">
                                                        <?= date('d/m/Y', strtotime($eval['created_at'])) ?>
                                                    </span>
                                                    <span class="time">
                                                        <?= date('H:i', strtotime($eval['created_at'])) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="actions-cell">
                                                <div class="table-actions">
                                                    <a href="/evaluateur/review/<?= $eval['idea_id'] ?>" 
                                                       class="action-btn primary" 
                                                       title="Voir l'idée">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="action-btn secondary" 
                                                            onclick="editEvaluation(<?= $eval['id'] ?>)" 
                                                            title="Modifier l'évaluation">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="action-btn danger" 
                                                            onclick="deleteEvaluation(<?= $eval['id'] ?>)" 
                                                            title="Supprimer l'évaluation">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Modern Pagination -->
                        <?php if (isset($pagination) && $pagination['pages'] > 1): ?>
                            <div class="modern-pagination">
                                <div class="pagination-info">
                                    <span>Page <?= $pagination['current'] ?> sur <?= $pagination['pages'] ?></span>
                                    <span>(<?= $pagination['total'] ?> résultats au total)</span>
                                </div>
                                <div class="pagination-controls">
                                    <?php if ($pagination['current'] > 1): ?>
                                        <a href="?page=<?= $pagination['current'] - 1 ?><?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="pagination-btn prev">
                                            <i class="fas fa-chevron-left"></i>
                                            <span>Précédent</span>
                                        </a>
                                    <?php endif; ?>

                                    <div class="pagination-numbers">
                                        <?php for ($i = max(1, $pagination['current'] - 2); $i <= min($pagination['pages'], $pagination['current'] + 2); $i++): ?>
                                            <a href="?page=<?= $i ?><?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                                               class="pagination-number <?= $i == $pagination['current'] ? 'active' : '' ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                    </div>

                                    <?php if ($pagination['current'] < $pagination['pages']): ?>
                                        <a href="?page=<?= $pagination['current'] + 1 ?><?= isset($_GET['score']) ? '&score=' . urlencode($_GET['score']) : '' ?><?= isset($_GET['theme_id']) ? '&theme_id=' . urlencode($_GET['theme_id']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="pagination-btn next">
                                            <span>Suivant</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <!-- Modern Empty State -->
                    <div class="modern-empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>Aucune évaluation trouvée</h3>
                        <p>
                            <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['score']) || !empty($_GET['period'])): ?>
                                Aucune évaluation ne correspond à vos critères de recherche.<br>
                                Essayez de modifier vos filtres pour obtenir plus de résultats.
                            <?php else: ?>
                                Vous n'avez pas encore effectué d'évaluations.<br>
                                Commencez par explorer les idées soumises et donnez votre avis !
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($_GET['search']) || !empty($_GET['theme_id']) || !empty($_GET['score']) || !empty($_GET['period'])): ?>
                            <a href="/evaluateur/my-evaluations" class="modern-action-btn primary">
                                <i class="fas fa-refresh"></i>
                                <span>Réinitialiser les filtres</span>
                            </a>
                        <?php else: ?>
                            <a href="/evaluateur/review" class="modern-action-btn primary">
                                <i class="fas fa-lightbulb"></i>
                                <span>Découvrir les idées</span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function editEvaluation(evaluationId) {
    // Redirect to the idea detail page for editing
    fetch(`/api/evaluation/${evaluationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/evaluateur/review/${data.idea_id}`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la récupération de l\'évaluation', 'error');
        });
}

function deleteEvaluation(evaluationId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?')) {
        return;
    }

    fetch(`/evaluateur/evaluation/${evaluationId}/delete`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Évaluation supprimée avec succès', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de la suppression', 'error');
    });
}

function exportEvaluations() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.location.href = '/evaluateur/my-evaluations?' + params.toString();
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
<!-- Modern JavaScript -->
<script>
// Export Evaluations Functionality
function exportEvaluations() {
    const button = event.target.closest('.modern-action-btn');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Export...</span>';
    button.disabled = true;
    
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    
    // Create download link
    const link = document.createElement('a');
    link.href = '/evaluateur/my-evaluations?' + params.toString();
    link.download = 'mes-evaluations-' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
        showNotification('Export terminé avec succès', 'success');
    }, 1000);
}

// Edit Evaluation Functionality
function editEvaluation(evaluationId) {
    showNotification('Fonction de modification en cours de développement', 'info');
    // This would typically open a modal or redirect to an edit page
    // For now, we'll just show a notification
}

// Delete Evaluation Functionality
function deleteEvaluation(evaluationId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ? Cette action est irréversible.')) {
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        fetch('/evaluateur/evaluations/' + evaluationId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?? '' ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row with animation
                const row = button.closest('tr');
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.remove();
                    showNotification('Évaluation supprimée avec succès', 'success');
                }, 300);
            } else {
                showNotification('Erreur lors de la suppression', 'error');
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur de connexion', 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}

// Show Full Comment Modal
function showFullComment(comment) {
    // Remove existing modal if any
    const existingModal = document.getElementById('commentModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Create modal
    const modal = document.createElement('div');
    modal.id = 'commentModal';
    modal.className = 'modern-modal';
    modal.innerHTML = `
        <div class="modal-backdrop" onclick="closeCommentModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-comment"></i>
                    Mon commentaire complet
                </h3>
                <button onclick="closeCommentModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="comment-full">
                    ${comment.replace(/\n/g, '<br>')}
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeCommentModal()" class="modern-action-btn primary">
                    <i class="fas fa-check"></i>
                    <span>Fermer</span>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('show'), 10);
}

function closeCommentModal() {
    const modal = document.getElementById('commentModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

// Dropdown Toggle Functionality
document.addEventListener('click', function(e) {
    const dropdownToggle = e.target.closest('.dropdown-toggle');
    if (dropdownToggle) {
        e.preventDefault();
        const dropdown = dropdownToggle.nextElementSibling;
        dropdown.classList.toggle('show');
    } else {
        // Close all dropdowns when clicking outside
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

// Modern Notification System
function showNotification(message, type) {
    // Remove existing notifications
    const existing = document.querySelectorAll('.modern-notification');
    existing.forEach(notification => notification.remove());
    
    // Create new notification
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Export with 'E' key
        if (e.key === 'e' || e.key === 'E') {
            if (!e.target.matches('input, textarea, select')) {
                exportEvaluations();
            }
        }
        
        // Close modal with Escape key
        if (e.key === 'Escape') {
            closeCommentModal();
        }
    });
    
    // Add smooth hover effects to table rows
    document.querySelectorAll('.evaluation-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.002)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

<!-- Modern CSS Styles -->
<style>
/* Modern My Evaluations Page Styles */
.modern-my-evaluations-page {
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
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
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

.modern-action-btn.secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card.primary::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success::before { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.stat-card.warning::before { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.stat-card.info::before { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
.stat-card.secondary::before { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
.stat-card.accent::before { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }

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
.stat-card.secondary .stat-icon { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
.stat-card.accent .stat-icon { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }

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

/* Statistics Summary Bar */
.stats-summary-bar {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.summary-label {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

.summary-progress {
    height: 8px;
    background: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
    transition: width 0.6s ease;
    position: relative;
}

.progress-bar.quality {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.progress-bar.activity {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.summary-value {
    font-weight: 700;
    color: #1e293b;
    font-size: 1.1rem;
    text-align: right;
}

/* Enhanced Header Stats */
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

/* Filters Section */
.modern-filters-section {
    margin-bottom: 2rem;
}

.modern-filters-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
}

.filters-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 0.5rem 0;
}

.filters-header p {
    color: #666;
    margin: 0 0 1.5rem 0;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.search-group {
    grid-column: span 2;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.modern-select, .modern-search-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.modern-select:focus, .modern-search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.search-input-wrapper {
    position: relative;
    display: flex;
}

.search-submit-btn {
    position: absolute;
    right: 4px;
    top: 4px;
    bottom: 4px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-submit-btn:hover {
    background: #5a67d8;
}

.filters-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Evaluations Section */
.modern-evaluations-section {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.modern-evaluations-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.evaluations-title h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 0.5rem 0;
}

.results-count {
    background: #667eea;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-left: 1rem;
}

.evaluations-title p {
    color: #666;
    margin: 0;
}

.evaluations-actions {
    display: flex;
    gap: 1rem;
}

.sort-dropdown {
    position: relative;
}

.dropdown-toggle {
    background: none !important;
    border: none !important;
    padding: 0 !important;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    padding: 0.5rem 0;
    min-width: 200px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: #f8fafc;
    color: #667eea;
}

.modern-evaluations-content {
    padding: 2rem;
}

/* Table Styles */
.modern-evaluations-table {
    background: white;
    border-radius: 16px;
    overflow: hidden;
}

.table-container {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.modern-table thead th {
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

.modern-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.001);
}

.modern-table td {
    padding: 1.5rem 1rem;
    vertical-align: top;
}

.idea-info {
    max-width: 300px;
}

.idea-title {
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.idea-excerpt {
    color: #64748b;
    margin: 0;
    line-height: 1.5;
    font-size: 0.9rem;
}

.theme-tag {
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

.my-rating {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.star-rating {
    display: flex;
    gap: 2px;
}

.star-rating i {
    font-size: 0.9rem;
    color: #e2e8f0;
    transition: all 0.3s ease;
}

.star-rating i.active {
    color: #fbbf24;
}

.score-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.score-badge.score-high {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
}

.score-badge.score-medium {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.score-badge.score-low {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

.average-rating {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.average-score {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.evaluations-count {
    color: #64748b;
    font-size: 0.8rem;
}

.single-evaluation {
    color: #94a3b8;
    font-style: italic;
    font-size: 0.9rem;
}

.comment-preview {
    max-width: 250px;
    position: relative;
}

.comment-text {
    display: block;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.expand-comment-btn {
    background: #f1f5f9;
    border: none;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    color: #667eea;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.expand-comment-btn:hover {
    background: #e2e8f0;
}

.no-comment {
    color: #94a3b8;
    font-style: italic;
    font-size: 0.9rem;
}

.evaluation-date {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.evaluation-date .date {
    font-weight: 600;
    color: #1e293b;
}

.evaluation-date .time {
    color: #64748b;
    font-size: 0.85rem;
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

.action-btn.danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Pagination */
.modern-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding: 1.5rem 0;
    border-top: 1px solid #f1f5f9;
}

.pagination-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    color: #64748b;
    font-size: 0.9rem;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.pagination-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    color: #667eea;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.pagination-btn:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.pagination-numbers {
    display: flex;
    gap: 0.5rem;
}

.pagination-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #f8fafc;
    color: #667eea;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.pagination-number:hover,
.pagination-number.active {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

/* Empty State */
.modern-empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 20px;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem auto;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #94a3b8;
}

.modern-empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
}

.modern-empty-state p {
    color: #64748b;
    font-size: 1.1rem;
    margin: 0 0 2rem 0;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

/* Modal Styles */
.modern-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modern-modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: white;
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow: hidden;
    position: relative;
    transform: scale(0.9);
    transition: all 0.3s ease;
}

.modern-modal.show .modal-content {
    transform: scale(1);
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f1f5f9;
    color: #667eea;
}

.modal-body {
    padding: 2rem;
    overflow-y: auto;
    max-height: 60vh;
}

.comment-full {
    color: #333;
    line-height: 1.7;
    font-size: 1rem;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
}

/* Notification System */
.modern-notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    z-index: 9999;
    min-width: 300px;
    animation: slideInRight 0.3s ease;
}

.modern-notification.success {
    border-left: 4px solid #10b981;
}

.modern-notification.error {
    border-left: 4px solid #ef4444;
}

.modern-notification.info {
    border-left: 4px solid #3b82f6;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-content i {
    font-size: 1.2rem;
}

.modern-notification.success .notification-content i {
    color: #10b981;
}

.modern-notification.error .notification-content i {
    color: #ef4444;
}

.modern-notification.info .notification-content i {
    color: #3b82f6;
}

.notification-close {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.notification-close:hover {
    background: #f1f5f9;
    color: #64748b;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .search-group {
        grid-column: span 1;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-summary-bar {
        grid-template-columns: 1fr;
        gap: 1.5rem;
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
    
    .stat-card {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
        min-height: auto;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .stat-content h3 {
        font-size: 2rem;
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
    }
    
    .modern-evaluations-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .modern-pagination {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .table-container {
        overflow-x: scroll;
    }
    
    .modern-notification {
        left: 1rem;
        right: 1rem;
        min-width: auto;
    }
    
    .modal-content {
        margin: 1rem;
        max-height: 90vh;
    }
}

/* Print Styles */
@media print {
    .modern-header-section,
    .modern-filters-section,
    .table-actions,
    .modern-notification,
    .modern-modal {
        display: none !important;
    }
    
    .modern-my-evaluations-page {
        background: white !important;
    }
    
    .modern-table {
        break-inside: auto;
    }
    
    .evaluation-row {
        break-inside: avoid;
    }
}
</style>
