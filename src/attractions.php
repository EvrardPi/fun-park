<?php
session_start();
$pageTitle = "Attractions";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId == $_SESSION['id']) {

    // PARTIE INFO USER
    $stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
    $stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
    $result = $stmt->execute();
    $infoUser = $stmt->fetch();
    $idUser = $infoUser['id'];
    $mailUser = $infoUser['mail'];
    $nameUser = $infoUser['name'];
    $firstNameUser = $infoUser['firstname'];
    $roleUser = $infoUser['role'];

    // PARTIE AFFICHAGE DE TOUTES LES ATTRACTIONS
    $attracs = $bdd->prepare("SELECT * FROM attractions");
    $attracs->execute();
    $resultAttracs = $attracs->fetchAll();
    $nbAttracs = count($resultAttracs);

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

        <div class="container">

            <h3>
                <?php echo $pageTitle ?>
            </h3>

            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <form>
                            <select id="statuts" name="statuts" onchange="showStatut(idu,this.value,document.getElementById('types').value,document.getElementById('ages').value)">
                                <option value="">Sélectionner un statut :</option>
                                <?php
                                for ($a = 0; $a < $countStatuts; $a++) {
                                ?>
                                    <option><?php print_r($resultStatuts[$a]["statut"]); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </form>
                    </div>
                    <div class="col">
                        <form>
                            <select id="types" name="types" onchange="showType(idu,this.value,document.getElementById('statuts').value,document.getElementById('ages').value)">
                                <option value="">Sélectionner un type</option>
                                <?php
                                for ($o = 0; $o < $countTypes; $o++) {
                                ?>
                                    <option><?php print_r($resultTypes[$o]["type"]); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </form>
                    </div>
                    <div class="col">
                        <form>
                            <select id="ages" name="ages" onchange="showAge(idu,this.value,document.getElementById('statuts').value,document.getElementById('types').value)">
                                <option value="">Sélectionner un âge minimum</option>
                                <?php
                                for ($u = 0; $u < $countAges; $u++) {
                                ?>
                                    <option><?php print_r($resultAges[$u]["age"]); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div id="txtHint">

                <div class="row">
                    <div class="col">
                        <div class="login-form">

                            <?php
                            for ($u = 0; $u < $nbAttracs; $u++) {
                            ?>

                                <form method="post">
                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="row">
                                                    <img src="Design/picture/<?php echo $resultAttracs[$u]['pic']; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-8">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <p class="info">Nom de l'attraction</p>
                                                        <input id="nameA" class="form-control" placeholder="<?php echo ($resultAttracs[$u]['name']); ?>" disabled="disabled">
                                                    </div>
                                                    <div class="col">
                                                        <p class="info">Capacité</p>
                                                        <input class="form-control" placeholder="<?php echo ($resultAttracs[$u]['capacity']); ?>" disabled="disabled">
                                                    </div>
                                                    <div class="col">
                                                        <p class="info">Type</p>
                                                        <input id="nameA" class="form-control" placeholder="<?php echo ($resultAttracs[$u]['type']); ?>" disabled="disabled">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <p class="info">Âge minimum</p>
                                                        <input class="form-control" placeholder="<?php echo ($resultAttracs[$u]['age_min']); ?>" disabled="disabled">
                                                    </div>
                                                    <div class="col">
                                                        <p class="info">Horaires</p>
                                                        <input class="form-control" placeholder="<?php echo ($resultAttracs[$u]['open_hour'] . "h-" . $resultAttracs[$u]['close_hour'] . "h"); ?>" disabled="disabled">
                                                    </div>
                                                    <div class="col">
                                                        <p class="info">Statut</p>
                                                        <input class="form-control" placeholder="<?php echo ($resultAttracs[$u]['statut']); ?>" disabled="disabled">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <a class="btn btn-info" href="ticket.php?id=<?php echo $idUser; ?>&attrac=<?php echo $resultAttracs[$u]['id']; ?>" role="button">Acheter un ticket</a>
                                                    </div>
                                                    <div class="col">
                                                        <a class="btn btn-info" href="reservation.php?id=<?php echo $idUser; ?>&attrac=<?php echo $resultAttracs[$u]['id']; ?>" role="button">Réserver</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>

                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script src="Structure/Functions/functions.js"></script>
    <script src="Structure/Sidebar/sidebar.js"></script>
    <script>
        var idu = <?php echo json_encode($idUser); ?>;
    </script>
    </body>

    </html>

<?php
} else {
    header("Location: index.php");
}
?>