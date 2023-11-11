<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>

<div id="signup-form">
  <?php
  require_once('connection.php');
  
  // Nếu người dùng đã bấm nút đăng kí
  if(isset($_POST['btnSignup'])) {
    $username = $_POST['username'];
    $password =$_POST['password'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password =password_hash($password, PASSWORD_DEFAULT);
    $stmt_check = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt_check->bind_param("s", $username);
$stmt_check->execute();
$result = $stmt_check->get_result();
  
if ($result->num_rows > 0) {
  echo '<script>
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: "Tên tài khoản đã tồn tại",
      confirmButtonText: "OK",
    });
  </script>';
} else {
    // Chuẩn bị truy vấn sử dụng Prepared Statements
    $stmt = $conn->prepare("INSERT INTO user (username, password, email, address, phone) VALUES (?, ?, ?, ?, ?)");
    
    // Bind các biến đến các tham số của Prepared Statement
    $stmt->bind_param("sssss", $username,$password , $email, $address, $phone);
    
    // Thực hiện truy vấn
    if($stmt->execute()) {
      echo '<script>
        Swal.fire({
          icon: "success",
          title: "Đăng kí thành công",
          confirmButtonText: "OK",
        }).then(function() {
          window.location.href = "?page=login";
        });
      </script>';
      echo '<meta http-equiv="refresh" content="2;URL=?page=login"/>';
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    // Đóng Prepared Statement
    $stmt->close();
  }}
  ?>
  
  <h2>SIGN UP</h2>
  <form method="POST">
    <label for="username">Tài khoản:</label>
    <input type="text" id="username" name="username" required placeholder="Nhập tên tài khoản">
    <label for="password">Mật khẩu:</label>
    <input type="password" id="password" name="password" oninput="checkLenght(); checkPass();" required placeholder="Nhập mật khẩu">
    <label for="re-password">Mật khẩu:</label>
    <input type="password" id="re_password" name="re_password" oninput="checkPass()" required placeholder="Nhập lại mật khẩu">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required placeholder="Nhập email">
    <label for="address">Địa chỉ:</label>
    <input type="text" id="address" name="address" required placeholder="Nhập địa chỉ">
    <label for="phone">SĐT:</label>
    <input type="number" id="phone" name="phone" required placeholder="Nhập số điện thoại">
    <input type="submit" name="btnSignup" value="Submit">
  </form>
</div>
