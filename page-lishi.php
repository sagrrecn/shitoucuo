<?php
/**
 * 历史
 * @package custom
 */
if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>
<style>
        .cat-search{padding: 0rem 1rem 0rem!important;border-radius: 9999px;margin-bottom:0rem!important;margin-top:1rem!important;}
        label.flex.flex-row{height:54px;width:56px!important;}
        </style>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<body class="jasmine-body">
<div class="jasmine-container grid grid-cols-12">
		<div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
			<?php $this->need("component/menu.php"); ?>
			<div class="flex flex-col gap-y-12">
				<div></div>
                <div class="markdown-body dark:!bg-[#161829] dark:!bg-[#0d1117] !text-neutral-900 dark:!text-gray-400" itemprop="articleBody">
                            
                     <?php echo handleContent($this->content);?>
<?php DevelopmentHistory_Plugin::render(); ?>
              
                </div>
				</div>
			</div>
		</div>
		<?php $this->need("footer.php"); ?>
</div>

</body>
</html>
