<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // register module migrations
        $modulePath = base_path('modules');
        $modules = File::directories($modulePath);

        foreach ($modules as $module) {
            $migrationPath = $module . '/Database/Migrations';
            if (File::isDirectory($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }
        }

        
        // Agregar rastreo de usuario
        Blueprint::macro('userTracking', function () {
            $this->unsignedBigInteger('created_by')->nullable();
            $this->unsignedBigInteger('updated_by')->nullable();

            $this->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $this->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Agregar rastreo de fecha (año y mes)
        Blueprint::macro('dateTracking', function () {
            $this->timestamps();
            $this->year('created_year')->nullable();
            $this->string('created_month', 7)->nullable(); // yyyy-mm
            $this->year('updated_year')->nullable();
            $this->string('updated_month', 7)->nullable(); // yyyy-mm
        });

        // Agregar rastreo de bloqueo
        Blueprint::macro('lockTracking', function () {
            $this->boolean('is_locked')->default(false);
            $this->unsignedBigInteger('locked_by')->nullable();
            $this->timestamp('locked_at')->nullable();
            $this->year('locked_year')->nullable();
            $this->string('locked_month', 7)->nullable(); // yyyy-mm
            $this->foreign('locked_by')->references('id')->on('users')->onDelete('set null');
        });

        // Macro para agregar todos los campos de restreo juntos
        Blueprint::macro('fullTracking', function () {

            $this->userTracking();
            $this->dateTracking();
            $this->lockTracking();
        });
        
        // Macro para agregar todos los campos de restreo juntos
        Blueprint::macro('commonColumns', function () {
            $this->text('description')->nullable();
            $this->userTracking();
            $this->dateTracking();
            $this->lockTracking();
        });
    }
}
