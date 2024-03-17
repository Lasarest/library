<?php
session_start();
$title = $_GET['genre'];
$_SESSION['title'] = $title;
require_once "documents/make_head.php";
require_once 'documents/make_menu.php';
require_once 'documents/db_connection.php';

$book_query = "SELECT books.book_name FROM books INNER JOIN genres ON genres.id = books.genre_id WHERE genres.genre_name = '$title'";
$book_result = mysqli_query($conn, $book_query);

$is_admin = $_SESSION['is_admin'];
?>

<main style="display: flex; justify-content: space-between;">

    <div class="aside" style="width: 20%;">
        <h3>Книги жанра:</h3>
        <?php
        if (mysqli_num_rows($book_result) > 0) {
            while($row = mysqli_fetch_assoc($book_result)) {
                echo "<p align='center'><a class='link' href='book_page.php?book=".$row['book_name']."'>".$row['book_name']."</a></p>";
            }
        } else{
            echo "<p align='center'>Пусто!</p>";
        }
        ?>
    </div>

    <div class="content" style="width: 70%;">
        <?php
        $genre_query = "SELECT genre_description  FROM genres WHERE genre_name = '$title'";
        $genre_result = mysqli_query($conn, $genre_query);
        $genre_data = mysqli_fetch_assoc($genre_result);

        echo '<h3>'.$title.'</h3>';
        echo '<p>'.$genre_data['genre_description'].'</p>';
        ?>
    </div>
</main>
<?php require_once 'documents/make_footer.php'; ?>
