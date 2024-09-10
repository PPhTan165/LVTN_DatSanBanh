<?php
session_start();
require "../config/config.php";
require ROOT . "/include/function.php";
require "../mail/index.php";
spl_autoload_register("loadClass");

$errorMessage = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $db = new DB;
        $user = new User;
        $password = htmlspecialchars($_POST['password']); 
        $confirm_password = htmlspecialchars($_POST['confirm_password']);          
        if(isValidPassword($password) == false) {
            $errorMessage['validate'] = "Mật khẩu không hợp lệ";
        } elseif ($password != $confirm_password) {
            $errorMessage['fail'] = "Mật khẩu không khớp";
        } else {
            $email = $_SESSION['email'];
            $md5 = md5($password);
            $sql = "UPDATE user SET password = :password WHERE email = :email";
            $arr = array(":password" => $md5, ":email" => $email);
            $db->update($sql, $arr);
            header("location: login.php");
            exit();
        }
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
    <title>Reset Password</title>
</head>

<body class="flex justify-center items-center mt-10 p-8 bg-gray-800">

    <div class="flex justify-center items-center bg-white rounded-xl shadow-xl p-8">
        <div class="w-62 me-5">
            <img src="https://congtrinhthep.vn/wp-content/uploads/2017/12/cong-trinh-dan-khong-gian-gioi-san-bong-moi-cua-tottenham-hotspur.jpg" alt="img" height="600px" class="rounded-xl">
        </div>
        <form class="w-96" action="resetPass.php" method="POST">
            <h2 class="text-center text-2xl font-bold whitespace-normal mt-3">THAY ĐỔI MẬT KHẨU</h2>
            <div class="mb-5">
               
                
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mật khẩu</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ít nhất 6 ký tự có chữ thường, số, chữ in hoa" required />
                <?php if(isset($errorMessage['validate'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errorMessage['validate']; ?></p>
                <?php endif; ?>

            </div>

            <div class="mb-5">
                <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Xác thực mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                <?php if(isset($errorMessage['fail'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errorMessage['fail']; ?></p>
                    
            </div>
                <?php endif; ?>
            
            <div class="flex justify-center w-full mt-5">
                <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Lấy mật khẩu mới</button>
            </div>
        </form>
    </div>
                
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  
</body>
</html>
