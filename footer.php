<?php
// 处理AI摘要保存
if (isset($_POST['save_ai_summary']) && $_POST['save_ai_summary'] == '1') {
    $postId = intval($_POST['post_id']);
    $summary = trim($_POST['summary_content']);
    
    if ($postId > 0 && !empty($summary)) {
        $db = Typecho_Db::get();
        
        // 检查字段是否存在
        $field = $db->fetchRow($db->select()
            ->from('table.fields')
            ->where('cid = ?', $postId)
            ->where('name = ?', 'description'));
        
        if ($field) {
            // 更新字段
            $db->query($db->update('table.fields')
                ->rows(array('str_value' => $summary))
                ->where('cid = ?', $postId)
                ->where('name = ?', 'description'));
        } else {
            // 插入新字段
            $db->query($db->insert('table.fields')
                ->rows(array(
                    'cid' => $postId,
                    'name' => 'description',
                    'type' => 'str',
                    'str_value' => $summary,
                    'int_value' => 0
                )));
        }
        
        // 注意：这里不重定向，因为前端使用AJAX
    }
}
?>
<?php if (!defined("__TYPECHO_ROOT_DIR__")) {exit();} ?>

<?php $this->footer(); ?>

<div class="flex grow flex-col justify-between top">
    <ul class="flex flex-col flex-wrap content-center gap-y-2 ">
        <li class="relative nav-li">
            <button onclick="jasmine.switchDark()" title="日夜模式" id="theme-toggle">
                <iconify-icon id="theme-icon"
                              class="rounded px-2 py-1 text-2xl jasmine-primary-bg-hover btop"></iconify-icon>
            </button>
        </li>
        
       <?php if (class_exists('TocPlugin_Plugin')): ?>
<?php echo TocPlugin_Plugin::outputNavigationButtons(); ?>
<?php endif; ?>
    </ul>
</div>

<script>
    <?php $this->options->customScript(); ?>
</script>
<!-- OwO 表情 -->
<script src="<?php $this->options->themeUrl('/owo/OwO.min.js'); ?>"></script>
<!--图片灯箱JS 2024.2.3-->
<script src="https://qcm.xgsd.cc/js/view-image.min.js"></script>
<script>window.ViewImage && ViewImage.init('.markdown-body img');</script>
<script>
    <?php $this->options->customScript(); ?>
</script>

<?php if($this->fields->zhaiyao):?>
<?php if ($this->is("post")): ?>
<link rel="stylesheet" href="https://www.shitoucuo.com/usr/themes/sagrre/assets/dist/tianli_gpt.css">
<script>
let tianliGPT_wordLimit = 5000;
let tianliGPT_postSelector = '.markdown-body';
let tianliGPT_key = 'b1b2e6754734b2ad1e1b64e75b1c3956eb15';

// 标记是否已保存，防止重复保存
let aiSummarySaved = false;

// 获取完整的AI摘要内容
function getFullAISummary() {
    const element = document.querySelector('.tianliGPT-explanation');
    if (element && element.textContent && element.textContent.length > 20) {
        return element.textContent.trim();
    }
    return null;
}

// 保存到字段（使用AJAX，不刷新页面）
function saveToDescription(postId, summary) {
    if (aiSummarySaved) return;
    
    console.log('保存AI摘要，长度:', summary.length);
    
    // 使用FormData发送请求
    const formData = new FormData();
    formData.append('save_ai_summary', '1');
    formData.append('post_id', postId);
    formData.append('summary_content', summary);
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        console.log('AI摘要保存成功');
        aiSummarySaved = true;
    })
    .catch(error => {
        console.log('保存完成');
    });
}

// 检查并保存AI摘要
function checkAndSaveAISummary() {
    if (aiSummarySaved) return true;
    
    const postId = <?php echo $this->cid; ?>;
    const summary = getFullAISummary();
    
    if (summary && summary.length > 50) { // 确保是完整的摘要
        console.log('找到完整AI摘要，长度:', summary.length);
        saveToDescription(postId, summary);
        return true;
    }
    
    return false;
}

// 智能监听AI摘要生成
function initAISummarySaver() {
    // 先等待一段时间，让AI摘要完全生成
    setTimeout(() => {
        // 第一次检查
        if (!checkAndSaveAISummary()) {
            // 设置一个较长的检查间隔，避免频繁检查
            let checkCount = 0;
            const maxChecks = 5;
            
            const checkInterval = setInterval(() => {
                checkCount++;
                if (checkAndSaveAISummary() || checkCount >= maxChecks) {
                    clearInterval(checkInterval);
                }
            }, 3000); // 每3秒检查一次
        }
    }, 8000); // 等待8秒后开始检查，给AI摘要足够时间生成

    // 监听DOM变化，但只在AI摘要区域变化时处理
    const observer = new MutationObserver((mutations) => {
        if (aiSummarySaved) {
            observer.disconnect();
            return;
        }
        
        for (let mutation of mutations) {
            for (let node of mutation.addedNodes) {
                if (node.nodeType === 1) {
                    // 只有当新增的元素包含AI摘要时才处理
                    if (node.classList && node.classList.contains('tianliGPT-explanation')) {
                        console.log('检测到AI摘要元素变化');
                        setTimeout(() => checkAndSaveAISummary(), 1000);
                    }
                }
            }
        }
    });
    
    // 只观察AI摘要区域的变化
    const aiContainer = document.querySelector('.tianli-gpt');
    if (aiContainer) {
        observer.observe(aiContainer, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }
}

// 页面加载完成后启动
document.addEventListener('DOMContentLoaded', function() {
    // 延迟启动，避免干扰AI摘要的正常生成
    setTimeout(initAISummarySaver, 2000);
});

// 如果页面已经加载完成
if (document.readyState === 'complete') {
    setTimeout(initAISummarySaver, 2000);
}
</script>
<script src="https://www.shitoucuo.com/usr/themes/sagrre/assets/dist/tianli_gpt.min.js"></script>
<?php endif; ?>
<?php endif; ?>
<script>

/*评论表情配置*/
var OwO_demo = new OwO({
            
            target: document.getElementsByClassName('OwO-textarea')[0],
            api: '/usr/themes/sagrre/owo/OwO.json',
            position: 'down',
            width: '66vw',
            maxHeight: '250px'
});
</script>
