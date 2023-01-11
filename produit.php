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

// Afficher le produit

if (isset($_GET['produit'])) {

    $id = $_GET['produit'];

    // Verification si le produit existe

    $verifExiste = "SELECT COUNT(*) from produits WHERE id = $id";
    $rqVerifExiste = $pdo->prepare($verifExiste);
    $rqVerifExiste->execute();
    $resultatsRqVerifExist = $rqVerifExiste->fetchAll(PDO::FETCH_ASSOC);
    $resultatsRqVerifExist = $resultatsRqVerifExist[0]['COUNT(*)'];

    // Récupération si le produit existe
    if ($resultatsRqVerifExist === 1) {


        $recupProduit = "SELECT * from produits WHERE id = $id";
        $rqRecupProduit = $pdo->prepare($recupProduit);
        $rqRecupProduit->execute();
        $resultats = $rqRecupProduit->fetchAll(PDO::FETCH_ASSOC);
        $resultats = $resultats[0];
        //var_dump($resultats);

    }
}

// Ajout au panier

if (isset($_POST['ajoutPanier'])) {

    if (!empty($_POST['quantite'])) {


        $session = $_POST['session'];
        $produit = (int)$_POST['produit'];
        $quantite = (int)$_POST['quantite'];
        $prix = (int)$_POST['prix'];

        // On verifie si le produit est déja dans le panier

        $verifPanier = "SELECT COUNT(*) FROM panier WHERE  produit = $produit and session = '$session'";
        $rqVerifPanier = $pdo->prepare($verifPanier);
        $rqVerifPanier->execute();
        $resultatVerifPanier = $rqVerifPanier->fetchAll(PDO::FETCH_ASSOC);
        $resultatVerifPanier = $resultatVerifPanier[0]['COUNT(*)'];
        //var_dump($resultatVerifPanier);
    

            if ($resultatVerifPanier === 0) {

            $ajoutPanier = "INSERT INTO panier (session, produit, quantite, prix) VALUES ('$session', $produit, $quantite, $prix*100)";
            $rqAjoutpanier = $pdo->prepare($ajoutPanier);
            $rqAjoutpanier->execute();

            $recupPanier = "SELECT * FROM panier WHERE session = '$session'";
            $RqRecupPanier= $pdo->prepare($recupPanier);
            $RqRecupPanier->execute();
            $resultatsPanier = $RqRecupPanier->fetchAll(PDO::FETCH_ASSOC);

        } 

        if ($resultatVerifPanier === 1) {

            $ajoutPanier = "UPDATE panier SET quantite = quantite +$quantite WHERE produit = $produit and session = '$session'";
            $rqAjoutpanier = $pdo->prepare($ajoutPanier);
            $rqAjoutpanier->execute();
        }
    }

}




include 'components/Head.html';
?>

    <title><?php $resultats['titre']?></title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<main>

<?php 

if ($resultatsRqVerifExist===1) {

echo 
'<div style="margin: 20px;">
        <div> 
            <img style="max-height: 130px;" src="'. $resultats['image'].'" alt="">
        </div>
        
        <h1>'.$resultats['titre'].'</h1>
        <div>
            <p>'. $resultats['description']. '</p>
            <p>'. $resultats['prix'].'€</p>
        </div>
</div>

<form action="produit.php?produit='.$id.'" method="POST">
    <select name="quantite" id="quantite">
        <option value="" >Quantité :</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
    </select>
    <input type="hidden" name="session" id="session" value="'.$_SESSION['id'].'">
    <input type="hidden" name="produit" id="produit" value="'.$id.'">
    <input type="hidden" name="prix" id="prix" value="'.$resultats['prix'].'">

    <input type="submit" name="ajoutPanier" value="Ajouter au panier">'

;


} else {
    header("Location: catalogue.php");
}

?>

</main>
       

<?php
include 'components/Footer.html';
?>