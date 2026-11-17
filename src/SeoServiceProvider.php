<?php

namespace ItmoSoft\Seo;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/itmosoft-seo.php',
            'itmosoft-seo'
        );

        $this->app->scoped(SeoManager::class, function ($app): SeoManager {
            return new SeoManager($app['config']->get('itmosoft-seo.defaults', []));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/itmosoft-seo.php' => config_path('itmosoft-seo.php'),
        ], 'itmosoft-seo-config');

        Blade::directive('seo', function (): string {
            return '<?php echo app(\\ItmoSoft\\Seo\\SeoManager::class)->render(); ?>';
        });
    }
}
