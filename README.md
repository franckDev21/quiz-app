# php -S localhost:8000

# fichier d'entrer ./index.php : http://localhost:8000/

# page admin : 

      Questions: http://localhost:8000/pages/admin/questions/index.php

      QUiz : http://localhost:8000/pages/admin/quiz/index.php

      Dossier quiz : http://localhost:8000/pages/admin/result/index.php?quiz_id=1
      (cliquez sur le btn 'Dossier' dans la page de listing des quizs)

      Result quiz : http://localhost:8000/pages/admin/result/result.php?dossier_id=4&quiz_id=1

# page user : http://localhost:8000/pages/user/index.php?quiz_id=5 
la page user prend un paramètre quiz_id obligatoire ( paceque pour arrivé à cette le user clique forcement sur un quiz )