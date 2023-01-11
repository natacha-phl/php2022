<?php
require 'config/init.php';
/* 

Ici, vous pouvez requérir le fichier auth.php (si vous en êtes à cette étape)

*/

/*

Ici, incluez les fichiers des fonctions dont vous aurez besoin dans votre page

*/

 

include 'functions/cardCatalogue.php'; 

/*

Ici, insérez le traitement des formulaires,
             les requêtes à la base de données,
             la récupération des données

*/

// Récupération des produits pour affichage

$recupProduit = "SELECT * FROM produits";

$rqRecupProduit = $pdo->prepare($recupProduit);

$rqRecupProduit->execute();


$resultats = $rqRecupProduit->fetchAll(PDO::FETCH_ASSOC);


// Rechercher

if (isset($_POST['submitRechercher'])) {


    if (!empty($_POST['recherche'])){
        $idRecherche = $_POST['recherche'];
        $rechercheProduit = "SELECT * FROM produits where titre like '%$idRecherche%'"; 
        $rqRechercheProduit =  $pdo->prepare($rechercheProduit);
        $rqRechercheProduit->execute();
        $resultatsRecherche = $rqRechercheProduit->fetchAll(PDO::FETCH_ASSOC);

    }

    if (!empty($resultatsRecherche)){
        $messageValidation = ['Voici les resultat de votre recherche'];
    } else {
        $tableauErreur = ['Aucun produit coresspont a votre recherche'];
    }
    
};



include 'components/Head.html';
?>

    <title>Page catalogue</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage



-->

<h1>Tous les produits</h1>

<form action="catalogue.php" method="POST">
    <input type="text" name="recherche" id="recherche" placeholder="Entrer votre recherche">
    <input type="submit" name="submitRechercher" value="rechercher">
</form>

<?php

if (isset($_POST['submitRechercher'])) {

    if (!empty($resultatsRecherche)){
        card ($resultatsRecherche);
    } else { card ($resultats);
    }    

} else {
    card($resultats);
}
;

?>



<?php
include 'components/Footer.html';
?>