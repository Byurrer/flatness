<div class="card">
    <div class="card-content"><?= $content; ?></div>
    <div class="card-tags">
    <?php
        foreach ($tags as $tag) {
            ?> <a class="service" href="<?= TAG_URI_PREFIX . $tag; ?>">#<?= $tag; ?></a>
    <?php } ?>
    </div>
</div>