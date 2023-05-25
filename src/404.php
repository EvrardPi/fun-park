<?php
session_start();
$pageTitle = "404 Not found";
?>

<!DOCTYPE html>
<html lang="fr">

<?php
require "Structure/Head/head.php"; //intègre le head
?>
<link href="Design/css/404.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <img src="Design/picture/abandon.jpg">
            </div>
            <div class="col">
                <h1>404</h1>
                <p>Oups... On dirait que vous êtes perdu. <br> Vous devriez vous rediriger en lieu sûr</p>
                <center>
                    <a class="btn btn-dark" href="javascript:history.go(-1)">Retour en lieu sûr</a>
                </center>
            </div>
        </div>
    </div>
    </div>
</body>