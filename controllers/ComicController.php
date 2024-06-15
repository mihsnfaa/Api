<?php
include_once '../models/Comic.php';

class ComicController {
    private $db;
    public $comic;

    public function __construct(){
        $this->db = include('../config/Database.php');
        $this->comic = new Comic($this->db);
    }

    public function read($email){
        $this->comic->email = $email;
        $result = $this->comic->read();
        $comic = array();
        while ($row = $result->fetch_assoc()){
            $comic[] = $row;
        }
        return $comic;
    }

    public function create($data, $email){
        $this->comic->email = $email;
        $this->comic->title = $data['title'];
        $this->comic->genre = $data['genre'];

        if (isset($_FILES['image'])) {
            $imageId = $this->comic->generateUniqueImageId();
            $fileName = $imageId . ".jpeg";
            $directory = "../images/";
                        
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $targetFilePath = $directory . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $this->comic->imageId = $imageId;
                if($this->comic->create()){
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public function delete($id, $email){
        $this->comic->id = $id;
        $this->comic->email = $email; 

        if($this->comic->delete()){
            return true;
        }
        return false;
    }        
}
?>

