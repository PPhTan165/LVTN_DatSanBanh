<?php 
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>TOTTENHAM FC</title>
</head>
<body>
    <?php require_once("../include/header.php") ?>
    <div class="main mb-5">
        <h2 class="text-3xl font-bold text-center mt-5">ĐIỀU LỆ VỀ GIẢI ĐẤU</h2>
        <div class="container mx-auto">
            <div class="p-8">
                <h3 class="text-2xl font-bold">1. Điều kiện tham gia</h3>
                <p class="text-lg">- Các đội bóng phải đăng ký thông tin đầy đủ và chính xác</p>
                <p class="text-lg">- Các đội bóng phải có đủ số lượng cầu thủ để tham gia giải đấu. Nếu không đủ sẽ tính là <b> không đủ điều kiện thi đấu </b></p>
                <p class="text-lg">- Các đội bóng phải đăng ký trước thời hạn</p>
                <p class="text-lg">- Các đội bóng phải đóng phí tham gia giải đấu</p>
                <p class="text-lg">- Các cầu thủ không vi phạm pháp luật nhà nước</p>
                <p class="text-lg">- Các cầu thủ phải có quốc tịch Việt Nam</p>
                <p class="text-lg">- Các cầu thủ không phải là cầu thủ chuyên nghiệp hay tham gia các giải hạng 1</p>
                <hr>
                <h3 class="text-2xl font-bold mt-5">2. Quy định về trang phục</h3>
                <p class="text-lg">- Các đội bóng phải mặc đồng phục đầy đủ</p>
                <p class="text-lg">- Các cầu thủ phải mặc giày đúng quy định</p>
                <p class="text-lg">- Các cầu thủ phải mặc bảo hộ đúng quy định</p>
                <hr>
                <h3 class="text-2xl font-bold mt-5">3. Quy định về thời gian</h3>
                <p class="text-lg">- Các đội bóng phải đến sân trước 1 tiếng để làm thủ tục điểm danh đội</p>
                <p class="text-lg">- Nếu đội bóng nào đến muộn 15ph sẽ tính là từ bỏ thi đấu</p>
                <hr>
                <h3 class="text-2xl font-bold mt-5">4. Quy định về thể lệ</h3>
                <p class="text-lg">- Các trận đấu sẽ được tổ chức theo thể lệ thi đấu của FIFA</p>
                <p class="text-lg">- Các trận đấu sẽ được tổ chức trong 2 hiệp, mỗi hiệp 45 phút</p>
                <p class="text-lg">- Các trận đấu sẽ được tổ chức trong 1 tuần</p>
                <hr>
                <div class="mt-5">

                    <a href="tournament_register.php?id=<?=$_GET['id']?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mt-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Quay lại giải đấu</a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>