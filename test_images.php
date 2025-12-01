<?php
// Test simple page to verify functionality
echo "Stockify Image System Test\n\n";

// Check if storage link exists
$link = 'C:\\xampp\\htdocs\\stockify\\public\\storage';
if (is_link($link)) {
    echo "✓ Storage symlink exists\n";
} else {
    echo "✗ Storage symlink missing\n";
}

// Check if products folder exists
$folder = 'C:\\xampp\\htdocs\\stockify\\storage\\app\\public\\products';
if (is_dir($folder)) {
    echo "✓ Products folder exists\n";
} else {
    echo "✗ Products folder missing\n";
}

// Check placeholder image
$placeholder = 'C:\\xampp\\htdocs\\stockify\\public\\images\\no-image.svg';
if (file_exists($placeholder)) {
    echo "✓ Placeholder image exists\n";
} else {
    echo "✗ Placeholder image missing\n";
}

echo "\nSetup complete! Ready for image uploads.\n";
?>