<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>

<?php
$cids = getStickyPost();
if (null != $cids): ?>
    <div class="flex zd flex-wrap flex-col lg:flex-row gap-y-12 border-stone-100 dark:border-neutral-600">
        <?php foreach ($cids as $cid): ?>
            <?php $this->widget("Widget_Archive@jasmine" . $cid, "pageSize=1&type=post", "cid=" . $cid)->to($item); ?>
            <div class="zdwz" style="width:100%;">
                <?php if ($thumbnail = getThumbnail($item->cid, "")): ?>
                    <a href="<?php $item->permalink(); ?>" title="<?php $item->title(); ?>" class=""
                       title="<?php $item->title(); ?>">
                        <img
                            src="<?php echo $thumbnail; ?>"
                            alt="" width="100%"
                            height="260"
                            alt="<?php $item->title(); ?>"
                            loading="lazy"
                            class="h-[260px]  rounded object-cover"/ style="margin-bottom:0px;">
                    </a>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif;?>
