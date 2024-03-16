<?php
session_start();
$title = $_GET['author'];
$_SESSION['title'] = $title;
require_once "documents/make_head.php";
require_once 'documents/make_menu.php';
require_once 'documents/db_connection.php';

$book_query = "SELECT books.book_name FROM books INNER JOIN authors ON authors.id = books.author_id WHERE authors.author_name = '$title'";
$book_result = mysqli_query($conn, $book_query);

$is_admin = $_SESSION['is_admin'];
?>

<main style="display: flex; justify-content: space-between;">

    <div class="aside" style="width: 20%;">
        <h3>Книги автора:</h3>
        <?php
        if (mysqli_num_rows($book_result) > 0) {
            while($row = mysqli_fetch_assoc($book_result)) {
                echo "<p><a class='link' href='book_page.php?book=".$row['book_name']."'>".$row['book_name']."</a></p>";
            }
        } else{
            echo "<p>Пусто!</p>";
        }
        ?>


        <?php if ($is_admin == 1): ?>
            <div class="button-container">
                <a class="link" href=""><button class="green-button">Изменить контент</button></a>
            </div>
        <?php endif; ?>
    </div>

    <div class="content" style="width: 70%;">
        <?php
        $author_query = "SELECT author_img, author_description  FROM authors WHERE author_name = '$title'";
        $author_result = mysqli_query($conn, $author_query);
        $author_data = mysqli_fetch_assoc($author_result);

        echo '<h3>'.$title.'</h3>';
        echo '<img src="images/book_images/'.$author_data['author_img'].'" alt="'.$author_data['author_img'].'">';
        echo '<p>'.$author_data['author_description'].'</p>';
        ?>
    </div>
</main>
<?php require_once 'documents/make_footer.php'; ?>
