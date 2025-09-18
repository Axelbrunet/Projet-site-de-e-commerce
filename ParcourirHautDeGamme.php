<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'projet agora';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les filtres de tri envoyés par le formulaire
$types = isset($_GET['types']) ? $_GET['types'] : [];

// Construire la requête SQL
$query = "SELECT Nom_prod, `Type de vente`, Prix, Photo, Description FROM produits WHERE Catégorie = 'Haut de gamme'";

if (!empty($types)) {
    $placeholders = implode(',', array_fill(0, count($types), '?'));
    $query .= " AND `Type de vente` IN ($placeholders)";
}

$stmt = $pdo->prepare($query);
$stmt->execute($types);
$produits_haut_de_gamme = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles à Vendre</title>
    <link rel="stylesheet" href="styleA.css">
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }


        /* Conteneur principal */
        .container {
            display: flex;
            flex-wrap: wrap; /* Permet de passer à la ligne si nécessaire */
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        /* Styles pour chaque case */
        .article-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
        }

        .article-card:hover {
            transform: scale(1.05); /* Agrandissement au survol */
        }

        .article-card img {
            width: 100%;
            height: auto;
        }

        .article-card h2 {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .article-card p {
            font-size: 14px;
            color: #555;
            padding: 0 10px;
        }

        .article-card .TypeVente {
            font-size: 16px;
            color: #007BFF;
            font-weight: bold;
            margin: 10px 0;
        }

        .article-card button {
            background-color: darkslategrey;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 10px 0 20px;
            cursor: pointer;
            font-size: 14px;
        }

        .article-card button:hover {
            background-color: #4d2b1f;
        }

        .vente-article {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 16px;
            color: black;
            background-color: #F2F2F2;
            border: 1;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }

        .vente-article:hover {
            background-color: #E6E6E6; /* Couleur plus sombre au survol */
        }

        .BoxBouttons {
            display: flex;
            flex-wrap: wrap; /* Permet de passer à la ligne si nécessaire */
            justify-content: center;
            gap: 20px;
            padding: 20px;
            background-color: #F6CEF5;
        }

        .sort-container button {
            background-color: #4d2b1f; 
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 10px 0 20px;
            cursor: pointer;
            font-size: 14px;
        }

        .sort-container button:hover {
            background-color: #61380B;
            transform: scale(1.1);
        }

        .box-choix-right{
            color: rgba(230, 214, 186, 0.9);
        }

    </style>

    <script>
        function ajouterAuPanier(nomProduit) {
            alert("Le produit " + nomProduit + " a été ajouté au panier !");
            // Ici, vous pouvez ajouter une logique pour envoyer une requête Ajax ou rediriger
            // vers une autre page pour gérer l'ajout au panier.
        }
    </script>
</head>


<body>
    <div class="header">
        <div class="box-choix-left">
          <a href="Accueil.php"><img src="accueil.webp" alt="Accueil"></a>
        </div>

        <div class="box-choix-right">
          <p>...</p>
        </div> 

      <div class="logo">
        <img src="logoAgora.jpeg" alt="Logo Agora" class="temple-logo">
        <h1>Articles hauts de gamme</h1>
      </div>
    </div>



    <div class="sous-navigation">
        <a href="ToutParcourir.php"><button>Tout les articles</button></a>
      <a href="ParcourirRéguliers.php"><button>Articles réguliers</button></a>
      <a href="ParcourirHautDeGamme.php"><button>Articles haut de gamme</button></a>
      <a href="ParcourirRares.php"><button>Articles rares</button></a>
    </div>

<!-- Formulaire pour trier par type de vente -->
    <div class="sort-container">
        <form method="GET" action="ParcourirHautDeGamme.php">
            <div class="checkbox-container">
                <h3>Trier par :</h3>
                <label>
                    <input type="checkbox" name="types[]" value="immediat" <?= isset($_GET['types']) && in_array('immediat', $_GET['types']) ? 'checked' : '' ?>>
                    Vente immédiate
                </label>
                <label>
                    <input type="checkbox" name="types[]" value="négociation" <?= isset($_GET['types']) && in_array('négociation', $_GET['types']) ? 'checked' : '' ?>>
                    Vente par négociation
                </label>
                <label>
                    <input type="checkbox" name="types[]" value="encheres" <?= isset($_GET['types']) && in_array('encheres', $_GET['types']) ? 'checked' : '' ?>>
                    Vente aux enchères
                </label>
            </div>
            <button type="submit" class="apply-button">Appliquer</button>
        </form>
    </div>

    <!-- Affichage des articles -->
    <div class="container">
        <?php if (!empty($produits_haut_de_gamme)): ?>
            <?php foreach ($produits_haut_de_gamme as $produit): ?>
                <div class="article-card">
                    <img src="<?= htmlspecialchars($produit['Photo']); ?>" alt="Image du produit">
                <h2><?= htmlspecialchars($produit['Nom_prod']); ?></h2>
                <p><?= htmlspecialchars($produit['Description']); ?></p>
                <div class="TypeVente">Type de vente : <?= htmlspecialchars($produit['Type de vente']); ?></div>
                <div class="Prix">Prix : <?= number_format($produit['Prix'], 2, ',', ' '); ?> &euro;</div>

                <?php if (strtolower($produit['Type de vente']) === 'encheres' || strtolower($produit['Type de vente']) === 'enchères'): ?>
                    <button onclick="window.location.href='Enchérir.php?Nom_prod=<?= urlencode($produit['Nom_prod']); ?>'">Enchérir</button>
                <?php elseif (strtolower($produit['Type de vente']) === 'négociation' || strtolower($produit['Type de vente']) === 'negociation'): ?>
                    <button onclick="window.location.href='Négociation.php?Nom_prod=<?= urlencode($produit['Nom_prod']); ?>'">Négocier</button>
                <?php else: ?>
                    <button onclick="ajouterAuPanier('<?= htmlspecialchars($produit['Nom_prod']); ?>')">Ajouter au panier</button>
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun article haut de gamme disponible pour le moment.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
      <p>© 2024 Agora Francia - Inspiré par l'Antiquité</p>
    </div>
</body>
</html>
