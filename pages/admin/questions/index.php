<?php
// verification du role et token

include('../../../db/connexion.php');

try {
  $statement = $pdo->query('SELECT * FROM gfc_questions');
} catch (\PDOException $e) {
  die('ERREUR SQL : '.$e->getMessage());
}

$questions = $statement->fetchAll();

//  header
require_once('../../../layouts/Header/index_admin.php') ?>

<div class="container main mt-4">

  <div class="header-title card mb-5">
    <h2 class="title card-header">
      <span>Gestion des questions </span>

      <a href="./create.php" class="btn btn-primary">
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
            <a href="./update.php?id=<?= $question->id ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
            <a onclick="return confirm('Voulez vous vraiment supprimer ?')" href="./delete.php?id=<?= $question->id ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>

</div>

<?php require_once('../../../layouts/Footer/index.php') ?>