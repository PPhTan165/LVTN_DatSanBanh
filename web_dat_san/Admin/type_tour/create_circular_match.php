<?php
require "../../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');


if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['create'])){
    $db = new Db;
    $tour_id = $_GET['tour_id'];
    $query =  "SELECT t.id, t.name 
    FROM team t
    WHERE t.tournament_id = :tour_id AND t.group = :group
    ORDER BY t.point DESC
    LIMIT 1
    ";
    $params_A = array(
        ":tour_id" => $tour_id,
        ":group" => 'A'
    );
    $teams_a = $db->select($query, $params_A);
    
    $params_B = array(
        ":tour_id" => $tour_id,
        ":group" => 'B'
    );
    $teams_b = $db->select($query, $params_B);

    // Bước 1: Tạo mảng chứa các đội bóng
    $teams_arr = array_merge($teams_a, $teams_b);
    
    
    // Bước 2: Chọn hai đội đầu tiên từ mảng
    // lấy số lượng đội
    $teams_lenght = count($teams_arr);

    if($teams_lenght < 2){
        echo "<script>alert('Số lượng đội bóng ít nhất là 2 đội')
        window.location.href = '../detail_tournament.php?id=$tour_id';
        </script>";
        return;
    }
    $currentDate = date("Y-m-d");
    $round = $teams_lenght > 2 ? ceil($teams_lenght / 2) : 1;
    
    for ($i = 0; $i < $round; $i++) {
        $id1 = $i;
        $id2 = $i + 1;
        $name =  $teams_arr[$id1]['name'] . ' vs ' . $teams_arr[$id2]['name'];

        
        $query_match = "INSERT INTO `match`( name, date, pitch_detail_id,referee) VALUES (:name,:date,:pd_id,:refer)";
        $params_match = array(
            ":name" => $name,
            ":date" => $currentDate,
            ":pd_id" => 1,
            ":refer" => 'Tài',
        );
        //Thêm trận đấu
        $result_match = $db->insert($query_match, $params_match);
        
                $update_team = 'UPDATE team SET `group`=NULL,`isWinner`=0,`point`=0 WHERE tournament_id = :tour_id';
                $params_update = array(
                    ":tour_id" => $tour_id
                );
                $db->update($update_team, $params_update); // cập nhật lại trận đấu từ xoay vòng sang loại trực tiếp; 

        if ($result_match) {

            // Lấy id của trận đấu vừa tạo
            $id_match = $db->getInsertId();
            $insert_match_detail = "INSERT INTO match_detail( score, team_id, match_id) VALUES (:score,:team_id,:match_id)";
            
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
                winfow.location.href = "../detail_tournament.php?id=' . $tour_id . '"
            </script>';exit();
            } else {
                echo '<script>alert("Tạo lịch thi đấu thất bại")</script>';
            }
        }else{
            echo '<script>alert("Tạo trận đấu thất bại")</script>';
        }
    }
}

    
    ?>