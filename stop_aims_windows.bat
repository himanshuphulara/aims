@echo off
echo ===================================================
echo Stopping AIMS Application Services
echo ===================================================
echo.

:: 1. Stop Laravel Web Server (Port 8000)
echo Stopping Laravel Web Server (Port 8000)...
set "found_laravel=0"
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000 ^| findstr LISTENING') do (
    echo Killing Laravel server process [PID %%a]...
    taskkill /f /pid %%a >nul 2>&1
    set "found_laravel=1"
)
if "%found_laravel%"=="0" echo Laravel server was not running.

:: 2. Stop AI FastAPI Service (Port 8001)
echo.
echo Stopping AI FastAPI Service (Port 8001)...
set "found_ai=0"
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8001 ^| findstr LISTENING') do (
    echo Killing AI service process [PID %%a]...
    taskkill /f /pid %%a >nul 2>&1
    set "found_ai=1"
)
if "%found_ai%"=="0" echo AI service was not running.

:: 3. Stop MySQL Service (Optional, requires admin privileges)
echo.
echo Attempting to stop MySQL Service (requires Administrator privileges)...
net stop MySQL >nul 2>&1
if %errorlevel% == 0 (
    echo MySQL Service stopped successfully.
) else (
    echo MySQL Service was already stopped or requires Administrator privileges to stop.
)

echo.
echo ===================================================
echo Clean-up completed!
echo ===================================================
pause
