<?php
// 获取当前文章发布时间
global $postCreated;
if (!isset($postCreated) && isset($this->widget('Widget_Archive')->created)) {
    $postCreated = $this->widget('Widget_Archive')->created;
}
?>
<style>
    /**修复评论间距 2025.12.12***/
    .py-7 {
        padding-top: .8rem;
        padding-bottom: .8rem !important;
    }
    
    .pltz {
        margin-bottom: 25px;
    }
    
    .pltj input {
        width: 14px;
        height: 14px;
        margin-bottom: -2px;
        justify-content: center; /* 水平居中 */
        align-items: center;
    }
    
    /** 评论时间悬停提示 **/
    .comment-time-tooltip {
        position: relative;
        display: block; /* 改为block实现换行 */
        margin-top: 8px; /* 添加一点上边距分隔 */
        font-size: 13px;
        color: #666;
        line-height: 1.4;
    }
    
    .dark .comment-time-tooltip {
        color: #a0a0a0;
    }
    
    /* 管理员评论 - 正常样式 */
    .comment-time-tooltip.is-admin {
        cursor: default;
    }
    
    /* 普通用户评论 - 悬停效果 */
    .comment-time-tooltip:not(.is-admin) {
        cursor: help;
    }
    
    /* 提示框容器 */
    .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 20%;
        transform: translateX(-50%);
        margin-bottom: 8px;
        z-index: 1;
        pointer-events: none;
    }
    
    /* 提示框样式 */
    .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content .tooltip-box {
        background: #1f2937;
        color: #f3f4f6;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        font-weight: normal;
        min-width: 140px;
        text-align: center;
        border: 1px solid #374151;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    
    /* 提示框箭头 */
    .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content .tooltip-arrow {
        position: absolute;
        top: 100%;
        left: 20%;
        transform: translateX(-50%);
        border-width: 6px;
        border-style: solid;
        border-color: #1f2937 transparent transparent transparent;
    }
    
    /* 悬停时显示 */
    .comment-time-tooltip:not(.is-admin)[data-relative]:hover .tooltip-content {
        display: block;
    }
    
    /* 亮色模式适配 */
    .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content .tooltip-box {
        background: #1f2937;
        color: #f3f4f6;
        border-color: #374151;
    }
    
    .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content .tooltip-arrow {
        border-color: #1f2937 transparent transparent transparent;
    }
    
    /* 暗色模式适配 */
    .dark .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content .tooltip-box {
        background: #111827;
        color: #e5e7eb;
        border-color: #1f2937;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.35);
    }
    
    .dark .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content .tooltip-arrow {
        border-color: #111827 transparent transparent transparent;
    }
    
    /* 确保提示框不被其他元素遮挡 */
    .comment-time-tooltip:not(.is-admin)[data-relative] {
        z-index: 9999;
    }
    
    .comment-content {
        position: relative;
    }
    
    /* 防止提示框被裁剪 */
    .comment-content .comment-time-tooltip:not(.is-admin)[data-relative] .tooltip-content {
        overflow: visible;
    }
    
    /* 作者用户名样式（有链接的） */
    .author-name a {
        color: #f15a22;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .author-name a:hover {
        color: #ff6b35;
        text-decoration: underline;
    }
    
    .dark .author-name a {
        color: #f15a22;
    }
    
    .dark .author-name a:hover {
        color: #ff7c4a;
    }
    
    /* @被回复用户的链接样式（在内容前面） */
    .reply-to-link {
        color:rgb(211, 47, 47)!important;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s ease;
        display: inline;
    }
    
    .reply-to-link:hover {
        color: #ff6b35;
        text-decoration: underline;
    }
    
    .dark .reply-to-link {
        color: #f15a22;
    }
    
    .dark .reply-to-link:hover {
        color: #ff7c4a;
    }
    
    /* 确保所有内容都在一行 */
    .plq, .plq * {
        display: inline !important;
        white-space: normal !important;
    }
    
    /* 清理@提到的换行 */
    .pl .comment-at {
        display: inline !important;
        margin-right: 5px;
    }
    
    /* 楼层号样式 */
    .comment-floor {
        display: inline-block;
        margin-right: 5px;
        font-weight: bold;
        color: #dc2626;
        padding: 1px 6px;
        border-radius: 3px;
        font-size: 14px;
        line-height: 1;
        vertical-align: middle;
    }
    
    .dark .comment-floor {
        color: #dc2626;
    }
    
    /* 回复按钮样式 - 去掉楼层的右边距 */
    .comments-reply {
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>

<?php if (!defined("__TYPECHO_ROOT_DIR__")) {
    exit();
} ?>

<?php function threadedComments($comments, $options)
{
    $commentClass = "";
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= " comment-by-author";
        } else {
            $commentClass .= " comment-by-user";
        }
    }
    ?>
    
    <!--py-7 class-margin代替 2025.12.12-->
    <li id="<?php $comments->theId(); ?>" 
        class="flex flex-col gap-y-4 border-b-2 py-7 border-stone-100 dark:border-neutral-600 comment-body<?php
            if ($comments->levels > 0) {
                echo " comment-child";
                $comments->levelsAlt(" comment-level-odd", " comment-level-even");
            } else {
                echo " comment-parent";
            }
            $comments->alt(" comment-odd", " comment-even");
            echo $commentClass;
        ?>">
        
        <div class="flex w-full gap-x-2 grow plz">
            <?php if ($comments->authorId == $comments->ownerId) { ?>
                <img class="rounded w-[50px] h-[50px] object-cover aumg" 
                     width="50" 
                     height="50"
                     src="<?php echo getAvatarByMail($comments->mail, true); ?>"
                     loading="lazy"
                     alt="<?php $comments->author; ?>">
            <?php } else { ?>
                <img class="rounded w-[50px] h-[50px] object-cover aumg" 
                     width="50" 
                     height="50"
                     src="<?php echo getAvatarByMail($comments->mail); ?>"
                     loading="lazy"
                     alt="<?php $comments->author; ?>">
            <?php } ?>
            
            <div class="flex flex-col w-full">
                <div class="flex justify-between auhui">
                    <div class="whitespace-nowrap">
                        <span class="author-name">
                            <!--2023.8.5 评论博主认证及用户等级-->
                       
                            <?php
                            // 显示评论作者 - 保持橙色可跳转
                            if (class_exists('UserCard_Plugin')) {
                                // 使用极简版调用
                                echo UserCard_Plugin::render($comments);
                            } else {
                                // 备用方案 - 作者用户名橙色可跳转
                                if ($comments->url) {
                                    echo '<a href="' . $comments->url . '" rel="external nofollow" target="_blank">' . $comments->author . '</a>';
                                } else {
                                    // 如果没有网址，也显示为橙色文本
                                    echo '<span style="color:#f15a22;">' . $comments->author . '</span>';
                                }
                            }
                            ?>
                            
                            <?php if (method_exists('RecentlyActive_Plugin', 'showUserLevel')) {
                                echo RecentlyActive_Plugin::showUserLevel($comments->authorId); 
                            } ?> 
                            
                            <?php if (method_exists('RecentlyActive_Plugin', 'show')) {
                                echo '(' . RecentlyActive_Plugin::show($comments->authorId) . ')';
                            } ?>
                            
                            <?php if ($comments->authorId > 0) { ?>
                                <!-- 注册认证图标 -->
                                <!-- <span class="vc" style="margin-right:-10px;">
                                    <i title="已注册认证" class="iconfont icon-anquanbaozhang"></i>
                                </span> -->
                            <?php } else { ?>
                                <!--<span class="aue"></span>-->
                            <?php } ?>
                            
                            <?php $commentApprove = commentApprove($comments, $comments->mail); ?>
                        </span>
                        
                        <?php if ($comments->status == "waiting") { ?>
                            <span class="small dark:text-gray-300">
                                - 您的评论正等待审核！
                            </span>
                        <?php } ?>
                    </div>
                       
                            
                    <div class="comments-reply bg-black huifu text-white rounded px-2 text-sm py-1 whitespace-nowrap" 
                         data-no-instant>
                         <?php if ($comments->coid && $comments->cid): ?>
    <?php echo RecentlyActive_Plugin::showFloorNumber($comments->coid, $comments->cid); ?>
<?php endif; ?>
                        <i class="iconfont icon-huifu"></i>
                        <?php $comments->reply("回复"); ?>
                    </div>
                </div>
                
                <div class="comment-content cm dark:text-gray-400 break-all">
                    <?php 
                    // 先把 @提到 的内容移到前面
                    $commentAt = getCommentAt($comments->coid);
                    
                    // 如果是回复评论，在内容前面添加@被回复用户（可跳转）
                    $replyPrefix = '';
                    if ($comments->parent > 0) {
                        $parentAuthor = getParentCommentAuthor($comments->parent);
                        $parentUrl = getParentCommentUrl($comments->parent);
                        
                        if ($parentAuthor) {
                            // 修复@作者名重复问题：只在没有commentAt时才添加replyPrefix
                            if (!$commentAt) {
                                if ($parentUrl) {
                                    $replyPrefix = '<a href="' . $parentUrl . '" class="reply-to-link" rel="external nofollow" target="_blank">@' . $parentAuthor . '</a> ';
                                } else {
                                    $replyPrefix = '<span class="reply-to-link">@' . $parentAuthor . '</span> ';
                                }
                            }
                        }
                    }
                    
                    $cos = parseBiaoQing($comments->content); 
                    ?>
                    
                    <div class="plq">
                        <?php 
                        // 如果有commentAt就显示commentAt，否则显示replyPrefix
                        if ($commentAt) {
                            echo $commentAt . $cos; 
                        } else {
                            echo $replyPrefix . $cos; 
                        }
                        ?>
                    </div>
                    
                    <!--2023.7.30 评论显示浏览器和操作系统-->
                    <?php if (Typecho_Widget::widget('Widget_User')->hasLogin()) { ?>
                        <!-- 评论时间显示 - 这里需要换行 -->
                        <span class="comment-time-tooltip <?php echo ($comments->authorId > 0 && $comments->authorId == $comments->ownerId) ? 'is-admin' : ''; ?>"
                            <?php 
                            // 判断是否为管理员评论
                            $isAdminComment = ($comments->authorId > 0 && $comments->authorId == $comments->ownerId);
                            
                            // 如果不是管理员，添加相对时间到data属性中
                            if (!$isAdminComment && function_exists('getRelativeTime')) {
                                global $postCreated;
                                $relativeTime = getRelativeTime($comments->created, $postCreated, false);
                                if ($relativeTime) {
                                    echo ' data-relative="ID: ' . $comments->coid . ' | ' . htmlspecialchars($relativeTime) . '评论"';
                                } else {
                                    echo ' data-relative="ID: ' . $comments->coid . '"';
                                }
                            } elseif (!$isAdminComment) {
                                // 如果函数不存在，至少显示ID
                                echo ' data-relative="ID: ' . $comments->coid . '"';
                            }
                            ?>
                        >
                            <i class="iconfont icon-xiugai"></i>
                            <?php echo date('Y-m-d H:i:s', $comments->created); ?>
                            
                            <?php if (!$isAdminComment) { ?>
                                <!-- 提示框内容 -->
                                <div class="tooltip-content">
                                    <div class="tooltip-box">
                                        <?php 
                                        if (function_exists('getRelativeTime')) {
                                            global $postCreated;
                                            $relativeTime = getRelativeTime($comments->created, $postCreated, false);
                                            if ($relativeTime) {
                                                echo '本评论ID: ' . $comments->coid . ' | ' . htmlspecialchars($relativeTime) . '评论';
                                            } else {
                                                echo '本评论ID: ' . $comments->coid;
                                            }
                                        } else {
                                            echo '本评论ID: ' . $comments->coid;
                                        }
                                        ?>
                                    </div>
                                    <div class="tooltip-arrow"></div>
                                </div>
                            <?php } ?>
                        </span>
                        
                        <!-- 位置和浏览器信息 -->
                        <?php if (class_exists('XQLocation_Plugin')) { ?>
                        <span class="xq">
                            <i class="iconfont icon-didiandingwei_o"></i>
                            <?php XQLocation_Plugin::render($comments->ip); ?>
                        </span>
                        <?php } ?>
                        
                        <?php if (class_exists('XQUserAgent_Plugin')) { ?>
                        <span class="xq">
                            <i class="iconfont icon-shouji"></i>
                            <?php XQUserAgent_Plugin::render($comments->agent); ?>
                        </span>
                        <?php } ?>
                        
                        <!--<span class="xq">
                            <i class="iconfont icon-iddenglu" style="font-size:13px;"></i>
                            <?php echo $comments->coid; ?>
                        </span>-->
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <?php if ($comments->children) { ?>
            <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>

<?php 
/**
 * 获取父评论作者信息
 * @param int $parentId 父评论ID
 * @return string 父评论作者名
 */
function getParentCommentAuthor($parentId) {
    $db = Typecho_Db::get();
    try {
        $row = $db->fetchRow($db->select('author')
            ->from('table.comments')
            ->where('coid = ?', $parentId));
        
        return isset($row['author']) ? $row['author'] : null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * 获取父评论URL
 * @param int $parentId 父评论ID
 * @return string 父评论URL
 */
function getParentCommentUrl($parentId) {
    $db = Typecho_Db::get();
    try {
        $row = $db->fetchRow($db->select('url')
            ->from('table.comments')
            ->where('coid = ?', $parentId));
        
        return isset($row['url']) && !empty($row['url']) ? $row['url'] : null;
    } catch (Exception $e) {
        return null;
    }
}
?>

<?php if (Typecho_Widget::widget('Widget_User')->hasLogin()) { ?>
    <div class="">
        <div id="comments" data-no-instant>
            <?php $this->comments()->to($comments); ?>
            
            <?php if ($this->allow("comment")): ?>
                <div id="<?php $this->respondId(); ?>" class="respond">
                    <div class="pltz">
                        <form method="post" 
                              action="<?php $this->commentUrl(); ?>" 
                              id="comment-form" 
                              role="form"
                              class="flex flex-col gap-y-2"
                              data-no-instant>
                              
                            <?php if (!$this->user->hasLogin()): ?>
                                <div class="flex flex-row md:flex-nowrap flex-wrap w-full gap-x-2 gap-y-2 grid grid-cols-3">
                                    <input name="author" 
                                           type="text"
                                           class="dark:!bg-[#0d1117] dark:border-black dark:!text-gray-400 col-span-3 md:col-span-1 border-[#ced4da] border rounded px-2 py-2"
                                           placeholder="昵称" 
                                           required
                                           value="<?php $this->remember("author"); ?>"
                                           required/>
                                    
                                    <input name="mail" 
                                           type="email"
                                           class="dark:!bg-[#0d1117] dark:border-black dark:!text-gray-400 col-span-3 md:col-span-1 border-[#ced4da] border rounded px-2 py-2"
                                           placeholder="邮箱" 
                                           required
                                           value="<?php $this->remember("mail"); ?>"
                                           <?php if ($this->options->commentsRequireMail): ?> required <?php endif; ?>/>
                                    
                                    <input type="url" 
                                           name="url" 
                                           id="url"
                                           class="dark:!bg-[#0d1117] dark:border-black dark:!text-gray-400 col-span-3 md:col-span-1 border-[#ced4da] border rounded px-2 py-2"
                                           placeholder="网址"
                                           value="<?php $this->remember("url"); ?>"
                                           <?php if ($this->options->commentsRequireURL): ?> required <?php endif; ?> />
                                    
                                    <?php $security = $this->widget("Widget_Security"); ?>
                                    <input type="hidden" 
                                           name="_"
                                           value="<?php echo $security->getToken($this->request->getReferer()); ?>"/>
                                </div>
                            <?php endif; ?>
                            
                            <div class="basis-full">
                                <textarea rows="6" 
                                          cols="40" 
                                          name="text" 
                                          id="textarea"
                                          class="w-full border-[#ced4da] dark:!text-gray-400 border rounded px-2 py-2 dark:!bg-[#0d1117] dark:border-black OwO-textarea ys002"
                                          required
                                          placeholder="第一次评论请填写真实邮箱和个人网址，邮箱用于接收评论审核、回复通知邮件,网址会链接到用户名，方便夕格和其他友友访问你的网站。有任何问题随时给夕格发邮件：shitoucuo@126.com。"><?php $this->remember("text"); ?></textarea>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="javascript:void(0);" 
                                   class="OwO-logo rounded" 
                                   style="background-color:#f15a22;padding:5px 20px;color:#fff;" 
                                   rel="external nofollow">
                                    <i class="mdi mdi-emoticon-wink-outline"></i>OωO
                                </a>
                                
                                <div class="pltj">
                                    <?php if (class_exists('ThoughtsPlugin') && ThoughtsPlugin::isAdmin()): ?>
                                        <input type="checkbox" name="thoughts" value="1">
                                        <label style="font-size:15px;">
                                            <?php echo Typecho_Widget::widget('Widget_Options')->plugin('ThoughtsPlugin')->label_text ?: '感想'; ?>
                                        </label>
                                    <?php endif; ?>
                                    
                                    <?php $comments->cancelReply(); ?>
                                    
                                    <button type="submit"
                                            class="bg-black text-white rounded px-2 py-1 ml-2"
                                            style="background-color:#f15a22;padding:5px 20px;">
                                        <?php _e("发布"); ?>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="OwO"></div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($comments->have()): ?>
                <?php $comments->listComments(); ?>
                <?php $comments->pageNav("上一页", "下一页", 0, ".."); ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>

<script>
    window.TypechoComment = {
        dom: function (id) {
            return document.getElementById(id);
        },
        
        create: function (tag, attr) {
            var el = document.createElement(tag);
            for (var key in attr) {
                el.setAttribute(key, attr[key]);
            }
            return el;
        },
        
        reply: function (cid, coid) {
            console.log(cid);
            var comment = this.dom(cid),
                response = this.dom("<?php $this->respondId(); ?>"),
                input = this.dom("comment-parent"),
                form = "form" == response.tagName ? response : response.getElementsByTagName("form")[0],
                textarea = response.getElementsByTagName("textarea")[0];
            
            if (null == input) {
                input = this.create("input", {
                    "type": "hidden",
                    "name": "parent",
                    "id": "comment-parent"
                });
                form.appendChild(input);
            }
            
            input.setAttribute("value", coid);
            console.log(form);
            
            if (null == this.dom("comment-form-place-holder")) {
                var holder = this.create("div", {
                    "id": "comment-form-place-holder"
                });
                response.parentNode.insertBefore(holder, response);
            }
            
            comment.appendChild(response);
            this.dom("cancel-comment-reply-link").style.display = "";
            this.dom("cancel-comment-reply-link").className += "btn btn-dark btn-sm";
            
            if (null != textarea && "text" == textarea.name) {
                textarea.focus();
            }
            
            return false;
        },
        
        cancelReply: function () {
            var response = this.dom("<?php $this->respondId(); ?>"),
                holder = this.dom("comment-form-place-holder"),
                input = this.dom("comment-parent");
            
            if (null != input) {
                input.parentNode.removeChild(input);
            }
            
            if (null == holder) {
                return true;
            }
            
            this.dom("cancel-comment-reply-link").style.display = "none";
            holder.parentNode.insertBefore(response, holder);
            
            return false;
        }
    };
</script>