<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if($_SERVER['REQUEST_METHOD']=='POST'){
    $db = new DB;
    $admin = new Admin;
    $date_exp = $_POST['date_exp'];
    $discount = $_POST['discount'];
    $max_get = $_POST['max_get'];
    $name = randomString(10);
    $exist = $admin->existPromotion($name);
    while($exist){
        $name = randomString(10);
        $exist = $admin->existPromotion($name);
    }
    $query = "INSERT INTO promotion(name, date_exp, discount, max_get) VALUES (:name, :date_exp, :discount, :max_get)";
    $params = array(
        ":name"=>$name, 
        ":date_exp"=>$date_exp, 
        ":discount"=>$discount, 
        ":max_get"=>$max_get
    );
    $result = $db->insert($query, $params);
    if($result){
        echo "<script>alert('Tạo mã khuyến mãi thành công')
        window.location.href='index.php?url=promotion'
        </script>";
    }else{
        echo "<script>alert('Tạo mã khuyến mãi thất bại')
        </script>";
        exit();
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
    <title>Document</title>
</head>

<body>
    <?php require_once("../include/header_admin.php") ?>

    <h2 class="text-center font-bold text-2xl mb-5 mt-5">TẠO MÃ KHUYẾN MÃI</h2>

    <div class="flex justify-center items-center gap-8">
        
        <form action="create_promotion.php" method="POST" class="w-96">
            <div class="mb-5">

                <label for="date_exp" class="block mb-2">Hạn sử dụng:</label>
                <input type="date" name="date_exp" id="date_exp" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <div class="mb-5">

                <label for="discount" class="block mb-2">Discount:</label>
                <input type="number" name="discount" id="discount" min="0" max="100" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <div class="mb-5">

                <label for="max_get" class="block mb-2">Tối đa:</label>
                <input type="number" name="max_get" id="max_get" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <input type="submit" value="Tạo mã khuyến mãi" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        </form>
    </div>
    <div>

    </div>
</body>

</html>