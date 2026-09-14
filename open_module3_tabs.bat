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
echo   NUTRISHARE VIVA DEFENSE -- MODULE 3: HIEW LI WEI (25WMR09728)
echo   Module: Claims Management and Logistics Distribution
echo =========================================================================
echo   [1] LIVE DEMO:      NGO claims food -^> Donor approves -^> Logistics -^> Collected
echo   [2] DESIGN PATTERN: State Pattern (app/States/Claim/ClaimState.php)
echo   [3] SECURITY:       Race Conditions / IDOR (app/Policies/ClaimPolicy.php)
echo   [4] WEB SERVICES:   Claims Tracking API (app/Http/Controllers/Api/ClaimApiController.php)
echo =========================================================================
echo.
echo Opening 4 core viva presentation tabs in VS Code...

call "%EDITOR%" -r ^
  -g "%BASE%app\States\Claim\ClaimState.php:18" ^
  -g "%BASE%app\States\Claim\PendingState.php:20" ^
  -g "%BASE%app\Policies\ClaimPolicy.php:25" ^
  -g "%BASE%app\Http\Controllers\Api\ClaimApiController.php:18"

echo.
echo [SUCCESS] 4 Module 3 tabs opened cleanly!
echo.
pause
