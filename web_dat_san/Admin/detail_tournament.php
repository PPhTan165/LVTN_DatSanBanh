<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css//output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>TOTTENHAM FC</title>
</head>

<body>
    <?php require_once("../include/header_admin.php") ?>

    <?php
    $id = $_GET['id'];
    $admin = new Admin;
    $team = new Team;
    $tour = new Tournament;

    ?>

    <h1 class="text-center font-bold text-3xl mt-5 mb-5 pb-8">CHI TIẾT GIẢI ĐẤU</h1>

    <div class="mb-5 flex justify-center">
        <?php

        $info_tournament = $admin->getTournamentById($id);
        $team_of_tournament = $team->getTeamOfTournamentById($id);
        $start_day = date("d-m-Y", strtotime($info_tournament['start_day']));
        $end_day = date("d-m-Y", strtotime($info_tournament['end_day']));

        ?>
        <div class="info-tour w-96 ms-5 sm:rounded-lg shadow-md p-4">
            <h2 class="text-xl font-bold mb-5">Thông tin giải đấu</h2>

            <div class="mb-5">

                <label for="tour_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên giải
                    đấu</label>
                <input type="text" id="tour_name" aria-label="disabled input" class="mb-5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $info_tournament['name']  ?>" disabled>

            </div>

            <div class="mb-5">

                <label for="tour_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày bắt
                    đâu</label>
                <input type="text" id="tour_name" aria-label="disabled input" class="mb-5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $start_day ?>" disabled>

            </div>


            <div class="mb-5">

                <label for="tour_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày kết
                    thúc</label>
                <input type="text" id="tour_name" aria-label="disabled input" class="mb-5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $end_day  ?>" disabled>

            </div>
        </div>

        <div class="overflow-y-auto shadow-md sm:rounded-lg w-full ms-5 p-5">
            <h2 class="text-xl font-bold ms-5 mb-5">Danh sách đội</h2>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Đội
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Đội trưởng
                        </th>
                        <th scope="col" class="px-6 py-3">

                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($team_of_tournament) {
                        $index = 1;
                        foreach ($team_of_tournament as $team) {

                            echo '   <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $index++ . '
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $team['t_name'] . '
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ' . $team['cus_name'] . '
                        </th>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <a href="delete_team.php?id=' . $team['id'] . '" class="text-red-500">Delete</a>
                        </th>
                        
                    </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <hr>
    <div class="mb-5 mt-5">
        <h2 class="text-left font-bold text-2xl mb-5 ms-5">LỊCH THI ĐẤU</h2>
        <div class="flex ">
            <?php
            $result = $tour->existMatchTour($id);
            if (!isset($result)) {

                echo '<form action="random_match.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id ?>">
            <button type="submit" name="random"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-5">
                Random Match
            </button>
            </form>';
            }
            ?>
            <form action="create_match?tour_id=<?= $id ?>" method="post">
                <button type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-5">
                    Create Match
                </button>
            </form>
        </div>

        <?php

        $histories = $admin->historyMatchDetail($id);

        echo ' <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                       STT
                    </th>
                    <th scope="col" class="px-3 py-3">
                       Đội 1 - Đội 2
                    </th>
                    <th scope="col" class="px-3 py-3">
                       Sân thi đấu
                    </th>
                    
                    <th scope="col" class="px-1 py-3">
                       Giờ thi đấu
                    </th>
                    <th scope="col" class="px-6 py-3">
                       Ngày thi đấu
                    </th>
                    <th scope="col" class="px-1 py-3">
                        Kết quả thắng cuộc
                    </th>
                    <th scope="col" class="px-1 py-3">
                       Trạng thái
                    </th>
                    <th scope="col" class="px-1 py-3">
                
                    </th>
                </tr>
            </thead>

            <tbody>';
        if ($result == null) {
            echo '<script>alert(
                                "Không có trận đáu nào cả"
                                )</script>';
        } else {
            $currentDate = date('Y-m-d');
            $index = 1;
            foreach ($histories as $match_detail) {
                $j = 0;

                $infoMatch = $tour->getInfoMatchByMatchId($match_detail['id']);
                $count = count($infoMatch);

                //Kiểm tra nếu team trúng thăm trắng
                if ($count == 1) {
                    $infoMatch[] = array(
                        "pitch_id" => '',
                        "match_id" => '',
                        "start" => '',
                        "t_name" => '',
                        "score" => 0,
                        "team_id" => '',

                    );
                }

                $pitch_id = $infoMatch[$j]['pitch_id'];
                $id_match = $infoMatch[$j]['match_id'];
                $start = $infoMatch[$j]['start'];
                //Thay đổi biến tĩnh thành động

                $team_1 = $infoMatch[$j]['t_name'];
                $team_2 = $infoMatch[$j + 1]['t_name'];
                $score_1 = $infoMatch[$j]['score'];
                $score_2 = $infoMatch[$j + 1]['score'];
                $team_id_1 = $infoMatch[$j]['team_id'];
                $team_id_2 = $infoMatch[$j + 1]['team_id'];
                $time = $tour->getAllDurationByPitchId($pitch_id);
                $pitch = $tour->getAllPitch();
                $winner = $tour->getTeamWinById($id_match);
                echo '<tr>
                <td class="px-6 py-4">
                   ' . $index++ . '
                </td>
                        
                <td class="px-3 py-4 font-bold text-lg">
                   ' . $match_detail['name'] . '
                </td>
                        
                <td class="px-3 py-4  text-base">
                   ' . $match_detail['p_name'] . '
                </td>
                  
                        
                <td class="px-1 py-4 text-base">
                   <input type="text" id="time" name="time" aria-label="disabled input" class=" mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-52 p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' . $match_detail['start'] . '" disabled/>
                </td>

                <td class="px-6 py-4 text-base">
                   ' . $match_detail['date'] . '
                </td>

                <td class="px-6 py-4 font-bold text-base text-red-500">
                ' . $winner . ' 
                </td>

                <td class="px-1 py-4">
                   ';
                if ($currentDate > $match_detail['date'] && $winner != '') {
                    echo '<div class="text-base font-bold text-red-700">Kết thúc</div>';
                } else if ($currentDate < $match_detail['date']) {
                    echo '<div class="text-base font-bold text-blue-500">Chưa bắt đầu</div>';
                } else {
                    echo '<div class="text-base font-bold text-green-500">Đang diễn ra</div>';
                }
                echo '
                </td>
            
                

                <td class="px-1 py-4">
                    <button onclick="toggleDetail(' . $index . ')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Chi tiết</button>
                </td>
            </tr>
            
            <tr id="detail-' . $index . '" style="display: none;">
                    <td colspan="7">
                        <div class="flex justify-center items-center">
                            <form action="update_match.php?tour_id=' . $_GET['id'] . '&match_id=' . $id_match . '" method="post">
                                <div>
                                    <input type="hidden" name="team_1" value="' . $team_id_1 . '"/> 
                                    <input type="hidden" name="team_2" value="' . $team_id_2 . '"/> 
                                </div>
                                <div class="flex items-center gap-5">

                                    <div class="mb-5">
                                        <label for="team_1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Đội 1</label>
                                        <input type="text" id="team_1" value="' . $team_1 . '" aria-label="disabled input" disabled class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                    <div class="mb-5">
                                        <label for="score" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-center">Tỷ số</label>
                                        <div class="flex justify-center gap-5 items-center">
                                            <input type="text" id="score" name="score_1" value="' . $score_1 . '" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            -
                                            <input type="text" id="score" name="score_2" value="' . $score_2 . '" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="team_2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-right">Đội 2</label>
                                        <input type="text" id="team_2"  value="' . $team_2 . '" aria-label="disabled input" disabled class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <div class="mb-5">
                                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày thi đấu</label>
                                        <input type="date" value="' . $infoMatch[0]['date'] . '" id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                    <div class="mb-5">
                                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn giờ muốn thi đấu</label>
                                        <select id="time" name="time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected value="' . $infoMatch[0]['d_id'] . '">' . $infoMatch[0]['start'] . '</option>';
                                             foreach ($time as $value) {
                                                 echo '<option value="' . $value['d_id'] . '">' . $value['start'] . '</option>';
                                             }
                                    echo '
                                        </select>
                                    </div>

                                    <div class="mb-5">
                                        <label for="pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn sân muốn thi đấu</label>
                                        <select id="pitch" name="pitch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected value="' . $infoMatch[0]['pitch_id'] . '">' . $infoMatch[0]['pitch_name'] . '</option>';
                                             foreach ($pitch as $value) {
                                                 echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                                             }
                                    echo '
                                        </select>
                                    </div>
                                </div>
                                <div class = "mb-5 w-full">
                                    <button type="submit" name="update" class="flex justify-center w-full focus:outline-none text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Update</a>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                
               ';
                $j += 2;
            }
        }

        echo ' </tbody>
            </table>';
        ?>
        
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
    function toggleDetail(index) {
        var detailRow = document.getElementById("detail-" + index);
        if (detailRow.style.display === "none") {
            detailRow.style.display = "table-row";
        } else {
            detailRow.style.display = "none";
        }
    }
</script>

</html>