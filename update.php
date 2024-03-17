<?php
session_start();
$title = 'Изменить';
$_SESSION['title'] = $title;
require_once "documents/make_head.php";
require_once 'documents/make_menu.php';

$is_admin = $_SESSION['is_admin'];
$update_name = $_GET['update'];

function extracted(mysqli $conn, $update_name, $update_id)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name_form = $_POST['name'];
        $description_form = $_POST['description'];
        if(!empty($name_form) && !empty($description_form)){
            if($update_name == 'genre'){
                $sql = "UPDATE genres SET genre_name = '$name_form', genre_description = '$description_form' WHERE id = '$update_id'";
                if ($conn->query($sql) === true) {
                    header("Location: genres.php");
                    exit();
                } else {
                    echo "<h4>Ошибка при добавлении жанра: " . $conn->error . "</h4>";
                }
            } elseif ($update_name == 'author' or $update_name == 'book'){
                if(!empty($_FILES["fileToUpload"]["name"])){
                    $targetDirectory = "images/".$update_name."_images/";
                    $fileName = basename($_FILES["fileToUpload"]["name"]);
                    $targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    if ($_FILES["fileToUpload"]["size"] > 500000) {
                        echo "<h4>Ошибка: Файл слишком большой!</h4>";
                        $uploadOk = 0;
                    }
                    if(!in_array(strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)), array("jpg", "jpeg", "png", "gif"))) {
                        echo "<h4>Ошибка: Разрешены только изображения форматов JPG, JPEG, PNG, GIF!</h4>";
                        $uploadOk = 0;
                    }

                    if($uploadOk != 0){
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                            if($update_name == 'author'){
                                $sql = "UPDATE authors SET author_name = '$name_form', author_description = '$description_form', author_img = '$fileName' WHERE id = '$update_id'";
                                if ($conn->query($sql) === true) {
                                    header("Location: authors.php");
                                    exit();
                                } else {
                                    echo "<h4>Ошибка при добавлении автора: " . $conn->error . "</h4>";
                                }
                            }
                            if($update_name == 'book'){
                                $option_genre_form = $_POST['option_genre'];
                                $option_author_form = $_POST['option_author'];
                                if (!empty($option_author_form) && !empty($option_genre_form)){
                                    $sql = "UPDATE books SET book_name = '$name_form', book_description = '$description_form', book_img = '$fileName', genre_id = '$option_genre_form', author_id = '$option_author_form' WHERE id = '$update_id'";
                                    if ($conn->query($sql) === true) {
                                        header("Location: books.php");
                                        exit();
                                    } else {
                                        echo "<h4>Ошибка при добавлении книги: " . $conn->error . "</h4>";
                                    }
                                }
                            }
                        } else {
                            echo "<h4>Ошибка при загрузке файла!</h4>";
                        }
                    }
                } else {
                    echo "<h4>Ошибка: Файл не был загружен!</h4>";
                }
            }
        } else {
            echo "<h4>Ошибка: Заполните все поля формы!</h4>";
        }
    }
}

if ($is_admin == 1) {
    require 'documents/db_connection.php';
    $update_id = $_GET['id'];
    echo '<main>
            <div class="div_form">
                <h2>Изменение</h2>
                <form method="post" id="login-form" enctype="multipart/form-data">';
    if ($update_name == 'author') {
        $author = mysqli_query($conn, "SELECT * FROM authors WHERE id = '$update_id'");
        $result = mysqli_fetch_assoc($author);
        echo '<label>Автор:</label>
              <input type="text" name="name" value="'.$result["author_name"].'"><br>
              <div class="label-textarea">
                  <label for="description">Описание:</label>
                  <textarea name="description" id="description" >'.$result["author_description"].'</textarea>
              </div>
              <input type="file" name="fileToUpload" id="fileToUpload">';
    } elseif ($update_name == 'genre') {
        $genre = mysqli_query($conn, "SELECT * FROM genres WHERE id = '$update_id'");
        $result = mysqli_fetch_assoc($genre);
        echo '<label>Жанр:</label>
              <input type="text" name="name" value="'.$result["genre_name"].'"><br>
              <div class="label-textarea">
                  <label for="description">Описание:</label>
                  <textarea name="description" id="description">'.$result["genre_description"].'</textarea>
              </div>';
    } elseif ($update_name == 'book') {
        $book = mysqli_query($conn, "SELECT * FROM books WHERE id = '$update_id'");
        $result = mysqli_fetch_assoc($book);
        echo '<label>Книга:</label>
              <input type="text" name="name" value="'.$result["book_name"].'"><br>
              <div class="label-textarea">
                  <label for="description">Описание:</label>
                  <textarea name="description" id="description">'.$result["book_description"].'</textarea>
              </div>
              <label>Автор:</label>
              <select name="option_author">';
        $authors_query = "SELECT * FROM authors";
        $authors_result = mysqli_query($conn, $authors_query);
        while ($row = mysqli_fetch_assoc($authors_result)) {
            echo '<option value="' . $row['id'] . '">' . $row['author_name'] . '</option>';
        }
        echo '</select><br><label>Жанр:</label><select name="option_genre">';
        $genres_query = "SELECT * FROM genres";
        $genres_result = mysqli_query($conn, $genres_query);
        while ($row = mysqli_fetch_assoc($genres_result)) {
            echo '<option value="' . $row['id'] . '">' . $row['genre_name'] . '</option>';
        }
        echo '</select><br>';
        echo '<input type="file" name="fileToUpload" id="fileToUpload">';
    }
    extracted($conn, $update_name, $update_id);
    echo '<input type="submit" value="Добавить">
          </form>
        </div>
    </main>';
} else {
    header("Location: access_denied.php");
    exit();
}
