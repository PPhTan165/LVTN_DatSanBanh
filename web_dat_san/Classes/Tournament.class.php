<?php
class tournament extends DB
{
    public function existMatchTour($id_tournament)
    {
        $query = "SELECT COUNT(*) FROM `match` WHERE tournament_id = :id";
        $params = array(
            ':id' => $id_tournament
        );
        $result = $this->select($query, $params);
        if ($result[0]['COUNT(*)'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getAllDurationByPitchId($id){
        $query = "SELECT pd.id,pd.pitch_id,d.id as d_id, d.start FROM pitch_detail pd
        JOIN duration d ON pd.duration_id = d.id
         WHERE pd.pitch_id = :id";
        $params = array(
            ':id' => $id
        );
        $result = $this->select($query, $params);
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function getInfoTournament($id_tournament)
    {
        $query = "SELECT * FROM tournament WHERE id = :id";
        $params = array(
            ':id' => $id_tournament
        );
        $result = $this->select($query, $params);
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getTeamWin($id){
        $query = 'SELECT * FROM `match` m 
        JOIN match_detail md ON md.match_id = m.id
        JOIN team t ON md.team_id = t.id
        WHERE md.isWinner = 1 and match_id = :id';
        $params = array(
            ':id' => $id
        );
        $result = $this->select($query, $params);
        if($result){
            return $result;
        }else{
            return false;
        } 
    }

    public function getInfoMatchByMatchId($id){
        $query = "SELECT md.id, score, team_id, match_id, isWinner, m.name as m_name, m.date, t.name as t_name, pd.id as pd_id, p.id as pitch_id, d.id as d_id, d.start FROM match_detail md 
        JOIN `match` m ON md.match_id = m.id
        JOIN team t ON md.team_id = t.id
        JOIN pitch_detail pd ON m.pitch_detail_id = pd.id
        JOIN pitch p ON pd.pitch_id = p.id
        JOIN duration d ON pd.duration_id = d.id
        where md.match_id = :id";
        $params = array(
            ':id' => $id
        );
        $result = $this->select($query, $params);
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function getAllTournament()
    {
        $query = "SELECT * FROM tournament";
        $result = $this->select($query);

        $currentDate = new datetime(date("Y-m-d"));
        $url = $_GET['url'] ?? '';

        if ($result == null) {
            echo '<div class="text-center mt-5">
            <h2 class="text-2xl font-bold">Không có giải đấu nào</h2></div>';
        } else {
            foreach ($result as $tournament) {
                $start = DateTime::createFromFormat('Y-m-d', $tournament['start_day']);
                $start_format = DateTime::createFromFormat('Y-m-d', $tournament['start_day'])->format('d/m/Y');
                $end = DateTime::createFromFormat('Y-m-d', $tournament['end_day']);
                $end_format = DateTime::createFromFormat('Y-m-d', $tournament['end_day'])->format('d/m/Y');

                echo '<div class=" max-w-xs max-h-lg p-6 h-60 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">

                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center overflow-hidden text-ellipsis whitespace-nowrap" style="max-height: 3em; line-height: 1.5em;">' . $tournament['name'] . '</h5>
                <p class="text-center font-semibold text-lg">' . $start_format . ' - ' . $end_format . '</p>';

                if ($currentDate >= $start && $currentDate <= $end) {
                    echo '<p class="font-semibold text-red-500 text-center text-lg mt-5">Đang diễn ra</p><br>';
                    echo '<a href="tournament_detail.php?id=' . $tournament['id'] . '" class="me-5 w-full inline-flex items-center px-3 py-3 text-sm font-medium justify-center text-blue-500 rounded-lg border hover:border-blue-700 focus:ring-4 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Xem kết quả
                    </a>';
                } else if ($currentDate < $start) {
                    echo '<p class="font-semibold text-blue-500 text-center text-lg mt-5">Sắp diễn ra</p><br>';
                    echo '<div class="flex justify-center mb-3">
                   <a href="tournament_detail.php?id=' . $tournament['id'] . '" class="me-5 inline-flex items-center px-3 py-3 text-sm font-medium text-center text-blue-500 rounded-lg border hover:border-blue-700 focus:ring-4 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Xem kết quả
                    </a>

                    <a href="tournament_register.php?id=' . $tournament['id'] . '" class="inline-flex items-center px-3 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Đăng ký
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                    </div>';
                } else {
                    echo '<p class="font-semibold text-green-500 text-center text-lg mt-5">Đã kết thúc</p><br>';
                    echo '<a href="tournament_detail.php?id=' . $tournament['id'] . '" class="me-5 w-full inline-flex items-center px-3 py-3 text-sm font-medium justify-center text-blue-500 rounded-lg border hover:border-blue-700 focus:ring-4 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Xem kết quả
                    </a>';
                }
                echo '
    
            </div>';
            }
        }
    }

    public function existTeam($name)
    {
        $query = "SELECT COUNT(*) FROM team WHERE name LIKE :name";
        $params = array(
            'name' => '%' . $name . '$'
        );
        $result = $this->select($query, $params);
        if ($result[0]['COUNT(*)'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function registerTournament($id_tournament)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit']) && isset($_POST['agree'])) {
                $name = $_POST['team'];
                $info = $_POST['message'];
                $existTeam = $this->existTeam($name);
                
                if ($existTeam) {

                    echo "<script>alert('Tên đội đã tồn tại')</script>";
                    exit();

                } else {

                    $query = "INSERT INTO team( name, deleted, info_team, tournament_id) VALUES (:name,0,:info,:tournament_id)";
                    $params = array(
                        ':name' => $name,
                        ':info' => $info,
                        ':tournament_id' => $id_tournament
                    );
                    $result = $this->insert($query, $params);
                    $team_id = $this->getInsertId();
                    if ($result) {
                        $update = "UPDATE customer SET team_id= :team_id WHERE id = :cus_id";
                        $cus_params = array(
                            ':team_id' => $team_id,
                            ':cus_id' => $_SESSION['cus_id']
                        );
                        $team_result = $this->update($update, $cus_params);
                        if ($team_result) {

                            echo "<script>alert('Đăng ký thành công')
                                window.location.href='tournament_detail.php?id=$id_tournament'
                                </script>";
                        } else {
                            echo "<script>alert('Đăng ký thất bại')</script>";
                        }
                    } else {
                        echo "<script>alert('Đăng ký thất bại')</script>";
                    }
                }
            }
        }

        echo ' <div class="flex justify-center items-center h-full mt-5">
        <form class="w-96 mx-auto bg-white p-5 rounded-xl shadow-xl" action="tournament_register.php?id='.$id_tournament.'" method="POST">
            <h2 class="text-center text-2xl font-bold whitespace-normal mb-5 ">Đơn đăng ký</h2>

            <div class="mb-5">
                <label for="team" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên đội</label>
                <input type="text" name="team" id="team" placeholder="Nguyễn Văn A" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
            </div>
            <div class="mb-5">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Thông tin đội</label>
                <textarea id="message" rows="10" name="message" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tên cầu thủ - số áo"></textarea>
            </div>            

            <div class="mb-5">
                <input type="checkbox" id="agree" name="agree" class="mr-2" required />
                <span class="text-sm text-blue-500"><a href="regulations.php" title="Đọc điều lệ chi tiết">Tôi đồng ý với điều lệ</a></span>
            </div>

            <div class="mb-5">
                <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </div>
        </form>

    </div>';
    }

  
}
