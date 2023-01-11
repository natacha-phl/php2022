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

// Récupération du panier

$session = $_SESSION['id'];

$recupPanier = "SELECT * from panier where session = '$session'";
$rqRecupPanier = $pdo->prepare($recupPanier);
$rqRecupPanier->execute();
$resultatsPanier = $rqRecupPanier->fetchAll(PDO::FETCH_ASSOC);
//var_dump($resultatsPanier);

// Total panier + Quantite Panier
$totalPanier = [];
$quantitePanier = [];

foreach($resultatsPanier as $panier) {

    //var_dump($panier);

    // Quantite   
    array_push($quantitePanier,$panier['quantite']);
    //var_dump($quantitePanier);

    $totalQuantite = array_sum($quantitePanier);
    //var_dump($totalQuantite);

    //Total
    array_push($totalPanier, $panier['quantite'] * ($panier['prix']/100));
    //var_dump($totalPanier);   

    $totalCommande = array_sum($totalPanier);
    //var_dump($totalCommande);
        
}


// Insertion dans la BDD

if (isset($_POST['commander'])){
    if (!empty($_POST['email']) && !empty($_POST['adresse'])) {
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $adresse = $_POST['adresse'];
        $adresse = htmlentities($adresse, ENT_QUOTES);

        // Insertion dans la tables commandes_total
        $commandeTotal =  "INSERT INTO commandes_total (email, quantite_produit, total, session) VALUES ('$email', $totalQuantite, $totalCommande, '$session')";
        $rqCommandeTotal = $pdo->prepare($commandeTotal);
        $rqCommandeTotal->execute();
        
        // Récuperation de l'ID de la table commandes_total
        $recupIdCommande = "SELECT id FROM commandes_total WHERE session = '$session'"; 
        $rqrecupIdCommande = $pdo->prepare($recupIdCommande);
        $rqrecupIdCommande->execute();
        $rqrecupIdCommande =  $rqrecupIdCommande->fetchAll(PDO::FETCH_ASSOC);
        $idCommande = (int) $rqrecupIdCommande[0]['id'];

        // Insertion dans la table client
        $client =  "INSERT INTO clients (email, nom, adresse, id_commandes_total) VALUES ('$email', '$nom', '$adresse', $idCommande)";
        $rqClient = $pdo->prepare($client);
        $rqClient->execute();

        //Insertion dans la table commandes_details

        foreach($resultatsPanier as $panier){
            $produit = (int) $panier['produit'];
            $quantite = (int) $panier['quantite'];

            $commmandeDetail = "INSERT INTO commandes_detail (produit_id, produit_quantite, id_commandes_total) VALUES ($produit, $quantite, $idCommande)";
            $rqCommandeDetail = $pdo->prepare($commmandeDetail);
            $rqCommandeDetail->execute();
            }

            header("Location: validation.php?commande=$idCommande");
        
    }
}




include 'components/Head.html';
?>

    <title>Commander</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<h1>Valider votre</h1>

<form action="commande.php" method="POST">
    <label for="nom">Nom</label></br>
    <input type="text" name="nom" id="nom"></br>

    <label for="email">Email</label></br>
    <input type="email" name="email" id="email"></br>

    <label for="adresse">Adresse</label></br>
    <input type="text" name="adresse" id="adresse"></br>

    <input type="submit" name="commander" value="Commander">




</form>

<?php
include 'components/Footer.html';
?>