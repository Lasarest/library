<?php
session_start();
$title = $_GET['book'];
$_SESSION['title'] = $title;
require_once "documents/make_head.php";
require_once 'documents/make_menu.php';
require_once 'documents/db_connection.php';

$author_query = "SELECT authors.author_name FROM authors INNER JOIN books ON authors.id = books.author_id WHERE books.book_name = '$title'";
$author_result = mysqli_query($conn, $author_query);
$author_name = mysqli_fetch_assoc($author_result)['author_name'];

$genre_query = "SELECT genres.genre_name FROM genres INNER JOIN books ON genres.id = books.genre_id WHERE books.book_name = '$title'";
$genre_result = mysqli_query($conn, $genre_query);
$genre_name = mysqli_fetch_assoc($genre_result)['genre_name'];

$is_admin = $_SESSION['is_admin'];
?>

<main style="display: flex; justify-content: space-between;">

    <div class="aside" style="width: 20%;">
        <h3>Автор книги:</h3>
        <p align='center'><a class='link' href='author_page.php?author=<?= $author_name ?>'><?= $author_name ?></a></p>

        <h3>Жанр:</h3>
        <p align='center'><a class='link' href='genre_page.php?genre=<?= $genre_name ?>'><?= $genre_name ?></a></p>
    </div>

    <div class="content" style="width: 70%;">
        <?php
        $book_query = "SELECT book_img, book_description  FROM books WHERE book_name = '$title'";
        $book_result = mysqli_query($conn, $book_query);
        $book_data = mysqli_fetch_assoc($book_result);

        echo '<h3>'.$title.'</h3>';
        echo '<img src="images/book_images/'.$book_data['book_img'].'" alt="'.$book_data['book_img'].'">';
        echo '<p>'.$book_data['book_description'].'</p>';
        ?>
    </div>
</main>
<?php require_once 'documents/make_footer.php'; ?>
