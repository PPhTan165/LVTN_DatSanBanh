<?php
session_start();
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <title>Document</title>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $phone = $_POST['phonenumber'];
            $password = $_POST['password'];
            $cfm_password = $_POST['cfmpassword'];

            if (preg_match('/\s/', $email)) {
                echo "Email không được chứa khoảng trắng.";
            }
            if (strpos($password, ' ') !== false) {
                echo "<script>alert('Mật khẩu không được chứa khoảng trắng')</script>";
                exit;
            }
            if(!isValidVietnamPhoneNumber($phone)){
                echo '<script>alert("Số điện thoại không hợp lệ")</script>';
            }
            if ($password != $cfm_password) {
                echo "<script>alert('Mật khẩu và xác nhận mật khẩu không trùng khớp')</script>";
                exit;
            }
            $password_encrypt = md5($password);
            $db = new Db();
            $sql = " select count(id) from user where email = :email";
            $arr = array(":email" => $email);
            $count_user = $db->select($sql, $arr)[0];
            if ($count_user['count(id)'] > 0) {

            } else {

                $insert_query = "INSERT INTO user( deleted, email, password, role_id) VALUES (0,:email,:password,2)";
                $insert_data = array(
                    ':email' => $email,
                    ':password' => $password_encrypt,
                );

                if (empty($email) || empty($fullname) || empty($password) || empty($cfm_password)) {

                    echo "<script>alert('Vui lòng điền đầy đủ thông tin.')</script>";
                
                } else {

                    $insert_result = $db->insert($insert_query, $insert_data);
                    $user_id = $db->getInsertId();
                    
                    if ($insert_result > 0) {

                        $insert_cus_query = "INSERT INTO manager( name, phone, user_id) VALUES (:name,:phonenumber,:user_id)";
                        $cus_arr = array(
                            ':name' => $fullname,
                            ':phonenumber' => $phone,
                            ':user_id' => $user_id
                        );
                        $result_cus = $db->insert($insert_cus_query, $cus_arr);
                        if ($result_cus) {
                            echo '<script>Đăng ký thành công</script>';
                            header("Location: index.php?url=manager");                            
                        } else {
                            echo '<script>Đăng ký thất bại</script>';
                        }
                    }
                }
            }
        }
    }

    ?>
    <div class="flex justify-center items-center w-96 rounded-xl p-5 h-full mt-28 absolute top-0 ">
        <form class="w-96 mx-auto bg-white p-5 rounded-xl shadow-xl" action="create_manager.php" method="POST">
            <h2 class="text-center text-2xl font-bold whitespace-normal mt-3 ">Đăng ký</h2>
            <div class="mb-5">
                <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nguyễn văn A" required />
            </div>
            <div class="mb-5">
                <label for="phonenumber" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SDT</label>
                <input type="text" id="phonenumber" name="phonenumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="09xxxxxxxx" maxlength="10" required />
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="example@gmail.com" required />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="mb-5">
                <label for="cfmpassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                <input type="password" id="cfmpassword" name="cfmpassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            
            <div class="mb-5 flex justify-between">
                <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                <a href="index.php?url=manager">Huỷ</a>
            </div>
        </form>
    </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>