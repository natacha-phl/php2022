<?php
require 'config/init.php';
require 'config/auth.php';
/* 

Ici, vous pouvez requérir le fichier auth.php (si vous en êtes à cette étape)

*/

/*

Ici, incluez les fichiers des fonctions dont vous aurez besoin dans votre page

*/

include 'functions/card.php'; 
include 'functions/messageErreur.php';
include 'functions/messageValidation.php';


// Récup des produits pour affichage


$recupProduit = "SELECT * FROM produits";

$rqRecupProduit = $pdo->prepare($recupProduit);

$rqRecupProduit->execute();


$resultats = $rqRecupProduit->fetchAll(PDO::FETCH_ASSOC);

//var_dump($resultats);


// Supression des produits


if (isset($_GET['delete'])){

    $idProduit = $_GET['delete']; 
    $confirmationSuppression =  "<div>Confirmez vous la suppression du produit</div><button><a href=\"-index.php?deleteconfirmation&delete=". $idProduit . "\">Oui</a></button>"; 

        if (isset($_GET['deleteconfirmation'])) {
        $idProduit = $_GET['delete'];     
        $supprimerProduit = "DELETE FROM produits WHERE id='$idProduit'"; 
        $rqSupprimerProduit =  $pdo->prepare($supprimerProduit);
        $rqSupprimerProduit ->execute();
        $messageValidation = ['Le produit a bien été supprimé, veuilez raffraichir la page'];

    } ;
}    

// Redirection modification des produits

if (isset($_GET['modifier'])){

    $idProduit = $_GET['modifier']; 
    header("Location: modifier.php?modifier=$idProduit");
  
} ;

// Déconnexion


if (isset($_GET['deconnexion'])){
    if ($_GET['deconnexion'] == 1){
        $_SESSION['membre_connecté'] = false;
        header("Location: connexion.php?deconnexion=ok");
        }
} ;


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


// Trier 

if (isset($_POST['submitTri'])) {

    if ($_POST['trier'] == 'croissant') {

        $Tri = "SELECT *  FROM produits ORDER BY prix ASC";
        


    }

    if ($_POST['trier'] == 'decroissant') {

        $Tri = "SELECT *  FROM produits ORDER BY prix DESC";
        

    }

    if ($_POST['trier'] == 'recent') {

        $Tri = "SELECT *  FROM produits ORDER BY date_enregistrement DESC";
        

    }

    if ($_POST['trier'] == 'ancien') {

        $Tri = "SELECT * FROM produits ORDER BY date_enregistrement ASC";
       

    }

    $rqTri =  $pdo->prepare($Tri);
        $rqTri->execute();
        $resultatsTri = $rqTri->fetchAll(PDO::FETCH_ASSOC);
}




include 'components/Head.html';
?>

    <title>Tous les produits</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<button><a href="-index.php?deconnexion=1">Déconnexion</a></button>

<form action="-index.php" method="POST">
    <input type="text" name="recherche" id="recherche" placeholder="Entrer votre recherche">
    <input type="submit" name="submitRechercher" value="rechercher">
</form>

<form action="-index.php" method="POST">
    <select name="trier" id="trier">
        <option value="" >Trier par :</option>


        <option value="croissant" <?php 
        if (isset($_POST['submitTri'])) {
        if ($_POST['trier'] == 'croissant') {
            echo "selected";
        } 
        }; ?>>Prix Croissant</option>


        <option value="decroissant" <?php 
        if (isset($_POST['submitTri'])) {
        if ($_POST['trier'] == 'decroissant') {
            echo "selected";
        }
        }; ?>>Prix décroissant</option>


        <option value="recent" <?php 
        if (isset($_POST['submitTri'])) {
        if ($_POST['trier'] == 'recent') {
            echo "selected";
        }
        }; ?>>Plus récent</option>



        <option value="ancien" <?php 
        if (isset($_POST['submitTri'])) {
        if ($_POST['trier'] == 'ancien') {
            echo "selected";
        }
        }; ?>>Plus ancien</option>



    </select>
    <input type="submit" name="submitTri" value="Valider">
    
</form>

<?php 

if (isset($confirmationSuppression)){
    echo $confirmationSuppression;
};

if (!(empty($messageValidation))){
    messageValidation ($messageValidation);
};

if (!(empty($tableauErreur))){
    messageErreur ($tableauErreur);
};

if(!empty($resultatsRecherche))  {
    card ($resultatsRecherche);
}; 
?>

<h1>Tous les produits</h1>

<?php 

if (isset($_POST['submitTri'])) {

    if (!empty($resultatsTri)){
        card ($resultatsTri);
    }

    

} else { card ($resultats);

};

?>

<?php
include 'components/Footer.html';
?>