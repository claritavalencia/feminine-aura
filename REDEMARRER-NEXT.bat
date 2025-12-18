@echo off
echo ========================================
echo   REDEMARRAGE NEXT.JS - Feminine Aura
echo ========================================
echo.

echo [1/3] Suppression du cache .next...
if exist .next (
    rmdir /s /q .next
    echo     Cache supprime avec succes!
) else (
    echo     Aucun cache a supprimer
)
echo.

echo [2/3] Installation des dependances (si necessaire)...
call npm install
echo.

echo [3/3] Demarrage du serveur Next.js...
echo.
echo ========================================
echo   Serveur en cours de demarrage...
echo   Appuyez sur Ctrl+C pour arreter
echo ========================================
echo.

npm run dev

pause
