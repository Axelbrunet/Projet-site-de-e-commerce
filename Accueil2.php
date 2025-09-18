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

// Récupérer trois produits aléatoires depuis la base de données
$query = "SELECT Nom_prod, `Type de vente`, Prix, Photo, Description FROM produits ORDER BY RAND() LIMIT 3";
$stmt = $pdo->query($query);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia</title>
    <link rel="stylesheet" href="styleA.css">
    <style type="text/css">
      .navigation {
        margin-bottom: 20px;
      }
    </style>
</head>

<body>
  <div class="accueil">
    <div class="header">
      <div class="logo">
        <img src="logoAgora.jpeg" alt="Logo Agora" class="temple-logo">
        <h1>Agora Francia</h1>
      </div>
    </div>

    <div class="navigation">
      <a href="Accueil2.php"><button>Accueil</button></a>
      <a href="ToutParcourir2.php"><button>Tout Parcourir</button></a>
      <a href="Notifications2.php"><button>Notifications</button></a>
      <a href="Panier2.php"><button>Panier</button></a>
      <a href="VotreCompte2.php"><button>Votre Compte</button></a>
    </div>

    <div class="map-box common-box">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.999883503074!2d2.292292615674284!3d48.85884407928744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66f1dfc6eb3b3%3A0xd50fdf9c1b4b9d93!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1614702106369!5m2!1sfr!2sfr" 
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy">
    </iframe>
    </div>

    <div class="contact-box common-box">
      <h3>Contactez-nous</h3>
      <p>Adresse : 123 Rue Antique, Paris</p>
      <p>Email : contact@agorafrancia.fr</p>
      <p>Téléphone : +33 1 23 45 67 89</p>
    </div>




    <div class="section">
      <div class="description">
        <h2>Bienvenue dans l'Agora</h2>
        <p>Découvrez un espace de commerce et d'échange comme dans la Grèce antique.</p>
      </div>

      <div class="best-sellers">
        <h3>Sélection du jour</h3>
        <p>Voici les nouveaux articles qui viennent d'arriver.</p>

        <div class="gallery">
            <?php foreach ($produits as $produit): ?>
                <a href="#">
                    <img src="<?= htmlspecialchars($produit['Photo']); ?>" alt="<?= htmlspecialchars($produit['Nom_prod']); ?>">
                    <p>
                        <?= htmlspecialchars($produit['Nom_prod']); ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
      </div>
    </div>




    <div class="footer">
      <p>© 2024 Agora Francia - Inspiré par l'Antiquité</p>
    </div>
  </div>
</body>
</html>
