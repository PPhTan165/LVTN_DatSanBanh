<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/output.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <title>Chi tiết sân</title>
</head>

<body>

  <?php require_once("../include/header.php") ?>

  <div class="mt-5">
    <h3 class="text-center font-bold text-4xl mt-5">Thông tin đặt sân</h3>
    <div class="main flex justify-center items-center w-full mt-5 mb-5 ">

      <?php
      if (isset($_GET['time']) && isset($_GET['id']) && isset($_GET['date'])) {

        $db = new DB;
        $currenDate =  date('Y-m-d');
        $selectedTimes = $_GET['time'];
        $idPitch = $_GET['id'];
        $date = $_GET['date'];

        $pitch_detail = "SELECT * FROM pitch_detail 
                JOIN duration ON pitch_detail.duration_id = duration.id
                JOIN pitch ON pitch_detail.pitch_id = pitch.id
                JOIN price ON pitch_detail.price_id = price.id
                WHERE duration_id = :duration_id AND pitch_id = :pitch_id";

        $pitch_detail_arr = array(
          ":duration_id" => $selectedTimes,
          "pitch_id" => $idPitch
        );

        $pitch_detail_result = $db->select($pitch_detail, $pitch_detail_arr)[0];


        if (!empty($selectedTimes)) {
          echo '
                    <form class="max-w-2xl; mx-auto flex justify-between items-start" action="form_booking.php?id=' . $idPitch . '&date=' . $date . '&time=' . $selectedTimes . '" method="post">
                    <div class="me-5">
                      <div class="mb-5">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Họ và tên người đặt</label>
                        <input type="text" id="name" name="fname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nguyễn Văn A"  required />
                      </div>

                      <div class="mb-5">
                        <label for="phone-number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Số điện thoại</label>
                        <input type="text" id="phone-number" name="phone-number" maxLength="10"
                             class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                             placeholder="0123456789" 
                             required />
                      </div>
                    </div>
                    <div>
                      <div class="mb-5 ">
                        <label for="pitch_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                        <input type="text" id="pitch_name" name="pitch-name" aria-label="disabled input" class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        value="' . $pitch_detail_result['name'] . '" disabled>                      
                      </div>

                      <div class="mb-5 ">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Giá sân</label>
                        <input type="text" id="disabled-input" name="price" aria-label="disabled input" class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        value="' . $pitch_detail_result['price_per_hour'] . '" disabled>                      
                      </div>
                      <div class="flex justify-between">

                      <div class="mb-5 ">
                        <label for="time-start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Giờ bắt đầu</label>
                        <input type="text" id="time-start" name="time-start" aria-label="disabled input" class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        value="' . $pitch_detail_result['start'] . '" disabled>

                      </div>

                      <div class="mb-5 ">
                        <label for="time-end" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Giờ kết thúc</label>
                        <input type="text" id="time-end" name="time-end" aria-label="disabled input" class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        value="' . $pitch_detail_result['end'] . '" disabled>
                      </div>

                      </div>
                       
                      <div class="mb-5">
                      <label for="promotion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mã khuyến mãi</label>
                      <input type="text" id="promotion" name="promotion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>

                      <button type="submit" name="submit" class="mb-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    </div>
                      </form>
                    ';
        } else {
          echo "Chưa chọn thời gian.";
        }
      } else {
        echo "Invalid request.";
      }

      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['submit'])) {

          $name = $_POST['fname'];
          $phoneNumber = $_POST['phone-number'];
          $phonePattern = '/^0[0-9]{9}$/';
          $promotion = $_POST['promotion'];

          $promotion_query = "SELECT * from promotion WHERE name = :name";
          $promotion_arr = array(
            ":name" => $promotion
          );
          $promotion_result = $db->select($promotion_query, $promotion_arr)[0];
          $promotion_id = isset($promotion_result['id']) ? $promotion_result['id'] : '';
          $price = $pitch_detail_result['price_per_hour'];
          var_dump($promotion_result);
          if ($promotion_result) {
            if ($promotion_result['date'] >= $currenDate) {
              $discount = $price * $promotion_result['discount'] / 100;
              if ($discount > $promotion_result['max_get']) {
                $discount = $promotion_result['max_get'];
                $total = $price - $discount;
              } else {
                $total = $price - $discount;
              }
            }else{
              $total = $price;
            }
          }else{
            $total = $price;
          }
          $customer_query = "SELECT user.id as user_id,user.email,customer.id as cus_id FROM user JOIN customer ON user.id = customer.user_id WHERE user.email = :email";
          $customer_arr = array(
            ":email" => $_SESSION['user']
          );
          $customer_result = $db->select($customer_query, $customer_arr)[0];
          
          if (preg_match($phonePattern, $phoneNumber) && isset($name) && isset($phoneNumber)) {
            $booking_query = "INSERT INTO booking( date, total, cus_id, pitch_detail_id, status_id) 
                              VALUES (:date, :total, :cus_id, :pitch_detail_id, 1)";
            $booking_arr = array(
              ":date" => $date,
              ":total" => $total,
              ":cus_id" => $customer_result['cus_id'],
              ":pitch_detail_id" => $pitch_detail_result['id'],
            );
            $booking_insert = $db->insert($booking_query, $booking_arr);
            if ($booking_insert) {
              echo '
              <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                  <a href="index.php">
                      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Đặt sân thành công</h5>
                  </a>
                  <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Hẹn bạn ngày trên sân cỏ.</p>
                  <a href="index.php" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                      Quay lại
                      <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                      </svg>
                  </a>
              </div>
              ';
            } else {
              echo '

              <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                  <a href="index.php">
                      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Đặt sân thất bại</h5>
                  </a>
                  <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Có thể sân này đã được đặt hoặc đã qua giờ đặt xin lỗi quý khách, mong quý khách có thể chọn sân khác</p>
                  <a href="index.php" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                      Quay lại
                      <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                      </svg>
                  </a>
              </div>
              ';
            }
          } else {
            // Số điện thoại không hợp lệ, thông báo lỗi
            echo "Số điện thoại không hợp lệ hoặc chưa điền đủ thông tin.<a href=index.php>Quay lại trang chủ.</a>";
          }
        }

      }
      ?>

    </div>

  </div>
</body>
<script src="../js/chooseTime.js"></script>

</html>