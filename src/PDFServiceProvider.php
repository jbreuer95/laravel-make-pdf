<?php

namespace Breuer\PDF;

use Breuer\PDF\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PDFServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-headless-pdf')
            ->hasConfigFile()
            ->hasCommand(InstallCommand::class);
    }
}
