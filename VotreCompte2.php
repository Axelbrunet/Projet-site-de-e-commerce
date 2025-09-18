<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'projet agora');
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_ID'])) {
    header("Location: Connexion.php");
    exit;
}

$new_product_id = isset($_GET['new_product_id']) ? (int)$_GET['new_product_id'] : null;

// Récupérer les informations de l'utilisateur connecté
$userID = $_SESSION['user_ID'];
$sql = "SELECT Nom, Prénom, email, Ville, NuméroTel, TypeCarte, NuméroCarte, Rôle FROM utilisateurs WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("Utilisateur non trouvé.");
}

// Récupérer le produit récemment ajouté si l'ID est défini
$new_product = null;
if ($new_product_id) {
    $sql_new_product = "SELECT Nom_prod, Catégorie, Prix, Photo, Description FROM produits WHERE ID_produits = ? AND ID = ?";
    $stmt_new_product = $conn->prepare($sql_new_product);
    $stmt_new_product->bind_param("ii", $new_product_id, $userID);
    $stmt_new_product->execute();
    $result_new_product = $stmt_new_product->get_result();

    if ($result_new_product->num_rows > 0) {
        $new_product = $result_new_product->fetch_assoc();
    }
    $stmt_new_product->close();
}

// Récupérer les produits de l'utilisateur
$sql_products = "SELECT Nom_prod, Catégorie, Prix, Photo, Description FROM produits WHERE ID_produits = ?";
$stmt_products = $conn->prepare($sql_products);
$stmt_products->bind_param("i", $userID);
$stmt_products->execute();
$result_products = $stmt_products->get_result();

$products = [];
while ($row = $result_products->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$stmt_products->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleA.css">
    <title>Mon Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            background-color: #4d2b1f;
            color: white;
            padding: 15px 20px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-around;
        }

        .nav-links li {
            display: inline;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .profil-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profil-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profil-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-right: 20px;
            border: 3px solid black;
        }

        .profil-details h1 {
            margin: 0;
            font-size: 24px;
            color: #4d2b1f;
        }

        .profil-details p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        .articles-container h2 {
            color: #4d2b1f;
            font-size: 20px;
            margin-bottom: 10px;
        }

        #articles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .article {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .article img{
            width: 40%;
        }

        .article-photo {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .article-details h3 {
            font-size: 16px;
            margin: 10px 0 5px;
            color: #4d2b1f;
        }

        .article-details p {
            font-size: 14px;
            margin: 5px 0;
            color: #333;
        }

        button {
            background-color: #4d2b1f; 
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 10px 0 20px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #61380B;
            transform: scale(1.1);
        }

        footer {
          width: 100%;
          background-color: rgba(230, 214, 186, 0.9);
          text-align: center;
          padding: 20px;
          color: #4d2b1f;
          font-size: 14px;
          border-top: 3px solid #af8c62;
          
          bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="Accueil2.php">Accueil</a></li>
                <li>PROFIL</li>
                <li><a href="NégociationsAcheteur.php">Négociations</a></li>
                <li><a href="Accueil.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main class="profil-container">
        <!-- Profil de l'utilisateur -->
        <div class="profil-header">
            <img src="profil.webp" alt="Photo de profil" class="profil-photo">
            <div class="profil-details">
                <h1><?= htmlspecialchars($user['Prénom'] . ' ' . $user['Nom']) ?></h1>
                <p><?= htmlspecialchars($user['email']) ?></p>
                <p><?= htmlspecialchars($user['Ville']) ?></p>
                <p><strong>Rôle :</strong> <?= htmlspecialchars($user['Rôle']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['NuméroTel']) ?></p>
                <p><strong>Type de Carte :</strong> <?= htmlspecialchars($user['TypeCarte']) ?></p>
                <p><strong>Carte :</strong> <?= htmlspecialchars($user['NuméroCarte']) ?></p><br><button class="btn ajout" onclick="location.href='AjoutProduit.php'">Ajouter un article</button>
            </div>
        </div>

        <div class="articles-container">
            <h2>Produit récemment ajouté</h2>
            <?php if ($new_product): ?>
                <div class="article">
                    <img src="<?= htmlspecialchars($new_product['Photo']) ?>" alt="<?= htmlspecialchars($new_product['Nom_prod']) ?>" class="article-photo">
                    <div class="article-details">
                        <h3><?= htmlspecialchars($new_product['Nom_prod']) ?></h3>
                        <p><?= htmlspecialchars($new_product['Description']) ?></p>
                        <p><strong>Prix :</strong> <?= htmlspecialchars($new_product['Prix']) ?> €</p>
                    </div>
                </div>
            <?php else: ?>
                <p>Aucun produit récemment ajouté.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    </footer>
</body>
</html>
