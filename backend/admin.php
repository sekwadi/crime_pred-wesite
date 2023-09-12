<?php 
$conn = new mysqli("localhost", "root", "","crime_p");
//include('./backend/db.php');
session_start();


$current_admin = [];
//login admin

if(isset($_POST['login_admin'])){
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
        $password = md5($password);


        $sql = "SELECT * FROM admin WHERE email = '$email' AND password = '$password' LIMIT 1";
        $results = mysqli_query($conn, $sql);


        print_r($results);

        if(mysqli_num_rows($results) < 1){
            header('location:../frontend/admin.php?msg=Invalid login details');
            exit;
        }else{
            //$_SESSION['cur_admin'] = $results;
            $current_admin = mysqli_fetch_assoc($results);

            
            $_SESSION['admin_fname'] = $current_admin['fname'];
            $_SESSION['admin_lname'] = $current_admin['lname'];
            $_SESSION['admin_email'] = $current_admin['email'];
            $_SESSION['admin_phone'] = $current_admin['phone'];


            header('location:../frontend/admin_home.php');
            exit;
        }
    }

    
}





?>