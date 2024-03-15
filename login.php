<?php
session_start();
$title = 'Авторизация';
$_SESSION['title'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="div_form">
        <h2>Авторизация:</h2>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password"><br>
            <?php
            session_start();
            if (!isset($_SESSION['email'])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $email_form = $_POST['email'];
                    $password_form = $_POST['password'];
                    if (!empty($email_form) && !empty($password_form)) {
                        require_once 'documents/db_connection.php';
                        $sql = "SELECT * FROM users WHERE email = '$email_form'";
                        $result = $conn->query($sql);
                        $conn->close();
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $hashedPassword = $row['password'];
                            if (password_verify($password_form, $hashedPassword)) {
                                $_SESSION['email'] = $email_form;
                                $_SESSION['name'] = $row['name'];
                                $_SESSION['image'] = $row['image'];
                                $_SESSION['is_admin'] = $row['is_admin'];
                                header("Location: account.php");
                                exit();
                            } else {
                                echo "<h4>Неверный логин или пароль</h4>";
                            }
                        } else {
                            echo "<h4>Неверный логин или пароль</h4>";
                        }
                    } else{
                        echo "<h4>Пожалуйста, заполните все поля</h4>";
                    }
                }
            } else {
                header("Location: account.php");
                exit();
            }
            ?>
            <input type="submit" value="Войти">
        </form>
    </div>
</main>
<?php
require_once 'documents/make_footer.php';
?>
</body>
