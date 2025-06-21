<?php
header('Content-Type: application/json');

function sendToTelegram($data) {
    $botToken = '7751603543:AAEx6r68poRTxEtApCSLiHgX_tjGANNvEKk';
    $chatId = '7466444398';
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage"; // Правильный URL Telegram API

    $postData = [
        'chat_id' => $chatId,
        'text' => "📢 Новая заявка!\nИмя: {$data['name']}\nКонтакт: {$data['email']}\nСообщение: {$data['message']}",
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($postData)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result !== false;
}

// Основной код
$data = [
    'name' => $_POST['name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'message' => $_POST['message'] ?? ''
];

if (sendToTelegram($data)) {
    echo json_encode(['success' => true, 'message' => '✅ Сообщение отправлено!']);
} else {
    $error = error_get_last();
    echo json_encode([
        'success' => false, 
        'message' => '❌ Ошибка отправки',
        'debug' => $error['message'] ?? 'Unknown error'
    ]);
}
?>