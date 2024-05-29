<?php
session_start();
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $db = new DB;
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = 'SELECT * FROM user JOIN customer ON user.id = customer.user_id WHERE email = :email';
        $arr = array(":email" => $email);

        $userList = $db->select($sql, $arr);

        if (count($userList) > 0) {
            $user = $userList[0];
            $md5 = md5($password);

            if ($md5 == $user['password']) {
                $_SESSION["user"] = $email;
                $_SESSION["role"] = $user["role_id"];
                $_SESSION['name'] = $user['name'];
                switch ($user['role_id']) {
                    case '1':
                        header("location: ../admin/index.php");
                        exit();
                    case '2':
                        header("location: ../manager/index.php");
                        exit();
                    case '3':
                        header("location: index.php");
                        exit();
                    default:
                        // Do something for undefined role_id
                        break;
                }
            } else {
                $errorMessage = "Password is wrong";
            }
        } else {
            $errorMessage = "Email does not exist";
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
    <title>Đăng nhập</title>
</head>

<body class="flex items-center justify-center min-h-screen bg-blue-200 dark:bg-gray-900">
    <?php if ($errorMessage): ?>
    <div id="errorModal" tabindex="-1" class="fixed inset-0 z-50 flex items-center justify-center hidden overflow-y-auto">
        <div class="relative w-full max-w-md p-4">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Error
                    </h3>
                    <div class="mt-2 mb-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <?php echo $errorMessage; ?>
                        </p>
                    </div>
                    <div class="flex justify-end">
                        <button id="closeModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="flex justify-center items-center w-80 rounded-xl p-5 h-full w-full mt-28 absolute top-0 ">
        <form class="w-96 mx-auto bg-white p-5 rounded-xl shadow-xl" action="login.php" method="POST">
            <h2 class="text-center text-2xl font-bold whitespace-normal mt-3 ">Đăng nhập</h2>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="example@gmail.com" required />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="flex items-start mb-5">
                <p class="font-bold text-sm">Bạn chưa có tài khoản ? Bạn có thể <a class="text-blue-500" href="register.php">đăng ký ở đây.</a></p>
            </div>
            <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        // Show the modal if there is an error message
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($errorMessage): ?>
                var modal = document.getElementById('errorModal');
                modal.style.display = 'flex';

                var closeModal = document.getElementById('closeModal');
                closeModal.addEventListener('click', function () {
                    modal.style.display = 'none';
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>
