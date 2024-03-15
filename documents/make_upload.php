<?php
$targetDirectory = "../images/user_images/";
$fileName = basename($_FILES["fileToUpload"]["name"]);
$targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
}

if ($_FILES["fileToUpload"]["size"] > 500000) {
    $uploadOk = 0;
}

$allowedExtensions = array("jpg", "jpeg", "png", "gif");
$fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
if(!in_array($fileExtension, $allowedExtensions)) {
    $uploadOk = 0;
}
if ($uploadOk != 0) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        session_start();
        $email_session = $_SESSION['email'];
        require_once 'db_connection.php';
        $sql = "UPDATE users SET image = '$fileName' WHERE email = '$email_session';";
        $result = $conn->query($sql);
        $sql = "SELECT * FROM users WHERE email = '$email_session'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $_SESSION['image'] = $row['image'];
        header("Location: ../account.php");
    }
}
