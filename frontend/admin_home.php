<?php
error_reporting(E_ERROR | E_PARSE);
include('../backend/admin.php');


$conn = new mysqli("sql210.epizy.com", "epiz_34121291", "zYvP2EB3S099guV","epiz_34121291_crime_p");


if(empty($_SESSION['admin_fname'])){
  header('location:./admin.php');
}

$current_user = [];

$sql = '';
$sql = "SELECT * FROM officer";
$officer_info =  mysqli_query($conn, $sql);

$no_of_officer = mysqli_num_rows($officer_info);

if(isset($_POST['add_officer'])){

    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    
    if(strlen($id) != 13){
        header('location:./admin_add_officer.php?msg=Invalid ID number length');
        exit;
    }else{
    $id_year = substr($id, 0, 2);
    $current_year = substr(date("Y"), 2, 2);

    echo($id_year);
  
    //validate id year
    if($id_year >= $current_year){
        header('location:./admin_add_officer.php?msg=Invalid ID number year');
        exit;
    }
    $id_month = substr($id, 2, 2);
    
    //validate id month
   if($id_month < 1 || $id_month > 12 ){
        header('location:./admin_add_officer.php?msg=Invalid ID number month');
        exit;
    }
    $id_day = substr($id, 4, 2);
    echo($id_day);

    //validate id day
    if($id_day < 1 || $id_day > 31 ){
        header('location:./admin_add_officer.php?msg=Invalid ID number day');
        exit;
    }  
}

// validate phone number

    if(preg_match('/^[0-9]{10}+$/', $phone)) {
        $validated = true;
    } else {
        header('location:./admin_add_officer.php?msg=Invalid phone number');
        exit;
    }
   

    if($validated){
        $sql = "INSERT INTO officer (officer_id , fname, lname, email,  phone)
                VALUES ('$id' , '$fname', '$lname', '$email', '$phone')";
        $results = mysqli_query($conn, $sql);
      
        header('location:./admin_add_officer.php?msg=Officer info added successfully');
        
        exit;
    }else{
        header('location:./admin_add_officer.php?msg=Something wrong with the info provided');
        exit;
    }
 

}


$sql = '';
$sql = 'SELECT * from victim';

$results_users = mysqli_query($conn, $sql);

$no_of_users = mysqli_num_rows($results_users);

if(isset($_POST['add_account'])){
   // Get data

$id = uniqid();
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

// Validate data


// validate phone number

    if(preg_match('/^[0-9]{10}+$/', $phone)) {
        $validated = true;
    } else {
        header('location:./add_account.php?msg=Invalid phone number');
        exit;
    }
    //hash password
    $password = md5($password);

    if($validated){
        $sql = "INSERT INTO victim ( id , fname, lname, email, phone, password )
                VALUES ('$id' , '$fname', '$lname', '$email', '$phone', '$password')";
        $results = mysqli_query($conn, $sql);
        header('location:./login.php?msg=User created successfully');
        exit;
    }else{
        header('location:.d/add_account.php?msg=Something wrong with the info provided');
        exit;
    }
 
}


if(isset($_POST['login_user'])){
    $email = $_POST['email'];
    $password = $_POST['password'];


    if(!isset($email)){
        header('location:./login.php?msg=Please enter email');
        exit;
    }else if(!isset($password)){
        header('location:../frontend/login.php?msg=Please enter password');
        exit;
    }else {
        $sql = '';
          //hash password
        $password = md5($password);

        $sql = "SELECT * FROM victim WHERE email = '$email' AND password = '$password' LIMIT 1";
        $results = mysqli_query($conn, $sql);


        $current_user = mysqli_fetch_assoc($results);

        if(mysqli_num_rows($results) < 1){
            header('location:./login.php?msg=Invalid login details');
            exit;
        }else{


            $_SESSION['id'] = $current_user['id'];
            $_SESSION['fname'] = $current_user['fname'];
            $_SESSION['lname'] = $current_user['lname'];
            $_SESSION['email'] = $current_user['email'];
            $_SESSION['phone'] = $current_user['phone'];

            header('location:./get_pre.php?msg=Welcome back');
            exit;
        }
    }


}






//login admin

if(isset($_POST['add_account_admin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!isset($email)){
        header('location:./admin.php?msg=Please enter email');
        exit;
    }else if(!isset($password)){
        header('location:./admin.php?msg=Please enter password');
        exit;
    }else {
        $sql = '';

        $sql = "SELECT * FROM admin WHERE email = '$email' AND password = '$password' LIMIT 1";
        $results = mysqli_query($conn, $sql);


        print_r($results);

        if(mysqli_num_rows($results) < 1){
            header('location:./admin.php?msg=Invalid login details');
            exit;
        }else{
            $_SESSION['cur_admin'] = $results;
            header('location:./admin_home.php?msg=Welcome back');
            exit;
        }
    }

    
}


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

    header('location:./admin_home.php?msg=Location deleted successfully');
       
}



if(isset($_POST['delete_user'])){

$cur_id = $_POST['delete_user'];

$sql = "DELETE FROM victim WHERE id = '$cur_id'";
$results = mysqli_query($conn, $sql);
header('location:./admin_home.php?msg=User Deleted!');
      
$cur_id = '';


}






//delete location
if(isset($_POST['delete_location'])){
  $loc_id = $_POST['delete_location'];

 // echo($loc_id);
  $sql = '';

  $sql = "DELETE FROM location WHERE location_id = '$loc_id' ";
  $results = mysqli_query($conn, $sql);

  header('location:./admin_home.php?msg=Location deleted successfully');
     
}


//get all crime info

$id_vic = '';
$officer_id = '';



$sql = '';
$sql = "SELECT * FROM case_info";
$crime_report_info =  mysqli_query($conn, $sql);

$no_of_crimes = mysqli_num_rows($crime_report_info);

if(isset($_POST['add_crime_report'])){
   // Get data

   if(!empty($id_vic)){
        $id_vic = $_POST['victim_id'];
   }else if(!empty($officer_id)){
        $officer_id_no = $_POST['officer_id'];
   }

$officer_id_no = $_POST['officer_idd'];
$id_vic = $_POST['victim_id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];

$crime_id = uniqid('crime00');
//echo($crime_id);

$location = $_POST['location_crime'];



$crime_type = $_POST['crime_type'];
$case_name = $_POST['case_name'];
$case_description = $_POST['case_description'];
$date = $_POST['date'];
$time = $_POST['time'];
$case_description = $_POST['case_description'];

// Validate data



if(strlen($id_vic) != 13){
    header('location:./admin_add_crime.php?msg=Invalid ID number length');
    exit;
}else{
    $id_year = substr($id_vic, 0, 2);
    $current_year = substr(date("Y"), 2, 2);

  //  echo($id_year);
  
    //validate id year
    if($id_year >= $current_year){
        header('location:./admin_add_crime.php?msg=Invalid ID number year');
        exit;
    }
    $id_month = substr($id_vic, 2, 2);
    
    //validate id month
   if($id_month < 1 || $id_month > 12 ){
        header('location:./admin_add_crime.php?msg=Invalid ID number month');
        exit;
    }
    $id_day = substr($id_vic, 4, 2);
    //echo($id_day);

    //validate id day
    if($id_day < 1 || $id_day > 31 ){
        header('location:./admin_add_crime.php?msg=Invalid ID number day');
        exit;
    }  
}

// validate phone number

    if(preg_match('/^[0-9]{10}+$/', $phone)) {
        $validated = true;
    } else {
        header('location:./admin_add_crime.php?msg=Invalid phone number');
        exit;
    }
   

    if($validated){
        

        //exit();

        $sql = "INSERT INTO criminal_info ( criminal_id_n , fname, lname, phone, dob )
                VALUES ('$id_vic' , '$fname', '$lname', '$phone', '$dob');";
                
        $sql2 = "INSERT INTO case ( crime_id , officer_id_no , location_id, crime_type_id, case_name, case_description, date, time, criminal_id_n  )
                VALUES ('$crime_id' , '$officer_id_no' , '$location', '$crime_type', '$case_name', '$case_description', '$date', '$time', '$id_vic');";
   
 
        mysqli_query($conn, $sql);
        mysqli_query($conn, $sql2);
        header('location:./admin_crime.php?msg=Crime info added successfully');
        
echo ('location is : ' . $location);
    
        $id = '';
        $officer_id = '';
        exit;
    }else{
        header('location:./admin_add_crime.php?msg=Something wrong with the info provided');
        exit;
    }

}




if(isset($_POST['delete_crime'])){
    $crime_id = $_POST['delete_crime'];

    $sql = '';

    $sql = "DELETE FROM case WHERE crime_id = '$crime_id' ";
    $results = mysqli_query($conn, $sql);

    header('location:./admin_crime.php?msg=Crime info deleted successfully');
    
}



if(isset($_POST['add_location'])){
  $name = $_POST['location'];
  $lat = $_POST['lat'];
  $long = $_POST['long'];

  $location_id = uniqid('location00');

  $sql = "INSERT INTO location ( location_id , name, latitude, longitude )
  VALUES ('$location_id' , '$name', '$lat', '$long');";
  mysqli_query($conn, $sql);
  header('location:./admin_home.php?msg=Location added successfully');
  exit;

}


if(isset($_POST['edit_location'])){
  session_start();
  $_SESSION['del_loc_id'] = $_POST['edit_location'];
  header('location:./admin_edit_location.php');
}


if(isset($_POST['edit_user'])){
  session_start();
  
  $_SESSION['del_user_id'] = $_POST['edit_user'];

  echo $_SESSION['del_user_id'];
  header('location:./admin_edit_user.php');
}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script type="module" src="../assets/address.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.5.1.js" type="text/javascript"></script>
     <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.js" type="text/javascript"></script>
    <link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
    
    
      <!-- Reference to the Bing Maps SDK -->
      <script type='text/javascript'
            src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' 
            async defer></script>
    
    <script type='text/javascript'>// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.
let map, infoWindow;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 6,
  });
  infoWindow = new google.maps.InfoWindow();

  const locationButton = document.createElement("button");

  locationButton.textContent = "Pan to Current Location";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
  locationButton.addEventListener("click", () => {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          infoWindow.setPosition(pos);
          infoWindow.setContent("Location found.");
          infoWindow.open(map);
          map.setCenter(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        }
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
  });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

window.initMap = initMap;
    </script>


    <style>


/* Float cancel and delete buttons and add an equal width */
.cancelbtn, .deletebtn {
  float: left;
  width: 50%;
}

/* Add a color to the cancel button */
.cancelbtn {
  background-color: #ccc;
  color: black;
}

/* Add a color to the delete button */
.deletebtn {
  background-color: #f44336;
}

/* Add padding and center-align text to the container */
.container {
  padding: 16px;
  text-align: center;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(71, 78, 93, 0.4);
  padding-top: 50px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* Style the horizontal ruler */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}
 
/* The Modal Close Button (x) */
.close {
  position: absolute;
  right: 35px;
  top: 15px;
  font-size: 40px;
  font-weight: bold;
  color: #f1f1f1;
}

.close:hover,
.close:focus {
  color: #f44336;
  cursor: pointer;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and delete button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .deletebtn {
     width: 100%;
  }
}

    </style>


<style type="text/css">
        .ui-autocomplete-loading
        {
            background: white  right center no-repeat;
        }
        #searchBox
        {
            width: 100%;
        }
        
        .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
    </style>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#searchBox").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "http://dev.virtualearth.net/REST/v1/Locations",
                        dataType: "jsonp",
                        data: {
                            key: "AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd",
                            q: request.term
                        },
                        jsonp: "jsonp",
                        success: function (data) {
                            var result = data.resourceSets[0];
                            if (result) {
                                if (result.estimatedTotal > 0) {
                                    response($.map(result.resources, function (item) {
                                        return {
                                            data: item,

                                            

                                            label: item.name + ' (' + item.address.countryRegion + ')',
                                            value: item.name
                                        }
                                    }));
                                    console.log(data)
                                }
                            }
                        }
                    });
                },
                minLength: 1,
                change: function (event, ui) {
                    if (!ui.item)
                        $("#searchBox").val('');
                },
                select: function (event, ui) {
                    displaySelectedItem(ui.item.data);
                }
            });
        });

        function displaySelectedItem(item) {
            $("#searchResult").empty().append('Result: ' + item.name).append(' (Latitude: ' + item.point.coordinates[0] + ' Longitude: ' + item.point.coordinates[1] + ')');
            $("#lat").val(item.point.coordinates[0]);
            $("#long").val(item.point.coordinates[1]);

         
          }
    </script>


    <title>Admin Home | Crime Prediction</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
        <img style="width: 4rem; border-radius:25px" src="../assets/logo.PNG" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../index.php">User Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./admin_home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./admin_crime.php">Crime</a>
        </li>
       
        <li class="nav-item">
          <a class="nav-link" href="./admin_officer.php">Officer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"  href="./admin_add_account.php">Add New Admin</a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>

<div style="padding:1rem 3rem; text-align:right">

<div style="font-size: 1.5rem;" class="notification is-warning is-light">
  Welcome back, <strong><?php print_r($_SESSION['admin_fname'] . ' ' . $_SESSION['admin_lname']) ?>  </strong>
</div>

</div>


<section>
<div style="margin: 1rem 5rem" class="tile is-ancestor">
  <div  class="tile is-parent">
    <article style="background-color: #2D2727; border-radius:25px" class="tile is-child box">
      <p class="subtitle">Cases</p>
      <p style="color: white;" class="title"><?php 
      echo($no_of_crimes)
      ?></p>
    </article>
  </div>

  <div class="tile is-parent">
    <article style="background-color: #2D2727; border-radius:25px" class="tile is-child box">
      <p class="subtitle">Officers</p>
      

      <p style="color: white;" class="title"><?php 
      echo($no_of_officer)
      ?></p>
    </article>
  </div>

  <div class="tile is-parent">
    <article style="background-color: #2D2727; border-radius:25px" class="tile is-child box">
      <p class="subtitle">Victims</p>
      <p style="color: white;" class="title"><?php 
      echo($no_of_users)
      ?></p>
    </article>
  </div>
  <div class="tile is-parent">
    <article style="background-color: #2D2727; border-radius:25px;" class="tile is-child box">
      <p  class="subtitle">Locations</p>
      <p style="color: white;" class="title">
    <?php echo($loc_no)?>
    </p>
   
    </article>
  </div>
</div>

</section>




<section>


<div style="padding: 2rem;" class="tile is-ancestor">
  <div class="tile is-parent">
    <article class="tile is-child box">
      <p class="title">Victims</p>
  <div style="overflow:scroll; height:400px;" class="table-responsive">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Email</th>
      <th scope="col">Phone</th>
      <th scope="col">Action</th>
  
    </tr>
  </thead>
  <tbody>
    <?php 
    
    while($row = mysqli_fetch_assoc($results_users)){
    ?>



    <tr>
      <th scope="row">#</th>
      <td><?php echo($row['fname'])?></td>
      <td><?php echo($row['lname'])?></td>
      <td><?php echo($row['email'])?></td>
      <td><?php echo($row['phone'])?></td>

        <td>
          <form action="./admin_home.php" method="post">
            <button value="<?php echo($row['id'])?>" type="submit" name="edit_user" style="width:4rem; margin:1px" class="btn btn-primary btn-sm">Edit</button>
            </form>



    
      <form action="./admin_home.php" method="post"> 
         <button value="<?php echo($row['id'])?>" type="submit" name="delete_user" onclick="document.getElementById('id01').style.display='block'" style="width:4rem; margin:1px" class="btn btn-danger btn-sm">Delete</button>
        </form>
      </td>
  
    
     <?php 
     
        $user_del_id = $row['id'];

     ?>

    </tr>

 <?php 
} ?>


</table>

  </div>
  </tbody>

    
    </article>
    
  
  </div>
  <div class="tile is-parent is-">
    <article class="tile is-child box">
    <img src="../assets/5.jpg">
    
    </article>
  </div>
 
</div>


</section>
<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="">
    <div class="container">
      <h1>Deleting User</h1>
      <p>You have deleted a user</p>

      <div class="clearfix">
        
          <button type="submit" name="delete_user"  class="btn btn-primary">Okay</button>
      
         </div>
    </div>
  </form>
</div>


<section>

<div style="padding: 2rem;" class="tile is-ancestor">
  <div class="tile is-parent is-4">
  <article class="tile is-child box">
  <p class="title">Add new location</p>

  <form action="./admin_home.php" method="post">

  <div class="field">
        <label class="label">Location</label>
        <div class="control">
          <input required name="location" id="searchBox" class="input" type="text" placeholder="">
        </div>
        <input style="display: none;" name="lat" id="lat" type="text">
        <input style="display: none;" name="long" id ="long" type="text">
        
      </div>

      
        <div id="searchResult" class="ui-widget" style="margin-top: 1em;">
    </div>
    <button name="add_location" class="btn btn-primary">Add location</button>
    <br><br>
    <p class="title">Location latitude and longitude is dynamic and will be added automatically</p>
  </form>
    </article>
  </div>



  <div class="tile is-parent is-8">
    <article class="tile is-child box">
    

    <div id="id02" class="modal">
  <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="">
    <div class="container">
      <h1>Deleting Location</h1>
      <p>You have deleted a location</p>

      <div class="clearfix">
        
          <button type="submit" class="btn btn-primary">Okay</button>
      
         </div>
    </div>
  </form>
</div>



      <p class="title">Locations</p>
      <div style="overflow:scroll; height:400px;" class="table-responsive">
           <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">City & Country</th>
      <th scope="col">Latitude</th>
      <th scope="col">Longitude</th>
      <th scope="col">Action</th>
  
    </tr>
  </thead>
  <tbody>
    <?php 
    
    while($row = mysqli_fetch_assoc($location_results)){
    ?>
    <tr>
      <th scope="row">#</th>
      <td><?php echo($row['name'])?></td>
      <td><?php echo($row['latitude'])?></td>
      <td><?php echo($row['longitude'])?></td>
  
      <td>
      <form action="./admin_home.php" method="post">
        <button  value="<?php echo($row['location_id'])?>" type="submit" name="edit_location" type="submit" style="width:4rem; margin:1px" class="btn btn-primary btn-sm">Update</button>
     
     </form>  

     <form action="./admin_home.php" method="post">
        <button value="<?php echo($row['location_id'])?>" type="submit" name="delete_location" onclick="document.getElementById('id02').style.display='block'" style="width:4rem; margin:1px" class="btn btn-danger btn-sm">Delete</button>
     
     </form> 
       </td>
    
    </tr>
 <?php } ?>
  </tbody>
</table>

      </div>
   
    
   
    </article>
  </div>
 
</div>


</section>






<section style="background-color: blue"><footer class="footer">
  <div class="content has-text-centered">
    <p>
      <strong>Crime Predictor</strong> <br> 
      <span>
      <a href="https://www.facebook.com/profile.php?id=100093650438112"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
  <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
</svg> </a>
      
      </span>

      <span>
      <a href="https://twitter.com/crimePRED"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
  <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
</svg></a>
     
      </span>

      <span>
      <a href="https://www.linkedin.com/feed/"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
  <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
</svg></a>
      

      </span>
      <br>
      All rights reserved.
       </p>
  </div>
</footer>

<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly"
      defer
    ></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>