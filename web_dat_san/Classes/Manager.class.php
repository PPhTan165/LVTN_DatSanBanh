<?php

class Manager extends DB
{

    public function getAllManager()
    {
        $query = "SELECT * FROM manager join user on manager.user_id = user.id where deleted = 0";
        $result = $this->select($query);
        return $result;
    }

    public function getType()
    {
        $query = "SELECT * FROM type";
        $result = $this->select($query);
        return $result;
    }

    public function filterPage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $url = $_GET['url'] ?? "pitch";

            switch ($url) {
                case 'pitch':

                    $this->getAllPitch();

                    break;
                case 'booking':
                    $this->getAllBooking();
                    break;
                case 'price':
                    $this->getPrice();
                    break;
                
                case 'team':
                    # code...
                    break;
                case 'tournament':
                    # code...
                    break;
                case 'promotion':
                    # code...
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    public function getAllPitch($page = 1, $records_per_page = 10)
    {
        $total_query = "SELECT COUNT(*) as total FROM pitch";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);

        $query = "SELECT pitch.id as pitch_id, pitch.name as pitch_name, type.name as type_name,manager.name as mana_name,description FROM pitch 
                    join type on pitch.type_id = type.id
                    join manager on pitch.manager_id = manager.id
                    where deleted = 0 and manager_id = :manager_id";
        $params = array(
            ":manager_id" => $_SESSION['manager']
        );
        $result = $this->select($query,$params);

        echo '
        <div>
            <h2 class=" text-4xl font-bold text-center">Danh sách sân</h2>
            </div>
            
            <div class=" overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tên sân
                        </th>   
                        <th scope="col" class="px-6 py-3">
                            Mô tả
                        </th>   
                        <th scope="col" class="px-6 py-3">
                            Quản lý
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Loại sân
                        </th>
                        <th scope="col" class="px-6 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody>';
        $index = 1;
        foreach ($result as $team) {
            echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    ' . $index++ . '
                    </th>
                    <td class="px-6 py-4">
                    ' . $team['pitch_name'] . '
                    </td>
                    <td class="px-6 py-4">
                    ' . $team['description'] . '
                    </td>
                    <td class="px-6 py-4">
                    ' . $team['mana_name'] . '
                    </td>
                    <td class="px-6 py-4">
                    ' . $team['type_name'] . '
                    </td>

                    <td class="px-1-py-4">
                        <a href="update_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Update</a>
                        <a href="delete_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</a>
                    </td>

                    </tr>';
        }

        echo '</tbody>
                    </table>
        </div>
        ';

        $this->pagination($page, $total_pages, $_SERVER['PHP_SELF']);
    }

    public function createPitch()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name_pitch = $_POST['name_pitch'];
            $manager_post = $_POST['manager'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';

            $insert_pitch = "INSERT INTO pitch (name,deleted, description, manager_id, type_id) VALUES (:name_pitch,:deleted,:des,:manager_id,:type_id)";
            $pitch_arr = array(
                ":name_pitch" => $name_pitch,
                ":deleted" => 0,
                ":des" => $des,
                ":manager_id" => $manager_post,
                ":type_id" => $type_post
            );

            $result_pitch = $this->insert($insert_pitch, $pitch_arr);

            if ($result_pitch > 0) {
                $id_pitch = $this->getInsertId();
                for ($i = 1; $i < 19; $i++) {

                    $sql = "insert into pitch_detail (duration_id, pitch_id, price_id) values (:duration, :pitch, :price)";

                    if ($i < 5) {
                        if ($type_post == 1) {
                            $price = 1;
                        } else if ($type_post == 2) {
                            $price = 5;
                        } else {
                            $price = 9;
                        }
                    } else if ($i < 10) {
                        if ($type_post == 1) {
                            $price = 2;
                        } else if ($type_post == 2) {
                            $price = 6;
                        } else {
                            $price = 10;
                        }
                    } else if ($i < 13) {
                        if ($type_post == 1) {
                            $price = 3;
                        } else if ($type_post == 2) {
                            $price = 7;
                        } else {
                            $price = 11;
                        }
                    } else if ($i >= 13) {
                        if ($type_post == 1) {
                            $price = 4;
                        } else if ($type_post == 2) {
                            $price = 8;
                        } else {
                            $price = 12;
                        }
                    }

                    $arr = array(
                        ":duration" => $i,
                        ":pitch" => $id_pitch,
                        ":price" => $price
                    );

                    $result_pd = $this->insert($sql, $arr);
                }
                if ($result_pd > 0) {
                    echo 'tạo sân thành công <a href="index.php?url=pitch">Quay lại</a>';
                }
            }
        }


        $managers = $this->getAllManager();
        $types = $this->getType();

        echo '<form class="max-w-sm mx-auto" method="post" action="create_pitch.php">
        <div class="mb-5">
            <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
            <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required />
            </div>
            <div class="mb-5">
            
            <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
            <select id="manager" name="manager" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';
        foreach ($managers as $manager) {
            echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
        }

        echo '</select>
        </div>

        <div class="mb-5">

            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';
        foreach ($types as $type) {
            echo '<option value="' . $type['id'] . '">' . $type['name'] . '</option>';
        }
        echo '

            </select>
        </div>
        <div class="mb-5">
            <label for="descript" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chú thích</label>
            <textarea id="message" rows="4" name="descript" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
        </div>


        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
        </form>';
    }

    public function updatePitch()
    {
        $id = $_GET['pitch'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name_pitch = $_POST['name_pitch'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';

            $update_query = "UPDATE pitch SET name=:name, description=:descript, type_id=:type_id WHERE id=:id";

            $params = array(
                ":name" => $name_pitch,
                ":descript" => $des,
                ":type_id" => $type_post,
                ":id" => $id
            );

            $result = $this->update($update_query, $params);


            if ($result > 0) {
                echo "Cập nhật thành công";
            } else {
                echo "Không có thay đổi nào được thực hiện.";
            }
        }


        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $query = "SELECT * FROM pitch WHERE id = :id";
            $params = array(":id" => $id);
            $result = $this->select($query, $params);
            if ($result > 0) {

                $pitch = $result[0];
                $types = $this->getType();

                echo ' <h2 class="text-2xl font-bold mt-5 text-center">Cập nhật sân banh</h2>
                <form class="max-w-sm mx-auto" method="post" action="update_pitch.php?pitch=' . $id . '">
                <div class="mb-5">
                    <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                    <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $pitch['name'] . '" />
                    </div>
                    
        
                <div class="mb-5">
        
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
                
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        ';
                foreach ($types as $type) {
                    echo '<option value="' . $type['id'] . '">' . $type['name'] . '</option>';
                }
                echo '
        
                    </select>
                </div>
                <div class="mb-5">
                    <label for="descript" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chú thích</label>
                    <textarea id="message" rows="4" name="descript" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>

                <div class="flex justify-between">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cập nhật</button>
                <a href="index.php?url=pitch" class="font-medium text-blue-600 underline">Quay lại</a>
                </div>
                </form>';
            }
        }
    }

    public function getAllBooking($page = 1, $records_per_page = 10)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $offset = ($page - 1) * $records_per_page;

        // Truy vấn để lấy tổng số bản ghi
        $total_query = "SELECT COUNT(*) as total FROM booking";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);

        $query = "SELECT b.id b_id, b.date, total, c.name cus_name, phone, pitch.name p_name,status_id, d.start,d.end
        FROM booking b 
        JOIN customer c ON b.cus_id = c.id 
        JOIN pitch_detail pd ON b.pitch_detail_id = pd.id
        JOIN pitch on pd.pitch_id = pitch.id
       	JOIN duration d on d.id = pd.duration_id
        ";

        $result = $this->select($query);

        $index = 1;
        $currentDate = date("Y-m-d");
        $time_now = date("H");
        echo '
        <div>
        <h2 class=" text-4xl font-bold text-center mt-5">Danh sách đặt sân</h2>
        </div>
        
        <div class=" overflow-x-auto mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        STT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Người đặt
                    </th>   
                    <th scope="col" class="px-6 py-3">
                        SDT
                    </th>   
                    <th scope="col" class="px-6 py-3">
                        Tên sân
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Ngày đặt
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Giờ đặt
                    </th>
                    <th scope="col" class="px-6 py-3">
                    Tổng
                    </th>
                    <th scope="col" class="px-6 py-3">
                    Trạng thái
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
            </thead>
            <tbody>';
        foreach ($result as $booking) {

            $start = new DateTime($booking['start']);
            $start_format = $start->format("H:i");
            $start_h = $start->format("H");

            $end = new DateTime($booking['end']);
            $end_format = $end->format("H:i");

            $date = new DateTime($booking['date']);
            $date_format = $date->format("Y-m-d");

            $interval = (int)$start_h - (int)$time_now;
            echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ' . $index++ . '
                </th>

                <td class="px-6 py-4">
                ' . $booking['cus_name'] . '
                </td>

                <td class="px-6 py-4">
                ' . $booking['phone'] . '
                </td>

                <td class="px-6 py-4">
                ' . $booking['p_name'] . '
                </td>

                <td class="px-6 py-4">
                ' . $date_format . '
                </td>

                <td class="px-6 py-4">
                ' . $start_format  . '- ' . $end_format . '
                </td>

                <td class="px-6 py-4">
                ' . $booking['total'] . '
                </td>

                <td class="px-6 py-4 font-bold">
                ';
            switch ($booking['status_id']) {
                case 1:
                    echo '<div class="text-blue-500">CREATED</div>';
                    break;
                case 2:
                    echo '<div class="text-red-500">CANCEL</div>';
                    break;
                case 3:
                    echo '<div class="text-green-500">DONE</div>';
                    break;

                default:
                    break;
            }
            echo '</td>

                <td class="px-1-py-4">';
            if ($booking['status_id'] != 2) {

                if ($booking['status_id'] == 1) {

                    if ($currentDate <= $date_format) {

                        if ($interval < 2) {

                            echo '
                                <a href="done_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Done</a>
                                ';
                        } else {

                            echo '
                                <a href="done_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Done</a>
                                <a href="cancel_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Cancel</a>
                                ';
                        }
                    } else {

                        echo '
                                <a href="done_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Done</a>
                                ';
                    }
                }
            }
            echo '</td>

            </tr>';
        }

        echo '</tbody>
                </table>
        </div>
        ';

        $this->pagination($page, $total_pages, $_SERVER['PHP_SELF']);
    }

    public function getPrice()
    {
        $query = "SELECT * FROM type";
        $result = $this->select($query);

        $selected_type_id = $_GET['type'] ?? '';

        if ($result > 0) {
            echo '
                <div class="text-2xl font-bold text-center mt-5"><h2>Thông tin giá sân</h2></div>
                <form action="index.php" method="get"> 
                    <div class="mb-5 ">
                        <input type="hidden" name="url" value="price"/>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-96 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">';

            foreach ($result as $type) {
                $selected = ($type['id'] == $selected_type_id) ? 'selected' : '';
                echo '<option value="' . $type['id'] . '" ' . $selected . '>' . $type['name'] . '</option>';
            }
            echo '
                        </select>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Chọn</button>
                    </div>
                </form>';
        }

        $request_method = $_SERVER['REQUEST_METHOD'];
        $type_id = $_GET['type'] ?? '';
        switch ($request_method) {

            case 'GET':
                if (isset($_GET['type'])) {
                    $price_query = "SELECT price.id, price_per_hour, period.name p_name, type.name t_name FROM `price`
                        JOIN period ON price.period_id = period.id
                        JOIN type ON type.id = price.type_id
                        WHERE type_id = :type_id";

                    $price_arr = array(":type_id" => $type_id);
                    $price_result = $this->select($price_query, $price_arr);

                    if ($price_result > 0) {
                        echo '<div class="overflow-x-auto mt-5">
                        <form action="update_price.php" method="post">
                            <input type="hidden" name="type" value="' . $type_id . '"/>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead>
                                    <tr class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <th scope="col" class="px-6 py-3 text-center">Buổi</th>
                                        <th scope="col" class="px-6 py-3">Giá</th>
                                        <th scope="col" class="px-6 py-3"></th>   
                                    </tr>
                                </thead> 
                                <tbody>';
                        foreach ($price_result as $price) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="col" class="px-1 py-3 text-center">' . $price['p_name'] . '</td>
                                <td scope="col" class="px-1 py-3">
                                    <input
                                        type="number" 
                                        id="price_' . $price['id'] . '" 
                                        name="prices[' . $price['id'] . ']"
                                        value="' . $price['price_per_hour'] . '" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                </td>
                                <td scope="col" class="px-1 py-3">
                                    <button type="submit" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Update</button>
                                </td>
                            </tr>';
                        }

                        echo ' 
                        
                        </tbody>
                          </table>
                          </form>
                          </div>';
                    }
                }
                break;

            default:

                // Handle other request methods if needed
                break;
        }
    }

    public function getAllUser($page = 1, $records_per_page = 10)
    {

        $total_query = "SELECT COUNT(*) as total FROM pitch";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);

        $query = "SELECT manager.id, manager.name, manager.phone, user_id, deleted, email from manager 
                    join user on manager.user_id = user.id 
                    where user_id = user.id AND deleted = 0";
        $result = $this->select($query);
        if ($result > 0) {
            echo '
            <div>
                <h2 class=" text-4xl font-bold text-center">Danh sách nhân viên</h2>
                </div>
                <div class="flex justify-end">
                <a href="create_manager.php" class="me-5 font-bold text-lg text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tạo tài khoản quản lý</a>
                </div>
                <div class=" overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                STT
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tên nhân viên
                            </th>   
                            <th scope="col" class="px-6 py-3">
                                SDT
                            </th>   
                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>';
            $index = 1;
            foreach ($result as $manager) {
                echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ' . $index++ . '
                        </th>
                        <td class="px-6 py-4">
                        ' . $manager['name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $manager['phone'] . '
                        </td>
    
                        <td class="px-1-py-4">
                            <a href="update_manager.php?id=' . $manager['user_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Update</a>
                            <a href="delete_manager.php?id=' . $manager['user_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</a>
                        </td>
    
                        </tr>';
            }

            echo '</tbody>
                        </table>
            </div>
            ';

            $this->pagination($page, $total_pages, $_SERVER['PHP_SELF']);
        }
    }

    public function updateManager()
    {
        $id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['fname'];
            $phone = $_POST['phone'];
            if (!isValidVietnamPhoneNumber($phone)) {
                echo '<script>alert("Số điện thoại không hợp lệ)</script>';
            }


            $update_query = "UPDATE manager SET name=:name,phone=:phone WHERE user_id = :id";

            $params = array(
                ":name" => $name,
                ":phone" => $phone,
                ":id" => $id
            );

            $result = $this->update($update_query, $params);


            if ($result > 0) {
                echo "Cập nhật thành công";
            } else {
                echo "Không có thay đổi nào được thực hiện.";
            }
        }


        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $query = "SELECT * FROM manager WHERE user_id = :id";
            $params = array(":id" => $id);
            $result = $this->select($query, $params);
            var_dump($result);
            if ($result > 0) {

                $manager = $result[0];


                echo '<form class="max-w-sm mx-auto" method="post" action="update_manager.php?id=' . $id . '">
                <div class="mb-5">
                    <label for="fname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên nhân viên</label>
                    <input type="text" id="fname" name="fname" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $manager['name'] . '" />
                    </div>
                  
       
                    <div class="mb-5">
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SDT</label>
                    <input type="tel" id="phone" name="phone" maxlength = "10" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $manager['phone'] . '" />
                    </div>

                <div class="flex justify-between">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cập nhật thông tin</button>
                <a href="index.php?url=manager" class="font-medium text-blue-600 underline">Quay lại</a>
                </div>
                </form>';
            }
        }
    }

    public function createManager()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {




            // if ($result_pitch > 0) {
            // }


            $managers = $this->getAllManager();
            $types = $this->getType();

            echo '<form class="max-w-sm mx-auto" method="post" action="create_pitch.php">
        <div class="mb-5">
            <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
            <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required />
            </div>
            <div class="mb-5">
            
            <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
            <select id="manager" name="manager" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';
            foreach ($managers as $manager) {
                echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
            }

            echo '</select>
        </div>

        <div class="mb-5">

            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';
            foreach ($types as $type) {
                echo '<option value="' . $type['id'] . '">' . $type['name'] . '</option>';
            }
            echo '

            </select>
        </div>
        <div class="mb-5">
            <label for="descript" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chú thích</label>
            <textarea id="message" rows="4" name="descript" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
        </div>


        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
        </form>';
        }
    }
}
