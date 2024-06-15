<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

include_once '../controllers/ComicController.php';

$comicController = new ComicController();

$request_method = $_SERVER["REQUEST_METHOD"];
$headers = getallheaders();

if (!isset($headers['authorization'])) {
    echo json_encode(array("status" => "error", "message" => "Authorization header not provided."));
    exit();
}

$email = $headers['authorization'];

switch($request_method) {
    case 'GET':
        $comic = $comicController->read($email);
        echo json_encode($comic);
        break;

    case 'POST':
        $data = $_POST;
        if($comicController->create($data, $email)){
        echo json_encode(array("status" => "success", "message" => "Comic created successfully."));
        } else {
        echo json_encode(array("status" => "error", "message" => "Unable to create memory."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $id = $data['id'];
            if($comicController->delete($id, $email)){
                echo json_encode(array("status" => "success", "message" => "Comic deleted successfully."));
            } else {
                echo json_encode(array("status" => "error", "message" => "Unable to delete memory."));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "ID not provided."));
        }
        break;

    default:
        echo json_encode(array("status" => "success", "message" => "Comic deleted successfully."));
        break;
}
?>
