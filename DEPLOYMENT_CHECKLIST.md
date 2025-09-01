# 📋 Deployment Checklist

Use this checklist when sharing the project or deploying to a new environment.

## 🔍 Pre-Sharing Checklist

### ✅ Files to Include:
- [ ] All source code (`app/`, `config/`, `public/`, `sql/`)
- [ ] `.env.example` (template file)
- [ ] `.htaccess` files
- [ ] `README.md` and `SETUP_GUIDE.md`
- [ ] Database files (`sql/schema.sql`, `sql/seed.sql`)

### ❌ Files to EXCLUDE:
- [ ] `.env` (contains sensitive data)
- [ ] Any debug/test PHP files
- [ ] `vendor/` folder (if using Composer)
- [ ] IDE configuration files (`.vscode/`, `.idea/`)
- [ ] Log files and cache
- [ ] User uploaded files (unless needed for demo)

## 🚀 Quick Start Instructions for Recipients

### 1. Download/Extract Project
```bash
# Extract to web server directory
# XAMPP: C:\xampp\htdocs\innovation-hub\
# WAMP: C:\wamp\www\innovation-hub\
# MAMP: /Applications/MAMP/htdocs/innovation-hub/
```

### 2. Database Setup
```bash
# Open phpMyAdmin: http://localhost/phpmyadmin
# Create database: company_ideas
# Import sql/schema.sql first
# Import sql/seed.sql second
```

### 3. Configuration
```bash
# Copy .env.example to .env
# Edit .env with database settings:
DB_HOST=localhost
DB_NAME=company_ideas
DB_USER=root
DB_PASS=(your MySQL password, usually empty for local)
```

### 4. Access Application
```bash
# Open browser: http://localhost/innovation-hub/public/
# Login with: admin@company.com / password123
```

## 🔧 Environment Configurations

### Development Environment:
```env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/innovation-hub/public
```

### Production Environment:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
# Use strong database passwords
# Generate secure APP_KEY
```

## 📦 Packaging for Distribution

### Option 1: ZIP Archive
```bash
# Create project archive excluding sensitive files
zip -r innovation-hub.zip . -x "*.env" "vendor/*" "*.log" ".git/*"
```

### Option 2: Git Repository
```bash
# Initialize git (if not already)
git init
git add .
git commit -m "Initial commit"

# Push to GitHub/GitLab
git remote add origin <your-repo-url>
git push -u origin main
```

## 🔒 Security Considerations

### Before Sharing:
- [ ] Remove all debug files
- [ ] Clear any real user data from database
- [ ] Ensure `.env` is not included
- [ ] Check for hardcoded passwords
- [ ] Review file permissions

### For Recipients:
- [ ] Change default passwords immediately
- [ ] Generate new APP_KEY
- [ ] Configure proper database credentials
- [ ] Set appropriate file permissions
- [ ] Review security settings

## 📞 Support Information

### System Requirements:
- PHP 8.0+
- MySQL 5.7+
- Apache/Nginx with mod_rewrite
- 50MB+ disk space

### Default Credentials:
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@company.com | password123 |
| Employee | marie.dupont@company.com | password123 |
| Evaluator | sophie.bernard@company.com | password123 |

### Common Setup Issues:
1. **Database Connection**: Check MySQL service and credentials
2. **Routing Issues**: Ensure mod_rewrite is enabled
3. **Permission Errors**: Set proper folder permissions
4. **PHP Errors**: Check PHP version and extensions

### Documentation:
- `README.md` - Complete project overview
- `SETUP_GUIDE.md` - Detailed setup instructions
- `sql/` folder - Database structure and sample data

---

**📧 Contact**: Provide your contact information for support

**🔗 Repository**: Include repository URL if applicable

**📅 Last Updated**: {current_date}
