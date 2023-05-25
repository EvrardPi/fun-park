<?php
session_start();
$pageTitle = "Sélectionner les avis d'une attraction";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

if ($getId != $_SESSION['id']) header("Location: index.php");

// PARTIE SELECT INFO USER
$stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
$stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
$result = $stmt->execute();
$infoUser = $stmt->fetch();

// PARTIE SELECT ALL ATTRACTIONS
$attracs = $bdd->prepare('SELECT * FROM attractions');
$attracs->execute();
$infoAttracs = $attracs->fetchAll();
$countAttracs = count($infoAttracs);

// PARTIE VARIABLES
$idUser = $infoUser['id'];
$mailUser = $infoUser['mail'];
$nameUser = $infoUser['name'];
$firstNameUser = $infoUser['firstname'];
$roleUser = $infoUser['role'];

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/allview.css" rel="stylesheet" type="text/css">
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
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

            <?php for ($a = 0; $a < $countAttracs; $a++) { ?>

                <div class="col-3">
                    <div class="rat-form">
                        <form>
                            <div class="form-group">
                                <h6>
                                    <?php echo $infoAttracs[$a]['name']; ?>
                                </h6>
                            </div>
                            <div class="form-group">
                                <img src="../Design/picture/<?php echo $infoAttracs[$a]['pic']; ?>">
                            </div>
                            <div class="form-group">
                                <a class="btn btn-info" href="view.php?id=<?php echo $_SESSION['id']; ?>&attrac=<?php echo $infoAttracs[$a]['id'] ?>">Voir les avis</a>
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