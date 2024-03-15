<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "library";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Соединение не удалось: " . mysqli_connect_error());
}
