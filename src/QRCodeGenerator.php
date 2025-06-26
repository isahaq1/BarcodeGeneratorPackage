<?php

namespace Isahaq\BarcodeQrCode;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\HtmlWriter;
use Endroid\QrCode\Writer\JpgWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelQuartile;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeEnlarge;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeShrink;
use Exception;

class QRCodeGenerator
{
    protected $defaultOptions;

    public function __construct()
    {
        $this->defaultOptions = [
            'size' => 300,
            'margin' => 10,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'error_correction_level' => 'medium',
            'round_block_size_mode' => 'margin',
            'logo_path' => null,
            'logo_size' => 100,
            'logo_position' => 'center'
        ];
    }

    /**
     * Generate QR code in PNG format
     */
    public function generatePNG(string $data, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        
        try {
            $qrCode = $this->createQRCode($data, $options);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            return $result->getString();
        } catch (Exception $e) {
            throw new Exception("Failed to generate PNG QR code: " . $e->getMessage());
        }
    }

    /**
     * Generate QR code in SVG format
     */
    public function generateSVG(string $data, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        
        try {
            $qrCode = $this->createQRCode($data, $options);
            $writer = new SvgWriter();
            $result = $writer->write($qrCode);
            
            return $result->getString();
        } catch (Exception $e) {
            throw new Exception("Failed to generate SVG QR code: " . $e->getMessage());
        }
    }

    /**
     * Generate QR code in HTML format
     */
    public function generateHTML(string $data, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        
        try {
            $qrCode = $this->createQRCode($data, $options);
            $writer = new HtmlWriter();
            $result = $writer->write($qrCode);
            
            return $result->getString();
        } catch (Exception $e) {
            throw new Exception("Failed to generate HTML QR code: " . $e->getMessage());
        }
    }

    /**
     * Generate QR code in JPG format
     */
    public function generateJPG(string $data, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        
        try {
            $qrCode = $this->createQRCode($data, $options);
            $writer = new JpgWriter();
            $result = $writer->write($qrCode);
            
            return $result->getString();
        } catch (Exception $e) {
            throw new Exception("Failed to generate JPG QR code: " . $e->getMessage());
        }
    }

    /**
     * Save QR code to file
     */
    public function save(string $data, string $filepath, string $format = 'PNG', array $options = []): bool
    {
        try {
            switch (strtoupper($format)) {
                case 'PNG':
                    $content = $this->generatePNG($data, $options);
                    break;
                case 'SVG':
                    $content = $this->generateSVG($data, $options);
                    break;
                case 'HTML':
                    $content = $this->generateHTML($data, $options);
                    break;
                case 'JPG':
                    $content = $this->generateJPG($data, $options);
                    break;
                default:
                    throw new Exception("Unsupported format: {$format}");
            }

            return file_put_contents($filepath, $content) !== false;
        } catch (Exception $e) {
            throw new Exception("Failed to save QR code: " . $e->getMessage());
        }
    }

    /**
     * Create QR code instance with options
     */
    protected function createQRCode(string $data, array $options): QrCode
    {
        $qrCode = QrCode::create($data)
            ->setSize($options['size'])
            ->setMargin($options['margin'])
            ->setForegroundColor(new Color(
                $options['foreground_color'][0],
                $options['foreground_color'][1],
                $options['foreground_color'][2]
            ))
            ->setBackgroundColor(new Color(
                $options['background_color'][0],
                $options['background_color'][1],
                $options['background_color'][2]
            ))
            ->setErrorCorrectionLevel($this->getErrorCorrectionLevel($options['error_correction_level']))
            ->setRoundBlockSizeMode($this->getRoundBlockSizeMode($options['round_block_size_mode']));

        // Add logo if specified
        if (!empty($options['logo_path']) && file_exists($options['logo_path'])) {
            $qrCode->setLogoPath($options['logo_path'])
                   ->setLogoSize($options['logo_size'], $options['logo_size']);
        }

        return $qrCode;
    }

    /**
     * Get error correction level
     */
    protected function getErrorCorrectionLevel(string $level): object
    {
        switch (strtolower($level)) {
            case 'low':
                return new ErrorCorrectionLevelLow();
            case 'medium':
                return new ErrorCorrectionLevelMedium();
            case 'high':
                return new ErrorCorrectionLevelHigh();
            case 'quartile':
                return new ErrorCorrectionLevelQuartile();
            default:
                return new ErrorCorrectionLevelMedium();
        }
    }

    /**
     * Get round block size mode
     */
    protected function getRoundBlockSizeMode(string $mode): object
    {
        switch (strtolower($mode)) {
            case 'margin':
                return new RoundBlockSizeModeMargin();
            case 'enlarge':
                return new RoundBlockSizeModeEnlarge();
            case 'shrink':
                return new RoundBlockSizeModeShrink();
            default:
                return new RoundBlockSizeModeMargin();
        }
    }

    /**
     * Get supported error correction levels
     */
    public function getSupportedErrorCorrectionLevels(): array
    {
        return [
            'low' => 'Low (7%)',
            'medium' => 'Medium (15%)',
            'high' => 'High (25%)',
            'quartile' => 'Quartile (30%)'
        ];
    }

    /**
     * Get supported round block size modes
     */
    public function getSupportedRoundBlockSizeModes(): array
    {
        return [
            'margin' => 'Margin',
            'enlarge' => 'Enlarge',
            'shrink' => 'Shrink'
        ];
    }

    /**
     * Validate QR code data
     */
    public function isValidData(string $data): bool
    {
        return !empty($data) && strlen($data) <= 2953; // Maximum capacity for QR code
    }
} 