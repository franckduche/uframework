µFramework project
==================

Par Mathieu Coissard et Franck Duché avec les travaux pratiques de William Durand.

Outils et langages utilisés :
-----------------------------

* Vagrant pour utiliser une VM.
* Editeur de texte simple pour l'écriture du code.
* Composer pour gérer les dépendances du projet.
* PHPUnit pour les tests unitaires.
* Goutte pour un test fonctionnel.
* Negotiation pour déterminer le format de réponse.

Chronologie :
-------------

1.	Création d'un autoloader pour en comprendre le principe (+ notion de namespace, casse, cache).
2.	Récupération du code de base et de l'architecture des dossiers du Framework.
3.	Le Framework se veut *REST*. Donc utilisation des verbes *HTTP* : `POST`, `GET` et `DELETE`.
	Ecriture des routes du projet : `/statuses` et `/statuses/:id` suffisent.
	Implémentation des contrôleurs qui sont ici des closures appelées avec $app.
	Pour représenter la requête, création d'une classe `Request`.
4.	Pour représenter la réponse, création d'une classe `Response`.
5.	Ajouter d'une connexion à une base de données, pour remplacer la sauvegarde dans un fichier JSON.
	Création de tests unitaires.
6.	Ajout d'un module simple d'authentification avec un pare-feu.
	Création de tests fonctionnels.