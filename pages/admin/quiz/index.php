<?php 

include('../../../db/connexion.php');

try {
  $statement = $pdo->query('SELECT * FROM gfc_quizs');
} catch (\PDOException $e) {
  die('ERREUR SQL : '.$e->getMessage());
}

$quizs = $statement->fetchAll();

require_once('../../../layouts/Header/index_admin.php') ?>

  <div class="container main mt-4">
    
    <div class="header-title card mb-5">
      <h2 class="title card-header">
        <span>Gestion des quizs </span>

        <a href="./create.php" class="btn btn-primary">
          Ajouter un quiz
        </a>
      </h2>
    </div>

    <!-- listing des questions -->
    <table id="example" class="table table-striped" style="width:100%">
      <thead>
        <tr>
          <th>Nom du quiz</th>
          <th>Nombre de question</th>
          <th>Auteur</th>
          <th>Date de céation</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($quizs as $quiz) : ?>
          <tr>
            <td><?= htmlentities($quiz->name) ?></td>
            <td>4</td>
            <td>John Doe</td>
            <td><?= htmlentities($quiz->date) ?></td>
            <td>
              <a href="./update.php?id=<?= $quiz->id ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
              <a onclick="return confirm('Voulez vous vraiment supprimer ?')" href="./delete.php?id=<?= $quiz->id ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
              <a href="../result/index.php?quiz_id=<?= $quiz->id ?>" class="btn btn-sm btn-secondary">Dossiers</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>


  </div>

<?php require_once('../../../layouts/Footer/index.php') ?>