<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
    exit();
    
} ?>

<?php
// 获取文章分类
$categories = $this->categories;

// 定义需要特殊样式的分类slug或mid
$special_categories = array('探店', '户外', '爸爸厨房');

// 判断是否包含特殊分类
$has_special_class = false;
foreach ($categories as $category) {
    if (in_array($category['name'], $special_categories) || 
        in_array($category['slug'], $special_categories) ||
        in_array($category['mid'], $special_categories)) {
        $has_special_class = true;
        break;
    }
}
?>

<?php if ($has_special_class): ?>
<style>
/* 其他样式保持不变 */
.cat-search {
    padding: .4rem 1rem .3rem!important;
    border-radius: 9999px;
}
label.flex.flex-row {
    height: 54px;
    width: 56px!important;
}
form {
    margin-block-end: 0em!important;
}

/* 最简单直接的解决方案：给body添加特殊类 
body.special-category-active p img:hover,
body.special-category-active .post-content img:hover,
body.special-category-active .typecho-post-content img:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}*/

/* 强制保护地图卡片 */
.amap-card,
.amap-card * {
    transform: none !important;
    transition: none !important;
}

/* 地图卡片图片特殊保护 */
.amap-card img,
.amap-card .amap-cover-image,
.amap-card .amap-photo-thumb img {
    transform: none !important;
    transition: none !important;
}
</style>

<script>
// 最简单的方法：直接给body添加类
document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('special-category-active');
});
</script>
<?php else: ?>
<style>
/* 非特殊分类的样式 */
.cat-search {
    padding: .4rem 1rem .3rem!important;
    border-radius: 9999px;
}
label.flex.flex-row {
    height: 54px;
    width: 56px!important;
}
form {
    margin-block-end: 0em!important;
}
</style>
<?php endif; ?>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<?php $this->need('assets/lantern.html'); ?>
<body class="jasmine-body" style="margin-top:5rem;">
    <div class="jasmine-container grid grid-cols-12">
        <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
            <?php $this->need("component/menu.php"); ?>
            <div class="flex flex-col gap-y-12">
                <div></div>
                <?php $this->need("component/post-title.php"); ?>

                <div class="markdown-body yj dark:!bg-[#161829] dark:!bg-[#0d1117] !text-neutral-900 dark:!text-gray-400 post<?php if ($has_special_class) echo ' special-category'; ?>" itemprop="articleBody" id="post-<?php $this->cid(); ?>">

                    <?php if (Typecho_Widget::widget('Widget_User')->hasLogin()) { ?>
                        <?php echo handleContent($this->content); ?>
                    <?php } ?>
                    <?php if (!Typecho_Widget::widget('Widget_User')->hasLogin()) { ?>
                        <div class="tepasspost">
                            <div class="tp">
                                <span class="tips"><i class="iconfont icon-suo"></i></span>
                                <span class="tips">已进入私人领地，请登录再阅读<!--，石头厝仅接受主动邀请（免费）和订阅(付费)两种方式招募会员，付费订阅请先阅读政策：<a href="https://www.shitoucuo.com/dingyue.html" style="text-decoration:underline;"  target="_blank">成为会员</a><!--<br>欢迎订阅夕格树洞<a href="https://www.shitoucuo.com/feed" target="_blank"><strong>RSS</strong></a>，无需权限全文阅读大量未涉及隐私内容</span>-->
                                <div class="tips"><a class="btn" target="_blank" href="/xige">登录</a> </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

<?php if ($this->is('post')): ?>
    <!-- 显示在文章内容之后 -->
    <!--<section class="post-thoughts">--->
        <?php if (class_exists('ThoughtsPlugin_Plugin')): ?>
            <?php echo ThoughtsPlugin_Plugin::renderThoughts($this->cid); ?>
        <?php endif; ?>
    <!--</section>-->
<?php endif; ?>


                <div id="history-t">
                    <h2>往年今日<i class="iconfont icon-fabu"></i> <?php echo date('m-d', $this->created); ?></h2>
                </div>

                <div class="flex flex-row gap-x-2 mb post-history" style="display: flex;
  flex-wrap: wrap;align-items:center;align-items: center; /* 垂直居中 */
justify-content: center; ">
                    <?php historyToday($this->created); ?>
                </div>


                <div class="flex flex-row gap-x-2 mb " id="post-tag">


                    <?php
                    // 获取所有标签的文章数（减少 SQL 查询）
                    $db = Typecho_Db::get();
                    $tagCounts = $db->fetchAll($db->select('mid, COUNT(cid) AS count')
                        ->from('table.relationships')
                        ->group('mid'));
                    $tagCountMap = array_column($tagCounts, 'count', 'mid');
                    ?>
                    <?php if (Typecho_Widget::widget('Widget_User')->hasLogin()) { ?>
                        <?php if ($this->tags): ?>
                            <?php foreach ($this->tags as $tag): ?>
                                <a href="<?php echo $tag['permalink']; ?>" class="post--keyword" data-title="<?php echo $tag['name']; ?>" data-type="post_tag" data-term-id="<?php echo $tag['mid']; ?>">
                                    <?php echo $tag['name']; ?><sup>[<?php echo $tagCountMap[$tag['mid']] ?? 0; ?>]</sup>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php } ?>


                </div>

                <div class="flex flex-row gap-x-2 revis">     
                  <?php if ($comments->authorId == $comments->ownerId) { ?>
            <?php $this->widget("Widget_Comments_Recent", [])->to($newComments); ?>
            <?php if ($newComments->have()): ?>
                <?php while ($newComments->next()): ?>
                    <li>
                        <a href="<?php $newComments->url(); ?>"
                           title="近期评论：<?php $newComments->excerpt(35, "..."); ?>" target="_blank"
                           class="line-clamp-2  text-sm dark:text-gray-400 jasmine-link-color-hover text-neutral-500">
                             <img class="rounded w-[50px] h-[50px] object-cover" width="50" height="50"
                     src="<?php echo getAvatarByMail($newComments->mail); ?>"
                     loading="lazy"
                     alt="<?php $comments->author; ?>">
                            </a>
                    </li>
                <?php endwhile; ?>
            <?php endif; ?>
        <?php } ?>
       </div>
            </div>
        </div>

    </div>

    <!-- 相关文章
       <div class="flex flex-row gap-x-2 mb post-history" style="paddding-top：20px;" >
                      <?php $this->related(5)->to($relatedPosts); ?>
    <?php while ($relatedPosts->next()): ?>
    <a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>"><?php $relatedPosts->title(); ?></a>
    <?php endwhile; ?>-->

<?php if ($this->is('single')): ?>
    <?php RelatedPosts_Plugin::output(); ?>
<?php endif; ?>

    </div>
    <?php if ($this->allow("comment")): ?>
        <div class="jasmine-container grid grid-cols-12 jlq">
            <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
                <div class="pl" style="padding-top:3rem;">

                    <div class="comment_ding">
                        <h2>交流区</h2>
                    </div>
                    <?php if (!Typecho_Widget::widget('Widget_User')->hasLogin()) { ?>

                        <div class="tepasspostpl">
                            <div class="tppl">
                                <span class="tipspl"><i class="iconfont icon-zhucehuiyuan"></i></span>
                                <span class="tipspl mb">已开启深度、隐私交流模式，请登录后参与互动</span>
                                <span class="tipspl">已有 <a style="border-radius:999999px;color:#FF6600;">
                                        <<?php $this->commentsNum() ?>> </a>条评论</span>
                                <!--<span class="tipspl mb">注册登录遇问题请发邮件到sagrre_cn@126.com，老朋友请注明曾用评论昵称、邮箱，夕格帮您查询或注册初始账户</span>-->
                                <div class="tipspl"><a class="btn" href="/xige">登录</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($this->user->hasLogin()): ?>
                        <div class="tepasspostpl">
                            <div class="tppl">
                                <span class="tipspl"> <img class="img-thumbnail rounded tx me-1" width="50" height="50" src="<?php echo getAvatarByMail($this->user->mail, true); ?>" loading="lazy" alt="<?php $this->user->screenName(); ?>"></i></span>
                                <span class="tipspl"><?php $this->user->screenName(); ?></span>
                                <span class="tipspl">您已成功登录，祝阅读愉快</span>
                                <div class="tipspl">

                                    <a class="btn " target="_blank" href="https://www.shitoucuo.com/xige/">个人中心</a>
                                    <a class="btn" href="<?php $this->options->logoutUrl(); ?>" title="退出">退出登录</a>

                                    <!--<a class="btn " onclick="tepassCheckin();">签到</a>-->
                                </div>
                            </div>
                        </div>

        </div>
        <?php $this->need("comments.php"); ?>
    <?php endif; ?>
    </div>
    </div>
    </div>
<?php endif; ?>
<?php $this->need("footer.php"); ?>
</body>

</html>