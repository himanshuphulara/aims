@echo off
echo ===================================================
echo AIMS Application Startup and Diagnostics Script
echo ===================================================
echo.

:: 1. Verify MySQL Service Status
echo [1/4] Checking MySQL Database Service...
sc query MySQL | findstr RUNNING >nul 2>&1
if %errorlevel% NEQ 0 (
    echo [WARNING] MySQL service is not running.
    echo Attempting to start MySQL service [requires Administrator privileges]...
    net start MySQL >nul 2>&1
    sc query MySQL | findstr RUNNING >nul 2>&1
    if %errorlevel% NEQ 0 (
        echo [ERROR] MySQL is offline and could not be started automatically.
        echo FIX: Open PowerShell as Administrator and run:
        echo   Start-Service MySQL
        echo.
        set /p proceed="Do you want to try launching AIMS anyway? [y/n]: "
        if /i "%proceed%" neq "y" goto END
    ) else (
        echo [SUCCESS] MySQL service started successfully.
    )
) else (
    echo [OK] MySQL database service is running.
)
echo.

:: 2. Verify Ollama Status
echo [2/4] Checking Ollama AI Model Server...
netstat -aon | findstr :11434 | findstr LISTENING >nul 2>&1
if %errorlevel% NEQ 0 (
    echo [WARNING] Ollama is not active on port 11434.
    echo Attempting to start Ollama automatically...
    start "" "ollama.exe" serve >nul 2>&1
    echo Waiting for Ollama to initialize [5 seconds]...
    timeout /t 5 >nul
    netstat -aon | findstr :11434 | findstr LISTENING >nul 2>&1
    if %errorlevel% NEQ 0 (
        echo [ERROR] Ollama failed to start automatically.
        echo FIX: Click the Ollama desktop icon from your Start Menu to launch it.
        echo.
    ) else (
        echo [SUCCESS] Ollama started successfully.
    )
) else (
    echo [OK] Ollama server is running.
)
echo.

:: 3. Check and run Laravel Setup if needed
if not exist html\.env (
    echo [INFO] Laravel configuration [.env] is missing. Running html\setup_laravel_windows.bat...
    cd html
    call setup_laravel_windows.bat
    cd ..
)

:: 4. Check and run AI Service Setup if needed
if not exist ai_service\.venv (
    echo [INFO] AI Service virtual environment [.venv] is missing. Running ai_service\setup_ai_windows.bat...
    cd ai_service
    call setup_ai_windows.bat
    cd ..
)
echo.

:: 5. Launch Laravel Web Server in a new window
echo [3/4] Starting Laravel Web Server on http://127.0.0.1:8000 ...
start "AIMS - Laravel Web Server" cmd /k "cd html\public && php -d upload_max_filesize=25M -d post_max_size=30M -d max_execution_time=300 -S 127.0.0.1:8000 ..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php"

:: 6. Launch AI Service in a new window
if exist ai_service\.venv (
    echo [4/4] Starting AI FastAPI Service on port 8001 ...
    start "AIMS - AI Service" cmd /k "cd ai_service && .venv\Scripts\python.exe -m uvicorn main:app --host 127.0.0.1 --port 8001"
) else (
    echo [ERROR] Skipping AI Service: Setup failed or .venv is still missing.
    echo FIX: Run ai_service\setup_ai_windows.bat manually and check for Python version/C++ compilation errors.
)

echo.
echo ===================================================
echo LAUNCH COMPLETE!
echo ===================================================
echo  - Laravel App URL:   http://127.0.0.1:8000
echo  - AI Service URL:   http://127.0.0.1:8001
echo.
echo  - Default Login:     Username: admin / Password: Admin@123
echo  - To stop services:  Run .\stop_aims_windows.bat in your terminal.
echo ===================================================
timeout /t 5
goto END

:END
