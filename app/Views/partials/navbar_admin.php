<nav class="sidebar-nav">
    <ul class="nav-list">
        <li class="nav-item">
            <a href="/admin" class="nav-link <?= $_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '' ?>">
                <i class="icon-dashboard"></i>
                <span>Tableau de Bord</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="/admin/users" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0 ? 'active' : '' ?>">
                <i class="icon-users"></i>
                <span>Utilisateurs</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="/admin/themes" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/themes') === 0 ? 'active' : '' ?>">
                <i class="icon-themes"></i>
                <span>Thématiques</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="/admin/ideas" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/ideas') === 0 ? 'active' : '' ?>">
                <i class="icon-ideas"></i>
                <span>Idées</span>
            </a>
        </li>
        
        <li class="nav-divider"></li>
        
        <li class="nav-item">
            <a href="/admin/reports" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/reports') === 0 ? 'active' : '' ?>">
                <i class="icon-reports"></i>
                <span>Rapports</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="/admin/settings" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') === 0 ? 'active' : '' ?>">
                <i class="icon-settings"></i>
                <span>Paramètres</span>
            </a>
        </li>
        
        <li class="nav-divider"></li>
        
        <!-- User Info -->
        <li class="nav-user">
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                <div class="user-role">Administrateur</div>
            </div>
        </li>
        
        <li class="nav-item">
            <form method="POST" action="/logout" class="logout-form">
                <?= csrf_field() ?>
                <button type="submit" class="nav-link btn-logout" data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?">
                    <i class="icon-logout"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </li>
    </ul>
</nav>

<style>
.sidebar-nav {
    padding: 0;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    border-bottom: 1px solid #455a75;
}

.nav-link {
    display: flex;
    align-items: center;
    color: #bdc3c7;
    padding: 1rem 1.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.nav-link:hover,
.nav-link.active {
    background-color: #455a75;
    color: white;
}

.nav-link i {
    margin-right: 0.75rem;
    width: 16px;
    text-align: center;
}

.nav-divider {
    height: 1px;
    background-color: #455a75;
    margin: 0.5rem 0;
}

.nav-user {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #455a75;
}

.user-info {
    text-align: center;
}

.user-name {
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.user-role {
    font-size: 0.875rem;
    color: #95a5a6;
}

.logout-form {
    margin: 0;
}

.btn-logout {
    border: none;
    background: none;
    color: #e74c3c;
}

.btn-logout:hover {
    background-color: #c0392b;
    color: white;
}

/* Modern Icons using Unicode */
.nav-link i::before {
    font-style: normal;
    font-size: 1rem;
}

.icon-dashboard::before { content: "📊"; }
.icon-users::before { content: "👥"; }
.icon-themes::before { content: "🏷️"; }
.icon-ideas::before { content: "💡"; }
.icon-reports::before { content: "📈"; }
.icon-settings::before { content: "⚙️"; }
.icon-logout::before { content: "🚪"; }

/* Mobile Sidebar Toggle */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 1001;
    background: #1e293b;
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 0.5rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

/* Responsive Sidebar */
@media (max-width: 1024px) {
    .admin-sidebar {
        transform: translateX(-100%);
    }
    
    .admin-sidebar.open {
        transform: translateX(0);
    }
    
    .admin-content {
        margin-left: 0;
    }
    
    .sidebar-toggle {
        display: block;
    }
    
    .sidebar-overlay.show {
        display: block;
    }
}
</style>
