<?php

/**
 * Laravel Usage Examples for Barcode & QR Code Generator
 * 
 * This file demonstrates how to use the package in Laravel applications.
 * Copy these examples to your Laravel controllers, services, or other classes.
 */

use Isahaq\BarcodeQrCode\Facades\Barcode;
use Isahaq\BarcodeQrCode\Facades\QRCode;
use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Isahaq\BarcodeQrCode\QRCodeGenerator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

// Example 1: Controller method using Facades
class BarcodeController extends Controller
{
    public function generateBarcode($type, $code)
    {
        // Generate barcode using facade
        $barcodePNG = Barcode::generatePNG($type, $code);
        
        // Return as image response
        return response($barcodePNG)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="barcode.png"');
    }

    public function generateQRCode($data)
    {
        // Generate QR code using facade
        $qrCodePNG = QRCode::generatePNG($data);
        
        // Return as image response
        return response($qrCodePNG)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="qrcode.png"');
    }

    public function downloadBarcode($type, $code, $format = 'PNG')
    {
        // Generate and download barcode
        $method = 'generate' . strtoupper($format);
        $content = Barcode::$method($type, $code);
        
        $filename = "barcode_{$type}_{$code}.{$format}";
        
        return response($content)
            ->header('Content-Type', $this->getMimeType($format))
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function saveBarcode($type, $code, $format = 'PNG')
    {
        // Save barcode to storage
        $filename = "barcodes/{$type}_{$code}.{$format}";
        
        $saved = Barcode::save($type, $code, Storage::path($filename), $format);
        
        if ($saved) {
            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => Storage::url($filename)
            ]);
        }
        
        return response()->json(['success' => false], 500);
    }

    private function getMimeType($format)
    {
        $mimeTypes = [
            'PNG' => 'image/png',
            'SVG' => 'image/svg+xml',
            'HTML' => 'text/html',
            'JPG' => 'image/jpeg',
            'PDF' => 'application/pdf'
        ];
        
        return $mimeTypes[strtoupper($format)] ?? 'application/octet-stream';
    }
}

// Example 2: Service class using Dependency Injection
class BarcodeService
{
    protected $barcodeGenerator;
    protected $qrCodeGenerator;

    public function __construct(BarcodeGenerator $barcodeGenerator, QRCodeGenerator $qrCodeGenerator)
    {
        $this->barcodeGenerator = $barcodeGenerator;
        $this->qrCodeGenerator = $qrCodeGenerator;
    }

    public function generateProductBarcode($productCode, $type = 'EAN13')
    {
        // Validate barcode type
        if (!$this->barcodeGenerator->isValidType($type)) {
            throw new \InvalidArgumentException("Unsupported barcode type: {$type}");
        }

        // Generate barcode with custom options
        $options = [
            'width' => 2,
            'height' => 40,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'padding' => 15
        ];

        return $this->barcodeGenerator->generatePNG($type, $productCode, $options);
    }

    public function generateProductQRCode($productUrl, $options = [])
    {
        // Validate QR code data
        if (!$this->qrCodeGenerator->isValidData($productUrl)) {
            throw new \InvalidArgumentException("Invalid QR code data");
        }

        // Merge with default options
        $defaultOptions = [
            'size' => 300,
            'margin' => 10,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'error_correction_level' => 'medium'
        ];

        $finalOptions = array_merge($defaultOptions, $options);

        return $this->qrCodeGenerator->generatePNG($productUrl, $finalOptions);
    }

    public function generateContactQRCode($contactData)
    {
        // Generate vCard format
        $vCard = $this->generateVCard($contactData);
        
        $options = [
            'size' => 400,
            'error_correction_level' => 'high',
            'foreground_color' => [0, 100, 200] // Blue color
        ];

        return $this->qrCodeGenerator->generatePNG($vCard, $options);
    }

    private function generateVCard($contactData)
    {
        $vCard = "BEGIN:VCARD\n";
        $vCard .= "VERSION:3.0\n";
        $vCard .= "FN:{$contactData['name']}\n";
        
        if (isset($contactData['phone'])) {
            $vCard .= "TEL:{$contactData['phone']}\n";
        }
        
        if (isset($contactData['email'])) {
            $vCard .= "EMAIL:{$contactData['email']}\n";
        }
        
        if (isset($contactData['company'])) {
            $vCard .= "ORG:{$contactData['company']}\n";
        }
        
        $vCard .= "END:VCARD";
        
        return $vCard;
    }
}

// Example 3: Blade template usage
/*
In your Blade templates, you can use the provided directives:

@barcode('C128', '123456789')
@qrcode('https://example.com')
@barcodePNG('C128', '123456789')
@qrcodePNG('https://example.com')

Or use the facades directly in PHP blocks:

@php
    $barcode = Barcode::generateHTML('C128', '123456789');
    $qrcode = QRCode::generateHTML('https://example.com');
@endphp

{!! $barcode !!}
{!! $qrcode !!}
*/

// Example 4: Artisan command
class GenerateBarcodeCommand extends Command
{
    protected $signature = 'barcode:generate {type} {code} {--format=PNG} {--output=}';
    protected $description = 'Generate a barcode';

    public function handle()
    {
        $type = $this->argument('type');
        $code = $this->argument('code');
        $format = strtoupper($this->option('format'));
        $output = $this->option('output');

        // Validate barcode type
        if (!Barcode::isValidType($type)) {
            $this->error("Unsupported barcode type: {$type}");
            return 1;
        }

        try {
            if ($output) {
                // Save to file
                $saved = Barcode::save($type, $code, $output, $format);
                if ($saved) {
                    $this->info("Barcode saved to: {$output}");
                } else {
                    $this->error("Failed to save barcode");
                    return 1;
                }
            } else {
                // Output to console (for text formats)
                $method = 'generate' . $format;
                $content = Barcode::$method($type, $code);
                $this->line($content);
            }
        } catch (\Exception $e) {
            $this->error("Error generating barcode: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

// Example 5: Middleware for dynamic barcode generation
class BarcodeMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if request is for barcode generation
        if ($request->is('barcode/*')) {
            $type = $request->segment(2);
            $code = $request->segment(3);
            $format = $request->get('format', 'PNG');

            if (Barcode::isValidType($type)) {
                $method = 'generate' . strtoupper($format);
                $content = Barcode::$method($type, $code);
                
                return response($content)
                    ->header('Content-Type', $this->getMimeType($format))
                    ->header('Cache-Control', 'public, max-age=3600');
            }
        }

        return $next($request);
    }

    private function getMimeType($format)
    {
        $mimeTypes = [
            'PNG' => 'image/png',
            'SVG' => 'image/svg+xml',
            'HTML' => 'text/html',
            'JPG' => 'image/jpeg'
        ];
        
        return $mimeTypes[strtoupper($format)] ?? 'application/octet-stream';
    }
}

// Example 6: Configuration usage
/*
In your config/barcode-qrcode.php (after publishing):

return [
    'barcode' => [
        'default_type' => 'C128',
        'default_options' => [
            'width' => 2,
            'height' => 30,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
        ],
    ],
    'qrcode' => [
        'default_options' => [
            'size' => 300,
            'margin' => 10,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'error_correction_level' => 'medium',
        ],
    ],
];
*/

// Example 7: Route definitions
/*
Add these routes to your routes/web.php:

Route::get('/barcode/{type}/{code}', [BarcodeController::class, 'generateBarcode']);
Route::get('/qrcode/{data}', [BarcodeController::class, 'generateQRCode']);
Route::get('/barcode/download/{type}/{code}', [BarcodeController::class, 'downloadBarcode']);
Route::post('/barcode/save', [BarcodeController::class, 'saveBarcode']);

// Or use middleware for dynamic generation
Route::get('/barcode/{type}/{code}', function($type, $code) {
    return Barcode::generatePNG($type, $code);
})->middleware('barcode');
*/ 