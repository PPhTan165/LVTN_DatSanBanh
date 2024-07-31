<?php
require "./config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['random'])) {


    $db = new Db;
    $tour_id = 1;
    $query =  "SELECT team.id, team.name FROM team where  tournament_id = :tour_id";
    $params = array(
        ":tour_id" => $tour_id
    );
    $teams = $db->select($query, $params);
    if (count($teams) < 2) {
        echo "<script>alert('Số lượng đội bóng không đủ để tạo lịch thi đấu')</script>";
        return;
    }

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
    // Bắt được cặp 
    $round = $teams_lenght > 2 ? ceil($teams_lenght / 2) : 1;

    echo '
<table border="1">
    <thead>
        <tr>
            <th>STT</th>
            <th>Cặp</th>
            <th>Đội 1</th>
            <th>Tỷ số</th>
            <th>Đội 2</th>
        </tr>
    </thead>
    <tbody>';
    $currentDate = date("Y-m-d");
    var_dump($teams_arr);
    for ($i = 0; $i < $round; $i++) {
        $id1 = (2 * $i);
        $id2 = (2 * $i + 1);
        $name = '';
        echo $id1 . '-' . $id2 . '<br>';
        // Kiểm tra nếu đội đã được chọn trước đó thì bỏ qua
        // if ($i + 2 < count($teams_arr) && $teams_arr[$i] != $teams_arr[$i + 2]) {
        //     $name =  $teams_arr[$id1]['name'] . ' vs ' . $teams_arr[$id2]['name'];
        // } else {
        //     $name =  $teams_arr[$id1]['name'] . ' vs ' . $teams_arr[$id2]['name'];
        // }

        // $query_match = "INSERT INTO `match`( name, date, pitch_detail_id, referee_id, tournament_id) VALUES (:name,:date,:pd_id,:refer_id,:tournament_id)";
        // $params_match = array(
        //     ":name" => $name,
        //     ":date" => $currentDate,
        //     ":pd_id" => 16,
        //     ":refer_id" => 1,
        //     ":tournament_id" => $tour_id
        // );
        //$result_match = $db->insert($query_match, $params_match);
        $id_match = $db->getInsertId();

        // $insert_match_detail = "INSERT INTO match_detail( score, team_id, match_id) VALUES (:score,:team_id,:match_id)";
        // $params_md_1 = array(
        //     ":score" => 0,
        //     ":team_id" => $teams_arr[$id1]['id'],
        //     ":match_id" => $id_match
        // );
        // $params_md_2 = array(
        //     ":score" => 0,
        //     ":team_id" => $teams_arr[$id2]['id'],
        //     ":match_id" => $id_match
        // );
        // $result_md_1 = $db->insert($insert_match_detail, $params_md_1);
        // $result_md_2 = $db->insert($insert_match_detail, $params_md_2);


        echo '  <tr>
                <td>' . ($i + 1) . '</td>
                <td>' . $name . '</td>
                <td>' . $teams_arr[$id1]['id'] . '</td>
                <td>
                    <input type="number" min="0" name="score1" id="score1" value="0">
                    <input type="number" min="0" name="score2" id="score2" value="0">

                </td>
                <td>' . $teams_arr[$id2]['id'] . '</td>
            </tr>
        ';
    }
    echo '
</tbody>
</table>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="#" method="post">
        <input type="submit" name="random" value="Random">
    </form>
</body>

</html>