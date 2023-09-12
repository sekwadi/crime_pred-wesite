<?php 
include('../backend/user.php');

$conn = new mysqli("sql210.epizy.com", "epiz_34121291", "zYvP2EB3S099guV","epiz_34121291_crime_p");

if(empty($_SESSION['fname'])){
  header('location:./login.php');
}



$sql = "SELECT * FROM location";
$location_report_info =  mysqli_query($conn, $sql);


$location_array = array();
$crime_no_array = array();
$type_array = array();
$type_no_array = array();

$new_lat = $_SESSION['my_lat'];
$new_lon = $_SESSION['my_lon'];
$city = $_SESSION['myLoc_name'];


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
  $sql = "SELECT * FROM case_info WHERE crime_type_id = '$id'";
  $type_no_report_info =  mysqli_query($conn, $sql);

  array_push($type_no_array, mysqli_num_rows($type_no_report_info));

}

// Previous data for training
$trainingData = [
    [10, [40.7128, -74.0060]],
    [5, [34.0522, -118.2437]],
    [8, [51.5074, -0.1278]],


    // Add more training data...
];

// Specific location for prediction
$location = [$new_lat, $new_lon];

// Train the linear regression model
$model = trainLinearRegression($trainingData);

// Make prediction
$prediction = predictLinearRegression($model, $location);
$prediction = $prediction - 1.47; 

/**
 * Train a linear regression model.
 *
 * @param array $trainingData Training data with crime counts and corresponding locations
 * @return array Trained model parameters
 */
function trainLinearRegression($trainingData) {
    $X = $y = [];

    foreach ($trainingData as [$count, $coords]) {
        $X[] = array_merge([1], $coords);
        $y[] = $count;
    }

    $XtXInv = array_intersect_key(
        $XtX = array_map(null, ...$X),
        array_flip([0, 1, 2]) // Adjust the indices based on the number of features
    );

    $Xty = array_map(
        fn($a, $b) => $a * $b,
        $Xt = array_map('array_sum', $XtX),
        $y
    );

    return array_map(
        fn($a, $b) => $a / $b,
        array_map('array_sum', $XtXInv),
        $Xty
    );
}

/**
 * Predict using a trained linear regression model.
 *
 * @param array $model Trained model parameters
 * @param array $location Location for prediction [latitude, longitude]
 * @return float Predicted value
 */
function predictLinearRegression($model, $location) {
    return array_sum(
        array_map(
            fn($a, $b) => $a * $b,
            array_merge([1], $location),
            $model
        )
    );
}

//$location_array = json_encode($location_array);
//$crime_no_araay = json_encode($crime_no_araay);
//print_r($location_array);
//print_r($crime_no_araay);

/*

*/



$predicted_crimes[0] = 0;

$results = '';
$sql = "SELECT location FROM case_info";
$results = mysqli_query($conn, $sql);


$_COOKIE['myLat_Cookie'] = '';
$_COOKIE['myLon_Cookie'] = '';



$my_city_name = $_SESSION['mycityname'];
$t_array = array();
$count_array = array();



$sql = '';
$sql = "SELECT crime_type_id from case_info WHERE location = '$my_city_name'"; 
$resultss = mysqli_query($conn, $sql);

//print_r($resultss);

if(mysqli_num_rows($resultss) >= 1){
    
while($type = mysqli_fetch_array($resultss)){
array_push($t_array, $type['crime_type_id']);

}

array_push($count_array, array_count_values($t_array));
//print_r($count_array);
$high = 0;
$val;
$poss_crime_type;

for($i = 0; $i < count($count_array); $i ++){
    if($count_array[$i] >= $high){
        $val = $count_array[$i];
    }
}



for($i = 0; $i < count($t_array); $i ++){
    if($count_array[$i] == max($val)){
        $poss_crime_type = $count_array[$i];
    }
}
$poss_crime_type;

//$possible_crime_type = $type_array[rand(0,3)];


$ind = array_search(max($val),$val);

$possible_crime_type = $type_array[$ind - 1];

}else{
    $possible_crime_type = 'Taking longer than expected...';
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
    
    <script type="module" src="../assets/address.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.5.1.js" type="text/javascript"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.js" type="text/javascript"></script>
    <link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>

    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <title>Crime Geolocation | Crime Prediction</title>

    <!-- Reference to the Bing Maps SDK -->
    <script type='text/javascript'
            src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' 
            async defer></script>
</head>

<script type="text/javascript">
$( document ).ready(function() {
  //showPosition();
  showChart();
});
</script>




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
          <a class="nav-link"  href="./user_profile.php">User Profile</a>
        </li>
<?php
        }
        
        ?>
      </ul>
    </div>
  </div>
</nav>


<sectionn id="pdf">
<div >

<p class="title" style="text-align:center;padding:1rem">Crime Geolocation</p>

<input style="display:none" type="text" name="" value="<?php echo($_SESSION['my_lat'])?>" id="my_lat">
<input style="display:none" type="text" name="" value="<?php echo($_SESSION['my_lon'])?>" id="my_lon">

</div>

<section>

</section>

</section>

<div style="padding:5px; font-size:1rem; margin:5px; text-align:center; background-color:#400E32;color:white; margin:auto; width:50%; border-radius: 25px;">
<p id="city_name">City Name Loading ...</p>
</div>
<br>

<h1 style="text-align:center">possible crimes</h1>
<br>
<h1 id="show_pre" style="font-size:1.5rem; text-align:center; background-color:#FFEBEB; margin:auto; width:30%; border-radius: 25px;"><?php 
 if(substr($prediction,0,1) === "-"){
    ?>
<h1 id="show_pre" style="font-size:4rem; text-align:center; background-color:#FFEBEB; margin:auto; width:50%; border-radius: 25px;"><?php print_R (substr($prediction,1,4));?></h1>
<?php 
  }else{
    ?>
<h1 id="show_pre" style="font-size:4rem; text-align:center; background-color:#FFEBEB; margin:auto; width:50%; border-radius: 25px;"><?php print_R (substr($prediction,0,4));?></h1><br>
<?php 
  }
?>


<br>
<p style="font-size:1rem; text-align:center; background-color:#FFEBEB; margin:auto; width:50%; border-radius: 25px;">
<?php echo $possible_crime_type?>

</p>

</h1>
<h1 style="text-align:center">crime type mostly likely to happen now</h1>



<section style="padding:1rem 1rem">

<section>

<div style="padding:0rem 2rem;" class="tile is-ancestor">
  <div class="tile is-parent is-4">
  <article class="tile is-child box">
    <p class="title">My map</p>
      <div id="map" style=" width:100%;height:400px;"></div>

    </article>
  </div>


</sectionnn>

  <div class="tile is-parent is-4">
    <article class="tile is-child box">
    <p class="title">Crime and chances history</p>
    <div>
  <canvas id="myChart"></canvas>
</div>
    </article>
  </div>
 
 <div class="tile is-parent is-4">
    <article class="tile is-child box">
    <p class="title">Other crime news</p>
    <div>
 <iframe style="width:100%; height:90%" src="https://www.iol.co.za/news/crime-and-courts" title="Latest News"></iframe> 


</div>
</div>


</div>
</section>
</section>




<script>
var doc = new jsPDF();

 function saveDiv(divId, title) {
 doc.fromHTML(`<html><head><title>${title}</title></head><body>` + document.getElementById(divId).innerHTML + `</body></html>`);
 doc.save('div.pdf');
}

function printDiv(divId,
  title) {

  let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

  mywindow.document.write(`<html><head><title>${title}</title>`);
  mywindow.document.write('</head><body >');
  mywindow.document.write(document.getElementById(divId).innerHTML);
  mywindow.document.write('</body></html>');

  mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/

  mywindow.print();
  mywindow.close();

  return true;
}
</script>


<section style="background-color: blue"><footer class="footer">
  <div class="content has-text-centered">
  <div style="margin:auto">

<button onclick="printDiv('pdf','Crime Prediction')" type="button" class="btn btn-warning">Generate Prediction Report</button>

</div>
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
var map;




async function initMap() {
  

let lat = parseFloat(document.getElementById("my_lat").value)
let lon = parseFloat(document.getElementById("my_lon").value)
// Initialize and add the map
let map;

console.log(lat)
console.log(lon)

  const position = { lat: lat, lng: lon };

const citymap = {
  Crime_Range: {
    center: position,
    population: 2714856,
  },
};

for (const city in citymap) {
    // Add the circle for this city to the map.
    const cityCircle = new google.maps.Circle({
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
      map,
      center: citymap[city].center,
      radius: Math.sqrt(citymap[city].population) * 100,
    });
  }


const { Map } = await google.maps.importLibrary("maps");
const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");


  map = new Map(document.getElementById("map"), {
    zoom: 18,
    center: position,
    mapId: "CRIME_MAP_ID",
  });

  // The marker, positioned at Uluru
  const marker = new AdvancedMarkerElement({
    map: map,
    position: position,
    title: "Searched position",
  });





}


initMap();

let lat = '';
let lon = '';



function showChart(){
  
  var type_array = <?php echo json_encode($type_array); ?>;
  var type_no_array = <?php echo json_encode($type_no_array); ?>;
var arr = [];
  //convert into %
  let total = 0;
  for(let i = 0; i < type_no_array.length; i ++){
      total = type_no_array[i] + total
  }

  for(let i = 0; i < type_no_array.length; i ++){
    let type = (type_no_array[i] / total) * 100;

    arr.push(type)
  }

  console.log(total)

 
const ctx = document.getElementById('myChart');


new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: type_array,
    datasets: [{
      label: 'No. of crimes',
      data: arr,
      backgroundColor: [
      'rgb(255, 99, 132)',
      'rgb(54, 162, 235)',
      'rgb(25, 205, 186)',
      'rgb(255, 205, 86)',
         'rgb(25, 105, 86)'
    ],
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


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfv2oXWVuKVx0IAokOr9UUIAslsD1QBhE&v=weekly"></script>    



 <script>

let x = document.getElementById('city_name');

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

  createCookie("city_name", cityName , "1");
}



// Creating a cookie after the document is ready
$(document).ready(function () {
    //createCookie("city_name",cityName , "1");
});
   
// Function to create the cookie
function createCookie(name, value, days) {
    var expires;
      
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
      
    document.cookie = escape(name) + "=" + 
        escape(value) + expires + "; path=/";
}



</script>


<?php


?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' defer></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfv2oXWVuKVx0IAokOr9UUIAslsD1QBhE&callback=initMap&v=weekly"
      defer
    ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>

    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyCfv2oXWVuKVx0IAokOr9UUIAslsD1QBhE", v: "beta"});</script>




  </body>
</html>