<?php
/**
 * 主页
 * @package custom
 **/
if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} 

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
?>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<style>
/* 只添加必要的过渡效果，避免闪烁 */
body, .jasmine-container, .top, .tepasspost, #bber-talk {
    transition: background-color 0.3s ease, color 0.3s ease;
}
label.flex.flex-row{height:54px;width:56px!important;margin-bottom:-20px!important;}
.cat-search{padding: .4rem 1rem .3rem!important;border-radius: 9999px;}

/* 添加一个加载状态样式 */
#bber-talk.loading {
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
#bber-talk .loading-text {
    opacity: 0.7;
    font-size: 0.9em;
}
</style>
<?php $this->need('assets/lantern.html'); ?>
<body class="jasmine-body" style="margin-top:5rem;">
<div class="jasmine-container grid grid-cols-12">
    <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
       <?php if (Typecho_Widget::widget('Widget_User')->hasLogin()){?> 
                     <?php $this->need("component/menu.php"); ?>
            <?php $this->need("component/post-top.php"); ?>
      <div id="bber-talk" class="loading">
          <!-- 加载中的占位符，数据加载完成后会被替换 -->
          <div class="loading-text">加载中...</div>
      </div>  
                        <?php } ?>
                          <?php if (!Typecho_Widget::widget('Widget_User')->hasLogin()){?> 
                    <div class="tepasspost">
<div class="tp" style="margin:140px auto;">
	<span class="tips"><i class="iconfont icon-suo"></i></span>
	<span class="tips">已进入私人领地，请登录再浏览阅读<!--，石头厝仅接受主动邀请（免费）和订阅(付费)两种方式招募会员，付费订阅请先阅读政策：<a href="https://www.shitoucuo.com/dingyue.html" style="text-decoration:underline;color: #f15a22;"  target="_blank">成为会员</a></strong>-->
	<div class="tips"><a class="btn" target="_blank" href="/xige">登录</a> </div>
	</div>
</div>
                       <?php } ?>
    </div>
</div>

<div class="flex grow flex-col justify-between top" style="top:22em;">
    <ul class="flex flex-col flex-wrap content-center gap-y-2 ">
        <li class="relative nav-li">
            <button onclick="jasmine.switchDark()" title="日夜模式">
                <iconify-icon icon="<?php echo getOptionValueOrDefault("switchDarkIconPhone", "tabler:sun-moon"); ?>"
                              class="rounded px-2 py-1 text-2xl jasmine-primary-bg-hover btop"></iconify-icon>
            </button>
            
        </li>
    </ul>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  var bbUrl = "https://me.sangushui.com/api/v1/memo?creatorId=1&rowStatus=NORMAL&limit=6";
  var bbDom = document.querySelector('#bber-talk');
  
  // 使用 async/await 简化代码
  async function loadBberTalk() {
    try {
      const response = await fetch(bbUrl);
      const resdata = await response.json();
      const data = resdata.slice(0, 5); // 只取前5条数据
      
      let result = '';
      
      for (let i = 0; i < data.length; i++) {
        const bbTime = new Date(data[i].createdTs * 1000).toLocaleString();
        let bbCont = data[i].content;
        const newbbCont = bbCont
          .replace(/!\[.*?\]\((.*?)\)/g, ' <a href="$1" target="_blank">🌅</a> ')
          .replace(/\[(.*?)\]\((.*?)\)/g, ' <a href="$2" target="_blank">$1 🔗</a> ');
        result += `<li class="item" style="text-align:center;"><span class="datetime">${bbTime} </span>${newbbCont}</li>`;
      }
      
      // 构建完整的HTML结构
      const bbBefore = `<i class="iconfont icon-tengxunweibo"></i><ul class="talk-list" onclick="window.open('https://me.xgsd.cc/u/xige', '_blank')">`;
      const bbAfter = `</ul><i class="iconfont icon-fangxiang_round"></i>`;
      const resultAll = bbBefore + result + bbAfter;
      
      // 一次性替换整个容器内容
      bbDom.innerHTML = resultAll;
      bbDom.classList.remove('loading'); // 移除加载样式
      
      // 循环滚动 - 修复版本
      const startScroll = () => {
        const list = document.querySelector(".talk-list");
        if (!list) return;
        
        // 检查是否有足够的项目进行滚动
        if (list.children.length < 2) return;
        
        // 使用setInterval持续滚动
        setInterval(() => {
          // 每次重新获取第一个子元素
          const firstItem = list.firstElementChild;
          if (firstItem) {
            // 将第一个元素移动到列表末尾
            list.appendChild(firstItem);
          }
        }, 2000);
      };
      
      // 确保DOM已更新后开始滚动
      setTimeout(startScroll, 0);
      
    } catch (error) {
      console.error('加载说说数据失败:', error);
      bbDom.innerHTML = '<div class="error-text">加载失败，请刷新页面</div>';
      bbDom.classList.remove('loading');
    }
  }
  
  // 开始加载数据
  loadBberTalk();
});
</script>
</body>
</html>