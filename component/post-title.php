<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>
<style>
.mr-3 {
	margin-right:0rem!important;
}
.dark .markdown-body{margin-top:0px!important;}
</style>
<div class="mx-1 flex flex-col">
                        <?php SimpleBreadcrumb_Plugin::show(); ?>
    <div></div>
    <div class="flex flex-row" itemscope itemtype="https://schema.org/NewsArticle">
        <div class="mr-3 flex flex-1 flex-col justify-center gap-y-5" style="text-align:center;">
            <h1 class="text-2xl ntitle font-semibold jasmine-primary-color jasmine-letter-spacing" itemprop="headline">
                <?php $this->title(); ?>
            </h1>
            <div class="dark:text-gray-400" style="text-align:center;">
               <div class="ICON">
            <!--<span><i class="iconfont icon-xiugai"></i>  <?php echo date('Y-m-d H:i' , $this->created); ?></span>-->
                <span><i class="iconfont icon-xiugai"></i> <?php $weekday = date('w', $this->created); // 获取数字0(周日)到6(周六)
$weekday_cn = ['日', '一', '二', '三', '四', '五', '六'];
echo date('Y-m-d H:i', $this->created) . ' 星期' . $weekday_cn[$weekday];
;?></span>
                <span><i class="iconfont icon-iddenglu"></i> <?php $this->cid(); ?></span><br>
<?php if ($this->is('post')) : ?>
<span><<?php $this->category(','); ?>></span>

<?php endif; ?>

<?php 
// 自动显示（已内置）
// 或手动调用
echo ArticleWeather_Plugin::show($this->cid);
?>
<?php if (class_exists('EditHistory_Plugin')): ?>
    <?php echo EditHistory_Plugin::output(); ?>
<?php endif; ?>
               </div>
            </div>
            <span class="hidden" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <meta itemprop="url" content="<?php $this->author->permalink(); ?>"/>
                    <a itemprop="url" href="<?php $this->author->permalink(); ?>">
                        <span itemprop="name"><?php $this->author->screenName(); ?></span>
                    </a>
                </span>
        </div>
    </div>
</div>
