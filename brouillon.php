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
        var_dump($TableauResultatRqEssai);
        $resultatRqEssai = $TableauResultatRqEssai[0]['essai'];

        if (password_verify($password, $resultatPasswordMatch[0]['password']) == true && $resultatRqEssai < 4 ) {

            $_SESSION['membre_connecté'] = true;
            $messageValidation = ['Vous êtes connectés'];

        } elseif (password_verify($password, $resultatPasswordMatch[0]['password']) == true && $resultatRqEssai >= 4 )  {
            array_push($tableauErreur, 'Votre compte est bloqué');
                    $_SESSION['membre_connecté'] = false;

        } elseif (password_verify($password, $resultatPasswordMatch[0]['password']) != true && $resultatRqEssai < 4) {
            array_push($tableauErreur, 'Votre compte est bloqué');
                    $_SESSION['membre_connecté'] = false;

        } elseif (password_verify($password, $resultatPasswordMatch[0]['password']) != true /*&& $resultatRqEssai < 5*/) {

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

        }  
    } else {
        array_push($tableauErreur, 'L\'email n\'existe pas');
    }    
    }  else {
    array_push($tableauErreur, 'Veuillez remplir tous les champs');
    }
}        