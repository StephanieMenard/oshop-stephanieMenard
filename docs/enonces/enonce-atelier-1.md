S06-E01 Atelier backoffice, Premières pages
Objectifs
Intégrer le code HTML/CSS statique fourni, dans l'architecture MVC du projet.
Créer nos premières pages dynamiques.
Etapes
1. Intégration du thème HTML/CSS dans les templates de l'application
🖌️ Ajouter l'intégration HTML/CSS fournie − dossier docs/integration/html-css du repo de ce matin − dans les templates de l'application oShop. Pas de données à cette étape.
Pour liste et ajout de Catégorie et Produit, soit 4 pages.
A faire : vous devrez donc créer routes, contrôleurs et templates associés, ajouter des liens dans la nav et vérifier que le HTML/CSS s'affiche correctement via le MVC fourni, qu'on a revu ce matin en détail.

2. Dynamisation des listes
⚙️ Dynamiser l'affichage des listes avec les données des tables associées à Catégorie et Produit et via les Models fournis dans l'appli.
A faire : depuis le contrôleur concerné, récupérer les données via le modèle. Les transmettre à la vue, modifier le template HTML statique : boucler sur les données et dynamiser le bloc de code HTML qui correspond à une ligne du tableau.

2bis. Dynamisation des listes en home du BackOffice
⚙️ Dynamiser l'affichage des listes de la page d'accueil du BackOffice avec les données des tables associées à Catégorie et Produit et via les Models fournis dans l'appli (si besoin, créer de nouvelles méthodes dans les Models).
2ter. Bonus : Pré-remplir la mise à jour d'un enregistrement
🔗 Créer un lien pour l'édition d'une Catégorie, dans la page liste.
Pré-remplir les champs du formulaire avec les données de la BDD pour la catégorie demandée.
Ne pas traiter la sauvegarde, on le fera plus tard.
♻️ Répétez l'opération pour un Produit.
A faire : Modifier le lien avec l'icône pencil pour renvoyer vers l'URL d'édition d'une catégorie, en transmettant l'identifiant de celle-ci. Récupérer la catégorie depuis le modèle, la transmettre à la vue et indiquer ses valeurs par défaut dans les champs du formulaire associé (attribut value).

3. Challenge solo ou à deux : Dico de routes
🛣️ Créer un document routes.md avec toutes les routes prévues pour le projet, en se basant sur les docs fournis avec le projet, docs/user_stories.md notamment.