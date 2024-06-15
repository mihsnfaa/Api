<?php

if (!isset($_GET['id'])) {
    die("comicImageId is required");
}
$comicImageId = $_GET['id'];

$imageFolder = "../images";
$imagePath = "$imageFolder/$comicImageId.jpeg";

if (file_exists($imagePath)) {
    $imageData = file_get_contents($imagePath);

    header('Content-Type: image/jpeg');
    header('Content-Length: ' . strlen($imageData));

    echo $imageData;
    exit;
} else {
    header("HTTP/1.0 404 Not Found");
    echo json_encode(['status' => 'error', 'message' => 'Image not found']);
    exit;
}

$mysqli->close();