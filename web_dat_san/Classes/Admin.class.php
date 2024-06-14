<?php

class Admin extends DB
{


    public function filterPage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $url = $_GET['url'] ?? "pitch";

            switch ($url) {
                case 'pitch':

                    $this->getAllPitch();
                    break;
                case 'booking':
                    # code...
                    break;
                case 'manager':
                    # code...
                    break;
                case 'team':
                    # code...
                    break;
                case 'tournament':
                    # code...
                    break;
                case 'promotion':
                    # code...
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    public function getAllPitch()
    {
        $query = "SELECT pitch.id as pitch_id, pitch.name as pitch_name, type.name as type_name,manager.name as mana_name,description FROM pitch 
                    join type on pitch.type_id = type.id
                    join manager on pitch.manager_id = manager.id
                    where deleted = 0";
        $result = $this->select($query);
        
        echo '
        <div>
            <h2 class=" text-4xl font-bold text-center">Danh sách sân</h2>
            </div>
            <div class="flex justify-end">
            <a href="Create_pitch.php" class="me-5 font-bold text-lg text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tạo thêm sân </a>
            </div>
            <div class=" overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tên sân
                        </th>   
                        <th scope="col" class="px-6 py-3">
                            Mô tả
                        </th>   
                        <th scope="col" class="px-6 py-3">
                            Quản lý
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Loại sân
                        </th>
                        <th scope="col" class="px-6 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody>';
        $index = 1;
        foreach ($result as $team) {
            echo ' <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    ' . $index++ . '
                    </th>
                    <td class="px-6 py-4">
                    ' . $team['pitch_name'] . '
                    </td>
                    <td class="px-6 py-4">
                    ' . $team['description'] . '
                    </td>
                    <td class="px-6 py-4">
                    ' . $team['mana_name'] . '
                    </td>
                    <td class="px-6 py-4">
                    ' . $team['type_name'] . '
                    </td>

                    <td class="px-1-py-4">
                        <a href="update_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500">Update</a>
                        <a href="delete_pitch.php?pitch=' . $team['pitch_id'] . '" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</a>
                    </td>

                    </tr>';
        }

        echo '</tbody>
                    </table>
        </div>
        ';
    }
    public function getAllBooking()
    {
        $query = "SELECT * FROM booking";
        $result = $this->select($query);
    }
    public function getAllManager()
    {
        $query = "SELECT * FROM manager";
        $result = $this->select($query);
        return $result;
    }
    public function getType()
    {
        $query = "SELECT * FROM type";
        $result = $this->select($query);
        return $result;
    }
    public function createTournament()
    {
    }
    public function getAllReferee()
    {
    }

    public function createPitch()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name_pitch = $_POST['name_pitch'];
            $manager_post = $_POST['manager'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';

            $insert_pitch = "INSERT INTO pitch (name,deleted, description, manager_id, type_id) VALUES (:name_pitch,:deleted,:des,:manager_id,:type_id)";
            $pitch_arr = array(
                ":name_pitch" => $name_pitch,
                ":deleted"=>0,
                ":des" => $des,
                ":manager_id" => $manager_post,
                ":type_id" => $type_post
            );

            $result_pitch = $this->insert($insert_pitch, $pitch_arr);
        
            if ($result_pitch > 0) {
                $id_pitch = $this->getInsertId();
                for ($i = 1; $i < 19; $i++) {
                    $sql = "insert into pitch_detail (duration_id, pitch_id, price_id) values (:duration, :pitch, :price)";

                    if ($i < 5) {
                        if ($type_post == 1) {
                            $price = 1;
                        } else if ($type_post == 2) {
                            $price = 5;
                        } else {
                            $price = 9;
                        }
                    } else if ($i < 10) {
                        if ($type_post == 1) {
                            $price = 2;
                        } else if ($type_post == 2) {
                            $price = 6;
                        } else {
                            $price = 10;
                        }
                    } else if ($i < 13) {
                        if ($type_post == 1) {
                            $price = 3;
                        } else if ($type_post == 2) {
                            $price = 7;
                        } else {
                            $price = 11;
                        }
                    } else if ($i >= 13) {
                        if ($type_post == 1) {
                            $price = 4;
                        } else if ($type_post == 2) {
                            $price = 8;
                        } else {
                            $price = 12;
                        }
                    }

                    $arr = array(":duration" => $i, ":pitch" => $id_pitch, ":price" => $price);

                    $result_pd = $this->insert($sql, $arr);
                    
                }
                if($result_pd > 0){
                    echo 'tạo sân thành công <a href="index.php?url=pitch">Quay lại</a>';

                }
            }
        }
        $managers = $this->getAllManager();
        $types = $this->getType();
        echo '<form class="max-w-sm mx-auto" method="post" action="create_pitch.php">
        <div class="mb-5">
            <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
            <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required />
            </div>
            <div class="mb-5">
            
            <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
            <select id="manager" name="manager" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';
        foreach ($managers as $manager) {
            echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
        }

        echo '</select>
        </div>

        <div class="mb-5">

            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            ';
        foreach ($types as $type) {
            echo '<option value="' . $type['id'] . '">' . $type['name'] . '</option>';
        }
        echo '

            </select>
        </div>
        <div class="mb-5">
            <label for="descript" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chú thích</label>
            <textarea id="message" rows="4" name="descript" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
        </div>


        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
    </form>';
    }
    

    public function inputPitch(){
        $id = $_GET['pitch'];

        if($_SERVER['REQUEST_METHOD']=='POST'){

            $name_pitch = $_POST['name_pitch'];
            $manager_post = $_POST['manager'];
            $type_post = $_POST['type'];
            $des = $_POST['descript'] ?? '';

            $update_query = "UPDATE pitch SET name=:name, description=:descript, manager_id=:manager_id, type_id=:type_id WHERE id=:id";
            
            $params =array(
                ":name"=>$name_pitch,
                ":descript"=>$des,
                ":manager_id"=>$manager_post,
                ":type_id"=>$type_post,
                ":id"=>$id
            );
            
            $result = $this->update($update_query,$params);
            
            
            if ($result > 0) {
                echo "Cập nhật thành công";
            } else {
                echo "Không có thay đổi nào được thực hiện.";
            }
        }


        if($_SERVER['REQUEST_METHOD']=='GET'){
            $query = "SELECT * FROM pitch WHERE id = :id";
            $params = array(":id"=>$id);
            $result = $this->select($query,$params);
            if($result > 0){
                $pitch = $result[0];
                $managers = $this->getAllManager();
                $types = $this->getType();
                // them 1 đóng input vào r update
                echo '<form class="max-w-sm mx-auto" method="post" action="update_pitch.php?pitch='.$id.'">
                <div class="mb-5">
                    <label for="name_pitch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên sân</label>
                    <input type="text" id="name_pitch" name="name_pitch" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" value="'.$pitch['name'].'" />
                    </div>
                    <div class="mb-5">
                    
                    <label for="manager" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn quản lý</label>
                    <select id="manager" name="manager" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    ';
                foreach ($managers as $manager) {
                    echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
                }
        
                echo '</select>
                </div>
        
                <div class="mb-5">
        
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chọn loại sân</label>
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        ';
                foreach ($types as $type) {
                    echo '<option value="' . $type['id'] . '">' . $type['name'] . '</option>';
                }
                echo '
        
                    </select>
                </div>
                <div class="mb-5">
                    <label for="descript" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chú thích</label>
                    <textarea id="message" rows="4" name="descript" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                </div>
                <div class="flex justify-between">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>
                <a href="index.php?url=pitch" class="font-medium text-blue-600 underline">Quay lại</a>
                </div>
                </form>';
            }

        }
    }

    public function updatePitch(){
        
    }
}
