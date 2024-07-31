<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css">
</head>
<body>
    
<?php 
require "./config.php";
require ROOT . "/include/function.php";
spl_autoload_register("loadClass");
$db = new Db;
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    var_dump($_GET);
    if(isset($_GET['confirm'])){
        $tour_id = 1;
        $query =  "SELECT * FROM team where  tournament_id = :tour_id";
        $params = array(
            ":tour_id" => $tour_id
        );
        $teams = $db->select($query, $params);
        if($teams){
        echo 'Data';
        }else{
            echo "No data";
        
        }
    }

}

?>
<form action="test.php?" method="get">

    <!-- Modal toggle -->
    <button 
    data-modal-target="default-modal" 
    data-modal-toggle="default-modal" 
    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" 
    type="submit"
    name="confirm">
        Toggle modal
    </button>
</form>

<!-- Main modal -->


</body>
</html>