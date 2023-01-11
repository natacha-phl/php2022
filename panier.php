<?php
require 'config/init.php';
/* 

Ici, vous pouvez requérir le fichier auth.php (si vous en êtes à cette étape)

*/

/*

Ici, incluez les fichiers des fonctions dont vous aurez besoin dans votre page

*/

include 'functions/cardPanier.php';
/* 


Ici, insérez le traitement des formulaires,
             les requêtes à la base de données,
             la récupération des données

*/

// Récupération du panier

$session = $_SESSION['id'];

$recupPanier = "SELECT * from panier where session = '$session'";
$rqRecupPanier = $pdo->prepare($recupPanier);
$rqRecupPanier->execute();
$resultatsPanier = $rqRecupPanier->fetchAll(PDO::FETCH_ASSOC);
//var_dump($resultatsPanier);

// Total panier
$totalPanier = [];

foreach($resultatsPanier as $panier) {
    
    array_push($totalPanier, $panier['quantite'] * ($panier['prix']/100));
    //var_dump($totalPanier);   
        
}


$totalCommande = array_sum($totalPanier);
//var_dump($totalCommande);


// Supression

if (isset($_GET['supprimer'])) {
    $id = (int) $_GET['supprimer'];
    $supprimerProduit = "DELETE FROM panier WHERE produit = $id";
    $rqSupprimerProduit = $pdo->prepare($supprimerProduit);
    $rqSupprimerProduit->execute();
    header("Location: panier.php");

}

//Modification

if (isset($_POST['modifier'])){
    if(!empty($_POST['quantite'])){
        (int) $quantite = $_POST['quantite'];
        (int) $produit = $_POST['produit'];
        (int) $id = $_POST['id'];
        $modifQuantite = "UPDATE panier SET quantite = $quantite WHERE id = $id and produit = $produit";
        $rqModifQuantite = $pdo->prepare($modifQuantite);
        $rqModifQuantite->execute();
    }
}



include 'components/Head.html';
?>

    <title>Mon panier</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<h1>Mon panier</h1>
<?php

if(!empty($resultatsPanier)){



card ($resultatsPanier);
echo 
'<div>Total '.$totalCommande.' : €</div></br>
<button><a href="commande.php?panier='.$panier['session'].'">Commander</a></button>';

}

?>

<?php
include 'components/Footer.html';
?>