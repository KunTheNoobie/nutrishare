@echo off
title "NutriShare - Database Reset & Revert"
echo ===================================================
echo   Reverting NutriShare Database to Baseline State
echo ===================================================
echo.

set "MYSQL=C:\xampp\mysql\bin\mysql.exe"
if not exist "%MYSQL%" (
    set "MYSQL=mysql"
)

echo [1/3] Dropping and recreating fresh database 'nutrishare'...
"%MYSQL%" -u root -e "DROP DATABASE IF EXISTS nutrishare; CREATE DATABASE nutrishare;"
if errorlevel 1 (
    echo [ERROR] Failed to connect to MySQL. Ensure MySQL / XAMPP is running.
    pause
    exit /b 1
)

echo [2/3] Importing pre-built baseline dataset (nutrishare_dump.sql)...
powershell -NoProfile -Command "Get-Content '%~dp0database\nutrishare_dump.sql' | & '%MYSQL%' -u root nutrishare"
if errorlevel 1 (
    echo [ERROR] Failed to import database dump.
    pause
    exit /b 1
)

echo [3/3] Running NutriShare Diagnostic Health Check...
echo.
php "%~dp0artisan" nutrishare:health-check

echo.
echo ===================================================
echo   Database successfully reset and reverted!
echo ===================================================
pause
