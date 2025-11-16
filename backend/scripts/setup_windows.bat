@echo off
:: ==============================================
:: File: scripts/setup_windows.bat
:: ==============================================

:: ==============================================
:: Windows setup script for Next-Generation Social Network Platform
:: EN: This script runs initial setup and creates the database.
:: FA: این اسکریپت راه‌اندازی اولیه را انجام می‌دهد و دیتابیس را ایجاد می‌کند.
:: ==============================================

:: EN: Navigate to project root
:: FA: رفتن به ریشه پروژه
cd /d %~dp0\..

:: EN: Run PHP database creation script
:: FA: اجرای اسکریپت ایجاد دیتابیس با PHP
php database\create_db.php

echo Setup completed successfully.
pause