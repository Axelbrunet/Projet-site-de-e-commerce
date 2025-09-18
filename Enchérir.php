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

// Vérifier si le paramètre 'Nom_prod' est défini et valide
if (!isset($_GET['Nom_prod']) || empty(trim($_GET['Nom_prod']))) {
    die("Produit introuvable ou Nom invalide.");
}

// Sécuriser l'entrée utilisateur
$Nom_prod = trim($_GET['Nom_prod']);

// Récupérer les détails du produit en utilisant Nom_prod
$query = "SELECT * FROM produits WHERE Nom_prod = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$Nom_prod]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enchérir sur <?= htmlspecialchars($produit['Nom_prod']); ?></title>
    <link rel="stylesheet" href="styleA.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
            color: #4d2b1f;
        }
        .container img {
            width: 40%;
            margin-bottom: 10px;
        }
        .container .Prix{

        }
        .container input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .container button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 40%;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .container .acc button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4d2b1f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 50%;
        }
        .container .acc button:hover {
            background-color: #6b3e2b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enchérir sur : <?= htmlspecialchars($produit['Nom_prod']); ?></h1>
        <img src="<?= htmlspecialchars($produit['Photo']); ?>" alt="Image du produit">
        <p>Description : <?= htmlspecialchars($produit['Description']); ?></p>
        <div class="Prix"><b>Prix : <?= number_format($produit['Prix'], 2, ',', ' '); ?> &euro;</b></div><br>
        
        <!-- Formulaire pour soumettre une enchère -->
        <form action="submit_bid.php" method="POST">
            <input type="hidden" name="produit_nom" value="<?= htmlspecialchars($produit['Nom_prod']); ?>">
            <label for="montant">Votre enchère maximale (en €) :</label>
            <input type="number" id="montant" name="montant" 
                   min="<?= htmlspecialchars($produit['Prix'] + 1); ?>" 
                   required>
            <button type="submit">Soumettre l'enchère</button>
        </form>
         <div class="acc">
            <br>
            <!-- Bouton pour revenir à l'accueil -->
            <button class="btn retour-accueil" onclick="location.href='Accueil.php'">Retour à l'accueil</button>
        </div>
    </div>
</body>
</html>
