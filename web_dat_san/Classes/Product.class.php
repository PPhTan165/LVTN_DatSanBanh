<?php

class Product extends Db
{
    public function getAllPitch()
    {
        $db = new DB;
        $query = "SELECT pitch.id as pitchid, type.id as typeid ,pitch.name as pitchname, description, type.name as typename FROM pitch JOIN type ON pitch.type_id = type.id WHERE 1";
        $pitchs = $db->select($query);
        echo '<div class="grid grid-cols-4 gap-4">';
        $currentDate = date("Y-m-d");
        foreach ($pitchs as $pitch) {
            $name = $pitch['pitchname'];
            $type = $pitch['typename'];
            $des = $pitch['description'];
            $idpitch = $pitch['pitchid'];
            echo '<a href="detail_pitch.php?id=' . $idpitch . '&date=' . $currentDate . '" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="/docs/images/blog/image-4.jpg" alt="">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tên sân: ' . $name . '</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Loại sân: ' . $type . '</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Mô tả; ' . $des . '</p>
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
            $type_query = "SELECT pitch.id as pitchid, type.id as typeid ,pitch.name as pitchname, description, type.name as typename FROM pitch JOIN type ON pitch.type_id = type.id WHERE type.name LIKE :type";
            $type_arr = array(
                ":type" => '%' . $type_get . '%'
            );
            $pitchs = $db->select($type_query, $type_arr);

            if (isset($pitchs)) {
                echo '<div class="grid grid-cols-4 gap-4">';
                $currentDate = date("Y-m-d");
                foreach ($pitchs as $pitch) {
                    var_dump($currentDate);
                    $name = $pitch['pitchname'];
                    $type = $pitch['typeid'];
                    $type_name = $pitch['typename'];
                    $des = $pitch['description'];
                    $idpitch = $pitch['pitchid'];
                    echo '<a href="detail_pitch.php?id=' . $idpitch . '&date=' . $currentDate . '" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="/docs/images/blog/image-4.jpg" alt="">
                <div class="flex flex-col justify-between p-4 leading-normal">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tên sân: ' . $name . '</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Loại sân: ' . $type_name . '</p>
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
        $db = new DB;

        if (isset($_GET['id']) && isset($_GET['date'])) {
            $idpitch = $_GET['id'];
            $date = $_GET['date'];

            $pitch_query = "SELECT pitch_detail.id as pitch_detail_id, pitch_detail.pitch_id as pitch_id, name FROM pitch_detail
                JOIN pitch ON pitch_id = pitch.id 
                WHERE pitch_detail.pitch_id = :id";
            $pitch_arr = array(":id" => $idpitch);
            $pitchs = $db->select($pitch_query, $pitch_arr)[0];


            if ($pitchs) {

                echo '
                <form action="detail_pitch.php?id=' . $idpitch . '&" method="get" class="mt-5 w-full flex justify-center items-center">
                    <input type="hidden" name="id" value="' . $idpitch . '" />
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


                $booking_query = "SELECT * FROM booking where date = :date";
                $booking_arr = array(
                    ":date" => $date
                );

                $booking_result = $db->select($booking_query, $booking_arr);
                $duration_query = "SELECT duration.id dur_id, start, end ,pd.id as pd_id FROM duration JOIN pitch_detail pd on duration.id = pd.duration_id ";
                // $duration_arr = array(
                //     ":pd_id" => $booking_result['pitch_detail_id'],
                // );
                $duration_result = $db->select($duration_query);

                foreach ($duration_result as $duration) {
                    $start = new Datetime($duration['start']);
                    $end = new DateTime($duration['end']);
                    $start_formatted = $start->format('H:i');
                    $end_formatted = $end->format('H:i');
                    $duration_id = $duration['dur_id'];
                    $pd_id = $duration['pd_id'];

                    if ($booking_result == null) {
                        if ($date <= $currentDate && $time_now > $start) {
                            echo '<div class="text-white bg-gray-500 font-medium rounded-lg text-sm px-2 py-5 me-2 mb-2">' . $start_formatted . ' - ' . $end_formatted . '</div>';
                        } else {
                            echo '<button type="button" class="select-button text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" 
                            data-id="' . $duration_id . '" 
                            data-start="' . $start_formatted . '" 
                            data-end="' . $end_formatted . '">
                            ' . $start_formatted . ' - ' . $end_formatted . '
                            </button>';
                        }
                    } else  {
                        
                        if ($booking_result[0]['pitch_detail_id'] == $pd_id && $booking_result[0]['status_id']==1) {
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
                }
                echo '</div>';
                echo '
                <div class = "flex justify-between">
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
