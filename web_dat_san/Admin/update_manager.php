<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
$admin = new Admin;
$db = new DB;
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
        $id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['fname'];
            $phone = $_POST['phone'];
            if (!isValidVietnamPhoneNumber($phone)) {
                echo '<script>alert("Số điện thoại không hợp lệ)</script>';
            }


            $update_query = "UPDATE manager SET name=:name,phone=:phone WHERE user_id = :id";

            $params = array(
                ":name" => $name,
                ":phone" => $phone,
                ":id" => $id
            );

            $result = $db->update($update_query, $params);


            if ($result > 0) {
                echo "Cập nhật thành công";
            } else {
                echo "Không có thay đổi nào được thực hiện.";
            }
        }


        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $query = "SELECT * FROM manager WHERE user_id = :id";
            $params = array(":id" => $id);
            $result = $this->select($query, $params);
            if ($result > 0) {
                
                $manager = $result[0];


                echo '<form class="max-w-sm mx-auto" method="post" action="update_manager.php?id=' . $id . '">
                <div class="mb-5">
                    <label for="fname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên nhân viên</label>
                    <input type="text" id="fname" name="fname" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $manager['name'] . '" />
                    </div>
                  
       
                    <div class="mb-5">
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SDT</label>
                    <input type="tel" id="phone" name="phone" maxlength = "10" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $manager['phone'] . '" />
                    </div>

                <div class="flex justify-between">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cập nhật thông tin</button>
                <a href="index.php?url=manager" class="font-medium text-blue-600 underline">Quay lại</a>
                </div>
                </form>';
            }
        }
        ?>
        
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>