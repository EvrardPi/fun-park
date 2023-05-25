<?php
session_start();
$pageTitle = "Réservation";
date_default_timezone_set('Europe/Paris');
require "Structure/Bdd/config.php"; // intègre la base de données
require "Structure/Functions/functions.php"; // intègre les fonctions

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId == $_SESSION['id'] && !empty($_GET['attrac'])) {

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

    // PARTIE SÉLECTION DES INFOS DE L'ATTRACTION
    $attra = $bdd->prepare('SELECT * FROM attractions WHERE id = ?');
    $attra->execute(array($idAttrac));
    $infoAttrac = $attra->fetch();
    $nbMaxUser = $infoAttrac['capacity'];
    $dbutH = $infoAttrac['open_hour'];
    $finH = $infoAttrac['close_hour'];
    $statutAttrac = $infoAttrac['statut'];

    if ($statutAttrac == "Ouvert") {

        // PARTIE HORAIRES DE RESERVATION DISPO
        $durat = 15;
        $dateNow = new DateTime();
        //$dateNow->modify('+4 hour'); pour tester
        $dateF = new DateTime();
        $dateAuj = new DateTime();
        $dateMa = new DateTime();
        $dateMa->modify('+ 7 day');
        $dateMax = $dateMa->format('Y-m-d');

        if ($dateNow->format('H') > $dbutH && $dateNow->format('H') < $finH) { // l'attraction est ouverte
            $nbM = $dateNow->format('i'); // on récup le nombre de minutes actuels
            $dateNow->modify('-' . $nbM . " " . 'minute'); // on enlève le nombre de minutes pour initialiser à 00
            $dateNow->modify('+1 hour'); // ajoute une heure pour la mettre dans le futur
            $dateAujd = $dateAuj->format('Y-m-d');
            $resultOpen = $dateNow->format('Y-m-d H:i'); // ????-??-?? si heure actuel = 12:00 alors resultOpen = 13:00

        } else if ($dateNow->format('H') < $dbutH) { // l'attraction n'est pas encore ouverte
            $nbH = $dateNow->format('H'); // on récup le nombre d'heures actuels
            $nbM = $dateNow->format('i'); // on récup le nombre de minutes actuels
            $dateNow->modify('-' . $nbH . " " . 'hour'); // on enlève le nombre d'heures pour initialiser à 00
            $dateNow->modify('-' . $nbM . " " . 'minute'); // on enlève le nombre de minutes pour initialiser à 00
            $dateNow->modify('+' . $dbutH . " " . 'hour'); // on met l'heure d'ouverture
            $dateAujd = $dateAuj->format('Y-m-d');
            $resultOpen = $dateNow->format('Y-m-d H:i'); // ????-??-?? 08:00

        } else if ($dateNow->format('H') > $finH || $dateNow->format('H') == $finH) { // l'attraction est maintenant fermée pour la journée
            $nbH = $dateNow->format('H'); // on récup le nombre d'heures actuels
            $nbM = $dateNow->format('i'); // on récup le nombre de minutes actuels
            $dateNow->modify('-' . $nbH . " " . 'hour'); // on enlève le nombre d'heures pour initialiser à 00
            $dateNow->modify('-' . $nbM . " " . 'minute'); // on enlève le nombre de minutes pour initialiser à 00
            $dateNow->modify('+' . $dbutH . " " . 'hour'); // on met l'heure d'ouverture
            $dateAuj->modify('+1 day');
            $dateAujd = $dateAuj->format('Y-m-d');
            $resultOpen = $dateNow->format('Y-m-d H:i'); // ????-??-?? 08:00
        }

        $nbHF = $dateF->format('H'); // on récup le nombre d'heures
        $nbMF = $dateF->format('i'); // on récup le nombre de minutes 
        $dateF->modify('-' . $nbHF . " " . 'hour'); // on enlève le nombre d'heures pour initialiser à 00
        $dateF->modify('-' . $nbMF . " " . 'minute'); // on enlève le nombre de minutes pour initialiser à 00
        $dateF->modify('+' . $finH . " " . 'hour'); // on met l'heure de fermeture
        $resultClose = $dateF->format('Y-m-d H:i'); // ????-??-?? 20:00
        $foncReserv = Reserve($resultOpen, $resultClose, $durat);
        $countTimes = count($foncReserv);

        // PARTIE RECUP INFO RESERVATION
        if (isset($_POST['formReserv'])) {
            if (!preg_match('/^(?=.*\d)[0-9&:]{4,5}$/', $_POST['times'])) $errorTime = "L'heure de réservation n'est pas conforme";
            if (!preg_match('/^(?=.*\d)[0-9&-]{10}$/', $_POST['dateR'])) $errorDate = "La date de réservation n'est pas conforme";
            if (!intval($_POST['numberR'])) $errorNbr = "Le nombre de personnes n'est pas conforme";
            if (empty($errorDate) && empty($errorTime) && empty($errorNbr)) {
                $timeR = $_POST['times'];
                $dateR = $_POST['dateR'];
                $qty = intval($_POST['numberR']);
                $insrtReser = $bdd->prepare("INSERT INTO reservation(date, time, quantity, id_user, id_attrac) VALUES(?, ?, ?, ?, ?)");
                $insrtReser->execute(array($dateR, $timeR, $qty, $idUser, $idAttrac)); ?>
                <script>
                    var idu = <?php echo json_encode($idUser); ?>;
                    var create = alert("Votre réservation Fun Park a bien été prise en compte.");
                    document.location.href = "myfunpark.php?id=" + idu;
                </script>
        <?php
            }
        }

        require "Structure/Head/head.php"; // intègre le head
        ?>
        <link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
        <link href="Design/css/reservation.css" rel="stylesheet" type="text/css">
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

                <div class="text">
                    <?php echo $pageTitle; ?>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="rat-form">

                                <form method="post">

                                    <div class="form-group">
                                        <div class="row">

                                            <div class="col">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <h5><?php echo $infoAttrac['name']; ?></h5>
                                                        <img src="Design/picture/<?php echo $infoAttrac['pic'] ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="col">
                                                <div class="form-group">
                                                    <p class="info">Heure de réservation</p>
                                                    <select class="form-control" id="times" name="times">
                                                        <option>Sélectionner un horaire</option>
                                                        <?php
                                                        for ($u = 0; $u < $countTimes; $u++) {
                                                        ?>
                                                            <option value="<?php print_r($foncReserv[$u]); ?>"><?php print_r($foncReserv[$u]); ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <p class="errorr">
                                                        <?php if (isset($errorTime)) echo $errorTime; ?>
                                                    </p>
                                                </div>

                                                <div class="form-group">
                                                    <p class="info">Date de réservation</p>
                                                    <input class="form-control" type="date" id="dateR" name="dateR" value="<?php echo $dateAujd; ?>" min="<?php echo $dateAujd; ?>" max="<?php echo $dateMax; ?>" required>
                                                    <p class="errorr">
                                                        <?php if (isset($errorDate)) echo $errorDate; ?>
                                                    </p>
                                                </div>

                                                <div class="form-group">
                                                    <p class="info">Nombre de personnes</p>
                                                    <input class="form-control" type="number" id="numberR" name="numberR" value="1" min="1" max="<?php echo $nbMaxUser; ?>" required>
                                                    <p class="errorr">
                                                        <?php if (isset($errorNbr)) echo $errorNbr; ?>
                                                    </p>
                                                </div>

                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-info" name="formReserv" value="Reserver">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <script src="Structure/Sidebar/sidebar.js"></script>
        </body>

        </html>

    <?php
    } else { ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("L'attraction n'est pas ouverte.");
            document.location.href = "attractions.php?id=" + idu;
        </script>
<?php
    }
} else {
    header("Location: index.php");
}
?>