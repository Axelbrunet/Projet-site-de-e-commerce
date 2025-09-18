<?php 
// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'projet agora';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le paramètre 'Nom_prod' est défini et valide
if (!isset($_GET['Nom_prod']) || empty(trim($_GET['Nom_prod']))) {
    die("Produit introuvable ou Nom invalide.");
}

// Sécuriser l'entrée utilisateur
$Nom_prod = trim($_GET['Nom_prod']);

// Récupérer les détails du produit en utilisant Nom_prod
$query = "SELECT * FROM produits WHERE Nom_prod = :Nom_prod";
$stmt = $pdo->prepare($query);
$stmt->execute([':Nom_prod' => $Nom_prod]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}

// Initialiser une variable pour afficher les messages
$message = "";

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prix_propose = filter_var($_POST['prix_propose'], FILTER_VALIDATE_FLOAT);
    $email_acheteur = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // Validation des données
    if (!$prix_propose || $prix_propose <= 0) {
        $message = "Le prix proposé doit être un nombre valide supérieur à 0.";
    } elseif (!$email_acheteur) {
        $message = "Veuillez entrer une adresse e-mail valide.";
    } else {
        // Insérer l'offre dans la table negociations
        $query = "INSERT INTO negociations (produit_nom, prix_propose, email_acheteur)
                  VALUES (:produit_nom, :prix_propose, :email_acheteur)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':produit_nom' => $produit['Nom_prod'],
            ':prix_propose' => $prix_propose,
            ':email_acheteur' => $email_acheteur
        ]);

        $message = "Votre offre a été soumise avec succès !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire une Offre</title>
    <link rel="stylesheet" href="styleA.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="number"],
        input[type="email"],
        button {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Faire une Offre pour <?= htmlspecialchars($produit['Nom_prod']); ?></h1>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="product">Nom du Produit</label>
            <input type="text" id="product" name="product" readonly value="<?= htmlspecialchars($produit['Nom_prod']); ?>">

            <label for="price">Votre Prix Proposé (en €)</label>
            <input type="number" id="price" name="prix_propose" required min="1" step="0.01">

            <label for="email">Votre Adresse E-mail</label>
            <input type="email" id="email" name="email" required placeholder="Votre e-mail">

            <button type="submit">Envoyer l'Offre</button>
        </form>

        <div class="back-link">
            <a href="ToutParcourir.php">Retour</a>
        </div>
    </div>
</body>
</html>
