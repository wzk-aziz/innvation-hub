#!/bin/bash

# Innovation Hub - Quick Setup Script for Mac/Linux
# Make this file executable: chmod +x setup.sh

echo "========================================"
echo "Innovation Hub - Quick Setup Script"
echo "========================================"
echo

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running from correct directory
if [ ! -f "app/Core/Bootstrap.php" ]; then
    echo -e "${RED}✗ ERROR: Please run this script from the project root directory${NC}"
    echo "The script should be in the same folder as the app/ directory"
    exit 1
fi

echo "[1/5] Setting up environment file..."
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}✓ Created .env file from template${NC}"
    else
        echo -e "${RED}✗ .env.example file not found${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}✓ .env file already exists${NC}"
fi

echo
echo "[2/5] Checking PHP installation..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;" 2>/dev/null)
    echo -e "${GREEN}✓ PHP ${PHP_VERSION} is installed${NC}"
    
    # Check PHP version (should be 8.0+)
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;" 2>/dev/null)
    if [ "$PHP_MAJOR" -lt 8 ]; then
        echo -e "${YELLOW}⚠ Warning: PHP 8.0+ is recommended. You have PHP ${PHP_VERSION}${NC}"
    fi
else
    echo -e "${RED}✗ PHP is not installed or not in PATH${NC}"
    echo "Please install PHP 8.0+ using:"
    echo "  macOS: brew install php"
    echo "  Ubuntu: sudo apt install php8.0"
    echo "  CentOS: sudo yum install php80"
    exit 1
fi

echo
echo "[3/5] Checking MySQL installation..."
if command -v mysql &> /dev/null; then
    echo -e "${GREEN}✓ MySQL is installed${NC}"
else
    echo -e "${YELLOW}⚠ MySQL client not found in PATH${NC}"
    echo "Please ensure MySQL/MariaDB is installed and running"
fi

echo
echo "[4/5] Setting file permissions..."
chmod -R 755 .
if [ -d "public/assets/uploads" ]; then
    chmod -R 777 public/assets/uploads
    echo -e "${GREEN}✓ Upload directory permissions set${NC}"
fi
echo -e "${GREEN}✓ File permissions set${NC}"

echo
echo "[5/5] Database setup reminder..."
echo -e "${YELLOW}Please make sure to:${NC}"
echo "1. Create MySQL database: CREATE DATABASE company_ideas;"
echo "2. Import sql/schema.sql (creates tables)"
echo "3. Import sql/seed.sql (adds sample data)"
echo
echo "You can do this via phpMyAdmin or command line:"
echo "  mysql -u root -p -e 'CREATE DATABASE company_ideas;'"
echo "  mysql -u root -p company_ideas < sql/schema.sql"
echo "  mysql -u root -p company_ideas < sql/seed.sql"
echo

# Ask if user wants to start the development server
echo -e "${YELLOW}Would you like to start the PHP development server? (y/n)${NC}"
read -p "> " start_server

if [ "$start_server" = "y" ] || [ "$start_server" = "Y" ] || [ "$start_server" = "yes" ]; then
    echo
    echo "Starting PHP development server..."
    echo "Application will be available at: http://localhost:8000"
    echo "Press Ctrl+C to stop the server"
    echo
    echo "Default login credentials:"
    echo "  Admin: admin@company.com / password123"
    echo "  Employee: marie.dupont@company.com / password123"
    echo "  Evaluator: sophie.bernard@company.com / password123"
    echo
    echo "Starting server in 3 seconds..."
    sleep 3
    
    cd public
    php -S localhost:8000
else
    echo
    echo -e "${GREEN}Setup completed successfully!${NC}"
    echo "To start the development server later, run:"
    echo "  cd public && php -S localhost:8000"
    echo
    echo "Or configure your web server to point to the public/ directory"
fi
