<?php

// Create connection
//include('./db.php');
session_start();
$conn = new mysqli("localhost", "root", "","crime_p");

$current_user = [];


$sql = '';
$sql = 'SELECT * from users';

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
        $sql = "INSERT INTO users ( id , fname, lname, email, phone, password )
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

        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";
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

            header('location:../frontend/get_pre.php?msg=Welcome back');
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

$sql = "DELETE FROM users WHERE id = '$cur_id'";
$results = mysqli_query($conn, $sql);
header('location:../frontend/admin_home.php?msg=User Deleted!');
      
$cur_id = '';


}



?>  