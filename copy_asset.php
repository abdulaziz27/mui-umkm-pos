<?php

$src = '/Users/abdulaziz/.gemini/antigravity/brain/c1235a07-1c79-40b2-8526-050fd716e888/pos_interface_mockup_1780314454932.png';
$destDir = __DIR__ . '/public/images';
$dest = $destDir . '/pos_mockup.png';

if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

if (copy($src, $dest)) {
    echo "SUCCESS: Image copied to $dest\n";
} else {
    echo "ERROR: Failed to copy image\n";
}
unlink(__FILE__); // Self destruct
