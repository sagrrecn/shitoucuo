<?php
/**
 * 速览
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 在输出任何HTML之前添加主题检测脚本
echo '<script>
// 在页面加载前立即设置主题，避免闪烁
(function() {
    const theme = localStorage.theme || (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
    if (theme === "dark" || (!localStorage.theme && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
})();
</script>';

$this->need('header.php');

// 计算最近6个月的日期范围
$endDate = new DateTime();
$startDate = new DateTime();
$startDate->modify('-5 months'); // 最近6个月（包含当前月）
$startDate->modify('first day of this month'); // 从当月第一天开始

// 只显示最近6个月
$db = Typecho_Db::get();

// 查询所有已发布的文章，在PHP中进行日期筛选
$posts = $db->fetchAll($db->select('title', 'created', 'slug', 'cid')
    ->from('table.contents')
    ->where('table.contents.type = ?', 'post')
    ->where('table.contents.status = ?', 'publish')
    ->order('table.contents.created', Typecho_Db::SORT_ASC));

// 组织热力图数据
$heatmapData = [];

foreach ($posts as $post) {
    $postDate = date('Y-m-d', $post['created']);
    $postDateTime = new DateTime($postDate);
    
    // 只处理最近6个月的数据
    if ($postDateTime >= $startDate && $postDateTime <= $endDate) {
        if (!isset($heatmapData[$postDate])) {
            $heatmapData[$postDate] = [
                'count' => 0,
                'articles' => []
            ];
        }
        
        $heatmapData[$postDate]['count']++;
        // 构建正确的文章链接（添加.html后缀）
        $postUrl = Typecho_Common::url($post['slug'] . '.html', $this->options->index);
        $heatmapData[$postDate]['articles'][] = [
            'title' => $post['title'],
            'url' => $postUrl
        ];
    }
}

// 补全最近6个月中所有日期的数据
$currentDate = clone $startDate;
$endOfRange = clone $endDate;
$endOfRange->modify('last day of this month');

for ($date = clone $currentDate; $date <= $endOfRange; $date->modify('+1 day')) {
    $dateString = $date->format('Y-m-d');
    if (!isset($heatmapData[$dateString])) {
        $heatmapData[$dateString] = [
            'count' => 0,
            'articles' => []
        ];
    }
}

// 计算月份标签的准确位置
$monthPositions = [];
$tempDate = clone $startDate;
$firstDayOfWeek = $tempDate->format('N'); // 1=周一, 7=周日
$offset = $firstDayOfWeek - 1; // 转换为0=周一, 6=周日

$currentMonth = (int)$tempDate->format('n');
$monthPositions[$currentMonth] = 0; // 第一个月从位置0开始

$dayCount = 0;
while ($tempDate <= $endOfRange) {
    $month = (int)$tempDate->format('n');
    if ($month != $currentMonth) {
        $monthPositions[$month] = $dayCount;
        $currentMonth = $month;
    }
    $dayCount++;
    $tempDate->modify('+1 day');
}

// 生成最近6个月的月份标签和位置
$monthLabels = [];
$currentMonth = clone $startDate;
for ($i = 0; $i < 6; $i++) {
    $monthNum = (int)$currentMonth->format('n');
    $position = isset($monthPositions[$monthNum]) ? $monthPositions[$monthNum] : 0;
    $monthLabels[] = [
        'name' => $currentMonth->format('n月'),
        'position' => $position
    ];
    $currentMonth->modify('+1 month');
}

// 计算总周数
$totalDays = $endOfRange->diff($startDate)->days + 1;
$totalWeeks = ceil(($totalDays + $offset) / 7);

//以下为往年今日文章调用代码
// 获取当前日期信息
$current_month = date('m');
$current_day = date('d');
$current_year = date('Y');

// 查询往年今日文章
$db = Typecho_Db::get();
$select = $db->select()
    ->from('table.contents')
    ->where('type = ?', 'post')
    ->where('status = ?', 'publish');

// 获取所有文章然后手动过滤
$all_posts = $db->fetchAll($select);
$past_posts = array();

foreach ($all_posts as $post) {
    $post_month = date('m', $post['created']);
    $post_day = date('d', $post['created']);
    $post_year = date('Y', $post['created']);
    
    // 筛选条件：同月同日，但不是今年
    if ($post_month == $current_month && $post_day == $current_day && $post_year != $current_year) {
        $past_posts[] = $post;
    }
}

// 按创建时间倒序排列
usort($past_posts, function($a, $b) {
    return $b['created'] - $a['created'];
});
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <style>
        /* 添加平滑过渡效果 */
        body, .article-card, .heatmap-content, .post-list, .comments-section {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .cat-search{padding: .4rem 1rem .3rem!important;border-radius: 9999px;}
        label.flex.flex-row{height:54px;width:56px!important;}
        
        /* 重置样式 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* 基础样式 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
            padding: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .markdown-body a:before {
            display: none !important;
            content: none !important;
        }
        
        /* 文章卡片样式 */
        .articles-list {
            display: grid;
            gap: 20px;
        }
        
        .article-card {
            background:#eee;
            border-radius: 10px;
            padding: 0 25px 25px;
            border: 1px solid rgba(0, 0, 0, 0);
            transition: all 0.3s ease;
        }
        
        .dark .article-card {
            background:rgb(10 12 25 /1);
            transition: all 0.3s ease;
        }
        
         .dark .article-card {
            background:rgb(10 12 25 /1);
            transition: all 0.3s ease;
        }
        
        .article-card:hover {
            border:1px solid #f15a22;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            margin: 0px auto 15px;
            color: #e74c3c;
            font-size: 1rem;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .article-time {
            font-weight: 500;
        }
        
        .article-category {
            padding: 2px 2px;
            border-radius: 5px;
            text-decoration: none;
            font-weight:500;
            color: #e74c3c!important;
        }
        .article-category:hover{
            text-decoration: underline!important;
        }
        .scm {padding:15px 0px 0px;}
        .article-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: rgb(0 0 0 /1);
            transition: color 0.3s ease;
        }
        
        .dark .article-title a{
            color:rgb(156 163 175 /1)!important;
            transition: color 0.3s ease;
        }
        
        .scm{
            border-top: 1px solid hsla(210, 18%, 87%, 1);
            transition: border-color 0.3s ease;
        }
        
        .dark .scm{
            border-top: 1px solid #21262d;
            transition: border-color 0.3s ease;
        }
        
        .article-title a {
            text-decoration: none;
            color: inherit;
            transition: color 0.2s;
        }
        
        .article-title a:hover {
            color: #e74c3c;
        }
        
        .article-excerpt {
            color: #555;
            line-height: 1.7;
            transition: color 0.3s ease;
        }
        
        .dark .article-excerpt{
            color:rgb(156 163 175 / var(--tw-text-opacity)) !important;
            transition: color 0.3s ease;
        }           
        
        .comment-item:last-child{margin-bottom:0px!important;}
        
        /* 文章图片样式 */
        .article-images {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding-top:10px;
        }
        
        .article-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s;
        }
        
        .article-image:hover {
            transform: scale(1.03);
        }
        
        /* 评论样式 */
        .comments-section {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .dark  .comments-section { 
  
            transition: all 0.3s ease;
        }
        
        .comment-item {
            padding: 1px 0px;
            margin-bottom: 8px;
            border-radius: 0 10px 10px 0;
            transition: background-color 0.2s;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .dark .comment-content{
            color:rgb(156 163 175 / 1);
            transition: color 0.3s ease;
        }
        
        .comment-author {
            font-weight: 500;
        }
        
        .comment-content {
            color: #2c3e50;
            display: inline;
            transition: color 0.3s ease;
        }
        
        .comment-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .comment-link:hover .comment-content {
            color: #e74c3c;
        }
        
        /* 热力图样式 */
        .heatmap-container {
            max-width: 800px;
            margin: 20px auto 25px;
            padding: 0px;
            border-radius: 10px;
        }
        
        .heatmap-header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .heatmap-header h1 {
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 1.5rem;
        }
        
        .heatmap-content {
            overflow-x: visible;
            background: #f8f9fa;
            padding: 25px 10px;
            border-radius: 10px;
            position: relative;
            border: 1px solid #f15a22;
            transition: all 0.3s ease;
        }
        
        .dark .heatmap-content{
            background: rgb(10 12 25 /1);
            transition: all 0.3s ease;
        }
        
        .heatmap {
            display: block;
            width: 100%;
            position: relative;
        }
        
        .month-labels {
            display: flex;
            margin-left: 100px;
            margin-bottom: 8px;
            position: relative;
            height: 20px;
        }
        
        .month-label {
            font-size: 0.7rem;
            color: #7f8c8d;
            text-align: left;
            position: absolute;
            transform: translateX(-50%);
            transition: color 0.3s ease;
        }
        
        .dark .month-label{
            color: #fff;
            transition: color 0.3s ease;
        }
        
        .calendar {
            display: flex;
            padding-left: 15px;
        }
        
        .day-labels {
            display: flex;
            flex-direction: column;
            margin-right: 7px;
            flex-shrink: 0;
            margin-top:-5px;
        }
        
        .day-label {
            height: 12px;
            font-size: 0.8rem;
            color: #7f8c8d;
            text-align: right;
            padding-right: 3px;
            margin-bottom: 2px;
            transition: color 0.3s ease;
        }
        
        .dark .day-label{
            color: #fff;
            transition: color 0.3s ease;
        }
        
        .weeks {
            display: flex;
            flex-wrap: nowrap;
            width: calc(100% - 25px);
        }
        
        .week {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        
        .day {
            width: 12px;
            height: 12px;
            margin: 0 1px 2px 0;
            border-radius: 2px;
            background: #ebedf0;
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
        }
        
        /* 热力图颜色等级 */
        .activity-low { background: #ebedf0; }
        .activity-medium { background: #9be9a8; }
        .activity-high { background: #40c463; }
        .activity-higher { background: #30a14e; }
        .activity-highest { background: #216e39; }
        
        /* 提示框样式 */
        .day {
            position: relative;
        }
        
        .day-tooltip {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.95);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            min-width: 200px;
            max-width: 280px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 1px solid #333;
            pointer-events: none;
        }
        
        .weeks {
            position: relative;
            z-index: 1;
        }
        
        .day:hover {
            z-index: 100;
        }
        
        .day:hover .day-tooltip {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        
        .tooltip-date {
            font-weight: bold;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            color: #3498db;
            pointer-events: none;
            font-size: 0.7rem;
        }
        
        .tooltip-articles {
            max-height: 180px;
            overflow-y: auto;
            pointer-events: auto;
        }
        
        .tooltip-article {
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            white-space: normal;
            line-height: 1.3;
            text-align: left;
        }
        
        .tooltip-article:last-child {
            border-bottom: none;
        }
        
        .tooltip-article a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 3px 6px;
            border-radius: 2px;
            margin: 1px 0;
            transition: all 0.2s ease;
            pointer-events: auto;
            cursor: pointer;
            font-size: 0.7rem;
        }
        
        .tooltip-article a:hover {
            color: #3498db;
            background: rgba(255, 255, 255, 0.15);
        }
        
        /* 滚动条样式 */
        .tooltip-articles::-webkit-scrollbar {
            width: 4px;
        }
        
        .tooltip-articles::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }
        
        .tooltip-articles::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .tooltip-articles::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .article-images {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .article-image {
                height: 120px;
            }
            
            .comment-item {
                font-size: 0.85rem;
                padding: 8px 12px;
            }
            
            .heatmap-container {
                padding: 10px;
                margin: 10px auto;
            }
            
            .heatmap-content {
                padding: 10px;
            }
            
            .day-tooltip {
                min-width: 160px;
                font-size: 0.65rem;
                max-width: 240px;
            }
            
            .month-labels {
                margin-left: 20px;
            }
            
            .day {
                width: 10px;
                height: 10px;
            }
            
            .day-label {
                height: 10px;
                font-size: 0.55rem;
            }
        }
        
        @media (max-width: 480px) {
            .article-images {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .article-title {
                font-size: 1.3rem;
            }
            
            .day-tooltip {
                bottom: 15px;
                transform: translateX(-50%);
            }
        }
        
        /*以下为往年今日CSS 2025.11.17*/
        .past-posts {
            margin-top: 20px;
        }

        .past-posts ul{
            padding:0 0;
        }
        
        .dark .past-posts ul{
            padding:0 0;
        }
        
        .post-list {
            list-style: none;
            padding: 20px 25px!important;
            margin: 0;
            background: #eee;
            border-radius: 10px;
            border: 1px solid #f15a22;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .dark .post-list {
            background: rgb(10 12 25 /1);
            transition: all 0.3s ease;
        }

        .post-item {
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        
        .dark .post-item a{
            color:rgb(156 163 175 /1)!important;
            transition: color 0.3s ease;
        }

        .post-item:last-child {
            border-bottom: none;
        }

        .post-date {
            color: #666;
            font-size: 1.0em;
            min-width: 120px;
            margin-right: 20px;
            transition: color 0.3s ease;
        }

        .dark .post-date{
            color:rgb(156 163 175 /1);
            transition: color 0.3s ease;
        }

        .post-link {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
            flex: 1;
            font-size: 1.0em;
        }

        .post-link:hover {
            color: #007cba;
            text-decoration: none;
        }

        .alert {
            padding: 30px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            text-align: center;
            color: #666;
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .post-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 12px 15px;
            }
            
            .post-date {
                min-width: auto;
                margin-right: 0;
                margin-bottom: 5px;
                font-size: 0.85em;
            }
            
            .post-link {
                font-size: 1em;
            }
            
            .container {
                padding: 0 15px;
            }
        }
        @media (min-width: 1024px) {
    .jasmine-body {
        padding-left: 0px!important;
        padding-right: 0px!important;

    }
}
    </style>
    <?php $this->need("header.php"); ?>
</head>
<?php $this->need('assets/lantern.html'); ?>
<body class="jasmine-body" style="margin-top:4rem;">
<div class="jasmine-container grid grid-cols-12">
    <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
        <?php $this->need("component/menu.php"); ?>
        <div class="flex flex-col gap-y-12">
            <div></div>
            <div class="markdown-body dark:!bg-[#161829] dark:!bg-[#0d1117] !text-neutral-900 dark:!text-gray-400" itemprop="articleBody">
                <?php echo handleContent($this->content); ?>
                
                <!-- 热力图部分 -->
                <div class="heatmap-container">
                    <div class="heatmap-content">
                        <div class="heatmap">
                            <div class="month-labels">
                                
                                <?php foreach ($monthLabels as $monthData): ?>
                                    <?php $position = $monthData['position']; ?>
                                    <?php $left = ($position / $totalDays) * 100; ?>
                                    <div class="month-label" style="left: <?php echo $left; ?>%;">
                                        <?php echo $monthData['name']; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="calendar">
                                <div class="day-labels">
                                    <div class="day-label">一</div>
                                    <div class="day-label">二</div>
                                    <div class="day-label">三</div>
                                    <div class="day-label">四</div>
                                    <div class="day-label">五</div>
                                    <div class="day-label">六</div>
                                    <div class="day-label">日</div>
                                </div>
                                
                                <div class="weeks">
                                    <?php
                                    // 生成最近6个月的日历视图
                                    $currentDate = clone $startDate;
                                    
                                    for ($week = 0; $week < $totalWeeks; $week++): ?>
                                        <div class="week">
                                            <?php for ($day = 0; $day < 7; $day++): ?>
                                                <?php
                                                $currentDayIndex = $week * 7 + $day - $offset;
                                                $tempDate = clone $startDate;
                                                
                                                if ($currentDayIndex >= 0 && $currentDayIndex < $totalDays):
                                                    $tempDate->modify("+$currentDayIndex days");
                                                    $dateString = $tempDate->format('Y-m-d');
                                                    $dayData = isset($heatmapData[$dateString]) ? $heatmapData[$dateString] : ['count' => 0, 'articles' => []];
                                                    $count = $dayData['count'];
                                                    
                                                    // 确定颜色等级
                                                    $activityClass = 'activity-low';
                                                    if ($count === 1) $activityClass = 'activity-medium';
                                                    else if ($count === 2) $activityClass = 'activity-high';
                                                    else if ($count === 3) $activityClass = 'activity-higher';
                                                    else if ($count >= 4) $activityClass = 'activity-highest';
                                                    ?>
                                                    
                                                    <div class="day <?php echo $activityClass; ?>">
                                                        <?php if ($count > 0): ?>
                                                            <div class="day-tooltip">
                                                                <div class="tooltip-date">
                                                                    <?php echo $tempDate->format('Y年m月d日'); ?> (<?php echo $count; ?>篇)
                                                                </div>
                                                                <div class="tooltip-articles">
                                                                    <?php foreach ($dayData['articles'] as $article): ?>
                                                                        <div class="tooltip-article">
                                                                            <a href="<?php echo $article['url']; ?>" target="_blank">
                                                                                <?php echo htmlspecialchars($article['title']); ?>
                                                                            </a>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="day activity-low" style="opacity:0.3"></div>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             
                
                <!-- 文章列表部分 -->
                <div class="container" style="margin-top:-5px;">
                    <div class="articles-list">
                        <?php 
                        // 查询最新10篇文章
                        $this->widget('Widget_Contents_Post_Recent', 'pageSize=5')->to($posts); 
                        ?>
                        
                        <?php while($posts->next()): ?>
                            <?php
                            // 修复日期计算
                            $postTime = $posts->created;
                            $todayStart = strtotime('today');
                            $yesterdayStart = strtotime('yesterday');
                            
                            if ($postTime >= $todayStart) {
                                $timeText = '今天';
                            } elseif ($postTime >= $yesterdayStart) {
                                $timeText = '昨天';
                            } else {
                                $daysAgo = floor(($todayStart - $postTime) / (60 * 60 * 24));
                                if ($daysAgo < 7) {
                                    $timeText = $daysAgo . '天前';
                                } elseif ($daysAgo < 30) {
                                    $timeText = floor($daysAgo / 7) . '周前';
                                } else {
                                    $timeText = floor($daysAgo / 30) . '月前';
                                }
                            }
                            
                            $postDate = date('Y-m-d', $postTime);
                            
                            // 获取文章分类信息 - 使用更可靠的方法
                            $categoryName = '';
                            $categoryUrl = '';
                            $showImages = false;
                            
                            // 方法1：直接通过posts对象获取分类
                            if ($posts->categories) {
                                $categories = $posts->categories;
                                if (!empty($categories)) {
                                    $category = current($categories); // 获取第一个分类
                                    $categoryName = $category['name'];
                                    $categoryUrl = $category['permalink'];
                                    $showImages = in_array($categoryName, array('户外', '爸爸厨房', '探店'));
                                }
                            }
                            
                            // 方法2：如果方法1失败，使用备用方法
                            if (empty($categoryName)) {
                                ob_start();
                                $posts->category();
                                $categoryOutput = ob_get_clean();
                                
                                if (!empty($categoryOutput)) {
                                    $categoryName = strip_tags($categoryOutput);
                                    $showImages = in_array($categoryName, array('户外', '爸爸厨房', '探店'));
                                    
                                    // 尝试获取分类链接
                                    $db = Typecho_Db::get();
                                    $categoryRow = $db->fetchRow($db->select('mid')
                                        ->from('table.metas')
                                        ->where('name = ?', $categoryName)
                                        ->where('type = ?', 'category'));
                                    
                                    if ($categoryRow) {
                                        $categoryWidget = Typecho_Widget::widget('Widget_Metas_Category_List');
                                        while ($categoryWidget->next()) {
                                            if ($categoryWidget->mid == $categoryRow['mid']) {
                                                $categoryUrl = $categoryWidget->permalink;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                            
                            <article class="article-card">
                                <h2 class="article-title">
                                    <a href="<?php $posts->permalink(); ?>" target="_blank"><?php $posts->title(); ?></a>
                                </h2>
                                
                                <div class="article-excerpt">
                                    <?php 
                                    // 检查是否有自定义description字段
                                    $customDescription = $posts->fields->description;
                                    if (!empty($customDescription)) {
                                        // 如果有自定义描述，直接显示
                                        echo Typecho_Common::subStr(strip_tags($customDescription), 0, 150, '...');
                                    } else {
                                        // 如果没有自定义描述，按现有方式截取
                                        if ($posts->excerpt) {
                                            echo Typecho_Common::subStr(strip_tags($posts->excerpt), 0, 150, '...');
                                        } else {
                                            echo Typecho_Common::subStr(strip_tags($posts->content), 0, 150, '...');
                                        }
                                    }
                                    ?>
                                </div>
                                
                                <?php if ($showImages): ?>
                                    <?php
                                    $content = $posts->content;
                                    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);
                                    $images = $matches[1] ?? [];
                                    $displayImages = array_slice($images, 0, 4);
                                    ?>
                                    
                                    <?php if (!empty($displayImages)): ?>
                                        <div class="article-images">
                                            <?php foreach ($displayImages as $image): ?>
                                                <img src="<?php echo $image; ?>" alt="文章图片" class="article-image">
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php
                                // 获取文章的最新3条评论
                                $db = Typecho_Db::get();
                                $user = Typecho_Widget::widget('Widget_User');
                                $adminName = $user->name;
                                $adminMail = $user->mail;
                                
                                $comments = $db->fetchAll($db->select('author', 'text', 'created', 'parent', 'mail')
                                    ->from('table.comments')
                                    ->where('cid = ?', $posts->cid)
                                    ->where('status = ?', 'approved')
                                    ->where('author != ?', $adminName)
                                    ->where('mail != ?', $adminMail)
                                    ->where('parent = ?', 0)
                                    ->order('created', Typecho_Db::SORT_DESC)
                                    ->limit(10));
                                
                                $processedComments = array();
                                foreach ($comments as $comment) {
                                    $commentAuthor = htmlspecialchars($comment['author']);
                                    $commentText = Typecho_Common::subStr(strip_tags($comment['text']), 0, 80, '...');
                                    
                                    $processedComments[] = array(
                                        'author' => $commentAuthor,
                                        'text' => $commentText
                                    );
                                }
                                ?>
                                
                                <div class="comments-section">
                                    <div class="article-meta" style="margin:10px 0px 10px;">
                                       
                                        <span class="article-time"><?php echo $timeText; ?></span>
                                        <span class="article-date"><?php echo $postDate; ?></span>
                                         <?php if ($categoryName && $categoryUrl): ?>
                                            <a href="<?php echo $categoryUrl; ?>" class="article-category" target="_blank">
                                                <?php echo $categoryName; ?>
                                            </a>
                                        <?php elseif ($categoryName): ?>
                                            <span class="article-category" style="background: #e74c3c; color: white; padding: 2px 4px; border-radius: 5px; font-size: 0.75rem;">
                                                <?php echo $categoryName; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($processedComments)): ?>
                                    <div class="scm">
                                        <?php foreach ($processedComments as $comment): ?>
                                            <a href="<?php $posts->permalink(); ?>#comments" class="comment-link" target="_blank">
                                                <div class="comment-item">
                                                    <span class="comment-author"><?php echo $comment['author']; ?></span>
                                                    ：<span class="comment-content"><?php echo $comment['text']; ?></span>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
           
                <!---往年今日开始-->
               <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="past-posts">
                <?php if ($past_posts): ?>
                    <ul class="post-list">
                        <?php foreach ($past_posts as $post): ?>
                        <li class="post-item">
                            <span class="post-date"><?php echo date('Y年m月d日', $post['created']); ?></span>
                            <a href="<?php echo Typecho_Router::url('post', $post); ?>" target="_blank" class="post-link">
                                <?php echo $post['title']; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info">
                        <p>暂无往年今日的文章，今天是个开始新篇章的好日子！</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!---往年今日结束-->
	<?php $this->need("footer.php"); ?>
    </div>
</div>
</body>
</html>