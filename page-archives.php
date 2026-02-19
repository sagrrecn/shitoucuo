<?php
/**
 * 归档
 * @package custom
 */
if (!defined("__TYPECHO_ROOT_DIR__")) {exit();} ?>
<script>// 在页面加载前立即设置主题，避免闪烁
(function(){const e=localStorage.theme||(window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light");"dark"===e||!localStorage.theme&&window.matchMedia("(prefers-color-scheme: dark)").matches?document.documentElement.classList.add("dark"):document.documentElement.classList.remove("dark")})();</script>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<style>


label.flex.flex-row{height:54px;width:56px!important;margin-bottom:-20px!important;}
/* 仅展开按钮相关样式 */
.tags-container {
    position: relative;
}

.tag-list {
    max-height: 200px; /* 控制初始显示高度，根据实际情况调整 */
    overflow: hidden;
    position: relative;
    transition: max-height 0.4s ease;
}

.tag-list.expanded {
    max-height: none !important;
}

.more-tag {
    display: none;
}

.tag-list.expanded .more-tag {
    display: list-item;
}

.more-tags-wrapper {
    text-align: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.more-tags-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 20px;
    background: hsl(0 0% 15%);
    border-radius: 20px;
    color: #FFF;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}


.more-tags-btn:active {
    transform: translateY(1px);
}

.more-tags-btn.expanded .arrow {
    transform: rotate(180deg);
}

.arrow {
    display: inline-block;
    transition: transform 0.3s ease;
    font-size: 11px;
    color: #999;
}
.cat-search{padding: .4rem 1rem .3rem!important;border-radius: 9999px;}
.month-archives {
    display: flex; /* 使子元素横排显示 */
    flex-wrap: wrap; /* 如果需要，子元素可以在必要时换行 */
    justify-content: space-around; /* 子元素之间的间距 */
    padding: 0;
    margin: 0;
    float: left;
    list-style: none; /* 如果你是用列表的话，需要这一行 */
}
.month-archive {
    /* 你可以在这里添加更多样式来美化你的月份统计 */
    margin: 5px; /* 子元素之间的外边距 */
    padding: 3px 15px; /* 子元素的内边距 */
    background-color:  rgb(255,129,0); /* 背景色 */
    border-radius: 9999px; /* 边框圆角 */
    color:#fff!important;
}
.text-2xl {
  font-size: inherit!important;
  line-height: inherit!important;
}

    
.archive-year, .archive-month{
    background-color: #f15a22;
    padding: 3px 7px;
    border-radius: 10px;
    color: #fff!important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
.archive-year:hover, .archive-month:hover{
  transform: translateY(-2px);}


/* 添加一些样式用于展开收起效果 */
.year-title, .month-title {
  cursor: pointer;
}
.month-list {
  margin-left: 20px;
}

.dark .archive-year { background: hsl(0 0% 15%)}
.dark .archive-month { background: hsl(0 0% 15%)}
.year-count, .month-count {
  color: #FFF;
  font-size: 0.8em;
  margin-left: 8px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 10000px;
  padding: 3px 6px;
}
.dark .year-count, .month-count {background:#f15a22;}
.year-content {
  margin-left: 20px;
}

/* 文章标题省略号样式 */
.archive-item {
  display: flex;
  align-items: flex-start;
  gap: 8px;
}
.archive-title {
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: none;
}
.archive-date {
  flex-shrink: 0;
  min-width: 50px;
}
/* 还原分类链接样式 */
.category-link {
  color: inherit;
  text-decoration: none;
}
.category-link:hover {
  color: inherit;
  text-decoration: underline;
}
.comments-count {
  flex-shrink: 0;
}

/* 分类、标签、年份归档美化样式 */
.archive-section {
  margin-bottom: 20px;
  padding: 15px;
  background: #eee;
  border-radius: 10px;
}

.archive-section:last-child{margin-bottom:25px;}

.dark .archive-section {
  background: rgb(10 12 25 / var(--tw-bg-opacity));
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.archive-title-header {
  font-size: 1.5em;
  font-weight: 600;
  margin-bottom: 15px;
  color: #374151;
}

.dark .archive-title-header {
  color: #d1d5db;
}

.category-list, .tag-list {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.category-item, .tag-item {
  margin: 0;
}

.dark .category-link-archive, .tag-link { background: hsl(0 0% 15%)}
.category-link-archive, .tag-link {
  display: inline-flex;
  align-items: center;
  padding: 8px 16px;
  background:#f15a22;
  color: white !important;
  text-decoration: none;
  border-radius: 25px;
  font-size: 0.9em;
  font-weight: 500;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.category-link-archive:hover, .tag-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  color: white !important;
}
.dark .tag-link { background: hsl(0 0% 15%)}
.tag-link {
  background: #f15a22;
}

.dark .category-count {background:#f15a22;}
.dark .tag-count  {background:#f15a22;}
.category-count, .tag-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 24px;
  height: 20px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 10000px;
  font-size: 0.8em;
  margin-left: 6px;
  padding: 0 6px;
}


/* 年份月份归档样式 */
.year-month-archive {
  margin: 0;
  padding: 0;
}

.year-section {
  margin-bottom: 15px;
}

.month-section {
  margin-bottom: 10px;
}

/* 时间进度统计组件的深色模式适配 */
/* 时间进度容器 */
.time-stats-container {
  margin: 20px 0;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.dark .time-stats-container {
  background: rgba(20, 25, 40, 0.8);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.05);
}

/* 时间进度标题 */
.time-stats-title {
  font-size: 1.4em;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
  display: flex;
  align-items: center;
  gap: 10px;
}

.dark .time-stats-title {
  color: #e2e8f0;
}

/* 时间进度图标 */
.time-stats-title i {
  color: #f15a22;
}

.dark .time-stats-title i {
  color: #ff8c42;
}

/* 进度项容器 */
.time-stats-items {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 15px;
}

/* 进度项 */
.time-stat-item {
  background: white;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

.dark .time-stat-item {
  background: rgba(30, 35, 50, 0.8);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.05);
}

.time-stat-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dark .time-stat-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  border-color: rgba(255, 255, 255, 0.1);
}

/* 进度标签 */
.time-stat-label {
  font-size: 0.9em;
  color: #666;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.dark .time-stat-label {
  color: #a0aec0;
}

/* 进度条容器 */
.time-progress-container {
  margin: 10px 0 5px;
}

/* 进度条背景 */
.time-progress-bar {
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.dark .time-progress-bar {
  background: rgba(255, 255, 255, 0.1);
}

/* 进度条填充 */
.time-progress-fill {
  height: 100%;
  border-radius: 4px;
  background: linear-gradient(90deg, #f15a22, #ff8c42);
  transition: width 1s ease-out;
}

/* 进度值 */
.time-progress-value {
  font-size: 0.85em;
  color: #555;
  margin-top: 4px;
  text-align: right;
}

.dark .time-progress-value {
  color: #cbd5e0;
}

/* 进度描述 */
.time-stat-description {
  font-size: 0.8em;
  color: #777;
  margin-top: 5px;
  line-height: 1.4;
}

.dark .time-stat-description {
  color: #94a3b8;
}

/* 特殊高亮项目 */
.time-stat-item.highlight {
  background: linear-gradient(135deg, #fff8e1, #ffecb3);
}

.dark .time-stat-item.highlight {
  background: linear-gradient(135deg, rgba(241, 90, 34, 0.15), rgba(255, 140, 66, 0.15));
  border-color: rgba(241, 90, 34, 0.3);
}

.time-stat-item.highlight .time-stat-label {
  color: #d97706;
}

.dark .time-stat-item.highlight .time-stat-label {
  color: #fbbf24;
}

/* 响应式设计 */
@media (max-width: 768px) {
  .category-list, .tag-list {
    gap: 8px;
  }
  
  .category-link-archive, .tag-link {
    padding: 6px 12px;
    font-size: 0.85em;
  }
  
  .archive-section {
    padding: 15px;
    margin-bottom: 20px;
  }
  
  .time-stats-container {
    padding: 15px;
    margin: 15px 0;
  }
  
  .time-stats-items {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  
  .time-stat-item {
    padding: 12px;
  }
}

/* 超小屏幕适配 */
@media (max-width: 480px) {
  .time-stats-title {
    font-size: 1.2em;
  }
  
  .time-progress-value {
    font-size: 0.8em;
  }
  
  .time-stat-description {
    font-size: 0.75em;
  }
}
</style>

<?php $this->need('assets/lantern.html'); ?>
<body class="jasmine-body" style="margin-top:5rem;">
   
<div class="jasmine-container grid grid-cols-12">
        <div class="flex col-span-12 lg:col-span-8 flex-col border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
            <?php $this->need("component/menu.php"); ?>
<?php
// 在归档页面任意位置直接调用
if (class_exists('TimeProgressStats_Plugin')) {
    echo TimeProgressStats_Plugin::renderStats();
}
?>
            
            <div class="flex flex-col " style="margin:-10px auto -25px;">
                
                <!-- 分类归档 -->
                <div class="archive-section" style="margin-top:10px;">
                    <?php 
                    // 获取分类并按文章数排序
                    $categories = $this->widget("Widget_Metas_Category_List");
                    $categoryArray = array();
                    
                    // 重新遍历分类并存储到数组
                    $categories->reset();
                    while ($categories->next()) {
                        $categoryArray[] = array(
                            'name' => $categories->name,
                            'permalink' => $categories->permalink,
                            'count' => $categories->count,
                            'description' => $categories->description
                        );
                    }
                    
                    // 按文章数从多到少排序
                    usort($categoryArray, function($a, $b) {
                        return $b['count'] - $a['count'];
                    });
                    ?>
                    <?php if (count($categoryArray) > 0): ?>
                        <div class="archive-title-header">📁 按分类归档</div>
                        <ul class="category-list">
                            <?php foreach ($categoryArray as $category): ?>
                                <li class="category-item">
                                    <a class="category-link-archive" href="<?php echo $category['permalink']; ?>" rel="tag"
                                       title="<?php echo $category['name']; ?>">
                                        <?php echo $category['name']; ?>
                                        <span class="category-count"><?php echo $category['count']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
        

                <!-- 年份月份归档 -->
                <div class="archive-section">
                   <div class="archive-title-header">🕰️ 按年份归档</div>
                    <div class="year-month-archive">
                        <?php 
                        $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);
                        
                        // 先统计每个年份和月份的文章数量
                        $yearCount = array();
                        $monthCount = array();
                        $allPosts = array();
                        
                        // 第一次遍历：收集所有文章并统计数量
                        while($archives->next()):
                            $year_tmp = date('Y', $archives->created);
                            $mon_tmp = date('m', $archives->created);
                            $yearMonth = $year_tmp . '-' . $mon_tmp;
                            
                            // 统计年份文章数
                            if (!isset($yearCount[$year_tmp])) {
                                $yearCount[$year_tmp] = 0;
                            }
                            $yearCount[$year_tmp]++;
                            
                            // 统计月份文章数
                            if (!isset($monthCount[$yearMonth])) {
                                $monthCount[$yearMonth] = 0;
                            }
                            $monthCount[$yearMonth]++;
                            
                            // 处理标题长度
                            $title = $archives->title;
                            if (mb_strlen($title, 'UTF-8') > 30) {
                                $title = mb_substr($title, 0, 30, 'UTF-8') . '...';
                            }
                            
                            // 存储文章信息
                            $allPosts[] = array(
                                'year' => $year_tmp,
                                'month' => $mon_tmp,
                                'created' => $archives->created,
                                'permalink' => $archives->permalink,
                                'title' => $title,
                                'original_title' => $archives->title, // 保留原始标题用于tooltip
                                'commentsNum' => $archives->commentsNum,
                                'categories' => $archives->categories
                            );
                        endwhile;
                        
                        // 按年份和月份排序（从新到旧）
                        krsort($yearCount);
                        krsort($monthCount);
                        
                        $output = '';
                        $currentYear = '';
                        $currentMonth = '';
                        
                        foreach ($allPosts as $post):
                            $year_tmp = $post['year'];
                            $mon_tmp = $post['month'];
                            
                            // 获取分类信息 - 如果有分类就显示，没有就不显示
                            $categoryDisplay = '';
                            if (!empty($post['categories'])) {
                                $category = current($post['categories']);
                                $categoryName = $category['name'];
                                $categoryPermalink = $category['permalink'];
                                $categoryDisplay = '['.'<a href="'. $categoryPermalink .'" class="category-link dark:text-gray-400">'. $categoryName .'</a>'.']';
                            }
                            
                            // 新年份开始
                            if ($currentYear != $year_tmp) {
                                // 关闭前一个月份和年份
                                if ($currentMonth != '') {
                                    $output .= '</ul></div>';
                                }
                                if ($currentYear != '') {
                                    $output .= '</div></div>';
                                }
                                
                                $currentYear = $year_tmp;
                                $currentMonth = '';
                                $yearTotal = isset($yearCount[$currentYear]) ? $yearCount[$currentYear] : 0;
                                
                                $output .= '<div class="year-section">';
                                $output .= '<h3 class="archive-year title text-2xl my-2 dark:text-neutral-300 year-title" style="cursor:pointer;width:150px!important;padding:8px 16px;border-radius:25px;" onclick="toggleYear(this)">'. 
                                           $currentYear .'<span class="year-count">' . $yearTotal . '</span><span class="toggle-text ml-2">[+]</span></h3>';
                                $output .= '<div class="year-content" style="display:none;">';
                            }
                            
                            // 新月开始
                            if ($currentMonth != $mon_tmp) {
                                // 关闭前一个月
                                if ($currentMonth != '') {
                                    $output .= '</ul></div>';
                                }
                                
                                $currentMonth = $mon_tmp;
                                $yearMonth = $currentYear . '-' . $currentMonth;
                                $monthTotal = isset($monthCount[$yearMonth]) ? $monthCount[$yearMonth] : 0;
                                
                                $output .= '<div class="month-section">';
                                $output .= '<h4 class="archive-month title text-2xl my-2 dark:text-neutral-300 jasmine-primary-color month-title" style="cursor:pointer;color:#fff!important;width:130px!important;border-radius:25px;padding:5px 16px" onclick="toggleMonth(this)">'. $currentMonth .'<span class="month-count">' . $monthTotal . '</span><span class="toggle-text ml-2">[+]</span></h4>';
                                $output .= '<ul class="month-list" style="display:none; transition: all 0.3s ease;">';
                            }
                            
                            // 添加tooltip显示完整标题
                            $titleAttr = '';
                            if (mb_strlen($post['original_title'], 'UTF-8') > 30) {
                                $titleAttr = ' title="' . htmlspecialchars($post['original_title']) . '"';
                            }
                            
                            $output .= '<li class="archive-list archive-item py-1 gdwz">';
                            $output .= '<span class="archive-date">' . date('m-d', $post['created']) . '</span>';
                            $output .= '<a href="' . $post['permalink'] . '" class="dark:text-gray-400 archive-title"' . $titleAttr . '>';
                            $output .= $post['title'];
                            $output .= '</a>';
                            $output .= '</li>';
                        endforeach;
                        
                        // 关闭最后的月份和年份
                        if ($currentMonth != '') {
                            $output .= '</ul></div>';
                        }
                        if ($currentYear != '') {
                            $output .= '</div></div>';
                        }
                        
                        echo $output;
                        ?>
                    </div>
                </div>
                
                           <!-- 地图归档 -->
                <div class="archive-section" style="margin-top:10px;">
                    
                    <div class="archive-title-header">🗺️ 按足迹归档</div>
                    
                    <!--足迹于2025.12.21启用，数据也是这个日期开始统计，因地图整合了多个前端、后端插件，可能存在未知问题，友友们有发现记得提醒夕格。-->
                         <?php MyTrack_Widget::output(); ?>    
                </div>
                
                  <!-- 标签归档 -->
              <div class="archive-section">
    <?php
    // 获取所有标签
    $tags = $this->widget("Widget_Metas_Tag_Cloud");
    $tagArray = array();
    
    // 重新遍历标签并存储到数组
    $tags->reset();
    while ($tags->next()) {
        // 跳过文章数为0的标签
        if ($tags->count > 0) {
            $tagArray[] = array(
                'name' => $tags->name,
                'permalink' => $tags->permalink,
                'count' => $tags->count
            );
        }
    }
    
    // 按文章数从多到少排序
    usort($tagArray, function($a, $b) {
        return $b['count'] - $a['count'];
    });
    
    // 设置要显示的初始标签数量
    $initialDisplayCount = 20; // 可以根据需要调整
    $totalTags = count($tagArray);
    ?>
    
    <?php if ($totalTags > 0): ?>
        <div class="archive-title-header">🏷️ 按标签归档</div>
        
        <div class="tags-container">
            <ul class="tag-list">
                <?php foreach ($tagArray as $index => $tag): ?>
                    <li class="tag-item <?php echo $index >= $initialDisplayCount ? 'more-tag' : ''; ?>">
                        <a class="tag-link" href="<?php echo $tag['permalink']; ?>" rel="tag"
                           title="<?php echo $tag['name']; ?>">
                            <?php echo $tag['name']; ?>
                            <span class="tag-count"><?php echo $tag['count']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <?php if ($totalTags > $initialDisplayCount): ?>
                <div class="more-tags-wrapper">
                    <button type="button" class="more-tags-btn" onclick="toggleMoreTags()">
                        <span class="btn-text">展开全部 (<?php echo $totalTags - $initialDisplayCount; ?>个)</span>
                        <!--<span class="arrow">▼</span>-->
                    </button>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

                
                
            </div>
        </div>
    </div>
    
    
  
<script>  
    function toggleMonth(element) {  
        var monthList = element.nextElementSibling;  
        var toggleText = element.querySelector('.toggle-text');  
  
        if (monthList.style.display === 'none') {  
            monthList.style.display = 'block';  
            toggleText.textContent = '[-]';  
        } else {  
            monthList.style.display = 'none';  
            toggleText.textContent = '[+]';  
        }  
    }  
    
    function toggleYear(element) {  
        var yearContent = element.nextElementSibling;  
        var toggleText = element.querySelector('.toggle-text');  
  
        if (yearContent.style.display === 'none') {  
            yearContent.style.display = 'block';  
            toggleText.textContent = '[-]';  
        } else {  
            yearContent.style.display = 'none';  
            toggleText.textContent = '[+]';  
        }  
    }  
    
    function toggleMoreTags() {
    const tagList = document.querySelector('.tag-list');
    const moreBtn = document.querySelector('.more-tags-btn');
    const btnText = document.querySelector('.btn-text');
    const arrow = document.querySelector('.arrow');
    
    tagList.classList.toggle('expanded');
    moreBtn.classList.toggle('expanded');
    
    if (tagList.classList.contains('expanded')) {
        btnText.textContent = '收起';
    } else {
        btnText.textContent = '展开 (<?php echo $totalTags - $initialDisplayCount; ?>个)';
    }
}
</script>  
    <?php /*$this->need("footer.php"); */?>
    <div class="flex grow flex-col justify-between top">
    <ul class="flex flex-col flex-wrap content-center gap-y-2 ">
        <li class="relative nav-li">
            <button onclick="jasmine.switchDark()" title="日夜模式">
                <iconify-icon icon="<?php echo getOptionValueOrDefault("switchDarkIconPhone", "tabler:sun-moon"); ?>"
                              class="rounded px-2 py-1 text-2xl jasmine-primary-bg-hover btop"></iconify-icon>
            </button>
            
        </li>
        <li class="relative nav-li">
            <button onclick="jasmine.backtop()" title="返回顶部">
                <iconify-icon icon="tabler:arrow-bar-to-up"
                              class="rounded px-2 py-1 text-2xl jasmine-primary-bg-hover btop"></iconify-icon>
            </button>
            
        </li>

    </ul>
</div>
</body>
</html>