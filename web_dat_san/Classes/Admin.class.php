<?php

class Admin extends DB
{
    public function existPitch($name)
    {
        $query = "SELECT * FROM pitch where name like :name";
        $params = array(":name" => '%' . $name . '%');
        $result = $this->select($query, $params);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function existPromotion($name)
    {
        $query = "SELECT * FROM promotion where name like :name";
        $params = array(":name" => '%' . $name . '%');
        $result = $this->select($query, $params);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getPromotionById($id)
    {
        $query = 'SELECT * FROM promotion WHERE id = :id';
        $params = array(":id" => $id);
        $result = $this->select($query, $params);
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getAllManager()
    {
        $query = "SELECT manager.id,manager.name,manager.phone,user.deleted from manager join user on manager.user_id = user.id where user.deleted=0";
        $result = $this->select($query);
        return $result;
    }

    public function getType()
    {
        $query = "SELECT * FROM type";
        $result = $this->select($query);
        return $result;
    }

    private function getAllStatus()
    {
        $query = "SELECT * FROM status";
        $result = $this->select($query);
        return $result;
    }

    public function getTournamentById($id)
    {
        $query = "SELECT * FROM tournament where id = :id";
        $params = array(":id" => $id);
        $result = $this->select($query, $params);
        if($result){
            return $result[0];
        }else{
                return false;
            }
    }
    public function getPitch()
    {
        $query = "SELECT * FROM pitch where deleted = 0";
        $result = $this->select($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getBookingById($id)
    {
        $query = "SELECT b.id, b.total,b.date,p.manager_id,p.type_id FROM booking b
        JOIN pitch_detail pd ON pd.id = b.pitch_detail_id
        JOIN pitch p ON p.id = pd.pitch_id 
        where b.id = :id";
        $params = array(":id" => $id);
        $result = $this->select($query, $params);
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
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
                case 'manager':
                    $this->getAllUser();
                    break;
                case 'revenue':
                    $this->getAllRevenue();
                    break;
                case 'tournament':
                    $this->getAllTournament();
                    break;
                case 'promotion':
                    $this->getAllPromotion();
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    public function getAllPitch()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 5;
        $offset = ($page - 1) * $records_per_page;

        $total_query = "SELECT COUNT(*) as total FROM pitch";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);

        $query = "SELECT pitch.id as pitch_id, pitch.name as pitch_name, type.name as type_name, manager.name as mana_name, description, deleted FROM pitch 
                    join type on pitch.type_id = type.id
                    join manager on pitch.manager_id = manager.id
                    ORDER BY pitch_id
                    LIMIT $offset, $records_per_page";

        $result = $this->select($query);

        echo '
        <div>
            <h2 class=" text-4xl font-bold text-center">Danh sách sân</h2>
            </div>
            <div class="flex justify-end">
            <a href="Create_pitch.php" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tạo thêm sân </a>
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
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody>';
        $index = $offset + 1;  // Để số thứ tự bắt đầu từ vị trí đúng
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
                    <td class="px-6 py-4">
                    ';
            if ($team['deleted'] == 0) {
                echo '<div class="text-green-500 font-bold">Hoạt động</div>';
            } else {
                echo '<div class="text-red-500 font-bold">Đã khoá</div>';
            }
            echo '
                    </td>

                    <td class="px-1-py-4">';
            if ($team['deleted'] == 0) {
                echo '
                        <a href="detail_pitch.php?pitch=' . $team['pitch_id'] . '&page='.$page.'" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Chi tiết</a>
                        <a href="update_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Cập nhật</a>
                        <a href="delete_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Khoá</a>
                    ';
            } else {
                echo '<a href="open_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Mở sân</a> ';
            }

            echo ' </td>

                    </tr>';
        }

        echo '</tbody>
                    </table>

                    
        </div>
        ';
        echo '<div class="flex justify-center mt-5">';
        echo '<nav aria-label="Page navigation example">';
        echo '<ul class="inline-flex items-center -space-x-px">';
        if ($page > 1) {
            echo '<li><a href="index.php?url=pitch&page=' . ($page - 1) . '" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<li><a href="index.php?url=pitch&page=' . $i . '" class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">' . $i . '</a></li>';
            } else {
                echo '<li><a href="index.php?url=pitch&page=' . $i . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            echo '<li><a href="index.php?url=pitch&page=' . ($page + 1) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a></li>';
        }
        echo '</ul>';
        echo '</nav>';
        echo '</div>';
    }

    public function getAllBooking()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 10;
        $offset = ($page - 1) * $records_per_page;

        // Truy vấn để lấy tổng số bản ghi
        $total_query = "SELECT COUNT(*) as total FROM booking";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);

        $_GET['name'] = $_GET['name'] ?? '';
        $_GET['phone'] = $_GET['phone'] ?? '';
        $_GET['pitch'] = $_GET['pitch'] ?? '';
        $_GET['status'] = $_GET['status'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['phone'])) {
            if ($_GET['phone'] !== '') {
                if (isValidVietnamPhoneNumber($_GET['phone']) == false) {
                    echo '<script>alert("Số điện thoại không hợp lệ");</script>';
                    $_GET['phone'] = '';
                }
            }
        }

        $query = "SELECT b.id as b_id, b.name as b_name, b.date, total, c.name cus_name, c.phone, p.name p_name, p.id as p_id, status_id, d.start, d.end
              FROM booking b 
              JOIN customer c ON b.cus_id = c.id 
              JOIN pitch_detail pd ON b.pitch_detail_id = pd.id
              JOIN pitch p on pd.pitch_id = p.id
              JOIN duration d on d.id = pd.duration_id
              WHERE b.name like :name and b.date like :date and p.id like :pitch and b.status_id like :status
              LIMIT $offset, $records_per_page";
        $params = array(
            ":name" => '%' . $_GET['name'] . '%',
            ":date" => '%' . $_GET['phone'] . '%',
            ":pitch" => '%' . $_GET['pitch'] . '%',
            ":status" => '%' . $_GET['status'] . '%'
        );
        $result = $this->select($query, $params);

        $index = $offset + 1;  // Để số thứ tự bắt đầu từ vị trí đúng
        $currentDate = date("Y-m-d");
        $current_format = DateTime::createFromFormat('Y-m-d', $currentDate);

        $time_now = date("H");

        $pitchs = $this->getPitch();
        $statuses = $this->getAllStatus();
        echo '
    <div>
        <h2 class="text-4xl font-bold text-center mt-5">Danh sách đặt sân</h2>
    </div>
    <div class="mb-5 mt-5">
        <form class="mb-5 flex justify-center items-center gap-5" method="get" action="index.php">
              <input type="hidden" name="url" value="booking">
            <div class="mb-5">
                <label for="input1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Tên người đặt</label>
                <input value="' . $_GET['name'] . '" type="text" id="input1" name="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Nhập tên người đặt" />
            </div>
            <div class="mb-5">
                <label for="input2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Số điện thoại</label>
                <input value="' . $_GET['phone'] . '" type="text" id="input2" name="phone" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Nhập số điện thoại " />
            </div>
            <div class="mb-5">
                <label for="input3" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sân đặt</label>
                <select id="input3" name="pitch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">All</option>';
        foreach ($pitchs as $pitch) {
            echo '<option value="' . $pitch['id'] . '"' . ($_GET['pitch'] == $pitch['id'] ? ' selected' : '') . '>' . $pitch['name'] . '</option>';
        }
        echo '
                </select>
            </div>
            <div class="mb-5">
                <label for="input3" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trạng thái</label>
                <select id="input3" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">All</option>';
        foreach ($statuses as $status) {
            echo '<option value="' . $status['id'] . '"' . ($_GET['status'] == $status['id'] ? ' selected' : '') . '>' . $status['name'] . '</option>';
        }
        echo '
                </select>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">tìm</button>
        </form>
    </div>
    <div class="overflow-x-auto mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">STT</th>
                    <th scope="col" class="px-6 py-3">Người đặt</th>
                    <th scope="col" class="px-6 py-3">SDT</th>
                    <th scope="col" class="px-6 py-3">Tên sân</th>
                    <th scope="col" class="px-6 py-3">Ngày đặt</th>
                    <th scope="col" class="px-6 py-3">Giờ đặt</th>
                    <th scope="col" class="px-6 py-3">Tổng</th>
                    <th scope="col" class="px-6 py-3">Trạng thái</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>';

        foreach ($result as $booking) {
            $start = new DateTime($booking['start']);
            $start_format = $start->format("H:i");
            $start_h = $start->format("H");

            $end = new DateTime($booking['end']);
            $end_format = $end->format("H:i");

            $date = DateTime::createFromFormat('Y-m-d', $booking['date']);
            $date_format = $date->format("Y-m-d");

            $interval = (int)$start_h - (int)$time_now;
            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                     <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $index++ . '</th>
                     <td class="px-6 py-4">' . $booking['b_name'] . '</td>
                     <td class="px-6 py-4">' . $booking['phone'] . '</td>
                     <td class="px-6 py-4">' . $booking['p_name'] . '</td>
                     <td class="px-6 py-4">' . $date_format . '</td>
                     <td class="px-6 py-4">' . $start_format . '- ' . $end_format . '</td>
                     <td class="px-6 py-4">' . $booking['total'] . '</td>
                     <td class="px-6 py-4 font-bold">';

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
        <td class="px-1 py-4">';
            if ($booking['status_id'] != 2) {
                if ($booking['status_id'] == 1) {
                    if ($current_format <= $date) {
                        if ($interval < 2 && $interval >= 0) {
                            echo '
                        <a href="done_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Done</a>';
                        } else {
                            echo '
                        <a href="done_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Done</a>
                        <a href="cancel_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Cancel</a>';
                        }
                    } else {
                        echo '
                    <a href="done_booking.php?booking=' . $booking['b_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Done</a>';
                    }
                }
            }
            echo '</td>
        </tr>';
        }

        echo '</tbody>
        </table>
    </div>';

        // Pagination
        echo '<div class="flex justify-center mt-5">';
        echo '<nav aria-label="Page navigation example">';
        echo '<ul class="inline-flex items-center -space-x-px">';
        if ($page > 1) {
            echo '<li><a href="index.php?url=booking&page=' . ($page - 1) . '" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<li><a href="index.php?url=booking&page=' . $i . '" class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">' . $i . '</a></li>';
            } else {
                echo '<li><a href="index.php?url=booking&page=' . $i . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            echo '<li><a href="index.php?url=booking&page=' . ($page + 1) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a></li>';
        }
        echo '</ul>';
        echo '</nav>';
        echo '</div>';
    }


    public function getPrice()
    {
        $query = "SELECT * FROM type";
        $result = $this->select($query);

        $selected_type_id = $_GET['type'] ?? '';

        if ($result > 0) {
            echo '
                <div class="text-2xl font-bold text-center mt-5">
                    <h2>GIÁ SÂN</h2>
                </div>
                <form action="index.php" method="get"> 
                    <div class="mb-5 flex items-center justify-center gap-3 mt-5">
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
                    $price_query = "SELECT price.id, price_per_hour, period.name p_name, type.name t_name, period.time_period as p_time FROM price
                        JOIN period ON price.period_id = period.id
                        JOIN type ON type.id = price.type_id
                        WHERE type_id = :type_id";

                    $price_arr = array(":type_id" => $type_id);
                    $price_result = $this->select($price_query, $price_arr);

                    if ($price_result > 0) {
                        echo '<div class="overflow-x-auto mt-5">
                        <form action="update_price.php" method="post >
                            <input type="hidden" name="type" value="' . $type_id . '"/>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead>
                                    <tr class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <th scope="col" class="px-1 py-3 text-center text-base">STT</th>
                                        <th scope="col" class="px-1 py-3 text-base">Buổi</th>
                                        <th scope="col" class="px-1 py-3 text-base">Thời gian</th>
                                        <th scope="col" class="px-3 py-3 text-center text-base">Giá</th>
                                        <th scope="col" class="px-3 py-3"></th>   
                                    </tr>
                                </thead> 
                                <tbody>';
                        $index = 1;
                        foreach ($price_result as $price) {

                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="col" class="px-1 py-3 text-center">' . $index++ . '</td>
                                <td scope="col" class="px-1 py-3 font-semibold">' . strtoupper($price['p_name']) . '</td>
                                <td scope="col" class="px-1 py-3 font-semibold">' . $price['p_time'] . '</td>
                                <td scope="col" class="px-3 py-3">
                                    <input
                                        type="number" 
                                        id="price_' . $price['id'] . '" 
                                        name="prices[' . $price['id'] . ']"
                                        value="' . $price['price_per_hour'] . '" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                </td>
                                <td scope="col" class="px-1 py-3">
                                    <button type="submit" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Cập nhật giá</button>
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

    public function getAllUser()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 10;
        $total_query = "SELECT COUNT(*) as total FROM manager";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);
        $offset = ($page - 1) * $records_per_page;
        $query = "SELECT manager.id, manager.name, manager.phone, user_id, deleted, email from manager 
                    join user on manager.user_id = user.id 
                    where user_id = user.id AND deleted = 0
                    LIMIT $offset, $records_per_page";
        $result = $this->select($query);
        if ($result > 0) {
            echo '
            <div>
                <h2 class=" text-4xl font-bold text-center">Danh sách nhân viên</h2>
                </div>
                <div class="flex justify-end">
                <a href="create_manager.php" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tạo tài khoản quản lý</a>
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
            $index = $offset + 1;  // Để số thứ tự bắt đầu từ vị trí đúng
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
                            <a href="update_manager.php?id=' . $manager['user_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Cập nhật nhân viên</a>
                            <a href="delete_manager.php?id=' . $manager['user_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Xoá nhân viên</a>
                        </td>
    
                        </tr>';
            }

            echo '</tbody>
                        </table>
            </div>
            ';

            // Pagination
            echo '<div class="flex justify-center mt-5">';
            echo '<nav aria-label="Page navigation example">';
            echo '<ul class="inline-flex items-center -space-x-px">';
            if ($page > 1) {
                echo '<li><a href="index.php?url=manager&page=' . ($page - 1) . '" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a></li>';
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<li><a href="index.php?url=manager&page=' . $i . '" class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">' . $i . '</a></li>';
                } else {
                    echo '<li><a href="index.php?url=manager&page=' . $i . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">' . $i . '</a></li>';
                }
            }

            if ($page < $total_pages) {
                echo '<li><a href="index.php?url=manager &page=' . ($page + 1) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a></li>';
            }
            echo '</ul>';
            echo '</nav>';
            echo '</div>';
        }
    }


    public function getAllTeam()
    {
        $query = "SELECT team.id id, team.name team_name, customer.name cus_name, customer.phone
                    FROM team 
                    join customer on team.id = customer.team_id 
                    where team.deleted = 0";
        $result = $this->select($query);
        if ($result > 0) {
            echo '

                <div class="mb-5">
                    <h2 class=" text-4xl font-bold text-center">Danh sách đội bóng</h2>
                </div>
                
                <div class=" overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                STT
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Tên đội
                            </th>   
                            
                            <th scope="col" class="px-6 py-3">
                                Đội trưởng
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Số điện thoại
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
                        ' . $team['team_name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $team['cus_name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $team['phone'] . '
                        </td>
                        <td class="px-1-py-4">
                            <a href="delete_team.php?id=' . $team['id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</a>
                        </td>
                        </tr>';
            }
            echo '</tbody>
                        </table>';
        }
    }

    public function getAllTournament()
    {

        $query = "SELECT t.id as id, t.name as t_name,t.start_day, m.name as m_name, type_tour_id as type,t.deleted
        FROM tournament t
        JOIN manager m ON t.manager_id = m.id
        
        ORDER BY t.start_day ASC
        ";
        $result = $this->select($query);
        if ($result > 0) {
            echo '

                <div class="mb-5">
                    <h2 class=" text-4xl font-bold text-center">Danh sách đội bóng</h2>
                </div>
                <div class="flex justify-end">
                    <a href="create_tournament.php" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tạo giải đấu</a>
                </div>
                <div class=" overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                STT
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Tên giải đấu 
                            </th>   
                            
                            <th scope="col" class="px-6 py-3">
                                Thời gian bắt đầu
                            </th>

                              
                            <th scope="col" class="px-6 py-3">
                                Nhân viên Quản lý
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Thể loại
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Trạng thái
                            </th>

                            <th scope="col" class="px-6 py-3">
                            
                            </th>
                        </tr>
                    </thead>
                    <tbody>';
            $index = 1;
            $currentDate = date('Y-m-d');
            foreach ($result as $tournament) {
                $start_date = new DateTime($tournament['start_day']);
                $formatted_start_date = $start_date->format('d/m/Y');

                echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ' . $index++ . '
                        </th>
                        <td class="px-6 py-4">
                        ' . $tournament['t_name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $formatted_start_date . '
                        </td>
                        
                        <td class="px-6 py-4">
                        ' . $tournament['m_name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ';
                if ($tournament['type'] == 1) {
                    echo '<div class="font-bold">Đá loại trực tiếp</div>';
                } else {
                    echo '<div class="font-bold">Vòng tròn tính điểm</div>';
                }
                echo '
                        </td>

                        <td class="px-6 py-4">';
                if ($tournament['deleted'] == 1) {
                    echo '<div class="text-sm font-semibold text-red-500">Kết thúc</div>';
                } else if ($currentDate < $tournament['start_day']) {
                    echo '<div class="text-sm font-semibold text-blue-500">Chưa bắt đầu</div>';
                } else {
                    echo '<div class="text-sm font-semibold text-green-500">Đang diễn ra</div>';
                }
                echo '</td>
                    
                <td class="px-1-py-4">';
                if($tournament['deleted'] == 0){
                    echo '<a href="detail_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Chi tiết giải</a>';
                    echo '<a href="update_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Cập nhật</a>';
                    echo '<a href="delete_tournament.php?id=' . $tournament['id'] . '"  onclick="return confirm(\'Bạn có chắc chắn muốn xóa giải này không?\')" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Khoá giải</a>';
                }
                else{
                    echo '<a href="open_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Mở giải</a>';

                }

                echo '</td>
                        </tr>';
            }
            echo '      </tbody>
                    </table>
                </div>';
        }
    }

    public function existTournament($name)
    {
        $query = "SELECT COUNT(*) FROM tournament WHERE name LIKE :name";
        $params = array(":name" => '%' . $name . '%');
        $result = $this->select($query, $params);
        if ($result[0]['COUNT(*)'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getTypeTour()
    {
        $query = "SELECT * FROM type_tour";
        $result = $this->select($query);
        return $result;
    }

    public function historyMatchDetail($id)
    {
        $query = 'SELECT DISTINCT m.id, m.name, m.date, d.start, p.name as p_name, t.point,m.winner FROM `match` m
                        JOIN pitch_detail pd ON m.pitch_detail_id = pd.id
                        JOIN pitch p ON pd.pitch_id = p.id
                        JOIN duration d on pd.duration_id = d.id 
                        JOIN match_detail md ON md.match_id = m.id
                        JOIN team t ON t.id = md.team_id
                        WHERE t.tournament_id = :tour_id
                        group by m.id
                        ORDER BY m.id ';
        $params = array(
            ":tour_id" => $id
        );
        $result = $this->select($query, $params);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function getTime()
    {
        $product = new Product();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $currentDate = date('Y-m-d');
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        

        if (isset($_GET['pitch'])) {

            $idPitch = $_GET['pitch'];
            $date = $_GET['date'] ?? $currentDate;
            $pitchs = $product->getPitchDetailByPitchId($idPitch);

            if ($pitchs) {

                echo '
                <form action="detail_pitch.php?pitch=' . $idPitch . '&page='.$page.'" method="get" class="mt-5 w-full flex justify-center items-center">
                    <input type="hidden" name="pitch" value="' . $idPitch . '" />
                    <input type="date" id="dateInput" name="date"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' . $date . '" />
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ms-2 ">Chọn ngày</button>
                </form>
                ';
                echo '<div class="main flex justify-center items-center border-b w-full mt-5 mb-5 shadow-xl">';
                echo '<div class="text-center font-bold text-2xl">' . ($pitchs['name']) . ': </div>';

                echo '<form method="POST" action="detail_pitch.php?pitch=' . $pitchs['pitch_id'] . '&date=' . $date . '" id="timeForm">';
                echo '<div class="grid grid-cols-5 gap-5 mt-5 p-5">';
                echo '<input type="hidden" name="selected_time" id="selected_time">';
                echo '<input type="hidden" name="date" id="selected_time">';


                //lấy thông tin sân có được đặt chưa
                $booking_result = $product->existBooking($date);

                //lấy tất cả thời gian hoạt động của sân
                $duration_result = $product->getAllTimeByPitch($idPitch);

                foreach ($duration_result as $duration) {
                    $start = new Datetime($duration['start']);
                    $end = new DateTime($duration['end']);
                    $start_formatted = $start->format('H:i');
                    $end_formatted = $end->format('H:i');

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
                    } else {
                        echo '<div class="text-white bg-blue-500 font-medium rounded-lg text-sm px-2 py-5 me-2 mb-2">' . $start_formatted . ' - ' . $end_formatted . '</div>';
                    }
                }
            }

            echo '</div>';

            echo '
                    <div class="flex justify-between">
                        <div class="flex">
                            <div class="w-3 h-3 bg-red-500 mt-1.5 me-3 ms-3"></div><p>Sân đã được đặt</p>
                            <div class="w-3 h-3 bg-blue-500 mt-1.5 me-3 ms-3"></div><p>Sân chưa đặt</p>
                        </div>
                    </div>';

            echo '</form>';
        } else {
            echo '<p>No pitch found.</p>';
        }
    }

    public function getAllRevenue()
    {

        $date = $_GET['date'] ?? '';
        $type = $_GET['type'] ?? '';
        $manager = $_GET['manager'] ?? '';
        $pitch = $_GET['pitch'] ?? '';
        $month = $_GET['month'] ?? '';

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 10;
        $total_query = "SELECT COUNT(*) as total FROM booking
        group by date";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);
        $offset = ($page - 1) * $records_per_page;

        $query = 'SELECT b.date,t.name as t_name,p.name as p_name,m.name as m_name, SUM(total) as total FROM `booking` b
		JOIN pitch_detail pd ON pd.id = b.pitch_detail_id
		JOIN pitch p ON p.id = pd.pitch_id
 		JOIN type t ON p.type_id = t.id
        JOIN manager m ON m.id = p.manager_id 
   		WHERE b.status_id = 3 and b.date like :date and t.id like :type and m.id like :manager and p.name like :pitch and MONTH(b.date) like :month
        GROUP BY date
        ORDER BY b.date DESC
        ';
        $params = array(
            ":date" => '%' . $date . '%',
            ":type" => '%' . $type . '%',
            ":manager" => '%' . $manager . '%',
            ":pitch" => '%' . $pitch . '%',
            ":month" => '%' . $month . '%'
        );
        $result = $this->select($query, $params);
        $type = $this->getType();
        $manager = $this->getAllManager();
        if ($result > 0) {
            echo '
                <div class="mb-5">
                    <h2 class=" text-4xl font-bold text-center">Danh sách doanh thu</h2>
                </div>
                <form action="index.php" method="GET" class="flex justify-center items-center mt-5 gap-5">
                    <input type="hidden" name="url" value="revenue"/>
                    
                    <input type="date" name="date" placeholder="Ngày" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <select name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Tháng</option>';
                for ($m = 1; $m <= 12; $m++)  {
                    echo '<option value="' . $m . '">Tháng ' . $m . '</option>';
                }

                echo ' </select> 
                
                <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Loại sân</option>';
                foreach ($type as $t) {
                    echo '<option value="' . $t['id'] . '">' . $t['name'] . '</option>';
                }

                echo ' </select>
                    <select name="manager" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Quản lý</option>';
                foreach ($manager as $m) {
                    echo '<option value="' . $m['id'] . '">' . $m['name'] . '</option>';
                }

                echo '</select>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ms-2">Tìm kiếm</button>
                </form>
                <div class=" overflow-x-auto p-5 mt-5">
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
                                    Ngày
                                </th>

                                <th scope="col" class="px-6 py-3">
                                Quản lý
                                </th>
                                
                                <th scope="col" class="px-6 py-3">
                                Loại sân
                                </th>
                                
                                <th scope="col" class="px-6 py-3">
                                    Doanh thu
                                </th>
                        </tr>
                    </thead>
                    <tbody>';
                $index = $offset + 1;
                foreach ($result as $revenue) {
                    echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ' . $index++ . '
                        </th>
                        <td class="px-6 py-4">
                        ' . $revenue['p_name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $revenue['date'] . '
                        </td>
                       
                        <td class="px-6 py-4">
                        ' . $revenue['m_name'] . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $revenue['t_name'] . '
                        </td>
                        <td class="px-6 py-4 font-bold text-base">
                        ' . number_format($revenue['total'], 3) . ' VNĐ
                        </td>
                        
                        </tr>
                        ';
                }
                echo '</tbody>
            </table>';
            }

            echo '<div class="flex justify-center mt-5">';
            echo '<nav aria-label="Page navigation example">';
            echo '<ul class="inline-flex items-center -space-x-px">';
            if ($page > 1) {
                echo '<li><a href="index.php?url=revenue&page=' . ($page - 1) . '" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a></li>';
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<li><a href="index.php?url=revenue&page=' . $i . '" class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">' . $i . '</a></li>';
                } else {
                    echo '<li><a href="index.php?url=revenue&page=' . $i . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">' . $i . '</a></li>';
                }
            }

            if ($page < $total_pages) {
                echo '<li><a href="index.php?url=revenue&page=' . ($page + 1) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a></li>';
            }
            echo '</ul>';
            echo '</nav>';
            echo '</div>';
        }
    
    public function getAllPromotion()
    {

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 10;
        $total_query = "SELECT COUNT(*) as total FROM promotion 
        where deleted = 0";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);
        $offset = ($page - 1) * $records_per_page;

        $query = "SELECT * FROM promotion where deleted = 0
        LIMIT $offset, $records_per_page";
        $result = $this->select($query);

        if ($result) {
            echo '<div class="mb-5">
                    <h2 class="text-4xl font-bold text-center mb-5">Danh sách khuyến mãi</h2>
                  </div>
                    <div class="flex justify-end">
                        <a href="create_promotion.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tạo khuyến mãi</a>
                    </div>
                  <div class="overflow-x-auto p-5 mt-5">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                          <th scope="col" class="px-6 py-3">
                            STT
                          </th>
                          <th scope="col" class="px-6 py-3">
                            Tên khuyến mãi
                          </th>
                          <th scope="col" class="px-6 py-3">
                            Hạn sử dụng
                          </th>
                          <th scope="col" class="px-6 py-3">
                            Discount (%)
                          </th>
                          <th scope="col" class="px-6 py-3">
                            Tối đa nhận  
                          </th>
                          <th scope="col" class="px-6 py-3">
                            </th>
                        </tr>
                      </thead>
                      <tbody>';
            $index = $offset + 1;
            foreach ($result as $promotion) {
                echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          ' . $index++ . '
                        </th>
                        <td class="px-6 py-4">
                          ' . $promotion['name'] . '
                        </td>
                        <td class="px-6 py-4">
                          ' . $promotion['date_exp'] . '
                        </td>
                        <td class="px-6 py-4">
                          ' . $promotion['discount'] . ' %
                        </td>

                        <td class="px-6 py-4">
                          ' . $promotion['max_get'] . ' VNĐ
                        </td>

                        <td class="px-6 py-4">
                            <a href="update_promotion.php?id=' . $promotion['id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Cập nhật</a>
                            <a href="delete_promotion.php?id=' . $promotion['id'] . '"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                            onclick="return confirm(\'Bạn có chắc chắn muốn xóa mã này không?\')">Xoá mã</a>
                        </td>

                      </tr>';
            }
            echo '</tbody>
                  </table>
                </div>';
        }

        echo '<div class="flex justify-center mt-5">';
        echo '<nav aria-label="Page navigation example">';
        echo '<ul class="inline-flex items-center -space-x-px">';
        if ($page > 1) {
            echo '<li><a href="index.php?url=promotion&page=' . ($page - 1) . '" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<li><a href="index.php?url=promotion&page=' . $i . '" class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">' . $i . '</a></li>';
            } else {
                echo '<li><a href="index.php?url=promotion&page=' . $i . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            echo '<li><a href="index.php?url=promotion&page=' . ($page + 1) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a></li>';
        }
        echo '</ul>';
        echo '</nav>';
        echo '</div>';
    }
}
