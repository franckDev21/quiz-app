<?php
include('../../../db/connexion.php');

##  verifier le role du user

function test_input_value($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

# Mise a jour de la question
if (isset($_POST['name'], $_POST['description'])) {
  $name = test_input_value($_POST['name']);
  $description = test_input_value($_POST['description'] ?? '') ?? null;

  $date = date("Y-m-d H:i:s");

  // echo '<pre>';
  // print_r($_POST);
  // print_r([
  //   $name,
  //   $description
  // ]);
  // echo '</pre>';
  // exit;

  try {
    // save BD
    $statement = $pdo->prepare(
      'INSERT INTO gfc_quizs (name,description,date) VALUES(:name,:description,:date)'
    );

    $statement->execute([
      'name'        => $name,
      'description' => $description,
      'date'        => $date
    ]);

    header('location:./update.php');
  } catch (\PDOException $e) {
    die('ERREUR SQL : ' . $e->getMessage());
  }
}

require_once('../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">

  <div class="header-title card">
    <h2 class="title card-header">
      <span>Création du nouveau quiz</span>

      <a href="." class="btn btn-primary">
        Retour a la page d'accueil
      </a>
    </h2>
  </div>


  <div class="card mt-5">
    <div class="card-header"></div>
    <form action="" method="POST" class="p-4">
      <div class="row">
        <div class="col-md-12">
          <div class="mb-3">
            <label for="quiz" class="form-label">Nom du quiz *</label>
            <input required name="name" placeholder="Nom du quiz" id="quiz" class="form-control" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Description <em class="text-muted">(optionnel)</em></label>
            <textarea placeholder="Entrez une description au quiz" class="form-control" id="exampleFormControlInput1" name="description" id="" cols="30" rows="10"></textarea>
          </div>
        </div>
      </div>


      <button type="submit" class="btn btn-primary">Enregistrer le quiz</button>
    </form>
  </div>


</div>

<?php require_once('../../../layouts/Footer/index.php') ?>