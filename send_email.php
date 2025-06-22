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

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    curl_close($ch);

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
    session_write_close();
    echo json_encode(['success' => true, 'message' => '✅ Сообщение отправлено!']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Ошибка отправки. Попробуйте позже.']);
}
?>