<?php 
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();


 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['random'])) {

    $db = new Db;
    $currentDate = date("Y-m-d");
    $tour_id = $_POST['id'];
    $query =  "SELECT team.id, team.name FROM team where  tournament_id = :tour_id";
    $params = array(
        ":tour_id" => $tour_id
    );
    $teams = $db->select($query, $params);
    if (count($teams)<2) {
        echo "<script>alert('Số lượng đội bóng ít nhất là 4 đội')</script>";
        return;
    } else {

        // Bước 1: Tạo mảng chứa các đội bóng
        $teams_arr = array();
        foreach ($teams as $team) {
            $teams_arr[] = array(
                "id" => $team['id'],
                "name" => $team['name']
            );
        }
    
        $teams_lenght = count($teams_arr);
    
        if ($teams_lenght % 2 != 0) {
            $teams_arr[] = array(
                "id" => '',
                "name" => ''
            );
        }
        // Bước 2: Sử dụng hàm shuffle để xáo trộn các đội
        shuffle($teams_arr);
    
        // Bước 3: Chọn hai đội đầu tiên từ mảng đã xáo trộn
        
        // Số vòng thi
        $round = $teams_lenght > 2 ? ceil($teams_lenght / 2) : 1;
    
        for ($i = 0; $i < $round; $i++) {
            $id1 = (2 * $i);
            $id2 = (2 * $i + 1);
            $name =  $teams_arr[$id1]['name'] . ' vs ' . $teams_arr[$id2]['name'];


            $query_match = "INSERT INTO `match`( name, date, pitch_detail_id, referee_id, tournament_id) VALUES (:name,:date,:pd_id,:refer_id,:tournament_id)";
            $params_match = array(
                ":name" => $name,
                ":date" => $currentDate,
                ":pd_id" => 16,
                ":refer_id" => 1,
                ":tournament_id" => $tour_id
            );
            //Thêm trận đấu
            $result_match = $db->insert($query_match, $params_match);

            if ($result_match) {

                // Lấy id của trận đấu vừa tạo
                $id_match = $db->getInsertId();
                $insert_match_detail = "INSERT INTO match_detail( score, team_id, match_id, isWinner) VALUES (:score,:team_id,:match_id,0)";
                
                $params_md_1 = array(
                    ":score" => 0,
                    ":team_id" => $teams_arr[$id1]['id'],
                    ":match_id" => $id_match
                );
                
                //Thêm đội 1
                $result_md_1 = $db->insert($insert_match_detail, $params_md_1);

                $params_md_2 = array(
                    ":score" => 0,
                    ":team_id" => $teams_arr[$id2]['id'],
                    ":match_id" => $id_match
                );
                //Thêm đội 2
                $result_md_2 = $db->insert($insert_match_detail, $params_md_2);

                if ($result_md_1 && $result_md_2) {
                    echo '<script>alert("Tạo lịch thi đấu thành công")
                    winfow.location.href = "detail_tournament.php?id=' . $tour_id . '";
                </script>';
                } else {
                    echo '<script>alert("Tạo lịch thi đấu thất bại")</script>';
                }
            }else{
                echo '<script>alert("Tạo trận đấu thất bại")</script>';
            }
        }
    }
}
?>