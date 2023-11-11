<?php
// Kết nối tới cơ sở dữ liệu
include_once('connection.php');

// Lấy id sản phẩm cần cập nhật từ URL
$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// Truy vấn để lấy thông tin chi tiết của sản phẩm
$sql = "SELECT * FROM Product WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);

// Lấy thông tin sản phẩm từ kết quả truy vấn
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$name = mysqli_real_escape_string($conn, $row['name']);
	$description = mysqli_real_escape_string($conn, $row['description']);
	$price = mysqli_real_escape_string($conn, $row['price']);
	$quantity = mysqli_real_escape_string($conn, $row['quantity']);
	$category_id = mysqli_real_escape_string($conn, $row['category_id']);
} else {
	echo "Product does not exist.";
	exit();
}
$row = mysqli_fetch_assoc($result);
// Truy vấn để lấy danh sách các danh mục
$sql = "SELECT id, name FROM ProductCategory";
$result = mysqli_query($conn, $sql);

// Tạo các tùy chọn cho thẻ select
$options = '<option value="">--Select category--</option>';
while ($row = mysqli_fetch_assoc($result)) {
	$selected = ($row['id'] == $category_id) ? 'selected' : '';
	$options .= '<option value="' . $row['id'] . '" ' . $selected . '>' . mysqli_real_escape_string($conn, $row['name']) . '</option>';
}



// Tạo các checkbox để chọn xóa hình ảnh
$checkboxes = '';
while ($rowI = mysqli_fetch_assoc($result)) {
    $checkboxes .= '<label>';
    $checkboxes .= '<input type="checkbox" name="delete_images[]" value="' . mysqli_real_escape_string($conn, $rowI['id']) . '"> Delete';
    $checkboxes .= '</label>';
    $checkboxes .= '<br>';
    
    // Hiển thị đường dẫn URL của hình ảnh
    $image_url = mysqli_real_escape_string($conn, $rowI['image_url']);
    $checkboxes .= '<img src="../' . $image_url . '" height="100">';
    $checkboxes .= '<br>';
}
?>

<body>
  <h1>Cập nhật sản phẩm</h1>
  <form method="post" action="" id="formUP" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <label for="name">Tên sản phẩm:</label><br>
    <input type="text" id="name" name="name" value="<?php echo stripslashes($name); ?>" required><br>
    <label for="description">Mô tả:</label><br>
    <textarea id="description" name="description" required><?php echo stripslashes($description); ?></textarea><br>
    <label for="price">Giá:</label><br>
    <input type="number" id="price" name="price" min="0" value="<?php echo stripslashes($price); ?>" required><br>
    <label for="quantity">Số lượng:</label><br>
    <input type="number" id="quantity" name="quantity" min="0" value="<?php echo stripslashes($quantity); ?>" required><br>
    <label for="category_id">Hãng:</label><br>
    <select id="category" name="category_id" required>
      <?php echo stripslashes($options); ?>
    </select><br><br>
    <label>Image:</label><br>
<?php echo stripslashes($checkboxes); ?>
<input type="file" id="images" name="images[]" multiple>
    <br>
    <br>
    <a href="?mpage=manageProduct" id="btn-back">Trở về</a>
    <input type="submit" name="updateProduct" value="Update">
  </form>
</body>
<?php
if (isset($_POST['updateProduct'])) {
    // Lấy thông tin sản phẩm từ form
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
   
  
    // Xử lý xóa các hình ảnh được chọn
    if (isset($_POST['delete_images'])) {
        $delete_images = $_POST['delete_images'];
        foreach ($delete_images as $image_id) {
          $image_id = mysqli_real_escape_string($conn, $image_id);
          $sql = "SELECT image_url FROM ProductImage WHERE id = '$image_id'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $image_path = "../" . $row['image_url'];
          
          // Xóa bản ghi trong cơ sở dữ liệu
          $sql = "DELETE FROM ProductImage WHERE id = '$image_id'";
          mysqli_query($conn, $sql);
      
          // Xóa file ảnh tương ứng trong thư mục Images
          unlink($image_path);
        }
    }
  
    // Thực hiện truy vấn để cập nhật sản phẩm
    $sql = "UPDATE Product SET name='$name', description='$description', price='$price', quantity='$quantity', category_id='$category_id' WHERE id='$product_id'";
    if (mysqli_query($conn, $sql)) {
        if (isset($_FILES['images'])) {
            $fileCount = count($_FILES['images']['name']);
            
            // Lưu các ảnh mới vào cơ sở dữ liệu
            for ($i = 0; $i < $fileCount; $i++) {
              // Kiểm tra xem ảnh có lỗi không
              if ($_FILES['images']['error'][$i] == UPLOAD_ERR_OK) {
                // Lưu ảnh vào thư mục uploads
                $target_dir = "Images/";
                $target_file = $target_dir . basename($_FILES["images"]["name"][$i]);
                $disk_url = '../'. $target_file;
                move_uploaded_file($_FILES["images"]["tmp_name"][$i],$disk_url);
                
                // Lưu thông tin ảnh vào cơ sở dữ liệu
                $sql = "INSERT INTO ProductImage (product_id, image_url) VALUES ('$product_id', '$target_file')";
                mysqli_query($conn, $sql);
              }
            }
            echo '<script>
  Swal.fire({
      title: "Success!",
      text: "Đã cập nhật.",
      icon: "success",
      showConfirmButton: false,
      timer: 2000
  }).then(function() {
      window.location.href = "?mpage=manageProduct";
  });
</script>';
        }
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
