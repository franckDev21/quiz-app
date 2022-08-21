<?php 

include('../../../db/connexion.php');

##  verifier le role du user

if(!isset($_GET['id'])){
  header("location:./index.php"); 
}

try {
  $query = $pdo->prepare('SELECT * FROM gfc_quizs WHERE id = :id');
  $query->execute([
    'id' => $_GET['id']
  ]);

} catch (\PDOException $e) {
  die('ERREUR SQL : '.$e->getMessage());
}

# on recupere la question
$question = $query->fetch();
if(!$question){ // y'a t'il un question avec cette id 
  header("location:./index.php");
}

#on supprime le la question

try {
  $query = $pdo->prepare('DELETE FROM gfc_quizs WHERE id = :id');
  $query->execute([
    'id' => $_GET['id']
  ]);

  header("location:./index.php");

} catch (\PDOException $e) {
  die('ERREUR SQL : '.$e->getMessage());
}