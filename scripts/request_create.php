<?php
$ch = curl_init('http://127.0.0.1:8000/admin/products/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
if ($response === false) {
    echo "CURL ERROR: " . curl_error($ch) . PHP_EOL;
} else {
    $headerSize = $info['header_size'] ?? 0;
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    echo "HTTP_CODE: " . $info['http_code'] . PHP_EOL;
    echo "FINAL_URL: " . ($info['url'] ?? '') . PHP_EOL;
    echo "TOTAL_TIME: " . $info['total_time'] . PHP_EOL;
    echo "CONTENT_LEN: " . strlen($body) . PHP_EOL;
    echo "---RESPONSE PREVIEW (body)---\n";
    echo substr($body, 0, 1200);
}
curl_close($ch);
