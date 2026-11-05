param([string]$Target = "dealbd")
$ErrorActionPreference = "Stop"
$parent = Split-Path -Parent $PSScriptRoot
if (-not [System.IO.Path]::IsPathRooted($Target)) { $Target = Join-Path $parent $Target }
if (Test-Path $Target) { throw "Target path already exists: $Target" }
composer create-project laravel/laravel $Target '^13.0'
if ($LASTEXITCODE -ne 0) { throw "Composer failed" }
Copy-Item -Path (Join-Path $PSScriptRoot '*') -Destination $Target -Recurse -Force
Push-Location $Target
try {
    php artisan key:generate
    Write-Host "Code installed. Set your database credentials in .env, then run:"
    Write-Host "php artisan migrate --seed"
    Write-Host "php artisan serve"
} finally { Pop-Location }
