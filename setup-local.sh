#!/bin/bash

echo "🚀 Setting up Foz da Cova Dashboard for local development..."

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first:"
    echo "   macOS: brew install php"
    echo "   Ubuntu: sudo apt-get install php php-sqlite3"
    echo "   Windows: Download XAMPP from https://www.apachefriends.org/"
    exit 1
fi

echo "✅ PHP found: $(php -v | head -n1)"

# Check if SQLite is available
if ! php -m | grep -q sqlite3; then
    echo "❌ SQLite3 extension not loaded. Please install it:"
    echo "   Ubuntu: sudo apt-get install php-sqlite3"
    echo "   macOS: brew install php"
    exit 1
fi

echo "✅ SQLite3 extension available"

# Create data directory if it doesn't exist
if [ ! -d "data" ]; then
    mkdir -p data
    echo "✅ Created data directory"
fi

# Set permissions
chmod 755 data/
echo "✅ Set data directory permissions"

# Check if database exists
if [ ! -f "data/foz_da_cova.db" ]; then
    echo "📊 Creating database..."
    php admin/setup.php
    if [ $? -eq 0 ]; then
        echo "✅ Database created successfully"
    else
        echo "❌ Database creation failed"
        exit 1
    fi
else
    echo "✅ Database already exists"
fi

# Start development server
echo "🌐 Starting development server..."
echo "   Open http://localhost:8000 in your browser"
echo "   Demo credentials:"
echo "   - Admin: admin / admin123"
echo "   - Stewart: maria / maria123"
echo "   - WWOOFer: joao / joao123"
echo "   - WWOOFer: ana / ana123"
echo ""
echo "   Press Ctrl+C to stop the server"
echo ""

cd public/
php -S localhost:8000 