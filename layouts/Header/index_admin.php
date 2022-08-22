<?php
//  on vérifie si c'est un admin sinon , redirection vers la page user
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Quiz | Quiz</title>


  <!-- bootstrap -->
  <link rel="stylesheet" href="../../../styles/bootstrap.css">

  <!-- css -->
  <link rel="stylesheet" href="../../../styles/index.css">

  <!-- data table css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

  <!-- font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand logo-text" href="#">Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link active" aria-current="page" 
            <?php if(isset($quiz)): ?>
              href="../../../../pages/admin/quiz/index.php"
            <?php else: ?>
              href="../../../pages/admin/quiz/index.php"
            <?php endif ?>
            
          >Quiz</a>
          <a class="nav-link" 
            <?php if(isset($quiz)): ?>
              href="../../../../pages/admin/questions/index.php"
            <?php else: ?>
              href="../../../pages/admin/questions/index.php"
            <?php endif ?>
          >Questions</a>
          <!-- <a class="nav-link" href="../../../pages/admin/result/index.php">Resultat quiz </a> -->
        </div>
      </div>
    </div>
  </nav>