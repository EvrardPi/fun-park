<?php
if (!empty($_POST) && !empty($_POST['search'])) {
    extract($_POST);
    $search = strip_tags($search); // sécu

    require "Structure/Bdd/config.php"; // intègre la base de données

    // PARTIE REQUETE
    $reqr = $bdd->query("SELECT * FROM recherche WHERE titre LIKE '%$search%' OR contenu LIKE '%$search%' ORDER BY id");

    if ($reqr->rowCount() > 0) {
        while ($data = $reqr->fetch(PDO::FETCH_OBJ)) {
            echo '<hr>';
            echo '<h5>' . $data->titre . '</h5>';
            echo '<p>' . $data->contenu . '</p>';
        }
    } else {
        echo '<h3>Aucun resultat</h3>';
    }
} else {
    echo '<h3>Aucun resultat</h3>';
}
