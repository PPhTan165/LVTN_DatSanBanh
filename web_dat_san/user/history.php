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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css//output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>TOTTENHAM FC</title>
</head>

<body>
    <?php require_once("../include/header.php") ?>
    <div class="main">
        <h2 class="text-3xl font-bold text-center mt-5 mb-5 ">Lịch sử đặt sân</h2>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg h-full mb-10 p-8">
            
        <?php
        
            $user = new User;
            $user->historyBooked();
            
        ?>
            
            
        </div>
    </div>
    <?php require_once("../include/footer.php") ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>