<script src="script.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>
<div class="banner">
            <img src="./Images/banner1.png" alt="banner 1">
            <img src="./Images/banner3.png" alt="banner 2">
            <img src="./Images/banner2.png" alt="banner 3">
    </div>
  
    
  <h2 style="text-align:center">Sản phẩm</h2>
  



    <div class="products">
        <ul>
            <?php
            include_once('connection.php');

            if(isset($_GET['catSelect'])){
                $id =$_GET['catSelect'];
                $sq="select * from product where category_id = '$id'";
                
            }else{
                $sq ="select * from product";
            }
            $result =mysqli_query($conn,$sq);
            
            while($row = mysqli_fetch_assoc($result)) {
                $sql = "SELECT * FROM ProductImage WHERE product_id = " . $row['id'] . " ORDER BY id LIMIT 1";
                $res = mysqli_query($conn,$sql);
                $fect =mysqli_fetch_assoc($res);
                $imageUrl =$fect['image_url'];
            ?>
            <li><img src="<?php echo $imageUrl; ?>" alt="">
                <h3><?php echo $row['name'] ;?></h3>
                <p><?php echo intval($row['price']); ?><span> VNĐ</span></p>
                <?php
                if($row['quantity']>0){
                    ?>
                <a href="#" class="btn btn-secondary" onclick="addToCart(event, '<?php echo $row['id']; ?>')">Thêm vào giỏ</a>
                <a href="?page=product_detail&&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-details">Chi tiết</a></li>
                <?php
                }
                else{
                    ?>
                <a href="#" class="btn btn-secondary out-stock" onclick="" style="pointer-events:none;">Hết hàng</a>    
                
                
            <?php }}?>
        </ul>
        <script>
           function addToCart(event, id) {
            event.preventDefault();
            let product = {
                name: event.target.parentNode.querySelector('h3').textContent,
                price: parseFloat(event.target.parentNode.querySelector('p').textContent.replace(" VNĐ", "")),
                image: event.target.parentNode.querySelector('img').getAttribute('src'),
                quantity:1,
                id: id
            }
            let cartIndex = cart.findIndex(item => item.id == id);
            if (cartIndex === -1) {
                cart.push(product);
            } else {
                cart[cartIndex].quantity++;
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
        </script>
    </div>
