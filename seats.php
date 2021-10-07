<?php require "connect.php"?>
<?php

  // Starts the session
  session_start();

  // Makes sure that the session variable is declared and is not NULL
  if(isset($_SESSION['person'])){
    // Converts the POST variable to SESSION variable
    $_SESSION['Title'] = $_POST['Title'];
}

  // Makes sure that the session variable is declared and is not NULL
  if(isset($_SESSION['person'])){
    // Converts the POST variable to SESSION variable
    $_SESSION['PerfDate'] = $_POST['PerfDate'];
}

  // Makes sure that the session variable is declared and is not NULL
  if(isset($_SESSION['person'])){
    // Converts the POST variable to SESSION variable
    $_SESSION['PerfTime'] = $_POST['PerfTime'];
}
    // Makes sure that the session variable is declared and is not NULL
  if (isset($_POST['BasicTicketPrice'])) {
      // Converts the POST variable to SESSION variable
      $_SESSION['BasicTicketPrice'] = $_POST['BasicTicketPrice'];
       // Declares the alias cost so it can be used as in the SQL Query to stop injection attacks
       $cost = $_SESSION['BasicTicketPrice'];

}
?>
<!DOCTYPE html>
<html>
<head>
  <!--Main styling for the page-->
  <div class="info">
  <!--Link to stylesheet-->
   <link rel="stylesheet" href="mystyles.css">
  <title>The Pavillion: Book seats</title>
<script>

  // Javascript function which allows the user to go back one page
  function goBack() {
  history.go(-1);
  return false;
}

  //Check box method which is called when the boxes are checked
  function getCheckboxElements(){

    // Declares variables
    var checkboxes = document.getElementsByClassName('checkboxClass');
    var totalPrice= 0.00;
    var totalSeats = 'Selected Seats: ';
    var num = 0;

    // For loop which checks whether the checkboxes have been ticked if so run the code of the body
    for (var i = 0; i < checkboxes.length; i++){

         // Condition to display the results checkboxes
         if (checkboxes[i].checked){
             totalSeats += checkboxes[i].name + ' - ';

             // Allows the checked seats to be displayed
             // It is passed to the id class seatDetails which is then printed out as a live summary
             document.getElementById('seatDetails').style.display = 'block';
             document.getElementById('seatDetails').innerHTML = totalSeats;

             // Allows the total price to be displayed
             // It is passed to the id class priceDetails which is then printed out as a live summary
             totalPrice = parseFloat(totalPrice) + parseFloat(checkboxes[i].value);
             document.getElementById('priceDetails').innerHTML = totalPrice;
             document.getElementById('priceDetails').style.display = 'block';

             // Condition that the submit button will only work if a checkbox has been selected
             document.getElementById('submitButton').value = 'Confirm Booking';
             document.getElementById('submitButton').disabled = false;
             num += 1;
}
          // Condition if a checkbox hasn't been selected
          else if (totalPrice == 0.00){
                   document.getElementById('seatDetails').style.display = 'none';
                   document.getElementById('priceDetails').style.display = 'none';
}
          // Condition if a checkbox hasn't been selected
          if (num == 0){
              document.getElementById('submitButton').disabled = true;
              document.getElementById('submitButton').value = 'Choose a seat';
        }
    }
}
</script>
</head>
<body>
<?php

  // Conditions for the page to be displayed successfully
  if (isset($_SESSION['Title'], $_SESSION['PerfDate'], $_SESSION['PerfTime']) && !empty($_SESSION['person']) && !empty($_SESSION['email'])) {

  // Welcome message that displays the persons name, the title, date and time of the performance
  echo "<h4> Thanks for clicking through ". $_SESSION['person']."<br>
             Please see the availabilty for :<br>
             ".$_POST['Title']."-".$_POST['PerfTime']."-".$_POST['PerfDate']."</h4>";
  echo "<p> Please select one or more seats and click to proceed:</p>";

?>

<!--Button displayed which will take you back to the previous page-->
<button class= name='Go back' onclick='goBack()'>Previous page</button>

<!-- The live summary of seat booking and cost
     this displays both the cost and the seats booked.
     Data is pulled from the getCheckboxElements function above -->
<div class="Seat" id= 'seatsSummary'>
    <p style= 'display:inline' id= 'seatDetails'></p>
    <p style= 'display:inline' id= 'priceDetails'></p>
  </div>

<!--Scroll bar for the seats,making it easier to navigate the seat selection-->
<div class= "scroll">
<?php

  // Aliases have been created for the session variables so they can be defined in the SQL Query and used on other pages
  $time = $_SESSION['PerfTime'];
  $date = $_SESSION['PerfDate'];
  $title = $_SESSION['Title'];

    // Parameterised query to protect against SQL injection attacks
    // Query retrieves the available seats and prices of those seats
  $sql= "SELECT S.RowNumber, ROUND(Z.PriceMultiplier * :m, 2) AS price
         FROM Seat S JOIN Zone Z ON Z.Name=S.Zone
         WHERE S.RowNumber NOT IN
        (SELECT B.RowNumber FROM Booking B
         WHERE B.PerfTime= '$time'
         AND B.PerfDate= '$date'
         AND S.Zone = Z.name)
         ORDER BY S.RowNumber ASC";

         $handle = $conn->prepare($sql);

         // The $cost variable passes the arrays values to the letter m which acts as the BasicTicketPrice
         // allowing the query to execute without fear of injection attacks
         $handle->execute(array(":m" => $cost));
         $conn = null;

         $res = $handle->fetchAll();

         // Table created
         echo "<table>";

         // Foreach loop iterates through query and prints results
         foreach($res as $row){

         // form that specifies the page that the data is taken forward to
         // The method POST is used to take this data forward
         echo "<tr>
               <form name='seatForm' action='Book.php' method='POST'>";

         //Creates a row for the seat number
         echo "<td>". $row['RowNumber'] ."</td>";

         //Creates a row for the price of the seat
         echo "<td>"."£" . $row['price'] ."</td>";

         // Onchange element changes when a checkbox has been clicked
         echo "<td><input type ='checkbox' class='checkboxClass' value={$row['price']} id ='checkbox' name = {$row['RowNumber']}
                    onchange='getCheckboxElements()'><td>";

         echo"</tr>";

}

         echo "</table>";

        // Error message
}        else {

         echo "<p>There has been an issue.</p>
               <br>
               <a href='index.html'> Go back to the home page<a></p>";

}
?>
</div>

  <!--The submit button which will only work when the checkbox has been created-->
  <input type ='submit' class='btn3' id='submitButton' value='Calculate Price' id='confirmCTA' disabled = 'true'>

</form>
</div>
</body>
</html>
