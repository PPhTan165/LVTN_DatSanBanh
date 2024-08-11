<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
$db = new DB;
$admin = new Admin;

$promotion  = $admin->getPromotionById($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $currentDate = date('Y-m-d');

    $date_exp = $_POST['date_exp'];
    $discount = $_POST['discount'];
    $max_get = $_POST['max_get'];
    $name = $_POST['name'];
    if($max_get < 1000){
        echo "<script>alert('Tối đa phải lớn hơn 1000')
            window.location.href='update_promotion.php?id=".$_GET['id']."'
        </script>";
        exit();
    }
    if($date_exp < $currentDate){
        echo "<script>alert('Hạn sử dụng phải lớn hơn ngày hiện tại')
            window.location.href='update_promotion.php?id=".$_GET['id']."'
        </script>";
        exit();
    }
    $exist = $admin->existPromotion($name);
    if($name == $promotion['name']){
        $exist = false;
        
        $query = "UPDATE promotion SET date_exp = :date_exp, discount = :discount, max_get = :max_get WHERE id = :id";
        $params = array(
            ":date_exp" => $date_exp,
            ":discount" => $discount,
            ":max_get" => $max_get,
            ":id" => $_GET['id']
        );
        $result = $db->update($query, $params);
        header("Location: index.php?url=promotion");
        
       

    }else if($exist){
        echo "<script>alert('Mã khuyến mãi đã tồn tại')</script>";
    }else{
        $query = "UPDATE promotion SET name = :name, date_exp = :date_exp, discount = :discount, max_get = :max_get WHERE id = :id";
        $params = array(
            ":name" => $name,
            ":date_exp" => $date_exp,
            ":discount" => $discount,
            ":max_get" => $max_get,
            ":id" => $_GET['id']
        );
        $result = $db->update($query, $params);
        header("Location: index.php?url=promotion");
        
    }
        

      
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css//output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>ADMIN</title>
</head>

<body>
    <?php require_once("../include/header_admin.php");
    ?>

    <h2 class="text-center font-bold text-2xl mb-5 mt-5">TẠO MÃ KHUYẾN MÃI</h2>

    <div class="flex justify-center items-center gap-8">

        <form action="update_promotion.php?id=<?= $_GET['id'] ?>" method="POST" class="w-96">
            <div class="mb-5">

                <label for="name" class="block mb-2">Mã khuyến mãi:</label>
                <input type="text" name="name" id="name" value="<?= $promotion['name'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <div class="mb-5">

                <label for="date_exp" class="block mb-2">Hạn sử dụng:</label>
                <input type="date" name="date_exp" id="date_exp" value="<?= $promotion['date_exp'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <div class="mb-5">

                <label for="discount" class="block mb-2">Discount:</label>
                <input type="number" name="discount" id="discount" value="<?= $promotion['discount'] ?>" min="0" max="100" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <div class="mb-5">

                <label for="max_get" class="block mb-2">Tối đa:</label>
                <input type="number" name="max_get" id="max_get" value="<?= $promotion['max_get'] ?>" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <input type="submit" value="Cập nhật mã khuyến mãi" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        </form>
    </div>
    <div>

    </div>
</body>

</html>