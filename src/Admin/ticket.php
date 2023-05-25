<?php
session_start();
$pageTitle = "Gestion de la billeterie";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId != $_SESSION['id']) header("Location: index.php");

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

// PARTIE POUR RECUP LES INFOS DES TICKETS
$tckt = $bdd->prepare('SELECT * FROM ticket');
$tckt->execute();
$tickets = $tckt->fetchAll();
$countTickets = count($tickets);

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
<link href="Design/css/ticket.css" rel="stylesheet" type="text/css">
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

    <center>
        <a class="btn btn-info" href="addticket.php?id=<?php echo $idUser; ?>"><i class='bx bx-message-alt-add'></i> Ajouter une formule</a>
    </center>

    <div class="container">
        <div class="row">

            <?php for ($a = 0; $a < $countTickets; $a++) {

                if (isset($_POST['formModif' . $a])) {

                    if (isset($_POST['nameT'])) $nameT = $_POST['nameT'];
                    if (isset($_POST['priceT'])) $priceT = $_POST['priceT'];
                    if (isset($_POST['infoT'])) $descT = $_POST['infoT'];
                    if (isset($_POST['minT'])) $minT = $_POST['minT'];
                    if (isset($_POST['maxT'])) $maxT = $_POST['maxT'];
                    $idT = $tickets[$a]['id'];

                    if (isset($nameT) && !empty($nameT)) {
                        $upNa = $bdd->prepare('UPDATE ticket SET name = ? WHERE id = ?');
                        $upNa->execute(array($nameT, $idT)); ?>
                        <script>
                            var idu = <?php echo json_encode($idUser); ?>;
                            var create = alert("La modification de la billeterie bien été prise en compte.");
                            document.location.href = "ticket.php?id=" + idu;
                        </script>
                    <?php
                    }

                    if (isset($priceT) && !empty($priceT)) {
                        $upPr = $bdd->prepare('UPDATE ticket SET price = ? WHERE id = ?');
                        $upPr->execute(array($priceT, $idT)); ?>
                        <script>
                            var idu = <?php echo json_encode($idUser); ?>;
                            var create = alert("La modification de la billeterie bien été prise en compte.");
                            document.location.href = "ticket.php?id=" + idu;
                        </script>
                    <?php
                    }

                    if (isset($descT) && !empty($descT)) {
                        $upDe = $bdd->prepare('UPDATE ticket SET info = ? WHERE id = ?');
                        $upDe->execute(array($priceT, $descT)); ?>
                        <script>
                            var idu = <?php echo json_encode($idUser); ?>;
                            var create = alert("La modification de la billeterie bien été prise en compte.");
                            document.location.href = "ticket.php?id=" + idu;
                        </script>
                    <?php
                    }

                    if (isset($minT) && !empty($_minT)) {
                        $upMi = $bdd->prepare('UPDATE ticket SET mint = ? WHERE id = ?');
                        $upMi->execute(array($minT, $idT)); ?>
                        <script>
                            var idu = <?php echo json_encode($idUser); ?>;
                            var create = alert("La modification de la billeterie bien été prise en compte.");
                            document.location.href = "ticket.php?id=" + idu;
                        </script>
                    <?php
                    }

                    if (isset($maxT) && !empty($maxT)) {
                        $upMa = $bdd->prepare('UPDATE ticket SET maxt = ? WHERE id = ?');
                        $upMa->execute(array($maxT, $idT)); ?>
                        <script>
                            var idu = <?php echo json_encode($idUser); ?>;
                            var create = alert("La modification de la billeterie bien été prise en compte.");
                            document.location.href = "ticket.php?id=" + idu;
                        </script>
                <?php
                    }
                }
                ?>

                <div class="col-3">
                    <div class="login-form">
                        <form method="post">

                            <div class="form-group">
                                <p class="info">Nom de la formule</p>
                                <input type="text" name="nameT" class="form-control" placeholder="<?php echo $tickets[$a]['name']; ?>">
                            </div>

                            <div class="form-group">
                                <p class="info">Prix par ticket (€)</p>
                                <input type="text" name="priceT" class="form-control" placeholder="<?php echo $tickets[$a]['price']; ?>">
                            </div>

                            <div class="form-group">
                                <p class="info">Descriptif</p>
                                <textarea row="4" name="infoT" class="form-control" placeholder="<?php echo $tickets[$a]['info']; ?>"></textarea>
                            </div>

                            <div class="form-group">
                                <p class="info">Minimum de ticket</p>
                                <input type="number" name="minT" class="form-control" placeholder="<?php echo $tickets[$a]['mint']; ?>">
                            </div>

                            <div class="form-group">
                                <p class="info">Maximum de ticket</p>
                                <input type="number" name="maxT" class="form-control" placeholder="<?php echo $tickets[$a]['maxt']; ?>">
                            </div>

                            <div class="row">
                                <div class="col">
                                    <input type="submit" class="btn btn-info" name="formModif<?php print_r($a); ?>" value="Modifier">
                                </div>
                                <div class="col">
                                    <a class="btn btn-danger" id="exception" href="delete.php?ticket1784=<?php echo $tickets[$a]['id']; ?>"><i class='bx bx-trash'></i></a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            <?php } ?>

        </div>
    </div>

</section>

<script src="Structure/Sidebar/sidebar.js"></script>
</body>

</html>