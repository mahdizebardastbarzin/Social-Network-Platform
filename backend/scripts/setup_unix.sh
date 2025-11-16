#!/bin/bash
# ==============================================
# File: scripts/setup_unix.sh
# ==============================================

# ==============================================
# Unix/Linux setup script for Next-Generation Social Network Platform
# EN: This script sets up file permissions and runs initial setup.
# FA: این اسکریپت مجوز فایل‌ها را تنظیم می‌کند و راه‌اندازی اولیه را اجرا می‌کند.
# ==============================================

# Exit on error
set -e

# EN: Navigate to project root
# FA: رفتن به ریشه پروژه
cd "$(dirname "$0")/.."

# EN: Set permissions for backend and database scripts
# FA: تنظیم مجوزها برای اسکریپت‌های backend و database
chmod -R 755 backend/
chmod -R 755 database/

# EN: Set permissions for scripts
# FA: تنظیم مجوزها برای پوشه scripts
chmod +x scripts/setup_unix.sh

# EN: Run database creation script
# FA: اجرای اسکریپت ایجاد دیتابیس
php database/create_db.php

echo "Setup completed successfully."
