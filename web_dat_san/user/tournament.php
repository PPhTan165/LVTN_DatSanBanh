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
    <div class="main p-8">
        <h2 class="text-3xl font-bold text-center">Giải đấu</h2>
        
        <div class="grid place-items-center place-content-center gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 p-5">

            <?php

            $tournament = new Tournament;
            $tournament->getAllTournament();
            ?>
        </div>




    </div>
    <?php require_once("../include/footer.php") ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>