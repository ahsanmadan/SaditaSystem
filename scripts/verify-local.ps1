$ErrorActionPreference = 'Stop'

param(
    [switch]$SkipE2E
)

function Invoke-Step {
    param(
        [string]$Label,
        [scriptblock]$Action
    )

    Write-Host ""
    Write-Host "==> $Label" -ForegroundColor Cyan
    & $Action
}

Set-Location $PSScriptRoot\..

Invoke-Step "Clear Laravel caches" {
    php artisan config:clear
    php artisan view:clear
}

Invoke-Step "Run PHP feature verification" {
    php artisan test --filter=AuthFlowTest
    php artisan test --filter=AdminRoleAccessTest
    php artisan test --filter=AdminUserManagementFlowTest
    php artisan test --filter=AdminPanelSmokeTest
}

if ($SkipE2E) {
    Write-Host ""
    Write-Host "E2E skipped by flag -SkipE2E." -ForegroundColor Yellow
    exit 0
}

if (-not (Test-Path '.\node_modules')) {
    Write-Host ""
    Write-Host "node_modules tidak ditemukan. E2E Playwright dilewati." -ForegroundColor Yellow
    exit 0
}

if (-not $env:PLAYWRIGHT_BASE_URL) {
    $env:PLAYWRIGHT_BASE_URL = 'http://127.0.0.1:8001'
}

if (-not $env:E2E_ADMIN_EMAIL) {
    $env:E2E_ADMIN_EMAIL = 'admin'
}

if (-not $env:E2E_ADMIN_PASSWORD) {
    $env:E2E_ADMIN_PASSWORD = 'admin'
}

Write-Host ""
Write-Host "E2E memakai PLAYWRIGHT_BASE_URL=$($env:PLAYWRIGHT_BASE_URL)" -ForegroundColor DarkCyan
Write-Host "Jika E2E_DELETE_USER_ID belum diisi, skenario hapus user akan otomatis di-skip." -ForegroundColor DarkCyan

Invoke-Step "Run Playwright smoke" {
    npm run test:e2e
}
