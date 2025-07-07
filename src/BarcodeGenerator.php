<?php

namespace Isahaq\BarcodeQrCode;

use TCPDF;
use Exception;

class BarcodeGenerator
{
    protected $pdf;
    protected $defaultOptions;

    public function __construct()
    {
        $this->pdf = new TCPDF();
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
            // Create a new PDF instance for PNG generation
            $pdf = new TCPDF();
            $pdf->SetCreator('Barcode Generator');
            $pdf->SetAuthor('Isahaq');
            $pdf->SetTitle('Barcode');
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->AddPage();
            
            $styles = [
                'border' => false,
                'padding' => $options['padding'],
                'fgcolor' => $options['foreground_color'],
                'bgcolor' => $options['background_color'],
                'module_width' => $options['width'],
                'module_height' => $options['height'],
                'show_text' => $options['text'],
            ];

            $pdf->write1DBarcode($code, $this->getTCPDFType($type), '', '', '', 18, 0.4, $styles, 'S');
            
            return $pdf->Output('', 'S');
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
            // Create a new PDF instance for SVG generation
            $pdf = new TCPDF();
            $pdf->SetCreator('Barcode Generator');
            $pdf->SetAuthor('Isahaq');
            $pdf->SetTitle('Barcode');
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->AddPage();
            
            $styles = [
                'border' => false,
                'padding' => $options['padding'],
                'fgcolor' => $options['foreground_color'],
                'bgcolor' => $options['background_color'],
                'module_width' => $options['width'],
                'module_height' => $options['height'],
                'show_text' => $options['text'],
            ];

            $pdf->write1DBarcode($code, $this->getTCPDFType($type), '', '', '', 18, 0.4, $styles, 'S');
            
            return $pdf->Output('', 'S');
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
            // Create a new PDF instance for HTML generation
            $pdf = new TCPDF();
            $pdf->SetCreator('Barcode Generator');
            $pdf->SetAuthor('Isahaq');
            $pdf->SetTitle('Barcode');
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->AddPage();
            
            $styles = [
                'border' => false,
                'padding' => $options['padding'],
                'fgcolor' => $options['foreground_color'],
                'bgcolor' => $options['background_color'],
                'module_width' => $options['width'],
                'module_height' => $options['height'],
                'show_text' => $options['text'],
            ];

            $pdf->write1DBarcode($code, $this->getTCPDFType($type), '', '', '', 18, 0.4, $styles, 'S');
            
            return $pdf->Output('', 'S');
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
            // Create a new PDF instance for JPG generation
            $pdf = new TCPDF();
            $pdf->SetCreator('Barcode Generator');
            $pdf->SetAuthor('Isahaq');
            $pdf->SetTitle('Barcode');
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->AddPage();
            
            $styles = [
                'border' => false,
                'padding' => $options['padding'],
                'fgcolor' => $options['foreground_color'],
                'bgcolor' => $options['background_color'],
                'module_width' => $options['width'],
                'module_height' => $options['height'],
                'show_text' => $options['text'],
            ];

            $pdf->write1DBarcode($code, $this->getTCPDFType($type), '', '', '', 18, 0.4, $styles, 'S');
            
            return $pdf->Output('', 'S');
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
            // Create a new PDF instance for PDF generation
            $pdf = new TCPDF();
            $pdf->SetCreator('Barcode Generator');
            $pdf->SetAuthor('Isahaq');
            $pdf->SetTitle('Barcode');
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->AddPage();
            
            $styles = [
                'border' => false,
                'padding' => $options['padding'],
                'fgcolor' => $options['foreground_color'],
                'bgcolor' => $options['background_color'],
                'module_width' => $options['width'],
                'module_height' => $options['height'],
                'show_text' => $options['text'],
            ];

            $pdf->write1DBarcode($code, $this->getTCPDFType($type), '', '', '', 18, 0.4, $styles, 'S');
            
            return $pdf->Output('', 'S');
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
     * Convert type to TCPDF format
     */
    protected function getTCPDFType(string $type): string
    {
        return strtoupper($type);
    }
}