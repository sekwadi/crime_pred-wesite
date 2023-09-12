<?php
// Create connection
//include('./db.php');
session_start();
//$conn = new mysqli("localhost", "root", "","crime_p");
$conn = new mysqli("sql210.epizy.com", "epiz_34121291", "zYvP2EB3S099guV","epiz_34121291_crime_p");

$current_user = [];


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
        header('location:../frontend/add_account.php?msg=Invalid phone number');
        exit;
    }
    //hash password
    $password = md5($password);

    if($validated){
        $sql = "INSERT INTO victim ( id , fname, lname, email, phone, password )
                VALUES ('$id' , '$fname', '$lname', '$email', '$phone', '$password')";
        $results = mysqli_query($conn, $sql);
        header('location:../frontend/login.php?msg=User created successfully');
        exit;
    }else{
        header('location:../frontend/add_account.php?msg=Something wrong with the info provided');
        exit;
    }
 
}


if(isset($_POST['login_user'])){
    $email = $_POST['email'];
    $password = $_POST['password'];


    if(!isset($email)){
        header('location:../frontend/login.php?msg=Please enter email');
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
            header('location:../frontend/login.php?msg=Invalid login details');
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
        header('location:../frontend/admin.php?msg=Please enter email');
        exit;
    }else if(!isset($password)){
        header('location:../frontend/admin.php?msg=Please enter password');
        exit;
    }else {
        $sql = '';

        $sql = "SELECT * FROM admin WHERE email = '$email' AND password = '$password' LIMIT 1";
        $results = mysqli_query($conn, $sql);


        print_r($results);

        if(mysqli_num_rows($results) < 1){
            header('location:../frontend/admin.php?msg=Invalid login details');
            exit;
        }else{
            $_SESSION['cur_admin'] = $results;
            header('location:../frontend/admin_home.php?msg=Welcome back');
            exit;
        }
    }

    
}





if(isset($_POST['delete_user'])){

$cur_id = $_POST['delete_user'];

$sql = "DELETE FROM victim WHERE id = '$cur_id'";
$results = mysqli_query($conn, $sql);
header('location:../frontend/admin_home.php?msg=User Deleted!');
      
$cur_id = '';


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
    <title>Login | Crime Prediction</title>
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
      </ul>
    </div>
  </div>
</nav>



<p style="font-size: 3rem; text-align:center" class="fw-light">Login</p>



<section style="width:60%; margin:auto; padding: 5rem 0rem">

<div>
 <?php if (isset($_GET['msg'])) { ?>
      <div class="alert alert-primary" role="alert">
        <?php echo $_GET['msg']; ?>
  </div>
<?php } ?>
 </div>

<form action="login.php" method="POST">

  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input name="email" required type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
    </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input name="password" required type="password" class="form-control" id="exampleInputPassword1" >
  </div>
<br>
  <button name="login_user" style="width:100%;" type="submit" class="btn btn-primary">Login</button>
</form>

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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>