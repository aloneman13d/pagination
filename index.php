<?php
//connect db
$servername = "127.0.0.1";
$dbname = "pagination";
$username = "root";
$password = '';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

//spit page
$page = isset($_GET["page"]) ? (int) $_GET['page'] : 1;
$row_per_page = isset($_GET['row_per_page']) ? (int)$_GET['row_per_page'] : 3;
$start_row = ($page > 1) ? ($page * $row_per_page) - $row_per_page : 0;

$sql = "select * from users limit $start_row , $row_per_page ";
$statement = $conn->prepare($sql);
$statement->execute();
$resule = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <?php foreach ($resule as $v) : ?>
        <p><?php echo $v["id"]; ?> | <?php echo $v["name"]; ?></p>
    <?php endforeach; ?>

    <?php
    // nav page
    $sql = "select * from users ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $resule = $statement->fetchAll(PDO::FETCH_ASSOC);
    $total_rows = count($resule);
    ?>

    <?php
    //control nav
    $page_totol = ceil($total_rows / $row_per_page);

    $nav_show = 4;
    if (($nav_show % 2) == 0) {
        $nav_cal = $nav_show / 2;
        $nav_start = $page - $nav_cal;
        $nav_stop = ($page + $nav_cal) - 1;
    } else {
        $nav_cal = floor($nav_show / 2);
        $nav_start = $page - $nav_cal;
        $nav_stop = $page + $nav_cal;
    }


    if ($nav_start < 1) {
        $nav_start = 1;
    }
    if (($page_totol - $nav_start) < $nav_show) {
        $nav_start = ($page_totol - $nav_show) + 1;
    }

    if ($nav_stop > $page_totol) {
        $nav_stop = $page_totol;
    }
    if ($nav_stop <= $nav_show) {
        $nav_stop = $nav_show;
    }
    ?>



    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($page > 1) : ?>
                <li class="page-item disable">
                    <a class="page-link" href="?page=<?php echo ($page - 1) . '&row_per_page=' . $row_per_page; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($nav_start; $nav_start <= $nav_stop; $nav_start++) : ?>
                <?php if($nav_start==$page):?>
                    <li class="page-item active" aria-current="page"><a class="page-link" href="#"><?php echo $nav_start; ?></a></li>
                <?php else:?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $nav_start . '&row_per_page=' . $row_per_page; ?>"><?php echo $nav_start; ?></a></li>
                <?php endif;?>
            <?php endfor; ?>

            <?php if ($page < $page_totol) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page + 1) . '&row_per_page=' . $row_per_page; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>


</body>

</html>