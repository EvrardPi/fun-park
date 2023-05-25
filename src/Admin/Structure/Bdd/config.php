<?php
  try {
          $bdd = new PDO('mysql:host=localhost;dbname=funpark', 'root', 'root');
  }
  catch(Exception $e)
  {
    die('Erreur de bdd:' . $e->getMessage());
  }
?>