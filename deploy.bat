@echo off
chcp 65001 > nul
setlocal

cd /d "%~dp0"

echo.
echo ========================================
echo       KOHAN POLYMER - GIT DEPLOY
echo ========================================
echo.

echo [1/3] Checking Git status...
git status --short

echo.
set /p COMMIT_MESSAGE=Enter commit message: 

if "%COMMIT_MESSAGE%"=="" (
    echo.
    echo ERROR: Commit message cannot be empty.
    pause
    exit /b 1
)

echo.
echo [2/3] Adding changes...
git add -A

git diff --cached --quiet
if %ERRORLEVEL% EQU 0 (
    echo.
    echo No changes to commit.
    pause
    exit /b 0
)

echo.
echo [3/3] Creating commit...
git commit -m "%COMMIT_MESSAGE%"

if errorlevel 1 (
    echo.
    echo ERROR: Commit failed.
    pause
    exit /b 1
)

echo.
echo Uploading changes to GitHub...
git push origin main

if errorlevel 1 (
    echo.
    echo ERROR: Push failed. Your commit is still saved locally.
    pause
    exit /b 1
)

echo.
echo ========================================
echo       DEPLOY TO GITHUB SUCCESSFUL
echo ========================================
echo.

pause