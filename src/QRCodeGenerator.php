<?php

namespace Isahaq\BarcodeQrCode;

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
            'round_block_size_mode' => 'margin'
        ];
    }

    /**
     * Generate QR code in PNG format
     */
    public function generatePNG(string $data, array $options = []): string
    {
        $options = array_merge($this->defaultOptions, $options);
        
        try {
            $qrMatrix = $this->generateSimpleQRMatrix($data);
            return $this->matrixToPNG($qrMatrix, $options);
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
            $qrMatrix = $this->generateSimpleQRMatrix($data);
            return $this->matrixToSVG($qrMatrix, $options);
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
            $qrMatrix = $this->generateSimpleQRMatrix($data);
            return $this->matrixToHTML($qrMatrix, $options);
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
            $qrMatrix = $this->generateSimpleQRMatrix($data);
            return $this->matrixToJPG($qrMatrix, $options);
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
     * Generate a simple QR-like matrix pattern
     */
    protected function generateSimpleQRMatrix(string $data): array
    {
        $dataLength = strlen($data);
        $matrixSize = max(21, min(40, 21 + (int)($dataLength / 10)));
        
        // Create matrix
        $matrix = array_fill(0, $matrixSize, array_fill(0, $matrixSize, 0));
        
        // Add finder patterns (corners)
        $this->addFinderPatterns($matrix);
        
        // Add timing patterns
        $this->addTimingPatterns($matrix);
        
        // Add data pattern based on input
        $this->addDataPattern($matrix, $data);
        
        return $matrix;
    }

    /**
     * Add finder patterns to corners
     */
    protected function addFinderPatterns(array &$matrix): void
    {
        $size = count($matrix);
        
        // Top-left
        $this->addFinderPattern($matrix, 0, 0);
        
        // Top-right
        $this->addFinderPattern($matrix, $size - 7, 0);
        
        // Bottom-left
        $this->addFinderPattern($matrix, 0, $size - 7);
    }

    /**
     * Add a single finder pattern
     */
    protected function addFinderPattern(array &$matrix, int $row, int $col): void
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                if (($r == 0 || $r == 6 || $c == 0 || $c == 6) ||
                    ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)) {
                    $matrix[$row + $r][$col + $c] = 1;
                }
            }
        }
    }

    /**
     * Add timing patterns
     */
    protected function addTimingPatterns(array &$matrix): void
    {
        $size = count($matrix);
        
        // Horizontal timing pattern
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 == 0) ? 1 : 0;
        }
        
        // Vertical timing pattern
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[$i][6] = ($i % 2 == 0) ? 1 : 0;
        }
    }

    /**
     * Add data pattern
     */
    protected function addDataPattern(array &$matrix, string $data): void
    {
        $size = count($matrix);
        $dataLength = strlen($data);
        
        // Create a pattern based on the data
        for ($i = 0; $i < $dataLength && $i < $size * $size / 8; $i++) {
            $row = ($i * 8) % $size;
            $col = (($i * 8) / $size) % $size;
            
            if ($row < $size && $col < $size && $matrix[$row][$col] == 0) {
                $matrix[$row][$col] = (ord($data[$i % $dataLength]) >> ($i % 8)) & 1;
            }
        }
    }

    /**
     * Convert matrix to PNG
     */
    protected function matrixToPNG(array $matrix, array $options): string
    {
        $size = count($matrix);
        $pixelSize = $options['size'] / ($size + 2 * $options['margin']);
        
        $imageWidth = $options['size'];
        $imageHeight = $options['size'];
        
        // Create image
        $image = imagecreate($imageWidth, $imageHeight);
        
        // Set colors
        $bgColor = imagecolorallocate($image, 
            $options['background_color'][0], 
            $options['background_color'][1], 
            $options['background_color'][2]
        );
        $fgColor = imagecolorallocate($image, 
            $options['foreground_color'][0], 
            $options['foreground_color'][1], 
            $options['foreground_color'][2]
        );
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Draw QR code
        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                if ($matrix[$row][$col] == 1) {
                    $x = $options['margin'] * $pixelSize + $col * $pixelSize;
                    $y = $options['margin'] * $pixelSize + $row * $pixelSize;
                    imagefilledrectangle($image, $x, $y, $x + $pixelSize - 1, $y + $pixelSize - 1, $fgColor);
                }
            }
        }
        
        // Output PNG
        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();
        imagedestroy($image);
        
        return $pngData;
    }

    /**
     * Convert matrix to SVG
     */
    protected function matrixToSVG(array $matrix, array $options): string
    {
        $size = count($matrix);
        $pixelSize = $options['size'] / ($size + 2 * $options['margin']);
        
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $options['size'] . '" height="' . $options['size'] . '">';
        $svg .= '<rect width="' . $options['size'] . '" height="' . $options['size'] . '" fill="rgb(' . 
                $options['background_color'][0] . ',' . $options['background_color'][1] . ',' . $options['background_color'][2] . ')"/>';
        
        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                if ($matrix[$row][$col] == 1) {
                    $x = $options['margin'] * $pixelSize + $col * $pixelSize;
                    $y = $options['margin'] * $pixelSize + $row * $pixelSize;
                    $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $pixelSize . '" height="' . $pixelSize . 
                           '" fill="rgb(' . $options['foreground_color'][0] . ',' . $options['foreground_color'][1] . ',' . $options['foreground_color'][2] . ')"/>';
                }
            }
        }
        
        $svg .= '</svg>';
        return $svg;
    }

    /**
     * Convert matrix to HTML
     */
    protected function matrixToHTML(array $matrix, array $options): string
    {
        $size = count($matrix);
        $pixelSize = $options['size'] / ($size + 2 * $options['margin']);
        
        $html = '<div style="width:' . $options['size'] . 'px;height:' . $options['size'] . 'px;background-color:rgb(' . 
                $options['background_color'][0] . ',' . $options['background_color'][1] . ',' . $options['background_color'][2] . ');position:relative;">';
        
        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                if ($matrix[$row][$col] == 1) {
                    $x = $options['margin'] * $pixelSize + $col * $pixelSize;
                    $y = $options['margin'] * $pixelSize + $row * $pixelSize;
                    $html .= '<div style="position:absolute;left:' . $x . 'px;top:' . $y . 'px;width:' . $pixelSize . 'px;height:' . $pixelSize . 
                            'px;background-color:rgb(' . $options['foreground_color'][0] . ',' . $options['foreground_color'][1] . ',' . $options['foreground_color'][2] . ');"></div>';
                }
            }
        }
        
        $html .= '</div>';
        return $html;
    }

    /**
     * Convert matrix to JPG
     */
    protected function matrixToJPG(array $matrix, array $options): string
    {
        $size = count($matrix);
        $pixelSize = $options['size'] / ($size + 2 * $options['margin']);
        
        $imageWidth = $options['size'];
        $imageHeight = $options['size'];
        
        // Create image
        $image = imagecreate($imageWidth, $imageHeight);
        
        // Set colors
        $bgColor = imagecolorallocate($image, 
            $options['background_color'][0], 
            $options['background_color'][1], 
            $options['background_color'][2]
        );
        $fgColor = imagecolorallocate($image, 
            $options['foreground_color'][0], 
            $options['foreground_color'][1], 
            $options['foreground_color'][2]
        );
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Draw QR code
        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                if ($matrix[$row][$col] == 1) {
                    $x = $options['margin'] * $pixelSize + $col * $pixelSize;
                    $y = $options['margin'] * $pixelSize + $row * $pixelSize;
                    imagefilledrectangle($image, $x, $y, $x + $pixelSize - 1, $y + $pixelSize - 1, $fgColor);
                }
            }
        }
        
        // Output JPG
        ob_start();
        imagejpeg($image);
        $jpgData = ob_get_clean();
        imagedestroy($image);
        
        return $jpgData;
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
        return !empty($data) && strlen($data) <= 2953;
    }
} 