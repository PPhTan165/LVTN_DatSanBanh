<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
$admin = new Admin;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_id = $_POST['type'] ?? '';
    $prices = $_POST['prices'] ?? [];
    foreach ($prices as $id => $price) {
        $update_query = "UPDATE price SET price_per_hour = :price WHERE id = :id AND type_id = :type_id";
        $update_arr = array(
            ":price" => $price,
            ":id" => $id,
            ":type_id" => $type_id
        );
        $result = $admin->update($update_query, $update_arr);
        header('Location: index.php?url=price&type='.$type_id);
    }
    
}
