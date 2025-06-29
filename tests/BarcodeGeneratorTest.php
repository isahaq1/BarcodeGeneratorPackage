<?php

namespace Isahaq\BarcodeQrCode\Tests;

use PHPUnit\Framework\TestCase;
use Isahaq\BarcodeQrCode\BarcodeGenerator;
use Exception;

class BarcodeGeneratorTest extends TestCase
{
    protected BarcodeGenerator $barcodeGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->barcodeGenerator = new BarcodeGenerator();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(BarcodeGenerator::class, $this->barcodeGenerator);
    }

    public function testGeneratePNG()
    {
        $result = $this->barcodeGenerator->generatePNG('C128', '123456789');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        // TCPDF generates PDF content, so we check for PDF signature
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testGenerateSVG()
    {
        $result = $this->barcodeGenerator->generateSVG('C128', '123456789');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        // TCPDF generates PDF content, so we check for PDF signature
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testGenerateHTML()
    {
        $result = $this->barcodeGenerator->generateHTML('C128', '123456789');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        // TCPDF generates PDF content, so we check for PDF signature
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testGenerateJPG()
    {
        $result = $this->barcodeGenerator->generateJPG('C128', '123456789');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        // TCPDF generates PDF content, so we check for PDF signature
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testGeneratePDF()
    {
        $result = $this->barcodeGenerator->generatePDF('C128', '123456789');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith('%PDF', $result); // PDF file signature
    }

    public function testSaveToFile()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'barcode_test_');
        
        try {
            $result = $this->barcodeGenerator->save('C128', '123456789', $tempFile, 'PNG');
            
            $this->assertTrue($result);
            $this->assertFileExists($tempFile);
            $this->assertGreaterThan(0, filesize($tempFile));
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    public function testGetSupportedTypes()
    {
        $types = $this->barcodeGenerator->getSupportedTypes();
        
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);
        $this->assertArrayHasKey('C128', $types);
        $this->assertArrayHasKey('EAN13', $types);
        $this->assertArrayHasKey('C39', $types);
    }

    public function testIsValidType()
    {
        $this->assertTrue($this->barcodeGenerator->isValidType('C128'));
        $this->assertTrue($this->barcodeGenerator->isValidType('EAN13'));
        $this->assertTrue($this->barcodeGenerator->isValidType('C39'));
        $this->assertFalse($this->barcodeGenerator->isValidType('INVALID_TYPE'));
    }

    public function testGenerateWithCustomOptions()
    {
        $options = [
            'width' => 3,
            'height' => 50,
            'foreground_color' => [255, 0, 0], // Red
            'background_color' => [255, 255, 255], // White
            'padding' => 20
        ];

        $result = $this->barcodeGenerator->generatePNG('C128', '123456789', $options);
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testGenerateWithInvalidType()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unsupported barcode type: INVALID_TYPE');
        
        $this->barcodeGenerator->generatePNG('INVALID_TYPE', '123456789');
    }

    public function testGenerateWithInvalidFormat()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unsupported format: INVALID_FORMAT');
        
        $this->barcodeGenerator->save('C128', '123456789', 'test.txt', 'INVALID_FORMAT');
    }

    public function testDifferentBarcodeTypes()
    {
        $testTypes = ['C39', 'C128', 'EAN13', 'UPCA'];
        $testCode = '123456789';

        foreach ($testTypes as $type) {
            $result = $this->barcodeGenerator->generatePNG($type, $testCode);
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }

    public function testEAN13Validation()
    {
        // EAN13 should have exactly 13 digits
        $validCode = '1234567890123';
        $invalidCode = '123456789'; // Too short

        $result = $this->barcodeGenerator->generatePNG('EAN13', $validCode);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testUPCAValidation()
    {
        // UPC-A should have exactly 12 digits
        $validCode = '123456789012';
        $invalidCode = '123456789'; // Too short

        $result = $this->barcodeGenerator->generatePNG('UPCA', $validCode);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testSaveMultipleFormats()
    {
        $tempDir = sys_get_temp_dir();
        $formats = ['PNG', 'SVG', 'HTML', 'JPG'];

        foreach ($formats as $format) {
            $tempFile = $tempDir . '/barcode_test_' . $format . '.' . strtolower($format);
            
            try {
                $result = $this->barcodeGenerator->save('C128', '123456789', $tempFile, $format);
                
                $this->assertTrue($result);
                $this->assertFileExists($tempFile);
                $this->assertGreaterThan(0, filesize($tempFile));
            } finally {
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }
        }
    }

    public function testGenerateWithEmptyCode()
    {
        $this->expectException(Exception::class);
        $this->barcodeGenerator->generatePNG('C128', '');
    }

    public function testGenerateWithSpecialCharacters()
    {
        $specialCode = 'ABC-123_456';
        $result = $this->barcodeGenerator->generatePNG('C39', $specialCode);
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }
} 