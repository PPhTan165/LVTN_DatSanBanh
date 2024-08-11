<?php 
  require "../config/config.php";
  require ROOT . "/include/function.php";
  spl_autoload_register("loadClass");
  session_start();
  if($_SERVER['REQUEST_METHOD']=='GET'){
    $db = new DB;
    $admin = new Admin;
    $booking = $admin->getBookingById($_GET['booking']);
    $id =$_GET['booking'];
    $query = "UPDATE booking SET status_id=3 where id = :id";
    $params=array(
     ":id"=>$id
    );
   $update = $db->update($query,$params);

    if($update){

      

          $update_query = "UPDATE revenue SET daily_revenue = daily_revenue + :daily_revenue WHERE date = :date AND manager_id = :manager_id AND type_id = :type_id";
          $params_update = array(
            ":daily_revenue" => $booking['total'],
            ":date" => $booking['date'],
            ":manager_id" => $booking['manager_id'],
            ":type_id" => $booking['type_id']
          );
          $update_revenue = $db->update($update_query, $params_update);

          if($update_revenue){
            echo "<script>alert('Thanh toán sân thành công')
            window.location.href='index.php?url=booking'</script>";
          } else {
            echo "<script>alert('Thanh toán sân thất bại')
            window.location.href='index.php?url=booking'
            </script>";
          }

      
    }
  }  
?>