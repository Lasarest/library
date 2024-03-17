<?php
$servername = "ilyaosfd.beget.tech";
$username = "ilyaosfd_library";
$password = "ItI5wwEWDALg";
$database = "ilyaosfd_library";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Соединение не удалось: " . mysqli_connect_error());
}
