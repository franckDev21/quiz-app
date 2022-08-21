<?php 
  include('../../../db/connexion.php');

  ##  verifier le role du user

  function test_input_value($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  try {
    $statementQuiz = $pdo->query('SELECT * FROM gfc_quizs');
    $statementType = $pdo->query('SELECT * FROM gfc_types');

    $statementQuestion = $pdo->prepare('SELECT * FROM gfc_questions WHERE id = :id');
    $statementQuestion->execute([
      'id' => $_GET['id']
    ]);

  } catch (\PDOException $e) {
    die('ERREUR SQL : '.$e->getMessage());
  }

  $quizs = $statementQuiz->fetchAll();
  $types = $statementType->fetchAll();

  # on recupere la question
  $question = $statementQuestion->fetch();
  if(!$question){ // y'a t'il un question avec cette id 
    header("location:./index.php");
  }

  # on recupere le quiz de la question
  $statementQuizQuestion = $pdo->prepare('SELECT * FROM gfc_quizs WHERE id = :id');
  $statementQuizQuestion->execute([
    'id' => $question->quiz_id
  ]);
  $quizForQuestion = $statementQuizQuestion->fetch();

  $suggetions = $question->answers;
  $responses = $question->correct_answer;
  
  if(!trim($suggetions)){
    $suggetions = [];
  }else{
    $suggetions = unserialize($suggetions);

    if($question->is_more_answers == 1){
      $responses = unserialize($responses);
    }else{
      $responses = [];
    }
  }

  // echo '<pre>';
  // print_r($question);
  // echo '</pre>';

  # Mise a jour de la question
  if(
    isset(
      $_POST['quiz_id'] ,
      $_POST['type'] ,
      $_POST['intitule']))
    {
      $quiz_id = test_input_value($_POST['quiz_id']);
      $intitule = test_input_value($_POST['intitule']);
      $required = test_input_value($_POST['required'] ?? '') ? 1:0;
      $type = test_input_value($_POST['type']);
      $is_more_answers = test_input_value($_POST['is_more_answers'] ?? '') ? 1:0;
      $response = test_input_value($_POST['response'] ?? '') ?? '';

      // answers
      $answers = [];
      foreach($_POST as $k => $val){
        if(strpos($k, 'sub_') !== false){
          $answers[] = $_POST[$k];
        }
      }

      // responses
      $responses = null;
      foreach($_POST as $k => $val){
        if(strpos($k, 'reponse_') !== false){
          $responses[] = $_POST[$k];
        }
      }

      // serialisation en json
      $answers = serialize($answers);

      if($is_more_answers === 1){
        // si c'est plusieur reponse
        $responses = serialize($responses);
      }else{
        // si c'est une seule reponse
        $responses = $response ?? '';
      }

      $date = date("Y-m-d H:i:s");

      // echo '<pre>';
      // print_r($_POST);
      // print_r([
      //   $quiz_id,
      //   $intitule,
      //   $required,
      //   $type,
      //   $date
      // ]);
      // print_r($answers);
      // print_r($responses);
      // echo '</pre>';
      // exit;

      try {
        // save BD
        $statement = $pdo->prepare('UPDATE gfc_questions 
          SET 
            quiz_id     = :quiz_id, 
            type        = :type, 
            question    = :question,
            is_requered  = :is_requered,
            answers     = :answers,
            date        = :date,
            correct_answer = :correct_answer,
            is_more_answers = :is_more_answers
          WHERE id      = :id'
        );
        
        $statement->execute([
          'id'          => $_GET['id'],
          'quiz_id'     => (int)$quiz_id,
          'type'        => $type,
          'question'    => $intitule,
          'is_requered'  => $required,
          'answers'     => $answers,
          'date'        => $date,
          'correct_answer' => $responses,
          'is_more_answers' => $is_more_answers
        ]);

        header('location:./update.php');

      } catch (\PDOException $e) {
        die('ERREUR SQL : '.$e->getMessage());
      }
  }

  require_once('../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">
  <div class="header-title card">
    <h2 class="title card-header">
      <span>Edition de la question </span>

      <a href="./" class="btn btn-secondary">
        Retour a la page d'accueil
      </a>
    </h2>
  </div>


  <div class="card mt-5">
    <div class="card-header"></div>
    <form action="" method="POST" class="p-4">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="quiz" class="form-label">Quiz *</label>
            <select required name="quiz_id" id="quiz" class="form-control">
              <option value=""> - Choisissez le quiz de la question -</option>
              <?php foreach($quizs as $quiz): ?>
                <option <?= $quizForQuestion->name === $quiz->name ? 'selected':'' ?> value="<?= $quiz->id ?>"><?= $quiz->name ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="type" class="form-label">Choisissez un type a cette question</label>
            <select required name="type" id="type" class="form-control">
              <option value=""> - type de question -</option>
              <?php foreach($types as $type): ?>
                <option <?= htmlentities($question->type) === htmlentities($type->name) ? 'selected':'' ?> value="<?= htmlentities($type->name) ?>"><?= $type->name ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Titre de la question *</label>
            <input value="<?= $question->question ?>" name="intitule" required type="text" class="form-control" id="exampleFormControlInput1" placeholder="Entrer l'intitulé de la question">
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3 pt-4 d-flex justify-content-between align-items-center">
            <label for="required" class="form-label">Question obligartoire ?</label>
            <input <?= $question->is_requered === 1 ? 'checked':'' ?> name="required" type="checkbox" id="required" >
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="line"></div>
            <div class="mb-1 d-flex justify-content-between align-items-center">
              <label for="input-response-type" class="form-label fw-bold">Peut t’il y avoir plusieurs réponses possibles pour cette question ?</label>
              <span>
                <span id="label-response-type" style="display: inline-block;transform:translateY(-.4rem)"><?= $question->is_more_answers === 1 ? 'Oui':'Non' ?></span>
                <input name="is_more_answers" <?= $question->is_more_answers === 1 ? 'checked':'' ?> id='input-response-type' style="width: 20px; height:20px" type="checkbox" >
              </span>
            </div>

            <div class="mb-3 response-div <?= $question->is_more_answers === 1 ? 'invisible':'' ?>" id="response-one">
              <label for="response" class="form-label">Réponse</label>
              <input value="<?= $question->is_more_answers !== 1 ? $question->correct_answer:'' ?>" type="text" class="form-control" name="response" id="response" placeholder="Entrer la réponse se question">
            </div>

            <div class="mb-3 card response-div <?= $question->is_more_answers === 1 ? '':'invisible' ?>" id="response-many">
              <div class="card-header d-flex justify-content-between align-items-center">
                <span class="h4">Liste des réponses possibles</span>
                <i id="add-sub" class="fa-solid fa-circle-plus text-secondary h2" style="cursor: pointer;"></i>
              </div>
              <div class="card-body" id="sub-content">
                <?php foreach($responses as $k => $reponse): ?>
                  <?php if($reponse): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <input name="<?= "reponse_$k" ?>" value="<?= $reponse ?>" class="form-control-50 form-control mr-4" type="text" placeholder="Entrer votre subjection">
                      <i class="fa-solid fa-circle-xmark h4 text-danger btn-delete"></i>
                    </div>
                  <?php endif ?>
                <?php endforeach; ?>
              </div>
            </div>

          <div class="line"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="mb-3 card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Liste de subjection</span>
              <i id="add-sub-2" class="fa-solid fa-circle-plus text-secondary h2" style="cursor: pointer;"></i>
            </div>
            <div class="card-body" id="sub-content-2">
              <?php foreach($suggetions as $k => $sub): ?>
                <?php if($sub): ?>
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <input name="<?= "sub_$k" ?>" value="<?= $sub ?>" class="form-control-50 form-control mr-4" type="text" placeholder="Entrer votre subjection">
                    <i class="fa-solid fa-circle-xmark h4 text-danger btn-delete"></i>
                  </div>
                <?php endif ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Enregistrer la question</button>
    </form>
  </div>

</div>

<script>
  // add 
  const addBtn = document.querySelector('#add-sub')
  const subContent = document.querySelector('#sub-content')
  let countSub = 1

  const createElement = (type = 'input') => {
    const parentElement = document.createElement('div')

    const element = document.createElement(`${type}`)
    element.setAttribute('placeholder','Entrer votre réponse')
    element.setAttribute('name',`reponse_${countSub}`)
    element.setAttribute('class',`form-control-50 form-control mr-4`)
    element.setAttribute('required',`required`)
    element.setAttribute('id',`form-control-50_${countSub}`)

    const btnDelete = document.createElement('i')
    btnDelete.setAttribute('class',`fa-solid fa-circle-xmark h4 text-danger btn-delete`)

    parentElement.setAttribute('class','d-flex justify-content-between align-items-center mb-3')

    parentElement.append(element)
    parentElement.appendChild(btnDelete)
    
    return parentElement
  }

  if(addBtn){
    // add
    addBtn.addEventListener('click',e => {
      e.preventDefault()
      subContent.appendChild(createElement())
      countSub++
    })

    // delete
    subContent.addEventListener('click',e => {
      if(e.target.classList.contains('btn-delete')){
        e.target.parentElement.remove()
      }
    })
  }
</script>

<script>
  // add 
  const addBtn2 = document.querySelector('#add-sub-2')
  const subContent2 = document.querySelector('#sub-content-2')
  // let countSub = 1

  const createElement2 = (type = 'input') => {
    const parentElement = document.createElement('div')

    const element = document.createElement(`${type}`)
    element.setAttribute('placeholder','Entrer votre subjection')
    element.setAttribute('name',`sub_${countSub}`)
    element.setAttribute('class',`form-control-50 form-control mr-4`)
    element.setAttribute('required',`required`)
    element.setAttribute('id',`form-control-50_${countSub}`)

    const btnDelete = document.createElement('i')
    btnDelete.setAttribute('class',`fa-solid fa-circle-xmark h4 text-danger btn-delete`)

    parentElement.setAttribute('class','d-flex justify-content-between align-items-center mb-3')

    parentElement.append(element)
    parentElement.appendChild(btnDelete)
    
    return parentElement
  }

  if(addBtn2){
    addBtn2.addEventListener('click',e => {
      e.preventDefault()
      subContent2.appendChild(createElement2())
      countSub++
    })
    // add
    addBtn.addEventListener('click',e => {
      
    })

    // delete
    subContent2.addEventListener('click',e => {
      if(e.target.classList.contains('btn-delete')){
        e.target.parentElement.remove()
      }
    })
  }
</script>

<script>
  const inputToggle = document.querySelector('#input-response-type')
  const labelToggle = document.querySelector('#label-response-type')
  const manyResponse = document.querySelector('#response-many')
  const oneResponse = document.querySelector('#response-one')
  
  let openMultiQuestion = false
  inputToggle.addEventListener('change',e => {
    openMultiQuestion = e.target.checked

    manyResponse.classList.toggle('invisible')
    oneResponse.classList.toggle('invisible')

    if(openMultiQuestion){
      labelToggle.textContent = 'Oui'

      
    }else{
      labelToggle.textContent = 'Non'
    }
  })
</script>

<?php require_once('../../../layouts/Footer/index.php') ?>