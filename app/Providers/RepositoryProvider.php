<?php

namespace App\Providers;

use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Interfaces\BaseRepository\BaseRepositoryInterface;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Property\PropertyRepository;
use App\Repositories\Role\RoleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
    }
}
