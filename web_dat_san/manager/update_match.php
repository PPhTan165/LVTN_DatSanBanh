<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour = new Tournament;
    $db = new DB;

    $match_id = $_GET['match_id'];
    $tour_id = $_GET['tour_id'];

    $date = $_POST['date'];
    $time = $_POST['time'];
    $pitch = $_POST['pitch'];

    //ID 2 ĐỘI
    $team_id_1 = $_POST['team_1'];
    $team_id_2 = $_POST['team_2'];

    //TÊN 2 ĐỘI
    $t1_name = $_POST['t1_name'];
    $t2_name = $_POST['t2_name'];

    //TỶ SỐ 2 ĐỘI
    $score_1 = $_POST['score_1'] ?? 0;
    $score_2 = $_POST['score_2'] ?? 0;

    //ĐIỂM 2 ĐỘI
    $point_1 = $_POST['point_1'] ?? 0;
    $point_2 = $_POST['point_2'] ?? 0;

    //KIỂM TRA ĐỘI CÓ ĐIỀU KIỆN ĐỦ ĐỘI HAY KHÔNG
    $isWinner_1 = 0;
    $isWinner_2 = 0;

    $date_match = $tour->getDateMatchByTour($_GET['tour_id'], $date);
    $type_tour = $tour->getTypeTour($_GET['tour_id']);

    var_dump($_POST);

    $query = "SELECT * FROM pitch_detail WHERE duration_id = :duration_id and pitch_id = :pitch_id";
    $params = array(
        ":duration_id" => $time,
        ":pitch_id" => $pitch
    );
    $pitch_detail = $db->select($query, $params);

    if ($pitch_detail) {
        $pd_id = $pitch_detail[0]['id']; // lấy id của pd
        $exist_pd = $tour->existPitchDetail($tour_id, $pd_id);

        if ($date_match) {
            if ($exist_pd) {
                echo '<script>alert("Đã có sân trong giờ này")
                    window.location.href = "../detail_tournament.php?id=' . $tour_id . '";
                    </script>';
                exit();
            }
        }


        if (isset($_POST['date_info'])) {
            $update_match = "UPDATE `match` SET date=:date, pitch_detail_id=:pd_id WHERE id=:match_id";
            $match_arr = array(
                ":date" => $date,
                ":pd_id" => $pd_id,
                ":match_id" => $match_id
            );
            var_dump($match_arr);   
            $match = $db->update($update_match, $match_arr); // cập nhật thông tin trận đấu

            header("Location: detail_tournament.php?id=$tour_id");
        }

        else {

            // ĐÁ LOẠI TRỰC TIẾP
            if ($type_tour == 1) {

                //CẬP NHẬT TỶ SỐ
                $update_detail = "UPDATE match_detail SET score=:score WHERE match_id=:match_id and team_id=:team_id";

                //CẬP NHẬT ĐỘI THẮNG
                $update_team = "UPDATE team SET isWinner= :isWinner  WHERE id = :team_id";

                //CẬP NHẬT NGUOI THẮNG TRONG TRẬN
                $update_team_win = "UPDATE `match` SET winner= :winner WHERE id = :match_id";

                //NẾU 2 ĐỘI ĐỀU CÓ
                if ($team_id_1 !== '' && $team_id_2 !== '') {

                    //TEAM 1 THẮNG TEAM 2
                    if ($score_1 > $score_2) {

                        $isWinner_1 = 1;

                        $param_score_1 = array(
                            ":score" => $score_1,
                            ":match_id" => $match_id,
                            ":team_id"  => $team_id_1
                        );
                        $result_md_1 = $db->update($update_detail, $param_score_1);


                        $param_team_1 = array(
                            ":isWinner" => $isWinner_1,
                            ":team_id"  => $team_id_1
                        );
                        $result_team_1 = $db->update($update_team, $param_team_1);


                        $param_score_2 = array(
                            ":score" => $score_2,
                            ":match_id" => $match_id,
                            "team_id"   => $team_id_2
                        );
                        $result_md_2 = $db->update($update_detail, $param_score_2);


                        $param_team_2 = array(
                            ":isWinner" => $isWinner_2,
                            ":team_id"  => $team_id_2
                        );
                        $result_team_2 = $db->update($update_team, $param_team_2);


                        $param_match = array(
                            ":winner" => $t1_name,
                            ":match_id" => $match_id
                        );
                        $result_match = $db->update($update_team_win, $param_match);

                        if (isset($result_score_1) && isset($result_score_2) && isset($result_team_1) && isset($result_team_2) && isset($result_match)) {
                            echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                        } else {
                            echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                        }
                    }

                    //TEAM 2 THẮNG TEAM 1
                    else if ($score_1 < $score_2) {
                        $isWinner_2 = 1;

                        $param_score_1 = array(
                            ":score" => $score_1,
                            ":match_id" => $match_id,
                            ":team_id"  => $team_id_1
                        );
                        $result_md_1 = $db->update($update_detail, $param_score_1);


                        $param_team_1 = array(
                            ":isWinner" => $isWinner_1,
                            ":team_id"  => $team_id_1
                        );
                        $result_team_1 = $db->update($update_team, $param_team_1);

                        $param_score_2 = array(
                            ":score" => $score_2,
                            ":match_id" => $match_id,
                            "team_id"   => $team_id_2
                        );
                        $result_md_2 = $db->update($update_detail, $param_score_2);


                        $param_team_2 = array(
                            ":isWinner" => $isWinner_2,
                            ":team_id"  => $team_id_2
                        );
                        $result_team_2 = $db->update($update_team, $param_team_2);

                        $param_match = array(
                            ":winner" => $t2_name,
                            ":match_id" => $match_id
                        );
                        $result_match = $db->update($update_team_win, $param_match);

                        if (isset($result_score_1) && isset($result_score_2) && isset($result_team_1) && isset($result_team_2) && isset($result_match)) {
                            echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                        } else {
                            echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                        }
                    }
                }

                //TEAM 1 GẶP THẮNG TRẮNG
                else if ($team_id_2 === '' && $team_id_1 !== '') {
                    $isWinner_1 = 1;

                    $param_score_1 = array(
                        ":score" => $score_1,
                        ":match_id" => $match_id,
                        ":team_id"  => $team_id_1
                    );
                    $result_md_1 = $db->update($update_detail, $param_score_1);


                    $param_team_1 = array(
                        ":isWinner" => $isWinner_1,
                        ":team_id"  => $team_id_1
                    );
                    $result_team_1 = $db->update($update_team, $param_team_1);


                    $param_match = array(
                        ":winner" => $t1_name,
                        ":match_id" => $match_id
                    );
                    $result_match = $db->update($update_team_win, $param_match);

                    if (isset($result_md_1) && isset($result_team_1) && isset($result_match)) {
                        echo '<script>
                    window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                    </script>';
                    } else {
                        echo '<script>
                    window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                    </script>';
                    }
                }

                //TEAM 2 GẶP THĂM TRẮNG
                else if ($team_id_1 === '' && $team_id_2 !== '') {
                    $isWinner_2 = 1;

                    $param_score_2 = array(
                        ":score" => $score_2,
                        ":match_id" => $match_id,
                        "team_id"   => $team_id_2
                    );
                    $result_md_2 = $db->update($update_detail, $param_score_2);


                    $param_team_2 = array(
                        ":isWinner" => $isWinner_2,
                        ":team_id"  => $team_id_2
                    );
                    $result_team_2 = $db->update($update_team, $param_team_2);


                    $param_match = array(
                        ":winner" => $t2_name,
                        ":match_id" => $match_id
                    );
                    $result_match = $db->update($update_team_win, $param_match);

                    if (isset($result_md_2) && isset($result_team_2) && isset($result_match)) {
                        echo '<script>
                    window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                    </script>';
                    } else {
                        echo '<script>
                    window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                    </script>';
                    }
                }
            }


            // VÒNG LOẠI
            elseif ($type_tour == 2) {

                //CẬP NHẬT TỶ SỐ
                $update_detail = "UPDATE match_detail SET score=:score WHERE match_id=:match_id and team_id=:team_id";

                //CẬP NHẬT ĐIỂM VÀ ĐỘI THẮNG
                $update_team_win = "UPDATE team SET isWinner=:isWinner, point = point + 3 WHERE id = :team_id";

                //CẬP NHẬT ĐIỂM VÀ ĐỘI HÒA
                $update_team_draw = "UPDATE team SET isWinner=:isWinner, point = point + 1 WHERE id = :team_id";

                //CẬP NHẬT NGƯỜI THẮNG TRONG TRẬN
                $update_match_win = "UPDATE `match` SET winner= :winner WHERE id = :match_id";

                // CẬP NHẬT KẾT QUẢ TRẬN ĐẤU

                //HOÀ
                if ($score_1 == $score_2) {

                    $param_score_1 = array(
                        ":score" => $score_1,
                        ":match_id" => $match_id,
                        ":team_id"  => $team_id_1
                    );
                    $result_md_1 = $db->update($update_detail, $param_score_1);

                    $param_team_1 = array(
                        "isWinner" => 0,
                        ":team_id"  => $team_id_1
                    );
                    $result_team_1 = $db->update($update_team_draw, $param_team_1);

                    $param_score_2 = array(
                        ":score" => $score_2,
                        ":match_id" => $match_id,
                        "team_id"   => $team_id_2
                    );
                    $result_md_2 = $db->update($update_detail, $param_score_2);

                    $param_team_2 = array(
                        "isWinner" => 0,
                        ":team_id"  => $team_id_2
                    );
                    $result_team_2 = $db->update($update_team_draw, $param_team_2);

                    $param_match = array(
                        ":winner" => "Hòa",
                        ":match_id" => $match_id
                    );
                    $result_match = $db->update($update_match_win, $param_match);

                    if (isset($result_score_1) && isset($result_score_2) && isset($result_team_1) && isset($result_team_2) && isset($result_match)) {
                        echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                    } else {
                        echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                    }
                }

                // TEAM 1 THẮNG
                else if ($score_1 > $score_2) {
                    $isWinner_1 = 1;
                    $param_score_1 = array(
                        ":score" => $score_1,
                        ":match_id" => $match_id,
                        ":team_id"  => $team_id_1
                    );
                    $result_md_1 = $db->update($update_detail, $param_score_1);

                    $param_team_1 = array(
                        "isWinner" => $isWinner_1,
                        ":team_id"  => $team_id_1
                    );
                    $result_team_1 = $db->update($update_team_win, $param_team_1);

                    $param_score_2 = array(
                        ":score" => $score_2,
                        ":match_id" => $match_id,
                        "team_id"   => $team_id_2
                    );
                    $result_md_2 = $db->update($update_detail, $param_score_2);

                    $param_match = array(
                        ":winner" => $t1_name,
                        ":match_id" => $match_id
                    );
                    $result_match = $db->update($update_match_win, $param_match);

                    if (isset($result_md_1) && isset($result_md_2) && isset($result_team_1) && isset($result_match)) {
                        echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                    } else {
                        echo '<script>
                            window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                    }
                }

                //TEAM 2 THẮNG
                else {

                    $isWinner_2 = 1;
                    $param_score_2 = array(
                        ":score" => $score_1,
                        ":match_id" => $match_id,
                        ":team_id"  => $team_id_1
                    );
                    $param_team_2 = array(
                        "isWinner" => $isWinner_1,
                        ":team_id"  => $team_id_1
                    );

                    $result_md_2 = $db->update($update_detail, $param_score_2);
                    $result_team_2 = $db->update($update_team_win, $param_team_2);

                    $param_score_1 = array(
                        ":score" => $score_2,
                        ":match_id" => $match_id,
                        "team_id"   => $team_id_2
                    );

                    $result_md_1 = $db->update($update_detail, $param_score_1);

                    $param_match = array(
                        ":winner" => $t2_name,
                        ":match_id" => $match_id
                    );
                    $result_match = $db->update($update_match_win, $param_match);

                    if (isset($result_md_1) && isset($result_md_2) && isset($result_team_2) && isset($result_match)) {
                        echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                    } else {
                        echo '<script>
                        window.location.href = "detail_tournament.php?id=' . $tour_id . '";
                        </script>';
                    }
                }
            } else {
                echo '<script>alert("Sân đã được đặt vào thời gian này")
            window.location.href = "detail_tournament.php?id=' . $tour_id . '";
            </script>';
            }
        }
    }
}
