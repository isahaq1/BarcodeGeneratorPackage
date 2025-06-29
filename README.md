# Barcode & QR Code Generator for Laravel and PHP
 ![Banner](./banner.png)

A comprehensive barcode and QR code generator package for Laravel and PHP applications. This package provides easy-to-use functionality for generating various types of barcodes and QR codes using TCPDF library.

## Features

- **Multiple Barcode Types**: Support for 30+ barcode types including Code 128, Code 39, EAN-13, UPC-A, and more
- **QR Code Generation**: Generate QR codes with customizable options using Endroid QR Code
- **Multiple Output Formats**: PNG, SVG, HTML, JPG, and PDF using TCPDF
- **Laravel Integration**: Service provider, facades, and Blade directives
- **Customizable Options**: Colors, sizes, margins, error correction levels
- **Comprehensive Testing**: Full test coverage with PHPUnit
- **Easy to Use**: Simple API for both Laravel and standalone PHP usage
- **No External Dependencies**: Uses only TCPDF and Endroid QR Code libraries

## Installation

### Via Composer

```bash
composer require isahaq/barcode-qrcode-generator
```

### For Laravel Applications

The package will automatically register the service provider. If you're using Laravel 5.5+, the package will be auto-discovered.

For older Laravel versions, add the service provider to your `config/app.php`:

```php
'providers' => [
    // ...
    Isahaq\BarcodeQrCode\BarcodeQrCodeServiceProvider::class,
],

'aliases' => [
    // ...
    'Barcode' => Isahaq\BarcodeQrCode\Facades\Barcode::class,
    'QRCode' => Isahaq\BarcodeQrCode\Facades\QRCode::class,
],
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Isahaq\BarcodeQrCode\BarcodeQrCodeServiceProvider" --tag="barcode-qrcode-config"
```

## Usage

### Laravel Usage

#### Using Facades

```php
use Isahaq\BarcodeQrCode\Facades\Barcode;
use Isahaq\BarcodeQrCode\Facades\QRCode;

// Generate barcode
$barcodePNG = Barcode::generatePNG('C128', '123456789');
$barcodeSVG = Barcode::generateSVG('EAN13', '1234567890123');

// Generate QR code
$qrCodePNG = QRCode::generatePNG('https://example.com');
$qrCodeSVG = QRCode::generateSVG('Hello World');

// Save to file
Barcode::save('C128', '123456789', 'barcode.png', 'PNG');
QRCode::save('https://example.com', 'qrcode.png', 'PNG');
```

#### Using Dependency Injection

```php
use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Isahaq\BarcodeQrCode\QRCodeGenerator;

class BarcodeController extends Controller
{
    public function generate(BarcodeGenerator $barcode, QRCodeGenerator $qrcode)
    {
        $barcodeImage = $barcode->generatePNG('C128', '123456789');
        $qrCodeImage = $qrcode->generatePNG('https://example.com');

        return response($barcodeImage)->header('Content-Type', 'image/png');
    }
}
```

#### Using Blade Directives

```blade
<!-- Generate HTML barcode -->
@barcode('C128', '123456789')

<!-- Generate HTML QR code -->
@qrcode('https://example.com')

<!-- Generate PNG barcode as image -->
@barcodePNG('C128', '123456789')

<!-- Generate PNG QR code as image -->
@qrcodePNG('https://example.com')
```

### Standalone PHP Usage

```php
require 'vendor/autoload.php';

use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Isahaq\BarcodeQrCode\QRCodeGenerator;

// Initialize generators
$barcode = new BarcodeGenerator();
$qrcode = new QRCodeGenerator();

// Generate barcode
$barcodePNG = $barcode->generatePNG('C128', '123456789');

// Generate QR code
$qrCodePNG = $qrcode->generatePNG('https://example.com');

// Save to file
$barcode->save('C128', '123456789', 'barcode.png', 'PNG');
$qrcode->save('https://example.com', 'qrcode.png', 'PNG');
```

## Configuration

The package configuration file (`config/barcode-qrcode.php`) allows you to customize default settings:

```php
return [
    'barcode' => [
        'default_type' => 'C128',
        'default_options' => [
            'width' => 2,
            'height' => 30,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'text' => true,
            'text_size' => 12,
            'text_position' => 'bottom',
            'padding' => 10
        ],
    ],

    'qrcode' => [
        'default_options' => [
            'size' => 300,
            'margin' => 10,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'error_correction_level' => 'medium',
            'round_block_size_mode' => 'margin',
        ],
    ],
];
```

## Barcode Types

The package supports the following barcode types (using TCPDF):

- **C39** - Code 39
- **C39+** - Code 39+
- **C39E** - Code 39 Extended
- **C39E+** - Code 39 Extended+
- **C93** - Code 93
- **S25** - Standard 2 of 5
- **S25+** - Standard 2 of 5+
- **I25** - Interleaved 2 of 5
- **I25+** - Interleaved 2 of 5+
- **C128** - Code 128
- **C128A** - Code 128 A
- **C128B** - Code 128 B
- **C128C** - Code 128 C
- **EAN2** - EAN 2
- **EAN5** - EAN 5
- **EAN8** - EAN 8
- **EAN13** - EAN 13
- **UPCA** - UPC-A
- **UPCE** - UPC-E
- **MSI** - MSI
- **MSI+** - MSI+
- **POSTNET** - POSTNET
- **PLANET** - PLANET
- **RMS4CC** - RMS4CC
- **KIX** - KIX
- **IMB** - IMB
- **CODABAR** - Codabar
- **CODE11** - Code 11
- **PHARMA** - Pharma Code
- **PHARMA2T** - Pharma Code Two-Track

## QR Code Options

QR codes support the following options (using Endroid QR Code):

- **size**: Size of the QR code (default: 300)
- **margin**: Margin around the QR code (default: 10)
- **foreground_color**: Foreground color as RGB array (default: [0, 0, 0])
- **background_color**: Background color as RGB array (default: [255, 255, 255])
- **error_correction_level**: Error correction level (low, medium, high, quartile)
- **round_block_size_mode**: Round block size mode (margin, enlarge, shrink)
- **logo_path**: Path to logo image to embed in QR code
- **logo_size**: Size of the logo (default: 100)

## Advanced Usage

### Custom Options

```php
// Barcode with custom options
$options = [
    'width' => 3,
    'height' => 50,
    'foreground_color' => [255, 0, 0], // Red
    'background_color' => [255, 255, 255], // White
    'padding' => 20
];

$barcode = Barcode::generatePNG('C128', '123456789', $options);

// QR code with custom options
$options = [
    'size' => 400,
    'margin' => 20,
    'foreground_color' => [0, 0, 255], // Blue
    'background_color' => [255, 255, 255], // White
    'error_correction_level' => 'high',
    'round_block_size_mode' => 'enlarge'
];

$qrCode = QRCode::generatePNG('https://example.com', $options);
```

### Multiple Formats

```php
// Generate in multiple formats
$formats = ['PNG', 'SVG', 'HTML', 'JPG'];

foreach ($formats as $format) {
    $barcode = Barcode::generate($format, 'C128', '123456789');
    $qrCode = QRCode::generate($format, 'https://example.com');
}
```

### Validation

```php
// Check if barcode type is supported
if (Barcode::isValidType('C128')) {
    $barcode = Barcode::generatePNG('C128', '123456789');
}

// Check if QR code data is valid
if (QRCode::isValidData('https://example.com')) {
    $qrCode = QRCode::generatePNG('https://example.com');
}
```

## Testing

Run the test suite:

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## Examples

### E-commerce Product Barcode

```php
// Generate EAN-13 barcode for product
$productCode = '1234567890123';
$barcode = Barcode::generatePNG('EAN13', $productCode);

// Save to product images directory
Barcode::save('EAN13', $productCode, 'products/barcode.png', 'PNG');
```

### Contact QR Code

```php
// Generate vCard QR code
$vCard = "BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nTEL:+1234567890\nEMAIL:john@example.com\nEND:VCARD";
$qrCode = QRCode::generatePNG($vCard);

// Save to contacts directory
QRCode::save($vCard, 'contacts/john_doe.png', 'PNG');
```

### URL QR Code with Logo

```php
$options = [
    'size' => 400,
    'logo_path' => 'logo.png',
    'logo_size' => 80,
    'error_correction_level' => 'high'
];

$qrCode = QRCode::generatePNG('https://example.com', $options);
```

## Dependencies

- **TCPDF**: For barcode generation in multiple formats
- **Endroid QR Code**: For QR code generation with advanced features

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Run the test suite
6. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support, please open an issue on GitHub or contact the maintainer.

## Changelog

### v1.0.0

- Initial release
- Support for 30+ barcode types using TCPDF
- QR code generation with custom options using Endroid QR Code
- Multiple output formats (PNG, SVG, HTML, JPG, PDF)
- Laravel integration with service provider and facades
- Blade directives
- Comprehensive test suite
- No external barcode library dependencies
