@echo off
set BIN="C:\Users\yiler\AppData\Local\Programs\Antigravity\bin\antigravity-ide.cmd"
set BASE=%~dp0

echo Opening Module 1 (Liew Yi Ler) Tabs in Antigravity IDE...

call %BIN% -r "%BASE%app\Contracts\DonationObserverInterface.php"
call %BIN% -r "%BASE%app\Observers\DonationObserver.php"
call %BIN% -r "%BASE%app\Providers\EventServiceProvider.php"
call %BIN% -r "%BASE%app\Jobs\SendDonationNotificationJob.php"
call %BIN% -r "%BASE%app\Http\Controllers\NotificationController.php"
call %BIN% -r "%BASE%app\Models\Notification.php"
call %BIN% -r "%BASE%resources\views\notifications\index.blade.php"
call %BIN% -r "%BASE%resources\views\donations\create.blade.php"
call %BIN% -r "%BASE%resources\views\donations\show.blade.php"
call %BIN% -r "%BASE%app\Http\Controllers\Api\DonationApiController.php"

echo All Module 1 Donation & Notification tabs opened successfully!
