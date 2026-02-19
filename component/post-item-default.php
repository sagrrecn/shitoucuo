<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>

<div class="flex flex-row wz" itemscope itemtype="https://schema.org/NewsArticle" style="border-radius:1rem;">
    <div class=" flex flex-1 flex-col justify-between gap-y-3">
   <?php
$thumbnail = getThumbnail($this->cid, "");
$autoThumbnailCategories = array('探店', '爸爸厨房', '户外'); // 需要自动获取封面的分类

// 获取当前文章的分类
$categories = $this->categories;
$currentCategory = '';
if (!empty($categories)) {
    $currentCategory = $categories[0]['name'];
}

// 封面显示逻辑
$showThumbnail = false;
if ($thumbnail) {
    // 如果有手动设置的封面，始终显示
    $showThumbnail = true;
} elseif (in_array($currentCategory, $autoThumbnailCategories)) {
    // 如果是指定分类且没有设置封面，从文章内容中提取第一张图片
    preg_match_all('/<img.*?src=["\'](.*?)["\']/i', $this->content, $matches);
    if (!empty($matches[1])) {
        $thumbnail = $matches[1][0];
        $showThumbnail = true;
    }
}

if ($showThumbnail && $thumbnail): ?>
    <a href="<?php $this->permalink(); ?>" title="<?php $this->title(); ?>" class="">
        <img src="<?php echo $thumbnail; ?>" alt="<?php $this->title(); ?>" width="100%"
             height="90"
             class="h-[260px] rounded object-cover" loading="lazy"/>
    </a>
<?php endif; ?>
        <h2 class="jasmine-link-color-hover text-black line-clamp-1 text-xl jasmine-letter-spacing dark:text-neutral-200" itemprop="headline">
      <?php echo getTimeLabel($this->date->timeStamp); ?>
       <?php
// 从文章内容中统计图片标签
preg_match_all('/<img[^>]+>/i', $this->content, $matches);
$imageCount = count($matches[0]);

// 调试信息

if ($imageCount > 0) {
    if ($imageCount < 20) {
        echo '<span class="nwe">有图</span>';
    } else {
        echo '<span class="nwe">多图</span>';
    }
}
?><a href="<?php $this->permalink(); ?>" class="ptitle" title="<?php $this->title(); ?>"><?php $this->title(); ?></a> 
        </h2>
          <div class="dark:text-gray-400 tm-ca">
             <span><i class="iconfont icon-zuozhe"></i> <a href="<?php $this->author->permalink(); ?> "><?php $this->author() ?></a></span>
             <!--<span class="jasmine-link-color">--><span><i class="iconfont icon-fenlei"></i> <?php $this->category("·", true, "无"); ?></span>
            <span>  <i class="iconfont icon-shijian_o"></i> <?php echo date('Y-m-d H:i:s' , $this->created);?></span>
        </div>
        <p class="line-clamp-2 jasmine-letter-spacing dark:dark:text-gray-400 break-all" itemprop="abstract">
            <a href="<?php $this->permalink(); ?>"
               title="<?php $this->title(); ?>">
                <!--摘要处理，有自定义description字段显示自定义内容，没有则按现有方式截取-->
                <?php 
                // 检查是否有自定义description字段
                $customDescription = $this->fields->description;
                if (!empty($customDescription)) {
                    // 如果有自定义描述，直接显示
                    echo Typecho_Common::subStr(strip_tags($customDescription), 0, 100, '...');
                } else {
                    // 如果没有自定义描述，使用原有的excerpt方法
                    $this->excerpt(100, "");
                }
                ?>
            </a> 
        </p>
    </div>
</div>