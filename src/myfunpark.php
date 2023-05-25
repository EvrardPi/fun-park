<?php
session_start();
$pageTitle = "Mon Fun Park";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId == $_SESSION['id']) {

    // PARTIE INFO USER
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

    // PARTIE DATE AUJD
    $dateAuj = new DateTime();
    $dateAujd = $dateAuj->format('Y-m-d');

    // PARTIE INFO AVIS PASSÉ
    $reviewPast = $bdd->prepare('SELECT * FROM reservation WHERE id_user = ? && date < ?');
    $reviewPast->execute(array($idUser, $dateAujd));
    $infoAvisP = $reviewPast->fetchAll();
    $countAvisP = count($infoAvisP);

    // PARTIE INFO AVIS FUTUR
    $reviewFutur = $bdd->prepare('SELECT * FROM reservation WHERE id_user = ? && date >= ?');
    $reviewFutur->execute(array($idUser, $dateAujd));
    $infoAvisF = $reviewFutur->fetchAll();
    $countAvisF = count($infoAvisF);

    // PARTIE INFO ACHAT USER
    $tcks = $bdd->prepare('SELECT * FROM ticket_buy WHERE id_user = ?');
    $tcks->execute(array($idUser));
    $infoTickets = $tcks->fetchAll();
    $countAchat = count($infoTickets);

    require "Structure/Head/head.php"; // intègre le head
?>
    <link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
    <link href="Design/css/myfunpark.css" rel="stylesheet" type="text/css">
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
            <div class="row">
                <div class="col">
                    <div class="rat-form">
                        <h5>Mes réservations passées</h5>

                        <?php for ($u = 0; $u < $countAvisP; $u++) {
                            $idAttraResP = $infoAvisP[$u]['id_attrac'];
                            $attracs = $bdd->prepare("SELECT * FROM attractions WHERE id = ?");
                            $attracs->execute(array($idAttraResP));
                            $resultAttracsP = $attracs->fetch();
                            $dateAuj = date_create($infoAvisP[$u]["date"]);
                            $dateP = $dateAuj->format('d/m/Y'); ?>

                            <form method="post">
                                <h6><?php echo $resultAttracsP['name']; ?></h6>
                                <div class="col">
                                    <img src="Design/picture/<?php echo $resultAttracsP['pic']; ?>">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Date</p>
                                        <input class="form-control" value="<?php print_r($dateP); ?>" disabled>
                                    </div>
                                    <div class="col">
                                        <p class="info">Heure</p>
                                        <input class="form-control" value="<?php print_r(substr($infoAvisP[$u]["time"], 0, -3)); ?>" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Place(s)</p>
                                        <input class="form-control" value="<?php print_r($infoAvisP[$u]["quantity"]); ?>" disabled>
                                    </div>
                                    <div class="col">
                                        <p class="info">Laisser un avis</p>
                                        <a class="btn btn-info" href="review.php?id=<?php echo $idUser; ?>&attrac=<?php print_r($idAttraResP); ?>"><i class='bx bxs-comment-detail'></i></a>
                                    </div>
                                </div>
                            </form>

                        <?php } ?>

                    </div>
                </div>

                <div class="col">
                    <div class="rat-form">
                        <h5>Mes réservations à venir</h5>

                        <?php for ($a = 0; $a < $countAvisF; $a++) {
                            $idAttraResF = $infoAvisF[$a]['id_attrac'];
                            $attracs = $bdd->prepare("SELECT * FROM attractions WHERE id = ?");
                            $attracs->execute(array($idAttraResF));
                            $resultAttracsF = $attracs->fetch();
                            $dateAuj = date_create($infoAvisF[$a]["date"]);
                            $dateF = $dateAuj->format('d/m/Y'); ?>

                            <form method="post">
                                <h6><?php echo $resultAttracsF['name']; ?></h6>
                                <div class="col">
                                    <img src="Design/picture/<?php echo $resultAttracsF['pic']; ?>">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Date</p>
                                        <input class="form-control" value="<?php print_r($dateF); ?>" disabled>
                                    </div>
                                    <div class="col">
                                        <p class="info">Heure</p>
                                        <input class="form-control" value="<?php print_r(substr($infoAvisF[$a]["time"], 0, -3)); ?>" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Place(s)</p>
                                        <input class="form-control" value="<?php print_r($infoAvisF[$a]["quantity"]); ?>" disabled>
                                    </div>
                                    <div class="col">
                                        <p class="info">Annuler</p>
                                        <a class="btn btn-info" href="del-reservation.php?numRes=<?php echo ($infoAvisF[$a]['id']); ?>"><i class='bx bx-trash'></i></a>
                                    </div>
                                </div>
                            </form>

                        <?php } ?>

                    </div>
                </div>

                <div class="col">
                    <div class="rat-form">
                        <h5>Mes achats</h5>

                        <?php for ($i = 0; $i < $countAchat; $i++) {
                            $idAttraAch = $infoTickets[$i]['id_attrac'];
                            $attracs = $bdd->prepare("SELECT * FROM attractions WHERE id = ?");
                            $attracs->execute(array($idAttraAch));
                            $resultAttracsA = $attracs->fetch();
                            $dateA = date_create($infoTickets[$i]["date"]);
                            $dateAchat = $dateA->format('d/m/Y'); ?>

                            <form method="post">
                                <h6><?php echo $resultAttracsA['name']; ?></h6>
                                <div class="col">
                                    <img src="Design/picture/<?php echo $resultAttracsA['pic']; ?>">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Ticket(s)</p>
                                        <input class="form-control" value="<?php print_r($infoTickets[$i]["quantity"]); ?>" disabled>
                                    </div>
                                    <div class="col">
                                        <p class="info">Montant Total</p>
                                        <input class="form-control" value="<?php print_r($infoTickets[$i]["total"]); ?> €" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="info">Date d'achat</p>
                                        <input class="form-control" value="<?php print_r($dateAchat); ?>" disabled>
                                    </div>
                                    <div class="col">
                                        <p class="info">Racheter</p>
                                        <a class="btn btn-info" href="ticket.php?id=<?php echo $idUser; ?>&attrac=<?php print_r($resultAttracsA['id']); ?>"><i class='bx bxs-cart-add'></i></a>
                                    </div>
                                </div>
                            </form>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="Structure/Sidebar/sidebar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>

    </html>

<?php
} else {
    header("Location: index.php");
}
?>