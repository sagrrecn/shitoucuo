<?php if(!defined("__TYPECHO_ROOT_DIR__")){exit();}
error_reporting(E_ERROR);
require_once"core/index.php";
/**
 * 初始化主题
 * @param $archive
 * @return void
 */
function themeInit($archive){
  Helper::options()->commentsMaxNestingLevels=999;
  Helper::options()->commentsOrder="DESC";
}
/**
 * 文章与独立页自定义字段
 */
function themeFields(Typecho_Widget_Helper_Layout $layout){
$zhaiyao=new Typecho_Widget_Helper_Form_Element_Radio('zhaiyao',array('0'=>_t('不启用'),'1'=>_t('启用')),'0',_t('智能摘要'),_t("请选择是否启用AI摘要，默认不启用"));
$layout->addItem($zhaiyao);
$banner=new Typecho_Widget_Helper_Form_Element_Textarea("thumbnail",null,null,_t("自定封面"),_t("输入一个图片 url，作为缩略图显示在文章列表，没有则自动从文章附件获取"));
$banner->input->setAttribute('style','width:100%;');
$banner->input->setAttribute('placeholder','请输入图片地址...');
$layout->addItem($banner);
$description=new Typecho_Widget_Helper_Form_Element_Textarea("description",null,null,_t("自定摘要"),_t("输入文章摘要，则显示自定义摘要。如果留空，将自动从内容中截取"));
$description->input->setAttribute('style','width:100%;height:100px;');
$description->input->setAttribute('placeholder','请输入文章摘要...');
$layout->addItem($description);
   // 🔧 关键修复：添加编辑摘要字段
    if (class_exists('EditHistory_Plugin')) {
        EditHistory_Plugin::addFieldToLayout($layout);
    }
    
    // 在themeFields函数中添加：
if (class_exists('ArticleWeather_Plugin')) {
    ArticleWeather_Plugin::addFieldToLayout($layout);
}
}
$custom_functions=__DIR__."/custom/functions.php";
if(file_exists($custom_functions)){
  include_once$custom_functions;
}
//读者墙 注册会员数排序 2023.8.4 随机排序
function getusers($limit=24,$masterEmail='zhaowenyangld@126.com'){
    $db=Typecho_Db::get();
    $sql=$db->select('COUNT(screenName) AS cnt','screenName','url','mail')->from('table.users')->where('mail != ?',$masterEmail)->group('mail')->order('RAND()',Typecho_Db::SORT_DESC)->limit($limit);
    $result=$db->fetchAll($sql);
    if($result){
        foreach($result as $value){
            if(!$value['url']){
                $value['url']='mailto:'.$value['mail'];
            }
            $mostactive.='<li><a target="_blank" rel="nofollow" href="'.$value['url'].'" ><img src="https://cravatar.cn/avatar/'.md5(strtolower($value['mail'])).'?s=36&d=&r=G"><em>'.$value['screenName'].'</em><strong>VIP</strong></a></li>';
        }
        echo$mostactive;
    }
}
//读者墙 按评论数排序 2023.8.4 随机排序
function getMostVisitors($limit=96,$masterEmail='zhaowenyangld@126.com'){
    $db=Typecho_Db::get();
    $sql=$db->select('COUNT(author) AS cnt','author','url','mail')->from('table.comments')->where('mail != ?',$masterEmail)->group('mail')->order('RAND()',Typecho_Db::SORT_DESC)->limit($limit);
    $result=$db->fetchAll($sql);
    if($result){
        foreach($result as $value){
            if(!$value['url']){
                $value['url']='mailto:'.$value['mail'];
            }
            $mostactive.='<li><a target="_blank" rel="nofollow" href="'.$value['url'].'" ><img src="https://cravatar.cn/avatar/'.md5(strtolower($value['mail'])).'?s=36&d=&r=G"><em>'.$value['author'].'</em><strong>+'.$value['cnt'].'</strong></a></li>';
        }
        echo$mostactive;
    }
}
//读者墙 按评论数排序 2023.8.4，2024.3.19 按评论数排序
function getMostVisitorscnt($limit=32,$masterEmail='zhaowenyangld@126.com'){
    $db=Typecho_Db::get();
    $sql=$db->select('COUNT(author) AS cnt','author','url','mail')->from('table.comments')->where('mail != ?',$masterEmail)->group('mail')->order('cnt',Typecho_Db::SORT_DESC)->limit($limit);
    $result=$db->fetchAll($sql);
    if($result){
        foreach($result as $value){
            if(!$value['url']){
                $value['url']='mailto:'.$value['mail'];
            }
            $mostactive.='<li><a target="_blank" rel="nofollow" href="'.$value['url'].'" ><img src="https://cravatar.cn/avatar/'.md5(strtolower($value['mail'])).'?s=36&d=&r=G"><em>'.$value['author'].'</em><strong>+'.$value['cnt'].'</strong></a></li>';
        }
        echo$mostactive;
    }
}
//读者墙 最近访客 2024.2.21 再次启用,有问题并不是最新的,已修复问题2024.3.19 
function getRecentVisitors($limit=24,$masterEmail='zhaowenyangld@126.com'){
    $db=Typecho_Db::get();
    $sql=$db->select('COUNT(author) AS cnt','author','url','mail')->from('table.comments')->group('mail')->where('mail != ?',$masterEmail)->limit($limit)->order('MAX(created)',Typecho_Db::SORT_DESC);
    $result=$db->fetchAll($sql);
    if($result){
        foreach($result as $value){
            if(!$value['url']){
                $value['url']='mailto:'.$value['mail'];
            }
            $count=$db->fetchRow($db->select('COUNT(*)')->from('table.comments')->where('status = ?','approved')->where('mail = ?',$value['mail']));
            $commentnum=$count['COUNT(*)'];
            $mostactive.='<li><a target="_blank" rel="nofollow" href="'.$value['url'].'"><img src="https://cravatar.cn/avatar/'.md5(strtolower($value['mail'])).'?s=36&d=&r=G"><em>'.$value['author'].'</em><strong>+'.$value['cnt'].'</strong></a></li>';
        }
        echo$mostactive;
    }
}
function getRecentVisitorschuci($limit=20,$masterEmail='zhaowenyangld@126.com'){  
    $db=Typecho_Db::get();  
    $sql=$db->select('COUNT(author) AS cnt','author','url','mail')->from('table.comments')->where('mail != ?',$masterEmail)->group('mail')->having('COUNT(*) = 1')->limit($limit)->order('MAX(created)',Typecho_Db::SORT_DESC);  
    $result=$db->fetchAll($sql);  
    if($result){  
        $mostactive='';
        foreach($result as $value){  
            if(!$value['url']){  
                $value['url']='mailto:'.$value['mail'];  
            }  
            $mostactive.='<li><a target="_blank" rel="nofollow" href="'.htmlspecialchars($value['url']).'"><img src="https://cravatar.cn/avatar/'.md5(strtolower($value['mail'])).'?s=36&d=&r=G"><em>'.htmlspecialchars($value['author']).'</em><strong>+'.$value['cnt'].'</strong></a></li>';  
        }  
        echo$mostactive;  
    }  
}
/**    
 * 评论者认证等级 + 身份    
 * 2023.8.4   
 * @author Chrison    
 * @access public    
 * @param str $email 评论者邮址    
 * @return result     
 */     
function commentApprove($widget,$email=NULL){   
    $result=array("state"=>-1,"isAuthor"=>0,"userLevel"=>'',"userDesc"=>'',"bgColor"=>'',"commentNum"=>0);
    if(empty($email))return$result;      
    $result['state']=1;
    $master=array('710062962@qq.com','zhaowenyangld@126.com');      
    if($widget->authorId==$widget->ownerId){      
        $result['isAuthor']=1;
        $result['userLevel']='<!-- <span class="vc"><a  style="margin-right:-5px;margin-left:10px;" href="https://www.shitoucuo.com/author-6.html" target="_blank"><i title ="个人主页" class="iconfont icon-huidaozhuye"></i></a></span>-->（ L10 ）';
        $result['userDesc']='很帅的博主';
        $result['bgColor']='#dd3333';
        $result['commentNum']=999;
    }else if(in_array($email,$master)){      
        $result['userLevel']='<!--<span class="vc"><a  style="margin-right:-5px;margin-left:10px;" href="https://www.shitoucuo.com/author-6.html" target="_blank"><i title ="个人主页" class="iconfont icon-huidaozhuye"></i></a></span>-->（ L10 ）';
        $result['userDesc']='相亲相爱的一家人';
        $result['bgColor']='#dd3333';
        $result['commentNum']=888;
    }else{
        $db=Typecho_Db::get();
        $commentNumSql=$db->fetchAll($db->select(array('COUNT(cid)'=>'commentNum'))->from('table.comments')->where('mail = ?',$email));
        $commentNum=$commentNumSql[0]['commentNum'];
        $linkSql=$db->fetchAll($db->select()->from('table.links')->where('user = ?',$email));
        if($commentNum==1){
            $result['userLevel']='（ L1 ）';
            $result['bgColor']='#999999';
            $userDesc='你已经向目的地迈出了第一步！';
        }else{
            if($commentNum<30&&$commentNum>1){
                $result['userLevel']='（ L2 ）';
                $result['bgColor']='#FF6600';
            }elseif($commentNum<90&&$commentNum>=30){
                $result['userLevel']='（ L3 ）';
                $result['bgColor']='#A0DAD0';
            }elseif($commentNum<270&&$commentNum>=90){
                $result['userLevel']='（ L4 ）';
                $result['bgColor']='#A0DAD0';
            }elseif($commentNum<810&&$commentNum>=270){
                $result['userLevel']='（ L5 ）';
                $result['bgColor']='#A0DAD0';
            }elseif($commentNum<1000&&$commentNum>=810){
                $result['userLevel']='（ L6 ）';
                $result['bgColor']='#A0DAD0';
            }elseif($commentNum>=1000){
                $result['userLevel']='（ L7 ）';
                $result['bgColor']='#A0DAD0';
            }
            $userDesc='你已经向目的地前进了'.$commentNum.'步！'; 
        }
        if($linkSql){
            $result['userLevel']='L1';
            $result['bgColor']='#21b9bb';
            $userDesc='🔗'.$linkSql[0]['description'].'&#10;✌️'.$userDesc;
        }
        $result['userDesc']=$userDesc;
        $result['commentNum']=$commentNum;
    } 
    return$result;
}
/**
* 判断时间区间
*
* 2023.8.8
*/
function getTimeLabel($from){
    $timeDiff=time()-$from;
    if($timeDiff<24*60*60){
        return'<span class="nwe fresh">新鲜出炉</span>';
    }elseif($timeDiff<7*24*60*60){
        return'<span class="nwe outdated">有点过时</span>';
    }else{
        return'<span class="nwe old">成年旧货</span>';
    }
}
/*
 * owo 表情 2023.8.29 https://fantao.me/7.html#comment-367
 */
function parsePaopaoBiaoqingCallback($match){
    return'<img class="biaoqing" src="https://www.shitoucuo.com/usr/themes/sagrre/owo/paopao/'.str_replace('%','',urlencode($match[1])).'_2x.png">';
}
function parseAruBiaoqingCallback($match){
    return'<img class="biaoqing" src="https://www.shitoucuo.com/usr/themes/sagrre/owo/aru/'.str_replace('%','',urlencode($match[1])).'_2x.png">';
}
function parseBiaoQing($content){
    $content=preg_replace_callback('/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is','parsePaopaoBiaoqingCallback',$content);
    $content=preg_replace_callback('/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is','parseAruBiaoqingCallback',$content);
    return$content;
}
/*输出作者发表的评论 2024.1.6*/ 
class Widget_Post_AuthorComment extends Widget_Abstract_Comments{
    public function execute(){
        global$AuthorCommentId;
        $select=$this->select()->limit($this->parameter->pageSize)->where('table.comments.status = ?','approved')->where('table.comments.authorId = ?',$AuthorCommentId)->where('table.comments.type = ?','comment')->order('table.comments.coid',Typecho_Db::SORT_DESC);
        $this->db->fetchAll($select,array($this,'push'));
    }
}
//获得读者墙（按访问时间排序）getFriendWall函数名后加了个b，以便分别调用，2024.3.19 王语双博客
function getFriendWallb(){
  $db=Typecho_Db::get();
  $sql=$db->select('COUNT(author) AS cnt','max(coid) ttt','author','url','mail')->from('table.comments')->where('status = ?','approved')->where('type = ?','comment')->where('authorId = ?','0')->where('mail != ?','80060631@qq.com')->group('author')->order('ttt',Typecho_Db::SORT_DESC)->limit('12');
$result=$db->fetchAll($sql);
if(count($result)>0){
  $maxNum=$result[0]['cnt'];
  foreach($result as$value){
  $mostactive.='<li><a target="_blank" href="'.$value['url'].'"><span class="pic" style="background: url(http://sdn.geekzu.org/avatar/'.md5(strtolower($value['mail'])).'?s=36&d=&r=G) no-repeat; "></span><em>'.$value['author'].'</em><strong>+'.$value['cnt'].'</strong><br />'.$value['url'].'</a></li>'; 
    }
    echo$mostactive;
  }
}
/*输出作者其他信息 2024.1.6*/ 
function userok($id){
    $db=Typecho_Db::get();
    $userinfo=$db->fetchRow($db->select()->from('table.users')->where('table.users.uid=?',$id));
    return$userinfo;
}

/**
 * 计算相对时间（评论相对于文章发布时间）
 * @param int $commentTimestamp 评论时间戳
 * @param int $postTimestamp 文章发布时间戳
 * @param bool $isAdmin 是否为管理员
 * @return string 格式化后的相对时间
 */
function getRelativeTime($commentTimestamp, $postTimestamp = null, $isAdmin = false) {
    // 如果是管理员，只返回空字符串
    if ($isAdmin) {
        return "";
    }
    
    // 如果没有传入文章时间，尝试从全局获取当前文章时间
    if ($postTimestamp === null) {
        global $postCreated;
        if (isset($postCreated)) {
            $postTimestamp = $postCreated;
        } else {
            // 如果无法获取文章时间，返回空
            return "";
        }
    }
    
    // 确保评论时间在文章时间之后
    if ($commentTimestamp <= $postTimestamp) {
        return "刚刚发文";
    }
    
    $diff = $commentTimestamp - $postTimestamp;
    
    // 计算时间差
    $minute = 60;
    $hour = 60 * $minute;
    $day = 24 * $hour;
    $month = 30 * $day;
    $year = 365 * $day;
    
    if ($diff < $minute) {
        return "发文" . $diff . "秒后";
    } elseif ($diff < $hour) {
        $minutes = floor($diff / $minute);
        return "发文" . $minutes . "分钟后";
    } elseif ($diff < $day) {
        $hours = floor($diff / $hour);
        return "发文" . $hours . "小时后";
    } elseif ($diff < $month) {
        $days = floor($diff / $day);
        return "发文" . $days . "天后";
    } elseif ($diff < $year) {
        $months = floor($diff / $month);
        return "发文" . $months . "月后";
    } else {
        $years = floor($diff / $year);
        return "发文" . $years . "年后";
    }
}

/**
 * 获取人性化的时间显示（包含相对时间）
 * @param int $commentTimestamp 评论时间戳
 * @param bool $isAdmin 是否为管理员
 * @return string 格式化时间
 */
function getHumanizedTimeWithRelative($commentTimestamp, $isAdmin = false) {
    // 获取文章发布时间
    global $postCreated;
    
    // 原始的时间格式
    $original = date('Y-m-d H:i:s', $commentTimestamp);
    
    // 计算相对时间
    $relative = getRelativeTime($commentTimestamp, $postCreated, $isAdmin);
    
    // 如果是管理员，不显示括号内容
    if ($isAdmin || empty($relative)) {
        return $original;
    }
    
    // 组合返回，括号前后加空格
    return $original . ' ( ' . $relative . ' )';
}

/**
 * 专门用于评论的时间显示函数
 * @param int $commentTimestamp 评论时间戳
 * @param bool $isAdmin 是否为管理员
 * @return string 格式化时间
 */
function getCommentTime($commentTimestamp, $isAdmin = false) {
    global $postCreated;
    
    if ($isAdmin) {
        // 管理员只显示详细时间
        return date('Y-m-d H:i:s', $commentTimestamp);
    } else {
        // 普通用户显示详细时间 + 相对时间
        $original = date('Y-m-d H:i:s', $commentTimestamp);
        $relative = getRelativeTime($commentTimestamp, $postCreated, false);
        return $original . ' ( ' . $relative . ' )';
    }
}

/**
 * 获取简单的相对时间（旧版兼容函数）
 * @param int $commentTimestamp 评论时间戳
 * @return string 相对时间
 */
function getSimpleRelativeTime($commentTimestamp) {
    global $postCreated;
    return getRelativeTime($commentTimestamp, $postCreated, false);
}


/**
 * 获取评论的实际序号（解决ID不连续问题）
 * @param int $coid 评论ID
 * @return int 实际序号
 */
function getActualCommentNumber($coid) {
    $db = Typecho_Db::get();
    
    // 查询已审核评论的序号
    $result = $db->fetchRow($db->select('COUNT(*) as cnt')
        ->from('table.comments')
        ->where('coid <= ?', $coid)
        ->where('status = ?', 'approved')
        ->where('type = ?', 'comment'));
    
    return $result['cnt'] ?: 0;
}

/**
 * 获取文章评论的实际序号
 * @param int $coid 评论ID
 * @param int $cid 文章ID
 * @return int 文章内序号
 */
function getPostCommentNumber($coid, $cid) {
    $db = Typecho_Db::get();
    
    // 查询当前文章已审核评论的序号
    $result = $db->fetchRow($db->select('COUNT(*) as cnt')
        ->from('table.comments')
        ->where('cid = ?', $cid)
        ->where('coid <= ?', $coid)
        ->where('status = ?', 'approved')
        ->where('type = ?', 'comment'));
    
    return $result['cnt'] ?: 0;
}
/**
 * 往年今日文章调用函数  
 * @param int $created 文章创建时间戳  
 */
function historyToday($created){  
    $date=date('m/d',$created);
    $time=time();
    $db=Typecho_Db::get();
    $prefix=$db->getPrefix();
    $limit=12;
    $adapter=$db->getAdapterName();  
    if("Pdo_SQLite"===$adapter||"SQLite"===$adapter){  
        $sql="SELECT * FROM `{$prefix}contents` WHERE strftime('%m-%d', datetime(created, 'unixepoch')) = '{$date}' AND created <= {$time} AND created != {$created} AND type = 'post' AND status = 'publish' AND (password IS NULL OR password = '') LIMIT {$limit}";  
    }else{  
        $sql="SELECT * FROM `{$prefix}contents` WHERE DATE_FORMAT(FROM_UNIXTIME(created), '%m/%d') = '{$date}' AND created <= {$time} AND created != {$created} AND type = 'post' AND status = 'publish' AND (password IS NULL OR password = '') LIMIT {$limit}";  
    }  
    $result=$db->query($sql);
    $historyTodayList=[];
    if($result instanceof Traversable){  
        foreach($result as$item){  
            $item=Typecho_Widget::widget('Widget_Abstract_Contents')->push($item);  
            $title=htmlspecialchars($item['title']);  
            $permalink=$item['permalink'];  
            $historyDate=date('Y-m-d',$item['created']);  
            $historyTodayList[]=['title'=>$title,'permalink'=>$permalink,'date'=>$historyDate];  
        }  
    }  
    if(!empty($historyTodayList)){  
        echo"";  
        foreach($historyTodayList as$item){  
            $displayTitle=mb_strlen($item['title'],'UTF-8')>15?mb_substr($item['title'],0,15,'UTF-8').'...':$item['title'];
            echo"<a href='{$item['permalink']}' title='{$item['date']}：{$item['title']}' target='_blank' style='margin-top:10px;white-space: nowrap;'>{$displayTitle}</a>";  
        }  
        echo"";  
    }  
}
?>