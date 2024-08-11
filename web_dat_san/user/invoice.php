<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css//output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>TOTTENHAM FC</title>
</head>

<body>
    <?php require_once("../include/header.php");

    $user = new User;

    $invoices = $user->getInvoice();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $p_name = $_POST['p_name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $pay = $_POST['pay'];
        $total = $_POST['total'];
        if($pay == 'cash'){
            echo '<script>alert("Đặt sân thành công")
            window.location.href = "index.php";
            </script>';
        }else{
            echo '<script>
            window.location.href = "QRcode.php";';exit();
        }
    }
    ?>

    <div class="main">
        <h2 class="text-3xl font-bold text-center mt-5 mb-5 ">Hoá đơn đặt sân</h2>



        <div class="w-full max-w-3xl h-full mx-auto bg-white rounded-lg  dark:bg-gray-800 flex justify-center items-center mb-5 mt-5">';
            <form action="invoice.php" method="post">
                <input type="hidden" name="name" value="<?= $invoices[0]['name'] ?>">
                <input type="hidden" name="phone" value="<?= $invoices[0]['phone'] ?>">
                <input type="hidden" name="p_name" value="<?= $invoices[0]['p_name'] ?>">
                <input type="hidden" name="date" value="<?= $invoices[0]['date'] ?>">
                <input type="hidden" name="time" value="<?= $invoices[0]['start'] . ' - ' . $invoices[count($invoices)-1]['end'] ?>">                           

                <div>
                    <div class="block max-w-xl w-96 p-6 bg-white border border-gray-200 rounded-lg shadow">

                        <h5 class="mb-2 text-3xl  font-normal tracking-tight text-gray-900 dark:text-white text-right"><span class="font-bold"><?= $invoices[0]['name'] ?> </span></h5>
                        <p class="text-right  text-xl mb-3 font-normal text-gray-700 dark:text-gray-400"><?= $invoices[0]['phone'] ?> </p>

                        <hr>

                        <div class="flex justify-between items-center">
                            <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Sân: </p>

                            <p name="p_name" class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"><?= $invoices[0]['p_name'] ?></p>
                        </div>

                        <div class="flex justify-between items-center">
                            <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Ngày đặt:</p>

                            <p name="date" class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right"><?= $invoices[0]['date'] ?></p>
                        </div>
                        <?php
                        $total = 0;
                        $currentDate = date('Y-m-d');
                        for ($i = 0; $i < count($invoices); $i++) {
                            $invoice = $invoices[$i];
                            $promotions = $user->getPromotion($invoice['id']);
                            if($promotions == null){
                                $promotion = null;
                            }else{
                                $promotion = $promotions[0];
                                $discount = 0;

                                if ($promotion != null) {
                                    $date_exp  = $promotion['date_exp'];
                                    if ($date_exp < $currentDate) {
                                        $discount = 0;
                                    } else {
                                        if ($discount > $promotion['max_get']) {
                                            $discount = $promotion['max_get'];
                                        } else {
                                            $discount = $invoice['price_per_hour'] * $promotion['discount'] * 100;
                                        }
                                    }
                                }
                            }
                            $total += $invoice['price_per_hour'] - $discount;
                            echo '
                        <div class="flex justify-between items-center">
                            <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Giờ hoạt động: </p>
                            
                            <p name="date" class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right">' . $invoice['start'] . ' - ' . $invoice['end'] . ' </p>
                        </div>
    
                        <div class="flex justify-between items-center">
                            <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Giá sân 1 tiếng: </p>
    
                            <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right">' . $invoice['price_per_hour'] . ' VNĐ</p>
                        </div>
    
                        <div class="flex justify-between items-center">
                            <p class="mt-5 mb-3 font-semibold text-gray-700 dark:text-gray-500">Giảm giá: </p>
    
                            <p class="mt-5 mb-3 font-normal text-gray-700 dark:text-gray-300 text-right">' . number_format($discount, 3) . ' VNĐ</p>
                        </div>
                        <hr class="mb-5">';
                        }
                        ?>

                        <div class="mb-6 mt-5">
                            <label for="pay" class="block mb-2  font-medium text-gray-900 dark:text-white ">Chọn phương thức thanh toán</label>

                            <select id="pay" name="pay" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="cash">Thanh toán trực tiếp</option>
                                <option value="momo">MOMO</option>
                            </select>

                        </div>

                        <div class="flex justify-between items-center mb-5">
                            <p class="mt-5 font-bold text-2xl text-gray-700 dark:text-gray-300">Tổng: </p>

                            <p name="st" class="mt-5 font-bold text-2xl text-red-500 text-right"><?= number_format($total, 3) ?> VNĐ</p>
                        </div>
                        <input type="hidden" name="total" value="<?= $total ?>">

                        <div class="mb-5 mt-5 pt-5 flex justify-center w-full">
                            <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Thanh toán</button>
                        </div>



                    </div>
            </form>
        </div>


    </div>
    <?php

    ?>


    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>