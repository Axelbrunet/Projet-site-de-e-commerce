<?php
session_start();
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .return-home {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: background-color 0.3s;
}

.return-home:hover {
    background-color: #0056b3;
}

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-remove {
            background-color: #f44336;
        }
        .btn-remove:hover {
            background-color: #e53935;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">

    <a href="Accueil.php" class="btn return-home">Retour à l'accueil</a>

    <h2>Panier</h2>
    <p>Voici les articles que vous avez choisis :</p>

    <!-- Tableau des articles -->
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Prix Unitaire (€)</th>
                <th>Quantité</th>
                <th>Sous-total (€)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="cartItems">
            <?php if (!empty($_SESSION['panier'])): ?>
                <?php $total = 0; ?>
                <?php foreach ($_SESSION['panier'] as $produit): ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['Nom_prod']) ?></td>
                        <td><?= number_format($produit['Prix'], 2) ?></td>
                        <td>1</td> <!-- Quantité statique pour l'instant -->
                        <td><?= number_format($produit['Prix'], 2) ?></td>
                        <td>
                            <form action="supprimer_panier.php" method="POST">
                                <input type="hidden" name="id_produit" value="<?= $produit['ID_produits'] ?>">
                                <button type="submit" class="btn btn-remove">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php $total += $produit['Prix']; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Votre panier est vide.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="total">
        <strong>Total : <span id="totalPrice"><?= number_format($total, 2) ?></span> €</strong>
    </div>

    <!-- Bouton de validation -->
    <button class="btn" id="checkoutButton">Passer à la caisse</button>

</div>

</body>
</html>

