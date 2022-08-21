<?php

session_start();
$csrf_token = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : "";

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

// include("./db/db.php");
// include("../php/checklogin.php");

$page = 'quizz-fhm';

$questions = [
  [
    "question" => "VOTRE NOM ET PRENOM ainsi que le nom de votre accompagnateur",
    "type" => "text",
    "required" => true,
    "answers" => [],
  ],
  [
    "question" => "Adresse e-mail",
    "type" => "email",
    "required" => true,
    "answers" => [],
  ],
  [
    "question" => "Continuez-vous à suivre les webinaires, lesquels ?",
    "type" => "checkbox",
    "required" => true,
    "answers" => [
      "lives",
      "Webinaires",
      "Masterclass",
      "live intégration",
      null
    ],
  ],
  [
    "question" => "A quels webinaires assistiez-vous ?",
    "type" => "checkbox",
    "required" => true,
    "answers" => [
      "lives",
      "webinaires",
      "Masterclass",
      "Lives intégration",
      null
    ],
  ],
  [
    "question" => "Avez-vous pu créer votre entreprise rapidement ?",
    "type" => "radio",
    "required" => true,
    "answers" => [
      "OUI",
      "NON"
    ],
  ],
  [
    "question" => "Si vous n'avez pas encore créé votre entreprise, comptez-vous le faire et quand ?",
    "type" => "textarea",
    "required" => true,
    "answers" => [],
  ],
  [
    "question" => "Avez-vous été satisfait de votre accompagnement pour débuter votre entreprise ? Effectuer vos premières facturations ou autres démarches ?",
    "type" => "radio",
    "required" => true,
    "answers" => [
      "OUI",
      "NON"
    ],
  ],
  [
    "question" => "AVEZ-VOUS DEJA VOTRE N° SIRET ? Si oui quel est son N° ?",
    "type" => "text",
    "required" => false,
    "answers" => [],
  ],
  [
    "question" => "VEUILLEZ préciser ce qui vous a satisfait au niveau de votre accompagnement individualisé et collectif",
    "type" => "textarea",
    "required" => false,
    "answers" => [],
  ],
  [
    "question" => "VEUILLEZ préciser ce qui vous plairait de voir comme améliorations dans votre accompagnement individualisé ou collectif ?",
    "type" => "textarea",
    "required" => false,
    "answers" => [],
  ],
  [
    "question" => "QUEL EST LE STATUT QUE VOUS AVEZ CHOISI POUR VOTRE ENTREPRISE OU LE STATUT QUE VOUS ALLEZ CHOISIR ?",
    "type" => "text",
    "required" => true,
    "answers" => [
      "Micro entreprise",
      "SARL",
      "EURL",
      "SASU",
      "AUTRE",
    ],
  ],
  [
    "question" => "SI AUTRE PRECISEZ",
    "type" => "text",
    "required" => false,
    "answers" => [],
  ],
  [
    "question" => "Merci de préciser les catégories dans lesquelles vous auriez le plus besoin d'accompagnement",
    "type" => "text",
    "required" => true,
    "answers" => [
      "Etudier le marché : définition de l’offre et du positionnement, étude de la concurrence, clientèle et prescripteurs, évaluation du chiffre d’affaires prévisionnel",
      "Définir la stratégie marketing, commerciale et de communication de la future entreprise",
      "Valider la rentabilité et les besoins de financement : compte de résultat prévisionnel et plan de financement.",
      "Apprendre à communiquer et convaincre : rédaction du business plan, structuration du pitch commercial.",
      "Mobiliser les financements nécessaires pour démarrer",
      "Mesurer les impacts du statut juridique, fiscal et social sur la vie de l’entreprise et du dirigeant(e).",
      "Identifier les partenariats à mobiliser autour du projet",
      "Connaître les formalités administratives et juridiques à effectuer pour créer.",
      null,
    ],
  ],
  [
    "question" => "Merci d'évaluer chaque catégorie d'accompagnement et d'indiquer si le niveau était satisfaisant pour vos exigences ? Dans l'ensemble ?",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Etudier le marché : définition de l’offre et du positionnement, étude de la concurrence, clientèle et prescripteurs, évaluation du chiffre d’affaires prévisionnel",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Définir la stratégie marketing, commerciale et de communication de la future entreprise",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Valider la rentabilité et les besoins de financement : compte de résultat prévisionnel et plan de financement.",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Apprendre à communiquer et convaincre : rédaction du business plan, structuration du pitch commercial.",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Mobiliser les financements nécessaires pour démarrer",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Mesurer les impacts du statut juridique, fiscal et social sur la vie de l’entreprise et du dirigeant(e).",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "Identifier les partenariats à mobiliser autour du projet",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "connaître les formalités administratives et juridiques à effectuer pour créer.",
    "type" => "rating",
    "required" => true,
    "answers" => [
      "Accompagnement très efficace et cohérent",
      "Accompagnement à améliorer"
    ],
  ],
  [
    "question" => "COMMENTAIRES LIBRES",
    "type" => "textarea",
    "required" => false,
    "answers" => [],
  ],
  [
    "question" => "Pour les personnes en situation de Handicap, quels seraient vos besoins ?",
    "type" => "textarea",
    "required" => true,
    "answers" => [],
  ],
  [
    "question" => "A l'issue de vos QUIZZ, nous vous avons promis un/des cadeau(x)-surprise, Sur quels thèmes souhaiteriez-vous les guides-cadeaux ?",
    "type" => "text",
    "required" => true,
    "answers" => [
      "FACEBOOK",
      "INSTAGRAM",
      "YOUTUBE",
      "TWITTER",
      "TIK TOK",
      "LINKEDIN",
      "FORMATION PHOTO (disponible lors de l'ouverture des extranets)",
      "FORMATION AIDES à la création (si vous ne l'avez pas déjà souscrite)",
      "FORMATION spéciale création d'entreprise (si vous ne l'avez pas déjà souscrite)",
      "E-book sur l'Ingénierie de Paix",
    ],
  ],
];


// handle form submission
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// define variables and set to empty values
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["submit"]) {

  $student_id = $_SESSION['rainbow_uid'];
  $query1 = $conn->query("SELECT ncpf FROM student WHERE id = $student_id");
  $dossier_id = null;
  if ($queryResponse = $query1->fetch_assoc()) {
    $dossier_id = $queryResponse['ncpf'];
  }

  if (!$dossier_id) {
    http_response_code(403);
    exit;
  }

  $userAnswers = [];

  foreach ($questions as $qIndex => $question) {
    $value = null;
    if (is_array($_POST["question$qIndex"])) {
      $value = implode(', ', array_map('test_input', $_POST["question$qIndex"]));
    } else {
      $value = test_input($_POST["question$qIndex"]);
    }

    $userAnswers[] = [
      "question" => $question["question"],
      "answer" => $value
    ];
  }


  foreach ($userAnswers as $userAnswer) {
    $q = $userAnswer['question'];
    $r = $userAnswer['answer'];
    $sql = "INSERT INTO quiz_hybrid_marketing ( `id`, `dossier_id`, `question`, `reponse`, `date`) VALUES (NULL, '$dossier_id', \"$q\", \"$r\", NOW())";
    $conn->query($sql);
  }

  $success = true;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Questionnaire à froid sur votre FORMATION Hybrid Marketing </title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/font.css">
  <script src="js/jquery.js" type="text/javascript"></script>

  <script src="js/bootstrap.min.js" type="text/javascript"></script>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script>
    $(function() {
      $(document).on('scroll', function() {
        console.log('scroll top : ' + $(window).scrollTop());
        if ($(window).scrollTop() >= $(".logo").height()) {
          $(".navbar").addClass("navbar-fixed-top");
        }

        if ($(window).scrollTop() < $(".logo").height()) {
          $(".navbar").removeClass("navbar-fixed-top");
        }
      });
    });
  </script>
</head>

<body style="background:#eee;">
  <div class="header">
    <div class="row">
      <div class="col-lg-6">
        <!-- <span class="logo">FORMATION Hybrid Marketing</span> -->
      </div>
      <?php
      // include_once './db/db.php';
      // session_start();
      // $email = $_SESSION['rainbow_username'];
      // if (!(isset($_SESSION['rainbow_username']))) {
      //   // header("location:index.php");
      // } else {
      //   $name = $_SESSION['rainbow_name'];

      //   include_once './db/db.php';
      //   echo '<span class="pull-right top title1" ><span class="log1">Questionnaire FORMATION Hybrid Marketing<span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Hello,</span> <a href="#" class="log log1">' . $name . '</a>&nbsp;|&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Logout</button></a></span>';
      // } 
      ?>

    </div>
  </div>
  <!-- admin start-->

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
          <a href="https://Joo.parapacem.fr/" class="btn btn-success">Retour à l'accueil</a>
        </p>
      </div>

    <?php else : ?>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
        <input type="hidden" name="submit" value="1" />
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <!--home start-->


            <?php 
              foreach ($questions as $index => $question) : ?>
              <?php $iteration = $index + 1; ?>
              <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                  Question <?php echo $iteration; ?>.
                  &nbsp;
                  <!-- <small class="text-danger"><?php echo $question['required'] ? '[OBLIGATOIRE]' : ''; ?></small> -->
                </div>
                <div class="panel-body">
                  <p><?php echo $question['question']; ?></p>
                </div>


                <!-- List group -->
                <ul class="list-group">
                  <?php switch ($question['type']):
                    case 'text': ?>
                    <?php
                    case 'email': ?>
                      <li class="list-group-item">
                        <input type="<?php echo $question['type']; ?>" name="<?php echo "question$index"; ?>" <?php echo $question['required'] ? 'required' : ''; ?> class="form-control" />
                      </li>
                      <?php break; ?>
                    <?php
                    case 'textarea': ?>
                      <li class="list-group-item">
                        <textarea class="form-control" name="<?php echo "question$index"; ?>" <?php echo $question['required'] ? 'required' : ''; ?>></textarea>
                      </li>
                      <?php break; ?>
                    <?php
                    case 'rating': ?>
                      <li class="list-group-item">
                        <div class="row">
                          <div class="col-md-3"><?php echo $question['answers'][0]; ?></div>
                          <div class="col-md-6">
                            <ol style="display: flex; justify-content: space-around;">
                              <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <li>
                                  <input type="radio" value="<?php $i; ?>" name="<?php echo "question$index"; ?>" <?php echo $question['required'] ? 'required' : ''; ?> />
                                </li>
                              <?php endfor; ?>
                            </ol>
                          </div>
                          <div class="col-md-3"><?php echo $question['answers'][1]; ?></div>
                        </div>
                      </li>
                      <?php break; ?>
                    <?php
                    case 'radio': ?>
                    <?php
                    case 'checkbox': ?>
                      <?php foreach ($question['answers'] as $anserIndex => $answer) : ?>
                        <?php $answerIteration = $anserIndex + 1; ?>
                        <li class="list-group-item">
                          <input type="<?php echo $question['type']; ?>" value="<?php echo $answer; ?>" name="<?php echo "question$index"; ?>[]" />
                          &nbsp;&nbsp;
                          <?php if ($answer) : ?>
                            <span><?php echo $answer; ?></span>
                          <?php else : ?>
                            <span>Autre:</span>
                            &nbsp;&nbsp;
                            <input type="text" class="form-control" name="<?php echo "_question$index"; ?>" />
                          <?php endif ?>
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
</body>

</html>