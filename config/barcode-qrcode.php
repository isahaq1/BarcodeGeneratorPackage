<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Barcode Configuration
    |--------------------------------------------------------------------------
    |
    | Default settings for barcode generation
    |
    */
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
        'supported_types' => [
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
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Configuration
    |--------------------------------------------------------------------------
    |
    | Default settings for QR code generation
    |
    */
    'qrcode' => [
        'default_options' => [
            'size' => 300,
            'margin' => 10,
            'foreground_color' => [0, 0, 0],
            'background_color' => [255, 255, 255],
            'error_correction_level' => 'medium',
            'round_block_size_mode' => 'margin',
            'logo_path' => null,
            'logo_size' => 100,
            'logo_position' => 'center'
        ],
        'error_correction_levels' => [
            'low' => 'Low (7%)',
            'medium' => 'Medium (15%)',
            'high' => 'High (25%)',
            'quartile' => 'Quartile (30%)'
        ],
        'round_block_size_modes' => [
            'margin' => 'Margin',
            'enlarge' => 'Enlarge',
            'shrink' => 'Shrink'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Output Configuration
    |--------------------------------------------------------------------------
    |
    | Default output settings
    |
    */
    'output' => [
        'default_format' => 'PNG',
        'supported_formats' => ['PNG', 'SVG', 'HTML', 'JPG', 'PDF'],
        'storage_path' => storage_path('app/barcodes'),
        'public_path' => public_path('barcodes'),
        'url_prefix' => '/barcodes'
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache settings for generated barcodes and QR codes
    |
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
        'prefix' => 'barcode_qrcode_'
    ]
]; 