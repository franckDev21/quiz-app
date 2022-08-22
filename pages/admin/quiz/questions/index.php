<?php
// verification du role et token

include('../../../../db/connexion.php');

$quiz = null;

if(isset($_GET['quiz_id'])){
  try {
    $statement = $pdo->prepare('SELECT * FROM gfc_quizs WHERE id = :id');
    $statement->execute([
      'id' => $_GET['quiz_id']
    ]);
  } catch (\PDOException $e) {
    die('ERREUR SQL : ' . $e->getMessage());
  }
  $quiz = $statement->fetch();
  if(!$quiz){
    http_response_code(403);
    exit;
  }
}

try {
  if(isset($quiz)){
    $statement = $pdo->prepare('SELECT * FROM gfc_questions WHERE quiz_id = :id');
    $statement->execute([
      'id' => $_GET['quiz_id']
    ]);
  }else{
    $statement = $pdo->query('SELECT * FROM gfc_questions');
  }
} catch (\PDOException $e) {
  die('ERREUR SQL : '.$e->getMessage());
}

$questions = $statement->fetchAll();

//  header
require_once('../../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">

  <div class="header-title card mb-5">
    <h2 class="title card-header d-flex align-items-center justify-content-between">
      <span>Gestion des questions <span class="text-primary"><?= $quiz ? "| ".$quiz->name:'' ?></span></span>

      <a href="../../questions/create.php" class="btn btn-primary">
        Ajouter un question
      </a>
    </h2>
  </div>

  <!-- listing des questions -->
  <table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>Intitulé de la question</th>
        <th>obligatoire</th>
        <th>type</th>
        <th>Auteur</th>
        <th>Date de céation</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($questions as $question) : ?>
        <tr>
          <td><?= $question->question ?></td>
          <td><?= $question->is_requered ? 'Oui':'Non' ?></td>
          <td><?= htmlentities($question->type) ?></td>
          <td>John Doe</td>
          <td><?= htmlentities($question->date) ?></td>
          <td>
            <a href="../../questions/update.php?id=<?= $question->id ?>&&quiz_id=<?= $quiz->id ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
            <a onclick="return confirm('Voulez vous vraiment supprimer ?')" href="../../questions/delete.php?id=<?= $question->id ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>

</div>

<?php require_once('../../../../layouts/Footer/index.php') ?>