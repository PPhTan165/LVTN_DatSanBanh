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
    $db = new DB;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $start_day = $_POST['start_day'];
        $start_day_format = DateTime::createFromFormat('Y-m-d', $start_day);

        $manager = $_POST['manager'];
        $type = $_POST['type'] ?? '';

        if ($name == '' || $start_day == '' || $manager == '' || $type == '') {
            echo '<script>alert("Vui lòng điền đầy đủ thông tin")
            window.location.href = "create_tournament.php";
            </script>';
            exit();
        }

        if ($admin->existTournament($name)) {
            echo '<script>alert("Tên giải đấu đã tồn tại")
            window.location.href = "create_tournament.php";
            </script>';
        }

        $query = "INSERT INTO tournament (name,deleted, start_day, manager_id,type_tour_id) VALUES (:name, :deleted, :start_day, :manager, :type_tour)";
        $params = array(
            ":name" => $name,
            ":deleted" => 0,
            ":start_day" => $start_day,
            ":manager" => $manager,
            ":type_tour" => $type
        );

        $result = $db->insert($query, $params);

        if ($result) {
            echo '<script>
                    alert("Tournament created successfully!");
                    window.location.href = "index.php?url=tournament";
                </script>';
        } else {
            echo '<script>alert("Failed to create tournament.");
            </script>';
        }
    }

    $managers = $admin->getAllManager(); 
    $type_tour = $admin->getTypeTour();
    $currentDate = date('Y-m-d');

    echo '<form class="max-w-sm mx-auto" method="post" action="create_tournament.php">
    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên giải đấu</label>
        <input type="text" id="name" name="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"/>
        </div>

        <div class="mb-5">
            <div>
                <label for="start_day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày bắt đầu</label>
                <input type="date" id="start_day" name="start_day" min="' . $currentDate . '" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            </div>
           
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
            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại giải đấu</label>
            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">

        ';
    foreach ($type_tour as $t) {
        echo '  <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="type_list" type="radio" name="type" value="' . $t['id'] . '" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" ' . ($t['id'] == $type_tour[0]['id'] ? 'checked' : '') . '>
                        <label for="type_list" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">' . $t['name'] . '</label>
                    </div>
                </li>';
    }
    echo '  </ul>
        </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tạo giải đấu</button>
    </form>';
   ?>
</body>

</html>