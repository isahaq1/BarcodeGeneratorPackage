<?php

namespace Isahaq\BarcodeQrCode\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generatePNG(string $type, string $code, array $options = [])
 * @method static string generateSVG(string $type, string $code, array $options = [])
 * @method static string generateHTML(string $type, string $code, array $options = [])
 * @method static string generateJPG(string $type, string $code, array $options = [])
 * @method static string generatePDF(string $type, string $code, array $options = [])
 * @method static bool save(string $type, string $code, string $filepath, string $format = 'PNG', array $options = [])
 * @method static array getSupportedTypes()
 * @method static bool isValidType(string $type)
 *
 * @see \Isahaq\BarcodeQrCode\BarcodeGenerator
 */
class Barcode extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'barcode';
    }
} 