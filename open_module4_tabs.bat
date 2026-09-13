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

echo =========================================================================
echo  Opening Module 4: Wong Men Jing (25WMR09788) Tabs in VS Code / IDE...
echo  Module: Inventory and Food Safety Compliance
echo =========================================================================

call "%EDITOR%" -r ^
  -g "%BASE%app\Strategies\Notification\NotificationStrategyInterface.php:6" ^
  -g "%BASE%app\Strategies\Notification\EmailStrategy.php:14" ^
  -g "%BASE%app\Strategies\Notification\SMSStrategy.php:14" ^
  -g "%BASE%app\Strategies\Notification\NotificationDispatcher.php:30" ^
  -g "%BASE%app\Models\FoodItem.php:15" ^
  -g "%BASE%app\Models\InventoryLocation.php:15" ^
  -g "%BASE%app\Models\SystemLog.php:48" ^
  -g "%BASE%app\Http\Controllers\InventoryController.php:149" ^
  -g "%BASE%app\Http\Controllers\Api\InventoryApiController.php:80" ^
  -g "%BASE%app\Repositories\InventoryRepository.php:35"

echo.
echo [SUCCESS] All 10 Module 4 tabs opened successfully in VS Code!
echo.
