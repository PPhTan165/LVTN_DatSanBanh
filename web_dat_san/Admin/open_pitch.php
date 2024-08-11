<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = new DB;
    $id = $_GET['pitch'];

    $query = "UPDATE pitch SET deleted=0 where id = :id";
    $params = array(
        ":id" => $id
    );
    $open = $db->update($query, $params);
    if ($open) {
        header("Location: index.php?url=pitch ");
    } else {
        echo "<script>alert('Mở khoá sân thất bại')
        window.location.href='index.php?url=pitch'
        </script>";
    }
}
