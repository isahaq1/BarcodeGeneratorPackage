<?php

namespace Isahaq\BarcodeQrCode\Tests;

use PHPUnit\Framework\TestCase;
use Isahaq\BarcodeQrCode\QRCodeGenerator;
use Exception;

class QRCodeGeneratorTest extends TestCase
{
    protected QRCodeGenerator $qrCodeGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->qrCodeGenerator = new QRCodeGenerator();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(QRCodeGenerator::class, $this->qrCodeGenerator);
    }

    public function testGeneratePNG()
    {
        $result = $this->qrCodeGenerator->generatePNG('https://example.com');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith("\x89PNG", $result); // PNG file signature
    }

    public function testGenerateSVG()
    {
        $result = $this->qrCodeGenerator->generateSVG('https://example.com');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringContainsString('<svg', $result);
    }

    public function testGenerateHTML()
    {
        $result = $this->qrCodeGenerator->generateHTML('https://example.com');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringContainsString('<div', $result);
    }

    public function testGenerateJPG()
    {
        $result = $this->qrCodeGenerator->generateJPG('https://example.com');
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith("\xFF\xD8\xFF", $result); // JPEG file signature
    }

    public function testSaveToFile()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'qrcode_test_');
        
        try {
            $result = $this->qrCodeGenerator->save('https://example.com', $tempFile, 'PNG');
            
            $this->assertTrue($result);
            $this->assertFileExists($tempFile);
            $this->assertGreaterThan(0, filesize($tempFile));
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    public function testGetSupportedErrorCorrectionLevels()
    {
        $levels = $this->qrCodeGenerator->getSupportedErrorCorrectionLevels();
        
        $this->assertIsArray($levels);
        $this->assertNotEmpty($levels);
        $this->assertArrayHasKey('low', $levels);
        $this->assertArrayHasKey('medium', $levels);
        $this->assertArrayHasKey('high', $levels);
        $this->assertArrayHasKey('quartile', $levels);
    }

    public function testGetSupportedRoundBlockSizeModes()
    {
        $modes = $this->qrCodeGenerator->getSupportedRoundBlockSizeModes();
        
        $this->assertIsArray($modes);
        $this->assertNotEmpty($modes);
        $this->assertArrayHasKey('margin', $modes);
        $this->assertArrayHasKey('enlarge', $modes);
        $this->assertArrayHasKey('shrink', $modes);
    }

    public function testIsValidData()
    {
        $this->assertTrue($this->qrCodeGenerator->isValidData('https://example.com'));
        $this->assertTrue($this->qrCodeGenerator->isValidData('123456789'));
        $this->assertTrue($this->qrCodeGenerator->isValidData('Hello World'));
        $this->assertFalse($this->qrCodeGenerator->isValidData(''));
        $this->assertFalse($this->qrCodeGenerator->isValidData(str_repeat('A', 3000))); // Too long
    }

    public function testGenerateWithCustomOptions()
    {
        $options = [
            'size' => 400,
            'margin' => 20,
            'foreground_color' => [255, 0, 0], // Red
            'background_color' => [255, 255, 255], // White
            'error_correction_level' => 'high',
            'round_block_size_mode' => 'enlarge'
        ];

        $result = $this->qrCodeGenerator->generatePNG('https://example.com', $options);
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testGenerateWithDifferentErrorCorrectionLevels()
    {
        $levels = ['low', 'medium', 'high', 'quartile'];
        $testData = 'https://example.com';

        foreach ($levels as $level) {
            $options = ['error_correction_level' => $level];
            $result = $this->qrCodeGenerator->generatePNG($testData, $options);
            
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }

    public function testGenerateWithDifferentRoundBlockSizeModes()
    {
        $modes = ['margin', 'enlarge', 'shrink'];
        $testData = 'https://example.com';

        foreach ($modes as $mode) {
            $options = ['round_block_size_mode' => $mode];
            $result = $this->qrCodeGenerator->generatePNG($testData, $options);
            
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }

    public function testGenerateWithInvalidFormat()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unsupported format: INVALID_FORMAT');
        
        $this->qrCodeGenerator->save('https://example.com', 'test.txt', 'INVALID_FORMAT');
    }

    public function testSaveMultipleFormats()
    {
        $tempDir = sys_get_temp_dir();
        $formats = ['PNG', 'SVG', 'HTML', 'JPG'];

        foreach ($formats as $format) {
            $tempFile = $tempDir . '/qrcode_test_' . $format . '.' . strtolower($format);
            
            try {
                $result = $this->qrCodeGenerator->save('https://example.com', $tempFile, $format);
                
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

    public function testGenerateWithEmptyData()
    {
        $this->expectException(Exception::class);
        $this->qrCodeGenerator->generatePNG('');
    }

    public function testGenerateWithDifferentDataTypes()
    {
        $testData = [
            'https://example.com',
            '123456789',
            'Hello World',
            'mailto:test@example.com',
            'tel:+1234567890',
            'geo:40.7128,-74.0060',
            'BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nEND:VCARD'
        ];

        foreach ($testData as $data) {
            $result = $this->qrCodeGenerator->generatePNG($data);
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }

    public function testGenerateWithSpecialCharacters()
    {
        $specialData = 'https://example.com/path?param=value&another=test#fragment';
        $result = $this->qrCodeGenerator->generatePNG($specialData);
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testGenerateWithUnicodeCharacters()
    {
        $unicodeData = 'https://example.com/测试/unicode/characters';
        $result = $this->qrCodeGenerator->generatePNG($unicodeData);
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testGenerateWithLargeData()
    {
        $largeData = str_repeat('A', 1000);
        $result = $this->qrCodeGenerator->generatePNG($largeData);
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testGenerateWithDifferentSizes()
    {
        $sizes = [100, 200, 300, 400, 500];
        $testData = 'https://example.com';

        foreach ($sizes as $size) {
            $options = ['size' => $size];
            $result = $this->qrCodeGenerator->generatePNG($testData, $options);
            
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }

    public function testGenerateWithDifferentMargins()
    {
        $margins = [0, 5, 10, 20, 50];
        $testData = 'https://example.com';

        foreach ($margins as $margin) {
            $options = ['margin' => $margin];
            $result = $this->qrCodeGenerator->generatePNG($testData, $options);
            
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }
} 