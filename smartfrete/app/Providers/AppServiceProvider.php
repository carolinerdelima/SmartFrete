<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Docs\OpenApiSpec;

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
        // Força o carregamento da classe de documentação para o Swagger reconhecê-la
        if (class_exists(OpenApiSpec::class)) {
            // nada precisa ser feito aqui
        }
    }
}
