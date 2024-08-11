<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
       $db = new DB;
       $id = $_GET['pitch'];
       $curentDate = date('Y-m-d');
       $booking_query = 'SELECT b.date FROM booking b 
       JOIN pitch_detail pd ON b.pitch_detail_id = pd.id
       JOIN pitch p ON pd.pitch_id = p.id
       where p.id = :id and b.status_id = 1';
       $params_booking = array(
              ':id' => $id
       );
       $result = $db->select($booking_query, $params_booking);
       if ($result[0]['date'] >= $curentDate) {
              echo "<script>alert('Sân đang có lịch đặt không thể khoá')
              window.location.href='index.php?url=pitch'</script>";
              exit();
       } else {
              $query = "UPDATE pitch SET deleted=1 where id = :id";
              $params = array(
                     ":id" => $id
              );
              $deleted = $db->update($query, $params);
              if ($deleted) {
                     echo "<script>alert('Xóa sân thành công')
              window.location.href='index.php?url=pitch'</script>";
              } else {
                     echo "<script>alert('Xóa sân thất bại')</script>";
              }      
       }
}
