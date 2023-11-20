"use strict";

var cart = [];

function renderCart() {
  if (localStorage.getItem('cart')) {
    cart = JSON.parse(localStorage.getItem('cart'));
  }
  let cartItems = document.querySelector('.cart-items');
  let totalAmount = document.querySelector('.total-amount');
  let totalPrice = cart.reduce((acc, cartItem) => acc + (cartItem.price * cartItem.quantity), 0);
  cartItems.innerHTML = '';
  if (cart.length === 0) {
    cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
    totalPrice = cart.reduce((acc, cartItem) => acc + (cartItem.price * 0), 0);
    totalAmount.textContent = totalPrice.toFixed(0);
    return;
  }
  for (let i = 0; i < cart.length; i++) {
    let cartItem = cart[i];
    let cartItemElem = document.createElement('div');
    cartItemElem.classList.add('cart-item');
    cartItemElem.innerHTML = `
      <img src="${cartItem.image}" alt="${cartItem.name}">
      <div class="item-info">
          <h3 class="item-name">${cartItem.name}</h3>
          <div class="item-actions">
          <button class="btn-quantity btn-minus">-</button>
          <p class="quantity">${cartItem.quantity}</p>
          <button class="btn-quantity btn-plus">+</button>
          <div>
          <button class="btn-remove" data-id="${cartItem.id}"><i class="fa fa-trash"></i></button>
          </div>
          </div>
      </div>
      <div class="item-price">${cartItem.price}</div>
      `;
    cartItems.appendChild(cartItemElem);
    //button plus and minus quantity
    let btnPlus = cartItemElem.querySelector('.btn-plus');
    btnPlus.addEventListener('click', function () {
      event.stopPropagation();
    
      let productId=cartItem.id;
    
      // Lời gọi AJAX để lấy số lượng sản phẩm trong kho
      let xhr = new XMLHttpRequest();
      xhr.open('GET', 'check_quantity.php?id=' + productId, true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          let product = JSON.parse(xhr.responseText);
          let quantityInStock = product.quantity;
    
          // Kiểm tra số lượng sản phẩm trong kho
          if (cartItem.quantity < quantityInStock) {
            cartItem.quantity += 1;
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
            totalCart();
          } else {
            alert('Sorry, there are only ' + quantityInStock + ' items in stock.');
          }
        } else {
          alert('Error: ' + xhr.status);
        }
      };
      xhr.send();
    });
    
    let btnMinus = cartItemElem.querySelector('.btn-minus');
    btnMinus.addEventListener('click', function () {
      event.stopPropagation();
      if (cartItem.quantity > 1) {
        cartItem.quantity -= 1;
        localStorage.setItem('cart', JSON.stringify(cart));

        renderCart();
        totalCart()
      }

    });
    //button remove product from cart
    let btnRemove = cartItemElem.querySelector('.btn-remove');
    btnRemove.addEventListener('click', function () {
      event.stopPropagation();
      let cartItemElem = event.target.closest('.cart-item');
      let proId = btnRemove.getAttribute('data-id');

      for (let i = 0; i < cart.length; i++) {
        if (cart[i].id == proId) {
          cart.splice(i, 1);
          break;
        }
      }
      localStorage.setItem('cart', JSON.stringify(cart));

      renderCart();
      totalCart();
    });

  }

  totalAmount.textContent = totalPrice.toFixed(2);
}

function totalCart() {
  let totalQuantity = 0;
  for (let i = 0; i < cart.length; i++) {
    totalQuantity += cart[i].quantity;
  }
  localStorage.setItem('cart', JSON.stringify(cart));
  let supElement = document.getElementById('totalCart');
  supElement.innerText = totalQuantity.toString();
}

function checkLenght() {
  const passwordInput = document.getElementById("password");
  const password = passwordInput.value;

  if (password.length < 8) {
    passwordInput.setCustomValidity("Password must be at least 8 characters");
  } else {
    passwordInput.setCustomValidity("");
  }
}
function checkPass() {
  const password = document.getElementById("password");
  const rePassword = document.getElementById("re_password");

  if (rePassword.value !== password.value) {
    rePassword.setCustomValidity("Passwords do not match");
  } else {
    rePassword.setCustomValidity("");
  }
}

function viewCart() {
  let sendCart = JSON.parse(localStorage.getItem('cart'));
  $('#cart').val(JSON.stringify(sendCart));
  onclick = "window.location.href='?page=checkout_cart'";
};

var user_control = document.getElementById("user-control");
var user_action = document.getElementById("user-action");

user_control.addEventListener("click", function (event) {
  event.preventDefault();

  // Chuyển đổi giữa hiển thị và ẩn danh sách sản phẩm
  user_action.classList.toggle("hidden");
});

// Ẩn danh sách sản phẩm khi người dùng click vào nơi khác trên trang web
document.addEventListener("click", function (event) {
  if (!user_action.contains(event.target) && !user_control.contains(event.target)) {
    user_action.classList.add("hidden");
  }
});