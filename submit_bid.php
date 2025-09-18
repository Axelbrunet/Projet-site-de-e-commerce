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

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier et sécuriser les données transmises
    if (!isset($_POST['produit_nom'], $_POST['montant']) || empty(trim($_POST['produit_nom'])) || !is_numeric($_POST['montant'])) {
        die("Données invalides. Veuillez réessayer.");
    }

    $produit_nom = trim($_POST['produit_nom']);
    $montant = (float) $_POST['montant'];

    // Récupérer le produit pour vérifier le prix actuel
    $query = "SELECT * FROM produits WHERE Nom_prod = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$produit_nom]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        die("Produit introuvable.");
    }

    // Vérifier si l'enchère est supérieure au prix actuel
    if ($montant <= $produit['Prix']) {
        die("Votre enchère doit être supérieure au prix actuel (" . htmlspecialchars($produit['Prix']) . " €).");
    }

    // Mettre à jour le prix dans la base de données
    $update_query = "UPDATE produits SET Prix = ? WHERE Nom_prod = ?";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->execute([$montant, $produit_nom]);

    echo "Votre enchère a été soumise avec succès !";
    echo "<br><a href='ToutParcourir.php'>Retourner à la liste des produits</a>";
} else {
    // Redirection si accès direct au fichier sans soumission du formulaire
    header("Location: ToutParcourir.php");
    exit;
}
?>
