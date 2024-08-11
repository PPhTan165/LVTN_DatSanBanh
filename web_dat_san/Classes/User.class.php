<?php

class User extends DB
{

    public function getSessionCusId($email)
    {
        $query = "SELECT customer.id from user join customer on user.id = customer.user_id where email = :email";
        $params = array(":email" => $email);
        $result = $this->select($query, $params);
        return $result[0]['id'];
    }

    public function getCustomerById($id)
    {
        $query = "SELECT * FROM customer WHERE id = :id";
        $params = array(":id" => $id);
        $result = $this->select($query, $params);
        return $result;
    }

    public function getUserByID($email)
    {
        $query = "SELECT * FROM user WHERE email = :email";
        $params = array(":email" => $email);
        $result = $this->select($query, $params);
        return $result;
    }

    public function resetPassword($email, $newPassword)
    {
        $query = "UPDATE user SET password = :newPassword WHERE email = :email";
        $params = array(
            ":newPassword" => md5($newPassword),
            ":email" => $email
        );
        $result = $this->update($query, $params);
        return $result;
    }

    public function getPromotion($id)
    {   
        $promotion_query = "SELECT pr.id, pr.name, pr.discount, date_exp, discount, max_get FROM promotion pr
        join booking b on pr.id = b.promotion_id
        WHERE b.id = :id";
        $promotion_params = array(":id" => $id);
        $result = $this->select($promotion_query, $promotion_params);
        if (!isset($result))
            return 0;
        return $result;
    }

    public function getIdBooked()
    {
        $query = "SELECT b.id
                FROM booking b
                WHERE b.date_created IN (
                        SELECT date_created
                        FROM booking
                        WHERE cus_id = :cus_id
                        GROUP BY date_created
                        HAVING COUNT(*) > 1
                        )";
        $params = array(":cus_id" => $_SESSION['cus_id']);
        $result = $this->select($query, $params);
        if ($result) {
            return $result[0]['id'];
        } else {
            return false;
        }
    }

    public function getInvoice()
    {
        $cus_id = $_SESSION['cus_id'];
        $query = "SELECT b1.id, b1.name, b1.phone, b1.date, p.name AS p_name, b1.total, d.start, d.end, b1.status_id, b1.promotion_id, pr.price_per_hour
                    FROM booking b1
                    JOIN pitch_detail pd ON pd.id = b1.pitch_detail_id
                    JOIN pitch p ON pd.pitch_id = p.id
                    JOIN price pr ON pd.price_id = pr.id
                    JOIN duration d ON pd.duration_id = d.id
                    WHERE b1.cus_id = 1 AND b1.date_created IN (
                        SELECT date_created
                        FROM booking
                        WHERE cus_id = :cus_id
                        GROUP BY date_created
                        HAVING COUNT(*) > 1
                        )
                    ";
        $params = array(
            ":cus_id" => $cus_id
        );
        $result = $this->select($query, $params);
        if ($result == null) {
            echo '<script>alert("Không tìm thấy thông tin")
            window.location.href="history.php"</script>';
        } else {
            return $result;
        }
    }

    public function getPriceByPitchDetailId($id)
    {
        $query = "SELECT SUM(pr.price_per_hour) as total_price
        FROM (
            SELECT b1.id, b1.name, b1.date, b1.pitch_detail_id
            FROM booking b1
            JOIN booking b2 ON b1.name = b2.name AND b1.date = b2.date AND b1.pitch_detail_id <> b2.pitch_detail_id
            WHERE b1.cus_id = :cus_id
            ORDER BY b1.name, b1.date
        ) AS dup_booking
        JOIN booking b ON b.id = dup_booking.id
        JOIN pitch_detail pd ON b.pitch_detail_id = pd.id
        JOIN price pr ON pd.price_id = pr.id
        ORDER BY b.name, b.date;
        ";
        $params = array(":cus_id" => $id);
        $result = $this->select($query, $params);
        return $result[0]['total_price'];
    }

    public function registerUser()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit'])) {

                $email = $_POST['email'];
                $fullname = $_POST['fullname'];
                $phone = $_POST['phonenumber'];
                $password = $_POST['password'];
                $cfm_password = $_POST['cfmpassword'];

                //check phone
                if (isValidVietnamPhoneNumber($phone) == false) {
                    echo "<script>alert('Số điện thoại không hợp lệ')</script>";
                    exit();
                }

                //check khoảng trắng
                if (strpos($password, ' ') !== false) {
                    echo "<script>alert('Mật khẩu không được chứa khoảng trắng')</script>";
                    exit();
                }

                //check password
                if (isValidPassword($password) == false) {
                    echo "<script>alert('Mật khẩu phải chứa ít nhất 6 ký tự, 1 chữ hoa, 1 chữ thường và 1 số')</script>";
                    exit();
                }

                //check password and confirm password
                if ($password != $cfm_password) {
                    echo "<script>alert('Mật khẩu và xác nhận mật khẩu không trùng khớp')</script>";
                    exit();
                }

                $password_encrypt = md5($password);
                $sql = "SELECT COUNT(id) FROM user WHERE email = :email";
                $arr = array(":email" => $email);
                $count_user = $this->select($sql, $arr)[0];

                if ($count_user['COUNT(id)'] > 0) {

                    echo "<script>alert('Email đã tồn tại')</script>";
                } else {

                    $insert_query = "INSERT INTO user(deleted, email, password, role_id) VALUES (0, :email, :password, 3)";
                    $insert_data = array(
                        ':email' => $email,
                        ':password' => $password_encrypt,
                    );

                    if (empty($email) || empty($fullname) || empty($password) || empty($cfm_password)) {

                        echo "<script>alert('Vui lòng điền đầy đủ thông tin.')</script>";
                        exit();
                    } else {

                        $insert_result = $this->insert($insert_query, $insert_data);
                        $user_id = $this->getInsertId();

                        if ($insert_result > 0) {

                            $insert_cus_query = "INSERT INTO customer(name, phone, user_id) VALUES (:name, :phonenumber, :user_id)";
                            $cus_arr = array(
                                ':name' => $fullname,
                                ':phonenumber' => $phone,
                                ':user_id' => $user_id
                            );
                            $result_cus = $this->insert($insert_cus_query, $cus_arr);

                            if ($result_cus) {

                                echo '<script>
                                alert("Đăng ký thành công")
                                window.location.href = "login.php";
                                </script>';
                            } else {
                                echo '<script>alert("Đăng ký thất bại")</script>';
                            }
                        }
                    }
                }
            }
        }
    }



    public function historyBooked()
    {   
        $page = isset($_GET['page']) ? $_GET['page'] : 1; 
        $records_per_page = 10;
        $offset = ($page - 1) * $records_per_page;
        
        $currentDate = date("Y-m-d");
        $current_format = DateTime::createFromFormat('Y-m-d', $currentDate);

        $total_query = "SELECT COUNT(*) as total FROM booking";
        $total_result = $this->select($total_query);
        $total_records = $total_result[0]['total'];
        $total_pages = ceil($total_records / $records_per_page);


        $query = "SELECT b.id, b.name, b.date, p.name AS p_name, b.total, d.start, b.status_id 
                    FROM booking b
                    JOIN pitch_detail pd ON pd.id = b.pitch_detail_id
                    JOIN pitch p ON pd.pitch_id = p.id
                    JOIN duration d ON pd.duration_id = d.id
                    WHERE b.cus_id = :cus_id
                    order by b.date desc
                    limit $offset, $records_per_page";

        $params = array(":cus_id" => $_SESSION['cus_id']);
        $histories = $this->select($query, $params);
        if ($histories == null) {
            echo '<div class="text-center mt-5">
            <h2 class="text-2xl font-bold">Bạn chưa đặt sân nào</h2>';
        } else {

            $index = $offset + 1;
            echo '<div class="history">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
            <th scope="col" class="px-6 py-3">
            STT
            </th>
            <th scope="col" class="px-6 py-3">
            Tên người đặt
            </th>
            <th scope="col" class="px-6 py-3">
            Ngày đặt
            </th>
            <th scope="col" class="px-6 py-3">
            Tên sân
            </th>
            
            <th scope="col" class="px-6 py-3">
            Tổng tiền
            </th>
            <th scope="col" class="px-6 py-3">
            trạng thái
            </th>
            <th scope="col" class="px-6 py-3">
            
            </th>
            </tr>
            </thead>
            
            <tbody>';
            foreach ($histories as $history) {
                $date = DateTime::createFromFormat('Y-m-d', $history['date']);
                $date_format = $date->format('d-m-Y');
                $time_now = date("H");

                $start = new DateTime($history['start']);
                $start_h = $start->format("H");

                $interval = (int)$start_h - (int)$time_now;
                echo '            
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ' . $index++ . '
                </th>
                <td class="px-6 py-4">
                ' . $history['name'] . '
                </td>
                <td class="px-6 py-4">
                ' . $date_format . '
                </td>
                
                <td class="px-6 py-4">
                ' . $history['p_name'] . '
                </td>
                
                <td class="px-6 py-4">
                ' . $history['total'] . ' VNĐ
                </td>
                <td class="px-6 py-4">
                ';
                if ($history['status_id'] == 1) {

                    echo '<p class="font-bold text-blue-500">Chưa thanh toán</p>';
                } else if ($history['status_id'] == 2) {

                    echo '<p class="font-bold text-red-500">Đã hủy</p>';
                } else {

                    echo '<p class="font-bold text-green-500">Đã thanh toán</p>';
                }
                echo '</td>
                <td class="px-1-py-4">';
                if ($history['status_id'] != 2) {

                    if ($history['status_id'] == 1 || $history['status_id'] == 3) {

                        if ($current_format <= $date) {

                            if ($interval < 2) {

                                echo '
                                <a href="detail_booked.php?id=' . $history['id'] . '" class="w-full focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Chi tiết</a>
                                ';
                            } else {

                                echo '
                                <a href="detail_booked.php?id=' . $history['id'] . '" class="focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Chi tiết</a>
                                <a href="cancel_booked.php?id=' . $history['id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Cancel</a> ';
                            }
                        } else {

                            echo '
                            <a href="detail_booked.php?id=' . $history['id'] . '" class="w-full focus:outline-none text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">Chi tiết</a>
                            ';
                        }
                    }
                }
                echo '</td>';
            }
            echo '</tbody>
            </table>';
           
            echo '</div>';

            //pagination
            echo '<div class="flex justify-center mt-5">';
            echo '<nav aria-label="Page navigation example">';
            echo '<ul class="inline-flex items-center -space-x-px">';
            if ($page > 1) {
                echo '<li><a href="history.php&page=' . ($page - 1) . '" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a></li>';
            }
    
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<li><a href="history.php?page=' . $i . '" class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">' . $i . '</a></li>';
                } else {
                    echo '<li><a href="history.php?page=' . $i . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">' . $i . '</a></li>';
                }
            }
    
            if ($page < $total_pages) {
                echo '<li><a href="history.php?page=' . ($page + 1) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a></li>';
            }
            echo '</ul>';
            echo '</nav>';
            echo '</div>';
        }
    }

    public function getInfoBooked($id)
    {

        $cus_id = $_SESSION['cus_id'];
        $query = "SELECT b.id, b.name,b.phone, b.date, p.name AS p_name, b.total, d.start, d.end, b.status_id,b.promotion_id, pr.price_per_hour
                    from booking b
                    join pitch_detail pd on pd.id = b.pitch_detail_id
                    join pitch p on pd.pitch_id = p.id
                    join price pr on pd.price_id = pr.id
                    join duration d on pd.duration_id = d.id
                    where b.id = :id and b.cus_id = :cus_id";
        $params = array(
            ":id" => $id,
            ":cus_id" => $cus_id
        );
        $result = $this->select($query, $params);

        $promotion = $this->getPromotion($id);

        $date_format = DateTime::createFromFormat('Y-m-d', $result[0]['date'])->format('d-m-Y');
        $discount = 0;
        if ($promotion != null) {
            $discount = $promotion[0]['discount'];
        } else {
            $discount = 0;
        }
        if ($result == null) {
            echo '<script>alert("Không tìm thấy thông tin")
            window.location.href="history.php"</script>';
        } else {

            $booked = $result[0];
            echo '<div class="w-full max-w-3xl h-full mx-auto bg-white rounded-lg  dark:bg-gray-800 flex justify-center items-center mb-5 mt-5 p-8">';

            echo    '<div class="p-8"> 
                        ' . $this->historyBooked() . '
                    </div>';

            echo    '<div>
                        <div class="block max-w-xl w-96 p-6 bg-white border border-gray-200 rounded-lg shadow">
                        
                            <h5 class="mb-2 text-3xl  font-normal tracking-tight text-gray-900 dark:text-white text-right"><span class="font-bold"> ' . ($booked['name']) . '</span></h5>
                            <p class="text-right  text-xl mb-3 font-normal text-gray-700 dark:text-gray-400"> ' . $booked['phone'] . ' </p>

                            <hr>
                            
                            <div class="flex justify-between items-center">
                                <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Sân: </p>                            

                                <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"> ' . $booked['p_name'] . '</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Ngày đặt:</p>                            

                                <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"> ' . $date_format . '</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Giờ hoạt động: </p>                            

                                <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"> ' . $booked['start'] . '- ' . $booked['end'] . '</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Giá sân 1 tiếng: </p>                            

                                <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"> ' . $booked['price_per_hour'] . ' VNĐ</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Khuyến mãi: </p>                            

                                <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"> ' . $discount . ' %</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Trạng thái: </p>';

            if ($booked['status_id'] == 1) {
                echo '<p class="mt-5 mb-3 font-semibold text-blue-500 text-right">
                                            Chưa thanh toán
                                        </p>';
            } else if ($booked['status_id'] == 2) {
                echo '<p class="mt-5 mb-3 font-semibold text-red-500 text-right">
                                            Đã hủy
                                        </p>';
            } else {
                echo '<p class="mt-5 mb-3 font-semibold text-green-500 text-right">
                                            Đã thanh toán
                                        </p>';
            }
            echo '</div>
                        
                            <hr>

                            <div class="flex justify-between items-center">
                                <p class="mt-5 font-bold text-2xl text-gray-700 dark:text-gray-300">Tổng: </p>

                                <p class="mt-5 font-bold text-2xl text-red-500 text-right">  ' . $booked['price_per_hour'] . ' VNĐ</p>
                            </div>


                        </div>
                    </div>
                    ';

            echo '</div>';
        }
    }
}
