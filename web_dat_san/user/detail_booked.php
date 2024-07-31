<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();

// Kiểm tra nếu form được submit

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>Chi tiết sân</title>
</head>

<body>
    
    <?php require_once("../include/header.php");
    $currenDate = date('Y-m-d') ?>
    <div class="p-6 mb-5">
        <h2 class="text-center font-bold text-4xl mt-5">Chi tiết lịch đặt sân</h3>

        
        <?php
            $user = new User();
            $user->getInfoBooked($_GET['id']);
        ?>
    </div>

    </div>
</body>
<script src="../js/chooseTime.js"></script>

</html>