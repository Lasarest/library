<?php
session_start();
$title = 'Авторы';
$_SESSION['title'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="main">
        <?php
        if (!isset($_SESSION['author_count'])) {
            $_SESSION['author_count'] = 1;
        }
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_size = 10;
        $offset = ($current_page - 1) * $page_size;
        $select_authors = "SELECT * FROM authors LIMIT $offset, $page_size";
        require_once 'documents/db_connection.php';
        $result = mysqli_query($conn, $select_authors);
        $total_authors = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM authors"));
        $total_pages = ceil($total_authors / $page_size);
        $start_count = $offset + 1;
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Номер</th>';
            echo '<th>Автор</th>';
            $is_admin = $_SESSION['is_admin'];
            if ($is_admin == 1) {
                echo "<th>Манипуляции</th>";
            }
            echo '</tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo "<td>". $start_count ."</td>";
                echo "<td><a class='link' href='author_page.php?author=". $row["author_name"] ."'>". $row["author_name"] ."</a></td>";
                if ($is_admin == 1) {
                    echo "<td><a class='link' href='update.php?update=author&id=".$row["id"]."'><img class='little_button' src='images/update.png' alt='update'>     </a>";
                    echo "<a class='link' href='delete.php?delete=author&author=".$row["author_name"]."'><img class='little_button' src='images/trash.png' alt='delete'>     </a></td>";
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
            echo '<h1>Список авторов пока пуст!</h1>';
        }
        mysqli_close($conn);
        ?>
    </div>
</main>
<?php
$is_admin = $_SESSION['is_admin'];
if ($is_admin == 1) {
    echo '<div class="button-container">';
    echo '<a class="link" href="add.php?add=author"><button class="green-button">Добавить автора</button></a>';
    echo '</div>';
}
require_once 'documents/make_footer.php';
?>
</body>
