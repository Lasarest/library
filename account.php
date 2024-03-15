<?php
session_start();
$title = 'Личный кабинет';
$_SESSION['title'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="div_form">
            <?php
            session_start();
            if(isset($_SESSION['email'])) {
                $email_session = $_SESSION['email'];
                $sql = "SELECT * FROM users WHERE email = '$email_session'";
                require_once 'documents/db_connection.php';
                $result = $conn->query($sql);
                $conn->close();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '<main>';
                    echo '<div class="main">';
                    echo '<span class="circle-image">';
                    ?>
                    <img src="images/user_images/<?php echo $row['image']; ?> " alt="<?php echo $row['name']; ?>" onerror="this.src='images/user_images/user_logo.png'">
                    <?php
                    echo '</span>';
                    echo '<h1>Приветствую, ' . $row['name'] . '  <a href="documents/make_exit.php"><img class="little_button" src="images/exit.png" alt="Exit"></a></h1>';
                    echo '<p>Статус акканута: ';
                    if($row['is_admin'] == 1){
                        echo "Администратор";
                    } else {
                        echo "Пользователь";
                    }
                    echo '</p>';
                    echo '<form action="documents/make_upload.php" method="post" enctype="multipart/form-data">';
                    echo '<input type="file" name="fileToUpload" id="fileToUpload">';
                    echo '<input type="submit" value="Изменить иконку профиля" name="submit">';
                    echo '</form>';
                }
            }
            else {
                header("Location: login.php");
                exit();
            }
            ?>
    </div>
</main>
<?php
require_once 'documents/make_footer.php';
?>
</body>
