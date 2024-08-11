<?php

class Team extends Db
{

    public function getAllTeam()
    {
        $db = new DB;
        $query = "SELECT * FROM team";
        $params = array();
        $result = $db->select($query, $params);
        return $result;
    }

    public function existTeam($name)
    {
        $query = "SELECT COUNT(*) as total FROM team WHERE name = :name";
        $params = array(
            ":name" => $name
        );
        $result = $this->select($query, $params);
        if ($result[0]['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function filterTeam()
    {
        echo '<div class="overflow-x-auto shadow-md sm:rounded-lg">
    ';
        echo '<form class="max-w-xl mx-auto flex my-10 max-h-xl" action="team.php" method="get">
        <label for="id_team" class="block m-2 text-sm font-medium text-black dark:text-white w-52 ">Nhập đội muốn tìm</label>
        <input type="text" id="id_team" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="team_name" />
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tìm</button>
    </form>';
        echo '<div class="flex justify-center items-center py-5 h-full">';
        echo '<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="text-lg font-bold text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
        <tr>
            <th scope="col" class="px-1 py-3">
                ID
            </th>
            <th scope="col" class="px-1 py-3">
                Tên đội
            </th>
            <th scope="col" class="px-1 py-3">
                Số lượng
            </th>
            <th scope="col" class="px-1 py-3">

            </th>
            
        </tr>
    </thead>
    <tbody>';

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $db = new DB;
            $results_per_page = 10;

            // Determine the current page from the URL
            $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

            // Determine the starting position of the results for the SQL query
            $start_from = ($current_page - 1) * $results_per_page;

            $team_name = isset($_GET['team_name']) ? trim($_GET['team_name']) : '';
            if ($team_name !== '') {
                $query = "SELECT team.id, team.name, COUNT(customer.id) as quantity 
                      FROM team 
                      JOIN team_detail ON team.id = team_detail.team_id
                      LEFT JOIN customer ON team_detail.cus_id = customer.id
                      WHERE team.name LIKE :team_name
                      GROUP BY team.id
                      LIMIT $start_from, $results_per_page";
                $params = array(
                    ":team_name" => '%' . $team_name . '%',

                );
            } else {
                $query = "SELECT team.id, team.name, COUNT(customer.id) as quantity 
                FROM team 
                JOIN team_detail ON team.id = team_detail.team_id
                LEFT JOIN customer ON team_detail.cus_id = customer.id
                GROUP BY team.id
                      LIMIT $start_from, $results_per_page ";
                $params = array();
            }

            $teams = $db->select($query, $params);

            $index = 1;
            $limit = 18;
            foreach ($teams as $team) {

                echo '
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-1 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    ' . $index++ . '
                </th>
                <td class="px-1 py-4">
                    ' . htmlspecialchars($team['name']) . '
                </td>
                <td class="px-1 py-4">
                    ' . $team['quantity'] . ' /18
                </td>
                <td class="px-1 py-4">
                    ';
                if (!isset($_SESSION['team_id']) && $team['quantity'] < $limit) {
                
                        echo '<a href="register_team.php?team_id='.$team['id'].'" class="text-blue-500 font-bold">Tham gia</a>';
                    
                } else {
                    echo '';
                }
                echo '
                </td>
            </tr>';
            }

            echo '</tbody>
        </table>
        </div>
        </div>';

            // Get the total number of teams for pagination
            $count_query = "SELECT COUNT(DISTINCT team.id) AS total 
            FROM team 
            JOIN team_detail ON team.id = team_detail.team_id
            LEFT JOIN customer ON team_detail.cus_id = customer.id";
            $count_result = $db->select($count_query);
            $total_pages = ceil($count_result[0]['total'] / $results_per_page);

            // Display pagination links
            echo '<div class="pagination flex justify-end">';
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $current_page) {
                    echo '<strong class="text-xl">' . $i . '</strong> ';
                } else {
                    echo '<a class="text-xl" href="team.php?page=' . $i . '">' . $i . '</a> ';
                }
            }
            echo '</div>';
        }
    }

    public function getTeamOfTournamentById($id)
    {
       $query = "SELECT DISTINCT t.id as id, t.name as t_name, c.name as cus_name, c.phone, t.point,t.group from team t
       JOIN customer c on c.team_id = t.id
        where t.tournament_id = :id
        ORDER BY t.point DESC";
       $params = array(
                ":id"=>$id,
            );
         $result = $this->select($query,$params);
         if($result > 0){
             return $result;
         }else{
             return false;
         }
    }
     
    public function getTeamOfGroupTournamentById($id,$group){
        $query = "SELECT DISTINCT t.id as id, t.name as t_name, c.name as cus_name, c.phone, t.point, t.group from team t
       JOIN customer c on c.team_id = t.id
        where t.tournament_id = :id and t.group = :group
        ORDER BY t.point DESC";
       $params = array(
                ":id"=>$id,
                ":group"=>$group
            );
         $result = $this->select($query,$params);
         if($result > 0){
             return $result;
         }else{
             return false;
         }
    }

    public function getIdUser(){
        $email =$_SESSION['user'];
        $query = "SELECT customer.id FROM user join customer on user.id = customer.user_id WHERE email = :email";
        $params = array(":email"=>$email);
        $result = $this->select($query,$params)[0];
        return $result;
    }
}
