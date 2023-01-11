<?php 
require 'config/init.php';
require 'config/auth.php';
include 'functions/messageErreur.php';
include 'functions/messageValidation.php';
/* 

Ici, vous pouvez requérir le fichier auth.php (si vous en êtes à cette étape)

*/

/*

Ici, incluez les fichiers des fonctions dont vous aurez besoin dans votre page

*/

/* 


Ici, insérez le traitement des formulaires,
             les requêtes à la base de données,
             la récupération des données

*/

// Traitement formulaire PRODUIT

if (isset($_POST['submitProduct'])) { 
   
    if (!(empty($_POST['titre']) || empty($_POST['description']) || empty($_POST['prix']))) {
       
        $titre = htmlentities($_POST['titre'], ENT_QUOTES);
        $description = htmlentities($_POST['description'], ENT_QUOTES);;
        $prix = htmlentities($_POST['prix'], ENT_QUOTES);

        $ajoutProduit = "INSERT INTO produits (titre, description, prix) VALUES ('$titre', '$description', $prix)";

        $rqAjoutProduit = $pdo->prepare($ajoutProduit);

        $rqAjoutProduit->execute();

        $messageValidation = ['Votre produit a bien été ajouté'];

    } else {
        $tableauErreur = ['Veuillez remplir tous les champs'];
    }
};




include 'components/Head.html';
?>

    <title>Créer un produit</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->
<?php

if (!(empty($messageValidation))){
    messageValidation ($messageValidation);
}

if (!(empty($tableauErreur))){
    messageErreur ($tableauErreur);
}


    
?>

    <h1>Créer un produit</h1>

    <form action="creer.php" method="POST">
    <label for="titre">Titre</label></br>
    <input type="text" name="titre" id="titre"></br>


    <label for="description">Description</label></br>
    <input type="text" name="description" id="description"></br>

    <label for="prix">Prix</label></br>
    <input type="number" name="prix" id="prix"></br>


    <input type="submit" name="submitProduct" value="Envoyer"></br>


    </form>

<?php
include 'components/Footer.html';
?>