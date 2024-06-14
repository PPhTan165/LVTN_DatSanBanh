<?php 
require "./config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");

$db = new DB;
$password = "admin";
$md5 = md5($password);
$query = "INSERT INTO user(deleted, email, password, role_id) VALUES (0,'admin@gmail.com',:password,1)";
$params = array(
    ":password" => $md5
);
$result = $db->insert($query,$params);
for ($i = 1; $i < 19; $i++) {
    $sql = "insert into pitch_detail (duration_id, pitch_id, price_id) values (:duration, :pitch, :price)";

    if($i < 5) {
        // if($typeId == 1)
        $price = 1;
        // else if(typeId == 2)  $price = 5 else $price $9
    } else if( $i < 10) {
        $price = 2;
    } else if ($i < 13) {
        $price = 3;
    } else $price = 4;

    $arr = array(":duration" => $i, ":pitch" => 1, ":price" => $price);

    $result = $db->insert($sql, $arr);
    if($result>0){
        echo "successful";
    }
}

?>