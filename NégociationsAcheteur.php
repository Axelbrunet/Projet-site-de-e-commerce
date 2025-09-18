<?php
// Connexion à la base de données
require('db_connection.php');

// Démarrer la session pour récupérer l'ID de l'utilisateur
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_ID'])) {
    echo "Vous devez être connecté pour voir les offres.";
    exit;
}

// Vérifier si l'ID du produit est passé dans l'URL
if (isset($_GET['produit_id'])) {
    $produit_id = $_GET['produit_id'];

    // Récupérer les informations du produit
    $query = "SELECT * FROM produits WHERE id = :produit_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':produit_id' => $produit_id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        echo "Produit non trouvé.";
        exit;
    }

    // Récupérer les offres faites pour ce produit
    $query_offres = "SELECT o.prix_propose, o.date_offre, a.nom AS nom_acheteur, o.statut
                     FROM negociations o
                     JOIN utilisateurs a ON o.acheteur_id = a.id
                     WHERE o.produit_id = :produit_id
                     ORDER BY o.date_offre DESC";

    $stmt_offres = $pdo->prepare($query_offres);
    $stmt_offres->execute([':produit_id' => $produit_id]);
    $offres = $stmt_offres->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID du produit manquant.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres reçues pour <?= htmlspecialchars($produit['Nom_prod']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f5;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 30px;
        }

        .product-info {
            margin-bottom: 40px;
            text-align: center;
        }

        .product-info h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-info p {
            font-size: 18px;
            color: #555;
        }

        .offer-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .offer-card h3 {
            color: #4CAF50;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .offer-card .offer-details {
            font-size: 16px;
            color: #555;
        }

        .offer-card .offer-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .pending {
            background-color: #FFA500;
            color: #fff;
        }

        .accepted {
            background-color: #28a745;
            color: #fff;
        }

        .rejected {
            background-color: #dc3545;
            color: #fff;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .offer-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease-in-out;
        }

        .offer-card .offer-status {
            align-self: flex-start;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Offres reçues pour <?= htmlspecialchars($produit['Nom_prod']); ?></h1>

        <div class="product-info">
            <h2>Produit : <?= htmlspecialchars($produit['Nom_prod']); ?></h2>
            <p><strong>Prix de vente : </strong> <?= number_format($produit['prix'], 2, ',', ' ') ?> €</p>
        </div>

        <?php if (count($offres) > 0): ?>
            <?php foreach ($offres as $offre): ?>
                <div class="offer-card">
                    <h3><?= htmlspecialchars($offre['nom_acheteur']); ?></h3>
                    <div class="offer-details">
                        <p><strong>Prix proposé : </strong> <?= number_format($offre['prix_propose'], 2, ',', ' '); ?> €</p>
                        <p><strong>Date de l'offre : </strong> <?= date('d/m/Y H:i', strtotime($offre['date_offre'])); ?></p>
                    </div>
                    <div class="offer-status <?= strtolower($offre['statut']); ?>">
                        <?= htmlspecialchars($offre['statut']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune offre reçue pour ce produit.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="ToutParcourir.php">Retour</a>
        </div>
    </div>
</body>
</html>
