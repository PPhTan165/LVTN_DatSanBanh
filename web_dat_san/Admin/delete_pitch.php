<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
       $db = new DB;
       $id = $_GET['pitch'];
       $booking_query = 'SELECT COUNT(*) FROM booking b 
       JOIN pitch_detail pd ON b.pitch_detail_id = pd.id
       JOIN pitch p ON pd.pitch_id = p.id
       where p.id = :id and b.status_id = 1';
       $params_booking = array(
              ':id' => $id
       );
       $result = $db->select($booking_query, $params_booking);
       if ($result[0]['COUNT(*)'] > 0) {
              echo "<script>alert('Sân đã có người đặt không thể xóa')
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
