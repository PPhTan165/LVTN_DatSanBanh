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
    $type_tour = $tour->getTypeTour($id);
    ?>

    <h1 class="text-center font-bold text-3xl mt-5 mb-5 pb-8">CHI TIẾT GIẢI ĐẤU</h1>

    <div class="mb-5 flex justify-center">
        <?php

        $info_tournament = $admin->getTournamentById($id);
        $team_of_tournament = $team->getTeamOfTournamentById($id);
        $groupTeam_A = $team->getTeamOfGroupTournamentById($id, 'A');
        $groupTeam_B = $team->getTeamOfGroupTournamentById($id, 'B');
        $start_day = date("d-m-Y", strtotime($info_tournament['start_day']));
        $winner_of_tournament = $tour->getWinnerOfTournament($id);
        ?>
        <div class="info-tour w-96 ms-5 sm:rounded-lg shadow-md p-4">
            <h2 class="text-xl font-bold mb-5">THÔNG TIN GIẢI ĐẤU</h2>

            <div class="mb-5">

                <label for="tour_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên giải đấu</label>
                <input type="text" id="tour_name" aria-label="disabled input" class="mb-5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $info_tournament['name']  ?>" disabled>

            </div>

            <div class="mb-5">

                <label for="tour_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày bắt đâu</label>
                <input type="text" id="tour_name" aria-label="disabled input" class="mb-5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $start_day ?>" disabled>

            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="">Đội chiến thắng:</label>
                <h2 class="font-bold text-3xl text-blue-500 text-center"><?=$winner_of_tournament?></h2>
            </div>
           
        </div>

        <div class="overflow-y-auto shadow-md sm:rounded-lg w-full ms-5 p-5">
            <h2 class="text-xl font-bold ms-5 mb-5">DANH SÁCH ĐỘI</h2>
            <?php
             $winnerOfTour = $tour->checkLastWinner($id);
                if($type_tour == 1){
                    require_once("./type_tour/knockout.php");
                }else{
                    require_once("./type_tour/circular.php");
                }
            ?>
        </div>

    </div>
    <hr>
    <div class="mb-5 mt-5">
        <h2 class="text-left font-bold text-2xl mb-5 ms-5">LỊCH THI ĐẤU</h2>
        <div class="flex ">
            <?php
            $existMatch = $tour->existMatchTour($id);
           
            if (!$existMatch) {
                if($type_tour == 1){
                    echo '<form action="./type_tour/random_knockout_match.php" method="post">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="random"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-5">
                            Bắt cặp ngẫu nhiên
                        </button>
                    </form>';
                }else{
                    echo '<form action="./type_tour/random_circular_match.php" method="post">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="random"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-5">
                            Tạo bảng thi đấu
                        </button>
                    </form>';
                }
                
            }else if($type_tour == 1){
                echo '<form action="./type_tour/create_knockout_match?tour_id='.$id.'" method="post">
                <button type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-5">
                    Tạo trận đấu mới (Vòng sau)
                </button>
            </form>';
            
            ?>
            
            <?php }
            else{
            
               echo ' <form action="./type_tour/create_circular_match?tour_id='.$id.'" method="post">
               <button type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-5">
                   Tạo trận chung kết
               </button>
           </form>'; 
          } ?>
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
                    <th scope="col" class="py-3 text-center">
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
        if ($existMatch == null) {
            return;
        } else {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentDate = date('Y-m-d');
            $currentHour = date('H');
            
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
                $start_int = (int)date('H', strtotime($start));

                //THÔNG TIN 2 ĐỘI
                //TEAM 1 - TEAM 2
                $team_1 = $infoMatch[$j]['t_name'];
                $team_2 = $infoMatch[$j + 1]['t_name'];

                //TỶ SỐ 2 ĐỘI
                $score_1 = $infoMatch[$j]['score'];
                $score_2 = $infoMatch[$j + 1]['score'];

                //ID 2 ĐỘI
                $team_id_1 = $infoMatch[$j]['team_id'];
                $team_id_2 = $infoMatch[$j + 1]['team_id'];

                //ĐIỂM 2 ĐỘI NẾU ĐÁ XOAY VÒNG
                $point_1 = $infoMatch[$j]['point']??0;
                $point_2 = $infoMatch[$j + 1]['point']??0;
                $time = $tour->getAllDurationByPitchId($pitch_id);
                $pitch = $tour->getAllPitch();
                
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

                <td class="py-4 text-center font-bold text-base text-green-500">
                ' . $match_detail['winner'] . ' 
                </td>

                <td class="px-1 py-4">
                   ';
                   $date_now = new DateTime($currentDate);
                   $date_match  = new DateTime($match_detail['date']);
                
                if ($date_now > $date_match || $match_detail['winner'] !== null) 
                {
                    echo '<div class="text-base font-bold text-red-700">Kết thúc</div>';
                
                } 
                
                else if ($currentDate == $match_detail['date'] ) 
                
                {

                        if( (int)$currentHour >= $start_int && ((int)$currentHour <= ($start_int + 2)) )
                        {
                            echo '<div class="text-base font-bold text-green-500">Đang diễn ra</div>';
                        }
                        elseif( (int)$currentHour < ($start_int + 2) )
                        {
                            echo '<div class="text-base font-bold text-blue-500">Chưa bắt đầu</div>';

                        }else{
                            echo '<div class="text-base font-bold text-red-700">Kết thúc</div>';
                            
                        }
                }else{

                            echo '<div class="text-base font-bold text-blue-500">Chưa bắt đầu</div>';

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
                                <div>
                                    <input type="hidden" name="point_1" value="' . $point_1 . '"/> 
                                    <input type="hidden" name="point_2" value="' . $point_2 . '"/> 
                                </div>
                                <div>
                                    <input type="hidden" name="t1_name" value="' . $team_1 . '"/> 
                                    <input type="hidden" name="t2_name" value="' . $team_2 . '"/> 
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
                                        <input type="date" value="' . $infoMatch[0]['date'] . '"  id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                                <div class = "mb-5 w-full flex">
                                <button type="submit" name="date_info" class="flex justify-center w-full focus:outline-none text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cập nhật thông tin sân</a>
                                <button type="submit" name="score" class="flex justify-center w-full focus:outline-none text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cập nhật tỷ số</a>
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