<?php

// bd
$pdo = new PDO('mysql:dbname=quiz_app;port=3308;host=localhost','root','root',[
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
