<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>
<script src="script.js"></script>
 <div class="container">
  <h1>Chi tiết giỏ hàng</h1>
  <table>
    <thead>
      <tr>
        <th>Hình ảnh</th>
        <th>Tên sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
        <th>Tổng</th>
      </tr>
    </thead>
    <tbody id="cart-table-body">
      <?php
      $total = 0;
      $fee = 0;
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-checkout'])) {
        $_SESSION['cart'] = json_decode($_POST['cart'], true);
      }
      // Xử lý giá trị của biến cart ở đây
      if (!empty($_SESSION['cart'])) {

        foreach ($_SESSION['cart'] as $item) {
          $total += $item['price'] * $item['quantity'];
          ?>
          <tr>
            <td><img src="<?php echo $item['image'] ?>" alt="<?php echo $item['name'] ?>"></td>
            <td>
              <?php echo $item['name'] ?>
            </td>
            <td>
              <?php echo $item['quantity'] ?>
            </td>
            <td>$
              <?php echo $item['price'] ?>
            </td>
            <td>$
              <?php echo $item['price'] * $item['quantity'] ?>
            </td>
          </tr>
        </tbody>
        <?php
        }
        $fee = ($total / 160 > 3) ? $total / 160 : 3;
        ?>
      <?php
      } else {
        ?>
      <td colspan="5" style="text-align:center">Không co sản phẩm nào được thêm vào giỏ hàng.</td>
      <?php
      }
      ?>
    <tfoot>
      <tr>
        <td colspan="4" style="text-align:right">Giá:</td>
        <td>
          <?php echo $total ?>$
        </td>
      </tr>
      <tr>
        <td colspan="4" style="text-align:right">Phí vận chuyển:</td>
        <td>
          <?php 
          $fee =number_format($fee, 2, '.', '');
          echo $fee; ?>$
        </td>
      </tr>
      <tr>
        <td colspan="4" style="text-align:right">Tổng :</td>
        <td>
          <?php echo $total + $fee ?>$
        </td>
      </tr>
    </tfoot>
    <!-- add more products as needed -->

  </table>
  <form style="all:unset;" method='POST'>
    <div class="pay_Method">
      <h3>Phương thức thanh toán</h3>
      <label>
        <input type="radio" checked name="payment_method" value="cash_on_delivery">
        Thanh toán khi nhận hàng
      </label>
    </div>
    <button type="submit" class="checkout-btn" name="checkout-btn" id="checkout-btn">Thanh toán</button>
  </form>
</div>

<?php
// Kiểm tra xem người dùng đã đăng nhập hay chưa
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!isset($_SESSION["us_id"])) {
    // Chuyển hướng đến trang đăng nhập hoặc đăng ký
    echo '<meta http-equiv="refresh" content="0;url=?page=login"/>';
  } else {
    // Kết nối đến cơ sở dữ liệu
    include_once('connection.php');
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
      echo "<script>swal.fire('Giỏ trống', 'Giỏ hàng của bạn đang trống', 'warning').then(() => {window.location.href = 'index.php'});</script>";
      exit();
  }
    //kiểm tra số lượng
    foreach ($_SESSION['cart'] as $product) {
      $product_id = $product['id'];
      $quantity = $product['quantity'];
      $price = $product['price'];

      // Lấy số lượng sản phẩm hiện có trong kho
      $get_product_quantity_query = "SELECT quantity FROM Product WHERE id = ?";
      $stmt = mysqli_prepare($conn, $get_product_quantity_query);
      mysqli_stmt_bind_param($stmt, "i", $product_id);
      mysqli_stmt_execute($stmt);
      $product_quantity = mysqli_stmt_get_result($stmt)->fetch_assoc()['quantity'];

      if ($quantity > $product_quantity) {
        // In thông báo lỗi và dừng đơn hàng
        echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Sorry, Hiện tại chỉ còn $product_quantity sản phẩm .',
    });
</script>";

        exit;
      }
      $total += $fee;
      $user_id = $_SESSION["us_id"];
      $invoice_number = 'INV-' . time(); // Số hóa đơn được tạo từ timestamp
      $order_date = date('Y-m-d'); // Ngày đặt hàng
      $deliver = date('Y-m-d', strtotime($order_date . ' + 5 days'));
      $status='Not Confirm';
      $query_user = "SELECT address, phone FROM user WHERE id = ?";
      $stmt = mysqli_prepare($conn, $query_user);
      mysqli_stmt_bind_param($stmt, "i", $user_id);
      mysqli_stmt_execute($stmt);
      $result_user = mysqli_stmt_get_result($stmt);
      $row_user = mysqli_fetch_assoc($result_user);
      
    }
    if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash_on_delivery') {
      // Tạo đơn hàng mới
      $query = "INSERT INTO Invoice (invoice_number, order_date, delivery_date, total,  user_id, address, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, "sssssss", $invoice_number, $order_date, $deliver, $total, $user_id, $address, $phone);
    
      if (mysqli_stmt_execute($stmt)) {
        $invoice_id = mysqli_insert_id($conn);
    
      } else {
        die('Lỗi: ' . mysqli_error($conn));
      }
      // Duyệt qua mảng cart và tạo chi tiết đơn hàng
      foreach ($_SESSION['cart'] as $product) {
        $product_id = $product['id'];
        $quantity = $product['quantity'];
        $price = $product['price'];
        $insert_invoice_detail_query = "INSERT INTO InvoiceDetail (quantity, price, product_id, invoice_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_invoice_detail_query);
        mysqli_stmt_bind_param($stmt, "ssss", $quantity, $price, $product_id, $invoice_id);
        mysqli_stmt_execute($stmt);
    
        // Trừ số lượng sản phẩm trong bảng Product
        $update_product_quantity_query = "UPDATE Product SET quantity = quantity - ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_product_quantity_query);
        mysqli_stmt_bind_param($stmt, "ss", $quantity, $product_id);
        mysqli_stmt_execute($stmt);
      }
      unset($_SESSION['cart']);
      ?>
      <script>
        var cart = [];
        localStorage.setItem('cart', JSON.stringify(cart));
        swal.fire({
          title: "Thành công!",
          text: "Bạn đã đặt hàng thành công!",
          type: "success",
          confirmButtonText: "OK"
      }).then(function() {
          window.location = "index.php";
      });
      </script>
      <?php
    }
    
  }
}

?>

