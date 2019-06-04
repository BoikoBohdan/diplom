@servers(['localhost' => '127.0.0.1'])

@task('dev-migrate')

php artisan migrate:fresh --seed

php artisan db:seed --class=ProdSeeder

@endtask
