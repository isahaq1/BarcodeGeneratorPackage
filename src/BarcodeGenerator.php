<?php

namespace Isahaq\BarcodeQrCode;

use TCPDF;
use Picqer\Barcode\BarcodeGenerator as PicqerBarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Exception;

class BarcodeGenerator
{
    protected $pdf;
    protected $picqerGenerator;
    protected $defaultOptions;

    public function __construct()
    {
        $this->pdf = new TCPDF();
        $this->picqerGenerator = new BarcodeGeneratorPNG();
        $this->defaultOptions = [
            'width' => 2,
            'height' => 30,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'text' => true,
            'text_size' => 12,
            'text_position' => 'bottom',
            'padding' => 10
        ];
    }

    /**
     * Generate barcode in PNG format
     */
    public function generatePNG(string $type, string $code, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        try {
            $generator = new BarcodeGeneratorPNG();
            return $generator->getBarcode(
                $code,
                $this->getPicqerType($type),
                $options['width'],
                $options['height'],
                $options['foreground_color']
            );
        } catch (Exception $e) {
            throw new Exception("Failed to generate PNG barcode: " . $e->getMessage());
        }
    }

    /**
     * Generate barcode in SVG format
     */
    public function generateSVG(string $type, string $code, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        try {
            $generator = new BarcodeGeneratorSVG();
            return $generator->getBarcode(
                $code,
                $this->getPicqerType($type),
                $options['width'],
                $options['height'],
                $options['foreground_color']
            );
        } catch (Exception $e) {
            throw new Exception("Failed to generate SVG barcode: " . $e->getMessage());
        }
    }

    /**
     * Generate barcode in HTML format
     */
    public function generateHTML(string $type, string $code, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        try {
            $generator = new BarcodeGeneratorHTML();
            return $generator->getBarcode(
                $code,
                $this->getPicqerType($type),
                $options['width'],
                $options['height'],
                $options['foreground_color']
            );
        } catch (Exception $e) {
            throw new Exception("Failed to generate HTML barcode: " . $e->getMessage());
        }
    }

    /**
     * Generate barcode in JPG format
     */
    public function generateJPG(string $type, string $code, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        try {
            $generator = new BarcodeGeneratorJPG();
            return $generator->getBarcode(
                $code,
                $this->getPicqerType($type),
                $options['width'],
                $options['height'],
                $options['foreground_color']
            );
        } catch (Exception $e) {
            throw new Exception("Failed to generate JPG barcode: " . $e->getMessage());
        }
    }

    /**
     * Generate barcode using TCPDF (for PDF generation)
     */
    public function generatePDF(string $type, string $code, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        
        try {
            $this->pdf->AddPage();
            
            $styles = [
                'border' => false,
                'padding' => $options['padding'],
                'fgcolor' => $options['foreground_color'],
                'bgcolor' => $options['background_color'],
                'module_width' => $options['width'],
                'module_height' => $options['height']
            ];

            $this->pdf->write1DBarcode($code, $this->getTCPDFType($type), '', '', '', 18, 0.4, $styles, 'S');
            
            return $this->pdf->Output('', 'S');
        } catch (Exception $e) {
            throw new Exception("Failed to generate PDF barcode: " . $e->getMessage());
        }
    }

    /**
     * Save barcode to file
     */
    public function save(string $type, string $code, string $filepath, string $format = 'PNG', array $options = []): bool
    {
        try {
            switch (strtoupper($format)) {
                case 'PNG':
                    $content = $this->generatePNG($type, $code, $options);
                    break;
                case 'SVG':
                    $content = $this->generateSVG($type, $code, $options);
                    break;
                case 'HTML':
                    $content = $this->generateHTML($type, $code, $options);
                    break;
                case 'JPG':
                    $content = $this->generateJPG($type, $code, $options);
                    break;
                case 'PDF':
                    $content = $this->generatePDF($type, $code, $options);
                    break;
                default:
                    throw new Exception("Unsupported format: {$format}");
            }

            return file_put_contents($filepath, $content) !== false;
        } catch (Exception $e) {
            throw new Exception("Failed to save barcode: " . $e->getMessage());
        }
    }

    /**
     * Get supported barcode types
     */
    public function getSupportedTypes(): array
    {
        return [
            'C39' => 'Code 39',
            'C39+' => 'Code 39+',
            'C39E' => 'Code 39 Extended',
            'C39E+' => 'Code 39 Extended+',
            'C93' => 'Code 93',
            'S25' => 'Standard 2 of 5',
            'S25+' => 'Standard 2 of 5+',
            'I25' => 'Interleaved 2 of 5',
            'I25+' => 'Interleaved 2 of 5+',
            'C128' => 'Code 128',
            'C128A' => 'Code 128 A',
            'C128B' => 'Code 128 B',
            'C128C' => 'Code 128 C',
            'EAN2' => 'EAN 2',
            'EAN5' => 'EAN 5',
            'EAN8' => 'EAN 8',
            'EAN13' => 'EAN 13',
            'UPCA' => 'UPC-A',
            'UPCE' => 'UPC-E',
            'MSI' => 'MSI',
            'MSI+' => 'MSI+',
            'POSTNET' => 'POSTNET',
            'PLANET' => 'PLANET',
            'RMS4CC' => 'RMS4CC',
            'KIX' => 'KIX',
            'IMB' => 'IMB',
            'CODABAR' => 'Codabar',
            'CODE11' => 'Code 11',
            'PHARMA' => 'Pharma Code',
            'PHARMA2T' => 'Pharma Code Two-Track'
        ];
    }

    /**
     * Validate barcode type
     */
    public function isValidType(string $type): bool
    {
        return array_key_exists(strtoupper($type), $this->getSupportedTypes());
    }

    /**
     * Convert type to Picqer format
     */
    protected function getPicqerType(string $type): string
    {
        $typeMap = [
            'C39' => PicqerBarcodeGenerator::TYPE_CODE_39,
            'C39+' => PicqerBarcodeGenerator::TYPE_CODE_39_CHECKSUM,
            'C39E' => PicqerBarcodeGenerator::TYPE_CODE_39E,
            'C39E+' => PicqerBarcodeGenerator::TYPE_CODE_39E_CHECKSUM,
            'C93' => PicqerBarcodeGenerator::TYPE_CODE_93,
            'S25' => PicqerBarcodeGenerator::TYPE_STANDARD_2_5,
            'S25+' => PicqerBarcodeGenerator::TYPE_STANDARD_2_5_CHECKSUM,
            'I25' => PicqerBarcodeGenerator::TYPE_INTERLEAVED_2_5,
            'I25+' => PicqerBarcodeGenerator::TYPE_INTERLEAVED_2_5_CHECKSUM,
            'C128' => PicqerBarcodeGenerator::TYPE_CODE_128,
            'C128A' => PicqerBarcodeGenerator::TYPE_CODE_128_A,
            'C128B' => PicqerBarcodeGenerator::TYPE_CODE_128_B,
            'C128C' => PicqerBarcodeGenerator::TYPE_CODE_128_C,
            'EAN2' => PicqerBarcodeGenerator::TYPE_EAN_2,
            'EAN5' => PicqerBarcodeGenerator::TYPE_EAN_5,
            'EAN8' => PicqerBarcodeGenerator::TYPE_EAN_8,
            'EAN13' => PicqerBarcodeGenerator::TYPE_EAN_13,
            'UPCA' => PicqerBarcodeGenerator::TYPE_UPC_A,
            'UPCE' => PicqerBarcodeGenerator::TYPE_UPC_E,
            'MSI' => PicqerBarcodeGenerator::TYPE_MSI,
            'MSI+' => PicqerBarcodeGenerator::TYPE_MSI_CHECKSUM,
            'POSTNET' => PicqerBarcodeGenerator::TYPE_POSTNET,
            'PLANET' => PicqerBarcodeGenerator::TYPE_PLANET,
            'RMS4CC' => PicqerBarcodeGenerator::TYPE_RMS4CC,
            'KIX' => PicqerBarcodeGenerator::TYPE_KIX,
            'IMB' => PicqerBarcodeGenerator::TYPE_IMB,
            'CODABAR' => PicqerBarcodeGenerator::TYPE_CODABAR,
            'CODE11' => PicqerBarcodeGenerator::TYPE_CODE_11,
            'PHARMA' => PicqerBarcodeGenerator::TYPE_PHARMA_CODE,
            'PHARMA2T' => PicqerBarcodeGenerator::TYPE_PHARMA_CODE_TWO_TRACKS
        ];

        $type = strtoupper($type);
        if (!isset($typeMap[$type])) {
            throw new Exception("Unsupported barcode type: {$type}");
        }

        return $typeMap[$type];
    }

    /**
     * Convert type to TCPDF format
     */
    protected function getTCPDFType(string $type): string
    {
        return strtoupper($type);
    }
}