<?php
include('../config/Database.php');
$headers = getallheaders();
$email = isset($headers['authorization']) ? $headers['authorization'] : '';
$method = $_SERVER['REQUEST_METHOD'];

if ($method != "POST") {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

try {
    if (empty($email)) {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }

    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if (empty($id)) {
        http_response_code(400);
        throw new Exception('Invalid request: ID is required');
    }

    $email = $mysqli->real_escape_string($email);
    $comicId = $mysqli->real_escape_string($id);

    $selectSql = "SELECT image_id FROM comics WHERE id = '$comicId' AND email = '$email'";
    $comicResult = $mysqli->query($selectSql);
    if ($comicResult && $comicResult->num_rows > 0) {
        $comic = $comicResult->fetch_assoc();
        $imageId = $comic['image_id'];

        $fileName = $imageId . ".jpeg";
        $directory = "../images/";
        $targetFilePath = $directory . $fileName;

        if (file_exists($targetFilePath)) {
            unlink($targetFilePath);
        }

        $deleteSql = "DELETE FROM comics WHERE id = '$comicId' AND email = '$email'";
        if ($mysqli->query($deleteSql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "comic record deleted successfully"]);
        } else {
            http_response_code(500);
            throw new Exception("Error: " . $deleteSql . "<br>" . $mysqli->error);
        }
    } else {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Forbidden: You do not have permission to delete this comic record']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$mysqli->close();