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

if (isset($_POST['connexion'])) {

    $tableauErreur = [];
    $messageValidation = [];

    if (!(empty($_POST['email'])) && !empty($_POST['password'])) {

        $email = htmlentities($_POST['email'], ENT_QUOTES);
        $password = htmlentities($_POST['password'], ENT_QUOTES);
        $emailExiste = "SELECT COUNT(*) FROM users WHERE email = '$email'";
        $rqEmailExiste = $pdo->prepare($emailExiste);
        $rqEmailExiste->execute();
        $resultatExisteEmail = $rqEmailExiste->fetchAll(PDO::FETCH_ASSOC); //fetchall permet de selectionner le premier élément
        $passwordMatch = "SELECT password FROM users WHERE email = '$email'";
        $rqPasswordMatch = $pdo->prepare($passwordMatch);
        $rqPasswordMatch->execute();
        $resultatPasswordMatch = $rqPasswordMatch->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($resultatPasswordMatch);
        


        if ($resultatExisteEmail[0]["COUNT(*)"] === 1) {

            $recupEssai = "SELECT essai from users WHERE email = '$email'";
            $rqEssai = $pdo->prepare($recupEssai);
            $rqEssai->execute();
            $TableauResultatRqEssai = $rqEssai->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($TableauResultatRqEssai);
            $resultatRqEssai = $TableauResultatRqEssai[0]['essai'];

            if (password_verify($password, $resultatPasswordMatch[0]['password']) == true && $resultatRqEssai < 4 ) {

                $_SESSION['membre_connecté'] = true;
                $messageValidation = ['Vous êtes connectés'];
                $reinitialisationMdp = "UPDATE users SET essai = 0 WHERE email = '$email'";
                $rqreinitialisationMdp = $pdo->prepare($reinitialisationMdp);
                $rqreinitialisationMdp->execute();
                

            } elseif (password_verify($password, $resultatPasswordMatch[0]['password']) == true && $resultatRqEssai >= 4 )  {
                array_push($tableauErreur, 'Votre compte est bloqué');
                        $_SESSION['membre_connecté'] = false;

            } elseif (password_verify($password, $resultatPasswordMatch[0]['password']) != true && $resultatRqEssai < 4) {
                array_push($tableauErreur, 'Le mot de passe est incorrect');
                $mauvaisMdp = "UPDATE users SET essai = essai +1 WHERE email = '$email'";
                $rqmauvaisMdp = $pdo->prepare($mauvaisMdp);
                $rqmauvaisMdp->execute(); 
                $i = $resultatRqEssai+1;
                if($i<5){
                    $restant = 5-$i;
                    $messageRestant = 'Il vous reste ' . $restant . ' essai(s)';
                    array_push($tableauErreur, $messageRestant);
                };   
        }  else {
            array_push($tableauErreur, 'Votre compte est bloqué');
        }
        }  else {
            array_push($tableauErreur, 'L\'email n\'existe pas');
        }  
    } else {
        array_push($tableauErreur, 'Veuillez remplir tous les champs');
        }
};     





include 'components/Head.html';
?>

<title>Connexion</title>

<?php
include 'components/Header.html';
?>

<!--
    

Contenu de la page, fonctions d'affichage


-->

<h1>Connexion</h1>

<div>
    <p>
        <?php
        if (isset(($_GET['deconnexion'])) && $_GET['deconnexion'] == 'ok') {
            $messageValidation = ['Vous êtes bien déconnecté'];
        };

        if (!(empty($messageValidation))) {
            messageValidation($messageValidation);
        };

        if (!(empty($tableauErreur))) {
            messageErreur($tableauErreur);
        };
        ?>
    </p>
</div>


<form action="connexion.php" method="POST">

    <label for="email">Email</label>
    <input type="email" name="email" id="email">

    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password">

    <input type="submit" name="connexion" value="Envoyer">
</form>

<?php
include 'components/Footer.html';
?>