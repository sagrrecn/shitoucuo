<?php
/**
 * 关于
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
                            
                     <?php echo handleContent($this->content);?>

              
                </div>
				</div>
			</div>
		</div>

<?php if ($this->allow("comment")): ?>     
<div class="jasmine-container grid grid-cols-12 jlq" >
     <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
                <div class="pl" style="padding-top:3rem;">
             
                    <div class="comment_ding">
                  <h2>交流区</h2>
                   </div>  
                <?php if (!Typecho_Widget::widget('Widget_User')->hasLogin()){?>

<div class="tepasspostpl">
    <div class="tppl">
	<span class="tipspl"><i class="iconfont icon-zhucehuiyuan"></i></span>
	<span class="tipspl mb">已开启深度、隐私交流模式，愿意参与朋友欢迎注册登录</span>
		<span class="tipspl">已有 <a  style="border-radius:999999px;color:#FF6600;"><<?php $this->commentsNum()?>> </a>条评论，登录参与互动</span>
		<span class="tipspl mb">注册登录遇问题请发邮件到sagrre_cn@126.com，老朋友请注明曾用评论昵称、邮箱，夕格帮您查询或注册初始账户</span>
	<div class="tipspl"><a class="btn" target="_blank" href="/tepass/signup">注册</a> <a class="btn" href="/tepass/signin">登录</a> 
	</div>
	</div>
</div>   
<?php } ?>
<?php if ($this->user->hasLogin()): ?>
<div class="tepasspostpl">
  <div class="tppl">
    <span class="tipspl">  <img class="img-thumbnail rounded tx me-1" width="50" height="50"src="<?php echo getAvatarByMail($this->user->mail, true); ?>"loading="lazy"alt="<?php $this->user->screenName(); ?>"></i></span>
	<span class="tipspl"><?php $this->user->screenName(); ?></span>
	<span class="tipspl">欢迎您，您已成功登录，祝阅读愉快</span>
	<div class="tipspl">
	 <a class="btn" href="<?php $this->options->logoutUrl(); ?>"title="退出登录">退出登录</a> 
	 <a class="btn "target="_blank" href="https://www.xigeshudong.com/xige/">个人中心</a>
	</div>
	</div>
</div> 

                        </div>
                    <?php $this->need("comments.php"); ?>
 <?php endif; ?>                     
                </div></div></div><?php endif; ?>         	
		<?php $this->need("footer.php"); ?>
</div>

</body>
</html>
