<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
$db = new DB;
$admin = new Admin;
$id = $_GET['id'];
$query = "UPDATE promotion SET deleted = 1 WHERE id = :id";
$params = array(
    ":id" => $id
);
$result = $db->update($query, $params);
var_dump($result);
if ($result) {
    header("Location: index.php?url=promotion");
    exit();
}
