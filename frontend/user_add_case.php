<?php 
include('../backend/user.php');

$conn = new mysqli("sql210.epizy.com", "epiz_34121291", "zYvP2EB3S099guV","epiz_34121291_crime_p");
//$conn = new mysqli("localhost", "root", "","crime_p");


if(empty($_SESSION['fname'])){
  header('location:./login.php');
}


$id = $_SESSION['id'];
$sql = "SELECT * FROM victim WHERE id = '$id'";
$results = mysqli_query($conn, $sql);
$results = mysqli_fetch_assoc($results);



if(isset($_POST['add_crime_report'])){
  // Get data

$officer_id_no = $_POST['officer_idd'];
$id_vic = $_POST['victim_id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];


$crime_id = uniqid('crime00');
//echo($crime_id);

$location = $_POST['location'];



$crime_type = $_POST['crime_type'];
echo($crime_type);

if($crime_type == 5){
    $crime_type = $_POST['crime_type2'];
}else{
    $crime_type = $_POST['crime_type'];
}

echo($crime_type);

//$case_name = $_POST['case_name'];
$case_description = $_POST['case_description'];
date_default_timezone_set('Africa/Johannesburg');
$date = date('Y-m-d');
$time = date('h:i:s');

// validate phone number
   if(preg_match('/^[0-9]{10}+$/', $phone)) {
       $validated = true;
   } else {
       header('location:./user_add_case.php?msg=Invalid phone number');
       exit;
   }
  

   if($validated){
       

       //exit();

       $sql = "INSERT INTO criminal_info ( criminal_id_n , fname, lname, phone )
               VALUES ('$id_vic' , '$fname', '$lname', '$phone');";
               
       $sql2 = "INSERT INTO case_info ( crime_id , officer_id_no , location, crime_type_id, case_description, date, time, criminal_id_n  )
               VALUES ('$crime_id' , '$officer_id_no' , '$location', '$crime_type', '$case_description', '$date', '$time', '$id_vic');";
  

       mysqli_query($conn, $sql);
       mysqli_query($conn, $sql2);
       header('location:./user_profile.php?msg=Crime info added successfully');
       
echo ('location is : ' . $location);
   
       $id = '';
       $officer_id = '';
       exit;
   }else{
       header('location:./user_profile.php?msg=Something wrong with the info provided');
       exit;
   }

}



?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Add Case | Crime Prediction</title>

    <!-- Reference to the Bing Maps SDK -->
    <script type='text/javascript'
            src='http://www.bing.com/api/maps/mapcontrol?key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' 
            async defer></script>
</head>
<body onload="getLocation()">
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
          <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./get_pre.php">Crime info</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"  href="./admin.php">Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"  href="./add_account.php">Register</a>
        </li>
        <?php 
        if(!empty($_SESSION['fname'])){
          ?>
           
          <li class="nav-item">
          <a class="nav-link"  href="./user_profile.php">Victim Profile</a>
        </li>
<?php
        }
        
        ?>
      </ul>
    </div>
  </div>
</nav>




<section>

<div style="padding:1rem 3rem; text-align:right">
<div>

<div style="text-align:center;margin:0rem 5px; " class="tile is-ancestor">
  <div  class="tile is-parent ">
   
          <article style="background-color: rgb(45, 39, 39);" class="tile is-child box ">
        <a href="./user_profile.php"><p style="color:whitesmoke" class="title">Your cases</p>
      <p class="subtitle"></p></a>
    </article>
    

  </div>
  <div class="tile is-parent">
    
        <article style="background-color: rgb(45, 39, 39);" class="tile is-child box">
    <a href="./user_add_case.php"><p style="color:whitesmoke" class="title">Add case</p>
      <p class="subtitle"></p></a>
  
    </article>
    
  </div>
  <div class="tile is-parent">
    
         <article style="background-color: rgb(45, 39, 39);" class="tile is-child box">
    <a href="./user_update.php"><p style="color:whitesmoke" class="title">Update profile</p>
      <p class="subtitle"></p></a>
    </article>
    
 
  </div>
</div>
</div>
</section>


<section style="width:60%; margin:auto; padding: 1rem 0rem">

<div>
 <?php if (isset($_GET['msg'])) { ?>
      <div class="alert alert-primary" role="alert">
        <?php echo $_GET['msg']; ?>
  </div>
<?php } ?>
 </div>

</section>






<div class="tile is-ancestor">
  <div class="tile is-parent">
    <article style="padding:4rem" class="tile is-child box">

    <h1 class="title">Victim Information</h1>
    <form action="./user_add_case.php" method="post">

    <div class="field">
        <label class="label">User ID</label>
        <div class="control">
          <input required name="victim_id" readonly value="<?php echo $results['id']?>" class="input" type="text"  placeholder="">
        </div>
      </div>

      <div class="field">
        <label class="label">Firstname</label>
        <div class="control">
          <input required name="fname" readonly value="<?php echo $results['fname']?>" class="input" type="text" placeholder="">
        </div>
      </div>

      <div class="field">
        <label class="label">Lastname</label>
        <div class="control">
          <input required name="lname" readonly value="<?php echo $results['lname']?>" class="input" type="text" placeholder="">
        </div>
      </div>

      <div class="field">
        <label class="label">Phone</label>
        <div class="control">
          <input required name="phone" readonly value="<?php echo $results['phone']?>" class="input" type="tel" placeholder="">
        </div>
      </div>

      


    </article>
  </div>
  <div class="tile is-parent is-8">
    <article class="tile is-child box">
    <section style="padding:3rem">
    <h1 class="title">Case information</h1>

    
    <div class="field">
      
        <label class="label">Officer</label>
       <div class="select">
          <select required style="width:100%" name="officer_idd" >
  <?php 
        $sql = " SELECT * FROM officer";
        $results = mysqli_query($conn, $sql);

        print_r($results);

        while($officer = mysqli_fetch_assoc($results)){?>

    <option value="<?php print_r($officer['officer_id'])?>"><?php print_r($officer['fname'] . ' ' . $officer['lname']) ?></option>
<?php }?>

  </select>
       </div>
      </div>     
  
      <div class="field">
        <label class="label">Location</label>

        <div class="control">
          <button style="width:100%; display:none" onclick="getLocation()" class="button is-link">Get location</button>
        </div>
<br>
              <div class="control">
          <input readonly style="width:100%" name="location"  id="demo" class="input" type="text" placeholder="Auto generated">
        </div>
</div>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfv2oXWVuKVx0IAokOr9UUIAslsD1QBhE&v=weekly"></script>    

 <script>
let x = document.getElementById('demo');

function getLocation() {
console.log('getting location...');
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    console.log("Geolocation is not supported by this browser.");
  }
}

function showPosition(position) {
  const latitude = position.coords.latitude;
  const longitude = position.coords.longitude;

  const geocoder = new google.maps.Geocoder();
  const latLng = new google.maps.LatLng(latitude, longitude);

  geocoder.geocode({ 'latLng': latLng }, function (results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        for (let i = 0; i < results[0].address_components.length; i++) {
          const addressType = results[0].address_components[i].types[0];
          if (addressType === "locality") {
            const cityName = results[0].address_components[i].long_name;
            console.log("City Name: " + cityName);
            x.innerHTML = cityName;
              x.setAttribute('value', cityName);
            return;
          }
        }
      } else {
        console.log("No results found.");
      }
    } else {
      console.log("Geocoder failed due to: " + status);
    }
  });
}

</script>


      <div  class="field">
        <label class="label">Crime Type</label>
        <div  class="select">

  <select onchange="check_crime_type()" id="sel_crime_type" style="width:100%" name="crime_type" >
  <?php 
        $sql = " SELECT crime_type, crime_type_id FROM crime_type";
        $results = mysqli_query($conn, $sql);

        print_r($results);

        while($type = mysqli_fetch_assoc($results)){?>

    <option value="<?php print_r($type['crime_type_id'])?>"> <?php print_r($type['crime_type']) ?></option>
<?php }?>
  <br>
  </select>

       <div class="control">
          <input id="my_crime_t" onchange="check_crime_type()" style="width:100%; display:none" name="crime_type2"  id="demo" class="input" type="text" placeholder="Specify crime type">
        </div>

</div>
 
      </div>

      <script>
      function check_crime_type(){
            let sel_crime_type = document.getElementById('sel_crime_type');
            let my_crime_t = document.getElementById('my_crime_t');
           

            if(sel_crime_type.value != 5){
                console.log(sel_crime_type.value)
            }else {
                my_crime_t.style.display = 'block';
                sel_crime_type.style.display = 'none';
              
            }
            
            console.log(my_crime_t.value);
      }
     
      </script>


      <div class="field">
        <label class="label">Case Description</label>
        <div class="control">
          <input required name="case_description" class="input" type="text" placeholder="">
        </div>
      </div>



      <div class="field is-grouped">
        <div class="control">
          <button name="add_crime_report" type="submit" class="button is-link">Submit</button>
        </div>
        <div class="control">
          <button class="button is-link is-light"><a href="./user_profile.php">Cancel</a> </button>
        </div>
      </div>
      </form>

      
</section>
    </article>
  </div>
</div>





















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



</section>




<script type='text/javascript'>
  
    </script>
<script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' async defer></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>