#!/bin/sh

abort()
{
    echo >&2 '
***************
*** ABORTED ***
***************
'
    echo "An error occurred. Exiting..." >&2
    exit 1
}

trap 'abort' 0

set -e

#----------------------------------------------------------

echo >&2 '
****************************
*** RUNNING DEPLOY TASKS *** 
****************************
'

# Pull the latest changes from the git repository
# git reset --hard
# git clean -df
git pull

# Clear config cache
php artisan config:clear

# Install/update composer dependecies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Run database migrations
php artisan migrate --force
php artisan migrate --database mysql_es --force
php artisan migrate --database mysql_pt --force
php artisan migrate --database mysql_ja --force

# Clear caches
php artisan cache:clear

# Clear expired password reset tokens
php artisan auth:clear-resets

# Cache config
php artisan config:cache

# Done!
trap : 0

echo >&2 '
*************
*** DONE! *** 
*************
'
