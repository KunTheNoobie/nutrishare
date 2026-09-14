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
echo   NUTRISHARE VIVA DEFENSE -- MODULE 4: WONG MEN JING (25WMR09788)
echo   Module: Inventory and Food Safety Compliance
echo =========================================================================
echo   [1] LIVE DEMO:      Warehouse items -^> Add batch -^> Check temp -^> View Logs
echo   [2] DESIGN PATTERN: Strategy Pattern (app/Strategies/Notification/NotificationStrategyInterface.php)
echo   [3] SECURITY:       Audit Log Tampering / Non-Repudiation (app/Models/SystemLog.php)
echo   [4] WEB SERVICES:   Inventory Stock API (app/Http/Controllers/Api/InventoryApiController.php)
echo =========================================================================
echo.
echo Opening 4 core viva presentation tabs in VS Code...

call "%EDITOR%" -r ^
  -g "%BASE%app\Strategies\Notification\NotificationStrategyInterface.php:6" ^
  -g "%BASE%app\Strategies\Notification\NotificationDispatcher.php:20" ^
  -g "%BASE%app\Models\SystemLog.php:20" ^
  -g "%BASE%app\Http\Controllers\Api\InventoryApiController.php:18"

echo.
echo [SUCCESS] 4 Module 4 tabs opened cleanly!
echo.
pause
