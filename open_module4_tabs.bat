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
echo   NUTRISHARE VIVA DEFENSE -- MODULE 4: WONG MEN JING (25WMR09788)
echo   Module: Inventory and Food Safety Compliance
echo =========================================================================
echo   [1] LIVE DEMO:       Login ngo@nutrishare.com -^> Facilities -^> Allergens -^> Export CSV
echo   [2] DESIGN PATTERN:  Strategy Pattern (NotificationStrategyInterface -^> Dispatcher)
echo   [3] SECURITY:        CRLF Sanitization (SecurityHelper) + Cryptographic HMAC Signed URLs
echo   [4] WEB SERVICES:    Provide: checkSafety() -^> Consume: fetchActiveDonationsForWarehouse()
echo =========================================================================
echo.
echo Opening 5 presentation tabs in VS Code (matching Viva Guide)...

call "%EDITOR%" -r ^
  -g "%BASE%app\Strategies\Notification\NotificationStrategyInterface.php:14" ^
  -g "%BASE%app\Strategies\Notification\EmailStrategy.php:14" ^
  -g "%BASE%app\Helpers\SecurityHelper.php:14" ^
  -g "%BASE%app\Http\Controllers\Api\InventoryApiController.php:80" ^
  -g "%BASE%app\Http\Controllers\Api\InventoryApiController.php:130"

echo.
echo [SUCCESS] 5 Module 4 tabs opened cleanly!
echo.
pause
