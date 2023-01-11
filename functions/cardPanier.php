<?php

function card ($resultatsPanier) {

    foreach ($resultatsPanier as $panier) {
        echo
        '<div style="margin: 20px;">
            <h1> Produit n°' . $panier ['produit'] . '</h1>
            <div>
                    <p>' . $panier ['prix']/100 . '€ x'.$panier['quantite'].'</p>
            </div>
            <button><a href="panier.php?supprimer='.$panier['produit'].'">Supprimer</a></button> 
            <form action="panier.php?panier='.$panier['id'].'" method="POST">
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
                <input type="hidden" name="id" value="'.$panier['id'].'">
                <input type="hidden" name="produit" value="'.$panier['produit'].'">
                <input type="submit" name="modifier" value="Modifier">
            </form>        
        </div>';
    }
}



?>