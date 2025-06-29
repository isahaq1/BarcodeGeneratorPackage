<?php

// Create banner image
$width = 800;
$height = 200;

// Create image
$image = imagecreate($width, $height);

// Define colors
$bgColor = imagecolorallocate($image, 37, 99, 235); // Blue background
$textColor = imagecolorallocate($image, 255, 255, 255); // White text
$accentColor = imagecolorallocate($image, 59, 130, 246); // Light blue accent

// Fill background with gradient
for ($y = 0; $y < $height; $y++) {
    $ratio = $y / $height;
    $r = 37 + ($ratio * 20);
    $g = 99 + ($ratio * 30);
    $b = 235 + ($ratio * 20);
    $color = imagecolorallocate($image, $r, $g, $b);
    imageline($image, 0, $y, $width, $y, $color);
}

// Add decorative elements
// Top border
imagefilledrectangle($image, 0, 0, $width, 5, $accentColor);

// Bottom border
imagefilledrectangle($image, 0, $height - 5, $width, $height, $accentColor);

// Add main title
$title = "Barcode & QR Code Generator";
$fontSize = 36;
$titleX = ($width - strlen($title) * 20) / 2;
$titleY = 60;
imagestring($image, 5, $titleX, $titleY, $title, $textColor);

// Add subtitle
$subtitle = "Universal Package for Laravel and PHP";
$subtitleX = ($width - strlen($subtitle) * 15) / 2;
$subtitleY = 100;
imagestring($image, 4, $subtitleX, $subtitleY, $subtitle, $textColor);

// Add features
$features = "Multiple Formats • 30+ Barcode Types • QR Codes • Easy Integration";
$featuresX = ($width - strlen($features) * 12) / 2;
$featuresY = 140;
imagestring($image, 3, $featuresX, $featuresY, $features, $textColor);

// Add version info
$version = "v1.0.0";
$versionX = $width - 80;
$versionY = 20;
imagestring($image, 3, $versionX, $versionY, $version, $textColor);

// Save image
imagepng($image, 'banner.png');
imagedestroy($image);

echo "Banner image created successfully: banner.png\n";
?> 