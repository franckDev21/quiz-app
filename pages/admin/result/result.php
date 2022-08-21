<?php
// verification du role et token

// We check if there is a quiz id in parameter 
if(!isset($_GET['quiz_id']) || !isset($_GET['dossier_id'])){
  http_response_code(403);
  exit;
}

include('../../../db/connexion.php');

try {
  $statement = $pdo->prepare('SELECT * FROM gfc_quizs WHERE id = :id');
  $statement->execute([
    'id' => $_GET['quiz_id']
  ]);
  $statement2 = $pdo->prepare('SELECT * FROM gfc_dossiers WHERE id = :id');
  $statement2->execute([
    'id' => $_GET['dossier_id']
  ]);
} catch (\PDOException $e) {
  die('ERREUR SQL : ' . $e->getMessage());
} 

$quiz    = $statement->fetch();
$dossier = $statement2->fetch();

if(!$quiz || !$dossier){
  http_response_code(403);
  exit;
}

$query = $pdo->prepare('SELECT * FROM gfc_user_answers WHERE quiz_id = :quiz_id AND dossier_id = :dossier_id');
$query->execute([
  'quiz_id' => $_GET['quiz_id'],
  'dossier_id' => $_GET['dossier_id']
]);

// les questions
$questions = $query->fetchAll();

$userAnswers = [];

foreach($questions as $k => $question){
  $query = $pdo->prepare("SELECT * FROM gfc_questions WHERE question = :question");
  $query->execute([
    'question' => $question->question
  ]);

  $newQuestion = $query->fetch();
  // $newQuestion->
  $userAnswers[] = (object)[
    "id" => $question->id,
    "quiz_id" => $question->quiz_id,
    "answer" => $question->answer,
    "is_correct" => $question->is_correct,
    "date" => $question->date,
    "question" => $newQuestion,
  ];
}

// echo '<pre>';
// print_r($userAnswers);
// echo '</pre>';

function arrayOrString ($value){
  if($value !== ""){
    $tab = unserialize(trim($value) || '') ?? '';
  }
  return "";
}

//  header
require_once('../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">

  <div class="card mb-5">
    <h2 class="title title-b card-header">
      <span class="fw-bold"><?= $quiz->name ?></span> | <span class="fw-bold" style="color: #676767;"><?= $dossier->name ?></span>
    </h2>
    <div class="card-body">
      <span class="h5"><?= count($userAnswers) ?> Question(s)</span>
    </div>
  </div>

  <?php foreach($userAnswers as $k => $question) :?>
    <div class="card mb-4">
      <div class="card-header">
        <span class="h5 fw-bold"><span style="color: #468BD0;" class=" text">Question <?= $k + 1 ?></span> | <?= $question->question->question ?></span> 
        <span class="badge bg-secondary">Secondary</span>
        <?php if($question->is_correct === 1): ?>
          <span class="badge bg-success">Réponse correcte</span>
        <?php else: ?>
          <span class="badge bg-danger">Mauvaise réponse</span>
        <?php endif ?>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item list-group-item-bold"><span class="title">Intitulé de la question : </span> <?= $question->question->question ?></li>
          <li class="list-group-item list-group-item-bold"><span class="title">Réponse de l'étudiant : </span><?= $question->answer ?>  </li>
          <li class="list-group-item list-group-item-bold">
              <span class="title">Choix de proposition : </span>
              <?php if(unserialize($question->question->answers)): ?>
                <br>
                <?php foreach((unserialize($question->question->answers)) as $resp): ?>
                  <span class="link-custum" style="width: 30%;">
                    <input class="mr-3" style="width: 23px; height:23px;" type="checkbox" disabled>
                    <span class=" ml-3"><?= $resp ?></span>
                  </span>
                <?php endforeach ?>
                <?php else: ?>
                  <?php if(is_array(unserialize($question->question->answers))): ?>
                      <?php if(count(unserialize($question->question->answers)) >= 1): ?>
                        <?php foreach((unserialize($question->question->answers)) as $resp): ?>
                          <span class="link-custum" style="width: 30%;">
                            <input class="mr-3" style="width: 23px; height:23px;" type="checkbox" disabled>
                            <span class=" ml-3"><?= $resp ?></span>
                          </span>
                        <?php endforeach ?>
                      <?php else: ?>
                        Aucune
                      <?php endif ?>
                  <?php else: ?>
                    <span><?= $question->question->correct_answer ?></span>
                  <?php endif ?>
                <?php endif ?>
              
          </li>
          <li class="list-group-item list-group-item-bold">
            <span class="title">Réponses juste : </span>
              <?php if($question->question->is_more_answers): ?>
                <br>
                  <?php foreach((unserialize($question->question->correct_answer)) as $resp): ?>
                    <span class="link-custum" style="width: 30%;">
                      <input class="mr-3" style="width: 23px; height:23px;" type="checkbox" disabled>
                      <span class=" ml-3"><?= $resp ?></span>
                    </span>
                  <?php endforeach ?>

                <?php else: ?>
                <span><?= $question->question->correct_answer ?></span>
              <?php endif ?>
              
          </li>
        </ul>
      </div>
    </div>
  <?php endforeach ?>
  
</div>

<?php require_once('../../../layouts/Footer/index.php') ?>