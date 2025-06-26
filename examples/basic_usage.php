<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Isahaq\BarcodeQrCode\QRCodeGenerator;

echo "=== Barcode & QR Code Generator - Basic Usage Example ===\n\n";

// Initialize generators
$barcode = new BarcodeGenerator();
$qrcode = new QRCodeGenerator();

// Example 1: Generate basic barcode
echo "1. Generating basic Code 128 barcode...\n";
$barcodePNG = $barcode->generatePNG('C128', '123456789');
file_put_contents('example_barcode.png', $barcodePNG);
echo "   ✓ Barcode saved as 'example_barcode.png'\n\n";

// Example 2: Generate basic QR code
echo "2. Generating basic QR code...\n";
$qrCodePNG = $qrcode->generatePNG('https://example.com');
file_put_contents('example_qrcode.png', $qrCodePNG);
echo "   ✓ QR code saved as 'example_qrcode.png'\n\n";

// Example 3: Generate barcode in different formats
echo "3. Generating barcode in different formats...\n";
$formats = ['PNG', 'SVG', 'HTML', 'JPG'];

foreach ($formats as $format) {
    $method = 'generate' . $format;
    $content = $barcode->$method('C128', '123456789');
    $filename = "example_barcode.{$format}";
    file_put_contents($filename, $content);
    echo "   ✓ {$format} format saved as '{$filename}'\n";
}
echo "\n";

// Example 4: Generate QR code in different formats
echo "4. Generating QR code in different formats...\n";
foreach ($formats as $format) {
    $method = 'generate' . $format;
    $content = $qrcode->$method('https://example.com');
    $filename = "example_qrcode.{$format}";
    file_put_contents($filename, $content);
    echo "   ✓ {$format} format saved as '{$filename}'\n";
}
echo "\n";

// Example 5: Generate different barcode types
echo "5. Generating different barcode types...\n";
$barcodeTypes = [
    'C39' => 'ABC-123',
    'C128' => '123456789',
    'EAN13' => '1234567890123',
    'UPCA' => '123456789012'
];

foreach ($barcodeTypes as $type => $code) {
    if ($barcode->isValidType($type)) {
        $content = $barcode->generatePNG($type, $code);
        $filename = "example_{$type}.png";
        file_put_contents($filename, $content);
        echo "   ✓ {$type} barcode saved as '{$filename}'\n";
    }
}
echo "\n";

// Example 6: Generate QR code with custom options
echo "6. Generating QR code with custom options...\n";
$options = [
    'size' => 400,
    'margin' => 20,
    'foreground_color' => [255, 0, 0], // Red
    'background_color' => [255, 255, 255], // White
    'error_correction_level' => 'high',
    'round_block_size_mode' => 'enlarge'
];

$customQRCode = $qrcode->generatePNG('https://example.com', $options);
file_put_contents('example_custom_qrcode.png', $customQRCode);
echo "   ✓ Custom QR code saved as 'example_custom_qrcode.png'\n\n";

// Example 7: Generate barcode with custom options
echo "7. Generating barcode with custom options...\n";
$barcodeOptions = [
    'width' => 3,
    'height' => 50,
    'foreground_color' => [0, 0, 255], // Blue
    'background_color' => [255, 255, 255], // White
    'padding' => 20
];

$customBarcode = $barcode->generatePNG('C128', '123456789', $barcodeOptions);
file_put_contents('example_custom_barcode.png', $customBarcode);
echo "   ✓ Custom barcode saved as 'example_custom_barcode.png'\n\n";

// Example 8: Generate different QR code data types
echo "8. Generating QR codes with different data types...\n";
$qrDataTypes = [
    'url' => 'https://example.com',
    'email' => 'mailto:test@example.com',
    'phone' => 'tel:+1234567890',
    'text' => 'Hello World',
    'vcard' => "BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nTEL:+1234567890\nEMAIL:john@example.com\nEND:VCARD"
];

foreach ($qrDataTypes as $type => $data) {
    if ($qrcode->isValidData($data)) {
        $content = $qrcode->generatePNG($data);
        $filename = "example_qrcode_{$type}.png";
        file_put_contents($filename, $content);
        echo "   ✓ {$type} QR code saved as '{$filename}'\n";
    }
}
echo "\n";

// Example 9: Get supported types
echo "9. Supported barcode types:\n";
$supportedTypes = $barcode->getSupportedTypes();
foreach (array_slice($supportedTypes, 0, 10) as $type => $name) {
    echo "   - {$type}: {$name}\n";
}
echo "   ... and " . (count($supportedTypes) - 10) . " more types\n\n";

// Example 10: Get QR code options
echo "10. QR code error correction levels:\n";
$errorLevels = $qrcode->getSupportedErrorCorrectionLevels();
foreach ($errorLevels as $level => $description) {
    echo "   - {$level}: {$description}\n";
}
echo "\n";

echo "=== Example completed successfully! ===\n";
echo "Check the generated files in the current directory.\n"; 