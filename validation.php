<?php
require 'config/init.php';
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

if (isset($_GET['commande'])) {
    $idCommande = $_GET['commande'];
    $recupCommande = "SELECT * from commandes_total WHERE id = $idCommande";
    $rqRecupCommande = $pdo->prepare($recupCommande);
    $rqRecupCommande->execute();
    $commande = $rqRecupCommande->fetchAll(PDO::FETCH_ASSOC);
    $commande = $commande[0];
    $email = $commande['email'];
    // Recup client

}


include 'components/Head.html';
?>

    <title>Confirmation de commande</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<h1>Recapitulatif de votre commande</h1>

<?php

if (!empty($_GET['commande'])){
    echo '<h2>Confirmation de votre commande '. $idCommande.'</h2></br>
    <p>Email : '.$email.'</p>';    


}

?>

<?php
include 'components/Footer.html';
?>