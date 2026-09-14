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
echo   [3] SECURITY:        CSRF (@csrf) and Stored XSS Context-Aware Escaping
echo   [4] WEB SERVICES:    Provide: active() -^> Consume: verifyNgoBeforeClaim()
echo =========================================================================
echo.
echo Opening 6 presentation tabs in VS Code in exact viva sequence (left-to-right)...
echo   Tab 1: DonationObserverInterface.php:12 (Pattern Interface)
echo   Tab 2: DonationObserver.php:15          (Pattern Concrete Observer)
echo   Tab 3: create.blade.php:12              (Security 1: CSRF @csrf Token)
echo   Tab 4: show.blade.php:18                (Security 2: Stored XSS Escaping)
echo   Tab 5: DonationApiController.php:43     (Web Services: active & verifyNgo)
echo   Tab 6: SendDonationNotificationJob.php:25 (Async Queue Worker)
echo.

call "%EDITOR%" -r ^
  -g "%BASE%app\Contracts\DonationObserverInterface.php:12" ^
  -g "%BASE%app\Observers\DonationObserver.php:15" ^
  -g "%BASE%resources\views\donations\create.blade.php:12" ^
  -g "%BASE%resources\views\donations\show.blade.php:18" ^
  -g "%BASE%app\Http\Controllers\Api\DonationApiController.php:43" ^
  -g "%BASE%app\Jobs\SendDonationNotificationJob.php:25"

echo.
echo [SUCCESS] All 6 Module 1 tabs opened in exact presentation sequence!
echo.
pause
