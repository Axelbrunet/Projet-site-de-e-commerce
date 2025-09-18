<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background-color: #4CAF50;
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
            border: 3px solid #4CAF50;
        }

        .profil-details h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }

        .profil-details p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        .articles-container h2 {
            color: #4CAF50;
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

        .article-photo {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .article-details h3 {
            font-size: 16px;
            margin: 10px 0 5px;
            color: #4CAF50;
        }

        .article-details p {
            font-size: 14px;
            margin: 5px 0;
            color: #333;
        }

        footer {
            text-align: center;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            margin-top: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
    <nav>
        <ul class="nav-links">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="NégociationsAcheteur.php">Négociations</a></li> <!-- Mise à jour du lien -->
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>



    <main class="profil-container">
        <!-- Profil de l'utilisateur -->
        <div class="profil-header">
            <img src="uploads/default-profile.jpg" alt="Photo de profil" class="profil-photo">
            <div class="profil-details">
                <h1 id="user-name">Prénom Nom</h1>
                <p id="user-description">Description du profil...</p>
            </div>
        </div>

        <!-- Articles mis en vente -->
        <div class="articles-container">
            <h2>Mes Articles en Vente</h2>
            <div id="articles">
                <!-- Les articles seront insérés dynamiquement ici -->
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Agora Francia. Tous droits réservés.</p>
    </footer>

    <script>
        // Exemple de script pour charger les données dynamiquement (mockup)
        document.addEventListener('DOMContentLoaded', function() {
            // Simulation des données utilisateur
            const user = {
                name: 'Jean Dupont',
                description: 'Passionné par les objets vintages et les antiquités.',
                photo: 'uploads/jean-dupont.jpg'
            };

            // Simulation des articles
            const articles = [
                { titre: 'Lampe vintage', description: 'Une belle lampe des années 50.', prix: '120', photo: 'uploads/lampe-vintage.jpg' },
                { titre: 'Chaise en bois', description: 'Chaise en bois massif.', prix: '85', photo: 'uploads/chaise-bois.jpg' }
            ];

            // Mise à jour des informations utilisateur
            document.getElementById('user-name').textContent = user.name;
            document.getElementById('user-description').textContent = user.description;
            document.querySelector('.profil-photo').src = user.photo;

            // Affichage des articles
            const articlesContainer = document.getElementById('articles');
            articles.forEach(article => {
                const articleElement = document.createElement('div');
                articleElement.className = 'article';

                articleElement.innerHTML = `
                    <img src="${article.photo}" alt="${article.titre}" class="article-photo">
                    <div class="article-details">
                        <h3>${article.titre}</h3>
                        <p>${article.description}</p>
                        <p><strong>Prix :</strong> ${article.prix} €</p>
                    </div>
                `;
                articlesContainer.appendChild(articleElement);
            });
        });
    </script>
</body>
</html>
