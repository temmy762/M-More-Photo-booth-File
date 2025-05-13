# Memories and More Photo Booths - PHP Form Setup

This document provides instructions for setting up the PHP environment required for the form submission functionality.

## Setup Instructions

### 1. PHP Configuration

1. Make sure PHP is installed in `C:\Users\user\Desktop\PHP`
2. Verify that the following extensions are enabled in php.ini:
   - mysqli
   - mbstring
   - openssl
   - fileinfo
3. Configure mail settings in php.ini:
   ```ini
   [mail function]
   SMTP = localhost
   smtp_port = 25
   sendmail_from = noreply@memoriesandmorephotobooths.com
   ```

### 2. Add PHP to System PATH

Run the PowerShell script as an administrator:
```powershell
powershell -ExecutionPolicy Bypass -File "c:\Users\user\Desktop\M&More Photo booth File\add-php-to-path.ps1"
```

This script will add PHP to your system PATH environment variable, allowing you to run PHP from any location.

### 3. Start the PHP Development Server

Run the batch file:
```
.\start-php-server.bat
```

This will start a PHP development server at http://localhost:8000

Alternatively, you can run this PowerShell command:
```powershell
Set-Location "c:\Users\user\Desktop\M&More Photo booth File"
& "$env:USERPROFILE\Desktop\PHP\php.exe" -S localhost:8000
```

### 4. Testing Forms

1. Open http://localhost:8000/test-form.html in your browser
2. Test the form submission
3. View detailed error messages and debugging information if there are issues

## Troubleshooting

### Check PHP Configuration

Open http://localhost:8000/phpinfo.php to see the full PHP configuration.

### Test Mail Functionality

Open http://localhost:8000/check-mail.php to test mail functionality.

### Common Issues

1. **Form submission fails with "Could not parse server response as JSON"**
   - Check for PHP syntax errors in form-handler.php
   - Enable error reporting in PHP

2. **Mail function returns FALSE**
   - Check SMTP settings in php.ini
   - Verify that your system can send emails
   - Consider using an alternative mail library like PHPMailer

3. **AJAX requests not working**
   - Check browser console for errors
   - Verify that the PHP server is running
   - Check file permissions

4. **PHP server won't start**
   - Verify PHP installation
   - Check if another service is using port 8000
   - Try specifying a different port

## Production Deployment

For production, you'll need to:

1. Set up a proper mail server or use a mail service provider
2. Configure secure SSL/TLS settings for mail
3. Remove debugging code and error outputs
4. Consider using a more robust PHP mail library like PHPMailer

## Files Overview

- `form-handler.php` - Processes form submissions
- `check-mail.php` - Tests mail functionality
- `phpinfo.php` - Shows PHP configuration
- `test-form.html` - Test form with debugging
- `start-php-server.bat` - Starts the PHP development server
- `add-php-to-path.ps1` - Adds PHP to system PATH
