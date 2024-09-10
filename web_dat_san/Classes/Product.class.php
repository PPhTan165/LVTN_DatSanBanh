<?php

class Product extends Db
{
    public function getSessionTeam($id)
    {
        $query = "SELECT * FROM customer where id = :id";
        $params = array(":id" => $id);
        $result = $this->select($query, $params);
        if ($result) {
            $_SESSION['team_id'] = $result[0]['team_id'];
        } else {
            return false;
        }
    }

    public function getDuration($id)
    {
        $query = "select id,end,start from duration where duration.id >= :duration_id";
        $params = array(":duration_id" => $id);
        $result = $this->select($query, $params);
        return $result;
    }

    public function getDurationStartById($id){
        $query = "select start from duration where duration.id = :duration_id";
        $params = array(":duration_id" => $id);
        $result = $this->select($query, $params);
        if($result){
            return $result[0]['start'];
        }
        return false;
    }

    public function existPromotion($name)
    {
        $query = "SELECT COUNT(*) FROM promotion WHERE name = :name";
        $params = array(
            ":name" => $name 
        );
        $result = $this->select($query, $params);

        if ($result[0]['COUNT(*)'] > 0) {
            return true;
        }
        return false;
    }

    public function expirePromotion($name)
    {
        $query = "SELECT * FROM promotion WHERE date_exp < :current_date AND name like :name";
        $params = array(
            ":current_date" => date("Y-m-d"),
            ":name" => "%" . $name . "%"
        );
        $result = $this->select($query, $params);

        if ($result) {
            return $result;
        }
        return false;
    }

    public function getAllTimeByPitch($id)
    {
        $query = "SELECT duration.id dur_id, start, end, pd.id as pd_id FROM duration 
        JOIN pitch_detail pd on duration.id = pd.duration_id
        JOIN pitch p on p.id = pd.pitch_id
        where pitch_id = :pitch_id";

        $params = array(
            ":pitch_id" => $id
        );

        $result = $this->select($query, $params);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function existBooking($date)
    {
        $query = "SELECT * FROM booking where date = :date";
        $params = array(":date" => $date);
        $result = $this->select($query, $params);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getPitchDetailByPitchId($id)
    {
        $query = "SELECT pitch_detail.id as pitch_detail_id, pitch_detail.pitch_id as pitch_id, name FROM pitch_detail 
                JOIN pitch ON pitch_id = pitch.id 
                WHERE pitch_detail.pitch_id = :id
                ";
        $params = array(":id" => $id);
        $result = $this->select($query, $params);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }


    //lấy thông tin chi tiết sân bằng thời gian chọn và id sân
    public function getPitch_detail($selectedTimes, $idPitch)
    {
        $pitch_detail = "SELECT * FROM pitch_detail 
                JOIN duration ON pitch_detail.duration_id = duration.id
                JOIN pitch ON pitch_detail.pitch_id = pitch.id
                JOIN price ON pitch_detail.price_id = price.id
                WHERE duration_id = :duration_id AND pitch_id = :pitch_id";

        $pitch_detail_arr = array(
            ":duration_id" => $selectedTimes,
            "pitch_id" => $idPitch
        );
        $result = $this->select($pitch_detail, $pitch_detail_arr);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getAllPitch()
    {
        $db = new DB;
        $query = "SELECT pitch.id as pitchid, type.id as typeid ,pitch.name as pitchname, description, type.name as typename FROM pitch JOIN type ON pitch.type_id = type.id WHERE deleted=0";
        $pitchs = $db->select($query);
        echo '<div class="grid grid-cols-4 gap-5 p-5 mb-5 py-6 bg-blue-900 ">';
        $currentDate = date("Y-m-d");

        foreach ($pitchs as $pitch) {
            $name = $pitch['pitchname'];
            $type = $pitch['typename'];
            $des = $pitch['description'];
            $idpitch = $pitch['pitchid'];
            echo '<a href="detail_pitch.php?id=' . $idpitch . '&date=' . $currentDate . '" class="flex flex-col items-center bg-white border-2 border-black rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 p-2">
                    <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="https://www.ledsaigon.net/upload/image/S%C3%82N%20BANH/z2362051233871_e70182af77a3215e0d8a3186d21ba199.jpg" alt="">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-800 dark:text-blue-100">Tên sân: ' . $name . '</h5>
                        <p class="mb-3 font-semibold text-gray-700 dark:text-gray-400 text-lg">Loại sân: ' . $type . '</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Mô tả: ' . $des . '</p>
                    </div>
                </a>';
        }
        echo '</div>';
    }

    public function filterPitch()
    {
        $db = new DB;
        $type_get = isset($_GET['type']) ? $_GET['type'] : '';
        $query = "SELECT * FROM type WHERE 1";
        $type_name = $db->select($query);
        if ($type_name) {

            echo '
            <form class="max-w-xl mx-auto flex my-10" action="index.php" method="GET">
            <label for="type" class="block m-2 text-sm font-medium text-black dark:text-white w-20 ">Loại sân:</label>
            <select id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="type">
            <option value=""' . ($type_get == '' ? ' selected' : '') . '>Chọn loại sân</option>';
            foreach ($type_name as $type) {
                echo '<option value="' . $type['name'] . '"' . ($type_get == $type['name'] ? ' selected' : '') . '>' . $type['name'] . '</option>';
            }
            echo '</select>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tìm</button>
        </form>';
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type'])) {
            $type_query = "SELECT pitch.id as pitchid, type.id as typeid ,pitch.name as pitchname, description, type.name as typename 
            FROM pitch 
            JOIN type ON pitch.type_id = type.id 
            WHERE type.name LIKE :type and deleted= :deleted";
            $type_arr = array(
                ":type" => '%' . $type_get . '%',
                ":deleted" => 0
            );
            $pitchs = $db->select($type_query, $type_arr);

            if (isset($pitchs)) {
                echo '<div class="grid grid-cols-4 gap-5 p-5 py-6 bg-blue-900 ">';
                $currentDate = date("Y-m-d");
                foreach ($pitchs as $pitch) {
                    $name = $pitch['pitchname'];
                    $type = $pitch['typeid'];
                    $type_name = $pitch['typename'];
                    $des = $pitch['description'];
                    $idpitch = $pitch['pitchid'];
                    echo '<a href="detail_pitch.php?id=' . $idpitch . '&date=' . $currentDate . '" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                             <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="https://www.ledsaigon.net/upload/image/S%C3%82N%20BANH/z2362051233871_e70182af77a3215e0d8a3186d21ba199.jpg" alt="">
                             <div class="flex flex-col justify-between p-4 leading-normal">
                                 <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tên sân: ' . $name . '</h5>
                                 <p class="mb-3 font-semibold text-gray-700 dark:text-gray-400 text-lg">Loại sân: ' . $type_name . '</p>
                                 <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Mô tả: ' . $des . '</p>
                             </div>
                         </a>';
                }
                echo '</div>';
            } else {
                $this->getAllPitch();
            }
        } else {
            $this->getAllPitch();
        }
    }


    public function getTime()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time_now = new DateTime();
        $currentDate = date('Y-m-d');

        if (isset($_GET['id']) && isset($_GET['date'])) {

            $idPitch = $_GET['id'];
            $date = $_GET['date'];

            $pitchs = $this->getPitchDetailByPitchId($idPitch);

            if ($pitchs) {

                echo '
                <form action="detail_pitch.php?id=' . $idPitch . '&" method="get" class="mt-5 w-full flex justify-center items-center">
                    <input type="hidden" name="id" value="' . $idPitch . '" />
                    <input type="date" id="dateInput" name="date" min="' . $currentDate . '" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' . $date . '" />
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ms-2 ">Chọn ngày</button>
                </form>
                ';
                echo '<div class="main flex justify-center items-center border-b w-full mt-5 mb-5 shadow-xl">';
                echo '<div class="text-center font-bold text-2xl">' . ($pitchs['name']) . ': </div>';

                echo '<form method="POST" action="detail_pitch.php?id=' . $pitchs['pitch_id'] . '&date=' . $date . '" id="timeForm">';
                echo '<div class="grid grid-cols-5 gap-5 mt-5 p-5">';
                echo '<input type="hidden" name="selected_time" id="selected_time">';
                echo '<input type="hidden" name="date" id="selected_time">';


                //lấy thông tin sân có được đặt chưa
                $booking_result = $this->ExistBooking($date);

                //lấy tất cả thời gian hoạt động của sân
                $duration_result = $this->getAllTimeByPitch($idPitch);

                foreach ($duration_result as $duration) {
                    $start = new Datetime($duration['start']);
                    $end = new DateTime($duration['end']);
                    $start_formatted = $start->format('H:i');
                    $end_formatted = $end->format('H:i');

                    $duration_id = $duration['dur_id'];
                    $pd_id = $duration['pd_id'];

                    $isBooked = false;
                    if ($booking_result) {
                        foreach ($booking_result as $booking) {
                            if ($booking['pitch_detail_id'] == $pd_id && $booking['status_id'] == 1) {
                                $isBooked = true;
                                break;
                            }
                        }
                    }


                    if ($isBooked) {
                        echo '<div class="text-white bg-red-500 font-medium rounded-lg text-sm px-2 py-5 me-2 mb-2">' . $start_formatted . ' - ' . $end_formatted . '</div>';
                    } else if ($date <= $currentDate && $time_now > $start) {
                        echo '<div class="text-white bg-gray-500 font-medium rounded-lg text-sm px-2 py-5 me-2 mb-2">' . $start_formatted . ' - ' . $end_formatted . '</div>';
                    } else {
                        echo '<button type="button" class="select-button text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" 
                                data-id="' . $duration_id . '" 
                                data-start="' . $start_formatted . '" 
                                data-end="' . $end_formatted . '">
                                ' . $start_formatted . ' - ' . $end_formatted . '
                                </button>';
                    }
                }

                echo '</div>';

                echo '
                    <div class="flex justify-between">
                        <div class="flex">
                            <div class="w-3 h-3 bg-red-500 mt-1.5 me-3 ms-3"></div><p>Sân được đặt</p>
                            <div class="w-3 h-3 bg-gray-500 mt-1.5 me-3 ms-3"></div><p>Không cho đặt sân</p>
                            <div class="w-3 h-3 bg-blue-500 mt-1.5 me-3 ms-3"></div><p>Sân được đặt</p>
                        </div>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 me-2 mb-2">Submit</button>
                    </div>';

                echo '</form>';
            } else {
                echo '<p>No pitch found.</p>';
            }
        }
    }
}
