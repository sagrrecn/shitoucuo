<?php
/**
 * 都市繁星 · 祝福墙API (Typecho适配版)
 * 存储路径: /usr/themes/sagrre/assets/wishes.json
 */

// 允许跨域调用
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// JSON文件存储路径（和本PHP文件在同一目录）
$jsonFile = __DIR__ . '/wishes.json';

// ===== 读取祝福列表 =====
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($jsonFile)) {
        $data = file_get_contents($jsonFile);
        echo $data;
    } else {
        // 首次运行，返回默认祝福语
        $defaultWishes = [
            ['nickname' => '星语者', 'recipient' => '博主', 'content' => '代码无BUG，发量永浓密', 'time' => time()],
            ['nickname' => '城市旅人', 'recipient' => '自己', 'content' => '房贷压力小，升职加薪早', 'time' => time()],
            ['nickname' => '宝妈', 'recipient' => '家人', 'content' => '宝宝健康，老公体贴', 'time' => time()]
        ];
        echo json_encode($defaultWishes, JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// ===== 提交新祝福 =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取POST数据
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nickname = trim($input['nickname'] ?? '匿名');
    $recipient = trim($input['recipient'] ?? '大家');
    $content = trim($input['content'] ?? '');
    
    if (empty($content)) {
        http_response_code(400);
        echo json_encode(['error' => '祝福内容不能为空']);
        exit;
    }
    
    // 限制长度
    $nickname = mb_substr($nickname, 0, 20);
    $recipient = mb_substr($recipient, 0, 20);
    $content = mb_substr($content, 0, 50);
    
    // 读取现有数据
    $wishes = [];
    if (file_exists($jsonFile)) {
        $wishes = json_decode(file_get_contents($jsonFile), true);
        if (!is_array($wishes)) $wishes = [];
    }
    
    // 添加新祝福（插到最前面）
    array_unshift($wishes, [
        'nickname' => $nickname,
        'recipient' => $recipient,
        'content' => $content,
        'time' => time()
    ]);
    
    // 只保留最新的50条
    $wishes = array_slice($wishes, 0, 50);
    
    // 写入JSON文件
    file_put_contents($jsonFile, json_encode($wishes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
    echo json_encode(['success' => true, 'wishes' => $wishes]);
    exit;
}

// ===== 其他请求 =====
http_response_code(405);
echo json_encode(['error' => 'Method Not Allowed']);
exit;
?>