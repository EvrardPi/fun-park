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
        <a href="attractions.php?id=<?php echo $idUser; ?>">
          <i class='bx bx-notepad'></i>
          <span class="links_name">Attractions</span>
        </a>
        <span class="tooltip">Attractions</span>
      </li>
      <li>
        <a href="myfunpark.php?id=<?php echo $idUser; ?>">
          <i class='bx bx-calendar-check'></i>
          <span class="links_name">Mon Fun Park</span>
        </a>
        <span class="tooltip">Mon Fun Park</span>
      </li>
      <li>
        <a href="view.php?id=<?php echo $idUser; ?>">
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

      <div id="content">
        <form action="search.php" method="post">
          <li class="search">
            <i class='bx bx-search'></i>
            <input type="text" name="search" id="search" placeholder="Rechercher...">
            <span class="tooltip">Rechercher</span>
          </li>
          <div class="loader">
            <img class="loader" src="Design/picture/loader.gif">
          </div>
        </form>
        <div class="result" id="result"></div>
      </div>