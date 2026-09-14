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
echo   [3] SECURITY:        CRLF Sanitization (SystemLog Mutator) + HMAC Signed URLs
echo   [4] WEB SERVICES:    Provide: checkSafety() -^> Consume: fetchActiveDonationsForWarehouse()
echo =========================================================================
echo.
echo Opening 6 presentation tabs in VS Code in exact viva sequence (left-to-right)...
echo   Tab 1: NotificationStrategyInterface.php:14 (Pattern Strategy Interface)
echo   Tab 2: EmailStrategy.php:14                 (Pattern Concrete Strategy)
echo   Tab 3: SystemLog.php:47                     (Security 1: CRLF Sanitization Mutators)
echo   Tab 4: InventoryController.php:146          (Security 2: Cryptographic HMAC Signed URLs)
echo   Tab 5: InventoryApiController.php:80        (Web Services: checkSafety & fetchActive)
echo   Tab 6: NotificationDispatcher.php:12        (Pattern Context / Dispatcher)
echo.

call "%EDITOR%" -r ^
  -g "%BASE%app\Strategies\Notification\NotificationStrategyInterface.php:14" ^
  -g "%BASE%app\Strategies\Notification\EmailStrategy.php:14" ^
  -g "%BASE%app\Models\SystemLog.php:47" ^
  -g "%BASE%app\Http\Controllers\InventoryController.php:146" ^
  -g "%BASE%app\Http\Controllers\Api\InventoryApiController.php:80" ^
  -g "%BASE%app\Strategies\Notification\NotificationDispatcher.php:12"

echo.
echo [SUCCESS] All 6 Module 4 tabs opened in exact presentation sequence!
echo.
pause
