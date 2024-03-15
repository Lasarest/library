<?php
session_start();
$title = $_SESSION['title'];
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="stylesheet" href="style.css"/>';
echo '<link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">';
echo '<title>' . $title . '</title>';
echo '</head>';










