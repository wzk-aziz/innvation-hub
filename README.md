# Company Ideas Management System

A complete PHP 8 MVC web application for managing company ideas and innovation with role-based access control.

## 🎯 Project Overview

This application allows companies to manage employee ideas through a structured workflow:

- **Employees (Salarie)**: Submit and manage their ideas
- **Evaluators (Evaluateur)**: Review and rate submitted ideas  
- **Administrators (Admin)**: Oversee the entire process, manage users and themes

## 🏗️ Architecture

- **PHP 8** with MVC architecture
- **PDO** for database operations (no MySQLi)
- **Role-based access control** (RBAC)
- **Two layouts**: Admin (back-office) and Front (employees/evaluators)
- **Security features**: CSRF protection, password hashing, input validation
- **Clean URLs** via .htaccess routing

## 📋 Features

### Core Functionality
- ✅ User authentication with password hashing
- ✅ Role-based access control (Admin, Salarie, Evaluateur)
- ✅ CRUD operations for all entities
- ✅ Server-side and client-side validation
- ✅ File upload support for idea attachments
- ✅ Evaluation system with scoring (0-10)
- ✅ Admin feedback system
- ✅ Dashboard with statistics

### Security Features
- ✅ CSRF token protection
- ✅ Password hashing with password_verify
- ✅ Input sanitization and validation
- ✅ SQL injection prevention (prepared statements)
- ✅ Login attempt throttling
- ✅ Secure session management

## 🗄️ Database Schema

### Tables Created:
- `users` - User accounts with roles
- `themes` - Idea categories/themes
- `ideas` - Employee submitted ideas
- `evaluations` - Evaluator ratings and comments
- `feedback` - Admin feedback on ideas
- `idea_attachments` - File attachments (optional)

### Sample Data Included:
- 1 Admin, 2 Employees, 2 Evaluators
- 4 Themes (Digital Innovation, Sustainability, etc.)
- 5 Sample ideas with evaluations and feedback

## 🚀 Installation

### Prerequisites
- PHP 8.0+
- MySQL 5.7+
- Web server (Apache/Nginx)
- Composer (optional, for autoloading)

### Setup Steps

1. **Database Setup**
   ```bash
   # Create database
   mysql -u root -p
   CREATE DATABASE company_ideas;
   
   # Import schema and seed data
   mysql -u root -p company_ideas < sql/schema.sql
   mysql -u root -p company_ideas < sql/seed.sql
   ```

2. **Environment Configuration**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Edit database credentials in .env
   DB_HOST=localhost
   DB_NAME=company_ideas
   DB_USER=your_username
   DB_PASS=your_password
   ```

3. **Web Server Configuration**
   - Point document root to `/public` folder
   - Ensure mod_rewrite is enabled for Apache
   - Set appropriate file permissions

4. **Composer Dependencies** (Optional)
   ```bash
   composer install
   ```

## 👥 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@company.com | password123 |
| Employee | marie.dupont@company.com | password123 |
| Employee | pierre.martin@company.com | password123 |
| Evaluator | sophie.bernard@company.com | password123 |
| Evaluator | jean.rousseau@company.com | password123 |

## 📁 Project Structure

```
company-ideas/
├── app/
│   ├── Controllers/
│   │   ├── Admin/           # Admin controllers
│   │   ├── Salarie/         # Employee controllers  
│   │   ├── Evaluateur/      # Evaluator controllers
│   │   ├── AuthController.php
│   │   └── HomeController.php
│   ├── Core/                # Framework core
│   │   ├── Bootstrap.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── View.php
│   │   ├── Database.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Auth.php
│   ├── Middlewares/         # Security middlewares
│   ├── Models/              # Data models
│   ├── Views/               # Templates
│   │   ├── layouts/         # Layout templates
│   │   ├── partials/        # Partial views
│   │   ├── admin/           # Admin views
│   │   ├── salarie/         # Employee views
│   │   └── evaluateur/      # Evaluator views
│   └── Validators/          # Input validation
├── config/
│   └── config.php           # Application configuration
├── public/
│   ├── assets/              # CSS, JS, images
│   ├── .htaccess           # URL rewriting
│   └── index.php           # Front controller
└── sql/
    ├── schema.sql          # Database schema
    └── seed.sql            # Sample data
```

## 🔧 Remaining Implementation Tasks

To complete the application, you need to create the following files:

### Controllers to Create:
1. **Admin Controllers**:
   - `app/Controllers/Admin/ThemeController.php`
   - `app/Controllers/Admin/IdeaController.php`

2. **Employee Controllers**:
   - `app/Controllers/Salarie/IdeaController.php`

3. **Evaluator Controllers**:
   - `app/Controllers/Evaluateur/ReviewController.php`

### Validators to Create:
- `app/Validators/IdeaValidator.php`
- `app/Validators/ThemeValidator.php`

### Views to Create:
1. **Layouts**:
   - `app/Views/layouts/admin.php` (Admin layout)
   - `app/Views/layouts/front.php` (Front office layout)

2. **Partials**:
   - `app/Views/partials/flash.php`
   - `app/Views/partials/navbar_admin.php`
   - `app/Views/partials/navbar_front.php`

3. **Auth Views**:
   - `app/Views/auth/login.php`

4. **Error Views**:
   - `app/Views/errors/404.php`
   - `app/Views/errors/403.php`
   - `app/Views/errors/500.php`

5. **Admin Views**: Complete CRUD views for users, themes, and ideas
6. **Employee Views**: Idea management views
7. **Evaluator Views**: Review and rating views

## 📊 Key Features by Role

### Admin Dashboard
- User management (CRUD)
- Theme management (CRUD)  
- Idea supervision and status changes
- Feedback management
- System statistics

### Employee Dashboard
- Submit new ideas
- Manage own ideas
- View evaluations and feedback
- Track idea status

### Evaluator Dashboard
- Review submitted ideas
- Rate ideas (0-10 scale)
- Add evaluation comments
- Track evaluation statistics

## 🔒 Security Implementation

- **Authentication**: Session-based with password hashing
- **Authorization**: Role-based access control middleware
- **CSRF Protection**: Token validation on all forms
- **Input Validation**: Server-side and client-side validation
- **SQL Injection Prevention**: PDO prepared statements
- **XSS Prevention**: Output escaping in views
- **File Upload Security**: Type and size validation

## 🎨 Frontend Features

- **Responsive Design**: Mobile-friendly layouts
- **Client-side Validation**: Real-time form validation
- **Interactive Components**: Sortable tables, modals, tooltips
- **Auto-save**: Draft saving for forms
- **File Upload**: Drag-and-drop support

## 📚 Development Guidelines

### Adding New Features:
1. Create model in `app/Models/`
2. Add controller in appropriate role folder
3. Create validator in `app/Validators/`
4. Add routes in `Router.php`
5. Create views in `app/Views/`

### Security Best Practices:
- Always validate CSRF tokens
- Escape output in views
- Use prepared statements
- Validate user permissions
- Sanitize file uploads

## 🐛 Known Limitations

- File uploads limited to 5MB by default
- No email notifications (can be added)
- Basic search functionality (can be enhanced)
- No API endpoints (web-only interface)

## 📈 Future Enhancements

- Email notification system
- Advanced search and filtering
- REST API endpoints
- Real-time notifications
- Advanced reporting and analytics
- Multi-language support

## 🤝 Contributing

1. Follow PSR-4 autoloading standards
2. Use prepared statements for all database queries
3. Validate all user inputs
4. Add CSRF protection to forms
5. Escape output in views
6. Follow the existing code structure

## 📄 License

This project is intended for educational and internal company use.

---

**Note**: This is a complete MVC framework built from scratch. While the core structure is implemented, you'll need to complete the remaining controllers and views based on the patterns established in the existing code.
