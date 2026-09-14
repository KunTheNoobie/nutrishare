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
echo   Module: Donation Publishing and Notification Management
echo =========================================================================
echo   [1] LIVE DEMO:       Login donor@nutrishare.com -^> Publish Donation -^> Check Bell
echo   [2] DESIGN PATTERN:  Observer Pattern (Interface -^> Concrete -^> Queue Dispatch)
echo   [3] SECURITY:        Synchronizer CSRF Token (@csrf) and Context-Aware Escaping
echo   [4] WEB SERVICES:    Provide: active() -^> Consume: verifyNgoBeforeClaim()
echo =========================================================================
echo.
echo Opening 5 presentation tabs in VS Code (matching Viva Guide)...

call "%EDITOR%" -r ^
  -g "%BASE%app\Contracts\DonationObserverInterface.php:12" ^
  -g "%BASE%app\Observers\DonationObserver.php:28" ^
  -g "%BASE%resources\views\donations\create.blade.php:12" ^
  -g "%BASE%app\Http\Controllers\Api\DonationApiController.php:43" ^
  -g "%BASE%app\Http\Controllers\Api\DonationApiController.php:98"

echo.
echo [SUCCESS] 5 Module 1 tabs opened cleanly!
echo.
pause
