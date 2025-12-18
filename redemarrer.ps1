# Script de redémarrage Next.js - Feminine Aura
# Usage: .\redemarrer.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  REDEMARRAGE NEXT.JS - Feminine Aura" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Vérifier qu'on est dans le bon dossier
if (-not (Test-Path "package.json")) {
    Write-Host "ERREUR: package.json non trouve!" -ForegroundColor Red
    Write-Host "Veuillez executer ce script depuis le dossier du projet" -ForegroundColor Red
    pause
    exit 1
}

# Étape 1 : Supprimer le cache .next
Write-Host "[1/4] Suppression du cache .next..." -ForegroundColor Yellow
if (Test-Path ".next") {
    Remove-Item -Recurse -Force .next -ErrorAction SilentlyContinue
    Write-Host "      Cache supprime avec succes!" -ForegroundColor Green
} else {
    Write-Host "      Aucun cache a supprimer" -ForegroundColor Gray
}
Write-Host ""

# Étape 2 : Supprimer node_modules/.cache si existe
Write-Host "[2/4] Nettoyage des caches Node..." -ForegroundColor Yellow
if (Test-Path "node_modules\.cache") {
    Remove-Item -Recurse -Force "node_modules\.cache" -ErrorAction SilentlyContinue
    Write-Host "      Cache Node supprime!" -ForegroundColor Green
} else {
    Write-Host "      Aucun cache Node a supprimer" -ForegroundColor Gray
}
Write-Host ""

# Étape 3 : Vérifier les dépendances
Write-Host "[3/4] Verification des dependances..." -ForegroundColor Yellow
npm list --depth=0 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "      Installation des dependances manquantes..." -ForegroundColor Yellow
    npm install
} else {
    Write-Host "      Dependances OK!" -ForegroundColor Green
}
Write-Host ""

# Étape 4 : Démarrer Next.js
Write-Host "[4/4] Demarrage du serveur Next.js..." -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Serveur en cours de demarrage..." -ForegroundColor Cyan
Write-Host "  Appuyez sur Ctrl+C pour arreter" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Attendez le message: " -NoNewline
Write-Host "Ready in..." -ForegroundColor Green
Write-Host ""
Write-Host "Puis ouvrez: " -NoNewline
Write-Host "http://localhost:3000" -ForegroundColor Cyan
Write-Host "Et appuyez sur: " -NoNewline
Write-Host "Ctrl+Shift+R" -ForegroundColor Yellow -NoNewline
Write-Host " (vider cache navigateur)"
Write-Host ""

npm run dev
