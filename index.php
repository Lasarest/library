<?php
session_start();
$title = 'Библиотечный каталог';
$_SESSION['title'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="main">
        <?php
        if (!isset($_SESSION['book_count'])) {
            $_SESSION['book_count'] = 1;
        }
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_size = 10;
        $offset = ($current_page - 1) * $page_size;
        $select_books = "SELECT * FROM books LIMIT $offset, $page_size";
        require_once 'documents/db_connection.php';
        $result = mysqli_query($conn, $select_books);
        $total_books = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM books"));
        $total_pages = ceil($total_books / $page_size);
        $start_count = $offset + 1;
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Номер</th>';
            echo '<th>Название</th>';
            echo '<th>Жанр</th>';
            echo '<th>Автор</th>';
            $is_admin = $_SESSION['is_admin'];
            if ($is_admin == 1) {
                echo "<th>Манипуляции</th>";
            }
            echo '</tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo "<td>". $start_count ."</td>";
                echo "<td><a class='link' href='book_page.php?book=". $row["book_name"] ."'>". $row["book_name"] ."</a></td>";
                $genre_id = $row["genre_id"];
                $genre_query = "SELECT genre_name FROM genres WHERE id = '$genre_id'";
                $genre_result = mysqli_query($conn, $genre_query);
                $genre_name = mysqli_fetch_assoc($genre_result)['genre_name'];
                echo "<td><a class='link' href='genre_page.php?genre=". $genre_name ."'>". $genre_name ."</a></td>";
                $author_id = $row["author_id"];
                $author_query = "SELECT author_name FROM authors WHERE id = '$author_id'";
                $author_result = mysqli_query($conn, $author_query);
                $author_name = mysqli_fetch_assoc($author_result)['author_name'];
                echo "<td><a class='link' href='author_page.php?author=". $author_name ."'>". $author_name ."</a></td>";
                if ($is_admin == 1) {
                    echo "<td><a class='link' href='update.php?update=book&id=".$row["id"]."'><img class='little_button' src='images/update.png' alt='update'>     </a>";
                    echo "<a class='link' href='delete.php?delete=book&book=".$row["book_name"]."'><img class='little_button' src='images/trash.png' alt='delete'>     </a></td>";
                }
                echo '</tr>';
                $start_count++;
            }
            echo '</table>';
            if ($total_pages > 1) {
                echo "<div class='pagination'>";
                if ($current_page > 1) {
                    echo "<a href='?page=" . ($current_page - 1) . "' class='arrow-link'>&#9664; Предыдущая</a>";
                }
                if ($current_page < $total_pages) {
                    echo "<a href='?page=" . ($current_page + 1) . "' class='arrow-link'>Следующая &#9654;</a>";
                }
                echo "</div>";
            }
        } else {
            echo '<h1>Каталог на данный момент пуст!</h1>';
        }
        mysqli_close($conn);
        ?>
    </div>
</main>
<?php
$is_admin = $_SESSION['is_admin'];
if ($is_admin == 1) {
    echo '<div class="button-container">';
    echo '<a class="link" href=""><button class="green-button">Добавить книгу</button></a>';
    echo '</div>';
}
require_once 'documents/make_footer.php';
?>
</body>
