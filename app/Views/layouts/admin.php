<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= htmlspecialchars($config['app']['name']) ?></title>
    
    <!-- Security Headers - Disabled for development -->
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self' http://localhost:8000; style-src 'self' 'unsafe-inline' http://localhost:8000; script-src 'self' 'unsafe-inline' http://localhost:8000; img-src 'self' data: http://localhost:8000;"> -->
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
</head>
<body class="admin-layout">
    
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <span class="hamburger-icon">☰</span>
    </button>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h2><?= htmlspecialchars($config['app']['name']) ?></h2>
            <p class="sidebar-subtitle">Administration</p>
        </div>
        
        <?php $this->partial('navbar_admin', ['user' => $authUser]); ?>
    </aside>
    
    <!-- Main Content Area -->
    <main class="admin-content">
        <!-- Top Bar for Mobile -->
        <div class="admin-topbar">
            <div class="topbar-left">
                <h1 class="page-title-mobile"><?= $pageTitle ?? 'Administration' ?></h1>
            </div>
            <div class="topbar-right">
                <div class="user-menu">
                    <div class="user-avatar-small">
                        <?= strtoupper(substr($authUser['first_name'], 0, 1) . substr($authUser['last_name'], 0, 1)) ?>
                    </div>
                    <span class="user-name-mobile"><?= htmlspecialchars($authUser['first_name']) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Flash Messages -->
        <?php if (!empty($flashMessages)): ?>
            <?php $this->partial('flash', ['messages' => $flashMessages]); ?>
        <?php endif; ?>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <?= $content ?>
        </div>
    </main>
    
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
        
        // Sidebar functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            body.classList.toggle('sidebar-open');
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;
            
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            body.classList.remove('sidebar-open');
        }
        
        // Close sidebar when clicking on main content on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.admin-content');
            if (content) {
                content.addEventListener('click', function(e) {
                    if (window.innerWidth <= 1024) {
                        closeSidebar();
                    }
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>
