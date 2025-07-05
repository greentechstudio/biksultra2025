@echo off
echo ==========================================
echo    ASRRUN - GitHub Repository Setup
echo ==========================================
echo.

echo Initializing Git repository...
git init

echo.
echo Adding all files...
git add .

echo.
echo Creating initial commit...
git commit -m "feat: initial commit - ASRRUN Dashboard Application

- WhatsApp Queue System with 10-second delay
- Automated unpaid registration cleanup after 6 hours
- Payment reminders every 30 minutes
- Xendit payment gateway integration
- Admin dashboard with monitoring
- Role-based access control
- Background job processing
- Automated scheduling system
- Testing interfaces and batch scripts
- Complete documentation"

echo.
echo Setting up remote repository...
echo Please create a new repository on GitHub named 'ASRRUN' first.
echo Then enter your GitHub username:
set /p username="GitHub Username: "

echo.
echo Adding remote origin...
git remote add origin https://github.com/%username%/ASRRUN.git

echo.
echo Setting default branch to main...
git branch -M main

echo.
echo Pushing to GitHub...
git push -u origin main

echo.
echo ==========================================
echo Repository setup complete!
echo ==========================================
echo.
echo Your repository is now available at:
echo https://github.com/%username%/ASRRUN
echo.
echo Next steps:
echo 1. Go to your GitHub repository
echo 2. Add a description and topics
echo 3. Enable GitHub Pages (optional)
echo 4. Set up branch protection rules
echo 5. Configure GitHub Actions (optional)
echo.
pause
