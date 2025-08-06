<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Docs\OpenApiSpec;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação.
     *
     * Este método é chamado antes de todos os outros service providers.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Executa tarefas de inicialização após todos os serviços terem sido registrados.
     *
     * Aqui, forcei o autoload da classe de especificação OpenAPI para que
     * o Swagger consiga reconhecer mesmo que não seja referenciada diretamente.
     *
     * @return void
     */
    public function boot(): void
    {
        // Força o carregamento da classe de documentação para o Swagger reconhecê-la
        if (class_exists(OpenApiSpec::class)) {
            // Nada precisa ser feito aqui; apenas garantir o autoload.
        }
    }
}
