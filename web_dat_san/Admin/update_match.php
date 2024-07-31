<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();


    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update'])){
        $db = new DB; 
        $team_id_1 = $_POST['team_1'];
        $team_id_2 = $_POST['team_2'];
        $score_1 = $_POST['score_1'];
        $score_2 = $_POST['score_2'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        $isWinner_1 = 0;
        $isWinner_2 = 0;

        $match_id = $_GET['match_id'];
        $tour_id = $_GET['tour_id'];
        $pitch_id = $_GET['pitch_id'];

        $query = "SELECT * FROM pitch_detail WHERE duration_id = :duration_id and pitch_id = :pitch_id";  
        $params = array(
            ":duration_id" => $time,
            ":pitch_id" => $pitch_id
        );
        $pitch_detail = $db->select($query,$params);
        
        if($pitch_detail){
            $pd_id = $pitch_detail[0]['id'];
            $update_match = "UPDATE `match` SET date=:date, pitch_detail_id=:pd_id WHERE id=:match_id";
            $param = array(
                ":date" => $date,
                ":pd_id" => $pd_id,
                ":match_id" => $match_id
            );
            $match = $db->update($update_match,$param);
            if($match){
                $update_detail = "UPDATE match_detail SET score=:score, isWinner=:isWinner WHERE match_id=:match_id and team_id=:team_id";
                if($score_1 > $score_2){
        
                    $isWinner_1 = 1;
                    
                    $param_1 = array(
                        ":score" => $score_1,
                        ":isWinner" => $isWinner_1,
                        ":match_id" => $match_id,
                        ":team_id"  => $team_id_1
                    );
                    $param_2 = array(
                        ":score" => $score_2,
                        ":isWinner" => $isWinner_2,
                        ":match_id" => $match_id,
                        "team_id"   => $team_id_2
                    );
                   $result_1 = $db->update($update_detail,$param_1);
                   $result_2 = $db->update($update_detail,$param_2);
                    if(isset($result_1) && isset($result_2)){
                        echo '<script>alert("Cập nhật thành ccông")
                        window.location.href = "detail_tournament.php?id='.$tour_id.'";
                        </script>';
        
                    }else{
                        echo '<script>alert("Cập nhật thất bại")
                        window.location.href = "detail_tournament.php?id='.$tour_id.'";
                        </script>';
                    }
        
                }else{
                    $isWinner_2 = 1;
                    $param_1 = array(
                        ":score" => $score_1,
                        ":isWinner" => $isWinner_1,
                        ":match_id" => $match_id,
                        ":team_id" => $team_id_1
                    );
                    $param_2 = array(
                        ":score" => $score_2,
                        ":isWinner" => $isWinner_2,
                        ":match_id" => $match_id,
                        ":team_id" => $team_id_2
        
                    );
                    $result_1 = $db->update($update_detail[],$param_1);
                   $result_2 = $db->update($update_detail[],$param_2);
                    if(isset($result_1) && isset($result_2)){
                        echo '<script>alert("Cập nhật thành ccông")
                        window.location.href = "detail_tournament.php?id='.$tour_id.'";
                        </script>';
        
                    }else{
                        echo '<script>alert("Cập nhật thất bại")
                        window.location.href = "detail_tournament.php?id='.$tour_id.'";
                        </script>';
                    }
                }
            }else{
                echo '<script>alert("Cập nhật thất bại")
                        window.location.href = "detail_tournament.php?id='.$tour_id.'";
                        </script>';
            }
        }
           
    }
?>