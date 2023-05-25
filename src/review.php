<?php
session_start();
$pageTitle = "Avis ";
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

    // PARTIE SÉLECTION DES INFOS DE L'ATTRACTION (picture)
    $latt = $bdd->prepare('SELECT * FROM attractions WHERE id = ?');
    $latt->execute(array($idAttrac));
    $infoAttrac = $latt->fetch();

    // PARTIE RECHERCHE AVIS
    $djpt = $bdd->prepare('SELECT * FROM avis WHERE id_user = ? && id_attraction = ?');
    $djpt->execute(array($idUser, $idAttrac));
    $djposter = $djpt->fetch();
    if (!empty($djposter['id'])) $idAvis = $djposter['id'];
    if (!empty($djposter['note'])) $note = $djposter['note'];
    if (!empty($djposter['comment'])) $comment = $djposter['comment'];

    // PARTIE POUR ENVOYER POUR LA PREMIERE FOIS UN AVIS
    if (isset($_POST['formAvis'])) {

        if (empty($_POST['rate1']) && empty($_POST['rate2']) && empty($_POST['rate3']) && empty($_POST['rate4']) && empty($_POST['rate5'])) $errorstar = "Une note doit être attribuée";
        if (!empty($_POST['rate1'])) $intStar = 1;
        if (!empty($_POST['rate2'])) $intStar = 2;
        if (!empty($_POST['rate3'])) $intStar = 3;
        if (!empty($_POST['rate4'])) $intStar = 4;
        if (!empty($_POST['rate5'])) $intStar = 5;
        if (!empty($_POST['txtarea'])) $txtarea = htmlspecialchars($_POST['txtarea']);
        if (empty($_POST['txtarea'])) $txtarea = "";

        if (empty($errorstar)) {
            $djpt = $bdd->prepare('INSERT INTO avis(note, comment, date_crea, id_user, id_attraction) VALUES(?, ?, NOW(), ?, ?)');
            $djpt->execute(array($intStar, $txtarea, $idUser, $idAttrac)); ?>
            <script>
                var idu = <?php echo json_encode($idUser); ?>;
                var ida = <?php echo json_encode($idAttrac); ?>;
                var create = alert(" Votre avis a bien été pris en compte.\n Il va être vérifié, puis affiché s'il correspond aux normes.");
                document.location.href = "review.php?id=" + idu + "&attrac=" + ida;
            </script>
        <?php
        }
    }

    if (isset($_POST['formResetAvis'])) {
        $dela = $bdd->prepare('DELETE FROM avis WHERE id_user = ? AND id_attraction = ?');
        $dela->execute(array($idUser, $idAttrac)); ?>
        <script>
            var idu = <?php echo json_encode($idUser); ?>;
            var ida = <?php echo json_encode($idAttrac); ?>;
            var create = alert("L'avis a bien été réinitialiser.");
            document.location.href = "review.php?id=" + idu + "&attrac=" + ida;
        </script>
    <?php
    }

    require "Structure/Head/head.php"; // intègre le head
    ?>
    <link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
    <link href="Design/css/review.css" rel="stylesheet" type="text/css">
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

                                        <?php if (!empty($djposter)) { // SI UN AVIS EXISTE DÉJA 
                                        ?>

                                            <div class="col">
                                                <div class="form-group">
                                                    <div class="rate">
                                                        <?php if ($note == 5) { ?>
                                                            <input type="radio" id="star5" name="ratem5" value="5" checked>
                                                            <label for="starm5" title="text">5 stars</label>
                                                        <?php } else { ?>
                                                            <input type="radio" id="star5" name="ratem5" value="5">
                                                            <label for="star5" title="text">5 stars</label>
                                                        <?php } ?>
                                                        <?php if ($note == 4) { ?>
                                                            <input type="radio" id="star4" name="ratem4" value="4" checked>
                                                            <label for="star4" title="text">4 stars</label>
                                                        <?php } else { ?>
                                                            <input type="radio" id="star4" name="ratem4" value="4">
                                                            <label for="star4" title="text">4 stars</label>
                                                        <?php } ?>
                                                        <?php if ($note == 3) { ?>
                                                            <input type="radio" id="star3" name="ratem3" value="3" checked>
                                                            <label for="star3" title="text">3 stars</label>
                                                        <?php } else { ?>
                                                            <input type="radio" id="star3" name="ratem3" value="3">
                                                            <label for="star3" title="text">3 stars</label>
                                                        <?php } ?>
                                                        <?php if ($note == 2) { ?>
                                                            <input type="radio" id="star2" name="ratem2" value="2" checked>
                                                            <label for="star2" title="text">2 stars</label>
                                                        <?php } else { ?>
                                                            <input type="radio" id="star2" name="ratem2" value="2">
                                                            <label for="star2" title="text">2 stars</label>
                                                        <?php } ?>
                                                        <?php if ($note == 1) { ?>
                                                            <input type="radio" id="star1" name="ratem1" value="1" checked>
                                                            <label for="star1" title="text">1 star</label>
                                                        <?php } else { ?>
                                                            <input type="radio" id="star1" name="ratem1" value="1">
                                                            <label for="star1" title="text">1 star</label>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" id="txtarea" name="txtaream" rows="5" placeholder="<?php if (isset($comment)) echo $comment; ?>" disabled></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-info" name="formResetAvis" value="Réinitialiser l'avis">
                                                </div>
                                            </div>

                                        <?php } else { // SI AUCUN AVIS 
                                        ?>

                                            <div class="col">
                                                <div class="form-group">
                                                    <div class="rate">
                                                        <input type="radio" id="star5" name="rate5" value="5">
                                                        <label for="star5" title="text">5 stars</label>
                                                        <input type="radio" id="star4" name="rate4" value="4">
                                                        <label for="star4" title="text">4 stars</label>
                                                        <input type="radio" id="star3" name="rate3" value="3">
                                                        <label for="star3" title="text">3 stars</label>
                                                        <input type="radio" id="star2" name="rate2" value="2">
                                                        <label for="star2" title="text">2 stars</label>
                                                        <input type="radio" id="star1" name="rate1" value="1">
                                                        <label for="star1" title="text">1 star</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" id="txtarea" name="txtarea" rows="5" placeholder="Laisser un commentaire ? (facultatif)"></textarea>
                                                </div>
                                                <p class="errorr">
                                                    <?php if (isset($errorstar)) echo $errorstar; ?>
                                                </p>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-info" name="formAvis" value="Envoyer">
                                                </div>
                                            </div>

                                        <?php } ?>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>

    </html>