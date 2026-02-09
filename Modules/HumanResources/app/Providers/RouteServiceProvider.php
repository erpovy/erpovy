<?php

namespace Modules\HumanResources\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'HumanResources';

    protected string $moduleNameLower = 'humanresources';

    public function map(): void
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(module_path($this->moduleName, 'routes/web.php'));
    }
}
