<?php
/**
 * 记录类个人站点，简洁、美观、响应式主题<br/>
 * Github：<a href="https://github.com/sagrre" target="_blank" title="xige Github">https://github.com/xige/</a>
 * Demo：<a href="https://www.shitoucuo.com/" target="_blank" title="sagrre">https://www.shitoucuo.com/</a>
 *
 * @package sagrre
 * @author sagrre
 * @version 1.2
 * @link https://www.xigeshudong.com/
 */
if (!defined("__TYPECHO_ROOT_DIR__")) {exit();} ?>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<body class="jasmine-body">
<div class="jasmine-container grid grid-cols-12">
    <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
	<?php $this->need("component/menu.php"); ?> <!--导航-->      
        <?php if ($this->is("index") && $this->_currentPage == 1): ?><!--置顶--> 
            <?php $this->need("component/post-top.php"); ?><!----> 
        <?php endif; ?>
        <?php $this->need("component/post-item.php"); ?><!--普通列表--> 
         <?php $this->need("component/paging.php"); ?><!--分页--> 
    </div>
</div>
    <?php $this->need("footer.php"); ?><!--底部--> 
</body>

</html>
