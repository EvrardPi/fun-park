<?php
session_start();
$pageTitle = "Modification";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

if ($getId != $_SESSION['id'] || empty($_GET['attrac'])) header("Location: index.php");

// PARTIE RECUP INFO USER
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
$idAttrac = intval($_GET['attrac']);

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


// PARTIE SÉLECTION DES INFOS DE L'ATTRACTION
$latt = $bdd->prepare('SELECT * FROM attractions WHERE id = ?');
$latt->execute(array($idAttrac));
$infoAttrac = $latt->fetch();

if (isset($_POST['formRegisAttrac'])) {

    if (isset($_FILES['avatarA']) && $_FILES['avatarA']['error'] == 0) {
        if ($_FILES['avatarA']['size'] >= 1000000) $erreurImg = "Taille de l'image trop grande";
        $infosfichier = pathinfo($_FILES['avatarA']['name']);
        $extension_upload = $infosfichier['extension'];
        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
        if (!in_array($extension_upload, $extensions_autorisees)) $erreurImg = "Le format doit être jpg, jpeg, gif ou png";
        if (empty($erreurImg)) {
            move_uploaded_file($_FILES['avatarA']['tmp_name'], '../Design/picture/' . basename($_FILES['avatarA']['name']));
            $upAt = $bdd->prepare('UPDATE attractions SET pic = ? WHERE id = ?');
            $upAt->execute(array($_FILES['avatarA']['name'], $idAttrac));
            header("Location: modify.php?id=" . $_SESSION['id'] . "&attrac=" . $idAttrac);
        }
    }

    if (isset($_POST['nameA']) && !empty($_POST['nameA'])) {
        $nameAtt = $_POST['nameA'];
        $upNA = $bdd->prepare('UPDATE attractions SET name = ? WHERE id = ?');
        $upNA->execute(array($nameAtt, $idAttrac));
?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }

    if (isset($_POST['capacityA']) && !empty($_POST['capacityA'])) {
        $capacAtt = $_POST['capacityA'];
        $upCA = $bdd->prepare('UPDATE attractions SET capacity = ? WHERE id = ?');
        $upCA->execute(array($capacAtt, $idAttrac));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }

    if (isset($_POST['typeA']) && !empty($_POST['typeA'])) {
        $typeAtt = $_POST['typeA'];
        $upTA = $bdd->prepare('UPDATE attractions SET type = ? WHERE id = ?');
        $upTA->execute(array($typeAtt, $idAttrac));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }

    if (isset($_POST['ageA']) && !empty($_POST['ageA'])) {
        $ageAtt = $_POST['ageA'];
        $upAA = $bdd->prepare('UPDATE attractions SET age_min = ? WHERE id = ?');
        $upAA->execute(array($ageAtt, $idAttrac));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }

    if (isset($_POST['statutA']) && !empty($_POST['statutA'])) {
        $statutAtt = $_POST['statutA'];
        $upSA = $bdd->prepare('UPDATE attractions SET statut = ? WHERE id = ?');
        $upSA->execute(array($statutAtt, $idAttrac));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }

    if (isset($_POST['openA']) && !empty($_POST['openA'])) {
        $openAtt = $_POST['openA'];
        $upOA = $bdd->prepare('UPDATE attractions SET open_hour = ? WHERE id = ?');
        $upOA->execute(array($openAtt, $idAttrac));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
    <?php
    }

    if (isset($_POST['closeA']) && !empty($_POST['closeA'])) {
        $closeAtt = $_POST['closeA'];
        $upCo = $bdd->prepare('UPDATE attractions SET close_hour = ? WHERE id = ?');
        $upCo->execute(array($closeAtt, $idAttrac));
    ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("La modification de l'attraction bien été prise en compte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
<?php
    }
}

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
<link href="Design/css/modify.css" rel="stylesheet" type="text/css">
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

                    <form method="post" enctype="multipart/form-data">

                        <div class="row">

                            <div class="col-4">
                                <img src="../Design/picture/<?php echo $infoAttrac['pic']; ?>">
                                <p class="info"></p>
                                <input type="file" name="avatarA">
                            </div>

                            <div class="col-8">
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Nom de l'attraction</p>
                                        <input name="nameA" class="form-control" placeholder="<?php echo ($infoAttrac['name']); ?>">
                                    </div>
                                    <div class="col">
                                        <p class="info">Capacité</p>
                                        <input type="number" name="capacityA" min="1" max="999" class="form-control" placeholder="<?php echo ($infoAttrac['capacity']); ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <p class="info">Âge minimum</p>
                                        <select name="ageA">
                                            <option><?php echo ($infoAttrac['age_min']); ?> (ans)</option>
                                            <?php for ($a = 0; $a < $countAges; $a++) { ?>
                                                <option><?php print_r($resultAges[$a]["age"]); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="errorr">
                                            <?php if (isset($erreurAge)) echo $erreurAge; ?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <p class="info">Type</p>
                                        <select name="typeA">
                                            <option><?php echo ($infoAttrac['type']); ?></option>
                                            <?php for ($o = 0; $o < $countTypes; $o++) { ?>
                                                <option><?php print_r($resultTypes[$o]["type"]); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="errorr">
                                            <?php if (isset($erreurType)) echo $erreurType; ?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <p class="info">Statut</p>
                                        <select name="statutA">
                                            <option><?php echo ($infoAttrac['statut']); ?></option>
                                            <?php for ($i = 0; $i < $countStatuts; $i++) { ?>
                                                <option><?php print_r($resultStatuts[$i]["statut"]); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="errorr">
                                            <?php if (isset($erreurStatut)) echo $erreurStatut; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <p class="info">Horaire d'ouverture</p>
                                        <input type="number" min="0" max="23" name="openA" class="form-control" placeholder="<?php echo $infoAttrac['open_hour']; ?> (h)">
                                    </div>
                                    <div class="col">
                                        <p class="info">Horaire de fermeture</p>
                                        <input type="number" min="0" max="23" name="closeA" class="form-control" placeholder="<?php echo $infoAttrac['close_hour']; ?> (h)">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div class="row">
                                    <div class="col">
                                        <?php if (isset($erreurImg)) echo $erreurImg; ?>
                                        <input type="submit" class="btn btn-info" name="formRegisAttrac" value="Modifier">
                                    </div>
                                    <div class="col">
                                        <a href="attractions.php?id=<?php print_r($idUser); ?>" class="btn btn-info">Annuler</a>
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