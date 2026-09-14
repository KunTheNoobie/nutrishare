@echo off
set "BASE=%~dp0"

:: Auto-detect Editor: Check VS Code full path first, then Antigravity IDE
set "EDITOR="
if exist "%LOCALAPPDATA%\Programs\Microsoft VS Code\bin\code.cmd" (
    set "EDITOR=%LOCALAPPDATA%\Programs\Microsoft VS Code\bin\code.cmd"
) else if exist "%PROGRAMFILES%\Microsoft VS Code\bin\code.cmd" (
    set "EDITOR=%PROGRAMFILES%\Microsoft VS Code\bin\code.cmd"
) else if exist "%ProgramFiles(x86)%\Microsoft VS Code\bin\code.cmd" (
    set "EDITOR=%ProgramFiles(x86)%\Microsoft VS Code\bin\code.cmd"
) else if exist "C:\Users\yiler\AppData\Local\Programs\Antigravity\bin\antigravity-ide.cmd" (
    set "EDITOR=C:\Users\yiler\AppData\Local\Programs\Antigravity\bin\antigravity-ide.cmd"
) else (
    set "EDITOR=code"
)

cls
echo =========================================================================
echo   NUTRISHARE VIVA DEFENSE -- MODULE 1: LIEW YI LER (25WMR09747)
echo   Module: Donation Publishing and Notifications
echo =========================================================================
echo   [1] LIVE DEMO:      Login donor@nutrishare.com -^> Create Donation -^> View Bell
echo   [2] DESIGN PATTERN: Observer Pattern (app/Observers/DonationObserver.php)
echo   [3] SECURITY:       Mass Assignment / BOLA (app/Http/Requests/StoreDonationRequest.php)
echo   [4] WEB SERVICES:   Donation REST API (app/Http/Controllers/Api/DonationApiController.php)
echo =========================================================================
echo.
echo Opening 4 core viva presentation tabs in VS Code...

call "%EDITOR%" -r ^
  -g "%BASE%app\Observers\DonationObserver.php:30" ^
  -g "%BASE%app\Jobs\SendDonationNotificationJob.php:36" ^
  -g "%BASE%app\Http\Requests\StoreDonationRequest.php:25" ^
  -g "%BASE%app\Http\Controllers\Api\DonationApiController.php:20"

echo.
echo [SUCCESS] 4 Module 1 tabs opened cleanly!
echo.
pause
