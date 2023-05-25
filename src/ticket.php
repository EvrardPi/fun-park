<?php
session_start();
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId == $_SESSION['id']) {

  $idAttrac = intval($_GET['attrac']);

  $stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
  $stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
  $result = $stmt->execute();
  $infoUser = $stmt->fetch();

  //PARTIE POUR RECUP LES INFOS DE L'ATTRACTION
  $seat = $bdd->prepare('SELECT * FROM attractions WHERE id = ?');
  $seat->execute(array($idAttrac));
  $attrac = $seat->fetch();

  // PARTIE VARIABLES
  $idUser = $infoUser['id'];
  $mailUser = $infoUser['mail'];
  $nameUser = $infoUser['name'];
  $firstNameUser = $infoUser['firstname'];
  $roleUser = $infoUser['role'];
  $nameAttrac = $attrac['name'];
  $statutAttrac = $attrac['statut'];

  $pageTitle = "Billeterie pour " . $nameAttrac;

  // PARTIE POUR RECUP LES INFOS DES TICKETS
  $tckt = $bdd->prepare('SELECT * FROM ticket');
  $tckt->execute();
  $tickets = $tckt->fetchAll();
  $countTickets = count($tickets);

  if ($statutAttrac == "Ouvert") {

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
      <div class="container">
        <h3>
          <?php echo $pageTitle ?>
        </h3>
      </div>

      <section class="pricing">
        <div class="container">
          <div class="row">

            <?php
            for ($a = 0; $a < $countTickets; $a++) {

              if (isset($_POST['formBuy' . $a])) {

                $qtyT = $_POST['numberT' . $a];
                if ($qtyT < $tickets[$a]['mint'] || $qtyT > $tickets[$a]['maxt']) $erreur[$a] = "Quantité de ticket incorrecte";

                if (empty($erreur[$a])) {
                  $totalP = $qtyT * $tickets[$a]['price'];
                  $insA = $bdd->prepare("INSERT INTO ticket_buy(quantity, total, date, id_user, id_attrac) VALUES(?, ?, NOW(), ?, ?)");
                  $insA->execute(array($qtyT, $totalP, $idUser, $idAttrac)); ?>
                  <script>
                    const idu = <?php echo json_encode($idUser); ?>;
                    const ticket = <?php echo json_encode($qtyT); ?>;
                    const total = <?php echo json_encode($totalP); ?>;
                    var create = alert("Votre achat de " + ticket + " ticket(s) Fun Park pour un montant de " + total + "€ a bien été pris en compte.");
                    document.location.href = "myfunpark.php?id=" + idu;
                  </script>
              <?php
                }
              }
              ?>

              <div class="col">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title text-muted text-uppercase text-center">Formule <?php print_r($tickets[$a]['name']); ?></h5>
                    <h6 class="card-price text-center"><?php print_r($tickets[$a]['price']); ?>€<span class="period">/ticket</span></h6>
                    <hr>
                    <div class="check">
                      <ul class="fa-ul">
                        <li><span class="fa-li"><i class="fas fa-check"></i></span><?php print_r($tickets[$a]['info']); ?></li>
                      </ul>
                    </div>
                    <div class="d-grid">

                      <form method="post">
                        <div class="form-group">
                          <div class="row">
                            <div class="col">
                              <label for="numberT<?php print_r($a); ?>" title="text">Ticket(s)</label>
                              <input class="form-control" type="number" name="numberT<?php print_r($a); ?>" value="<?php print_r($tickets[$a]['mint']); ?>" min="<?php print_r($tickets[$a]['mint']); ?>" max="<?php print_r($tickets[$a]['maxt']); ?>" required>
                            </div>
                            <div class="col">
                              <input type="submit" class="btn btn-info" name="formBuy<?php print_r($a); ?>" value="Acheter">
                            </div>
                          </div>
                        </div>
                        <div class="errorr">
                          <?php if (isset($erreur[$a])) echo $erreur[$a]; ?>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>

            <?php } ?>

          </div>
        </div>
      </section>

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