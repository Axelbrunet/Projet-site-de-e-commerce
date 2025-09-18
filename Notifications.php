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

// Récupération des critères dynamiques depuis la base de données
$types = $pdo->query("SELECT DISTINCT `Type` FROM produits")->fetchAll(PDO::FETCH_COLUMN);
$categories = $pdo->query("SELECT DISTINCT `Catégorie` FROM produits")->fetchAll(PDO::FETCH_COLUMN);
$types_de_vente = $pdo->query("SELECT DISTINCT `Type de vente` FROM produits")->fetchAll(PDO::FETCH_COLUMN);

// Si le formulaire est soumis
if (isset($_POST['submit'])) {
    $type_vente = $_POST['type_vente'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $type = $_POST['type'] ?? '';
    $prix_min = $_POST['prix_min'] ?? 0;
    $prix_max = $_POST['prix_max'] ?? PHP_INT_MAX;

    // Requête SQL pour récupérer les produits correspondant aux critères
    $sql = "SELECT * FROM produits WHERE 1=1";

    $params = [];

    if (!empty($type_vente)) {
        $sql .= " AND `Type de vente` = :type_vente";
        $params[':type_vente'] = $type_vente;
    }
    if (!empty($categorie)) {
        $sql .= " AND `Catégorie` = :categorie";
        $params[':categorie'] = $categorie;
    }
    if (!empty($type)) {
        $sql .= " AND `Type` = :type";
        $params[':type'] = $type;
    }
    if (!empty($prix_min)) {
        $sql .= " AND `Prix` >= :prix_min";
        $params[':prix_min'] = $prix_min;
    }
    if (!empty($prix_max)) {
        $sql .= " AND `Prix` <= :prix_max";
        $params[':prix_max'] = $prix_max;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si aucun produit n'est trouvé, enregistrement des critères dans la table notifications
    if (empty($produits)) {
        $sql_insert_notification = "INSERT INTO notifications (`type_vente`, `categorie`, `type`, `prix_min`, `prix_max`) 
                                     VALUES (:type_vente, :categorie, :type, :prix_min, :prix_max)";
        $stmt_insert = $pdo->prepare($sql_insert_notification);
        $stmt_insert->execute([
            ':type_vente' => $type_vente,
            ':categorie' => $categorie,
            ':type' => $type,
            ':prix_min' => $prix_min,
            ':prix_max' => $prix_max
        ]);
        $notification = "Aucun produit ne correspond à vos critères. Ils ont été enregistrés. Vous recevrez une notification lorsqu'un produit correspondant sera disponible.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Agora Francia</title>
    <link rel="stylesheet" href="styleA.css">
    <style>
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
            background-color: #4d2b1f;
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        select, input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-group {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #4d2b1f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #6b3e2b;
        }

        .alert-box {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f8ff;
            border-left: 4px solid #00bfff;
        }

        .product-list {
            margin-top: 30px;
        }

        .product-item {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .product-item h3 {
            margin: 0;
            font-size: 18px;
        }

        .product-item p {
            margin: 5px 0;
            font-size: 16px;
        }

        .container img {
            width: 40%;
            margin-bottom: 10px;
        }
    </style>
    </style>
</head>
<body>
<div class="container">
    <a href="Accueil.php" class="btn return-home">Retour à l'accueil</a>
    <h2>Notifications - Agora Francia</h2><br>
    <p>Activez les alertes pour être averti dès qu'un article correspondant à vos critères devient disponible.</p><br>

    <form id="notificationForm" method="POST">
        <div class="form-group">
            <label for="type">Type d'article</label>
            <select id="type" name="type">
                <option value="">Tous les types</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>">
                        <?php echo htmlspecialchars($type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="category">Catégorie de l'article</label>
            <select id="category" name="categorie">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo htmlspecialchars($categorie); ?>">
                        <?php echo htmlspecialchars($categorie); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="saleType">Type de vente</label>
            <select id="saleType" name="type_vente">
                <option value="">Tous les types de vente</option>
                <?php foreach ($types_de_vente as $type_vente): ?>
                    <option value="<?php echo htmlspecialchars($type_vente); ?>">
                        <?php echo htmlspecialchars($type_vente); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="priceMin">Prix minimum (€)</label>
            <input type="number" id="priceMin" name="prix_min" placeholder="Ex. 10">
        </div>

        <div class="form-group">
            <label for="priceMax">Prix maximum (€)</label>
            <input type="number" id="priceMax" name="prix_max" placeholder="Ex. 1000">
        </div>

        <div class="button-group">
            <button type="submit" class="btn" name="submit">Activer les alertes</button>
        </div>
    </form>

    <?php if (isset($produits) && count($produits) > 0): ?>
        <div class="product-list">
            <?php foreach ($produits as $produit): ?>
                <div class="product-item">
                    <h3><?php echo htmlspecialchars($produit['Nom_prod']); ?></h3>
                    <img src="<?= htmlspecialchars($produit['Photo']); ?>" alt="Image du produit">
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($produit['Type']); ?></p>
                    <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($produit['Catégorie']); ?></p>
                    <p><strong>Prix:</strong> €<?php echo htmlspecialchars($produit['Prix']); ?></p>
                    <p><strong>Type de vente:</strong> <?php echo htmlspecialchars($produit['Type de vente']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($notification)): ?>
        <p><?php echo htmlspecialchars($notification); ?></p>
    <?php endif; ?>
</div>
</body>
</html>
