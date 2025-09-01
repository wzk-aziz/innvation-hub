<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Config;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Ensure user is authenticated and is admin
        if (!Auth::check() || Auth::user()['role'] !== 'Admin') {
            $this->redirect('/login');
        }
    }

    public function index(): void
    {
        $data = [
            'pageTitle' => 'Paramètres du Système',
            'settings' => $this->getCurrentSettings()
        ];

        $this->view('admin/settings/index', $data, 'admin');
    }

    public function general(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleGeneralSettings();
            return;
        }

        $data = [
            'pageTitle' => 'Paramètres Généraux',
            'settings' => $this->getCurrentSettings()
        ];

        $this->view('admin/settings/general', $data, 'admin');
    }

    public function security(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSecuritySettings();
            return;
        }

        $data = [
            'pageTitle' => 'Paramètres de Sécurité',
            'settings' => $this->getCurrentSettings()
        ];

        $this->view('admin/settings/security', $data, 'admin');
    }

    public function email(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEmailSettings();
            return;
        }

        $data = [
            'pageTitle' => 'Paramètres Email',
            'settings' => $this->getCurrentSettings()
        ];

        $this->view('admin/settings/email', $data, 'admin');
    }

    public function backup(): void
    {
        $data = [
            'pageTitle' => 'Sauvegarde et Restauration',
            'backups' => $this->getAvailableBackups()
        ];

        $this->view('admin/settings/backup', $data, 'admin');
    }

    public function createBackup(): void
    {
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashMessage('error', 'Token CSRF invalide.');
            $this->redirect('/admin/settings/backup');
        }

        try {
            $backupFile = $this->createDatabaseBackup();
            $this->flashMessage('success', "Sauvegarde créée avec succès: {$backupFile}");
        } catch (Exception $e) {
            $this->flashMessage('error', 'Erreur lors de la création de la sauvegarde: ' . $e->getMessage());
        }

        $this->redirect('/admin/settings/backup');
    }

    private function handleGeneralSettings(): void
    {
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashMessage('error', 'Token CSRF invalide.');
            $this->redirect('/admin/settings/general');
        }

        $settings = [
            'app_name' => trim($_POST['app_name'] ?? ''),
            'app_description' => trim($_POST['app_description'] ?? ''),
            'maintenance_mode' => isset($_POST['maintenance_mode']),
            'allow_registration' => isset($_POST['allow_registration']),
            'ideas_require_approval' => isset($_POST['ideas_require_approval'])
        ];

        if ($this->updateSettings($settings)) {
            $this->flashMessage('success', 'Paramètres généraux mis à jour avec succès.');
        } else {
            $this->flashMessage('error', 'Erreur lors de la mise à jour des paramètres.');
        }

        $this->redirect('/admin/settings/general');
    }

    private function handleSecuritySettings(): void
    {
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashMessage('error', 'Token CSRF invalide.');
            $this->redirect('/admin/settings/security');
        }

        $settings = [
            'session_timeout' => (int)($_POST['session_timeout'] ?? 3600),
            'max_login_attempts' => (int)($_POST['max_login_attempts'] ?? 5),
            'password_min_length' => (int)($_POST['password_min_length'] ?? 6),
            'require_strong_passwords' => isset($_POST['require_strong_passwords']),
            'enable_two_factor' => isset($_POST['enable_two_factor'])
        ];

        if ($this->updateSettings($settings)) {
            $this->flashMessage('success', 'Paramètres de sécurité mis à jour avec succès.');
        } else {
            $this->flashMessage('error', 'Erreur lors de la mise à jour des paramètres.');
        }

        $this->redirect('/admin/settings/security');
    }

    private function handleEmailSettings(): void
    {
        if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashMessage('error', 'Token CSRF invalide.');
            $this->redirect('/admin/settings/email');
        }

        $settings = [
            'smtp_host' => trim($_POST['smtp_host'] ?? ''),
            'smtp_port' => (int)($_POST['smtp_port'] ?? 587),
            'smtp_username' => trim($_POST['smtp_username'] ?? ''),
            'smtp_password' => trim($_POST['smtp_password'] ?? ''),
            'smtp_encryption' => $_POST['smtp_encryption'] ?? 'tls',
            'from_email' => trim($_POST['from_email'] ?? ''),
            'from_name' => trim($_POST['from_name'] ?? '')
        ];

        if ($this->updateSettings($settings)) {
            $this->flashMessage('success', 'Paramètres email mis à jour avec succès.');
        } else {
            $this->flashMessage('error', 'Erreur lors de la mise à jour des paramètres.');
        }

        $this->redirect('/admin/settings/email');
    }

    private function getCurrentSettings(): array
    {
        // In a real app, these would come from a database settings table
        return [
            'app_name' => Config::get('app.name', 'Company Ideas Management'),
            'app_description' => 'Système de gestion des idées d\'entreprise',
            'maintenance_mode' => false,
            'allow_registration' => true,
            'ideas_require_approval' => true,
            'session_timeout' => 3600,
            'max_login_attempts' => 5,
            'password_min_length' => 6,
            'require_strong_passwords' => false,
            'enable_two_factor' => false,
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_encryption' => 'tls',
            'from_email' => '',
            'from_name' => ''
        ];
    }

    private function updateSettings(array $settings): bool
    {
        // In a real app, this would update the database settings table
        // For now, we'll just simulate success
        return true;
    }

    private function getAvailableBackups(): array
    {
        $backupDir = ROOT_PATH . '/storage/backups';
        $backups = [];

        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/backup_*.sql');
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'created_at' => date('Y-m-d H:i:s', filemtime($file))
                ];
            }
        }

        return array_reverse($backups); // Most recent first
    }

    private function createDatabaseBackup(): string
    {
        $backupDir = ROOT_PATH . '/storage/backups';
        
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . '/' . $filename;

        $config = Config::get('database');
        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $filepath
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception('Backup command failed');
        }

        return $filename;
    }
}
