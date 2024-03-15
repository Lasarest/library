<?php
session_start();
$title = 'Регистрация:';
$_SESSION['title'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="div_form">
        <h2>Регистрация:</h2>
        <form method="post">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username"><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password"><br>
            <label for="confirm_password">Подтвердите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password"><br>
            <?php
            if (!isset($_SESSION['email'])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username_form = $_POST['username'];
                    $email_form = $_POST['email'];
                    $password_form = $_POST['password'];
                    $confirm_password_form = $_POST['confirm_password'];

                    if (!empty($username_form) && !empty($email_form) && !empty($password_form) && !empty($confirm_password_form)) {
                        if ($password_form == $confirm_password_form) {
                            require_once 'documents/db_connection.php';
                            $check_email_query = "SELECT * FROM users WHERE email='$email_form'";
                            $result = $conn->query($check_email_query);
                            if ($result->num_rows > 0) {
                                echo "<h4>Этот электронный адрес уже зарегистрирован</h4>";
                            } else {
                                $hashedPassword = password_hash($password_form, PASSWORD_DEFAULT);
                                $sql = "INSERT INTO users (name, email, password, is_admin, image) VALUES ('$username_form','$email_form','$hashedPassword','0','user_logo.png')";
                                if ($conn->query($sql) === true) {
                                    header("Location: login.php");
                                    exit();
                                }
                            }
                            $conn->close();
                        } else {
                            echo "<h4>Пароли не совпадают</h4>";
                        }
                    } else {
                        echo "<h4>Пожалуйста, заполните все поля</h4>";
                    }
                }
            } else {
                header("Location: account.php");
                exit();
            }
            ?>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </div>
</main>
<?php
require_once 'documents/make_footer.php';
?>
</body>
