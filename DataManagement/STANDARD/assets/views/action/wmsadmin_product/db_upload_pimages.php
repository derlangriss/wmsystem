<?php
require '../dbconnect/connectdb_wms.php';

if (!empty($_FILES)) {
    $idproduct        = $_POST["idproduct"];
    $tempPath         = $_FILES['file']['tmp_name'];
    $serverUploadpath = './assets/images/product/';
    $uploadPath       = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
    //file name
    $image_name = $_FILES['file']['name'];
    //file path
    $image_path = $serverUploadpath . $_FILES['file']['name'];
    //file extension
    $path_parts      = pathinfo($image_name);
    $image_extension = $path_parts['extension'];

    if (file_exists($uploadPath)) {
        $increment        = 0;
        list($name, $ext) = explode('.', $uploadPath);

        while (file_exists($uploadPath)) {
            $increment++;
            // $loc is now "userpics/example1.jpg"
            $uploadPath = $name . $increment . '.' . $ext;
            $filename   = $name . $increment . '.' . $ext;
        }

        move_uploaded_file($tempPath, $uploadPath);

    } else {

        move_uploaded_file($tempPath, $uploadPath);
    }

    $strSQL = "INSERT INTO pimages ";
    $strSQL .= "(pimages_path,pimages_name,pimages_extension,product_idproduct)";
    $strSQL .= "VALUES ";
    $strSQL .= "('" . $image_path . "','" . $image_name . "','" . $image_extension . "'," . $idproduct;
    $strSQL .= ")";
    $objQuery = pg_query($strSQL);
    $answer   = array('answer' => 'File transfer completed');
    $json     = json_encode($answer);
    echo $json;
} else {
    echo 'No files';
}
?>