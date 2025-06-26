<?php

namespace Isahaq\BarcodeQrCode\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generatePNG(string $data, array $options = [])
 * @method static string generateSVG(string $data, array $options = [])
 * @method static string generateHTML(string $data, array $options = [])
 * @method static string generateJPG(string $data, array $options = [])
 * @method static bool save(string $data, string $filepath, string $format = 'PNG', array $options = [])
 * @method static array getSupportedErrorCorrectionLevels()
 * @method static array getSupportedRoundBlockSizeModes()
 * @method static bool isValidData(string $data)
 *
 * @see \Isahaq\BarcodeQrCode\QRCodeGenerator
 */
class QRCode extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'qrcode';
    }
} 