Foottees est un mini projet e-commerce réalisé en **PHP** et **MySQL** (XAMPP).  
Le site contient une boutique, un panier/checkout, et une partie administration.

Fonctionnalités
- Affichage des produits (catalogue / accueil)
- Panier (`cart.php`)
- Paiement / commande (`checkout.php`)
- Authentification (`login.php`, `logout.php`)
- Tableau de bord admin (`dashboard.php`)
- Configuration MySQL (`config.php`)
- Script d’installation BD (`setup_mysql.php`)
- Dossier des ressources : `assets/`

Technologies
- PHP
- MySQL
- HTML/CSS (+ éventuellement JS)
- XAMPP (Apache + MySQL)

- Structure du projet 
- `index.php` — accueil / catalogue
- `cart.php` — panier
- `checkout.php` — checkout / commande
- `login.php` / `logout.php` — connexion / déconnexion
- `dashboard.php` — administration
- `config.php` — connexion/config base de données
- `setup_mysql.php` — création/initialisation base de données
- `assets/` — images et fichiers statiques

  Installation (XAMPP)
1. Installer et ouvrir **XAMPP**
2. Démarrer :
   - **Apache**
   - **MySQL**
3. Placer le dossier du projet dans :
   - `C:\xampp\htdocs\foottees`
4. Créer la base de données :
   - Ouvrir `http://localhost/phpmyadmin`
   - Créer une BD (exemple : `foottees`)
5. Configurer les accès BD dans `config.php` (host, dbname, user, password)
6. Lancer l’installation (si votre projet l’utilise) :
   - `http://localhost/foottees/setup_mysql.php`

Lancer le projet
Ouvrir dans le navigateur :
- `http://localhost/foottees/`

 Utilisation
- Parcourir les produits depuis l’accueil
- Ajouter au panier
- Passer à la commande
- Se connecter pour accéder au dashboard admin
