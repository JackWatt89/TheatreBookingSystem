<?php require "connect.php"?>
<div class="info">
<?php

  // Starts the session
  session_start();

  // Makes sure that the session variable is declared and is not NULL
  if(isset($_GET['person'])){
     $_SESSION['person'] = $_GET['person'];

     // Alias is created
     $name = $_GET['person'];

  // Checks whether a valid characters have been entered
  // both in the URL and the box on the index page
  if(!preg_match("/^[a-zA-Z-' ]*$/",$name)){
     $name = NULL;
     $_SESSION['person'] = NULL;

     // Destroys the session after every submission
     // This allows errors to be picked up
     session_destroy();

    }
}

  // Makes sure that the session variable is declared and is not NULL
  if(isset($_GET['email'])){
    $_SESSION['email'] = $_GET['email'];

    // Alias is created
    $email = $_GET['email'];

  // Checks whether a valid email has been entered
  // both in the URL and the box on the index page
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
     $email = NULL;
     $_SESSION['email'] = NULL;

     // Destroys the session after every submission
     // this allows errors to be picked up
     session_destroy();

  }

}
?>
<!DOCTYPE html>
<html
<head>
  <!--Link to stylesheet-->
  <link rel="stylesheet" href="mystyles.css">
  <title>The Pavillion: Performance</title>
</head>
<script>

  // Javascript function which allows the user to go back one page
  function goBack() {
  history.go(-1);
  return false;
}

</script>
<body>
<?php

  // Conditions for the page to be displayed successfully
  if (isset($name) && (isset($email))) {

  // Displays welcome message with the name of the person which was entered in index.html
  // htmlspecialchars adds extra protection to the session variable 'person' and stops people changing the name
  echo "<h1> Welcome  ". htmlspecialchars($_SESSION['person']) . "!</h1>";
  echo "<p> Please select a performance from below:</p>";

  // Query retrieves the title, date and time of the perfomances from the database
  $sql = "SELECT P.Title, P.PerfDate, P.PerfTime, PP.BasicTicketPrice
          FROM Performance P JOIN Production PP
          ON P.Title=PP.Title;";

          // The handle that connects to the database and retrieves the requested data
          $handle = $conn->prepare($sql);
          $handle->execute();
          $conn = null;

          $res = $handle->fetchAll();

  // Allows the php to continue as one so that the div class to be executed in the php
  echo "</div>";

  // Creates table
  echo "<table>";

  // Loop which turns the results into rows
  foreach($res as $row){


          echo "<tr>";

          // Creates a row for title of the production
          echo "<td>".$row['Title']."</td>";

          // Creates a row for date of the production
          echo "<td>".$row['PerfDate']."</td>";

          // Creates a row for time of the production
          echo  "<td>".$row['PerfTime']."</td>";

          // Form that specifies the page that the data is taken forward to using the POST method
          // Submit button which pushes the data through to the next page via hidden values
          echo "<form action='seats.php' method= 'POST'>
          <td><input type ='submit' class='btn2' name='availabilty' value='Check availabilty'></td>
          <input type='hidden' value='{$row['Title']}'name='Title'>
          <input type='hidden' value='{$row['PerfDate']}' name='PerfDate'>
          <input type='hidden' value='{$row['PerfTime']}' name='PerfTime'>
          <input type='hidden' value = {$row['BasicTicketPrice']} name='BasicTicketPrice'>
          </tr>
          </form>";

}
  // Closing tag for the table
  // Button displayed below which will take you back to the previous page
  echo "</table>

  <div class='info'>

  <button class='btn' name='Go back' onclick='goBack()'>Previous page</button>";

  echo "</div>";

    // Error message displayed when incorrect information has been entered
} else {
  echo "<p>There has been an issue.</p>
        <br>
        <a href='index.html'> Go back to the home page<a></p>";
}
?>
</div>
</body>
</html>
