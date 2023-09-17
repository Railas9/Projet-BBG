<?php
  function connexpdo($base){
    $dsn="mysql:host=localhost;dbname=".$base;
    $user="root";
    $pass="root";
    try{
      $pdo=new PDO($dsn,$user,$pass);
      return $pdo;
      // retourne la connexion à la base donnée entré en parametre dans la fonction
    }catch(PDOException $except){
      echo"Echec de la connexion",$except->getMessage();
      return FALSE;
      exit();
      // Affiche un message en cas d'erreur
    }
  }
?>