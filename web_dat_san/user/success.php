<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();

if (isset($_GET['partnerCode'])) {
    $success = array();
    $user = new User;
    $invoice = $user->getInvoice();
    $status = 3; 
    for($i = 0; $i < count($invoice); $i++){
        $update = "UPDATE booking SET status_id = $status WHERE id = " . $invoice[$i]['id'];
        $user->update($update);
    }
    $success['success'] = "Thanh toán ATM thành công.";

} else {
    $success['cash'] = "Đặt sân thành công";
}

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
    <div class="main p-8">
    
        <div class="  fixed flex justify-center shadow-2xl items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Thông báo đặt sân thành công
                        </h3>
                       
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <?php if(isset($success['success'])): ?>
                        <p class="text-lg font-semibold text-green-500 dark:text-green-400">
                            <?= $success['success'] ?>
                        </p>
                        <p class="text-lg font-semibold  text-gray-500 dark:text-gray-400">
                            Hẹn bạn vào ngày trên sân cỏ nhé !
                        </p>
                        <?php else: ?>
                            <p class="text-lg font-semibold  text-green-500 dark:text-green-400">
                            <?= $success['cash'] ?>
                        </p>
                        <p class="text-lg font-semibold  text-gray-500 dark:text-gray-400 ">
                            Hẹn bạn vào ngày trên sân cỏ nhé !
                        </p>
                    <?php endif; ?>
                        
                    </div>
                    <!-- Modal footer -->
                    <!-- <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <a data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                    </div> -->
                    <div class="mb-5 flex items-center ms-5">
                        <a href="index.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Quay lại trang chủ</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</body>

</html>