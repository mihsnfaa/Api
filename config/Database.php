<?php

$host = "localhost";
$dbname = "id22322151_comicdb";
$username = "id22322151_mihsnfaa";
$password = "!hsaN12123.";

$mysqli = new mysqli(
    $host,
    $username,
    $password,
    $dbname,
);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;