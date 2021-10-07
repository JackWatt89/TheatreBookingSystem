<?php require "connect.php"?>
<?php

  // Starts session
  session_start();

?>
<!DOCTYPE html>
<html>
<head>
  <!--Main styling for the page-->
  <div class="info">
  <!--Link to stylesheet-->
  <link rel="stylesheet" href="mystyles.css">
</head>
<body>

<!--Styling for the booking confirmation heading-->
<div class="h1">
  <h3> Booking confirmation</h3>
</div>

<!--Further styling for the booking details paragraph-->
<div class="info">
  <a href="index.html">Go Back Home</a>
<?php

  // Conditions for the page to be displayed successfully
  if(!empty($_POST) && (isset($_SESSION['Title'], $_SESSION['PerfDate'], $_SESSION['PerfTime']))) {

  // Final booking message
  echo "<h5> Thanks for booking ". $_SESSION['person']."<br>
             Please see the details below: </h5>";

?>
</div>

<!-- Styling for the page-->
<div class="info2">
<?php

  // Declaring the aliases again so they can be used in the query below
  $email = $_SESSION['email'];
  $time = $_SESSION['PerfTime'];
  $date = $_SESSION['PerfDate'];

  // Pulls through the persons name and displays it
  echo "<p> Name : ". $_SESSION['person']."</p>";

  // Pulls through the persons email and displays it
  echo "<p> Email : ". $_SESSION['email']."</p>";

  // Pulls through the performance booked and displays it
  echo "<p> Performance  : ".$_SESSION['Title']."</p>";

  // Pulls through the date of the booking and displays it
  echo  "<p> Date : ".$_SESSION['PerfDate']."</p>";

  // Pulls through the time of the booking and displays it
  echo  "<p> Time : ".$_SESSION['PerfTime']."</p>";

  // Associative array which loops through POST
  foreach($_POST as $seat => $price) {

    // SQL query that inserts data into the database
    $sql = "INSERT INTO Booking
            VALUES ('{$email}','{$date}','{$time}','{$seat}');";

            // The handle which connects to the database
            $handle = $conn->prepare($sql);
            $handle->execute();

    //Prints the seat numbers
    echo " <p>" . $seat . " confirmed </p>";

}

    // Calculates and prints the total cost of the seats booked
    echo "<hr><p><strong>Total price</strong>: £" . array_sum($_POST) . "</p>";

    // Closes the connection
    $conn = null;

    // Destroys the session and unsets the variable
    session_destroy();
    unset($_POST);

  //Error message if conditions are not met
  } else {

    echo "<p>There has been an issue.</p>
          <br>
          <a href='index.html'> Go back to the home page<a></p>";
}
?>
</table>
</div>
</div>
</body>
</html>
