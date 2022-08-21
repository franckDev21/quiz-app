<?php

session_start();
$csrf_token = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : "";

// We check if there is a quiz id in parameter 
if(!isset($_GET['quiz_id'])){
  http_response_code(403);
  exit;
}

include('../../db/connexion.php');

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

//  get all questions
try {
  $statement2 = $pdo->prepare('SELECT * FROM gfc_questions WHERE quiz_id = :quiz_id');
  $statement2->execute([
    'quiz_id' => (int)$_GET['quiz_id']
  ]);
} catch (\PDOException $e) {
  die('ERREUR SQL : ' . $e->getMessage());
}

$questions = $statement2->fetchAll();
$newQuestions = [];

foreach ($questions as $k => $question) {
  $newQuestions[] = (object)[
    'id' => $question->id,
    'quiz_id' => $question->quiz_id,
    'type' => $question->type,
    'question' => $question->question,
    'is_requered' => $question->is_requered,
    'correct_answer' => $question->correct_answer,
    'is_more_answers' => $question->is_more_answers,
    'answers' => (trim($question->answers) !== null || trim($question->answers) !== '') ? unserialize(trim($question->answers)) : (object)[]
  ];
}

$questions = $newQuestions;
// echo '------------ questions ----------';
// echo '<pre>';
// print_r($questions);
// echo '</pre>';
// echo '------------ questions ----------';

if (
  $_SERVER['REQUEST_METHOD'] === 'POST' &&
  (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $csrf_token)
) {
  http_response_code(419);
  exit;
}

if (!$csrf_token) {
  // generate token and persist for later verification
  // - in practice use openssl_random_pseudo_bytes() or similar instead of uniqid() 
  $csrf_token = md5(uniqid());
  $_SESSION['csrf_token'] = $csrf_token;
}
session_write_close();

# include("../php/checklogin.php");    

$page = 'quizz-fhm';


// handle form submission
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

# define variables and set to empty values
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["submit"]) {

  // echo '<pre>';
  // print_r($_POST);
  // echo '</pre>';

  $_SESSION['rainbow_uid'] = 1; // to be modified after

  # We get the user
  $student_id = $_SESSION['rainbow_uid'];
  $query = $pdo->prepare("SELECT * FROM gfc_dossiers WHERE id = :id");
  $query->execute([
    'id' => $student_id
  ]);
  $dossier = $query->fetch();

  if (!$dossier) {
    http_response_code(403);
    exit;
  }

  # Recording of the student's answers 
  $userAnswers = [];
  $countQuestions = count($questions);
  $noteUser = 0;

  foreach ($questions as $qIndex => $question) {
    $value = null;
    
    if(isset($_POST["question$qIndex"])){
      if (is_array($_POST["question$qIndex"])) {
        $value = implode(', ', array_map('test_input', $_POST["question$qIndex"]));
      } else {
        $value = test_input($_POST["question$qIndex"]);
      }
    }else{
      $value = '';
    }

    $userAnswers[] = (object)[
      "question" => $question->question,
      "answer" => $value,
      "type" => $question->type,
      "correct_answer" => $question->correct_answer,
      "is_more_answers" => $question->is_more_answers,
    ];
  }

  // echo '<pre>';
  // print_r($userAnswers);
  // echo '</pre>';

  /**
   * on devrais peut être monbre_minimun_reponse_juste dans la table question et lorsqu'on choisie 'checkbox' || 'Choix multiple'
   * et faire apparaitre un input qui prend cette valeur
   * 
   * cette monbre_minimun_reponse_juste poura être use lorsqu'on corrige un quiz que l'edudiant repond
   */
  // verifier s'il y'a plusieur reponse possible a la question (is_more_answers === true || (is_more_answers === false))
    // si oui
        # verifier si le type de la question est checkbox (plusieurs reponses)
          // si oui 
            // verifier si au moins une des valeurs coché === se trouve dans le tab des reponse possible ( s'il y'avais 'monbre_minimun_reponse_juste' , on devais s'appuyer dessus)
              // si oui 
                // is_correct === true
              // si non 
                // is_correct === false
          // si non
            // verifier si la reponse du user se trouve dans l'ensemble des choix possibles
              // si oui 
                // doit mettre la prop is_correct a true
              // si non 
                // a is_correct === false
    // si non
        # verifier si le type de la question est checkbox (plusieurs reponses)
          // si oui
            // verifier si au moins une des valeurs coché === reponse attendue
              // si oui 
                // is_correct === true
              // si non 
                // is_correct === false
          // sinon
            // verifier si reponse === reponse attendu
              // si oui 
                // is_correct === true
              // si non 
                // is_correct === false



  foreach ($userAnswers as $userAnswer) {
    $q = $userAnswer->question;
    $r = $userAnswer->answer;
    $is_correct = false;

    if($userAnswer->is_more_answers === 1){
      $moreResponses = trim($userAnswer->correct_answer) === '' ? $userAnswer->correct_answer : unserialize($userAnswer->correct_answer);
      $moreResponsesUser = null;
      if(is_array($moreResponses)){
        // transformer toutes les valeurs du tab reponse en minuscule
        foreach($moreResponses as $k => $response){
          $moreResponses[$k] = strtolower($response);
        }
        if($userAnswer->type === 'checkbox'){
          $moreResponsesUser = explode(',',$userAnswer->answer);
          foreach($moreResponsesUser as $k => $response){
            $moreResponsesUser[$k] = strtolower(trim(trim($response)));
          }
          foreach($moreResponsesUser as $response){
            if(in_array($response,$moreResponses)){
              $is_correct = true;
              $noteUser = $noteUser + 1;
            }
          }
          // echo '<pre>';
          //   echo '------ checkbox & many reponse ------- <br>';
          //   var_dump($is_correct);
          //   print_r($moreResponsesUser);
          //   print_r($moreResponses);
          //   echo '<br> ------ checkbox & many reponse ------- <br>';
          // echo '</pre>';
        }else{
          if(in_array(strtolower($r),$moreResponses)){
            $is_correct = true;
            $noteUser = $noteUser + 1;
          }
          // echo '<pre>';
          //   echo '------ autre & many reponse ------- <br>';
          //   var_dump($is_correct);
          //   print_r($moreResponsesUser);
          //   print_r($moreResponses);
          //   print_r($r);
          //   echo '<br> ------ autre & many reponse -------';
          // echo '</pre>';
        }
      }
    }else{
      if($userAnswer->type === 'checkbox'){
        $moreResponses = explode(',',$userAnswer->answer);
        foreach($moreResponses as $k => $response){
          $moreResponses[$k] = strtolower(trim(trim($response)));
        }
        foreach($moreResponses as $response){
          if($response === strtolower(trim($userAnswer->correct_answer))){
            $is_correct = true;
            $noteUser = $noteUser + 1;
          }
        }
        // echo '<pre>';
        //   echo '<br> ------ checkbox & any reponse ------- <br>';
        //   var_dump($is_correct);
        //   print_r($moreResponses);
        //   print_r($userAnswer->correct_answer);
        //   echo '<br> ------ checkbox & any reponse ------- <br>';
        // echo '</pre>';
      }else{
        if(strtolower($r) === strtolower(trim($userAnswer->correct_answer))){
          $is_correct = true;
          $noteUser = $noteUser + 1;
        }
        // echo '<pre>';
        //   echo '<br> ------ autre & any reponse ------- <br>';
        //   var_dump($is_correct);
        //   print_r($userAnswer->correct_answer);
        //   print_r($r);
        //   echo '<br> ------ autre & any reponse ------- <br>';
        // echo '</pre>';
      }
    }

    $sql = "INSERT INTO gfc_user_answers 
            ( `quiz_id`, `dossier_id`, `question`, `answer`, `date`,`is_correct`) 
    VALUES ('{$_GET['quiz_id']}', '$dossier->id', \"$q\", \"$r\", NOW(),'$is_correct')";
    $pdo->query($sql);
  }

  $sql = "INSERT INTO gfc_user_result_quiz 
            ( `quiz_id`, `dossier_id`, `note`, `total_note`, `date`) 
    VALUES ('{$_GET['quiz_id']}', '$dossier->id',$noteUser,$countQuestions, NOW())";
  $pdo->query($sql);


  $success = true;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Questionnaire à froid sur votre FORMATION Hybrid Marketing </title>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <style>
    li {
      list-style-type: none;
    }
  </style>
</head>

<body style="background:#eee;">
  <!--navigation menu-->
  <nav class="navbar navbar-default title1">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="https://Joo.parapacem.fr/"><b>Dashboard</b></a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="dash.php?q=0">ACCUEIL<span class="sr-only">(current)</span></a></li>

        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <!--navigation menu closed-->
  <div class="container">
    <!--container start-->


    <?php if (isset($success) && $success) : ?>

      <div class="col-md-12">
        <h2 class="text-center text-success">
          Vos réponses ont bien été sauvergardées !
        </h2>
        <br />
        <br />
        <p class="text-center">
          <a href="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>?quiz_id=<?= $_GET['quiz_id'] ?>" class="btn btn-success">Retour à l'accueil</a>
        </p>
      </div>

    <?php else : ?>

      <div class="col-md-10 col-md-offset-1 mb-4 shodow" style="border-bottom: 1rem solid #337AB7AA;margin-bottom: 1.5rem; background-color:#fff; border-radius:4px">
        <div class="h1 text-primary" style="font-weight: bold;">
          <?= $quiz->name ?>
        </div>
      </div>

      <form id="form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>?quiz_id=<?= $_GET['quiz_id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>" />
        <input type="hidden" name="submit" value="1" />
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <!--home start-->
            <?php foreach ($questions as $index => $question) : ?>
              <?php $iteration = $index + 1; ?>

              <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                  Question <?= $iteration; ?>.
                  &nbsp;
                  <small class="text-danger"><?= $question->is_requered ? '[OBLIGATOIRE]' : ''; ?></small>
                </div>

                <div class="panel-body">
                  <p><?= $question->question; ?></p>
                </div>
                
                <div class="error-div"></div>
                <!-- List group -->
                <ul class="list-group <?= $question->is_requered && $question->type == 'checkbox' ? 'required' : ''; ?>">
                  <?php switch ($question->type):
                    case 'text': ?>
                    <?php case 'tel': ?>
                    <?php case 'number': ?>
                    <?php case 'url': ?>
                    <?php case 'email': ?>
                      <li class="list-group-item">
                        <input 
                          type="<?= $question->type; ?>" 
                          
                          name="<?= "question$index"; ?>" <?= $question->is_requered ? 'required' : ''; ?> 
                          class="form-control" 
                        />
                      </li>
                      <?php break; ?>
                    <?php
                    case 'textarea': ?>
                      <li class="list-group-item">
                        <textarea class="form-control" name="<?= "question$index"; ?>" <?= $question->is_requered ? 'required' : ''; ?>></textarea>
                      </li>
                      <?php break; ?>
                    <?php case 'rating': ?>
                      <li class="list-group-item">
                        <div class="row">
                          <div class="col-md-3"><?= $question->answers[0]; ?></div>
                          <div class="col-md-6">
                            <ol style="display: flex; justify-content: space-around;">
                              <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <li>
                                  <input type="radio" <?= $i === 3 ? 'checked':'' ?> value="<?= $i; ?>" name="<?= "question$index"; ?>" <?= $question->is_requered ? 'required' : ''; ?> />
                                </li>
                              <?php endfor; ?>
                            </ol>
                          </div>
                          <div class="col-md-3"><?= $question->answers[1]; ?></div>
                        </div>
                      </li>
                    <?php break; ?>

                    <?php case 'radio': ?>
                      <?php foreach ($question->answers as $anserIndex => $answer) : ?>
                        <?php $answerIteration = $anserIndex + 1; ?>
                        <li>
                          <label class="list-group-item">
                            <input <?= $anserIndex === 0 ? 'checked':'' ?> type="<?= $question->type; ?>" value="<?= $answer; ?>" name="<?= "question$index"; ?>[]" />
                            &nbsp;&nbsp;
                            <?php if ($answer) : ?>
                              <span><?= $answer; ?></span>
                            <?php else : ?>
                              <span>Autre:</span>
                              &nbsp;&nbsp;
                              <input type="text" class="form-control" name="<?= "_question$index"; ?>" />
                            <?php endif ?>
                          </label>
                        </li>
                      <?php endforeach ?>
                    <?php break; ?>

                    <?php case 'checkbox': ?>
                      <?php foreach ($question->answers as $anserIndex => $answer) : ?>
                        <?php $answerIteration = $anserIndex + 1; ?>
                        <li>
                          <label class="list-group-item">
                            <input type="<?= $question->type; ?>" value="<?= $answer; ?>" name="<?= "question$index"; ?>[]" />
                            &nbsp;&nbsp;
                            <?php if ($answer) : ?>
                              <span><?= $answer; ?></span>
                            <?php else : ?>
                              <span>Autre:</span>
                              &nbsp;&nbsp;
                              <input type="text" class="form-control" name="<?= "_question$index"; ?>" />
                            <?php endif ?>
                          </label>
                        </li>
                      <?php endforeach ?>
                  <?php endswitch ?>
                  
                </ul>
              </div>
            <?php endforeach ?>

            <div class="row text-center" style="margin-bottom: 40px;">
              <button type="submit" class="btn btn-lg btn-primary">Envoyer</button>
            </div>

          </div>
        </div>
      </form>

    <?php endif ?>

  </div>
  <!--container closed-->
  </div>
  </div>

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script>

    const form = document.querySelector("#form")

    form.addEventListener('submit', function (e) {
      Array.from(document.querySelectorAll('.required')).forEach(function (ul) {
        console.log(ul, ul.querySelectorAll('input'))
        const errorDiv = ul.parentElement.querySelector('.error-div')

        let atLeastOneIsChecked = false
        ul.querySelectorAll('input').forEach(input => {
          atLeastOneIsChecked |= input.checked
        })

        if (atLeastOneIsChecked) {
          errorDiv.innerHTML = ''
        } else {
          errorDiv.innerHTML = `<div class="alert alert-danger p-4">Vous devez repondre à cette question</div>`
          e.preventDefault()
        }
      })
    })

  </script>
</body>

</html>