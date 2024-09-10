<?php
require "../config/config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
session_start();

$user = new User;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $name = $_POST['fname'];
    $phone = $_POST['phone'];

    $erro = array();

    if (empty($name)) {
        $erro['name'] = "Vui lòng nhập tên người dùng";
    }

    if (isValidVietnamPhoneNumber($phone) == false) {
        $erro['validate'] = "Số điện thoại không hợp lệ";
    }
    if (empty($erro)) {
        if (isset($_POST['update'])) {

            $query = "UPDATE customer JOIN user ON customer.user_id = user.id SET customer.name = :name, phone = :phone WHERE email = :email";
            $arr = array(':name' => $name, ':phone' => $phone, ':email' => $email);
            $update = $user->update($query, $arr);
            if ($update) {
                $erro['success'] = "Cập nhật thông tin thành công";
                header("Location: info.php");
            } else {
                $erro['fail'] = "Cập nhật thông tin thất bại";
            }
        }else if(isset($_POST['delete'])){
            $query = "UPDATE customer JOIN user ON customer.user_id = user.id SET user.deleted = :deleted WHERE email = :email";
            $arr = array(
                ':deleted' => 1,
                ':email' => $email
            );
            $delete = $user->delete($query, $arr);
            if ($delete) {
                $erro['success'] = "Xóa tài khoản thành công";
                header("Location: logout.php");
            } else {
                $erro['fail'] = "Xóa tài khoản thất bại";
            }
        }
    }
}

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
    <?php require_once("../include/header.php") ?>
    <div class="main mt-5">
        <?php if (isset($erro['success'])): ?>
            <p class="text-green-500 text-center"><?php echo $erro['success'] ?></p>
        <?php endif; ?>
        <h5 class="text-2xl font-bold text-center">THÔNG TIN TÀI KHOẢN</h5>
        <?php
        $id = $_SESSION['cus_id'];
        $email = $_SESSION['email'];
        $user = new User;
        $info = $user->getInfoUserById($id, $email);
        $team_id = $info['team_id'];
        $team = $user->getTeamById($team_id);
        ?>

        <div class=" py-6 mt-5">

            <form action="info.php" method="POST" class="max-w-sm mx-auto">
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" id="email" name="email" aria-label="disabled input" value="<?php echo $info['email']; ?>" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" disabled />
                </div>
                <div class="mb-5">
                    <label for="fname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên người dùng</label>
                    <input type="text" id="fname" name="fname" value="<?php echo $info['cus_name']; ?>" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />

                    <?php if (isset($erro['name'])): ?>
                        <p class="text-red-500"><?php echo $erro['name'] ?></p>
                    <?php endif; ?>

                </div>
                <div class="mb-5">
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Số điện thoại</label>
                    <input type="text" max="10" id="phone" name="phone" value="<?php echo $info['phone']; ?>" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />

                    <?php if (isset($erro['validate'])): ?>
                        <p class="text-red-500"><?php echo $erro['validate'] ?></p>
                    <?php endif; ?>

                </div>
                <div class="mb-5">
                    <label for="team" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Đội</label>
                    <input type="text" id="team" name="team" aria-label="disabled input" value="<?php echo $team['name'] ?? 'Chưa có đội'; ?>" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" disabled />
                </div>
                <div class="flex">
                    <button type="submit" name="update" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm w-52 px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cập nhật tài khoản</button>
                    <button type="submit" name="delete" onclick="return confirm('Bạn có chắc chắn muốn xoá không?');" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm w-52 px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Xoá tài khoản</button>
                </div>
            </form>
        </div>


    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</html>