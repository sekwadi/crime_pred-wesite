<?php

// Create connection
//include('./backend/db.php');
$conn = new mysqli("localhost", "root", "","crime_p");
session_start();

//get all location
$sql = 'SELECT * FROM location';
$location_results = mysqli_query($conn, $sql);

$loc_no = mysqli_num_rows($location_results);


//delete location
if(isset($_POST['delete_location'])){
    $loc_id = $_POST['delete_location'];

   // echo($loc_id);
    $sql = '';

    $sql = "DELETE FROM location WHERE location_id = '$loc_id' ";
    $results = mysqli_query($conn, $sql);

    header('location:../frontend/admin_home.php?msg=Location deleted successfully');
       
}

?>