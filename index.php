<?php 

  // on verifie le role du user 
    // si admin  => redirection page Admin
    header("location:./pages/admin/questions/index.php");

    // si user  => redirection page User
    // header("location:./pages/user/index.php");