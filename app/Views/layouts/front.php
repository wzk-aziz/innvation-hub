<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Innovation Hub' ?> - Espace Salarié</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        /* Icon mapping for consistent usage */
        .icon-lightbulb::before { content: "\f0eb"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-plus::before { content: "\f067"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-clock::before { content: "\f017"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-check::before { content: "\f00c"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-star::before { content: "\f005"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-tag::before { content: "\f02b"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-calendar::before { content: "\f073"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-eye::before { content: "\f06e"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-edit::before { content: "\f044"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-message::before { content: "\f4ad"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-arrow-left::before { content: "\f060"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-send::before { content: "\f1d8"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-save::before { content: "\f0c7"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-x::before { content: "\f00d"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-help-circle::before { content: "\f059"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-chart::before { content: "\f080"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-file-text::before { content: "\f15c"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-target::before { content: "\f140"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-tool::before { content: "\f0ad"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-message-circle::before { content: "\f4ad"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-activity::before { content: "\f201"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-bar-chart::before { content: "\f080"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-settings::before { content: "\f013"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-download::before { content: "\f019"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-share::before { content: "\f064"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-trash::before { content: "\f1f8"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-cog::before { content: "\f013"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-info::before { content: "\f129"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-alert-triangle::before { content: "\f071"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-home::before { content: "\f015"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-trophy::before { content: "\f091"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-history::before { content: "\f1da"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-menu::before { content: "\f0c9"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-bell::before { content: "\f0f3"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-user::before { content: "\f007"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-logout::before { content: "\f2f5"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-user::before { content: "\f007"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-home::before { content: "\f015"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-logout::before { content: "\f2f5"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-bell::before { content: "\f0f3"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
        .icon-menu::before { content: "\f0c9"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
    </style>
</head>
<body class="front-layout">
    <!-- Navigation Header -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Brand -->
            <div class="navbar-brand">
                <?php 
                $currentUser = $user ?? $authUser ?? [];
                $userRole = $currentUser['role'] ?? 'user';
                $brandText = match($userRole) {
                    'admin' => 'Admin',
                    'salarie' => 'Salarié', 
                    'evaluateur' => 'Évaluateur',
                    default => 'User'
                };
                $homeUrl = match($userRole) {
                    'admin' => '/admin',
                    'salarie' => '/salarie/ideas',
                    'evaluateur' => '/evaluateur/dashboard',
                    default => '/'
                };
                ?>
                <a href="<?= $homeUrl ?>" class="brand-link">
                    <i class="icon-lightbulb"></i>
                    <span class="brand-text">Innovation Hub</span>
                    <span class="brand-badge"><?= $brandText ?></span>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="icon-menu"></i>
            </button>

            <!-- Navigation Links -->
            <div class="navbar-nav" id="navbarNav">
                <?php if ($userRole === 'salarie'): ?>
                    <a href="/salarie/ideas" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/salarie/ideas' ? 'active' : '' ?>">
                        <i class="icon-home"></i>
                        <span>Mes Idées</span>
                    </a>
                    <a href="/salarie/ideas/create" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/salarie/ideas/create') !== false ? 'active' : '' ?>">
                        <i class="icon-plus"></i>
                        <span>Nouvelle Idée</span>
                    </a>
                <?php elseif ($userRole === 'evaluateur'): ?>
                    <a href="/evaluateur/dashboard" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/evaluateur/dashboard' ? 'active' : '' ?>">
                        <i class="icon-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="/evaluateur/review" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/evaluateur/review') !== false ? 'active' : '' ?>">
                        <i class="icon-star"></i>
                        <span>Évaluer</span>
                    </a>
                    <a href="/evaluateur/best-ideas" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/evaluateur/best-ideas') !== false ? 'active' : '' ?>">
                        <i class="icon-trophy"></i>
                        <span>Top Idées</span>
                    </a>
                    <a href="/evaluateur/my-evaluations" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/evaluateur/my-evaluations') !== false ? 'active' : '' ?>">
                        <i class="icon-history"></i>
                        <span>Mes évaluations</span>
                    </a>
                    <a href="/evaluateur/statistics" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/evaluateur/statistics') !== false ? 'active' : '' ?>">
                        <i class="icon-chart"></i>
                        <span>Statistiques</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- User Menu -->
            <div class="navbar-user">
                <!-- Notifications -->
                <div class="notification-dropdown">
                    <button class="notification-btn" onclick="toggleNotifications()">
                        <i class="icon-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="notification-menu" id="notificationMenu">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                            <button class="mark-all-read">Tout marquer lu</button>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="icon-star"></i>
                                </div>
                                <div class="notification-content">
                                    <p>Votre idée "Amélioration processus" a reçu une évaluation</p>
                                    <span class="notification-time">Il y a 2 heures</span>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="icon-message-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <p>Nouveau commentaire de l'administration sur votre idée</p>
                                    <span class="notification-time">Il y a 1 jour</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="icon-check"></i>
                                </div>
                                <div class="notification-content">
                                    <p>Votre idée "Innovation digitale" a été acceptée</p>
                                    <span class="notification-time">Il y a 3 jours</span>
                                </div>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="/salarie/notifications">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="user-btn" onclick="toggleUserMenu()">
                        <div class="user-avatar">
                            <i class="icon-user"></i>
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?= htmlspecialchars(($currentUser['first_name'] ?? $authUser['firstname'] ?? '') . ' ' . ($currentUser['last_name'] ?? $authUser['lastname'] ?? '')) ?></span>
                            <span class="user-role"><?= $brandText ?></span>
                        </div>
                    </button>
                    <div class="user-menu" id="userMenu">
                        <div class="user-menu-header">
                            <div class="user-avatar-large">
                                <i class="icon-user"></i>
                            </div>
                            <div class="user-details">
                                <strong><?= htmlspecialchars(($currentUser['first_name'] ?? $authUser['firstname'] ?? '') . ' ' . ($currentUser['last_name'] ?? $authUser['lastname'] ?? '')) ?></strong>
                                <span><?= htmlspecialchars($currentUser['email'] ?? $authUser['email'] ?? '') ?></span>
                            </div>
                        </div>
                        <div class="user-menu-items">
                            <?php if ($userRole === 'evaluateur'): ?>
                                <a href="/evaluateur/profile" class="user-menu-item">
                                    <i class="icon-user"></i>
                                    Mon Profil
                                </a>
                                <a href="/evaluateur/settings" class="user-menu-item">
                                    <i class="icon-settings"></i>
                                    Paramètres
                                </a>
                            <?php else: ?>
                                <a href="/salarie/profile" class="user-menu-item">
                                    <i class="icon-user"></i>
                                    Mon Profil
                                </a>
                                <a href="/salarie/settings" class="user-menu-item">
                                    <i class="icon-settings"></i>
                                    Paramètres
                                </a>
                            <?php endif; ?>
                            <hr class="user-menu-divider">
                            <form method="POST" action="/logout" style="margin: 0; padding: 0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="user-menu-item logout" style="width: 100%; border: none; background: none; text-align: left; padding: 12px 16px; cursor: pointer;">
                                    <i class="icon-logout"></i>
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="icon-check"></i>
                <span><?= htmlspecialchars($_SESSION['success']) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="icon-x"></i>
                </button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="icon-alert-triangle"></i>
                <span><?= htmlspecialchars($_SESSION['error']) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="icon-x"></i>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info">
                <i class="icon-info"></i>
                <span><?= htmlspecialchars($_SESSION['info']) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="icon-x"></i>
                </button>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <!-- Page Content -->
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>Innovation Hub</h4>
                <p>Plateforme collaborative d'innovation pour stimuler la créativité et l'amélioration continue.</p>
            </div>
            <div class="footer-section">
                <h4>Liens utiles</h4>
                <ul>
                    <li><a href="/help">Aide</a></li>
                    <li><a href="/guide">Guide d'utilisation</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Statistiques</h4>
                <div class="footer-stats">
                    <div class="stat">
                        <strong>250+</strong>
                        <span>Idées soumises</span>
                    </div>
                    <div class="stat">
                        <strong>89</strong>
                        <span>Idées implémentées</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Innovation Hub. Tous droits réservés.</p>
        </div>
    </footer>

    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #2d3748;
            line-height: 1.6;
        }

        .front-layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            height: 70px;
        }

        .navbar-brand .brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #2d3748;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .brand-text {
            color: #667eea;
        }

        .brand-badge {
            background: #667eea;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #4a5568;
            cursor: pointer;
            padding: 0.5rem;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #667eea;
            color: white;
        }

        /* User Section */
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Notifications */
        .notification-dropdown {
            position: relative;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #4a5568;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background 0.2s ease;
        }

        .notification-btn:hover {
            background: #f7fafc;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #e53e3e;
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 0.125rem 0.375rem;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .notification-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1001;
            margin-top: 0.5rem;
        }

        .notification-menu.show {
            display: block;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .notification-header h4 {
            margin: 0;
            color: #2d3748;
        }

        .mark-all-read {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid #f7fafc;
            transition: background 0.2s ease;
        }

        .notification-item:hover {
            background: #f7fafc;
        }

        .notification-item.unread {
            background: #f0f9ff;
            border-left: 3px solid #667eea;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-content p {
            margin: 0;
            color: #2d3748;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .notification-time {
            color: #718096;
            font-size: 0.8rem;
        }

        .notification-footer {
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .notification-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .user-btn:hover {
            background: #f7fafc;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.9rem;
        }

        .user-role {
            color: #718096;
            font-size: 0.8rem;
        }

        .user-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 280px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1001;
            margin-top: 0.5rem;
        }

        .user-menu.show {
            display: block;
        }

        .user-menu-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .user-avatar-large {
            width: 50px;
            height: 50px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-details strong {
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .user-details span {
            color: #718096;
            font-size: 0.8rem;
        }

        .user-menu-items {
            padding: 0.5rem 0;
        }

        .user-menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: #4a5568;
            transition: background 0.2s ease;
        }

        .user-menu-item:hover {
            background: #f7fafc;
        }

        .user-menu-item.logout {
            color: #e53e3e;
        }

        .user-menu-item.logout:hover {
            background: #fed7d7;
            color: #c53030;
        }

        /* Ensure logout button inherits all menu item styles */
        .user-menu-item.logout[type="submit"] {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: #e53e3e;
            transition: background 0.2s ease;
            font-family: inherit;
            font-size: inherit;
            width: 100%;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }

        .user-menu-divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 0.5rem 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
            min-height: calc(100vh - 140px);
        }

        /* Alerts */
        .alert {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem auto;
            max-width: 1200px;
            position: relative;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #feb2b2;
        }

        .alert-info {
            background: #bee3f8;
            color: #2a4365;
            border: 1px solid #90cdf4;
        }

        .alert-close {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Footer */
        .footer {
            background: #2d3748;
            color: white;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 1rem 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h4 {
            color: #667eea;
            margin-bottom: 1rem;
        }

        .footer-section p {
            color: #a0aec0;
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: #a0aec0;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-section ul li a:hover {
            color: #667eea;
        }

        .footer-stats {
            display: flex;
            gap: 2rem;
        }

        .stat {
            display: flex;
            flex-direction: column;
        }

        .stat strong {
            color: #667eea;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .stat span {
            color: #a0aec0;
            font-size: 0.9rem;
        }

        .footer-bottom {
            border-top: 1px solid #4a5568;
            padding: 1rem;
            text-align: center;
            color: #a0aec0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .navbar-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                flex-direction: column;
                padding: 1rem;
                gap: 0.5rem;
            }

            .navbar-nav.show {
                display: flex;
            }

            .user-info {
                display: none;
            }

            .notification-menu,
            .user-menu {
                width: 300px;
            }

            .footer-stats {
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            justify-content: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-secondary {
            background: #4a5568;
            color: white;
        }

        .btn-secondary:hover {
            background: #2d3748;
        }

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 1px solid #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }
    </style>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const nav = document.getElementById('navbarNav');
            nav.classList.toggle('show');
        }

        // Notification dropdown
        function toggleNotifications() {
            const menu = document.getElementById('notificationMenu');
            const userMenu = document.getElementById('userMenu');
            
            menu.classList.toggle('show');
            userMenu.classList.remove('show');
        }

        // User dropdown
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            const notificationMenu = document.getElementById('notificationMenu');
            
            menu.classList.toggle('show');
            notificationMenu.classList.remove('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const notificationDropdown = document.querySelector('.notification-dropdown');
            const userDropdown = document.querySelector('.user-dropdown');
            
            if (!notificationDropdown.contains(e.target)) {
                document.getElementById('notificationMenu').classList.remove('show');
            }
            
            if (!userDropdown.contains(e.target)) {
                document.getElementById('userMenu').classList.remove('show');
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>
                    <p>Système de gestion des idées d'innovation</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?= $this->asset('js/app.js') ?>"></script>
    
    <!-- CSRF Token for JavaScript -->
    <script>
        window.csrfToken = '<?= htmlspecialchars($csrfToken) ?>';
        window.appConfig = {
            baseUrl: '<?= htmlspecialchars($config['app']['url']) ?>',
            maxFileSize: <?= (int)$config['upload']['max_size'] ?>,
            allowedExtensions: <?= json_encode($config['upload']['allowed_extensions']) ?>
        };
    </script>
</body>
</html>
