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
                    include(__DIR__ . '/introductions/category.php');
                } elseif ($type == 'post') {
                    include(__DIR__ . '/introductions/post.php');
                } elseif ($type == 'tag') {
                    include(__DIR__ . '/introductions/tag.php');
                } elseif ($type == 'service') {
                    include(__DIR__ . '/introductions/service.php');
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
        <?php echo print_r($allTags, true); ?>
        <?php echo print_r($allCategories, true); ?>
    </div>
</body>
</html>
