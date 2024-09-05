<?php
namespace App\Providers;

use App\Services\Authentication\AuthenticationServiceInterface;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;

class AuthCustomServiceProvider extends ServiceProvider
{
    public function register()
    {
        $config = Yaml::parseFile(config_path('auth_config.yaml'));
        $strategyClass = $config['strategies'][$config['default']]['class'];

        $this->app->singleton(AuthenticationServiceInterface::class, function ($app) use ($strategyClass) {
            return new $strategyClass;
        });
    }

    public function boot()
    {
        //
    }
}
