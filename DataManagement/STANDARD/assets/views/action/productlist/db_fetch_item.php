<?php
require '../dbconnect/connectdb_wms.php';
$resultArray     = array();
$record_per_page = 30;
$page            = '';

if (isset($_POST["page"])) {
    $page = $_POST["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $record_per_page;
$query      = "SELECT * FROM product ";
$query      .= "ORDER BY idproduct DESC OFFSET $start_from LIMIT $record_per_page";

$stmt = $PDOconn->prepare($query);
$stmt->execute();

if ($stmt->execute()) {
    $result = $stmt->fetchAll();
    $output = '';

    $page_query = "SELECT * FROM product ORDER BY idproduct DESC";
    $stmt2      = $PDOconn->prepare($page_query);
    $stmt2->execute();

    $total_records = $stmt2->rowCount();

    $total_pages = ceil($total_records / $record_per_page);
    $output .= "<div class='row padding-10'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='" . $i . "'>" . $i . "</span>";
    }
    $output .= "</div>";

    foreach ($result as $row) {
        $output .= '
        <div class="col-md-3 col-sm-6">
                <div class="product-grid6 margin-bottom-20">
                    <div class="product-image6">
                        <a href="#">
                            <img class="pic-1" src="assets/images/product/' . $row["p_image"] . '">
                        </a>
                    </div>
                    <div class="product-content">
                        <h3 class="title"><a href="#">' . mb_strimwidth($row["product"], 0, 20, "..."). '</a></h3>
                        <div class="price">$11.00
                            <span>$14.00</span>
                        </div>
                    </div>
                    <ul class="social">
                        <li><a class="aside" data-tip="More info" data-idproduct="' . $row["idproduct"] . '"><i class="fa fa-info"></i></a></li>
                      
                    </ul>
                </div>
        </div>
        ';
    }
    echo $output;
};
