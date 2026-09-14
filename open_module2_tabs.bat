@echo off
set "BASE=%~dp0"

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
echo   NUTRISHARE VIVA DEFENSE -- MODULE 2: CHEON JIE HAN (25WMR09703)
echo   Module: NGO Verification and Peer Trust Ratings
echo =========================================================================
echo   [1] LIVE DEMO:      Upload ROS Doc -^> Admin Approves -^> View Trust Rating
echo   [2] DESIGN PATTERN: Factory Method Pattern (app/Services/UserFactory/UserCreator.php)
echo   [3] SECURITY:       Malicious File Upload / RCE (app/Http/Controllers/VerificationController.php)
echo   [4] WEB SERVICES:   Verification Status API (app/Http/Controllers/Api/UserVerificationApiController.php)
echo =========================================================================
echo.
echo Opening 4 core viva presentation tabs in VS Code...

call "%EDITOR%" -r ^
  -g "%BASE%app\Services\UserFactory\UserCreator.php:15" ^
  -g "%BASE%app\Services\UserFactory\NgoCreator.php:14" ^
  -g "%BASE%app\Http\Controllers\VerificationController.php:35" ^
  -g "%BASE%app\Http\Controllers\Api\UserVerificationApiController.php:18"

echo.
echo [SUCCESS] 4 Module 2 tabs opened cleanly!
echo.
pause
