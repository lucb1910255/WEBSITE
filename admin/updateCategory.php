<!DOCTYPE html>
<html>
<head>
  <title>Cập nhật hãng hàng</title>
</head>
<?php
include_once('connection.php');
$id = $_GET['id'];
// Lấy thông tin của sản phẩm từ database
$stmt = mysqli_prepare($conn, "SELECT * FROM ProductCategory WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
?>
<body>
  <h1>Cập nhật hãng</h1>
  <form method="POST" action="">
  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
  <label>Tên:</label>
  <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
  <label>Mô tả:</label>
  <textarea name="description"><?php echo $row['description']; ?></textarea><br>
  <input type="submit" name="update" value="Cập nhật">
</form>
<?php 
include_once('connection.php');
if(isset($_POST['update'])){
    $id = $_POST['id'];
  $name = $_POST['name'];
  $description = $_POST['description'];

  // Cập nhật dữ liệu vào database
  $stmt = mysqli_prepare($conn, "UPDATE ProductCategory SET name=?, description=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $id);
if(mysqli_stmt_execute($stmt)){
  echo '<script>
  Swal.fire({
      title: "Success!",
      text: "Cập nhật thành công.",
      icon: "success",
      showConfirmButton: false,
      timer: 2000
  }).then(function() {
      window.location.href = "?mpage=manageCategory";
  });
</script>';
}else {
  echo "Thất bại: " . mysqli_error($conn);
}

}
?>
  
</body>
</html>
