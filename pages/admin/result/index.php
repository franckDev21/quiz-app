<?php
// verification du role et token

// We check if there is a quiz id in parameter 
if(!isset($_GET['quiz_id'])){
  http_response_code(403);
  exit;
}

include('../../../db/connexion.php');

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

try {
  $statement = $pdo->prepare(
    "SELECT *
      FROM gfc_user_answers
      INNER JOIN gfc_dossiers ON gfc_user_answers.dossier_id = gfc_dossiers.id WHERE quiz_id = :quiz_id");
  
  $statement->execute([
    'quiz_id' => $_GET['quiz_id']
  ]);  

} catch (\PDOException $e) {
  die('ERREUR SQL : '.$e->getMessage());
}

$questions_user = $statement->fetchAll();

$userAnswers = [];

foreach($questions_user as $k => $question){
  $query = $pdo->prepare("SELECT * FROM gfc_user_answers WHERE quiz_id = :quiz_id AND dossier_id = :dossier_id");
  $query->execute([
    'quiz_id' => $question->quiz_id,
    'dossier_id' => $question->dossier_id,
  ]);

  $newQuestionQuiz = $query->fetchAll();

  // $newQuestion->
  $userAnswers[] = (object)[
    "id" => $question->id,
    "quiz_id" => $question->quiz_id,
    "dossier_id" => $question->dossier_id,
    "is_correct" => $question->is_correct,
    "date" => $question->date,
    "answer" => $question->answer,
    "name" => $question->name,
    "question" => $question->question,
    "total_question" => count($newQuestionQuiz)
  ];
}

// echo '<pre>';
// print_r($userAnswers);
// echo '</pre>';

//  header
require_once('../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">

  <div class="header-title card mb-5">
    <h2 class="title title--border card-header">
      <span>Dossier quiz | <span class="title--span"><?= $quiz->name ?></span></span>
    </h2>
  </div>

  <!-- listing des questions -->
  <table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>Intitulé de la question</th>
        <th>Dossier</th>
        <th>Nombre de question</th>
        <th>Date de céation</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($userAnswers as $question) : ?>
        <tr>
          <td><?= $question->question ?></td>
          <td><?= htmlentities($question->name) ?></td>
          <td><?= $question->total_question ?></td>
          <td><?= htmlentities($question->date) ?></td>
          <td>
            <a href="./result.php?dossier_id=<?= $question->dossier_id ?>&quiz_id=<?= $question->quiz_id ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
            <!-- <a onclick="return confirm('Voulez vous vraiment supprimer ?')" href="./delete.php?id=<?= $question->id ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a> -->
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  
</div>

<?php require_once('../../../layouts/Footer/index.php') ?>