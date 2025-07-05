@echo off
cls
echo ===============================================
echo     GIT UPDATE HELPER
echo ===============================================
echo.

echo Step 1: Checking current git status...
echo.
git status --porcelain
echo.

echo Step 2: Adding files for commit...
echo.
echo Select files to add:
echo [1] Add only deployment fixes
echo [2] Add all modified files (except .env and vendor)
echo [3] Add specific files manually
echo [4] Show git status and exit
echo.

set /p choice="Enter your choice (1-4): "

if "%choice%"=="1" (
    echo Adding deployment fix files...
    git add app/Services/XenditService.php
    git add routes/console.php
    git add DEPLOYMENT-ERROR-FIX.md
    git add DEPLOYMENT-FIX-SUMMARY.md
    git add app/Console/Commands/DiagnoseDeploymentIssues.php
    git add app/Console/Commands/TestXenditService.php
    git add fix-deployment.bat
    echo ✅ Deployment fix files added!
) else if "%choice%"=="2" (
    echo Adding all modified files...
    git add .
    git reset HEAD .env
    git reset HEAD vendor/
    echo ✅ All files added (except .env and vendor)!
) else if "%choice%"=="3" (
    echo Please run: git add filename
    echo Example: git add app/Services/XenditService.php
    pause
    exit
) else if "%choice%"=="4" (
    echo Current git status:
    git status
    pause
    exit
) else (
    echo Invalid choice!
    pause
    exit
)

echo.
echo Step 3: Current staged files:
echo.
git status --cached --porcelain
echo.

set /p commit_msg="Enter commit message (or press Enter for default): "
if "%commit_msg%"=="" set commit_msg="Update: Latest changes"

echo.
echo Step 4: Committing changes...
echo.
git commit -m "%commit_msg%"

echo.
echo Step 5: Pushing to remote repository...
echo.
git push origin main

echo.
echo ===============================================
echo     GIT UPDATE COMPLETE
echo ===============================================
echo.
echo Recent commits:
git log --oneline -3
echo.
pause
