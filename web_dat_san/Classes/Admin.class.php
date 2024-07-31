<?php

class Admin extends DB
{

    public function getAllManager()
    {
        $query = "select manager.id,manager.name,manager.phone,user.deleted from manager join user on manager.user_id = user.id where user.deleted=0";
        $result = $this->select($query);
        return $result;
    }

    public function getType()
    {
        $query = "SELECT * FROM type";
        $result = $this->select($query);
        return $result;
    }

    public function getTournamentById($id)
    {
        $query = "SELECT * FROM tournament where deleted = 0 and id = :id";
        $params = array(":id" => $id);
        $result = $this->select($query, $params)[0];
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
                case 'manager':
                    $this->getAllUser();
                    break;
                case 'team':
                    $this->getAllTeam();
                    break;
                case 'tournament':
                    $this->getAllTournament();
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

    public function getAllPitch()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 10;
        $total_query = "SELECT COUNT(*) as total FROM pitch";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);

        $query = "SELECT pitch.id as pitch_id, pitch.name as pitch_name, type.name as type_name,manager.name as mana_name,description FROM pitch 
                    join type on pitch.type_id = type.id
                    join manager on pitch.manager_id = manager.id
                    where deleted = 0";

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
        $url = 'index/php?url=pitch';
        $this->pagination($page, $total_pages, $url);
    }
    public function existPitch($name)
    {
        $query = "SELECT * FROM pitch where deleted = 0 and name like :name";
        $params = array(":name" => '%' . $name . '%');
        $result = $this->select($query, $params);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function createPitch()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name_pitch = $_POST['name_pitch'];
            $manager_post = $_POST['manager'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';
            if ($this->existPitch($name_pitch)) {
                echo '<script>alert("Tên sân đã tồn tại");
                window.location.href="index.php?url=pitch";
                </script>';
                exit();
            } else {


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
                        echo '<script>alert("Tạo sân thành công"); window.location.href = "index.php?url=pitch";</script>';
                    }
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
            $manager_post = $_POST['manager'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';

            $update_query = "UPDATE pitch SET name=:name, description=:descript, manager_id=:manager_id, type_id=:type_id WHERE id=:id";

            $params = array(
                ":name" => $name_pitch,
                ":descript" => $des,
                ":manager_id" => $manager_post,
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
                $managers = $this->getAllManager();
                $types = $this->getType();

                echo '<form class="max-w-sm mx-auto" method="post" action="update_pitch.php?pitch=' . $id . '">
                <div class="mb-5">
                    <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                    <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="' . $pitch['name'] . '" />
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

                <div class="flex justify-between">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
                <a href="index.php?url=pitch" class="font-medium text-blue-600 underline">Quay lại</a>
                </div>
                </form>';
            }
        }
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

        $query = "SELECT b.id b_id, b.date, total, c.name cus_name, c.phone, pitch.name p_name, status_id, d.start, d.end
              FROM booking b 
              JOIN customer c ON b.cus_id = c.id 
              JOIN pitch_detail pd ON b.pitch_detail_id = pd.id
              JOIN pitch on pd.pitch_id = pitch.id
              JOIN duration d on d.id = pd.duration_id
              LIMIT $offset, $records_per_page";
        $result = $this->select($query);

        $index = $offset + 1;  // Để số thứ tự bắt đầu từ vị trí đúng
        $currentDate = date("Y-m-d");
        $current_format = DateTime::createFromFormat('Y-m-d', $currentDate);

        $time_now = date("H");

        echo '
    <div>
        <h2 class="text-4xl font-bold text-center mt-5">Danh sách đặt sân</h2>
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
                     <td class="px-6 py-4">' . $booking['cus_name'] . '</td>
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
                                        <th scope="col" class="px-1 py-3 text-center text-base">Buổi</th>
                                        <th scope="col" class="px-1 py-3 text-center text-base">Thời gian</th>
                                        <th scope="col" class="px-3 py-3 text-center text-base">Giá</th>
                                        <th scope="col" class="px-3 py-3"></th>   
                                    </tr>
                                </thead> 
                                <tbody>';
                        $index = 1;
                        foreach ($price_result as $price) {

                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="col" class="px-1 py-3 text-center">' . $index++ . '</td>
                                <td scope="col" class="px-1 py-3 text-center font-semibold">' . strtoupper($price['p_name']) . '</td>
                                <td scope="col" class="px-1 py-3 text-center font-semibold">' . $price['p_time'] . '</td>
                                <td scope="col" class="px-3 py-3">
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

    public function getAllUser()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 10;
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

        $query = "SELECT t.id as id, t.name as t_name,t.start_day,t.end_day, m.name as m_name
        FROM tournament t
        JOIN manager m ON t.manager_id = m.id
        WHERE t.deleted = 0
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
                                Thời gian kết thúc
                            </th>
                              
                            <th scope="col" class="px-6 py-3">
                                Nhân viên Quản lý
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

                $end_date = new DateTime($tournament['end_day']);
                $formatted_end_date = $end_date->format('d/m/Y');

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
                        ' . $formatted_end_date . '
                        </td>
                        <td class="px-6 py-4">
                        ' . $tournament['m_name'] . '
                        </td>
                        <td class="px-6 py-4">';
                if ($currentDate > $tournament['end_day']) {
                    echo '<div class="text-sm font-semibold text-red-500">Kết thúc</div>';
                } else if ($currentDate < $tournament['start_day']) {
                    echo '<div class="text-sm font-semibold text-blue-500">Chưa bắt đầu</div>';
                } else {
                    echo '<div class="text-sm font-semibold text-green-500">Đang diễn ra</div>';
                }
                echo '</td>
                        <td class="px-1-py-4">';

                if ($currentDate > $tournament['end_day']) {
                    echo '<a href="detail_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Detail</a>';
                } else {

                    echo '<a href="detail_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Detail</a>';

                    if ($currentDate < $tournament['start_day']) {

                        echo '<a href="update_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Update</a>';
                    }

                    echo '<a href="delete_tournament.php?id=' . $tournament['id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</a>';
                }

                echo '</td>
                        </tr>';
            }
            echo '      </tbody>
                    </table>
                </div>';
        }
    }

    public function createTournament()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $start_day = $_POST['start_day'];
            $start_day_format = DateTime::createFromFormat('Y-m-d', $start_day);

            $end_day = $_POST['end_day'];
            $end_day_format = DateTime::createFromFormat('Y-m-d', $end_day);

            $manager = $_POST['manager'];

            if ($start_day_format > $end_day_format) {
                echo '<script>alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc")
                window.location.href = "create_tournament.php";
                </script>';
            }

            $query = "INSERT INTO tournament (name,deleted, start_day, end_day, manager_id) VALUES (:name,:deleted,:start_day,:end_day,:manager)";
            $params = array(
                ":name" => $name,
                ":deleted" => 0,
                ":start_day" => $start_day,
                ":end_day" => $end_day,
                ":manager" => $manager
            );

            $result = $this->insert($query, $params);

            if ($result) {
                echo '<script>
                        alert("Tournament created successfully!");
                        window.location.href = "index.php?url=tournament";
                    </script>';
            } else {
                echo '<script>alert("Failed to create tournament.");</script>';
            }
        }
        $managers = $this->getAllManager();
        $currentDate = date('Y-m-d');

        echo '<form class="max-w-sm mx-auto" method="post" action="create_tournament.php">
        <div class="mb-5">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên giải đấu</label>
            <input type="text" id="name" name="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"/>
            </div>

            <div class="mb-5 flex justify-between">
                <div>
                    <label for="start_day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày bắt đầu</label>
                    <input type="date" id="start_day" name="start_day" min="' . $currentDate . '" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
                </div>
                <div>
                    <label for="end_day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày kết thúc</label>
                    <input type="date" id="end_day" name="end_day" min="' . $currentDate . '" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
                </div>
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
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tạo giải đấu</button>
        </form>';
    }

    public function updateTournament()
    {
        $id = $_GET['id'];
        $managers = $this->getAllManager();
        $tournament = $this->getTournamentById($id);
        $currentDate = date('Y-m-d');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'];

            $start_day = $_POST['start_day'];
            $start_day_format = DateTime::createFromFormat('Y-m-d', $start_day);

            $end_day = $_POST['end_day'];
            $end_day_format = DateTime::createFromFormat('Y-m-d', $end_day);

            $manager = $_POST['manager'];

            //Kiểm tra ngày bắt dầu không lớn hơn ngày kết thúc
            if ($start_day_format > $end_day_format) {
                echo '<script>alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc")
                window.location.href = "update_tournament.php?id=' . $id . '";
                </script>';
            }

            $query = "UPDATE tournament SET name=:name, start_day=:start_day, end_day=:end_day, manager_id=:manager WHERE id = :id";
            $params = array(
                ":name" => $name,
                ":start_day" => $start_day,
                ":end_day" => $end_day,
                ":manager" => $manager,
                ":id" => $id
            );

            $result = $this->update($query, $params);

            if ($result) {
                echo '<script>
                        alert("Tournament update successfully!");
                        window.location.href = "index.php?url=tournament";
                    </script>';
            } else {
                echo '<script>alert("Failed to update tournament.");</script>';
            }
        }


        echo '<form class="max-w-sm mx-auto" method="post" action="update_tournament.php?id=' . $id . '">
        <div class="mb-5">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên giải đấu</label>
            <input type="text" id="name" name="name" value="' . $tournament['name'] . '" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"/>
            </div>

            <div class="mb-5 flex justify-between">
            <div>
                <label for="start_day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày bắt đầu</label>
                <input type="date" id="start_day" name="start_day" min="' . $currentDate . '" value="' . $tournament['start_day'] . '" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            </div>
            <div>
                <label for="end_day" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ngày kết thúc</label>
                <input type="date" id="end_day" name="end_day" min="' . $currentDate . '" value="' . $tournament['end_day'] . '" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            </div>
            </div>
            
            <div class="mb-5">
            
            <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
            <select id="manager" name="manager" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';

        foreach ($managers as $manager) {
            $selected = ($manager['id'] == $tournament['manager_id']) ? 'selected' : '';
            echo '<option value="' . $manager['id'] . '" ' . $selected . '>' . $manager['name'] . '</option>';
        }

        echo '</select>
        </div>
                <div class="">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cập nhật</button>
                </div>
                </form>';
    }




    public function historyMatchDetail($id)
    {
        $query = 'SELECT DISTINCT m.id, m.name, m.date, d.start, p.name as p_name FROM `match` m
                        JOIN pitch_detail pd ON m.pitch_detail_id = pd.id
                        JOIN pitch p ON pd.pitch_id = p.id
                        JOIN duration d on pd.duration_id = d.id 
                        WHERE tournament_id = :tour_id';
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
}
