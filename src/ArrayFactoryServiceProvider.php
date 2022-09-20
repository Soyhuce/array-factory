<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Soyhuce\ArrayFactory\Commands\ArrayFactoryCommand;

class ArrayFactoryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('array-factory')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_array-factory_table')
            ->hasCommand(ArrayFactoryCommand::class);
    }
}
