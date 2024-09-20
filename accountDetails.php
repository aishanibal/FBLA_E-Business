<?php
/*
plugin name: Account Details
*/

$path = preg_replace('/wp-content.*$/', '', __DIR__);
require_once($path . '/wp-load.php');


function accountDetails()
{
 global $wpdb; 
 $email;
 clearstatcache();
 $currentUser = get_current_user_id();

 //User email
 $result = $wpdb->get_results("SELECT user_email, user_login, user_registered FROM wpkw_users where ID = $currentUser");
 foreach ($result as $row) { 
    $email= $row->user_email;
    $user = $row->user_login;
    $regTime = $row->user_registered;
 }

?>
<style>
    <meta name="viewport"content="width=device-width, initial-scale=1.0>
    <?php include "styles.css"?>
</style>
<body>
<fieldset>
<h2> Account Details </h2>
<h4> Username: </h4>
<p> <?php echo $user ?> </p>
<h4> Registered E-mail: </h4>
<p> <?php echo $email ?> </p>
<h5> Membership Points: 0 </h5>
<p>Not a member? Check out our <a href = "https://metroatlantis.site/membership/" >membership page</a> and take advantage of our member deals!</p>
</br>
<p> Date Created: <?php echo $regTime ?> </p>

<h2> Booking Details </h2>
<?php

 //-------- Using user email to find everything else
 //Total price
 $array = $wpdb->get_results("SELECT total, roomsnum, tot_taxes, confirmnumber, days, checkout FROM  wpkw_vikbooking_orders
 where custmail='".$email."'");
 $i = 1;
  foreach ($array as $row) { 
    $total= $row-> total;
    $tax= $row-> tot_taxes;
    $conNum= $row-> confirmnumber;
    $numRooms= $row-> roomsnum;
    $days = $row -> days;
    $trackNum = $row -> checkout;
    ?>
    <h5> Booking Order #<?php echo $i ?> </h5>
    <p> Room Names: 
   <?php
      $table = $wpdb -> get_results("SELECT idroom FROM wpkw_vikbooking_busy where checkout = $trackNum");
      foreach($table as $row2){
          $idRoom = $row2 -> idroom;
          $table2 = $wpdb -> get_results("SELECT name, info FROM wpkw_vikbooking_rooms where id = $idRoom");
          foreach($table2 as $row3){
             $roomName = $row3 -> name;
             $info = $row3 -> info;
             echo $roomName . ", ";

          }

     }
   ?>
</p>

    <p> Description: <?php echo $info ?> </p>
    <p> Total Price: <?php echo '$'.$total ?> </p>
    <p> Total Tax: <?php echo '$'.$tax ?> </p>
    <p> Confirmation Number: <?php echo $conNum ?> </p>
    <p> Number of Rooms: <?php echo $numRooms ?> </p>
    <p> Number of Days: <?php echo $days ?> </p>

    <?php
    $i++;
      }
      
    ?>

    <?php
    
 }
  ?>
  </fieldset>
</body>




<?php
/*function*/
add_shortcode('accountDetails', 'accountDetails'); //function and shortcode
?>