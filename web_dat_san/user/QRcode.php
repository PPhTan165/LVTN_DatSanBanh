<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/output.css">
    <title>Document</title>
</head>

<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();
$decoded_response = null; // Khởi tạo biến để tránh lỗi
$delete_response = null;  // Khởi tạo biến để lưu phản hồi khi xóa
global $qr_id ;           // Khởi tạo biến để lưu ID của mã QR

function createQR($amount, $description)
{
    global $decoded_response, $qr_id;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://bio.ziller.vn/api/qr/add",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 30, // Tăng thời gian chờ lên 30 giây
        CURLOPT_CONNECTTIMEOUT => 30, // Tăng thời gian chờ kết nối lên 30 giây
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer 8966c715cb8f30447e46b369a3e7d829",
            "Content-Type: application/json",
        ),
        CURLOPT_POSTFIELDS => json_encode(array(
            'type' => 'text',
            'data' => '2|99|0707719300|PHAN PHUC TAN||0|0|' . $amount . '|' . $description . '|tranfer_myqr',
            'background' => 'rgb(255,255,255)',
            'foreground' => 'rgb(0,0,0)',
            'logo' => 'https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png',
        )),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        $error_msg = curl_error($curl);
        $error_no = curl_errno($curl);
        echo "CURL Error Number: " . $error_no . "<br>";
        echo "CURL Error Message: " . $error_msg . "< br>";
    } else {
        $decoded_response = json_decode($response);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($decoded_response->link) && isset($decoded_response->id)) {
                $qr_id = $decoded_response->id; // Lưu trữ ID của mã QR
            } else {
                echo "Phản hồi không chứa thuộc tính 'link' hoặc 'id'.<br>";
            }
        } else {
            echo "Lỗi giải mã JSON: " . json_last_error_msg() . "<br>";
        }
        curl_close($curl);
    }
}

function deleteQR($id)
{
    global $delete_response;

    $url = "https://bio.ziller.vn/api/qr/{$id}/delete";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 30, // Tăng thời gian chờ lên 30 giây
        CURLOPT_CONNECTTIMEOUT => 30, // Tăng thời gian chờ kết nối lên 30 giây
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer 8966c715cb8f30447e46b369a3e7d829",
            "Content-Type: application/json",
        ),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        $error_msg = curl_error($curl);
        $error_no = curl_errno($curl);
        echo "CURL Error Number: " . $error_no . "<br>";
        echo "CURL Error Message: " . $error_msg . "<br>";
    } else {
        $delete_response = json_decode($response);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!isset($delete_response->success)) {
                echo "Phản hồi không chứa thuộc tính 'success'.<br>";
            }
        } else {
            echo "Lỗi giải mã JSON: " . json_last_error_msg() . "<br>";
        }

        curl_close($curl);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay']) && $_POST['pay'] == 'MOMO') {
        
        $total = $_POST['total'] * 1000;
        $content = $_POST['name'] . '-' . $_POST['phone'] . '-' . $_POST['p_name'] . '-' . $_POST['date'] . '-' . $_POST['time'];
        createQR($total, $content);
    }
}
?>


<body class="flex justify-center items-center mt-10 p-8 bg-gray-800">
    <div class="flex justify-center items-center bg-white rounded-xl shadow-xl p-8 ">

        <div class="logo p-8 border border-gray-100 rounded-lg shadow-lg w-full">
            <?php if ($decoded_response && isset($decoded_response->link)) : ?>
                <img src="<?= htmlspecialchars($decoded_response->link, ENT_QUOTES, 'UTF-8'); ?>" alt="QR Code" width="350px">
                
                <p class="text-center font-bold text-base">Mã thanh toán xoá trong:</p>
                <div id="countdown" class="text-center font-bold ">
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            initiateCountdownAndDelete('<?= htmlspecialchars($qr_id, ENT_QUOTES, 'UTF-8'); ?>',120);
                        });
                    </script>
                </div>
                <div class="text-center mt-5">
                    <a href="QRCode.php" class="text-center text-base text-blue-500">Tải lại mã</a>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</body>
<script src="../js/countDown.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>


</html>