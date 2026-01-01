#!/bin/bash

# Script for deploying Laravel application to VPS Ubuntu with Webuzo
# دليل نشر Laravel على سيرفر Ubuntu مع Webuzo

echo "🚀 Starting Deployment Process..."
echo "بدء عملية النشر..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}❌ .env file not found!${NC}"
    echo "Creating .env from .env.example..."
    cp .env.example .env
    echo -e "${YELLOW}⚠️  Please configure .env file before continuing!${NC}"
    exit 1
fi

# Step 1: Install/Update Composer Dependencies
echo -e "${GREEN}📦 Step 1: Installing Composer dependencies...${NC}"
composer install --optimize-autoloader --no-dev --no-interaction

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Composer install failed!${NC}"
    exit 1
fi

# Step 2: Install/Update NPM Dependencies
echo -e "${GREEN}📦 Step 2: Installing NPM dependencies...${NC}"
npm install

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ NPM install failed!${NC}"
    exit 1
fi

# Step 3: Build Assets
echo -e "${GREEN}🔨 Step 3: Building assets...${NC}"
npm run build

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Asset build failed!${NC}"
    exit 1
fi

# Step 4: Generate Application Key (if not exists)
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${GREEN}🔑 Step 4: Generating application key...${NC}"
    php artisan key:generate --force
fi

# Step 5: Set Permissions
echo -e "${GREEN}🔐 Step 5: Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage/app/public

# Step 6: Create Storage Link
echo -e "${GREEN}🔗 Step 6: Creating storage link...${NC}"
php artisan storage:link

# Step 7: Run Migrations
echo -e "${GREEN}🗄️  Step 7: Running migrations...${NC}"
read -p "Do you want to run migrations? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
fi

# Step 8: Clear and Cache Configuration
echo -e "${GREEN}🧹 Step 8: Clearing and caching configuration...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo "تم النشر بنجاح!"
