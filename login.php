<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>
<div id="login-box" class="" style="place-items: center;">
    <h2>LOGIN</h2>
    <?php
    if(isset($_POST['submit'])){
        $us =$_POST['IN_username'];
        $pa=$_POST['IN_password'];
        include_once("connection.php");
        // Escape special characters in a string
        $us = mysqli_real_escape_string($conn, $us);
        $pa = mysqli_real_escape_string($conn, $pa);
        $encrypted_password = password_hash($pa, PASSWORD_DEFAULT);
        $sq="select * from user where username='$us'";
        $res= mysqli_query($conn,$sq) or die(mysqli_error($conn));
        $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
        if(mysqli_num_rows($res)==1) {
            $encrypted_password = $row['password'];
            if (password_verify($pa, $encrypted_password)) {
                // mật khẩu hợp lệ
                $_SESSION["us"]=stripslashes($us);
                $_SESSION["us_id"]=$row['id'];
                echo '<script>Swal.fire({
                        icon: "success",
                        title: "Ok",
                        text: "Bạn đã đăng nhập!",
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function(){
                        window.location.href = "index.php";
                    });
                </script>';
            } else {
                // mật khẩu không hợp lệ
                echo '<script>Swal.fire({
                        icon: "error",
                        title: "Sai mật khẩu",
                        text: "Thử lại.",
                        showConfirmButton: true,
                    });</script>';
            }
        } else {
            // mật khẩu không hợp lệ
            echo '<script>Swal.fire({
                    icon: "error",
                    title: "Sai mật khẩu hoặc tài khoản",
                    text: "Thử lại.",
                    showConfirmButton: true,
                });</script>';
        }
    }
?>
    <form method="POST">
        <p>Tên đăng nhập</p>
        <input type="text" id="IN_username" name="IN_username" required placeholder="Nhập tài khoản">
        <p>Mật khẩu</p>
        <input type="password" id="IN_password" name="IN_password" required placeholder="Nhập mật khẩu">
        <input type="submit" name="submit" id="btnLogin" value="Login">
        <p>Không có tài khoản?<a href="?page=signup"> Đăng kí</a></p>
    </form>
</div>
