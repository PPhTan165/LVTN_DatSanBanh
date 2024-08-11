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
    <?php require_once("../include/header.php");
    $id = $_GET['id'];
    $tour = new Tournament;
    $tournament = $tour->getInfoTournament($id);
    $matchs = $tour->getInfoMatchById($id);
    $winner = $tour->getWinnerOfTournament($id);
    $type_tour = $tour->getTypeTour($id);

    ?>
    <div class="main p-8">
        <h2 class="text-3xl font-bold text-center mb-5">Chi tiết giải đấu</h2>
        <div class="title mb-5">
            <h5 class="text-2xl font-bold">Thông tin giải đấu</h5>
            <?php
            echo '<div class ="flex gap-2">
            <h5 class="font-semibold text-2xl text-gray-500 ">Tên giải đấu:  </h5>
            <h5 class="font-semibold text-2xl "> ' . $tournament['name'] . ' </h5>
            </div>
                
            <div class ="flex gap-2">
            <h5 class="font-semibold text-xl text-gray-500 ">Thời gian bắt đầu:  </h5>
            <h5 class="font-semibold text-2xl "> ' . $tournament['start_day'] . ' </h5>
            </div>';
            if ($tournament['deleted'] == 1) {
                echo '<div class ="flex gap-2">
                <p class="font-semibold text-xl text-gray-500">Trạng thái: </p>
                <p class="text-xl text-red-500 font-bold"> Đã kết thúc</p>
                </div>';
            } else {
                echo '<div class ="flex gap-2">
                <p class="font-semibold text-xl text-gray-500">Trạng thái: </p>
                <p class="text-xl text-green-500 font-bold">Đang diễn ra</p>
                </div>';
            }


            ?>

        </div>
        <div class="overflow-y-auto shadow-md sm:rounded-lg w-full ms-5 p-5">
            <h2 class="text-xl font-bold ms-5 mb-5">DANH SÁCH ĐỘI</h2>
            <?php
             $winnerOfTour = $tour->checkLastWinner($id);
                $team = new Team;
             $groupTeam_A = $team->getTeamOfGroupTournamentById($id, 'A');
             $groupTeam_B = $team->getTeamOfGroupTournamentById($id, 'B');
                if($type_tour == 1){
                    require_once("../admin/type_tour/knockout.php");
                }else{
                    require_once("../admin/type_tour/circular.php");
                }
            ?>
        </div>
        <div class="border-2 p-5 mt-5 mb-5 rounded-xl shadow-lg">
            <?php
            if ($winner) {
                echo '<div class ="text-center w-fit mb-5">
                    <p class="text-2xl font-semibold">Đội vô địch: </p>
                    <p class="text-5xl text-blue-500 font-bold "> ' . $winner . '</p>
                    </div>';
            }
            ?>
            <h2 class="text-3xl font-bold text-center mb-5">Kết quả trận đấu</h2>
            <div class="grid place-items-center place-content-center gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 p-5">

                <?php
                $index = 1;
                if ($matchs) {


                    foreach ($matchs as $match) {

                        $match_detail = $tour->getMatchDetailById($match['id']);
                        $md_lenght = count($match_detail);
                        if ($md_lenght % 2 != 0) {
                            $md_lenght -= 1;
                        }
                        echo '<a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

            <h5 class="text-center mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">' . $index++ . '. ' . $match['m_name'] . '</h5>
            <p class="font-bold text-center dark:text-gray-400 text-2xl text-gray-700  ">' . ($match['p_name']) . '</p>
            <div class="flex justify-center items-center gap-2 mb-3">
            <p class="font-light text-center dark:text-gray-400 text-xl ">' . $match['date'] . '</p>
            <span> | </span>
            <p class="font-light text-center dark:text-gray-400 text-xl ">' . $match['start'] . '</p>
            </div>';

                        for ($i = 0; $i < count($match_detail); $i += 2) {
                            $score_1 = $match_detail[$i]['score'] ?? 0;
                            $score_2 = $match_detail[$i + 1]['score'] ?? 0;

                            if ($score_1 > $score_2) {
                                echo '<div class="flex justify-center items-center gap-2">
                    <p class="font-bold text-center dark:text-gray-400 text-3xl text-green-500">' . $score_1 . '</p>
                    <span>-</span>
                    <p class="font-bold text-center dark:text-gray-400 text-3xl text-gray-500">' . $score_2 . '</p>
                    </div>';
                            } else if ($score_1 < $score_2) {
                                echo '<div class="flex justify-center items-center gap-2">
                    <p class="font-bold text-center dark:text-gray-400 text-3xl text-green-500">' . $score_2 . '</p>
                    <span>-</span>
                    <p class="font-bold text-center dark:text-gray-400 text-3xl text-gray-500">' . $score_1 . '</p>
                    </div>';
                            } else {
                                echo '<div class="flex justify-center items-center gap-2">
                    <p class="font-bold text-center dark:text-gray-400 text-3xl text-gray-500">' . $score_1 . '</p>
                    <span>-</span>
                    <p class="font-bold text-center dark:text-gray-400 text-3xl text-gray-500">' . $score_2 . '</p>
                    </div>';
                            }
                        }
                        if($match['winner'] == null){
                            echo '<p class="font-bold text-center dark:text-gray-400 text-2xl text-gray-700 mt-3">Chưa có kết quả</p>';
                        }
                            else{
                        echo '<p class="font-bold text-center dark:text-red-300 text-2xl text-red-400 mt-3">' . $match['winner'] . '</p>';
                    }
                        echo '</a>';
                    }
                } ?>



            </div>
        </div>




    </div>
    <?php require_once("../include/footer.php") ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>