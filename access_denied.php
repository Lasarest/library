<?php
session_start();
$title = '403 Error';
$_SESSION['title'] = $title;
require_once "documents/make_head.php"; ?>
<body>
<?php
require_once 'documents/make_menu.php';
?>
<main>
    <div class="div_form">
        <h1>Error 403!</h1>
        <p>Доступ к данной странице запрещён, пожалуйста обратитесь к администратору с этой проблемой!!</p>
    </div>
</main>
<?php
require_once 'documents/make_footer.php';
?>
</body>
