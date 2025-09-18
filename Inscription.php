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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscrire'])) {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse1 = $_POST['adresse1'];
    $adresse2 = $_POST['adresse2'] ?? null;
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $telephone = $_POST['telephone'];
    $type_carte = $_POST['type_carte'];
    $numero_carte = $_POST['numero_carte'];
    $nom_carte = $_POST['nom_carte'];
    $expiration_carte = $_POST['expiration_carte'];
    $code_securite = $_POST['code_securite'];
    $role = $_POST['role'];

    // Vérifier si l'email existe déjà dans la base de données
    $query = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $query->execute(['email' => $email]);
    $utilisateur = $query->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        $message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Insertion des données dans la table utilisateurs
        $query = $pdo->prepare(
            "INSERT INTO utilisateurs (email, mdp, Nom, Prénom, Adresse1, Adresse2, Ville, CodePostal, Pays, NuméroTel, TypeCarte, NuméroCarte, NomCarte, DateExpiration, CodeSécurité, Rôle) 
             VALUES (:email, :mdp, :nom, :prenom, :adresse1, :adresse2, :ville, :code_postal, :pays, :telephone, :type_carte, :numero_carte, :nom_carte, :expiration_carte, :code_securite, :role)"
        );

        $success = $query->execute([
            'email' => $email,
            'mdp' => $hashed_password,
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse1' => $adresse1,
            'adresse2' => $adresse2,
            'ville' => $ville,
            'code_postal' => $code_postal,
            'pays' => $pays,
            'telephone' => $telephone,
            'type_carte' => $type_carte,
            'numero_carte' => $numero_carte,
            'nom_carte' => $nom_carte,
            'expiration_carte' => $expiration_carte,
            'code_securite' => $code_securite,
            'role' => $role
        ]);

        if ($success) {
            $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $message = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <style>
        body {
            text-align: center;
        }
        .liste button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 20%;
        }

        .liste button:hover {
            background-color: #0056b3;
        }

        .acc button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4d2b1f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 30%;
        }
        .acc button:hover {
            background-color: #6b3e2b;
        }

    </style>
</head>
<body>
    <h1>Inscription</h1>
    <p style="color: green;">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <div class="liste">
        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br><br>
            <input type="text" name="nom" placeholder="Nom" required><br><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br><br>
            <input type="text" name="adresse1" placeholder="Adresse Ligne 1" required><br><br>
            <input type="text" name="adresse2" placeholder="Adresse Ligne 2"><br><br>
            <input type="text" name="ville" placeholder="Ville" required><br><br>
            <input type="text" name="code_postal" placeholder="Code Postal" required><br><br>
            <input type="text" name="pays" placeholder="Pays" required><br><br>
            <input type="text" name="telephone" placeholder="Numéro de téléphone" required><br><br>
            <input type="text" name="type_carte" placeholder="Type de carte de paiement" required><br><br>
            <input type="text" name="numero_carte" placeholder="Numéro de la carte" required><br><br>
            <input type="text" name="nom_carte" placeholder="Nom affiché dans la carte" required><br><br>
            <input type="date" name="expiration_carte" placeholder="Date d'expiration de la carte" required><br><br>
            <input type="text" name="code_securite" placeholder="Code de sécurité" required><br><br>
            <select name="role" required>
                <option value="Utilisateur">Acheteur</option>
                <option value="Admin">Vendeur</option>
            </select><br><br>
            <button type="submit" name="inscrire">S'inscrire</button>
        </form>
    </div>

    <div class="acc">
        <br>
        <!-- Bouton pour revenir à l'accueil -->
        <button class="btn retour-accueil" onclick="location.href='Accueil.php'">Retour à l'accueil</button>
    </div>
</body>
</html>
