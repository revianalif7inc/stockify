<?php
// Test upload endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    echo "FILES:\n";
    print_r($_FILES);
    echo "\nPOST:\n";
    print_r($_POST);
    echo "</pre>";
} else {
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Test Image Upload</title>
    </head>

    <body>
        <h1>Test Image Upload</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*">
            <button type="submit">Upload</button>
        </form>
    </body>

    </html>
<?php } ?>