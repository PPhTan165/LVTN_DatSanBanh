<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <link rel="stylesheet" href="../css//output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>Team</title>
</head>

<body>
    <?php require_once("../include/header.php") ?>
    <div class="main ms-5 me-5 mt-5">
        <h2 class="text-center text-2xl font-bold py-5">Thông tin đội bóng</h2>
        
        <?php
        $db = new DB;
        $query = "SELECT * FROM team where id = :id";
        $params = array(
            ":id"=>$_GET['team']
        );
        $result = $db->select($query,$params);
        try {
            if($result > 0){
                //echo '<h2 class="text-lg font-bold mb-5 text-center">Tên đội bóng: '.$result['name'].'</h2>';
                $team = new Team;
                $team->getTeamPersonal();
            }
            else{
                echo"Chưa có đội";
            }    //code...
        } 
        catch (\Throwable $th) {
            echo"sth happen";
        }
        
        ?>
        
    </div>


</body>

</html>