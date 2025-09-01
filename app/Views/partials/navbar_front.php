<nav class="navbar">
    <div class="navbar-brand">
        <a href="/" class="navbar-brand-link">
            <?= htmlspecialchars($config['app']['name'] ?? 'Company Ideas Management') ?>
        </a>
    </div>
    
    <ul class="navbar-nav">
        <?php if ($user): ?>
            
            <!-- Role-specific navigation -->
            <?php if ($user['role'] === 'salarie'): ?>
                <li class="nav-item">
                    <a href="/salarie/ideas" class="nav-link">Mes Idées</a>
                </li>
                <li class="nav-item">
                    <a href="/salarie/ideas/create" class="nav-link">Nouvelle Idée</a>
                </li>
            <?php elseif ($user['role'] === 'evaluateur'): ?>
                <li class="nav-item">
                    <a href="/evaluateur/reviews" class="nav-link">Évaluations</a>
                </li>
                <li class="nav-item">
                    <a href="/evaluateur/reviews?status=pending" class="nav-link">En Attente</a>
                </li>
            <?php endif; ?>
            
            <!-- User menu -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'evaluateur' ? 'warning' : 'info') ?>">
                        <?= htmlspecialchars(ucfirst($user['role'])) ?>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/profile" class="dropdown-link">Mon Profil</a></li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="/logout" style="display: inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="dropdown-link btn-link">Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </li>
            
        <?php else: ?>
            <li class="nav-item">
                <a href="/login" class="nav-link">Connexion</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
