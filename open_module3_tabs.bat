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
echo Opening 6 presentation tabs in VS Code in exact viva sequence (left-to-right)...
echo   Tab 1: ClaimState.php:8           (Pattern Abstract State Class)
echo   Tab 2: ApprovedState.php:22       (Pattern Concrete State Class)
echo   Tab 3: ClaimPolicy.php:33         (Security 1: BOLA/IDOR Policy Gate)
echo   Tab 4: ClaimController.php:131    (Security 2: Pessimistic Row Locking lockForUpdate)
echo   Tab 5: ClaimApiController.php:40  (Web Service Provide: details)
echo   Tab 6: SafetyServiceClient.php:25 (Web Service Consume: verifySafetyCompliance)
echo.

call "%EDITOR%" -r ^
  -g "%BASE%app\States\Claim\ClaimState.php:8" ^
  -g "%BASE%app\States\Claim\ApprovedState.php:22" ^
  -g "%BASE%app\Policies\ClaimPolicy.php:33" ^
  -g "%BASE%app\Http\Controllers\ClaimController.php:131" ^
  -g "%BASE%app\Http\Controllers\Api\ClaimApiController.php:40" ^
  -g "%BASE%app\Services\Clients\SafetyServiceClient.php:25"

echo.
echo [SUCCESS] All 6 Module 3 tabs opened in exact presentation sequence!
echo.
pause
