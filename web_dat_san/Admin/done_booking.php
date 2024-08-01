<?php 
  require "../config/config.php";
  require ROOT . "/include/function.php";
  spl_autoload_register("loadClass");
  session_start();
  if($_SERVER['REQUEST_METHOD']=='GET'){
    $id =$_GET['booking'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $query = "UPDATE booking SET status_id=3 where id = :id";
    $params=array(
     ":id"=>$id
    );
    $db = new DB;
    $db->update($query,$params);
    header("Location: index.php?url=booking&page=$page");
    exit();
  }  
?>