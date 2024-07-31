<?php 
  require "../config/config.php";
  require ROOT . "/include/function.php";
  spl_autoload_register("loadClass");
  session_start();
  if($_SERVER['REQUEST_METHOD']=='GET'){
    $id =$_GET['booking'];
    $query = "UPDATE booking SET status_id=2 where id = :id";
    $params=array(
     ":id"=>$id
    );
    $db = new DB;
    $db->update($query,$params);
    header("Location: index.php?url=booking");
    exit();
  }  
?>