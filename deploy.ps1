Write-Host "🚀 Deploying to GitHub..." -ForegroundColor Cyan

# Add changes
git add .

# Get current branch
$branch = git branch --show-current
if (!$branch) {
    Write-Host "❌ Failed to determine current branch." -ForegroundColor Red
    exit 1
}

$date = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$message = "Auto-deploy: $date"

# Check for changes
git diff-index --quiet HEAD --
if ($LASTEXITCODE -ne 0) {
    # Changes found
    git commit -m "$message"
    Write-Host "✅ Changes committed: $message" -ForegroundColor Green
}
else {
    Write-Host "⚠️  No changes to commit." -ForegroundColor Yellow
}

# Push changes
Write-Host "⬆️  Pushing to origin/$branch..." -ForegroundColor Cyan
git push origin $branch

if ($LASTEXITCODE -eq 0) {
    Write-Host "🎉 Deployment pushed to GitHub successfully!" -ForegroundColor Green
}
else {
    Write-Host "❌ Deployment failed!" -ForegroundColor Red
}
