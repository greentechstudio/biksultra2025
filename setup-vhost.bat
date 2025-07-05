@echo off
echo Setting up Virtual Host for ASR Dashboard...
echo.

echo Please add the following to your Apache Virtual Host configuration:
echo.
echo ^<VirtualHost *:80^>
echo     DocumentRoot "c:/xampp/htdocs/asr/dashboard-app/public"
echo     ServerName asr.local
echo     ServerAlias www.asr.local
echo     ^<Directory "c:/xampp/htdocs/asr/dashboard-app/public"^>
echo         AllowOverride All
echo         Require all granted
echo     ^</Directory^>
echo ^</VirtualHost^>
echo.
echo And add this to your hosts file (C:\Windows\System32\drivers\etc\hosts):
echo 127.0.0.1 asr.local
echo.
echo Then access via: http://asr.local
echo.
pause
