<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = new DB;
    $id = $_GET['id'];

    $query = "UPDATE tournament SET deleted=0 where id = :id";
    $params = array(
        ":id" => $id
    );
    $open = $db->update($query, $params);
    if ($open) {
        header("Location: index.php?url=tournament ");
    } else {
        echo "<script>alert('Mở khoá giải thất bại')
        window.location.href='index.php?url=pitch'
        </script>";
    }
}
