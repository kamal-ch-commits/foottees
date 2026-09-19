# Foottees — Boutique de maillots de football

**PHP · MySQL · HTML/CSS · XAMPP**

Projet e-commerce avec catalogue de maillots, panier, enregistrement des commandes et espace d'administration. Les prix sont exprimés en dirhams marocains (MAD).

## Fonctionnalités

- Consulter les produits et leurs tailles.
- Ajouter des articles au panier et passer une commande.
- Se connecter à l'espace d'administration.
- Initialiser la base avec des produits de démonstration.

## Structure

| Chemin | Rôle |
|---|---|
| `index.php` | Accueil et catalogue |
| `cart.php` | Panier |
| `checkout.php` | Enregistrement de commande |
| `login.php` / `logout.php` | Connexion et déconnexion |
| `admin/dashboard.php` | Administration |
| `config.php` | Connexion MySQL |
| `setup_mysql.php` | Création des tables et données de démonstration |
| `assets/` | Ressources visuelles |

## Installation locale avec XAMPP

1. Installer XAMPP et démarrer Apache et MySQL.
2. Placer le projet dans `C:\xampp\htdocs\foottees`.
3. Créer une base MySQL depuis phpMyAdmin.
4. Adapter les paramètres de connexion dans `config.php` à cette base.
5. Ouvrir http://localhost/foottees/setup_mysql.php pour créer les tables et les données de démonstration.
6. Ouvrir http://localhost/foottees/ pour accéder à la boutique.

## Démonstration locale

Parcourir le catalogue, ajouter un article au panier et enregistrer une commande avec des coordonnées fictives. Explorer ensuite l'administration depuis la page de connexion.

Le script d'installation crée le compte local `admin@foottees.ma` avec le mot de passe `admin123`. Remplacer ces identifiants et retirer l'accès public au script d'installation avant une mise en ligne.

## Périmètre

Ce dépôt présente un projet pédagogique PHP/MySQL. L'enregistrement d'une commande ne constitue pas une intégration de paiement bancaire en ligne.
