<?php
session_start();
$pageTitle = "Home";
require "Structure/Bdd/config.php"; // intègre la base de données

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId == $_SESSION['id']) {

    $stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
    $stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
    $result = $stmt->execute();
    $infoUser = $stmt->fetch();
    $idUser = $infoUser['id'];
    $mailUser = $infoUser['mail'];
    $nameUser = $infoUser['name'];
    $firstNameUser = $infoUser['firstname'];
    $roleUser = $infoUser['role'];

    require "Structure/Head/head.php"; // intègre le head
?>
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
        <div class="container">
            <h3>
                <?php echo $pageTitle ?>
            </h3>
        </div>
    </section>

    <script src="Structure/Sidebar/sidebar.js"></script>
    </body>

    </html>

<?php
} else {
    header("Location: index.php");
}
?>