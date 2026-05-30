@echo off
:: =========================================================================
:: WHEN TO RUN THIS SCRIPT:
:: Run this script ONCE on a fresh Windows machine to initialize the Python
:: virtual environment, install the AI dependencies, and download Ollama models.
:: Note: The master startup script (start_aims_windows.bat) in the project root 
:: will automatically run this script if it detects that the setup has not 
:: been completed yet.
:: =========================================================================
echo ===================================================
echo AIMS AI Service Setup Script (Windows)
echo ===================================================

:: 1. Check Python installation and locate the best version
set PYTHON_CMD=python

:: Check if default 'python' is 3.11
python -c "import sys; sys.exit(0 if sys.version_info.major == 3 and sys.version_info.minor == 11 else 1)" >nul 2>&1
if %errorlevel% == 0 goto CREATE_VENV

:: If not, check if 'py -3.11' launcher is available
py -3.11 -c "import sys; sys.exit(0)" >nul 2>&1
if %errorlevel% == 0 (
    set PYTHON_CMD=py -3.11
    goto CREATE_VENV
)

:: If no 3.11 found, check if default 'python' is at least installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Python is not installed or not in your PATH.
    echo FIX: Install Python 3.11.x from https://www.python.org/downloads/release/python-3119/ and check "Add Python to PATH" during setup.
    goto ERROR
)

:: Warn about using an unsupported Python version
echo [WARNING] Python 3.11 is highly recommended.
echo Your current default is:
python --version
echo Newer versions (3.12+) will fail to install ChromaDB/Pydantic binaries unless you have Visual Studio C++ Build Tools installed.
echo.
set /p choice="Do you want to proceed anyway with your default Python? (y/n): "
if /i "%choice%" neq "y" goto END

:CREATE_VENV

:: 3. Create virtual environment
echo.
echo [1/3] Creating Python virtual environment (.venv) using %PYTHON_CMD%...
%PYTHON_CMD% -m venv .venv
if %errorlevel% NEQ 0 (
    echo [ERROR] Failed to create virtual environment.
    echo FIX: Make sure the Python version you are running has venv module installed.
    goto ERROR
)

:: 4. Install dependencies
echo.
echo [2/3] Installing Python dependencies (this may take a moment)...
.venv\Scripts\pip.exe install -r requirements.txt
if %errorlevel% NEQ 0 (
    echo [ERROR] Failed to install dependencies.
    echo FIX: If you got a C++ compile error, you must either:
    echo   1. Install Python 3.11.x (highly recommended) which has precompiled binaries.
    echo   2. Or install Microsoft Visual C++ Build Tools (https://visualstudio.microsoft.com/visual-cpp-build-tools/).
    goto ERROR
)

:: 5. Pull Ollama models if Ollama is installed
echo.
echo [3/3] Checking Ollama installation...
where ollama >nul 2>&1
if %errorlevel% == 0 (
    echo Ollama detected. Pulling models...
    ollama pull nomic-embed-text
    ollama pull qwen2.5:7b-instruct
) else (
    echo [INFO] Ollama command not found in PATH.
    echo FIX: Please download and install Ollama manually from https://ollama.com/download
    echo Once installed, remember to start Ollama and run:
    echo   ollama pull nomic-embed-text
    echo   ollama pull qwen2.5:7b-instruct
)

echo.
echo ===================================================
echo SETUP COMPLETED SUCCESSFULLY!
echo ===================================================
echo To run the AI service, execute:
echo   .venv\Scripts\python.exe -m uvicorn main:app --host 127.0.0.1 --port 8001
echo ===================================================
pause
goto END

:ERROR
echo.
echo Setup failed. Please resolve the errors above.
pause

:END
