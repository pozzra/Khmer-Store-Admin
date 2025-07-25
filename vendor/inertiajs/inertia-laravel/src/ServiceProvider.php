<?php

namespace Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Testing\TestResponse;
use Illuminate\View\FileViewFinder;
use Inertia\Ssr\Gateway;
use Inertia\Ssr\HttpGateway;
use Inertia\Support\Header;
use Inertia\Testing\TestResponseMacros;
use LogicException;
use ReflectionException;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ResponseFactory::class);
        $this->app->bind(Gateway::class, HttpGateway::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/inertia.php',
            'inertia'
        );

        $this->registerBladeDirectives();
        $this->registerRequestMacro();
        $this->registerRouterMacro();
        $this->registerTestingMacros();
        $this->registerMiddleware();

        $this->app->bind('inertia.view-finder', function ($app) {
            return new FileViewFinder(
                $app['files'],
                $app['config']->get('inertia.page_paths'),
                $app['config']->get('inertia.page_extensions')
            );
        });

        $this->app->bind('inertia.testing.view-finder', function ($app) {
            return new FileViewFinder(
                $app['files'],
                $app['config']->get('inertia.testing.page_paths'),
                $app['config']->get('inertia.testing.page_extensions')
            );
        });
    }

    public function boot(): void
    {
        $this->registerConsoleCommands();

        $this->publishes([
            __DIR__.'/../config/inertia.php' => config_path('inertia.php'),
        ]);
    }

    protected function registerBladeDirectives(): void
    {
        $this->callAfterResolving('blade.compiler', function ($blade) {
            $blade->directive('inertia', [Directive::class, 'compile']);
            $blade->directive('inertiaHead', [Directive::class, 'compileHead']);
        });
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Commands\CreateMiddleware::class,
            Commands\StartSsr::class,
            Commands\StopSsr::class,
            Commands\CheckSsr::class,
        ]);
    }

    protected function registerRequestMacro(): void
    {
        Request::macro('inertia', function () {
            return (bool) $this->header(Header::INERTIA);
        });
    }

    protected function registerRouterMacro(): void
    {
        Router::macro('inertia', function ($uri, $component, $props = []) {
            return $this->match(['GET', 'HEAD'], $uri, '\\'.Controller::class)
                ->defaults('component', $component)
                ->defaults('props', $props);
        });
    }

    /**
     * @throws ReflectionException|LogicException
     */
    protected function registerTestingMacros(): void
    {
        if (class_exists(TestResponse::class)) {
            TestResponse::mixin(new TestResponseMacros);

            return;
        }

        throw new LogicException('Could not detect TestResponse class.');
    }

    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware(
            'inertia.encrypt',
            EncryptHistoryMiddleware::class
        );
    }
}
