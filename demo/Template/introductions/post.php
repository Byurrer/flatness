<div class="introduction-icon"><?= $frontMatter['icon']; ?></div>
<div class="introduction-desc">
    <h1 class="card-header"><?= $name; ?></h1>
    <div class="card-date">
        <span class="card-date-item"><?= $frontMatter['createdAt']; ?></span>
        <span class="card-date-item"><?php $a = [];
        if (isset($categories)) {
            foreach ($categories as $category) {
                $a[] = $category;
                $cat = implode('/', $a);
                ?> / <a class="service" href="<?= $buildUriCategory($cat); ?>"><?= $category; ?></a>
        <?php }} ?></span>
    </div>
    <div class="card-description"><?= $description; ?></div>
    <div class="card-tags">
    <?php
        if (isset($tags)) {
            foreach ($tags as $tag) {
                ?> <a class="service" href="<?= $buildUriTag($tag); ?>">#<?= $tag; ?></a>
    <?php }} ?>
    </div>
</div>
