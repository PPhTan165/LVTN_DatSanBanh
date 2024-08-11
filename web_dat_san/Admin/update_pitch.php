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
            
        $id = $_GET['pitch'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name_pitch = $_POST['name_pitch'];
            $manager_post = $_POST['manager'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';

            $update_query = "UPDATE pitch SET name=:name, description=:descript, manager_id=:manager_id, type_id=:type_id WHERE id=:id";

            $params = array(
                ":name" => $name_pitch,
                ":descript" => $des,
                ":manager_id" => $manager_post,
                ":type_id" => $type_post,
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
            $query = "SELECT * FROM pitch WHERE id = :id";
            $params = array(":id" => $id);
            $result = $this->select($query, $params);
            if ($result > 0) {

                $pitch = $result[0];
                $managers = $admin->getAllManager();
                $types = $admin->getType();

                echo '<form class="max-w-sm mx-auto" method="post" action="update_pitch.php?pitch=' . $id . '">
                <div class="mb-5">
                    <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                    <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $pitch['name'] . '" />
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

                <div class="flex justify-between">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
                <a href="index.php?url=pitch" class="font-medium text-blue-600 underline">Quay lại</a>
                </div>
                </form>';
            }
        }
        ?>
        
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>