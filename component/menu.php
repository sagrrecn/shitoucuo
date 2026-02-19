<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>


<div id="header-menu" class="jasmine-primary-color hidden lg:block sticky top-0 border-b  bt lg:py-5 bg-[#ffffffe6] dark:bg-[rgba(22,24,41,0.9)]  dark:border-neutral-600 backdrop-blur">
    <div id="header-menu-wrap" class="flex justify-between hidden lg:flex z-50">
        <ul class="nav flex items-center gap-x-3">
            <li>
                <a title="首页" href="<?php $this->options->siteUrl(); ?>"
                class="rounded-full px-4 py-2 jasmine-primary-bg-hover cat-hover hover:text-white hover:shadow-lg">首页</a>
            </li>
            <?php $this->widget("Jasmine_Meta_Row")->to($categorys); ?>
            <?php if ($categorys->have()): ?>
                <?php while ($categorys->next()): ?>
                    <li>
                        <a href="<?php $categorys->permalink(); ?>"
                        title="<?php $categorys->name(); ?>"
                        class="rounded-full px-4 py-2 jasmine-primary-bg-hover cat-hover hover:text-white hover:shadow-lg">
                            <?php $categorys->name(); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            <?php endif; ?>
               <!--<li><a href="https://www.shitoucuo.com/memos" class="rounded-full px-4 py-2 cat-hover jasmine-primary-bg-hover hover:text-white hover:shadow-lg">灵感</a></li>     <meta itemprop="url" content="<?php $this->options->siteUrl(); ?>">-->
                
               
            <!--<li><a href="https://www.shitoucuo.com/category-tushuo.html" class="rounded-full px-4 py-2 jasmine-primary-bg-hover hover:text-white hover:shadow-lg">图说</a></li>-->
             <!--<li><a href="https://www.shitoucuo.com/movies.html" class="rounded-full px-4 py-2 jasmine-primary-bg-hover hover:text-white hover:shadow-lg">观影</a></li>
              <li><a href="https://www.shitoucuo.com/books.html" class="rounded-full px-4 py-2 jasmine-primary-bg-hover hover:text-white hover:shadow-lg">阅读</a></li>-->
                    <!--<li><a href="https://www.shitoucuo.com/mailbox.html" class="rounded-full px-4 py-2 jasmine-primary-bg-hover hover:text-white hover:shadow-lg">友筒</a></li>-->
                                    <!-- <li><a href="links.html" class="rounded-full px-4 py-2 cat-hover jasmine-primary-bg-hover hover:text-white hover:shadow-lg">友圈</a></li>-->
                                    
                                       <?php if (Typecho_Widget::widget('Widget_User')->hasLogin()){?> 
                                        <li><a href="https://www.shitoucuo.com/sl.html" class="rounded-full px-4 py-2 cat-hover jasmine-primary-bg-hover hover:text-white hover:shadow-lg">速览</a></li>
                                         <!--<li><a href="https://www.shitoucuo.com/rp.html" class="rounded-full px-4 py-2 cat-hover jasmine-primary-bg-hover hover:text-white hover:shadow-lg">锐评</a></li>-->
                                         <!--<li><a href="https://www.shitoucuo.com/dw.html" class="rounded-full px-4 py-2 cat-hover jasmine-primary-bg-hover hover:text-white hover:shadow-lg">短文</a></li>-->
                     <li><a href="https://www.shitoucuo.com/gd.html" class="rounded-full px-4 py-2 cat-hover jasmine-primary-bg-hover hover:text-white hover:shadow-lg">归档</a></li><?php } ?>
                                       <form method="post" action="" id="search" itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction">
                    <meta itemprop="target" content="<?php $this->options->siteUrl(); ?>search/{s}/">
                    <label for="search" class="flex flex-row">
                    <button class="my-2 cat-hover cat-search pt-2" onclick="jasmine.clickSearch()">
                        <iconify-icon icon="tabler:search"
                                    class="rounded px-1 text-lg jasmine-link-color"></iconify-icon>
                    </button>
                    <input class=" duration-300  my-2 w-0 focus:w-32 bg-transparent" itemprop="query-input" id="search-input" type="text" name="s" required="true" autocomplete="off" placeholder=" 请输入关键词">
                    </label>
                </form>
            
                     <!--<li><a href="https://www.shitoucuo.com/aboutme.html" class="rounded-full px-4 py-2 cat-hover
                      jasmine-primary-bg-hover hover:text-white hover:shadow-lg">关于</a></li>-->
                  
                   
                  <!-- <li><a href="archive.html" class="rounded-full px-4 py-2 jasmine-primary-bg-hover hover:text-white hover:shadow-lg">归档</a></li>-->
                      <!--<li><a href="https://www.shitoucuo.com/aboutme.html" class="rounded-full px-4 py-2 cat-hover
                      jasmine-primary-bg-hover hover:text-white hover:shadow-lg">关于</a></li>
                         <li itemscope="" itemtype="https://schema.org/WebSite">-->
           
        </ul>
     <!--<ul class="nav flex items-center gap-x-3">
            <li itemscope="" itemtype="https://schema.org/WebSite">
                <meta itemprop="url" content="<?php $this->options->siteUrl(); ?>">
                <form method="post" action="" id="search" itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction">
                    <meta itemprop="target" content="<?php $this->options->siteUrl(); ?>search/{s}/">
                    <label for="search" class="flex flex-row">
                    <button class="my-2 pt-2" onclick="jasmine.clickSearch()">
                        <iconify-icon icon="tabler:search"
                                    class="rounded px-1 text-lg jasmine-link-color"></iconify-icon>
                    </button>
                    <input class=" duration-300 my-2 w-0 focus:w-32 bg-transparent" itemprop="query-input" id="search-input" type="text" name="s" required="true" autocomplete="off" placeholder="Search">
                    </label>
                </form>
            </li>
        </ul>-->
    </div>  
</div>
         <div class="divline"></div>

<div id="header-menu-mobile" class="mbh jasmine-primary-color lg:hidden flex justify-between sticky top-0 border-b border-stone-100 py-3 z-50 bg-[#ffffffe6] dark:bg-[rgba(22,24,41,0.9)] dark:text-neutral-300 dark:border-neutral-600 backdrop-blur">
    <ul class="nav flex items-center gap-x-3">
        <li>
            <?php $this->need("component/logo.php"); ?>
        </li>
    </ul>
    <ul class="nav flex items-center gap-x-3">
        <li>
            <button onclick="jasmine.switchDark()">
                <iconify-icon icon="<?php echo getOptionValueOrDefault("switchDarkIconPhone", "tabler:sun-moon"); ?>"
                              class="rounded px-3 py-2 text-lg"></iconify-icon>
            </button>
        </li>
        <li>
            <form method="post" action="" id="search" itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction">
                <meta itemprop="target" content="<?php $this->options->siteUrl(); ?>search/{s}/">
                <label for="search" class="flex flex-row">
                <button class="" onclick="jasmine.clickSearch()">
                    <iconify-icon icon="tabler:search"
                                class="rounded px-3 py-2 text-lg"></iconify-icon>
                </button>
                <input class=" duration-300 my-2 w-0 focus:w-32 bg-transparent" itemprop="query-input" id="search-input" type="text" name="s" required="true" autocomplete="off" placeholder="Search">
                </label>
            </form>
        </li>
        <li>
            <button onclick="jasmine.toggleMobileMenu()">
                <iconify-icon icon="tabler:menu-2"
                              class="rounded px-3 py-2 text-lg"></iconify-icon>
            </button>
        </li>
    </ul>
</div>

<div id="mobile-menus-bg" class="lg:hidden hidden fixed top-0 left-0 z-[999] bg-gray-500/50 dark:bg-[#0a0c19]/50 w-full min-h-screen"></div>
<div id="mobile-menus" class="lg:hidden fixed top-0 left-0 z-[1000] translate-x-[-1000px] w-4/5  duration-300">
    <div class="jasmine-primary-color bg-stone-100 min-h-screen flex flex-col gap-y-14 px-5 pt-14 dark:bg-[#161829]">
        <ul class="flex flex-col items-center gap-y-3">
            <li class="bg-white rounded w-full dark:bg-gray-700 ">
                <a title="首页" href="<?php $this->options->siteUrl(); ?>"
                class="w-full block px-4 py-2">首页</a>
            </li>
            <?php $this->widget("Jasmine_Meta_Row")->to($categorys); ?>
            <?php if ($categorys->have()): ?>
                <?php while ($categorys->next()): ?>
                    <li class="bg-white rounded w-full dark:bg-gray-700 ">
                        <a href="<?php $categorys->permalink(); ?>"
                        title="<?php $categorys->name(); ?>"
                        class="w-full block px-4 py-2">
                            <?php $categorys->name(); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>
        <ul class="flex flex-col items-center gap-y-3 w-full">
            <?php $menus = getLeftSidebarMenu(); ?>
            <?php if (!empty($menus)): ?>
                <?php foreach ($menus as $menu): ?>
                    <li class="bg-white rounded w-full dark:bg-gray-700 ">
                        <a class="w-full block px-4 py-2" href="<?php echo $menu["url"]; ?>" target="<?php echo $menu[
  "newTab"
]
  ? "_blank"
  : "_self"; ?>" title="<?php echo $menu["name"]; ?>">
                            <?php echo $menu["name"]; ?>
                        </a>
                        </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
