<?php
require "./config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");


if($_SERVER['METHOD_REQUEST']=='POST' && isset($_POST['create'])){

    $db = new Db;
    $tour_id = $_GET['id'];
    $query =  "SELECT * FROM team where  tournament_id = :tour_id and isWinner = 1";
    $params = array(
        ":tour_id" => $tour_id
    );
    $teams = $db->select($query, $params);
    
    // Bước 1: Tạo mảng chứa các đội bóng
    $teams_arr = array();
    foreach ($teams as $team) {
        $teams_arr[] = array(
            "id" => $team['id'],
            "name" => $team['name']
        );
    }
    
    
    // Bước 2: Chọn hai đội đầu tiên từ mảng
    // Bắt được cặp 
    $round = ceil(count($teams_arr) / 2);
    
    $currentDate = date("Y-m-d");
    for ($i = 0; $i < $round; $i++) {

        // Lấy index lẻ
        $id1 = (2 * $i + 1);
        // Lấy index chẵn 
        $id2 = (2 * $i);
        
        // Kiểm tra nếu đội đã được chọn trước đó thì bỏ qua
        $name = $teams_arr[$id1]['name'] . ' vs ' . $teams_arr[$id2]['name'];

        $query_match = "INSERT INTO `match`( `name`, `date`, `pitch_detail_id`, `referee_id`, `tournament_id`) VALUES (:name,:date,:pd_id,:refer_id,:tournament_id)";
        $params_match = array(
            ":name" => $name,
            ":date" => $currentDate,
            ":pd_id" => 16,
            ":refer_id" => 1,
            ":tournament_id" => $tour_id
        );
        $result_match = $db->insert($query_match, $params_match);
        $id_match = $db->getInsertId(); //lấy Id của match 
        
        
    }
}

    
    ?>