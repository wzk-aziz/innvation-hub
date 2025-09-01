# 🚀 Innovation Hub - Complete Setup Guide

This guide will help you set up the Innovation Hub project on any local machine. Follow these steps carefully to get the application running.

## 📋 Table of Contents
1. [Prerequisites](#prerequisites)
2. [Quick Setup (Recommended)](#quick-setup)
3. [Manual Setup (Advanced)](#manual-setup)
4. [Verification Steps](#verification)
5. [Troubleshooting](#troubleshooting)
6. [Default Login Credentials](#credentials)

---

## 🔧 Prerequisites

Before starting, ensure you have the following installed:

### Required Software:
- **PHP 8.0 or higher** 
  - Download: https://www.php.net/downloads
  - Windows: Use XAMPP, WAMP, or Laragon
  - Mac: Use MAMP or install via Homebrew
  - Linux: Install via package manager

- **MySQL 5.7 or higher** (or MariaDB)
  - Usually comes with XAMPP/WAMP/MAMP
  - Download: https://dev.mysql.com/downloads/

- **Web Server**
  - Apache (recommended - included in XAMPP/WAMP/MAMP)
  - Or Nginx

### Optional but Recommended:
- **Git** for version control
- **VSCode** or any code editor
- **Composer** (PHP dependency manager)

---

## ⚡ Quick Setup (Recommended)

### Option 1: Using XAMPP (Windows/Mac/Linux)

1. **Download and Install XAMPP**
   ```
   Download from: https://www.apachefriends.org/
   Install with default settings
   ```

2. **Start Services**
   ```
   Open XAMPP Control Panel
   Start Apache and MySQL services
   ```

3. **Clone/Download Project**
   ```bash
   # If using Git:
   cd C:\xampp\htdocs\  # Windows
   cd /Applications/XAMPP/htdocs/  # Mac
   cd /opt/lampp/htdocs/  # Linux
   
   git clone [your-repo-url] innovation-hub
   
   # Or extract downloaded ZIP to the above folder
   ```

4. **Setup Database**
   ```
   Open browser: http://localhost/phpmyadmin
   Create new database: "company_ideas"
   Import files in this order:
     1. sql/schema.sql (creates tables)
     2. sql/seed.sql (adds sample data)
   ```

5. **Configure Environment**
   ```bash
   # Navigate to project folder
   cd innovation-hub
   
   # Copy environment file
   copy .env.example .env  # Windows
   cp .env.example .env    # Mac/Linux
   
   # Edit .env file with your database settings:
   DB_HOST=localhost
   DB_NAME=company_ideas
   DB_USER=root
   DB_PASS=  # Leave empty for XAMPP default
   ```

6. **Set Permissions** (Mac/Linux only)
   ```bash
   chmod -R 755 .
   chmod -R 777 public/assets/uploads/  # If exists
   ```

7. **Access Application**
   ```
   Open browser: http://localhost/innovation-hub/public
   ```

---

## 🔨 Manual Setup (Advanced)

### Step 1: Environment Preparation

1. **PHP Configuration**
   ```bash
   # Check PHP version (must be 8.0+)
   php --version
   
   # Enable required extensions in php.ini:
   extension=pdo_mysql
   extension=mysqli
   extension=mbstring
   extension=openssl
   extension=curl
   extension=gd
   extension=fileinfo
   ```

2. **Web Server Configuration**
   ```apache
   # Apache .htaccess (included in project)
   # Ensure mod_rewrite is enabled
   
   # Virtual Host Example (optional):
   <VirtualHost *:80>
       DocumentRoot "C:/path/to/innovation-hub/public"
       ServerName innovation-hub.local
       <Directory "C:/path/to/innovation-hub/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

### Step 2: Database Setup

1. **Create Database**
   ```sql
   # Connect to MySQL
   mysql -u root -p
   
   # Create database
   CREATE DATABASE company_ideas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   
   # Create user (optional)
   CREATE USER 'ideas_user'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON company_ideas.* TO 'ideas_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Import Schema and Data**
   ```bash
   # Import database structure
   mysql -u root -p company_ideas < sql/schema.sql
   
   # Import sample data
   mysql -u root -p company_ideas < sql/seed.sql
   ```

### Step 3: Project Configuration

1. **Environment Variables**
   ```bash
   # Copy and edit .env file
   cp .env.example .env
   
   # Edit .env with your settings:
   DB_HOST=localhost
   DB_NAME=company_ideas
   DB_USER=root  # or your MySQL user
   DB_PASS=your_password
   
   APP_ENV=development
   APP_DEBUG=true
   APP_URL=http://localhost/innovation-hub/public
   APP_KEY=generate-a-random-32-character-string
   ```

2. **File Permissions**
   ```bash
   # Linux/Mac
   chmod -R 755 .
   chmod -R 777 storage/  # If exists
   chmod -R 777 public/assets/uploads/  # For file uploads
   
   # Windows (using Command Prompt as Administrator)
   icacls . /grant Everyone:F /T
   ```

3. **Dependencies** (Optional)
   ```bash
   # If using Composer
   composer install --no-dev
   ```

---

## ✅ Verification Steps

### 1. Check PHP Setup
```bash
# Test PHP
php -m | grep -E "(pdo|mysql|mbstring)"

# Test application
php -S localhost:8000 -t public/
# Then visit: http://localhost:8000
```

### 2. Database Verification
```sql
# Connect to MySQL and verify
USE company_ideas;
SHOW TABLES;

# Should show these tables:
# - users
# - themes  
# - ideas
# - evaluations
# - feedback
# - idea_attachments (optional)

# Check sample data
SELECT * FROM users;
SELECT * FROM themes;
```

### 3. Application Test
```bash
# Visit in browser:
http://localhost/innovation-hub/public

# Or if using PHP built-in server:
http://localhost:8000

# You should see the login page
```

---

## 🔑 Default Login Credentials

| Role | Email | Password | Access |
|------|-------|----------|---------|
| **Admin** | admin@company.com | password123 | Full system access |
| **Employee** | marie.dupont@company.com | password123 | Submit & manage ideas |
| **Employee** | pierre.martin@company.com | password123 | Submit & manage ideas |
| **Evaluator** | sophie.bernard@company.com | password123 | Review & rate ideas |
| **Evaluator** | jean.rousseau@company.com | password123 | Review & rate ideas |

### Security Note:
**⚠️ Change these passwords immediately in production!**

---

## 🔧 Troubleshooting

### Common Issues:

#### 1. "Class not found" errors
```bash
# Solution: Check autoloader
composer dump-autoload

# Or verify include paths in config/config.php
```

#### 2. Database connection errors
```bash
# Check .env file settings
# Verify MySQL service is running
# Test connection:
mysql -u root -p -h localhost
```

#### 3. 404 errors / Routing issues
```bash
# Apache: Ensure mod_rewrite is enabled
sudo a2enmod rewrite
sudo service apache2 restart

# Check .htaccess file exists in public/ folder
```

#### 4. Permission errors
```bash
# Linux/Mac:
sudo chown -R www-data:www-data .
chmod -R 755 .

# Windows:
# Run as administrator and check folder permissions
```

#### 5. PHP errors
```bash
# Check error logs:
# XAMPP: xampp/apache/logs/error.log
# Linux: /var/log/apache2/error.log
# Windows: Check Event Viewer

# Enable error display in .env:
APP_DEBUG=true
```

### Getting Help:

1. **Check error logs** (browser console, PHP error log)
2. **Verify all prerequisites** are installed
3. **Double-check configuration** files
4. **Test database connection** separately
5. **Ensure proper file permissions**

---

## 📁 Project Structure Overview

```
innovation-hub/
├── 📁 app/                  # Application core
│   ├── 📁 Controllers/      # Business logic
│   ├── 📁 Core/            # Framework core
│   ├── 📁 Models/          # Data models
│   ├── 📁 Views/           # Templates
│   └── 📁 Validators/      # Input validation
├── 📁 config/              # Configuration
├── 📁 public/              # Web root (point Apache here)
│   ├── 📁 assets/          # CSS, JS, images
│   ├── .htaccess          # URL rewriting
│   └── index.php          # Entry point
├── 📁 sql/                 # Database files
│   ├── schema.sql         # Database structure
│   └── seed.sql           # Sample data
├── .env.example           # Environment template
├── .env                   # Your configuration
└── README.md              # Project documentation
```

---

## 🎯 Next Steps After Setup

1. **Login** with admin credentials
2. **Explore the interface** - check all role dashboards
3. **Test functionality** - create ideas, evaluate them
4. **Customize** themes and users as needed
5. **Change default passwords** for security
6. **Configure** email settings (if needed)

---

## 🚀 Development Tips

### For Developers:
- Use `APP_DEBUG=true` during development
- Check `public/assets/` for frontend files
- Database migrations are in `sql/` folder
- Follow MVC pattern for new features

### For Deployment:
- Set `APP_DEBUG=false` in production
- Use strong database passwords
- Configure proper web server security
- Set up regular database backups

---

**🎉 Congratulations! Your Innovation Hub should now be running successfully.**

For additional support or customization, refer to the main README.md file or contact the development team.
