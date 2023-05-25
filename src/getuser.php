<!DOCTYPE html>

<html>

<head>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }

    table,
    td,
    th {
      border: 1px solid black;
      padding: 5px;
    }

    th {
      text-align: left;
    }
  </style>
</head>

<body>

  <?php
  if (isset($_GET['st'])) $st = htmlspecialchars($_GET['st']);
  if (isset($_GET['ty'])) $ty = htmlspecialchars($_GET['ty']);
  if (isset($_GET['ag'])) $ag = htmlspecialchars($_GET['ag']);
  if (isset($_GET['id'])) $id = htmlspecialchars($_GET['id']);

  $con = mysqli_connect('localhost', 'root', 'root', 'funpark');
  if (!isset($con)) {
    die('Could not connect: ' /*. mysqli_error($con)*/);
  }

  mysqli_select_db($con, "filtre_ajax");

  if (!empty($st) && empty($ty) && empty($ag)) {
    $sql = "SELECT * FROM attractions WHERE statut = '" . $st . "'";
  }
  if (!empty($ty) && empty($st) && empty($ag)) {
    $sql = "SELECT * FROM attractions WHERE type = '" . $ty . "'";
  }
  if (!empty($ag) && empty($st) && empty($ty)) {
    $sql = "SELECT * FROM attractions WHERE age_min = '" . $ag . "'";
  }
  if (!empty($st) && !empty($ty) && empty($ag)) {
    $sql = "SELECT * FROM attractions WHERE statut = '" . $st . "' && type = '" . $ty . "'";
  }
  if (!empty($ag) && !empty($st) && empty($ty)) {
    $sql = "SELECT * FROM attractions WHERE statut = '" . $st . "' && age_min = '" . $ag . "'";
  }
  if (!empty($ag) && !empty($ty) && empty($st)) {
    $sql = "SELECT * FROM attractions WHERE type = '" . $ty . "' && age_min = '" . $ag . "'";
  }
  if (!empty($st) && !empty($ty) && !empty($ag)) {
    $sql = "SELECT * FROM attractions WHERE statut = '" . $st . "' && type = '" . $ty . "' && age_min = '" . $ag . "'";
  }

  $result = mysqli_query($con, $sql);

  echo "<div class='row'>
        <div class='col'>
        <div class='login-form'>";


  while ($row = mysqli_fetch_array($result)) {

    echo "<form method='post'>";
    echo "<div class='row'>";

    echo "<div class='col-4'>";
    echo "<div class='form-group'>";
    echo "<div class='row'>";
    echo "<img src='Design/picture/" . $row['pic'] . "'>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    echo "<div class='col-8'>";
    echo "<div class='form-group'>";
    echo "<div class='row'>";
    echo "<div class='col'>";
    echo "<p class='info'>Nom de l'attraction :</p>";
    echo "<input type='text' class='form-control' placeholder='" . $row['name'] . "' disabled='disabled'>";
    echo "</div>";
    echo "<div class='col'>";
    echo "<p class='info'>Capacité :</p>";
    echo "<input type='text' class='form-control' placeholder='" . $row['capacity'] . "' disabled='disabled'>";
    echo "</div>";
    echo "<div class='col'>";
    echo "<p class='info'>Type :</p>";
    echo "<input type='text' class='form-control' placeholder='" . $row['type'] . "' disabled='disabled'>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<div class='row'>";
    echo "<div class='col'>";
    echo "<p class='info'>Âge minimum :</p>";
    echo "<input type='text' class='form-control' placeholder='" . $row['age_min'] . "' disabled='disabled'>";
    echo "</div>";
    echo "<div class='col'>";
    echo "<p class='info'>Horaires :</p>";
    echo "<input type='text' class='form-control' placeholder='" . $row['open_hour'] . "h-" . $row['close_hour'] . "h" . "' disabled='disabled'>";
    echo "</div>";
    echo "<div class='col'>";
    echo "<p class='info'>Statut :</p>";
    echo "<input type='text' class='form-control' placeholder='" . $row['statut'] . "' disabled='disabled'>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<div class='row'>";
    echo "<div class='col'>";
    echo "<a class='btn btn-info' href='ticket.php?id=" . $id . "&attrac=" . $row['id'] . "' role='button'>Acheter un ticket</a>";
    echo "</div>";
    echo "<div class='col'>";
    echo "<a class='btn btn-info' href='reservation.php?id=" . $id . "&attrac=" . $row['id'] . "' role='button'>Réserver</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    echo "</div>";
    echo "</form>";
  }

  echo "</div>
        </div>
        </div>";

  mysqli_close($con);
  ?>

</body>

</html>