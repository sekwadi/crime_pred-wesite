<?php
// Create connection
//include('./backend/db.php');
$conn = new mysqli("localhost", "root", "","crime_p");
session_start();

//get all crime info

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
        header('location:../frontend/admin_add_officer.php?msg=Invalid ID number length');
        exit;
    }else{
    $id_year = substr($id, 0, 2);
    $current_year = substr(date("Y"), 2, 2);

    echo($id_year);
  
    //validate id year
    if($id_year >= $current_year){
        header('location:../frontend/admin_add_officer.php?msg=Invalid ID number year');
        exit;
    }
    $id_month = substr($id, 2, 2);
    
    //validate id month
   if($id_month < 1 || $id_month > 12 ){
        header('location:../frontend/admin_add_officer.php?msg=Invalid ID number month');
        exit;
    }
    $id_day = substr($id, 4, 2);
    echo($id_day);

    //validate id day
    if($id_day < 1 || $id_day > 31 ){
        header('location:../frontend/admin_add_officer.php?msg=Invalid ID number day');
        exit;
    }  
}

// validate phone number

    if(preg_match('/^[0-9]{10}+$/', $phone)) {
        $validated = true;
    } else {
        header('location:../frontend/admin_add_officer.php?msg=Invalid phone number');
        exit;
    }
   

    if($validated){
        $sql = "INSERT INTO officer (officer_id , fname, lname, email,  phone)
                VALUES ('$id' , '$fname', '$lname', '$email', '$phone')";
        $results = mysqli_query($conn, $sql);
      
        header('location:../frontend/admin_add_officer.php?msg=Officer info added successfully');
        
        exit;
    }else{
        header('location:../frontend/admin_add_officer.php?msg=Something wrong with the info provided');
        exit;
    }
 

}




?>