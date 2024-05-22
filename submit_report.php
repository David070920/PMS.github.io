<?php

$host = "localhost";
$username = "root";
$password = "root";
$dbname = "pest_map";

$conn = new mysqli($host, $username, $password, $dbname);

if($conn->connect_error){
    die("connection failed:". $conn->connect_error);
}

$lat = $_POST['latitude'];
$lng = $_POST['longitude'];
$pest = $_POST['pest'];
$date = $_POST['lastSpotted'];

$sql = "INSERT INTO pest_tbl(latitude, longitude, pest, date) VALUES('$lat', '$lng', '$pest', '$date')";

if($conn->query($sql)==TRUE){
    echo "Form submitted";
}else{
    echo "Error";
}
?>