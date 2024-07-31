<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
$db = new DB;
if(!isset($_SESSION['role'])){
    header("Location: ../user/login");
    exit();
}else{
    $query = "SELECT * FROM user join manager on user.id = manager.user_id where email = :email ";
    $params= array(
        ":email"=>$_SESSION['user']
    );
    $result = $db->select($query,$params)[0];
     $_SESSION['manager']=$result['id'];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css//output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>TOTTENHAM FC</title>
</head>

<body>
    <?php require_once("../include/header_manager.php") ?>
    
        <?php
        
            $manager = new Manager;
            $manager->filterPage();
        ?>

        
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>