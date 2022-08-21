<?php 

$a = serialize([
  "lives",
  "Webinaires",
  "Masterclass",
  "live intégration",
  null
]);
echo $a;
echo '--------';
var_dump(unserialize($a));