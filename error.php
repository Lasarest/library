<?php
session_start();
$title = 'Библиотечный каталог';
$_SESSION['404 Error'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="div_form">
        <h1>Error 404!</h1>
        <p>Страница или её содержимое не найдено, пожалуйста обратитесь к администратору с этой проблемой!</p>
    </div>
</main>
<?php
require_once 'documents/make_footer.php';
?>
</body>
