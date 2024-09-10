<?php
header('Content-type: text/html; charset=utf-8');


function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //execute post
    $result = curl_exec($ch);
    if ($result === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        return $result;
    }
    //close connection
    curl_close($ch);
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$p_name = $_POST['p_name'];
$date = $_POST['date'];
$time = $_POST['time'];
$total = $_POST['total'];
$des =  $name . " - " . $phone . " - " . $p_name . " - " . $date . " - " . $time ;
$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

$orderInfo = $des;
$amount = $_POST['total'] * 1000;
$orderId = time() . "";
$redirectUrl = "http://localhost/luanvan/web_dat_san/web_dat_san/user/success.php";
$ipnUrl = "http://localhost/luanvan/web_dat_san/web_dat_san/user/invoice.php";
$extraData = "";
$requestId = time() . "";
$requestType = "captureWallet";

// $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
//before sign HMAC SHA256 signature
$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);
$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);
$result = execPostRequest($endpoint, json_encode($data));
var_dump($result);


$jsonResult = json_decode($result, true);  // decode json

//Just a example, please check more in there

header('Location: ' . $jsonResult['payUrl']);
