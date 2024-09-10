
<?php 
session_start();
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
// require_once("head.php");
if(isset($_SESSION['email'])){
    unset($_SESSION['email']);
    unset($_SESSION['role']);
    unset($_SESSION['name']);
    unset($_SESSION['cus_id']);
    unset($_SESSION['manager']);
    unset($_SESSION['code']);
    unset($_SESSION['team_id']);
    header("Location:index.php");

}
?>
