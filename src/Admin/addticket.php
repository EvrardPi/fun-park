<?php
session_start();
$pageTitle = "Ajout de formule";
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

if (isset($_POST['formCreat'])) {

    $nameF = $_POST['nameF'];
    $priceF = $_POST['priceF'];
    $infoF = $_POST['infoF'];
    $minF = $_POST['minF'];
    $maxF = $_POST['maxF'];

    if (!preg_match('/^[a-zA-Z& ]{2,50}$/', $nameF)) $errorName = "Le nom de l'attraction n'est pas conforme";
    if (!preg_match('/^[1-9&.]{1,50}$/', $priceF)) $errorPrice = "Le prix de l'attraction doit comporter un point si c'est un nombre décimal";

    if (empty($errorName) && empty($errorPrice)) {
        $insNewTicket = $bdd->prepare("INSERT INTO `ticket` (`id`, `name`, `price`, `info`, `mint`, `maxt`) VALUES (NULL, ?, ?, ?, ?, ?)");
        $insNewTicket->execute(array($nameF, $priceF, $infoF, $minF, $maxF));
?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var create = alert("L'ajout de la nouvelle formule bien été prise en compte.");
            document.location.href = "ticket.php?id=" + idu;
        </script>
<?php
    }
}

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
<link href="Design/css/addticket.css" rel="stylesheet" type="text/css">
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
                                <p class="info">Nom de la formule</p>
                                <input type="text" name="nameF" class="form-control" required>
                                <p class="errorr">
                                    <?php if (isset($errorName)) echo $errorName; ?>
                                </p>
                            </div>
                            <div class="col">
                                <p class="info">Prix par ticket (€)</p>
                                <input type="text" name="priceF" class="form-control" required>

                                <p class="errorr">
                                    <?php if (isset($errorPrice)) echo $errorPrice; ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p class="info">Descriptif</p>
                                <textarea rows="3" name="infoF" class="form-control" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p class="info">Minimum de ticket</p>
                                <input type="number" name="minF" class="form-control" required>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <p class="info">Maximum de ticket</p>
                                    <input type="number" name="maxF" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-info" name="formCreat" value="Ajouter">
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