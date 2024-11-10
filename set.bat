@echo off
echo Starting setup...

REM Define paths for backend and frontend directories relative to the script location
set BACKEND_DIR=%~dp0backend
set FRONTEND_DIR=%~dp0uvluate

REM Install backend dependencies (Composer and npm)
echo Installing backend dependencies...
cd %BACKEND_DIR%

IF EXIST composer.json (
    echo Installing Composer dependencies...
    composer install
) ELSE (
    echo Composer not found or not configured in backend. Skipping Composer installation...
)

IF EXIST package.json (
    echo Installing npm dependencies for backend...
    npm install
) ELSE (
    echo package.json not found in backend. Skipping npm installation for backend...
)

REM Start the backend server
echo Starting backend server...
start cmd /k "cd %BACKEND_DIR% && php -S localhost:8000"

REM Install frontend dependencies
echo Installing frontend dependencies...
cd %FRONTEND_DIR%

IF EXIST package.json (
    echo Installing npm dependencies for frontend...
    npm install
) ELSE (
    echo package.json not found in frontend. Skipping npm installation for frontend...
)

REM Start the frontend server
echo Starting frontend server...
start cmd /k "cd %FRONTEND_DIR% && npm start"

echo All setup complete. Backend and frontend servers are running.
pause
