<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();

// Kiểm tra nếu form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_SESSION['user'])){

        if (isset($_POST['selected_time']) && !empty($_POST['selected_time'])) {
            // Chuyển hướng đến trang booking.php với thông tin cần thiết
            $selected_time = $_POST['selected_time'];
            $id_pitch = $_GET['id'];
            $date= $_GET['date'];
            header("Location: form_booking.php?id=$id_pitch&date=$date&time=$selected_time");
            exit();
        } else {
            // Lưu thông báo lỗi vào biến để hiển thị sau
            $error_message = 'Vui lòng chọn giờ trước khi submit.';
        }
    }else{
        header("Location: login.php");
        exit();
    }
}

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
    
    <?php require_once("../include/header.php");
    $currenDate = date('Y-m-d') ?>
    <div>
        <h3 class="text-center font-bold text-4xl mt-5">Giờ sân banh hoạt động</h3>


        <?php
            // Hiển thị thông báo lỗi (nếu có)
            if (isset($error_message)) {
                echo '<p class="text-red-500">' . $error_message . '</p>';
            }

            // Gọi hàm getTime() để hiển thị form
            $pd = new Product;
            $pd->getTime();
        ?>
    </div>

    </div>
    <?php require_once("../include/footer.php") ?>
</body>
<script src="../js/chooseTime.js"></script>

</html>