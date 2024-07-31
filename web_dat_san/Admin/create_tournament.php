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
    <title>Create Tournament</title>
</head>

<body>
    <?php require_once("../include/header_admin.php") ?>
   <div class="text-2xl text-center font-bold mt-5 mb-5">
        <h2>Tạo giải đấu</h2>
   </div>
   <?php
    $admin = new Admin;
    $admin->createTournament();
   ?>
</body>

</html>