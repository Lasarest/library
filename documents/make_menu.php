<?php
echo '<header id="menu">';
echo '<nav>';
echo '<div class="logo"><a href="../index.php">Библиотечный каталог</a></div>';
echo '<ul class="menu">';
echo '<li><a href="../authors.php">Авторы</a></li>';
echo '<li><a href="../genres.php">Жанры</a></li>';
session_start();
if(isset($_SESSION['email'])) {
    echo '<li><a href="account.php">Привет, ' . $_SESSION['name'] . '</li></a>';
} else {
    echo '<li><a href="../login.php">Войти</a></li>';
    echo '<li><a href="../register.php">Регистрация</a></li>';
}
echo '</ul>';
echo '<div class="toggle-button">&#9776;</div>';
echo '</nav>';
echo '</header>';
echo '<script>';
echo 'document.querySelector(".toggle-button").addEventListener("click", function () {';
echo 'let menu = document.querySelector(".menu");';
echo 'menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "flex" : "none";';
echo '});';
echo '</script>';
