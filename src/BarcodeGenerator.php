<?php

namespace Isahaq\Barcode;

use TCPDF;

class BarcodeGenerator
{
    protected $pdf;

    public function __construct()
    {
        $this->pdf = new TCPDF();
    }

    public function generate($type, $code, $output = 'I')
    {
        $styles = [
            'border' => false,
            'padding' => 0,
            'fgcolor' => [0, 0, 0],
            'bgcolor' => false,
            'module_width' => 1,
            'module_height' => 1
        ];

        // List of barcode types: C39, C128, EAN13, QR, PDF417, etc.
        return $this->pdf->write1DBarcode($code, $type, '', '', '', 18, 0.4, $styles, $output);
    }

    public function outputPNG($type, $code, $filePath = null)
    {
        $this->pdf->AddPage();
        $this->generate($type, $code);
        $this->pdf->Output($filePath ?? 'barcode.png', 'F');
        return $filePath ?? 'barcode.png';
    }
}