<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    $db = new DB;
    $tour = new Tournament;
    $exist = $tour->existMatchTour($id);

    if($exist){
        echo "<script>alert('Không thể xóa giải đấu đã diễn ra')
            window.location.href='index.php?url=tournament'
        </script>";
        exit();
    }else{
        $team_query = "UPDATE customer JOIN team ON customer.team_id = team.id SET team_id = NULL WHERE tournament_id = :id";
        $query = "UPDATE tournament SET deleted = 1 where id = :id";
        $params = array(
            ":id" => $id
        );
        $db->update($query, $params);
        $db->update($team_query, $params);
        header("Location: index.php?url=tournament");
        exit();
    }
}
