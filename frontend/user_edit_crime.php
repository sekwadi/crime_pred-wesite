<?php 

$conn = new mysqli("sql210.epizy.com", "epiz_34121291", "zYvP2EB3S099guV","epiz_34121291_crime_p");
//$conn = new mysqli("localhost", "root", "","crime_p");
session_start();

$id = $_SESSION['update_crime_id'];
$sql = "SELECT * from case_info WHERE crime_id = '$id'";
$crime_res = mysqli_query($conn, $sql);
$crime_res = mysqli_fetch_assoc($crime_res);

$crime_info_id = $crime_res['criminal_id_n'];
$sql = "SELECT * from criminal_info WHERE '$crime_info_id'";
$crime_info_res = mysqli_query($conn, $sql);
$crime_info_res = mysqli_fetch_assoc($crime_info_res);



if(isset($_POST['update_crime'])){
  // Get data

$officer_id_no = $_POST['officer_idd'];
$id_vic = $_POST['victim_id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];


//$crime_id = uniqid('crime00');
//echo($crime_id);

$crime_id = $_POST['crime_id'];
$location = $_POST['location'];
$crime_type = $_POST['crime_type'];
$case_name = $_POST['case_name'];
$case_description = $_POST['case_description'];
date_default_timezone_set('UTC+02:00');
$date = date('Y-m-d');
$time = date('h:i:s');
// Validate data



// validate phone number

   if(preg_match('/^[0-9]{10}+$/', $phone)) {
       $validated = true;
   } else {
       header('location:./user_edit_crime.php?msg=Invalid phone number');
       exit;
   }
  

   if($validated){
       

       //exit();
       $sql = "UPDATE criminal_info SET criminal_id_n = '$id_vic' , fname = '$fname', lname = '$lname', phone = '$phone' WHERE criminal_id_n = '$id_vic'";
              
               
       $sql2 = "UPDATE case_info SET officer_id_no = '$officer_id_no', crime_type_id = '$crime_type', case_description = '$case_description', criminal_id_n = '$id_vic'  WHERE crime_id = '$crime_id'";
  

       mysqli_query($conn, $sql);
       mysqli_query($conn, $sql2);
       header('location:./user_profile.php?msg=Crime info added successfully');
       
   
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
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script type="module" src="../assets/address.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.5.1.js" type="text/javascript"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/jquery-ui.js" type="text/javascript"></script>
    <link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
    
    <title>Edit Case | Crime Prediction</title>
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
          <a class="nav-link"  href="./admin_add_Account.php">Add New Admin</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link"  href="./frontend/add_account.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


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



<div style="padding:2rem">
 <?php if (isset($_GET['msg'])) { ?>
      <div class="alert alert-primary" role="alert">
        <?php echo $_GET['msg']; ?>
  </div>
<?php } ?>
 </div>


 <div class="tile is-ancestor">
  <div class="tile is-parent">
    <article style="padding:4rem" class="tile is-child box">

    <h1 class="title">Criminal Information</h1>
    <form action="./user_edit_crime.php" method="post">

    <div class="field">
        <label class="label">Victime ID</label>
        <div class="control">
          <input required readonly name="victim_id" value="<?php echo $crime_res['criminal_id_n']?>" class="input" type="text"  placeholder="">
        </div>
      </div>

      <div class="field">
        <label class="label">Firstname</label>
        <div class="control">
          <input required value="<?php echo $crime_info_res['fname']?>" name="fname" class="input" type="text" placeholder="">
        </div>
      </div>

      <div class="field">
        <label class="label">Lastname</label>
        <div class="control">
          <input required name="lname" value="<?php echo $crime_info_res['lname']?>" class="input" type="text" placeholder="">
        </div>
      </div>

      <div class="field">
        <label class="label">Phone</label>
        <div class="control">
          <input required value="<?php echo $crime_info_res['phone']?>" name="phone" class="input" type="tel" placeholder="">
        </div>
      </div>

 

    </article>
  </div>
  <div class="tile is-parent is-8">
    <article class="tile is-child box">
    <section style="padding:3rem">
    <h1 class="title">Case Information</h1>

    <input style="display:none" type="text" name='crime_id' value="<?php echo $crime_res['crime_id']?>">

    <div class="field">

        <label class="label">Officer</label>
       <div class="select">
          <select required style="width:100%" name="officer_idd" >
            <option value="<?php print_r($crime_res['officer_id_no'])?>">Select officer</option>
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
        <label class="tittle">Location cannot be updated</label>
</div>


       
      <div  class="field">
        <label class="label">Crime Type</label>
        <div  class="select">

  <select required style="width:100%" name="crime_type" >
  
  <option value="<?php print_r($crime_res['crime_type_id'])?>">Select crime type</option>
  <?php 
        $sql = " SELECT crime_type, crime_type_id FROM crime_type";
        $results = mysqli_query($conn, $sql);

        print_r($results);

        while($type = mysqli_fetch_assoc($results)){?>

    <option value="<?php print_r($type['crime_type_id'])?>"><?php print_r($type['crime_type']) ?></option>
<?php }?>

  </select>
</div>


      </div>
      
    
      <div class="field">
        <label class="label">Case Description</label>
        <div class="control">
          <input required value="<?php echo $crime_res['case_description']?>" name="case_description" class="input" type="text" placeholder="">
        </div>
      </div>

     
      <div class="field is-grouped">
        <div class="control">
          <button name="update_crime" type="submit" class="button is-link">Update</button>
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
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly"
      defer
    ></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>