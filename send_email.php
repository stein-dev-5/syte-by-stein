<?php
header('Content-Type: application/json');
session_start();
if (isset($_SESSION['form_sent'])) {
    echo json_encode(['success' => false, 'message' => '❌ Форма уже была отправлена']);
    exit;
}

function sendToTelegram($data) {
    $botToken = '7751603543:AAEx6r68poRTxEtApCSLiHgX_tjGANNvEKk';
    $chatId = '7466444398';
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

    $text = "📢 <b>Новая заявка!</b>\n\n"
          . "👤 <b>Имя:</b> " . htmlspecialchars($data['name']) . "\n"
          . "📱 <b>Контакт:</b> " . htmlspecialchars($data['email']) . "\n"
          . "✉️ <b>Сообщение:</b>\n" . htmlspecialchars($data['message']);

    $postData = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($postData)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    return $result !== false;
}

$data = [
    'name' => trim($_POST['name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'message' => trim($_POST['message'] ?? '')
];

if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
    echo json_encode(['success' => false, 'message' => '❌ Заполните все поля']);
    exit;
}

if (sendToTelegram($data)) {
    $_SESSION['form_sent'] = true;
    echo json_encode([
        'success' => true, 
        'message' => 'Сообщение успешно отправлено! Мы скоро свяжемся с вами.'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Ошибка отправки. Попробуйте позже.'
    ]);
}
?>