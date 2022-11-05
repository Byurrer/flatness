<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/static/styles.css" rel="stylesheet" type="text/css">
    <title><?= $name; ?> | Демо</title>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-main">
                <div class="header-logo"><a href="/">Главная Demo</a></div>
            </div>
        </div>
        <div class="introduction-wrapper">
            <div class="introduction">
                <?php
                if ($type == 'index' || $type == 'category') {
                    include(__DIR__ . '/Introductions/Category.php');
                } elseif ($type == 'post') {
                    include(__DIR__ . '/Introductions/Post.php');
                } elseif ($type == 'tag') {
                    include(__DIR__ . '/Introductions/Tag.php');
                } elseif ($type == 'service') {
                    include(__DIR__ . '/Introductions/Service.php');
                }
                ?>
            </div>
        </div>
        <div class="content-wrapper">
            <?= $content; ?>
        </div>
        <div class="footer">
            <div class="footer-copyright">Copyright © 2019-2022 {username}</div>
        </div>
    </div>
</body>
</html>
