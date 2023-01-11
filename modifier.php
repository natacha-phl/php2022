<?php
require 'config/init.php';
require 'config/auth.php';
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


if (isset($_POST['modifierProduct'])) { 
   
    $idProduit = $_POST['id']; 
    $messageValidation = [];
 
        
        if (!(empty($_POST['titre']))){
            $titre = htmlentities($_POST['titre'], ENT_QUOTES);
            $modifTitre = "UPDATE produits SET titre='$titre' where id=$idProduit";
            $rqModifTitre = $pdo->prepare($modifTitre);
            $rqModifTitre->execute();
            array_push($messageValidation,'Le titre a bien été modifié' );

            }
        
            if (!(empty($_POST['prix']))){
                $prix = htmlentities($_POST['prix'], ENT_QUOTES);
                $modifPrix = "UPDATE produits SET prix=$prix where id=$idProduit";
                $rqModifPrix = $pdo->prepare($modifPrix);
                $rqModifPrix->execute();
                array_push($messageValidation,'Le prix a bien été modifié' );
        
            }    

            if (!(empty($_POST['description']))){
                $description = htmlentities($_POST['description'], ENT_QUOTES);
                $modifDescription = "UPDATE produits SET description='$description' where id=$idProduit";
                $rqModifDescription = $pdo->prepare($modifDescription);
                $rqModifDescription->execute();
                array_push($messageValidation,'Le description a bien été modifié' );
        
            }         
};
       
      

include 'components/Head.html';
?>

    <title>Modifier le produit</title>
    
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

?>

<h1>Modifier le produit</h1>

<form action="modifier.php" method="POST">
    
<label for="titre">Nouveau titre</label></br>
<input type="text" name="titre" id="titre"></br>


<label for="description">Nouvelle description</label></br>
<input type="text" name="description" id="description"></br>

<label for="prix">Nouveau prix</label></br>
<input type="number" name="prix" id="prix"></br>

<input type="hidden" name="id" id="id" value ="<?php 
if (isset($_GET['modifier'])){
   echo $_GET['modifier'];
} 
else { 
    echo $_POST['id'];
}
    ?>">
<input type="submit" name="modifierProduct" value="Envoyer"></br>


</form>


<?php
include 'components/Footer.html';
?>

