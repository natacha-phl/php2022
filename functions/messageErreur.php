
<?php


function messageErreur (array $tableauErreur) {
    foreach ($tableauErreur as $message) {
        $messageAffichiche = $message;
        echo '<div><p style= "color:red;" >' . $messageAffichiche . '</p></div>';
    }

    
   
}; 

?>
