<?php 


function messageValidation (array $messageValidation) {
    foreach ($messageValidation as $message) {
        $messageAffichiche = $message;
        echo '<div><p style= "color:green;" >' . $messageAffichiche . '</p></div>';
    }

    
   
};


?>

