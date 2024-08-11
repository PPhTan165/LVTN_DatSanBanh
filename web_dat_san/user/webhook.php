<?php
// webhook.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $json = json_decode($data, true);

    // Kiểm tra chữ ký của MoMo để đảm bảo tính xác thực của thông báo
    $partnerCode = "MOMO";
    $accessKey = "F8BBA842ECF85";
    $serectkey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";
    $signature = $json['signature'];
    $rawHash = "partnerCode=" . $json['partnerCode'] .
        "&accessKey=" . $json['accessKey'] .
        "&requestId=" . $json['requestId'] .
        "&orderId=" . $json['orderId'] .
        "&errorCode=" . $json['errorCode'] .
        "&transId=" . $json['transId'] .
        "&amount=" . $json['amount'] .
        "&message=" . $json['message'] .
        "&localMessage=" . $json['localMessage'] .
        "&responseTime=" . $json['responseTime'] .
        "&extraData=" . $json['extraData'];

    $checkSignature = hash_hmac("sha256", $rawHash, $serectkey);

    if ($signature === $checkSignature) {
        if ($json['errorCode'] == 0) {
            // Giao dịch thành công
            $orderId = $json['orderId'];
            $amount = $json['amount'];
            $transId = $json['transId'];
            // Cập nhật trạng thái giao dịch trong hệ thống của bạn
            // Ví dụ: Cập nhật trạng thái đơn hàng trong database
            echo "Transaction successful: Order ID: $orderId, Amount: $amount, Transaction ID: $transId";
        } else {
            // Giao dịch thất bại
            echo "Transaction failed: " . $json['message'];
        }
    } else {
        // Chữ ký không hợp lệ
        echo "Invalid signature";
    }
} else {
    echo "Invalid request method";
}
