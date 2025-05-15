<?php
session_start();
include_once 'config.php';
$ref = @$_GET['q'];
$password = $_POST['password'];

$email = stripslashes($email);
$email = addslashes($email);
$password = stripslashes($password);
$password = addslashes($password);
$result = mysqli_query($con, "SELECT email FROM admin WHERE email = '$email' and password = '$password'") or die('Error');
$count = mysqli_num_rows($result);
if (isset($_SESSION['emmail'])) {
    $_SESSION["name"] = 'Admin';
    $_SESSION["key"] = 'nasir123';
    $_SESSION["email"] = $email;
    header("location:dash.php?q=1");
} else header("location:$ref?w=Warning : Access denied");
