<?php
    header("Content-type:text/html;charset=utf-8");

    $strat = microtime(true);
    require_once './Cache.php';
    // url = " ?index=grade"
    $name = isset($_GET['name'])?$_GET['name']:'';
    $data = new Cache();

    $connect = new Connect();

    $result = $connect->getInstance()->link($name);

    var_dump($result);
    $end = microtime(true);

    echo '时间差:'. ($end-$strat)*100000;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

</body>
</html>