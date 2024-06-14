<?php 
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if($_SERVER['REQUEST_METHOD']=='GET'){
       $id = $_GET['pitch'];
       $query = "UPDATE pitch SET deleted=1 where id = :id";
       $params=array(
        ":id"=>$id
       );
       $db = new DB;
       $db->update($query,$params);
       header("Location: index.php");
       exit();
}
?>