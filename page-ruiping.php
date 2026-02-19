<?php
/**
 * 锐评
 * @package custom
 */
if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>
<!DOCTYPE html>
<html lang="zh">
    <style>
    label.flex.flex-row{height:54px;width:56px!important;margin-bottom:-20px!important;}
.cat-search{padding: .4rem 1rem .3rem!important;border-radius: 9999px;}
     .markdown-body a:before {
    content: ""!important;
    width: 0px!important;
    font-family: "iconfont";
    height: 0px;
    background-size: 18px 18px;
    display: inline-block !important;
    margin-bottom: -3px;
    justify-content: center;
    align-items: center;
}
    .cat-search{padding: .4rem 1rem -2rem!important;border-radius: 9999px;}
    label.flex.flex-row{height:54px;width:56px!important;}
        
.heartfelt-comment-source a{color:#666!important;}
.heartfelt-comment-author a{color:#666!important;}
 .heartfelt-comment-item{border: 1px solid rgba(0, 0, 0, 0);}
  .heartfelt-comment-item:hover{border: 1px solid #f15a22;}
 .dark .heartfelt-comments{background:#1d1d1e!important;}
 .dark .heartfelt-comment-item{background: rgb(10 12 25 / 1);}
 .dark .markdown-body a{color: rgb(156 163 175 / 1) !important;}
 .dark .heartfelt-comment-meta{color:#f15a22!important;}
 .dark .heartfelt-comment-author a,.dark .heartfelt-comment-source a{color:#f15a22!important;}
  .heartfelt-title{border: 1px solid  #f15a22;color:#000!important;}
  .dark .heartfelt-title{color:#fff!important;}
 .dark .markdown-body h3 {margin-top:0px!important;}
  .markdown-body h3 {margin-top:0px!important;font-weight:300!important;font-size:1em!important;}
 .markdown-body h3::before
 {
    content: ''!important;
    margin-right: 0px!important;
}
    </style>
<?php $this->need("header.php"); ?>
<body class="jasmine-body" style="margin-top:5rem;">
<div class="jasmine-container grid grid-cols-12">
		<div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
			<?php $this->need("component/menu.php"); ?>
			<div class="flex flex-col gap-y-12">
				<div></div>
                <div class="markdown-body dark:!bg-[#161829] dark:!bg-[#0d1117] !text-neutral-900 dark:!text-gray-400" itemprop="articleBody">
                            
<?php echo RuiPing_Plugin::render(); ?>
                </div>
				</div>
			</div>
		</div>
		<?php $this->need("footer.php"); ?>
</div>

</body>
</html>
