<?php
include_once('connection.php');

if(isset($_GET['keyword'])){
    $keyword = "%" . $_GET['keyword'] . "%";
    $sql ="SELECT * FROM product WHERE name LIKE ? OR description LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = array();


    while($row = mysqli_fetch_assoc($result)) {
        $found = true;
        $sql = "SELECT * FROM ProductImage WHERE product_id = ? ORDER BY id LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $row['id']);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $fect =mysqli_fetch_assoc($res);
        $imageUrl =$fect['image_url'];
        
        $product = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $imageUrl,
            'quantity' =>$row['quantity']
        );

        array_push($products, $product);
    }


    header('Content-Type: application/json');
    echo json_encode($products);
}
?>
