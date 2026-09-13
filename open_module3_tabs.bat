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
echo  Opening Module 3: Hiew Li Wei (25WMR09728) Tabs in VS Code / IDE...
echo  Module: Claims Management and Logistics Distribution
echo =========================================================================

call "%EDITOR%" -r ^
  -g "%BASE%app\States\Claim\ClaimState.php:18" ^
  -g "%BASE%app\States\Claim\PendingState.php:12" ^
  -g "%BASE%app\States\Claim\ApprovedState.php:25" ^
  -g "%BASE%app\States\Claim\CollectedState.php:12" ^
  -g "%BASE%app\Models\Claim.php:85" ^
  -g "%BASE%app\Policies\ClaimPolicy.php:25" ^
  -g "%BASE%app\Http\Controllers\ClaimController.php:115" ^
  -g "%BASE%app\Models\Vehicle.php:10" ^
  -g "%BASE%app\Http\Controllers\Api\ClaimApiController.php:36" ^
  -g "%BASE%app\Services\Clients\SafetyServiceClient.php:20"

echo.
echo [SUCCESS] All 10 Module 3 tabs opened successfully in VS Code!
echo.
