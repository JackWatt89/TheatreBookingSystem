<!DOCTYPE html>
<html
<head>
</head>
<body>
<?php

  function myConnect(){}
  {

  //connects to the database
  $host = 'dragon.ukc.ac.uk';
  $dbname = 'jafw2';
  $user = 'jafw2';
  $pwd = 'goteryk';

  try {

  $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch (PDOException $e) {
    echo "PDOException: ".$e->getMessage();
  }
}
?>
</div>
</body>
</html>
