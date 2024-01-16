<?php
header('Content-Type: application/json');

$clientId = '6db47bd7029562d';
$apiEndpoint = 'https://api.imgur.com/3/image';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);

        $headers = [
            'Authorization: Client-ID ' . $clientId,
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $postData = ['image' => base64_encode($imageData)];

        $options = [
            'http' => [
                'header' => implode("\r\n", $headers),
                'method' => 'POST',
                'content' => http_build_query($postData),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($apiEndpoint, false, $context);

        if ($result !== false) {
            $resultData = json_decode($result, true);
            if (isset($resultData['data']['id'])) {
                echo json_encode(['success' => true, 'imageId' => $resultData['data']['id']]);
                exit;
            }
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Error uploading image']);
?>
