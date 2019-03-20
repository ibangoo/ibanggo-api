@servers(['web' => 'ubuntu@118.126.101.144'])

@task('deploy')
cd /www/ibanggo-api
git fetch --all && git reset --hard origin/develop
composer install
php artisan migrate
@endtask
