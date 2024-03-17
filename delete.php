<?php
session_start();
$is_admin = $_SESSION['is_admin'];
if ($is_admin == 1) {
    $delete_name = $_GET['delete'];
    require_once 'documents/db_connection.php';
    if ($delete_name === 'author') {
        $name = $_GET['author'];
        $sql = "DELETE FROM authors WHERE author_name = '$name'";
    } elseif ($delete_name === 'genre') {
        $name = $_GET['genre'];
        $sql = "DELETE FROM genres WHERE genre_name = '$name'";
    } elseif ($delete_name === 'book') {
        $name = $_GET['book'];
        $sql = "DELETE FROM books WHERE book_name = '$name'";
    }
    if ($conn->query($sql) === true) {
        if ($delete_name == 'author') {
            header("Location: authors.php");
        } elseif ($delete_name == 'genre') {
            header("Location: genres.php");
        } elseif ($delete_name == 'book') {
            header("Location: index.php");
        }
    } else {
        header("Location: error.php");
    }
    $conn->close();
}else{
    header("Location: access_denied.php");
}