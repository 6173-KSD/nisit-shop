<?php
session_start();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ตะกร้าสินค้า | MARKET</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- ชี้ CSS ให้ถูก (ไฟล์ style.css อยู่ root ของโปรเจ็กต์) -->
  <link rel="stylesheet" href="/project/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <!-- กลับหน้าแรกแบบสั้นสุด -->
    <a class="navbar-brand fw-bold" href="/project/">MARKET</a>
  </div>
</nav>

<main class="container py-5">
  <h3 class="fw-bold mb-4">ตะกร้าสินค้า</h3>
  <table class="table align-middle">
    <thead class="table-light">
      <tr>
        <th>สินค้า</th>
        <th>ชื่อสินค้า</th>
        <th>ราคา</th>
        <th>จำนวน</th>
        <th>รวม</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="cart-body"></tbody>
    <tfoot id="cart-foot"></tfoot>
  </table>

  <div class="d-flex justify-content-between mt-3">
    <button class="btn btn-outline-danger" onclick="clearCart()">ลบทั้งหมด</button>
    <div>
      <!-- ปุ่มเลือกซื้อสินค้าต่อ ให้กลับไปหน้าแรก -->
      <a href="/project/" class="btn btn-outline-dark me-2">เลือกซื้อสินค้าต่อ</a>
      <a href="checkout.php" class="btn btn-success">ชำระเงิน</a>
    </div>
  </div>
</main>

<script>
function renderCart(){
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const body = document.getElementById('cart-body');
  const foot = document.getElementById('cart-foot');

  if(cart.length === 0){
    body.innerHTML = `<tr><td colspan="6" class="text-center text-muted">ตะกร้าสินค้าว่างเปล่า</td></tr>`;
    foot.innerHTML = "";
    return;
  }

  let total = 0;
  body.innerHTML = cart.map((item, index)=>{
    let sum = item.price * item.qty;
    total += sum;
    return `<tr>
      <td><img src="${item.img}" width="60" onerror="this.src='/project/upload/placeholder.png'"></td>
      <td>${item.name}</td>
      <td>${item.price.toLocaleString()} ฿</td>
      <td><input type="number" value="${item.qty}" min="1" class="form-control" style="width:70px" onchange="updateQty(${index}, this.value)"></td>
      <td>${sum.toLocaleString()} ฿</td>
      <td><button class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">ลบ</button></td>
    </tr>`;
  }).join('');
  foot.innerHTML = `<tr><td colspan="4" class="text-end fw-bold">รวมทั้งหมด</td><td colspan="2" class="fw-bold">${total.toLocaleString()} ฿</td></tr>`;
}

function updateQty(index, qty){
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  qty = parseInt(qty);
  if(qty <= 0){ cart.splice(index, 1); }
  else { cart[index].qty = qty; }
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
}

function removeItem(index){
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart.splice(index, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
}

function clearCart(){
  if(confirm("ต้องการลบสินค้าทั้งหมดใช่ไหม?")){
    localStorage.removeItem("cart");
    renderCart();
  }
}

renderCart();
</script>
</body>
</html>
