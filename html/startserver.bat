@echo off
cd /d E:\larapro\deaf
start cmd /k "php artisan serve"


start cmd /k "C:\xampp\apache\bin\httpd.exe"
start cmd /k "C:\xampp\mysql\bin\mysqld.exe"


exit