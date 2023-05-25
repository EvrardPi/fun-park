<?php
session_start();
$pageTitle = "Ajout d'attraction";
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

if (isset($_POST['formAjoutAttrac'])) {

    $nameA = $_POST['nameA'];
    $capacityA = $_POST['capacityA'];
    $typeA = $_POST['typeA'];
    $statutA = $_POST['statutA'];
    $ageA = $_POST['ageMinA'];
    $openA = $_POST['openA'];
    $closeA = $_POST['closeA'];
    $durationA = $_POST['durationA'];

    if (!preg_match('/^[a-zA-Z& ]{2,50}$/', $nameA)) $errorName = "Le nom de l'attraction n'est pas conforme";
    if ($capacityA < 0 || $capacityA > 999) $erreurCapacity = "Capacité invalide";
    if ($typeA == "Sélectionner un type") $erreurType = "Veuillez sélectionner un type";
    if ($ageA == "Sélectionner un âge") $erreurAge = "Veuillez sélectionner un âge";
    if ($statutA == "Sélectionner un statut") $erreurStatut = "Veuillez sélectionner un statut";
    if ($openA < 0 || $openA > 23) $erreurOpen = "Heure d'ouverture invalide";
    if ($closeA < 0 || $closeA > 23) $erreurClose = "Heure de fermeture invalide";
    if ($durationA < 1 || $durationA > 60) $erreurDuration = "Durée de l'attraction invalide";

    if (empty($errorName) && empty($erreurCapacity) && empty($erreurType) && empty($erreurAge) && empty($erreurStatut) && empty($erreurOpen) && empty($erreurClose) && empty($erreurDuration)) {
        $insNewAttrac = $bdd->prepare("INSERT INTO `attractions` (`id`, `name`, `capacity`, `duration`, `pic`, `open_hour`, `close_hour`, `type`, `statut`, `age_min`) VALUES (NULL, ?, ?, ?, '', ?, ?, ?, ?, ?)");
        $insNewAttrac->execute(array($nameA, $capacityA, $durationA, $openA, $closeA, $typeA, $statutA, $ageA));
        ?>
            <script>
                var idu = <?php echo json_encode($idUser); ?>;
                var create = alert("L'ajout de l'attraction bien été pris en compte.");
                document.location.href = "attractions.php?id=" + idu;
            </script>
    <?php
    }
}

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
<link href="Design/css/addattractions.css" rel="stylesheet" type="text/css">
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
                <div class="rat-form">
                    <form method="POST">

                        <div class="row">
                            <div class="col">
                            </div>
                            <div class="col">
                                <p class="info">Nom de l'attraction</p>
                                <input placeholder="Entrer un nom..." name="nameA" class="form-control">
                                <p class="errorr">
                                    <?php if (isset($errorName)) echo $errorName; ?>
                                </p>
                            </div>
                            <div class="col">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p class="info">Capacité</p>
                                <input type="number" value="1" min="1" max="999" name="capacityA" class="form-control">
                                <p class="errorr">
                                    <?php if (isset($erreurCapacity)) echo $erreurCapacity; ?>
                                </p>
                            </div>
                            <div class="col">
                                <p class="info">Durée de l'attraction (min)</p>
                                <input type="number" value="1" min="1" max="60" name="durationA" class="form-control">
                                <p class="errorr">
                                    <?php if (isset($erreurDuration)) echo $erreurDuration; ?>
                                </p>
                            </div>
                            <div class="col">
                                <p class="info">Horaire d'ouverture (h)</p>
                                <input type="number" value="0" min="0" max="23" name="openA" class="form-control">
                                <p class="errorr">
                                    <?php if (isset($erreurOpen)) echo $erreurOpen; ?>
                                </p>
                            </div>
                            <div class="col">
                                <p class="info">Horaire de fermeture (h)</p>
                                <input type="number" value="0" min="0" max="23" name="closeA" class="form-control">
                                <p class="errorr">
                                    <?php if (isset($erreurClose)) echo $erreurClose; ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p class="info">Âge minimum</p>
                                <select name="ageMinA">
                                    <option>Sélectionner un âge</option>
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
                                    <option>Sélectionner un type</option>
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
                                    <option>Sélectionner un statut</option>
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
                                <input type="submit" class="btn btn-info" name="formAjoutAttrac" value="Ajouter">
                            </div>
                            <div class="col">
                                <a href="attractions.php?id=<?php print_r($idUser); ?>" class="btn btn-info">Annuler</a>
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