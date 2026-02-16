#!/bin/bash

echo "🚀 Deploying to GitHub..."

# Add all changes
git add .

# Function to get current branch name
get_branch() {
  git branch --show-current
}

BRANCH=$(get_branch)
MESSAGE="Auto-deploy: $(date '+%Y-%m-%d %H:%M:%S')"

# Commit changes
if git diff-index --quiet HEAD --; then
    echo "⚠️  No changes to commit."
else
    git commit -m "$MESSAGE"
    echo "✅ Changes committed: $MESSAGE"
fi

# Push changes
echo "⬆️  Pushing to origin/$BRANCH..."
git push origin "$BRANCH"

echo "🎉 Deployment pushed to GitHub successfully!"
