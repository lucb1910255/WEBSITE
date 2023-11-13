<?php
session_start();
include_once("connection.php");
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta charset="utf-8" />
  <title>Tân Lực</title>
  <link rel="icon" href="Images/logo.webp" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="styles.css">
  <script src="script.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<?php
include_once('connection.php');
$sq = 'select id,name from productcategory';
$res = mysqli_query($conn, $sq);
$listPro = '';
while ($row = mysqli_fetch_assoc($res)) {
  $listPro .= '<li><a href="?catSelect=' . stripslashes($row['id']) . '">' . stripslashes($row['name']) . '</a></li>';
}
?>

<body>
<header>

    <div class="logo">
      <a href="?page=index">Tân Lực shop</a>
    </div>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn"><i class="fa fa-bars"></i></label>
    <div class="navigation">
      <a href="index.php">Trang Chủ</a>
      <a href="" id="product-link">Sản phẩm<span>&#9662</span></a>

      <div id="product-list" class="hidden">
        <ul>
          <?php echo $listPro; ?>
        </ul>
      </div>
      <a href="?page=aboutus">Về Chúng Tôi</a>

    </div>
    <div class="wrapper">
      <ul>
        <li data-tooltip="Tìm Kiếm"><a href="?page=searchPage"><i class="fa fa-search"></i></a></li>
        <?php
        if (!isset($_SESSION["us"])) { ?>
          <li data-tooltip="Đăng Nhập"><a href="?page=login" id="login-btn"><i class="fa fa-user-circle"
                aria-hidden="true"></i></a></li>
          <li data-tooltip="Đăng Ký"><a href="?page=signup" id="signup-btn"><i class="fa fa-user-plus"
                aria-hidden="true"></i></a></li>

        <?php } else { ?>
          <li data-tooltip="<?php echo $_SESSION['us']; ?>" id="user-control">
            <a href=""><i class="fa fa-id-card-o" aria-hidden="true"></i></a>
          </li>
          <li data-tooltip="Đăng Xuất"><a href="?page=signout" id=""><i class="fa fa-sign-out" aria-hidden="true"></i></a>
          </li>
        <?php } ?>

        <li data-tooltip="Giỏ Hàng" class="btn-cart">
          <a>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3"
              viewBox="0 0 16 16">
              <path
                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </svg>
            <sup id="totalCart">0</sup>
          </a>
        </li>
      </ul>
    </div>    
    <div class="shopping-cart hidden" id="shopping-cart">
      <h3 style="text-align:center">Giỏ hàng</h3>
      <div class="cart-items"></div>
      <div class="cart-total">
        <h3>Tổng: <span class="total-amount"></span></h3>
        <button class="btn-clear-cart">Xóa</button>
        <form method="POST" action="?page=checkout_details" style="all:unset;">
          <!-- Các trường thông tin người dùng -->
          <input type="hidden" name="cart" id="cart" value="">
          <button class="btn-checkout" name="btn-checkout" id="btn-checkout"
            onclick="viewCart();window.location.href='?page=checkout_cart';">Thanh toán</button>
        </form>
      </div>
    </div>



    <script>
      var productLink = document.getElementById("product-link");
      var productList = document.getElementById("product-list");
      productLink.addEventListener("click", function (event) {
        event.preventDefault(); 
        
        // Chuyển đổi giữa hiển thị và ẩn danh sách sản phẩm
        productList.classList.toggle("hidden");
         // Thay đổi nội dung của liên kết "Product" để thể hiện trạng thái hiện tại của danh sách
         if (productList.classList.contains("hidden")) {
          productLink.innerHTML = "Sản phẩm<span>&#9662;</span>";
        } else {
          productLink.innerHTML = "Sản phẩm<span>&#9652;</span>";
        }       
      });


      const cartIcon = document.querySelector('.btn-cart');
      const shoppingCart = document.querySelector('#shopping-cart');

      cartIcon.addEventListener('click', () => {
        event.preventDefault();
        shoppingCart.classList.toggle('hidden');
      });
      document.addEventListener("click", function (event) {
        if (!shoppingCart.contains(event.target) && !cartIcon.contains(event.target)) {
          shoppingCart.classList.add("hidden");
        }
      });
      //clear cart
      let btnClearCart = document.querySelector('.btn-clear-cart');
      btnClearCart.addEventListener('click', function () {
        cart = [];
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
        totalCart();
      });

      window.addEventListener('load', function () {
        renderCart();
        totalCart();
      });
      
    </script>
  </header>

  <body>
    <?php
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
      if ($page == 'index') {
        include_once('content.php');
      }
      if ($page == 'login') {
        include_once('Login.php');
      }
      if ($page == 'signup') {
        include_once('Signup.php');
      }
      if ($page == 'signout') {
        include_once('Logout.php');
      }
      if ($page == 'aboutus') {
        include('aboutus.php');
      }
      if ($page == 'product_detail') {
        include('product_detail.php');
      }
      if ($page == 'checkout_details') {
        include('detail_cart.php');
      }
      if ($page == 'searchPage') {
        include('searchPage.php');
      }
    } else {
      include_once('content.php');
    }
    ?>
  </body>
  
</script>
  <footer>
    <div class="footer-container">
      <div class="footer-section">
        <h3>About us</h3>
        <p style="text-align:justify;">Tân Lực shop là một cửa hàng chuyên cung cấp các dòng điện thoại mới, chất lượng nhất.</p>
      </div>
      <div class="footer-section">
        <h3>Liên hệ</h3>
        <ul>
          <li><i class="fa fa-map-marker" aria-hidden="true"></i>Can Tho, VietNam</li>
          <li><i class="fa fa-phone"></i>(+84) 787902919</li>
          <li><i class="fa fa-envelope"></i>info@tanlucshop.com</li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Kết nối</h3>
        <ul>
          <li><a href="#"><i class="fa fa-facebook"></i>Facebook</a></li>
          <li><a href="#"><i class="fa fa-twitter"></i>Twitter</a></li>
          <li><a href="#"><i class="fa fa-instagram"></i>Instagram</a></li>
        </ul>
      </div>
    </div>
  </footer>
</html>
