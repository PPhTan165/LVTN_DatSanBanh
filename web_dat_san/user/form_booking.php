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
        $arr_pd = array();
        $db = new DB;
        $pd = new Product;
        $currenDate =  date('Y-m-d');
        $timeCreated = date('Y-m-d/H:i:s');
        $selectedTimes = $_GET['time'];
        $idPitch = $_GET['id'];
        $date = $_GET['date'];

        $total = 0;

        $durations = $pd->getDuration($selectedTimes);
        $pitch_detail = "SELECT * FROM pitch_detail 
                JOIN duration ON pitch_detail.duration_id = duration.id
                JOIN pitch ON pitch_detail.pitch_id = pitch.id
                JOIN price ON pitch_detail.price_id = price.id
                WHERE duration_id = :duration_id AND pitch_id = :pitch_id";

        for ($i = 0; $i < count($durations); $i++) {
          $pitch_detail_arr = array(
            ":duration_id" => $selectedTimes,
            "pitch_id" => $idPitch
          );
          $pitch_detail_result = $pd->select($pitch_detail, $pitch_detail_arr)[0];
          array_push($arr_pd, $pitch_detail_result);
        }
        

        if (!empty($selectedTimes)) {
          $total = $pitch_detail_result['price_per_hour'];
          echo '
                    <form class="max-w-2xl mx-auto flex justify-between items-start" action="form_booking.php?id=' . $idPitch . '&date=' . $date . '&time=' . $selectedTimes . '" method="post">
                    

                    <div class="me-5">
                      <div class="mb-5">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Họ và tên người đặt</label>
                        <input type="text" id="name" name="fname" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nguyễn Văn A"  required />
                      </div>

                      <div class="mb-5">
                        <label for="phone-number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Số điện thoại</label>
                        <input type="text" id="phone-number" name="phone-number" maxLength="10"
                             class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                             placeholder="0123456789" 
                             required />
                      </div>
                    </div>
                    <div class = "w-full">
                      <div class="mb-5 ">
                        <label for="pitch_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                        <input type="text" id="pitch_name" name="pitch-name" aria-label="disabled input" class="mb-5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        value="' . $pitch_detail_result['name'] . '" disabled>                      
                      </div>

                      
                      
                      <div class="flex justify-between">

                      <div class="mb-5 ">

                        <label for="time-start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Giờ bắt đầu</label>
                        <select  id="time-start" name="start_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-64 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">';
                          foreach ($durations as $value) {
                            echo '<option value="' . $value['id'] . '">' . $value['start'] . '</option>
                            ';
                          }
                        
                    echo'  </select>
                                        
                      </div>

                      <div class="mb-5 ">
                      
                        <label for="time-end" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Giờ kết thúc</label>
                        <select id="time-end" name="end_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-64 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">';
                          foreach ($durations as $value) {
                            echo '<option value="' . $value['id'] . '">' . $value['end'] . '</option>
                            ';
                            
                          }
                        
                    echo'  </select>
                      </div>

                      </div>
                       

                      <div class="mb-5">
                      <label for="promotion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mã khuyến mãi</label>
                      <input type="text" id="promotion" name="promotion" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>

                      <button data-modal-target="default-modal" data-modal-toggle="default-modal" type="submit" name="submit" class="mb-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    </div>
                      </form>
                    ';
        } else {
          echo "Chưa chọn thời gian.";
        }
      } else {
        echo "Invalid request.";
      }
      echo  '</div>';

      if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $name = $_POST['fname'];
            $phoneNumber = $_POST['phone-number'];
            $promotion = $_POST['promotion'];
            $start_id = $_POST['start_id'];
            $end_id = $_POST['end_id'];

        $pitch = new Product;
        $existPromotion = $pitch->existPromotion($promotion);
         
        $expirePromotion = $pitch->expirePromotion($promotion);
        
        if(isValidVietnamPhoneNumber($phoneNumber) == false){
          echo '
          <script>
            alert("Số điện thoại không hợp lệ");
          </script>';exit();
        }
        if($promotion !== ''){
          if(!$existPromotion){
            echo '
            <script>
              alert("Mã khuyến mãi không tồn tại");
            </script>';exit();
          }
          if($expirePromotion){
            echo '
            <script>
              alert("Mã khuyến mãi đã hết hạn");
            </script>';exit();
          }
  
        }
        


        if ((int)$_POST['start_id'] > (int)$_POST['end_id']) {
          echo '
          <script>
            alert("Thời gian không hợp lệ");
          </script>';exit();
        } else {
          if (isset($_POST['submit'])) {
            

            //Truy vấn mã khyến mãi
            $promotion_query = "SELECT * from promotion WHERE name = :name";
            $promotion_arr = array(
              ":name" => $promotion
            );
            $promotion_result = $db->select($promotion_query, $promotion_arr);


            //lấy id của customer
            $customer_query = "SELECT user.id as user_id, user.email, customer.id as cus_id 
                                FROM user JOIN customer ON user.id = customer.user_id 
                                WHERE user.email = :email";
            $customer_arr = array(
              ":email" => $_SESSION['user']
            );
            $customer_result = $db->select($customer_query, $customer_arr)[0];

            //lấy id của pd mà số giờ đã chọn. Sau khi xử lý
            $pd_query = "SELECT pd.id as pd_id, price_per_hour FROM pitch_detail pd
                          join price on  pd.price_id = price.id
                          Where pitch_id = :pitch_id and duration_id between :start_id and :end_id";
            $pd_arr = array(
              ":pitch_id"=>$idPitch,
              ":start_id"=>$start_id,
              ":end_id"=>$end_id,
            );
            $pd_result = $db->select($pd_query,$pd_arr);

            //xử lý total 
            $promotion_id = isset($promotion_result[0]['id']) ? $promotion_result[0]['id'] : null;

            $total = 0;

            //tổng tiền đặt sân
            for($k = 0;$k<count($pd_result);$k++){
              $total += $pd_result[$k]['price_per_hour'];
            }

            //Kiểm tra mã khuyến mãi
            if ($promotion_result) {
              if ($promotion_result['date'] >= $currenDate) {
                $discount = $total * $promotion_result['discount'] / 100;
                if ($discount > $promotion_result['max_get']) {
                  $discount = $promotion_result['max_get'];
                  $total = $total - $discount;
                } else {
                  $total = $total - $discount;
                }
              } 
            }

            //Kiểm tra thông tin đặt sân đã hợp lệ chưa
            if (isValidVietnamPhoneNumber($phoneNumber) && isset($name) && isset($phoneNumber)) {
              $booking_query = "INSERT INTO booking(name,phone, date, date_created, total, cus_id, pitch_detail_id, status_id) 
                              VALUES (:name,:phone,:date,:date_created, :total, :cus_id, :pitch_detail_id, 1)";
              for($j = 0; $j < count($pd_result);$j++){
                $booking_arr = array(
                  ":name" => $name,
                  ":phone" => $phoneNumber,
                  ":date" => $date,
                  ":date_created" => $timeCreated,
                  ":total" => $total,
                  ":cus_id" => $customer_result['cus_id'],
                  ":pitch_detail_id" => $pd_result[$j]['pd_id'],
                );

                $booking_insert = $db->insert($booking_query, $booking_arr);
              }
              if ($booking_insert) {
                echo '
                <script>
                  window.location.href="invoice.php"
                </script>
              ';

              } else {
                echo '
                <script>
                  alert("Đặt sân thất bại");
                </script>
              ';exit();
              }
            } else {
              // Số điện thoại không hợp lệ, thông báo lỗi
             echo '
              <script>
                alert("Số điện thoại không hợp lệ");
              </script>
            ';exit();
            }
          }
        }
      }
      ?>


    </div>
</body>
<script src="../js/chooseTime.js"></script>

</html>