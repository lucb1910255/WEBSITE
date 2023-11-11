<link rel="stylesheet" type="text/css" href="styleManage.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>

<div class="product-container">
	<div class="product-images">
    <div class="preview-image-container">
		<?php 
		            $product_id = $_GET['id'];
					$sql = "SELECT * FROM productimage WHERE product_id = $product_id";
					$result = mysqli_query($conn, $sql);
					$res=mysqli_query($conn, $sql);
					$ima =mysqli_fetch_assoc($result);
					?>
        <img src="<?php echo $ima['image_url']; ?>" alt="Product Image 1" class="preview-image">
    </div>
</div>
		<div class="product-details">
        <?php
        include_once('connection.php');
// Lấy thông tin sản phẩm từ database dựa trên tham số "id" trên URL
$id = $_GET['id'];
$sql = "SELECT * FROM product WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!-- Hiển thị thông tin chi tiết của sản phẩm -->

<img src="<?php echo $ima['image_url']; ?>" class="hidden" alt="">
<h1><?php echo $row['name']; ?></h1>
<p><?php echo $row['description']; ?></p>
<p id="price">Giá: <?php echo intval($row['price']); ?> VNĐ</p>
<input type="number" id="quantity" name="quantity" min="1" max="<?php echo $row['quantity']?>"value="1">
<button onclick="addToCart(event, <?php echo $row['id']; ?>)">Thêm vào giỏ </button>
		</div>
	</div>
	<script src="script.js"></script>
<script>
function changePreview(imageSrc) {
    var previewImage = document.querySelector('.preview-image');
    previewImage.src = imageSrc;
}

function addToCart(event, id) {
  event.preventDefault();
  let product = {
    name: event.target.parentNode.querySelector('h1').textContent,
    price: event.target.parentNode.querySelector('#price').textContent,
    image: event.target.parentNode.querySelector('img').getAttribute('src'),
    quantity: parseInt(document.querySelector('#quantity').value),
    id: id
  };
  let cartIndex = cart.findIndex(item => item.id == id);
  if (cartIndex === -1) {
    cart.push(product);
  } else {
    cart[cartIndex].quantity += product.quantity;
  }
  Swal.fire({
          icon: "success",
          title: "Đã thêm sản phẩm vào giỏ",
          showConfirmButton: false,
          timer: 2000})
  localStorage.setItem('cart', JSON.stringify(cart));
  totalCart();
  renderCart();
}

function redirectToBuyNow() {
  var quantity = document.getElementById("quantity").value;
  var productId = <?php echo $row['id']; ?>;
  var url = "?page=buynow&id=" + productId + "&quantity=" + quantity;
  window.location.href = url;
}
</script>