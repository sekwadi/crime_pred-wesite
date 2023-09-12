<?php 
include('../backend/user.php');

$conn = new mysqli("sql210.epizy.com", "epiz_34121291", "zYvP2EB3S099guV","epiz_34121291_crime_p");


if(empty($_SESSION['fname'])){
  header('location:./login.php');
}


$predicted_crimes[0] = 0;

if(isset($_POST['run_prediction'])){

$_SESSION['myLoc_name'] = $_COOKIE['myLoc_name_Cookie'];
$_SESSION['my_lat'] = $_COOKIE['myLat_Cookie'];
$_SESSION['my_lon'] = $_COOKIE['myLon_Cookie'];



//$_SESSION['mycityname'] = $_COOKIE['mycityname_cookie'];
$_SESSION['mycityname'] = $_POST['city'];

//echo($_SESSION['mycityname']);

header("location:./run_prediction.php");

$_COOKIE['myLat_Cookie'] = '';
$_COOKIE['myLon_Cookie'] = '';
$_COOKIE['mycityname_cookie'] = '';


exit;

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
    
    
    <title>Crime Geolocation | Crime Prediction</title>

    <!-- Reference to the Bing Maps SDK -->
    <script type='text/javascript'
            src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' 
            async defer></script>
</head>
    


<script>

    document.cookie = "myLat_Cookie=";
    document.cookie = "myLon_Cookie=";

</script>

<script type="text/javascript">
        $(document).ready(function () {
         
            $("#searchBox").autocomplete({
                source: function (request, response) {

                  document.cookie = "myLat_Cookie=";
                  document.cookie = "myLon_Cookie=";
         


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
                                    //console.log(data)
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
            $("#searchResult").empty().append('Results: ' + item.name).append(' (Latitude: ' + item.point.coordinates[0] + ' Longitude: ' + item.point.coordinates[1] + ')');
            $("#lat").val(item.point.coordinates[0]);
            $("#long").val(item.point.coordinates[1]);

            lat = item.point.coordinates[0];
            lon = item.point.coordinates[1];

            document.cookie = "myLoc_name_Cookie=" + encodeURIComponent(item.name);
            document.cookie = "myLat_Cookie=" + encodeURIComponent(lat);
            document.cookie = "myLon_Cookie=" + encodeURIComponent(lon);
         
          }
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


<section>

<div style="padding:1rem 3rem; text-align:right">
<div class="notification is-warning is-light">
  <button class="delete"></button>
  Hi, <strong><?php print_r($_SESSION['fname']) ?> </strong>
</div>

<div>
<p class="title" style="text-align:center;padding:1rem">Crime Geolocation</p>



<div style="text-align:center;margin:0rem 5px; " class="tile is-ancestor">
  <div  class="tile is-parent is-12">
    <article style="background-color: rgb(45, 39, 39);" class="tile is-child box ">
      <p style="color:whitesmoke" class="title">Current Location</p>
      <p  class="subtitle">Get crime prediction based on your current location, instantly.</p>
      <button onclick="getLocation()" class="button is-normal is-fullwidth is-info">Get current location</button>
    

      <p  type="text" id="demo"></p>
    </article>
    </div>

<br>



</div>
<form action="./get_pre.php" method="post">
 
        <h1 id="text_id" style="text-align:center"></h1>
<br>
    <input style="display:none" type="text" value="<?php echo($_COOKIE['myLat_Cookie']) ?>">
    <input style="display:none" type="text" value="<?php echo($_COOKIE['myLon_Cookie']) ?>">
    <input style="display:none" id="city_name" name="city" type="text" value="<?php echo($_COOKIE['mycityname_cookie']) ?>">
    
    <button type="submit" id="run_prediction_id" style="display:none;"  name="run_prediction"  style="width:100%;" class="btn btn-outline-primary">Run Prediction</button>


</form>
</section>
<form action="./get_pre.php" method="post">
 
        <h1 id="text_id" style="text-align:center"></h1>
<br>
    <input style="display:none" type="text" value="<?php echo($_COOKIE['myLat_Cookie']) ?>">
    <input style="display:none" type="text" value="<?php echo($_COOKIE['myLon_Cookie']) ?>">
    <input style="display:none" id="city_name" name="city" type="text" value="<?php echo($_COOKIE['mycityname_cookie']) ?>">
   
    <button type="submit" id="run_prediction_id" style="display:none; "  name="run_prediction"  style="width:100%;" class="btn btn-outline-primary">Run Prediction</button>


</form>


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


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfv2oXWVuKVx0IAokOr9UUIAslsD1QBhE&v=weekly"></script>   

<script>
var x = document.getElementById("city_name");

var map;

function getLocation() {
  
  document.cookie = "myLat_Cookie=";
  document.cookie = "myLon_Cookie=";
  document.cookie = "mycityname_cookie=";

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  document.cookie = "myLat_Cookie=";
  document.cookie = "myLon_Cookie=";
  document.cookie = "mycityname_cookie=";

  //x.innerHTML = "Latitude: " + position.coords.latitude + 
  //"<br>Longitude: " + position.coords.longitude;

  lat = position.coords.latitude;
  lon = position.coords.longitude;


  document.cookie = "myLat_Cookie=" + encodeURIComponent(lat);
  document.cookie = "myLon_Cookie=" + encodeURIComponent(lon);

 
  function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");
    
    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
}



        document.getElementById("text_id").style.display = "none";
        document.getElementById("run_prediction_id").style.display = "block";
        document.getElementById("run_prediction_id").style.width = "100%";



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
            //  sendData(cityName)
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
        x.value = cityName;
        document.cookie = "mycityname_cookie=" + encodeURIComponent(cityName);
}





//
</script>


<script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiqlpboDYjRf4U1QCjWAPrx0bx_6Bd1uNWD4mU1vFMwL4-1ifK0L3Ds3j3dwOhzd' async defer></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>



  </body>
</html>