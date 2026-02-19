<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>

<style>
.post-TianliGPT{display:none;}

</style>

<div class="flex flex-col gap-y-3" itemscope itemtype="https://schema.org/NewsArticle">
  <!-- 2023.7.28 干掉说说头像日期等 <div class="flex flex-row gap-x-3 item-center">
        <img class="rounded object-cover w-[42px] h-[42px]" width="42" height="42"
             src="<?php echo getAvatarByMail($this->author->mail, true); ?>"
             loading="lazy"
             alt="<?php $this->author->screenName(); ?>">
        <div class="flex flex-col justify-center">
            <span class=""><?php $this->author->screenName(); ?></span>
            <span class=" text-sm"><?php echo getHumanizedDate($this->created); ?></span>
        </div>
    </div>-->
    <div class="markdown-body markdown-body-list !bg-stone-100 rounded p-5 relative dark:!bg-[#0d1117] !text-neutral-900 dark:!text-neutral-200" id="moment" style="margin-top:0px;">
         <div class="dark:text-gray-400 tm-ca" style="padding-bottom:10px;">
            <span><i class="iconfont icon-shijian_o"></i> <?php echo date('Y/m/d H:i:s' , $this->created);?></span>
             </br><span><i class="iconfont icon-zuozhe"></i> <?php $this->author() ?></span>
        </div>
         <?php echo handleContent($this->content);?>
            <div class="btt"> <a href="<?php $this->permalink(); ?>" title="<?php $this->title(); ?>"><?php $this->title(); ?></a></div>
    </div>
</div>
