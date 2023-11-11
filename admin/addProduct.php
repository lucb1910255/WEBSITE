<!DOCTYPE html>
<html>
<head>
	<title>Thêm sản phẩm</title>
</head>
<?php
// Kết nối tới cơ sở dữ liệu
include_once('connection.php');

// Truy vấn để lấy danh sách các danh mục
$sql = "SELECT id, name FROM ProductCategory";
$result = mysqli_query($conn, $sql);

// Tạo các tùy chọn cho thẻ select
$category = '<option value="">--Chọn hãng--</option>';
while ($row = mysqli_fetch_assoc($result)) {
    $category .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
}


?>
<body>
	<h1>Thêm sản phẩm mới</h1>
	<form method="post" action="" id="formAP" enctype="multipart/form-data">
		<label for="name">Tên :</label><br>
		<input type="text" id="name" name="name" required><br>
		<label for="description">Mô tả:</label><br>
		<textarea id="description" name="description" required></textarea><br>
		<label for="price">Giá:</label><br>
		<input type="number" id="price" name="price" min="0" required><br>
		<label for="image_url">Ảnh:</label><br>
		<input type="file" id="images" name="images[]" multiple required>
		<label for="quantity">Số lượng:</label><br>
		<input type="number" id="quantity" name="quantity" min="0" required><br>
		<label for="category_id">Hãng:</label><br>
		<select id="category" name="category_id" required>
    	<?php echo $category; ?>
		</select><br><br>
		</select><br><br>
		<input type="submit" name="addProduct" value="Thêm">
	</form>
	<?php 
	include_once('connection.php');
	
	if(isset($_POST['addProduct'])){
		$name = $_POST['name'];
		$description = $_POST['description'];
		$price = $_POST['price'];
		$quantity = $_POST['quantity'];
		$category_id = $_POST['category_id'];
		$images = $_FILES['images'];
		// Lưu sản phẩm vào bảng Product
		$sql = "INSERT INTO Product (name, description, price, quantity, category_id) VALUES (?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "ssiii", $name, $description, $price, $quantity, $category_id);
		$result = mysqli_stmt_execute($stmt);
		
		if ($result) {
		  // Lấy id của sản phẩm vừa được thêm vào
		  $product_id = mysqli_insert_id($conn);
		
		  // Lưu thông tin các hình ảnh vào bảng ProductImage
		  foreach ($images['tmp_name'] as $key => $tmp_name) {
			$image_name = $images['name'][$key];
			$image_url ="Images/$image_name";
			$disk_url="../Images/$image_name";
			move_uploaded_file($tmp_name, $disk_url);
		
			$sql = "INSERT INTO ProductImage (product_id, image_url) VALUES (?, ?)";
			$stmt = mysqli_prepare($conn, $sql);
			mysqli_stmt_bind_param($stmt, "is", $product_id, $image_url);
			mysqli_stmt_execute($stmt);
		  }
		
		  echo '<script>';
		  echo 'Swal.fire({';
		  echo '  icon: "success",';
		  echo '  title: "Thêm sản phẩm thành công",';
		  echo '  showConfirmButton: false,';
		  echo '  timer: 1500';
		  echo '}).then(function() {';
		  echo '  window.location.href="?page=manage&&mpage=manageProduct";';
		  echo '});';
		  echo '</script>';
		} else {
			echo '<script>';
			echo 'swal("Oops...", "Lỗi!", "error");';
			echo '</script>';			
		}		
	
	}
?>

</body>
</html>
