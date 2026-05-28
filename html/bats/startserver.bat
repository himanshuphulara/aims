@echo off

:: Change to the Laravel project directory
cd /d E:\larapro\deaf

:: Start the Laravel development server in the background
start /B php artisan serve

:: Start Apache in the background
start /B C:\xampp\apache\bin\httpd.exe

:: Start MySQL in the background
start /B C:\xampp\mysql\bin\mysqld.exe

exit