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
    <title>Đăng ký</title>
</head>

<body class="flex justify-center items-center mt-10 p-8 bg-gray-800">

    <?php
    $user = new User;
    $user->registerUser();
    ?>

        <div class="flex justify-center items-center bg-white rounded-xl shadow-xl p-8 ">
            <div class="w-62 me-5">
                <img src="https://congtrinhthep.vn/wp-content/uploads/2017/12/cong-trinh-dan-khong-gian-gioi-san-bong-moi-cua-tottenham-hotspur.jpg" alt="img" height="600px" class="rounded-xl">
            </div>
            <form class="w-96" action="register.php" method="POST">
                <h2 class="text-center text-3xl font-bold whitespace-normal mt-3 mb-5 ">ĐĂNG KÝ</h2>
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
                    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required placeholder="Có ít nhất 6 ký tự trong đó phải có 1 chữ in hoa 1 số" />
                </div>
                <div class="mb-5">
                    <label for="cfmpassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                    <input type="password" id="cfmpassword" name="cfmpassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                </div>

                <div class="mb-5 flex justify-between">
                    <a href="login.php" class="py-2.5">Đã có tài khoản</a>
                    <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Đăng ký</button>
                </div>
            </form>
        </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>