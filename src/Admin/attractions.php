<?php
session_start();
$pageTitle = "Attractions";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

if ($getId != $_SESSION['id']) header("Location: index.php");

// PARTIE SELECT INFO USER
$stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
$stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
$result = $stmt->execute();
$infoUser = $stmt->fetch();

// PARTIE VARIABLES
$idUser = $infoUser['id'];
$mailUser = $infoUser['mail'];
$nameUser = $infoUser['name'];
$firstNameUser = $infoUser['firstname'];
$roleUser = $infoUser['role'];

// PARTIE AFFICHAGE DE TOUS LES STATUTS
$statuts = $bdd->prepare("SELECT * FROM statut_attractions");
$statuts->execute();
$resultStatuts = $statuts->fetchAll();
$countStatuts = count($resultStatuts);

// PARTIE AFFICHAGE DE TOUS LES AGES
$ages = $bdd->prepare("SELECT * FROM age_min_attractions");
$ages->execute();
$resultAges = $ages->fetchAll();
$countAges = count($resultAges);

// PARTIE AFFICHAGE DE TOUS LES TYPES
$types = $bdd->prepare("SELECT * FROM type_attractions");
$types->execute();
$resultTypes = $types->fetchAll();
$countTypes = count($resultTypes);

// PARTIE AFFICHAGE DE TOUTES LES ATTRACTIONS
$attracs = $bdd->prepare("SELECT * FROM attractions");
$attracs->execute();
$resultAttracs = $attracs->fetchAll();
$nbAttracs = count($resultAttracs);

if (isset($_POST['formType'])) {
    if (!preg_match('/^[a-zA-Z& ]{2,50}$/', $_POST['typeA'])) $errorType = "Le type n'est pas conforme";
    if (empty($errorType)) {
        $typeA = $_POST['typeA'];
        $insType = $bdd->prepare("INSERT INTO `type_attractions` (`type`) VALUES (?)");
        $insType->execute(array($typeA));
?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("L'ajout du type bien été pris en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }
}

if (isset($_POST['formStatut'])) {
    if (!preg_match('/^[a-zA-Z& ]{2,50}$/', $_POST['statutA'])) $errorStatut = "Le statut n'est pas conforme";
    if (empty($errorStatut)) {
        $statutA = $_POST['statutA'];
        $insStatut = $bdd->prepare("INSERT INTO `statut_attractions` (`statut`) VALUES (?)");
        $insStatut->execute(array($statutA));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("L'ajout du statut bien été pris en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }
}

if (isset($_POST['formAge'])) {
    if (!intval($_POST['ageMinA'])) $errorAge = "L'âge n'est pas conforme";
    if (empty($errorAge)) {
        $ageA = intval($_POST['ageMinA']);
        $insAge = $bdd->prepare("INSERT INTO `age_min_attractions` (`age`) VALUES (?)");
        $insAge->execute(array($ageA));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("L'ajout de l'âge bien été pris en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
<?php
    }
}

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
<link href="Design/css/attractions.css" rel="stylesheet" type="text/css">
</head>

<?php require "Structure/Sidebar/sidebar.php"; // intègre la sidebar
?>
<li class="profile">
    <div class="profile-details">
        <img src="Design/picture/smile.svg" alt="profileImg">
        <div class="name_job">
            <div class="name"><?php echo ($nameUser . " " . $firstNameUser); ?></div>
            <div class="job"><?php echo ($roleUser); ?></div>
        </div>
    </div>
    <a class="nobackground" href="logout.php"><i class='bx bx-log-out' id="log_out"></i></a>
</li>
</ul>
</div>

<section class="home-section">

    <h3>
        <?php echo $pageTitle ?>
    </h3>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="login-form">

                    <form method="post">
                        <div class="row">
                            <div class="col">

                                <h4>Récapitulatif des attractions</h4>

                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Capacité</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Heure Ouverture</th>
                                            <th scope="col">Heure Fermeture</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Statut</th>
                                            <th scope="col">Âge Mini</th>
                                            <th scope="col"></th>
                                            <th scope="col"><a class="btn btn-info" href="addattractions.php?id=<?php echo $idUser; ?>"><i class='bx bx-message-alt-add'></i></a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($t = 0; $t < $nbAttracs; $t++) { ?>
                                            <tr class="table-info">
                                                <td><?php print_r($resultAttracs[$t]["name"]); ?></td>
                                                <td><?php print_r($resultAttracs[$t]["capacity"]); ?></td>
                                                <td><img src="../Design/picture/<?php echo $resultAttracs[$t]['pic']; ?>"></td>
                                                <td><?php print_r($resultAttracs[$t]["open_hour"]); ?></td>
                                                <td><?php print_r($resultAttracs[$t]["close_hour"]); ?></td>
                                                <td><?php print_r($resultAttracs[$t]["type"]); ?></td>
                                                <td><?php print_r($resultAttracs[$t]["statut"]); ?></td>
                                                <td><?php print_r($resultAttracs[$t]["age_min"]); ?></td>
                                                    <td><a class="btn btn-warning" href="modify.php?id=<?php echo $idUser; ?>&attrac=<?php echo $resultAttracs[$t]['id']; ?>"><i class='bx bx-wrench'></i></a></td>
                                                    <td><a class="btn btn-danger" href="delete.php?id1784=<?php echo ($resultAttracs[$t]['id']); ?>"><i class='bx bx-trash'></i></a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <hr>

                                <h5>Modération des filtres</h5>

                                <div class="row">
                                    <div class="col">
                                        <input name="statutA" class="form-control" placeholder="Entrer un statut">
                                        <p class="errorr"><?php if (isset($errorStatut)) echo $errorStatut; ?></p>
                                        <input name="formStatut" type="submit" class="btn btn-info" value="Ajouter le statut">
                                        <table class="table table-hover">
                                            <tbody>
                                                <?php for ($a = 0; $a < $countStatuts; $a++) { ?>
                                                    <tr class="table-info">
                                                        <td><?php print_r($resultStatuts[$a]["statut"]); ?></td>
                                                        <td><a class="btn btn-danger" href="delete.php?statut1784=<?php echo ($resultStatuts[$a]['statut']); ?>"><i class='bx bx-trash'></i></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col">
                                        <input name="typeA" class="form-control" placeholder="Entrer un type">
                                        <p class="errorr"><?php if (isset($errorType)) echo $errorType; ?></p>
                                        <input name="formType" type="submit" class="btn btn-info" value="Ajouter le type">
                                        <table class="table table-hover">
                                            <tbody>
                                                <?php for ($o = 0; $o < $countTypes; $o++) { ?>
                                                    <tr class="table-info">
                                                        <td><?php print_r($resultTypes[$o]["type"]); ?></td>
                                                        <td><a class="btn btn-danger" id="exception2" href="delete.php?type1784=<?php echo ($resultTypes[$o]['type']); ?>"><i class='bx bx-trash'></i></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col">
                                        <input name="ageMinA" class="form-control" type="number" placeholder="Entrer un âge minimum">
                                        <p class="errorr"><?php if (isset($errorAge)) echo $errorAge; ?></p>
                                        <input name="formAge" type="submit" class="btn btn-info" value="Ajouter l'âge">

                                        <table class="table table-hover">
                                            <tbody>
                                                <?php for ($u = 0; $u < $countAges; $u++) { ?>
                                                    <tr class="table-info">
                                                        <td><?php print_r($resultAges[$u]["age"]); ?></td>
                                                        <td><a class="btn btn-danger" id="exception" href="delete.php?agemin1784=<?php echo ($resultAges[$u]['age']); ?>"><i class='bx bx-trash'></i></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</section>

<script src="Structure/Sidebar/sidebar.js"></script>
</body>

</html>