<?php 
  include('../../../db/connexion.php');

  
  $quiz = null;
  // We check if there is a quiz id in parameter 
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
  }
  
  if(!$quiz){
    http_response_code(403);
    exit;
  }

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
  } catch (\PDOException $e) {
    die('ERREUR SQL : '.$e->getMessage());
  }

  $quizs = $statementQuiz->fetchAll();
  $types = $statementType->fetchAll();


   # Création  de la question
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

      echo '<pre>';
      print_r($answers);
      print_r($_POST);
      print_r($responses);
      echo '</pre>';

      try {
        // save BD
        $statement = $pdo->prepare(
        'INSERT INTO gfc_questions 
          (quiz_id,type,question,is_requered,answers,date,correct_answer,is_more_answers)
          VALUES( :quiz_id, :type, :question, :is_requered, :answers, :date, :correct_answer,:is_more_answers)'
        );
        
        $statement->execute([
          'quiz_id'     => (int)$quiz_id,
          'type'        => $type,
          'question'    => $intitule,
          'is_requered'  => $required,
          'answers'     => $answers,
          'date'        => $date,
          'correct_answer' => $responses,
          'is_more_answers' => $is_more_answers
        ]);

        header('location:./');
        
      } catch (\PDOException $e) {
        die('ERREUR SQL : '.$e->getMessage());
      }
  }

  require_once('../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">
  <div class="header-title card">
    <h2 class="title card-header">
      <span>Création d'une question <span class="text-primary"><?= $quiz ? "| ".$quiz->name:'' ?></span></span>

      <a href="./" class="btn btn-secondary">
        Retour a la page d'accueil
      </a>
    </h2>
  </div>


  <div class="card mt-5 mb-3">
    <div class="card-header"></div>
    <form action="" method="POST" class="p-4">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="quiz" class="form-label">Quiz *</label>
            <select required name="quiz_id" id="quiz" class="form-control">
              <option value=""> - Choisissez le quiz de la question -</option>
              <?php foreach($quizs as $quiz): ?>
                <option value="<?= $quiz->id ?>"><?= $quiz->name ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="type" class="form-label">Choisissez le type de la question</label>
            <select required name="type" id="type" class="form-control">
              <option value=""> - type de question -</option>
              <?php foreach($types as $type): ?>
                <option value="<?= $type->name ?>"><?= $type->display_name ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Titre de la question *</label>
            <input name="intitule" required type="text" class="form-control" id="exampleFormControlInput1" placeholder="Entrer l'intitulé de la question">
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3 pt-4 d-flex justify-content-between align-items-center">
            <label for="required" class="form-label">Question obligatoire ?</label>
            <input style="width: 20px; height:20px" name="required" type="checkbox" id="required" >
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <div class="line"></div>
            <div class="mb-1 d-flex justify-content-between align-items-center">
              <label for="input-response-type" class="form-label fw-bold">Peut t’il y avoir plusieurs réponses possibles pour cette question ?</label>
              <span>
                <span id="label-response-type" style="display: inline-block;transform:translateY(-.4rem)">Non</span>
                <input name="is_more_answers" id='input-response-type' style="width: 20px; height:20px" type="checkbox" >
              </span>
            </div>

            <div class="mb-3 response-div" id="response-one">
              <label for="response" class="form-label">Réponse</label>
              <input type="text" class="form-control" name="response" id="response" placeholder="Entrer la réponse se question">
            </div>

            <div class="mb-3 card invisible response-div" id="response-many">
              <div class="card-header d-flex justify-content-between align-items-center">
                <span class="h4">Liste des réponses possibles</span>
                <i id="add-sub" class="fa-solid fa-circle-plus text-secondary h2" style="cursor: pointer;"></i>
              </div>
              <div class="card-body" id="sub-content">
              </div>
            </div>

          <div class="line"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="mb-3 card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span class="h4">Liste de suggestion</span>
              <i id="add-sub-2" class="fa-solid fa-circle-plus text-secondary h2" style="cursor: pointer;"></i>
            </div>
            <div class="card-body" id="sub-content-2">
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