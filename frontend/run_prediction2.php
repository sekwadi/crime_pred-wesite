<?php 
include('../backend/user.php');

if(empty($_SESSION['fname'])){
  header('location:./login.php');
}



$sql = "SELECT * FROM location";
$location_report_info =  mysqli_query($conn, $sql);


$location_array = array();
$crime_no_array = array();
$type_array = array();
$type_no_array = array();

while($location = mysqli_fetch_array($location_report_info)){
  //print_r($location);
  array_push($location_array, array($location['latitude'], $location['longitude']));

  $id = $location['location_id'];
  $sql = "SELECT * FROM crime_report WHERE location_id = '$id'";
  $crime_report_info =  mysqli_query($conn, $sql);

  array_push($crime_no_array, mysqli_num_rows($crime_report_info));

}

$sql = "SELECT * FROM crime_type";
$type_report_info =  mysqli_query($conn, $sql);

while($type = mysqli_fetch_array($type_report_info)){
  //print_r($location);
  array_push($type_array, $type['crime_type']);

  $id = $type['crime_type_id'];
  $sql = "SELECT * FROM crime_report WHERE crime_type_id = '$id'";
  $type_no_report_info =  mysqli_query($conn, $sql);

  array_push($type_no_array, mysqli_num_rows($type_no_report_info));

}

//$location_array = json_encode($location_array);
//$crime_no_araay = json_encode($crime_no_araay);
//print_r($location_array);
//print_r($crime_no_araay);

/*

*/

print_r($type_array);

$possible_crime_type = $type_array[rand(0,4)];

$predicted_crimes[0] = 0;


$new_lat = $_SESSION['my_lat'];
$new_lon = $_SESSION['my_lon'];

//echo($new_lon);

echo '<script>
     
    </script>';


$new_data = [
  $lat = $new_lat,
  $lon = $new_lon,

  $loc = $location_array,
  $c_no = $crime_no_array,
];

$new_data = json_encode($new_data);

  $data = "hello world";
  $command = 'python python.py ' . escapeshellarg($new_data);
  $output = [];
  $returnValue = 0;

  exec($command, $output, $returnValue);

  if ($returnValue === 0) {
    $predicted_crimes = $output;
 
} else {
    echo "Failed to execute the Python script. Error code: $returnValue";
}


print_r($predicted_crimes);

//print_r($output);


$_COOKIE['myLat_Cookie'] = '';
$_COOKIE['myLon_Cookie'] = '';



?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    
    <script type="module" src="../assets/address.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.5.1.js" type="text/javascript"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.js" type="text/javascript"></script>
    <link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
    
    
    <title>Crime Geolocation | Crime Prediction</title>

    <!-- Reference to the Bing Maps SDK -->
    <script type='text/javascript'
            src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' 
            async defer></script>
</head>

<script type="text/javascript">
$( document ).ready(function() {
  showPosition();
  showChart();
});
</script>




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
          <a class="nav-link"  href="./user_profile.php">User Profile</a>
        </li>
<?php
        }
        
        ?>
      </ul>
    </div>
  </div>
</nav>




<section>


<p class="title" style="text-align:center;padding:1rem">Crime Geolocation</p>

<input style="display:none" type="text" name="" value="<?php echo($_SESSION['my_lat'])?>" id="my_lat">
<input style="display:none" type="text" name="" value="<?php echo($_SESSION['my_lon'])?>" id="my_lon">

</div>

<section>

</section>

</section>

<div style="padding:5px; font-size:1rem; margin:5px; text-align:center; background-color:#400E32;color:white; margin:auto; width:50%; border-radius: 25px;">
<?php print_r ($_SESSION['myLoc_name']);?>
</div>
<br>

<?php 
  if(substr($predicted_crimes[0],2,1) === "-"){
    ?>
<h1 id="show_pre" style="font-size:4rem; text-align:center; background-color:#FFEBEB; margin:auto; width:50%; border-radius: 25px;"><?php print_R (substr($predicted_crimes[0],1,4));?></h1>
<?php 
  }else{
    ?>
<h1 id="show_pre" style="font-size:4rem; text-align:center; background-color:#FFEBEB; margin:auto; width:50%; border-radius: 25px;"><?php print_R (substr($predicted_crimes[0],1,4));?></h1>
<?php 
  }
?>
<h1 style="text-align:center">possible crimes</h1>
<br>
<h1 id="show_pre" style="font-size:1.5rem; text-align:center; background-color:#FFEBEB; margin:auto; width:30%; border-radius: 25px;"><?php print_r($possible_crime_type);?></h1>
<h1 style="text-align:center">crime type mostly likely to happen now</h1>



<section style="padding:1rem 1rem">

<section>

<div style="padding:0rem 2rem;" class="tile is-ancestor">
  <div class="tile is-parent is-8">
  <article class="tile is-child box">
    <p class="title">My Map</p>
      <div id="myMap2" style="position:relative;width:100%px;height:400px;"></div>

    </article>
  </div>



  <div class="tile is-parent is-4">
    <article class="tile is-child box">
    <p class="title">Crime types and chances</p>
    <div>
  <canvas id="myChart"></canvas>
</div>
    </article>
  </div>
 
</div>


</section>


    

</section>
<script type='text/javascript'>
    function GetMap()
    {
        var map = new Microsoft.Maps.Map('#myMap');
        credentials: 'AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd';
        center: new Microsoft.Maps.Location(0, -0);
        mapTypeId: Microsoft.Maps.MapTypeId.aerial;
        zoom: 10.
    }
    </script>






<section style="background-color: blue">
<footer class="footer">
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



<script>
var x = document.getElementById("demo");
var map;

function getLocation() {

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {

let lat = document.getElementById("my_lat").value
let lon = document.getElementById("my_lon").value

  map = new Microsoft.Maps.Map('#myMap2', {});

        //Load the spatial math module
        Microsoft.Maps.loadModule("Microsoft.Maps.SpatialMath", function () {

           
            //Request the user's location
            navigator.geolocation.getCurrentPosition(function (position) {
                var loc = new Microsoft.Maps.Location(lat, lon);

                //Create an accuracy circle
                console.log(Microsoft.Maps.SpatialMath);

                var path = Microsoft.Maps.SpatialMath.getRegularPolygon(loc, position.coords.accuracy, 36,  Microsoft.Maps.SpatialMath.Meters);
                var poly = new Microsoft.Maps.Polygon(path);
                map.entities.push(poly);

                //Add a pushpin at the user's location.
                var pin = new Microsoft.Maps.Pushpin(loc);
                map.entities.push(pin);

                //Center the map on the user's location.
                map.setView({ center: loc, zoom: 18 });
            });
        });

}


function showChart(){
  
  var type_array = <?php echo json_encode($type_array); ?>;
  var type_no_array = <?php echo json_encode($type_no_array); ?>;

  //convert into %

  

const ctx = document.getElementById('myChart');




new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: type_array,
    datasets: [{
      label: 'No. of crimes',
      data: type_no_array,
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
}

</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' defer></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly"
      defer
    ></script>


  </body>
</html>