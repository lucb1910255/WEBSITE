<!DOCTYPE html>
<html>
<head>
	<title>Quản lí hãng điện thoại</title>
</head>
<body>

	<h1>Hãng hàng</h1>
    <a href="?mpage=addCategory">Thêm</a>
	<table>
		<tr>
			<th>ID</th>
			<th>Tên</th>
			<th>Mô tả</th>
			<th colspan="2">Edit</th>

		</tr>
		<?php
			// Thực hiện kết nối tới cơ sở dữ liệu
			include_once('connection.php');

			// Lấy danh sách các sản phẩm từ cơ sở dữ liệu
			$sql = 'SELECT * FROM ProductCategory';
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			    // Hiển thị dữ liệu lấy được từ cơ sở dữ liệu
			    while($row = mysqli_fetch_assoc($result)) {
			        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo stripslashes($row['name']); ?></td>
            <td><?php echo stripslashes($row['description']); ?></td>
            <td><a href='?mpage=updateCategory&&id=<?php echo $row['id']; ?>'>Cập nhật</a></td>
            <td><a href="?mpage=manageCategory&amp;function=del&amp;id=<?php echo $row['id']; ?>" onclick="event.preventDefault(); deleteConfirm(this)">Xóa</a>

		</td>

        </tr>
        <?php
			    }
			} else {
			    echo "<tr><td colspan='5'>Không thể thêm</td></tr>";
			}
		?>
	</table>
	<br>
	<?php
    include_once("connection.php");
    if(isset($_GET["function"])=="del"){
        if(isset($_GET['id'])){
            $id=$_GET["id"];

            // Kiểm tra xem category này có sản phẩm nào không
            $sql_check_product = "SELECT * FROM Product WHERE category_id = '$id'";
            $result_check_product = mysqli_query($conn, $sql_check_product);

            if (mysqli_num_rows($result_check_product) > 0) {
                // Nếu có sản phẩm liên kết thì không cho phép xóa
                echo "<script>
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Không thể xóa!'
				}).then(() => {
					window.location.href = '?mpage=manageCategory';
				});
			</script>";
            } else {
                // Nếu không có sản phẩm liên kết thì tiến hành xóa category
                mysqli_query($conn,"DELETE FROM ProductCategory WHERE id='$id'");
                echo "<script>
				Swal.fire({
					title: 'Category deleted!',
					text: 'Đã xóa.',
					icon: 'success',
					confirmButtonText: 'OK'
				}).then(function() {
					window.location.href = '?mpage=manageCategory';
				});
			</script>";
            }
        }
    } 
?>
</body>
</html>
