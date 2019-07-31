<?php

include "connect.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="shortcut icon" type="image/png" href="https://preview.ibb.co/mzgRVc/favico.png"/>
  <title>Bot Users</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<style>
body {
    background-color: black;
}
</style>
</head>
<body>

<div class="container col-lg-8">
  <img src="https://i0.wp.com/boxmining.com/wp-content/uploads/2017/08/binancelogoblack.jpg" width="200">
</div>

<div class="container col-lg-8"> 
  <table class="table table-bordered table-inverse table-hover">
    <thead>
      <tr>
        <th class="text-center">#</th>
        <th class="text-center">First Name</th>
        <th class="text-center">Last Name</th>
        <th class="text-center">@</th>
        <th class="text-center">Last use</th>
      </tr>
    </thead>
    <tbody>
      <?php 

      $result = mysqli_query($link, "SELECT first_name, last_name, username, reg_date FROM users ORDER BY ppvzlabot DESC");
      $count = 0;
      while($row = mysqli_fetch_array($result)){
          $count++;
          echo "<tr>";
          echo '<td class="text-center">'.$count."</td>";
          echo '<td class="text-center">'.substr($row['first_name'], 0, 15)."</td>";
          echo '<td class="text-center">'.substr($row['last_name'],0, 15)."</td>";
          echo '<td class="text-center">'.substr($row['username'],0, 15)."</td>";
          echo '<td class="text-center">'.substr($row['reg_date'],0, 10)."</td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>