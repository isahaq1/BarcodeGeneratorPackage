<?php

namespace Isahaq\BarcodeQrCode\Tests;

use Orchestra\Testbench\TestCase;
use Isahaq\BarcodeQrCode\BarcodeQrCodeServiceProvider;
use Isahaq\BarcodeQrCode\Facades\Barcode;
use Isahaq\BarcodeQrCode\Facades\QRCode;
use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Isahaq\BarcodeQrCode\QRCodeGenerator;

class LaravelIntegrationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            BarcodeQrCodeServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Barcode' => Barcode::class,
            'QRCode' => QRCode::class,
        ];
    }

    public function testServiceProviderRegistration()
    {
        $this->assertTrue($this->app->bound('barcode'));
        $this->assertTrue($this->app->bound('qrcode'));
        
        $this->assertInstanceOf(BarcodeGenerator::class, $this->app->make('barcode'));
        $this->assertInstanceOf(QRCodeGenerator::class, $this->app->make('qrcode'));
    }

    public function testFacadeAccess()
    {
        $this->assertInstanceOf(\Isahaq\BarcodeQrCode\BarcodeGenerator::class, Barcode::getFacadeRoot());
        $this->assertInstanceOf(\Isahaq\BarcodeQrCode\QRCodeGenerator::class, QRCode::getFacadeRoot());
    }

    public function testBarcodeFacadeMethods()
    {
        $result = Barcode::generatePNG('C128', '123456789');
        $this->assertIsString($result);
        $this->assertNotEmpty($result);

        $types = Barcode::getSupportedTypes();
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);

        $isValid = Barcode::isValidType('C128');
        $this->assertTrue($isValid);
    }

    public function testQRCodeFacadeMethods()
    {
        $result = QRCode::generatePNG('https://example.com');
        $this->assertIsString($result);
        $this->assertNotEmpty($result);

        $levels = QRCode::getSupportedErrorCorrectionLevels();
        $this->assertIsArray($levels);
        $this->assertNotEmpty($levels);

        $isValid = QRCode::isValidData('https://example.com');
        $this->assertTrue($isValid);
    }

    public function testBladeDirectives()
    {
        $blade = $this->app['blade.compiler'];
        
        // Test barcode directive
        $html = $blade->compileString('@barcode("C128", "123456789")');
        $this->assertStringContainsString('app(\'barcode\')->generateHTML', $html);
        
        // Test qrcode directive
        $html = $blade->compileString('@qrcode("https://example.com")');
        $this->assertStringContainsString('app(\'qrcode\')->generateHTML', $html);
        
        // Test barcodePNG directive
        $html = $blade->compileString('@barcodePNG("C128", "123456789")');
        $this->assertStringContainsString('app(\'barcode\')->generatePNG', $html);
        
        // Test qrcodePNG directive
        $html = $blade->compileString('@qrcodePNG("https://example.com")');
        $this->assertStringContainsString('app(\'qrcode\')->generatePNG', $html);
    }

    public function testConfigurationLoading()
    {
        $config = config('barcode-qrcode');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('barcode', $config);
        $this->assertArrayHasKey('qrcode', $config);
        $this->assertArrayHasKey('output', $config);
    }

    public function testBarcodeGenerationWithConfig()
    {
        $config = config('barcode-qrcode.barcode.default_options');
        $this->assertIsArray($config);
        
        $result = Barcode::generatePNG('C128', '123456789', $config);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testQRCodeGenerationWithConfig()
    {
        $config = config('barcode-qrcode.qrcode.default_options');
        $this->assertIsArray($config);
        
        $result = QRCode::generatePNG('https://example.com', $config);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testServiceAliases()
    {
        $barcode = $this->app->make(BarcodeGenerator::class);
        $this->assertInstanceOf(BarcodeGenerator::class, $barcode);
        
        $qrcode = $this->app->make(QRCodeGenerator::class);
        $this->assertInstanceOf(QRCodeGenerator::class, $qrcode);
    }

    public function testMultipleInstances()
    {
        $barcode1 = $this->app->make('barcode');
        $barcode2 = $this->app->make('barcode');
        
        $this->assertSame($barcode1, $barcode2); // Should be singleton
        
        $qrcode1 = $this->app->make('qrcode');
        $qrcode2 = $this->app->make('qrcode');
        
        $this->assertSame($qrcode1, $qrcode2); // Should be singleton
    }

    public function testFacadeMethodCalls()
    {
        // Test all barcode facade methods
        $this->assertIsString(Barcode::generateSVG('C128', '123456789'));
        $this->assertIsString(Barcode::generateHTML('C128', '123456789'));
        $this->assertIsString(Barcode::generateJPG('C128', '123456789'));
        $this->assertIsString(Barcode::generatePDF('C128', '123456789'));
        
        // Test all QR code facade methods
        $this->assertIsString(QRCode::generateSVG('https://example.com'));
        $this->assertIsString(QRCode::generateHTML('https://example.com'));
        $this->assertIsString(QRCode::generateJPG('https://example.com'));
    }

    public function testErrorHandling()
    {
        $this->expectException(\Exception::class);
        Barcode::generatePNG('INVALID_TYPE', '123456789');
    }

    public function testQRCodeErrorHandling()
    {
        $this->expectException(\Exception::class);
        QRCode::generatePNG('');
    }
} 