@echo off
:: =========================================================================
:: WHEN TO RUN THIS SCRIPT:
:: Run this script ONCE on a fresh Windows machine to initialize the Laravel
:: dependencies, copy environment configs, and link storage.
:: Note: The master startup script (start_aims_windows.bat) in the project root 
:: will automatically run this script if it detects that the setup has not 
:: been completed yet.
:: =========================================================================
echo ===================================================
echo AIMS Laravel App Setup Script (Windows)
echo ===================================================

:: 1. Check PHP installation
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in your PATH.
    echo FIX: Download PHP 8.2+ from https://windows.php.net/download/ and add the PHP directory to your system PATH.
    goto ERROR
)

:: 2. Check Composer installation
composer -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed or not in your PATH.
    echo FIX: Install Composer from https://getcomposer.org/doc/00-intro.md#installation-windows
    goto ERROR
)

:: 3. Copy .env if not exists
if not exist .env (
    echo.
    echo [1/4] Creating .env file from .env.example...
    copy .env.example .env
    echo [INFO] Please verify your database settings in html/.env
) else (
    echo.
    echo [1/4] .env file already exists. Skipping copy.
)

:: 4. Install Composer dependencies
echo.
echo [2/4] Installing Composer dependencies (this might take a few minutes)...
call composer install
if %errorlevel% neq 0 (
    echo [ERROR] Composer install failed.
    echo FIX: Make sure you have an active internet connection and that PHP extensions (openssl, mbstring, pdo_mysql, fileinfo, gd) are enabled in your php.ini.
    goto ERROR
)

:: 5. Generate application key
echo.
echo [3/4] Generating Laravel Application Key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo [ERROR] Failed to generate application key.
    goto ERROR
)

:: 6. Create storage symlink
echo.
echo [4/4] Creating Storage Symlink...
php artisan storage:link
if %errorlevel% neq 0 (
    echo [ERROR] Failed to link storage.
    goto ERROR
)

:: 7. Database info
echo.
echo ===================================================
echo SETUP STEP 1 COMPLETE!
echo ===================================================
echo Please ensure your MySQL server is running and the database
echo 'ledgersinfo_html' is created and seeded.
echo.
echo If you need to import the database, run:
echo   mysql -u root -e "CREATE DATABASE ledgersinfo_html CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo   mysql -u root ledgersinfo_html ^< ..\ledgersinfo_html.sql
echo.
echo After database import is done, run the migrations:
echo   php artisan migrate --force
echo   php artisan db:seed --class=AiPermissionSeeder --force
echo ===================================================
pause
goto END

:ERROR
echo.
echo Laravel setup failed.
pause

:END
