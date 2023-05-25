<body>
  <div class="sidebar">
    <div class="logo-details">
      <i class='bx bxl-tux icon'></i>
      <div class="logo_name">
        Fun Park
      </div>
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="dashboard.php?id=<?php echo $idUser; ?>">
          <i class='bx bx-bar-chart'></i>
          <span class="links_name">Dashboard</span>
        </a>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="attractions.php?id=<?php echo $idUser; ?>">
          <i class='bx bx-notepad'></i>
          <span class="links_name">Attractions</span>
        </a>
        <span class="tooltip">Attractions</span>
      </li>
      <li>
        <a href="ticket.php?id=<?php echo $idUser; ?>">
          <i class='bx bx-cart-alt'></i>
          <span class="links_name">Billeterie</span>
        </a>
        <span class="tooltip">Billeterie</span>
      </li>
      <li>
        <a href="allview.php?id=<?php echo $idUser; ?>">
          <i class='bx bxs-comment-detail'></i>
          <span class="links_name">Avis</span>
        </a>
        <span class="tooltip">Avis</span>
      </li>
      <li>
        <a href="profil.php?id=<?php echo $idUser; ?>">
          <i class='bx bx-id-card'></i>
          <span class="links_name">Profil</span>
        </a>
        <span class="tooltip">Profil</span>
      </li>