@echo off
echo ===============================================
echo Memories and More Photo Booths - PHP Form Server
echo ===============================================
echo.
echo This server is REQUIRED for form submissions to work properly.
echo KEEP THIS WINDOW OPEN while testing forms on the website.
echo.
echo PHP Server will be available at: http://localhost:8000
echo Your website should be accessed through your normal web server
echo (like VS Code Live Server at http://127.0.0.1:5501)
echo.
echo Form submissions will be automatically sent to this PHP server.
echo.
echo Press Ctrl+C to stop the server when done testing...
echo ===============================================
echo.

REM Change to the website directory
cd /d "%~dp0"

REM Start the PHP development server
"c:\Users\user\Desktop\PHP\php.exe" -S localhost:8000

pause
