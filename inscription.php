<?php
require 'config/init.php';
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

if (isset($_POST['inscription'])) { 
    $tableauErreur = [];
    $messageValidation = [];
   
    if (!(empty($_POST['email'])) && !empty($_POST['password'])){
       
        $email = htmlentities($_POST['email'], ENT_QUOTES); 
        $password = htmlentities($_POST['password'], ENT_QUOTES); 
        $passwordsecure = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $nom = htmlentities($_POST['nom'], ENT_QUOTES);
        $numberOfpassword = strlen($password);
        /* BONUS numero 5 str_spit()
        
        var_dump(str_split($email));
        $emailFormatVerif = str_split($email);
        if (in_array('@',$emailFormatVerif)) {
            $emailFormat = 'ok';
        } else {
            $emailFormat = 'not ok';
        }
        var_dump($emailFormat);

        */

        if ($numberOfpassword >= 8  && $numberOfpassword <=12  ) {


            $verifEmail = "SELECT COUNT(*) FROM users WHERE email = '$email'";
            $rqEmail = $pdo->prepare($verifEmail);
            $rqEmail->execute();
            $resultatVerifEmail = $rqEmail->fetchAll(PDO::FETCH_ASSOC); //fetchall permet de selectionner le premier élément
            //echo var_dump($resultatVerifEmail);


            if ($resultatVerifEmail[0]["COUNT(*)"] === 0 && strpos($email, '@') !== false) {

                $ajoutUtilisateur = "INSERT INTO users (email, nom, password) VALUES ('$email', '$nom', '$passwordsecure')";
 
                $rqAjoutUtilisateur = $pdo->prepare($ajoutUtilisateur);
 
                $rqAjoutUtilisateur->execute();

                array_push($messageValidation,'Votre compte a bien été crée');

            } else {
                array_push($tableauErreur,'Cet email existe déjà ou veillez rentrer un email valide');
                
            }
        }  else {
            array_push($tableauErreur,'Veuillez choisir un mot de  entre 8 et 13 caractères'); 
        }

    } else {
            array_push($tableauErreur, 'Veuillez remplir les champs requis');
    }
};




include 'components/Head.html';
?>

    <title>Inscription</title>
    
<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<h1>Créer votre compte</h1>

<div><p>
    <?php 
    if (!(empty($messageValidation))){
        messageValidation ($messageValidation);
    }
    
    if (!(empty($tableauErreur))){
        messageErreur ($tableauErreur);
    }
        ?>
 </p></div>

 <form action="inscription.php" method="POST">

<label for="nom">Nom</label></br>
<input type="text" name="nom" id="nom"></br>


<label for="email">Email</label></br>
<input type="text" name="email" id="email"></br>

<label for="password">Mot de passe</label></br>
<input type="text" name="password" id="password"></br>

<input type="hidden" name="crsf" id="crsf" value="">

<input type="submit" name="inscription" value="Envoyer"></br>
</form>




<?php
include 'components/Footer.html';
?>