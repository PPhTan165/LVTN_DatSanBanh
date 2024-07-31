
<?php 
session_start();
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
// require_once("head.php");
if(isset($_SESSION['user'])){
    unset($_SESSION['user']);
    unset($_SESSION['role']);
    unset($_SESSION['name']);
    unset($_SESSION['cus_id']);
    header("Location:index.php");

}
?>
