<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Isahaq\BarcodeQrCode\QRCodeGenerator;

echo "=== Simple Barcode & QR Code Generator Example ===\n\n";

// Initialize generators
$barcode = new BarcodeGenerator();
$qrcode = new QRCodeGenerator();

// Example 1: Generate basic barcode
echo "1. Generating Code 128 barcode...\n";
$barcodeData = $barcode->generatePNG('C128', '123456789');
file_put_contents('barcode_example.png', $barcodeData);
echo "   ✓ Barcode saved as 'barcode_example.png'\n\n";

// Example 2: Generate basic QR code
echo "2. Generating QR code...\n";
$qrCodeData = $qrcode->generatePNG('https://example.com');
file_put_contents('qrcode_example.png', $qrCodeData);
echo "   ✓ QR code saved as 'qrcode_example.png'\n\n";

// Example 3: Generate different barcode types
echo "3. Generating different barcode types...\n";
$types = ['C39', 'C128', 'EAN13'];
$codes = ['ABC-123', '123456789', '1234567890123'];

foreach ($types as $index => $type) {
    if ($barcode->isValidType($type)) {
        $data = $barcode->generatePNG($type, $codes[$index]);
        $filename = "barcode_{$type}.png";
        file_put_contents($filename, $data);
        echo "   ✓ {$type} barcode saved as '{$filename}'\n";
    }
}
echo "\n";

// Example 4: Generate QR code with custom options
echo "4. Generating QR code with custom options...\n";
$options = [
    'size' => 400,
    'margin' => 20,
    'foreground_color' => [255, 0, 0], // Red
    'background_color' => [255, 255, 255], // White
    'error_correction_level' => 'high'
];

$customQRCode = $qrcode->generatePNG('https://example.com', $options);
file_put_contents('custom_qrcode.png', $customQRCode);
echo "   ✓ Custom QR code saved as 'custom_qrcode.png'\n\n";

// Example 5: Check supported types
echo "5. Supported barcode types:\n";
$supportedTypes = $barcode->getSupportedTypes();
foreach (array_slice($supportedTypes, 0, 5) as $type => $name) {
    echo "   - {$type}: {$name}\n";
}
echo "   ... and " . (count($supportedTypes) - 5) . " more types\n\n";

echo "=== Example completed successfully! ===\n";
echo "Check the generated files in the current directory.\n"; 