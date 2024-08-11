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
        <h2 class="text-center text-2xl font-bold mt-5 mb-5">Thông tin giải đấu</h2>
        <?php
            $id = $_GET['id'];
            $managers = $admin->getAllManager();
            $tournament = $admin->getTournamentById($id);
            $currentDate = date('Y-m-d');
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
                $name = $_POST['name'];
    
                $start_day = $_POST['start_day'];
                $start_day_format = DateTime::createFromFormat('Y-m-d', $start_day);
                $manager = $_POST['manager'];
    
                //Kiểm tra ngày bắt dầu không lớn hơn ngày kết thúc
    
                $query = "UPDATE tournament SET name=:name, start_day=:start_day, manager_id=:manager WHERE id = :id";
                $params = array(
                    ":name" => $name,
                    ":start_day" => $start_day,
                    ":manager" => $manager,
                    ":id" => $id
                );
    
                $result = $db->update($query, $params);
    
                if ($result) {
                    echo '<script>
                            alert("Tournament update successfully!");
                            window.location.href = "index.php?url=tournament";
                        </script>';
                } else {
                    echo '<script>alert("Failed to update tournament.");</script>';
                }
            }
    
    
            echo '<form class="max-w-sm mx-auto w-full mt-5 p-5" method="post" action="update_tournament.php?id=' . $id . '">
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên giải đấu</label>
                <input type="text" id="name" name="name" value="' . $tournament['name'] . '" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"/>
                </div>
    
                <div class="mb-5">
                    <label for="start_day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày bắt đầu</label>
                    <input type="date" id="start_day" name="start_day" min="' . $tournament['start_day'] . '" value="' . $tournament['start_day'] . '" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
                </div>

                <div class="mb-5">
                
                <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
                <select id="manager" name="manager" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                ';
    
            foreach ($managers as $manager) {
                $selected = ($manager['id'] == $tournament['manager_id']) ? 'selected' : '';
                echo '<option value="' . $manager['id'] . '" ' . $selected . '>' . $manager['name'] . '</option>';
            }
    
            echo '</select>
            </div>
                    <div class="flex justify-center w-full">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cập nhật</button>
                    </div>
                    </form>';
        ?>
        
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>