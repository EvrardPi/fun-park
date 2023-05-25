<?php
session_start();
$pageTitle = "Avis";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

if ($getId != $_SESSION['id']) header("Location: index.php");

// PARTIE SELECT INFO USER
$stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
$stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
$result = $stmt->execute();
$infoUser = $stmt->fetch();

// PARTIE INFO AVIS
$views = $bdd->prepare('SELECT * FROM avis WHERE visible = ? ORDER BY date_crea DESC');
$views->execute(array("Yes"));
$infoViews = $views->fetchAll();
$countViews = count($infoViews);

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
        <?php echo $pageTitle ?>
    </h3>

    <div class="container">
        <div class="row">

            <?php for ($o = 0; $o < $countViews; $o++) {

                $userA = $bdd->prepare('SELECT * FROM member WHERE id = ?');
                $userA->execute(array($infoViews[$o]['id_user']));
                $infoUs = $userA->fetch();

                $attracN = $bdd->prepare('SELECT * FROM attractions WHERE id = ?');
                $attracN->execute(array($infoViews[$o]['id_attraction']));
                $nameA = $attracN->fetch();

                $note = $infoViews[$o]['note'];
            ?>

                <div class="col-12">
                    <div class="rat-form">
                        <form>
                            <div class="row">

                                <div class="col">
                                    <div class="card">
                                        <div class="col">
                                            <figcaption class="blockquote-footer">
                                                <cite title="Source Title">
                                                    <?php $dateconvert = date_create($infoViews[$o]['date_crea']);
                                                    echo ("Le " . date_format($dateconvert, 'd/m/Y') . " par " . $infoUs['name'] . " " . $infoUs['firstname']); ?>
                                                </cite>
                                            </figcaption>

                                            <h6>Note</h6>

                                            <p class="stars">
                                                <?php if ($note == 5) echo "★★★★★" ?>
                                                <?php if ($note == 4) echo "★★★★☆" ?>
                                                <?php if ($note == 3) echo "★★★☆☆" ?>
                                                <?php if ($note == 2) echo "★★☆☆☆" ?>
                                                <?php if ($note == 1) echo "★☆☆☆☆" ?>
                                            </p>

                                            <hr>

                                            <h6>Commentaire</h6>

                                            <textarea class="form-control" placeholder="<?php if (isset($infoViews[$o]['comment'])) echo ($infoViews[$o]['comment']); ?>" disabled></textarea>

                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="card">
                                        <div class="col">

                                            <h6><?php echo $nameA['name']; ?></h6>

                                            <div class="col">
                                                <img src="Design/picture/<?php echo $nameA['pic']; ?>">
                                            </div>

                                        </div>
                                    </div>
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