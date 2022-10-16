<div class="card">
    <h2 class="card-header"><?= $frontMatter['icon']; ?> <a class="service" href="<?= POST_URI_PREFIX . $uri; ?>.html"><?= $name; ?></a></h2>
    <div class="card-date">
        <span class="card-date-item"><?= $frontMatter['createdAt']; ?></span>
        <span class="card-date-item"><?php $a = [];
        foreach ($categories as $category) {
            $a[] = $category;
            $cat = implode('/', $a);
            ?> / <a class="service" href="<?= CATEGORY_URI_PREFIX . $cat; ?>"><?= $category; ?></a>
        <?php } ?></span>
    </div>
    <div class="card-description"><?= $description; ?></div>
    <div class="card-tags">
    <?php
        foreach ($tags as $tag) {
            ?> <a class="service" href="<?= TAG_URI_PREFIX . $tag; ?>">#<?= $tag; ?></a>
    <?php } ?>
    </div>
</div>