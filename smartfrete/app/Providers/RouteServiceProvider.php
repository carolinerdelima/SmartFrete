<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Service provider responsável por registrar e organizar as rotas da aplicação.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Registra os grupos de rotas da aplicação.
     *
     * Este método define os arquivos de rotas que serão carregados para as rotas
     * da API e da Web, e os respectivos middlewares e prefixos utilizados.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
