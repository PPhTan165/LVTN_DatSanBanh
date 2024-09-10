<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
ob_start();
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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $p_name = $_POST['p_name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $total = $_POST['total'];
        if (isset($_POST['submit'])) {
            header("Location: success.php");
        } else {
            header("Location: momo.php?total=$total");
        }
    }

    $invoices = $user->getInvoice();
    $limit = $_GET['limit'];
    ?>

    <div class="main">
        <h2 class="text-3xl font-bold text-center mt-5 mb-5 ">Hoá đơn đặt sân</h2>



        <div class="w-full max-w-3xl h-full mx-auto bg-white rounded-lg  dark:bg-gray-800 flex justify-center mb-5 mt-5 gap-5">';
            
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
                    $discount = 0;
                    for ($i = 0; $i < count($invoices); $i++) {
                        $invoice = $invoices[$i];
                        $promotions = $user->getPromotion($invoice['id']);
                        if ($promotions == null) {
                            $promotion = null;
                        } else {
                            $promotion = $promotions[0];
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

                   

                    <div class="flex justify-between items-center mb-5">
                        <p class="mt-5 font-bold text-2xl text-gray-700 dark:text-gray-300">Tổng: </p>

                        <p name="st" class="mt-5 font-bold text-2xl text-red-500 text-right"><?= number_format($total, 3) ?> VNĐ</p>
                    </div>
                    <input type="hidden" name="total" value="<?= $total ?>">
                </div>
                <div>

                    <h5 class="text-lg font-bold mb-5">Chọn phương thức thanh toán:</h5>
                    <form method="POST" action=""  class="mt-5">
                        <input type="hidden" name="name" value="<?= $invoices[0]['name'] ?>">
                        <input type="hidden" name="phone" value="<?= $invoices[0]['phone'] ?>">
                        <input type="hidden" name="p_name" value="<?= $invoices[0]['p_name'] ?>">
                        <input type="hidden" name="date" value="<?= $invoices[0]['date'] ?>">
                        <input type="hidden" name="total" value="<?= $total ?>">
                        <input type="hidden" name="limit" value="<?= $limit ?>">
                        <input type="hidden" name="time" value="<?= $invoices[0]['start'] . ' - ' . $invoices[count($invoices) - 1]['end'] ?>">
                        <div class="mb-3">
                            <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm w-52 px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Thanh toán</button>
                        </div>
                   
                </form>
                
                <form method="POST" action="momo.php" target="_blank" enctype="application/x-www-form-urlencoded" class="mt-5">
                        <input type="hidden" name="name" value="<?= $invoices[0]['name'] ?>">
                        <input type="hidden" name="phone" value="<?= $invoices[0]['phone'] ?>">
                        <input type="hidden" name="p_name" value="<?= $invoices[0]['p_name'] ?>">
                        <input type="hidden" name="date" value="<?= $invoices[0]['date'] ?>">
                        <input type="hidden" name="total" value="<?= $total ?>">
                        <input type="hidden" name="limit" value="<?= $limit ?>">

                        <input type="hidden" name="time" value="<?= $invoices[0]['start'] . ' - ' . $invoices[count($invoices) - 1]['end'] ?>">
                       
                    <div class="mb-5">
                        <button type="submit" name="momo" class="text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-52 px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Thanh toán MOMO</button>
                    </div>
                </form>

                <form method="POST" action="momo_atm.php" target="_blank" enctype="application/x-www-form-urlencoded" class="mt-5">
                        <input type="hidden" name="name" value="<?= $invoices[0]['name'] ?>">
                        <input type="hidden" name="phone" value="<?= $invoices[0]['phone'] ?>">
                        <input type="hidden" name="p_name" value="<?= $invoices[0]['p_name'] ?>">
                        <input type="hidden" name="date" value="<?= $invoices[0]['date'] ?>">
                        <input type="hidden" name="total" value="<?= $total ?>">
                        <input type="hidden" name="limit" value="<?= $limit ?>">

                        <input type="hidden" name="time" value="<?= $invoices[0]['start'] . ' - ' . $invoices[count($invoices) - 1]['end'] ?>">
                       
                    <div class="mb-5">
                        <button type="submit" name="momo_atm" class="text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-52 px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Thanh toán MOMO ATM</button>
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