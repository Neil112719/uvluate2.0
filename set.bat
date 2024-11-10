@echo off
echo Starting setup...

REM Define paths for backend and frontend directories relative to the script location
set BACKEND_DIR=%~dp0backend
set FRONTEND_DIR=%~dp0uvluate

cd %BACKEND_DIR%
REM Start the backend server
echo Starting backend server...
start cmd /k "cd %BACKEND_DIR% && php -S localhost:8000"

cd %FRONTEND_DIR%

REM Start the frontend server
echo Starting frontend server...
start cmd /k "cd %FRONTEND_DIR% && npm start"

echo All setup complete. Backend and frontend servers are running.
pause
