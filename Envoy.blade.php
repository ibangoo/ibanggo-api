@servers(['web' => 'ubuntu@118.126.101.144'])

@task('deploy')
cd /www/ibanggo-api
git pull origin develop
php artisan migrate
@endtask
