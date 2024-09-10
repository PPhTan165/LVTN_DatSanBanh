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
    <?php require_once("../include/header_admin.php") ?>
    
        <?php
            $admin = new Admin;
            $admin->filterPage();
        ?>

        
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

<script type="text/javascript">
    function confirmDelete() {
        return confirm("Bạn có chắc chắn muốn xóa không?");
    }
</script>
</html>