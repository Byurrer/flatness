<div class="pagenavi">
<?php
for ($i = 1; $i <= $countPage; ++$i) {
    if ($i == $currPage) {
        ?><div class="pagenavi-current"><?= $i; ?></div><?php
    } else {
        ?><div class="pagenavi-page"><a href="<?= $uri . $i; ?>"><?= $i; ?></a></div><?php
    }
}
?>
</div>
