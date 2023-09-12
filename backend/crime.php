<?php
// Create connection
//include('./backend/db.php');
session_start();
$conn = new mysqli("localhost", "root", "","crime_p");


//get all crime info

$id_vic = '';
$officer_id = '';



$sql = '';
$sql = "SELECT * FROM crime_report";
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
    header('location:../frontend/admin_add_crime.php?msg=Invalid ID number length');
    exit;
}else{
    $id_year = substr($id_vic, 0, 2);
    $current_year = substr(date("Y"), 2, 2);

  //  echo($id_year);
  
    //validate id year
    if($id_year >= $current_year){
        header('location:../frontend/admin_add_crime.php?msg=Invalid ID number year');
        exit;
    }
    $id_month = substr($id_vic, 2, 2);
    
    //validate id month
   if($id_month < 1 || $id_month > 12 ){
        header('location:./frontend/admin_add_crime.php?msg=Invalid ID number month');
        exit;
    }
    $id_day = substr($id_vic, 4, 2);
    //echo($id_day);

    //validate id day
    if($id_day < 1 || $id_day > 31 ){
        header('location:./frontend/admin_add_crime.php?msg=Invalid ID number day');
        exit;
    }  
}

// validate phone number

    if(preg_match('/^[0-9]{10}+$/', $phone)) {
        $validated = true;
    } else {
        header('location:./frontend/admin_add_crime.php?msg=Invalid phone number');
        exit;
    }
   

    if($validated){
        

        //exit();

        $sql = "INSERT INTO criminal_info ( criminal_id_n , fname, lname, phone, dob )
                VALUES ('$id_vic' , '$fname', '$lname', '$phone', '$dob');";
                
        $sql2 = "INSERT INTO crime_report ( crime_id , officer_id_no , location_id, crime_type_id, case_name, case_description, date, time, criminal_id_n  )
                VALUES ('$crime_id' , '$officer_id_no' , '$location', '$crime_type', '$case_name', '$case_description', '$date', '$time', '$id_vic');";
   
 
        mysqli_query($conn, $sql);
        mysqli_query($conn, $sql2);
        header('location:../frontend/admin_crime.php?msg=Crime info added successfully');
        
echo ('location is : ' . $location);
    
        $id = '';
        $officer_id = '';
        exit;
    }else{
        header('location:../frontend/admin_add_crime.php?msg=Something wrong with the info provided');
        exit;
    }

}




if(isset($_POST['delete_crime'])){
    $crime_id = $_POST['delete_crime'];

    $sql = '';

    $sql = "DELETE FROM crime_report WHERE crime_id = '$crime_id' ";
    $results = mysqli_query($conn, $sql);

    header('location:../frontend/admin_crime.php?msg=Crime info deleted successfully');
    
}




?>