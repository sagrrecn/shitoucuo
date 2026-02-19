<?php if (!defined("__TYPECHO_ROOT_DIR__")) {exit();} ?>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<body class="jasmine-body">
<div class="jasmine-container grid grid-cols-12">
        <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
            <?php $this->need("component/menu.php"); ?>
            <div class="flex flex-col gap-y-12">
                <div></div>
                    <div class="mx-1 flex flex-col">
                    <div></div>
                    <div class="flex flex-row" itemscope="" itemtype="https://schema.org/NewsArticle">
                        <div class="mr-3 flex flex-1 flex-col justify-center gap-y-5">
                            <h1 class="text-2xl font-semibold jasmine-primary-color jasmine-letter-spacing" itemprop="headline">未找到页面 </h1>
                        </div>
                    </div>
                </div>
                <!--404页面文章提示 2023.8.6-->
                <div class="404J markdown-body dark:!bg-[#161829] dark:!bg-[#0d1117] !text-neutral-900 dark:!text-gray-400" itemprop="articleBody">
                    <p>不好意思，没有找不到您想要的页面，请检查链接是否正确。</p>
                    <p>若网页有问题麻烦联系反馈（邮件sagrre_cn@126.com），夕格会第一时间修复。</p>
                    <p class="p404">您可以选择：<a href="<?php $this->options->siteUrl(); ?>" title="返回首页">返回首页</a></p> 
                </div>
         <!--2023.8.6 404页面文章调用-->          
            </div>
        </div>
    </div>
    <?php $this->need("footer.php"); ?>
</body>
</html>
