<?php

namespace Isahaq\BarcodeQrCode;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BarcodeQrCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/barcode-qrcode.php', 'barcode-qrcode'
        );

        $this->app->singleton('barcode', function ($app) {
            return new BarcodeGenerator();
        });

        $this->app->singleton('qrcode', function ($app) {
            return new QRCodeGenerator();
        });

        $this->app->alias('barcode', BarcodeGenerator::class);
        $this->app->alias('qrcode', QRCodeGenerator::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/barcode-qrcode.php' => config_path('barcode-qrcode.php'),
        ], 'barcode-qrcode-config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'barcode-qrcode');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/barcode-qrcode'),
        ], 'barcode-qrcode-views');

        $this->registerBladeDirectives();
    }

    /**
     * Register Blade directives
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('barcode', function ($expression) {
            return "<?php echo app('barcode')->generateHTML($expression); ?>";
        });

        Blade::directive('qrcode', function ($expression) {
            return "<?php echo app('qrcode')->generateHTML($expression); ?>";
        });

        Blade::directive('barcodePNG', function ($expression) {
            return "<?php echo '<img src=\"data:image/png;base64,' . base64_encode(app('barcode')->generatePNG($expression)) . '\" alt=\"Barcode\">'; ?>";
        });

        Blade::directive('qrcodePNG', function ($expression) {
            return "<?php echo '<img src=\"data:image/png;base64,' . base64_encode(app('qrcode')->generatePNG($expression)) . '\" alt=\"QR Code\">'; ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['barcode', 'qrcode'];
    }
} 