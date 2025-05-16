<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\ExcelServiceProvider as BaseExcelServiceProvider;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Classes\PHPExcel;
use PHPExcel_Settings;
use PHPExcel_Shared_Font;
use PHPExcel_Style_NumberFormat;

class ExcelCompatibilityServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Prevent the original service provider from being registered
        $this->app->bind('excel.service_provider', function () {
            return $this;
        });

        // Register the main Excel class
        $this->app->singleton('excel', function($app) {
            return new Excel($app);
        });

        // Register the writer class
        $this->app->singleton('excel.writer', function($app) {
            return new LaravelExcelWriter($app);
        });

        // Register the reader class
        $this->app->singleton('excel.reader', function($app) {
            return new LaravelExcelReader($app);
        });

        // Additional bindings as needed
        $this->app->singleton('phpexcel', function() {
            return new PHPExcel();
        });

        // Register aliases
        $this->app->singleton('excel.sheet', function() {
            return new LaravelExcelWorksheet;
        });
    }

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        // Any boot code needed
    }
}
