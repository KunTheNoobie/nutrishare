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
echo   NUTRISHARE VIVA DEFENSE -- MODULE 3: HIEW LI WEI (25WMR09728)
echo   Module: Claims and Logistics Distribution
echo =========================================================================
echo   [1] LIVE DEMO:       Login pichaeats@nutrishare.com -^> Claim #5 -^> Approve -^> Receipt
echo   [2] DESIGN PATTERN:  State Pattern (NOT an Enum! Pending -^> Approved -^> Collected)
echo   [3] SECURITY:        Policy-Driven Authorization (ClaimPolicy) + Row Locking (lockForUpdate)
echo   [4] WEB SERVICES:    Provide: details() -^> Consume: verifySafetyCompliance()
echo =========================================================================
echo.
echo Opening 5 presentation tabs in VS Code (matching Viva Guide)...

call "%EDITOR%" -r ^
  -g "%BASE%app\States\Claim\ClaimState.php:8" ^
  -g "%BASE%app\States\Claim\ApprovedState.php:7" ^
  -g "%BASE%app\Policies\ClaimPolicy.php:14" ^
  -g "%BASE%app\Http\Controllers\Api\ClaimApiController.php:40" ^
  -g "%BASE%app\Services\Clients\SafetyServiceClient.php:26"

echo.
echo [SUCCESS] 5 Module 3 tabs opened cleanly!
echo.
pause
