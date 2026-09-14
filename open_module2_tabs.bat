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
echo   NUTRISHARE VIVA DEFENSE -- MODULE 2: CHEON JIE HAN (25WMR09703)
echo   Module: NGO Verification and Peer Trust Rating System
echo =========================================================================
echo   [1] LIVE DEMO:       Login admin@nutrishare.com -^> Document Queue -^> Approve NGO
echo   [2] DESIGN PATTERN:  Factory Method (UserCreator -^> NgoCreator -^> AuthController)
echo   [3] SECURITY:        RBAC Middleware (CheckRole) + Cryptographic OTP Recovery
echo   [4] WEB SERVICES:    Provide: verifyNgo() -^> Consume: verifyClaimCompletedBeforeReview()
echo =========================================================================
echo.
echo Opening 6 presentation tabs in VS Code in exact viva sequence (left-to-right)...
echo   Tab 1: UserCreator.php:12                 (Pattern Abstract Creator)
echo   Tab 2: NgoCreator.php:11                  (Pattern Concrete Creator)
echo   Tab 3: CheckRole.php:14                   (Security 1: RBAC Middleware)
echo   Tab 4: PasswordResetController.php:30     (Security 2: Cryptographic OTP Recovery)
echo   Tab 5: UserVerificationApiController.php:40 (Web Service Provide: verifyNgo)
echo   Tab 6: VerificationController.php:107     (Web Service Consume: verifyClaimCompletedBeforeReview)
echo.

call "%EDITOR%" -r ^
  -g "%BASE%app\Services\UserFactory\UserCreator.php:12" ^
  -g "%BASE%app\Services\UserFactory\NgoCreator.php:11" ^
  -g "%BASE%app\Http\Middleware\CheckRole.php:14" ^
  -g "%BASE%app\Http\Controllers\PasswordResetController.php:30" ^
  -g "%BASE%app\Http\Controllers\Api\UserVerificationApiController.php:40" ^
  -g "%BASE%app\Http\Controllers\VerificationController.php:107"

echo.
echo [SUCCESS] All 6 Module 2 tabs opened in exact presentation sequence!
echo.
pause
