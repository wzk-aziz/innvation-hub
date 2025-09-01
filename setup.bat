@echo off
echo ========================================
echo Innovation Hub - Quick Setup Script
echo ========================================
echo.

REM Check if running from correct directory
if not exist "app\Core\Bootstrap.php" (
    echo ERROR: Please run this script from the project root directory
    echo The script should be in the same folder as the app/ directory
    pause
    exit /b 1
)

echo [1/4] Setting up environment file...
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" > nul
        echo ✓ Created .env file from template
    ) else (
        echo ✗ .env.example file not found
        goto :error
    )
) else (
    echo ✓ .env file already exists
)

echo.
echo [2/4] Checking PHP installation...
php --version > nul 2>&1
if %errorlevel% neq 0 (
    echo ✗ PHP is not installed or not in PATH
    echo Please install PHP 8.0+ or add it to your PATH
    goto :error
) else (
    echo ✓ PHP is installed
)

echo.
echo [3/4] Testing database connection...
echo Please make sure MySQL is running and you have created the 'company_ideas' database
echo.
echo To create the database, run these commands in MySQL:
echo   CREATE DATABASE company_ideas;
echo.
echo Then import the SQL files:
echo   1. Import sql/schema.sql (creates tables)
echo   2. Import sql/seed.sql (adds sample data)
echo.

echo [4/4] Starting development server...
echo.
echo The application will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
echo Default login credentials:
echo   Admin: admin@company.com / password123
echo   Employee: marie.dupont@company.com / password123
echo   Evaluator: sophie.bernard@company.com / password123
echo.
pause

echo Starting PHP development server...
cd public
php -S localhost:8000
goto :end

:error
echo.
echo Setup failed. Please check the errors above and try again.
echo Refer to SETUP_GUIDE.md for detailed instructions.
pause
exit /b 1

:end
echo.
echo Setup completed successfully!
pause
