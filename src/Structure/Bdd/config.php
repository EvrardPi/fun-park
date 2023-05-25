<?php
  try {
          $bdd = new PDO('mysql:host=localhost;dbname=funpark', '', '');
  }
  catch(Exception $e)
  {
    die('Erreur de bdd:' . $e->getMessage());
  }
?>