<?php
session_start();
$pageTitle = "Avis";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);
if (!empty($_GET['attrac'])) $getAttrac = intval($_GET['attrac']);

if ($getId != $_SESSION['id'] || empty($getAttrac)) header("Location: index.php");

// PARTIE SELECT INFO USER
$stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
$stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
$result = $stmt->execute();
$infoUser = $stmt->fetch();

$attracs = $bdd->prepare('SELECT * FROM attractions WHERE id = ?');
$attracs->execute(array($getAttrac));
$infoAttracs = $attracs->fetch();

// PARTIE INFO AVIS
$yes = "Yes";
$viewsVY = $bdd->prepare('SELECT * FROM avis WHERE visible = ? && id_attraction = ? ORDER BY date_crea DESC');
$viewsVY->execute(array($yes, $getAttrac));
$infoViewsVY = $viewsVY->fetchAll();
$countViewsVY = count($infoViewsVY);

// PARTIE INFO AVIS
$no = "No";
$viewsVN = $bdd->prepare('SELECT * FROM avis WHERE visible = ? && id_attraction = ? ORDER BY date_crea DESC');
$viewsVN->execute(array($no, $getAttrac));
$infoViewsVN = $viewsVN->fetchAll();
$countViewsVN = count($infoViewsVN);

// PARTIE VARIABLES
$idUser = $infoUser['id'];
$mailUser = $infoUser['mail'];
$nameUser = $infoUser['name'];
$firstNameUser = $infoUser['firstname'];
$roleUser = $infoUser['role'];

require "Structure/Head/head.php"; // intègre le head
?>
<link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
<link href="Design/css/view.css" rel="stylesheet" type="text/css">
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
        <img src="../Design/picture/<?php echo $infoAttracs['pic']; ?>">
        <?php echo $infoAttracs['name']; ?>
    </h3>

    <div class="container">
        <hr>
        <div class="row">

            <div class="col">
                <h5>
                    Avis non validé
                </h5>
                <?php for ($a = 0; $a < $countViewsVN; $a++) {
                    $reqa = $bdd->prepare('SELECT * FROM member WHERE id = ?');
                    $reqa->execute(array($infoViewsVN[$a]['id_user']));
                    $infoUsa = $reqa->fetch();
                    $noteA = $infoViewsVN[$a]['note'];
                ?>
                    <div class="row">
                        <div class="col">
                            <div class="rat-form">
                                <form>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card">
                                                <figcaption class="blockquote-footer">
                                                    <cite title="Source Title">
                                                        <?php $dateconvert = date_create($infoViewsVN[$a]['date_crea']);
                                                        echo ("Le " . date_format($dateconvert, 'd/m/Y') . " par " . $infoUsa['name'] . " " . $infoUsa['firstname']); ?>
                                                    </cite>
                                                </figcaption>
                                                <h6>Note</h6>
                                                <p class="stars">
                                                    <?php if ($noteA == 5) echo "★★★★★" ?>
                                                    <?php if ($noteA == 4) echo "★★★★☆" ?>
                                                    <?php if ($noteA == 3) echo "★★★☆☆" ?>
                                                    <?php if ($noteA == 2) echo "★★☆☆☆" ?>
                                                    <?php if ($noteA == 1) echo "★☆☆☆☆" ?>
                                                </p>
                                                <hr>
                                                <h6>Commentaire</h6>
                                                <textarea class="form-control" placeholder="<?php if (isset($infoViewsVN[$a]['comment'])) echo ($infoViewsVN[$a]['comment']); ?>" disabled></textarea>
                                                <center>
                                                    <div class="row">
                                                        <div class="col">
                                                            <a class="btn btn-info" href="valid-avis.php?numAvis=<?php echo ($infoViewsVN[$a]['id']); ?>&numAttrac=<?php echo ($infoViewsVN[$a]['id_attraction']); ?>"><i class='bx bxs-check-circle'></i></a>
                                                        </div>
                                                        <div class="col">
                                                            <a class="btn btn-info" href="del-avis.php?numAvis=<?php echo ($infoViewsVN[$a]['id']); ?>&numAttrac=<?php echo ($infoViewsVN[$a]['id_attraction']); ?>"><i class='bx bxs-trash'></i></a>
                                                        </div>
                                                    </div>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="col">
                <h5>
                    Avis validé
                </h5>
                <?php for ($o = 0; $o < $countViewsVY; $o++) {
                    $reqo = $bdd->prepare('SELECT * FROM member WHERE id = ?');
                    $reqo->execute(array($infoViewsVY[$o]['id_user']));
                    $infoUso = $reqo->fetch();
                    $noteO = $infoViewsVY[$o]['note'];
                ?>
                    <div class="row">
                        <div class="col">
                            <div class="rat-form">
                                <form>
                                    <div class="card">
                                        <figcaption class="blockquote-footer">
                                            <cite title="Source Title">
                                                <?php $dateconvert = date_create($infoViewsVY[$o]['date_crea']);
                                                echo ("Le " . date_format($dateconvert, 'd/m/Y') . " par " . $infoUso['name'] . " " . $infoUso['firstname']); ?>
                                            </cite>
                                        </figcaption>
                                        <h6>Note</h6>
                                        <p class="stars">
                                            <?php if ($noteO == 5) echo "★★★★★" ?>
                                            <?php if ($noteO == 4) echo "★★★★☆" ?>
                                            <?php if ($noteO == 3) echo "★★★☆☆" ?>
                                            <?php if ($noteO == 2) echo "★★☆☆☆" ?>
                                            <?php if ($noteO == 1) echo "★☆☆☆☆" ?>
                                        </p>
                                        <hr>
                                        <h6>Commentaire</h6>
                                        <textarea class="form-control" placeholder="<?php if (isset($infoViewsVY[$o]['comment'])) echo ($infoViewsVY[$o]['comment']); ?>" disabled></textarea>
                                        <center>
                                            <div class="row">
                                                <div class="col">
                                                    <a class="btn btn-info" href="del-avis.php?numAvis=<?php echo ($infoViewsVY[$o]['id']); ?>&numAttrac=<?php echo ($infoViewsVY[$o]['id_attraction']); ?>"><i class='bx bxs-trash'></i></a>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

</section>

<script src="Structure/Sidebar/sidebar.js"></script>
</body>

</html>