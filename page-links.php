<?php
/**
 * 友链
 * @package custom
 */
if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>



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
                <?php echo handleContent($this->content); ?>
                </div>
	<div id="reader-wall">
    <h2>近期来访</h2>
    <ul class='readers-list'>
       <?php getRecentVisitors(); ?>
    </ul>
</div>


<div id="reader-wall">
    <h2>三人为师</h2>
    <ul class='readers-list'>
       <?php getusers(); ?>
    </ul>
</div>





<div id="reader-wall">
    <h2>初来乍到</h2>
    <ul class='readers-list'>
       <?php getRecentVisitorschuci(); ?>
    </ul>
</div>


<div id="reader-wall">
     <h2>常来常往</h2>
    <ul class='readers-list'>
        <?php getMostVisitorscnt(); ?>
    </ul>
</div>	


<div id="reader-wall">
     <h2>随缘随访</h2>
    <ul class='readers-list'>
        <?php getMostVisitors(); ?>
    </ul>
</div>	
   <!--=<div class="rss">
               
           <?php
            // 获取JSON数据
            $jsonData = file_get_contents('./rss.json');
            // 将JSON数据解析为PHP数组
            $articles = json_decode($jsonData, true);
            // 对文章按时间排序（最新的排在前面）
            usort($articles, function ($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });
            // 设置每页显示的文章数量
            $itemsPerPage = 5;
            // 生成文章列表
            foreach (array_slice($articles, 0, $itemsPerPage) as $article) {
                $articles_list ='
                <div class="rss-entry line-clamp-2">
        <div class="rss-header">
            <a href="' . htmlspecialchars($article['link']) . '" target="_blank"><h4>' . htmlspecialchars($article['title']) . '</h4></a>
        </div>
        <div class="rss-content">
            <p>' . htmlspecialchars($article['description']) . '</p>
        </div>
        <div class="rss-footer">
            <div class="flex-item">
                    <img src="' . htmlspecialchars($article['icon']) . '">
            </div>
            <div class="flex-item">
               ' . htmlspecialchars($article['site_name']) . '
            </div>
            <div class="flex-item">
                 <span>' . htmlspecialchars($article['time']) . '</span>
            </div>
        </div>
    </div> ';
                echo $articles_list;
            }
        ?>

                </div>
<div class="f"></div>-->

  
  
  <!-- <div class="rss">
               

<?php $this->widget('Widget_Comments_Recent','ignoreAuthor=true')->to($comments); ?>
<?php while($comments->next()): ?>
  <li>
     <a href="<?php $comments->permalink(); ?>"><?php $comments->author(false); ?></a>: <?php $comments->excerpt(50, '...'); ?>
  </li>
<?php endwhile; ?>
</div>-->

<!--<?php
$period = time() - 999592000; // 時段: 30 天, 單位: 秒
$counts = Typecho_Db::get()->fetchAll(Typecho_Db::get()
->select('COUNT(author) AS cnt','author', 'url', 'mail')
->from('table.comments')
->where('created > ?', $period )
->where('status = ?', 'approved')
->where('type = ?', 'comment')
->where('authorId = ?', '0')
->group('author')
->order('cnt', Typecho_Db::SORT_DESC)
->limit(25)
);
$mostactive = '';
$avatar_path = 'http://www.gravatar.com/avatar/';
foreach ($counts as $count) {
  $avatar = $avatar_path . md5(strtolower($count['mail'])) . '.jpg';
  $c_url = $count['url']; if ( !$c_url ) $c_url = Helper::options()->siteUrl;
  $mostactive .= "<a href='" . $c_url . "' title='" . $count['author'] . " (参与" . $count['cnt'] . "次互动)' target='_blank'> ".$count['author']. "</a>\n";
}
echo $mostactive; ?>-->

</div>

			</div>
		<!--	<div class="hidden lg:col-span-3 lg:block" id="sidebar-right">
			</div>-->
		</div>
		<?php $this->need("footer.php"); ?>
</div>

</body>
</html>
