<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

function checkRole($role){
    if($_SESSION['role'] !== $role){
        header("Location: ../auth/login.php");
        exit();
    }
}
?>
