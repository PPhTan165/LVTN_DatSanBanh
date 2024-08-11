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
    <title>Admin</title>
</head>

<body>
    <?php require_once("../include/header_admin.php") ?>

    <div>
        <h2 class="text-2xl font-bold mt-5 text-center">Tạo sân banh</h2>

        <?php
            $admin = new Admin;
            $db = new DB;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name_pitch =$_POST['name_pitch'];
                $manager_post = $_POST['manager'];
                $type_post = $_POST['type'];
                $des = $_POST['descript'] ?? '';
                $exist = $admin->existPitch($name_pitch);
                
                if ($exist) {
                    echo '<script>alert("Tên sân đã tồn tại");
                    window.location.href="index.php?url=pitch";
                    </script>';
                    exit();
                } else {
    
    
                    $insert_pitch = "INSERT INTO pitch (name,deleted, description, manager_id, type_id) VALUES (:name_pitch,:deleted,:des,:manager_id,:type_id)";
                    $pitch_arr = array(
                        ":name_pitch" => $name_pitch,
                        ":deleted" => 0,
                        ":des" => $des,
                        ":manager_id" => $manager_post,
                        ":type_id" => $type_post
                    );
                    
                    $result_pitch = $db->insert($insert_pitch, $pitch_arr);
    
                    if ($result_pitch > 0) {
                        $id_pitch = $db->getInsertId();
                        for ($i = 1; $i < 19; $i++) {
    
                            $sql = "INSERT INTO pitch_detail (duration_id, pitch_id, price_id) values (:duration, :pitch, :price)";
    
                            if ($i < 5) {
                                if ($type_post == 1) {
                                    $price = 1;
                                } else if ($type_post == 2) {
                                    $price = 5;
                                } else {
                                    $price = 9;
                                }
                            } else if ($i < 10) {
                                if ($type_post == 1) {
                                    $price = 2;
                                } else if ($type_post == 2) {
                                    $price = 6;
                                } else {
                                    $price = 10;
                                }
                            } else if ($i < 13) {
                                if ($type_post == 1) {
                                    $price = 3;
                                } else if ($type_post == 2) {
                                    $price = 7;
                                } else {
                                    $price = 11;
                                }
                            } else if ($i >= 13) {
                                if ($type_post == 1) {
                                    $price = 4;
                                } else if ($type_post == 2) {
                                    $price = 8;
                                } else {
                                    $price = 12;
                                }
                            }
    
                            $arr = array(
                                ":duration" => $i,
                                ":pitch" => $id_pitch,
                                ":price" => $price
                            );
                            
                            $result_pd = $db->insert($sql, $arr);
                        }
                        if ($result_pd > 0) {
                            echo '<script>alert("Tạo sân thành công"); window.location.href = "index.php?url=pitch";</script>';
                        }
                    }
                }
            }
    
    
            $managers = $admin->getAllManager();
            $types = $admin->getType();
    
            echo '<form class="max-w-sm mx-auto" method="post" action="create_pitch.php">
            <div class="mb-5">
                <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required />
                </div>
                <div class="mb-5">
                
                <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
                <select id="manager" name="manager" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                ';
            foreach ($managers as $manager) {
                echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
            }
    
            echo '</select>
            </div>
    
            <div class="mb-5">
    
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
                <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                ';
            foreach ($types as $type) {
                echo '<option value="' . $type['id'] . '">' . $type['name'] . '</option>';
            }
            echo '
    
                </select>
            </div>
            <div class="mb-5">
                <label for="descript" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chú thích</label>
                <textarea id="message" rows="4" name="descript" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
            </div>
    
    
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
            </form>';
        ?>

    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>