<?php

$server = "localhost";
$username = "lostandfound_user";
$password = "password";
$database = "lostandfound";

$conn = mysqli_connect($server,$username,$password,$database);

if(!$conn){
    die("<script>alert('connection Failed.')</script>");
}
// else{
//     echo "<script>alert('connection successfully.')</script>";
// }
?>