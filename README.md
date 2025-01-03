# Blog-plateform


## Contexte du Projet

Le projet consiste à mettre en place une plateforme collaborative où les développeurs et passionnés de technologie peuvent s'inscrire, écrire et partager des articles. Le système intègre une gestion des utilisateurs, des articles, des catégories et des tags, avec un back office pour les administrateurs et un front office pour les utilisateurs. Le but est de permettre une navigation fluide et optimisée pour découvrir du contenu de qualité, tout en offrant un tableau de bord puissant pour les administrateurs.

## Fonctionnalités Clés

### Partie Back Office (Administrateurs)

1. **Gestion des Catégories :**
   - Création, modification et suppression des catégories.
   - Association de plusieurs articles à une catégorie.
   - Visualisation des statistiques des catégories via des graphiques interactifs.

2. **Gestion des Tags :**
   - Création, modification et suppression des tags.
   - Association de tags aux articles pour une recherche précise.
   - Visualisation des statistiques des tags sous forme de graphiques interactifs.

3. **Gestion des Utilisateurs :**
   - Consultation et gestion des profils utilisateurs.
   - Attribution de permissions aux utilisateurs pour devenir auteurs.
   - Suspension ou suppression des utilisateurs en cas de non-respect des règles.

4. **Gestion des Articles :**
   - Consultation, acceptation ou refus des articles soumis.
   - Archivage des articles inappropriés.
   - Consultation des articles les plus lus.

5. **Statistiques et Tableau de Bord :**
   - Affichage détaillé des entités : utilisateurs, articles, catégories, tags.
   - Visualisation des 3 meilleurs auteurs (basé sur les articles publiés ou lus).
   - Graphiques interactifs pour les catégories et les tags.
   - Consultation des articles les plus populaires.

6. **Pages Détail :**
   - **Single Page Article** : Affichage détaillé d’un article (contenu, catégories, tags, auteur).
   - **Single Page Profil** : Visualisation du profil utilisateur.

### Partie Front Office (Utilisateurs)

1. **Inscription et Connexion :**
   - Création de compte avec des informations de base (nom, e-mail, mot de passe).
   - Connexion sécurisée avec redirection selon le rôle (admin vers le tableau de bord, utilisateur vers la page d'accueil).

2. **Navigation et Recherche :**
   - Barre de recherche interactive pour trouver des articles, catégories ou tags.
   - Navigation dynamique entre les articles et les catégories.

3. **Affichage du Contenu :**
   - Derniers articles ajoutés, affichés sur la page d'accueil ou dans une section dédiée.
   - Dernières catégories ajoutées ou mises à jour, affichées pour une découverte rapide.
   - Redirection vers une page unique d’article pour afficher son contenu, ses catégories et tags associés, ainsi que les informations sur l'auteur.

4. **Espace Auteur :**
   - Création, modification et suppression d'articles.
   - Association d’une seule catégorie et de plusieurs tags à un article.
   - Gestion des articles publiés depuis un tableau de bord personnel.

## Technologies Utilisées

- **Langage** : PHP 8 (Programmation Orientée Objet)
- **Base de données** : MySQL (avec PDO comme driver pour l'interaction avec la base de données)
- **Frontend** : HTML, CSS (avec Tailwind CSS pour la mise en page)
- **Backend** : PHP (avec PDO pour l'accès à la base de données)

## Installation

1. **Clonez le Repository :**
   ```bash
   git clone (https://github.com/AbdeljalilElouafi/Blog-plateform.git)
