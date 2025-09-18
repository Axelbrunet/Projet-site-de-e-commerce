<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'projet agora');
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Initialisation des messages
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['connexion'])) {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Vérifier si l'utilisateur existe
        $result = $conn->query("SELECT * FROM utilisateurs WHERE email='$email'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Vérification du mot de passe (colonne 'mdp')
            if (password_verify($mot_de_passe, $user['mdp'])) {
                 session_start();
                $_SESSION['user_ID'] = $user['ID'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['Rôle'];
                
                // Redirection vers une page protégée ou tableau de bord
                header("Location: Accueil2.php");
                exit;
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            // Redirection vers la page d'inscription si l'utilisateur n'existe pas
            header("Location: Inscription.php?erreur=utilisateur_inexistant");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
            max-width: 400px;
            text-align: center;
        }
        .container h1 {
            margin-bottom: 10px;
            color: #555;
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

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <p class="error"><?= $message ?></p>

        <!-- Formulaire de connexion -->
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit" name="connexion">Se connecter</button><br>
        </form>

        

        <p>_______________________________________</p><br>
        <h1>Inscription</h1>
        <div class="inscription">
            <br>
            <!-- Bouton pour revenir à l'accueil -->
            <button class="btn inscription" onclick="location.href='Inscription.php'">Créer un compte</button>
        </div>

        <div class="acc">
            <br>
            <!-- Bouton pour revenir à l'accueil -->
            <button class="btn retour-accueil" onclick="location.href='Accueil.php'">Retour à l'accueil</button>
        </div><br>
    </div>
</body>
</html>
