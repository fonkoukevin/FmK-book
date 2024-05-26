# Projet Bookeo MVC
## Installation
* Vous devez tout d'abord créer un domaine local dans votre fichier hosts, puis modifier votre vhost d'Apache et redémarrer votre serveur.
* Dans votre dossier, récupérez les sources avec Git ou en les téléchargeant.
* Vous devez ensuite créer une base de données mysql et importer le fichier bookeo.sql.
* La base de données contient déjà un jeu de données avec également deux utilisateurs (un administrateur et un utilisateur) :
    * user@test.com, mot de passe : test (à ne pas utiliser sur un site en production :) )
    * admin@test.com, mot de passe : test (à ne pas utiliser sur un site en production :) )
* Modifiez le fichier db_config.php en y ajoutant les données de votre base de données.
* Assurez-vous que le site fonctionne en local.

## Contexte
Un prestataire a commencé à mettre en place le site Bookeo en utilisant une structure MVC. Ce site a pour but de permettre aux visiteurs de consulter des fichiers sur des livres, des bandes dessinées et des mangas, ainsi que de pouvoir commenter et noter ces fichiers.
https://vimeo.com/873226618/6f08f73e9b?share=copy

## L'existant
La navigation et la connexion sont déjà terminées. Pour les pages en cours, le code HTML a déjà été ajouté dans les différentes pages du dossier templates.

## Les tâches restantes
* Terminer l'affichage d'un livre
* Terminer l'ajout et la modification d'un livre.
* Terminer l'affichage de la liste des livres (et gérer la pagination).
* Terminer l'affichage des trois derniers livres sur la page d'accueil.
* Terminer la création d'un compte utilisateur.
* Terminer l'ajout de commentaires.
* Terminer la possibilité de pouvoir noter un livre.
* Gérer un CRUD en front (accessible uniquement aux admin) pour gérer les auteurs
* Gérer une nouvelle table genre (ex: aventure, policier, horreur etc.). On devra pouvoir associer plusieurs genres à un seul livre.

## Bonus
* Pouvoir filtrer les livres par catégorie
* Pouvoir effectuer une recherche sur le titre d'un livre (un seul mot clé)
* Pouvoir effectuer une recherche sur le titre d'un livre sur plusieurs mots clé
    * Il sera possible de faire cela en une seule requête (nécessite de modifier l'index du champ title)
