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
echo  Opening Module 2: Cheon Jie Han (25WMR09703) Tabs in VS Code / IDE...
echo  Module: NGO Verification and Peer Trust Ratings
echo =========================================================================

call "%EDITOR%" -r ^
  -g "%BASE%app\Services\UserFactory\UserCreator.php:15" ^
  -g "%BASE%app\Services\UserFactory\NgoCreator.php:14" ^
  -g "%BASE%app\Services\UserFactory\DonorCreator.php:14" ^
  -g "%BASE%app\Http\Controllers\AuthController.php:34" ^
  -g "%BASE%app\Models\VerificationDocument.php:12" ^
  -g "%BASE%app\Http\Controllers\VerificationController.php:20" ^
  -g "%BASE%app\Http\Middleware\CheckRole.php:15" ^
  -g "%BASE%app\Http\Controllers\PasswordResetController.php:25" ^
  -g "%BASE%app\Http\Controllers\Api\UserVerificationApiController.php:40" ^
  -g "%BASE%resources\views\verification\index.blade.php:15"

echo.
echo [SUCCESS] All 10 Module 2 tabs opened successfully in VS Code!
echo.
