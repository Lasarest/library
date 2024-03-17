<?php
session_start();
$title = 'Добавить';
$_SESSION['title'] = $title;
require_once "documents/make_head.php";
require_once 'documents/make_menu.php';

$is_admin = $_SESSION['is_admin'];
$add_name = $_GET['add'];

function extracted(mysqli $conn, $add_name)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name_form = $_POST['name'];
        $description_form = $_POST['description'];
        if(!empty($name_form) && !empty($description_form)){
            if($add_name == 'genre'){
                $sql = "INSERT INTO genres (genre_name, genre_description) VALUES ('$name_form', '$description_form')";
                if ($conn->query($sql) === true) {
                    echo "<h4>Жанр успешно добавлен!</h4>";
                } else {
                    echo "<h4>Ошибка при добавлении жанра: " . $conn->error . "</h4>";
                }
            } elseif ($add_name == 'author' or $add_name == 'book'){
                if(!empty($_FILES["fileToUpload"]["name"])){
                    $targetDirectory = "images/".$add_name."_images/";
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
                            if($add_name == 'author'){
                                $sql = "INSERT INTO authors (author_name, author_img ,author_description) VALUES ('$name_form', '$fileName' ,'$description_form')";
                                if ($conn->query($sql) === true) {
                                    echo "<h4>Автор успешно добавлен!</h4>";
                                } else {
                                    echo "<h4>Ошибка при добавлении автора: " . $conn->error . "</h4>";
                                }
                            }
                            if($add_name == 'book'){
                                $option_genre_form = $_POST['option_genre'];
                                $option_author_form = $_POST['option_author'];
                                if (!empty($option_author_form) && !empty($option_genre_form)){
                                    $sql = "INSERT INTO books (book_name, book_img ,book_description, genre_id, author_id) VALUES ('$name_form', '$fileName' ,'$description_form', '$option_genre_form', '$option_author_form')";
                                    if ($conn->query($sql) === true) {
                                        echo "<h4>Книга успешно добавлена!</h4>";
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
    echo '<main>
            <div class="div_form">
                <h2>Добавление</h2>
                <form method="post" id="login-form" enctype="multipart/form-data">';
    if ($add_name == 'author') {
        echo '<label>Автор:</label>
              <input type="text" name="name"><br>
              <div class="label-textarea">
                  <label for="description">Описание:</label>
                  <textarea name="description" id="description"></textarea>
              </div>
              <input type="file" name="fileToUpload" id="fileToUpload">';
    } elseif ($add_name == 'genre') {
        echo '<label>Жанр:</label>
              <input type="text" name="name"><br>
              <div class="label-textarea">
                  <label for="description">Описание:</label>
                  <textarea name="description" id="description"></textarea>
              </div>';
    } elseif ($add_name == 'book') {
        echo '<label>Книга:</label>
              <input type="text" name="name"><br>
              <div class="label-textarea">
                  <label for="description">Описание:</label>
                  <textarea name="description" id="description"></textarea>
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
    extracted($conn, $add_name);
    echo '<input type="submit" value="Добавить">
          </form>
        </div>
    </main>';
} else {
    header("Location: access_denied.php");
    exit();
}
