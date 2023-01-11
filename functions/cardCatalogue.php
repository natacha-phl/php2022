<?php

function card ($resultats) {

    foreach ($resultats as $product) {
        echo
        '<div style="margin: 20px;">
            <div> <img style="max-height: 130px;" src="' . $product ['image'] . '" alt="">
            </div>
        
            <h1>' . $product ['titre'] . '</h1>
            <div>
                    <p>' . $product ['description'] . '</p>
                    <p>' . $product ['prix'] . '</p>
            </div>
            <button><a href="produit.php?produit='.$product['id'].'">Voir</a></button>    
        </div>';
    }
}



?>