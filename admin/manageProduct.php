
</script>
<h1>Sản phẩm</h1>
    <a href="?page=manage&&mpage=addProduct">Thêm</a>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Tên</th>
      <th>Mô tả</th>
      <th>Giá</th>
      <th>Số lượng</th>
      <th>ID Hãng</th>
      <th colspan="2">Edit</th>
    </tr>
  </thead>
  <tbody>
  <?php
include_once('connection.php');

// Lấy dữ liệu từ bảng Product
$stmt = $conn->prepare("SELECT * FROM Product");
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra kết quả
if ($result->num_rows > 0) {
    // Hiển thị dữ liệu
    while($row = $result->fetch_assoc()) { ?>
      <tr>
          <td><?php echo htmlspecialchars($row["id"]); ?></td>
          <td><?php echo htmlspecialchars($row["name"]); ?></td>
          <td><?php echo htmlspecialchars($row["description"]); ?></td>
          <td><?php echo htmlspecialchars($row["price"]); ?></td>
          <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
          <td><?php echo htmlspecialchars($row["category_id"]); ?></td>
          <td>
              <a href="?mpage=updateProduct&amp;id=<?php echo htmlspecialchars($row["id"]); ?>">Cập nhật</a>
    </td>
    <td>
              <a href="?mpage=manageProduct&amp;function=del&amp;id=<?php echo htmlspecialchars($row["id"]); ?>" onclick="event.preventDefault(); deleteConfirm(this)">Xóa</a>
          </td>
      </tr>
  <?php }
} else { ?>
  <tr><td colspan="8">Không thể thêm</td></tr>
<?php } ?>


  </tbody>
</table>
<?php
  include_once("connection.php");
  if(isset($_GET["function"])=="del"){
    if(isset($_GET['id'])){
      $id= $_GET["id"];
          $sql_delete = "DELETE FROM product WHERE id=?";
          $stmt = mysqli_prepare($conn, $sql_delete);
          mysqli_stmt_bind_param($stmt, "i", $id);
          mysqli_stmt_execute($stmt);
          echo '<script>';
            echo 'Swal.fire({
                    icon: "success",
                    title: "Deleted!",
                    text: "Đã xóa!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3085d6"
                  }).then(function() {
                      window.location.href="?page=manage&mpage=manageProduct";
                  });';
            echo '</script>';
      }
  } 
?>
