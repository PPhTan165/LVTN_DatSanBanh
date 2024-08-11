<?php
require "../../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['random'])) {
    
    $db = new Db;   
    $currentDate = date("Y-m-d");
    $next_week = date('Y-m-d', strtotime('+1 week'));

    $tour_id = $_POST['id'];
    $query =  "SELECT team.id, team.name FROM team where  tournament_id = :tour_id";
    $params = array(
        ":tour_id" => $tour_id
    );
    $teams = $db->select($query, $params);
    
    if (count($teams) < 4) {
        echo "<script>alert('Số lượng đội bóng ít nhất là 4 đội')
        window.location.href = 'detail_tournament.php?id=$tour_id';
        </script>";
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
        //SỐ LƯỢNG ĐỘI
        shuffle($teams_arr);
        $teams_lenght = count($teams_arr);
        $half_teams = ceil($teams_lenght/2); // nữa đầu
        $half_b = $teams_lenght - $half_teams; // nữa cuối

        //BẢNG A VÀ BẢNG B
        $teams_a = array();
        $teams_b = array();
        for($groupA = 0; $groupA < $half_teams; $groupA++){
            $teams_a[] = $teams_arr[$groupA];
        }
        
        for($groupB = $half_teams; $groupB < $teams_lenght; $groupB++){
            $teams_b[] = $teams_arr[$groupB];
        }
        // Bước 2: tạo bảng thi đấu 

        //TẠO TRẬN CHO BẢNG A  
        for ($i = 0; $i < $half_teams; $i++) {
            $update_team = "UPDATE team SET `group` = 'A' WHERE id = :id";
            $params_team = array(
                ":id" => $teams_a[$i]['id']
            );
            $result_team_A = $db->update($update_team, $params_team);
            for ($j = $i + 1; $j < $half_teams; $j++) {

                $name_A =  $teams_a[$i]['name'] . ' vs ' . $teams_a[$j]['name'];

                $query_match = "INSERT INTO `match`( name, date, pitch_detail_id, referee) VALUES (:name,:date,:pd_id,:refer)";
                $params_match_A = array(
                    ":name" => $name_A,
                    ":date" => $next_week,
                    ":pd_id" => 1,
                    ":refer" => 'Tài',
                );

                //Thêm trận đấu
                $result_match_A = $db->insert($query_match, $params_match_A);

                    // Lấy id của trận đấu vừa tạo
                    $id_match_A = $db->getInsertId();
                    $insert_match_detail = "INSERT INTO match_detail( score, team_id, match_id) VALUES (:score,:team_id,:match_id)";

                    $params_md_A1 = array(
                        ":score" => 0,
                        ":team_id" => $teams_arr[$i]['id'],
                        ":match_id" => $id_match_A
                    );

                    //Thêm đội 1
                    $result_md_A1 = $db->insert($insert_match_detail, $params_md_A1);

                    $params_md_A2 = array(
                        ":score" => 0,
                        ":team_id" => $teams_arr[$j]['id'],
                        ":match_id" => $id_match_A
                    );

                    //Thêm đội 2
                    $result_md_A2 = $db->insert($insert_match_detail, $params_md_A2);

            }
        } 

        for ($k = 0; $k < $half_b; $k++) {
            $update_team = "UPDATE team SET `group` = 'B' WHERE id = :id";
            
            $params_team = array(
                ":id" => $teams_b[$k]['id']
            );
            $result_team_B = $db->update($update_team, $params_team);
            for ($l = $k + 1; $l < $half_b; $l++) {

                $name_B =  $teams_b[$k]['name'] . ' vs ' . $teams_b[$l]['name'];


                $query_match = "INSERT INTO `match`( name, date, pitch_detail_id, referee) VALUES (:name,:date,:pd_id,:refer)";
                $params_match_B = array(
                    ":name" => $name_B,
                    ":date" => $next_week,
                    ":pd_id" => 1,
                    ":refer" => 'Tài',
                );

                //Thêm trận đấu
                $result_match_B = $db->insert($query_match, $params_match_B);
                    // Lấy id của trận đấu vừa tạo
                    $id_match_B = $db->getInsertId();
                    $insert_match_detail = "INSERT INTO match_detail( score, team_id, match_id) VALUES (:score,:team_id,:match_id)";
                    $params_md_B1 = array(
                        ":score" => 0,
                        ":team_id" => $teams_arr[$k]['id'],
                        ":match_id" => $id_match_B
                    );

                    //Thêm đội 1
                    $result_md_B1 = $db->insert($insert_match_detail, $params_md_B1);

                    $params_md_B2 = array(
                        ":score" => 0,
                        ":team_id" => $teams_arr[$l]['id'],
                        ":match_id" => $id_match_B
                    );
                    //Thêm đội 2
                    $result_md_B2 = $db->insert($insert_match_detail, $params_md_B2);

                   
                } 
            }
        } 
        if (isset($result_md_B1) && isset($result_md_B2) && isset($result_md_A1) && isset($result_md_A2)) {
            echo '<script>alert("Tạo lịch thi đấu thành công")
        winfow.location.href = "../detail_tournament.php?id=' . $tour_id . '";
        </script>';exit();
        } else {
            echo '<script>alert("Tạo lịch thi đấu thất bại")
            window.location.href = "../detail_tournament.php?id=' . $tour_id . '";
            </script>';exit(); 
        }
    }

