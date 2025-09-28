<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';
?>

<!-- 🎉 Hero Carousel -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
  <div class="carousel-inner text-center">
    <div class="carousel-item active">
      <img src="uploads/1758443868_1.jpg" 
           class="d-block mx-auto rounded" 
           alt="เสื้อนิสิตหญิง"
           style="height:50vh; max-width:600px; object-fit:contain;">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3 animate__animated animate__fadeInUp">
        <h5 class="fw-bold display-6">เสื้อนิสิตหญิง</h5>    
        <p class="lead">เนื้อผ้าคุณภาพดี ใส่สบาย พร้อมส่งทันที</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="uploads/1758449096_2.jpg" 
           class="d-block mx-auto rounded" 
           alt="ชุดพละ"
           style="height:50vh; max-width:600px; object-fit:contain;">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3 animate__animated animate__fadeInUp">
        <h5 class="fw-bold display-6">ชุดพละ</h5>
        <p class="lead">สั่งล่วงหน้าได้ ลดการรอคิว</p>
      </div>
    </div>
  </div>

  <!-- ปุ่มเลื่อน -->
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- 🔥 Section แนะนำสินค้า -->
<section class="album py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">✨ NEW ARRIVAL ✨</h2>
      <p class="text-muted">สินค้าใหม่ล่าสุดที่นักศึกษานิยมที่สุด</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <!-- สินค้า 1 -->
      <div class="col">
        <div class="card shadow-sm h-100">
          <img src="uploads/1758443868_1.jpg" class="card-img-top" alt="เสื้อนิสิตหญิง" style="height:250px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">เสื้อนิสิตหญิง</h5>
            <p class="card-text text-primary fs-5">฿200</p>
            <a href="product.php?id=1" class="btn btn-dark w-100">สั่งซื้อ</a>
          </div>
        </div>
      </div>

      <!-- สินค้า 2 -->
      <div class="col">
        <div class="card shadow-sm h-100">
          <img src="uploads/1758449096_2.jpg" class="card-img-top" alt="ชุดพละ" style="height:250px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">ชุดพละ</h5>
            <p class="card-text text-primary fs-5">฿450</p>
            <a href="product.php?id=2" class="btn btn-dark w-100">สั่งซื้อ</a>
          </div>
        </div>
      </div>

      <!-- สินค้า 3 (ตัวอย่างเพิ่มได้อีก) -->
      <div class="col">
        <div class="card shadow-sm h-100">
          <img src="uploads/1758443868_1.jpg" class="card-img-top" alt="สินค้าใหม่" style="height:250px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">สินค้าใหม่</h5>
            <p class="card-text text-primary fs-5">฿350</p>
            <a href="product.php?id=3" class="btn btn-dark w-100">สั่งซื้อ</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 🚀 CTA Section -->
<div class="my-5 p-5 bg-light rounded text-center shadow-sm">
  <h3 class="fw-bold">พร้อมสั่งซื้อแล้ววันนี้</h3>
  <p class="text-muted">เช็คสต๊อกแบบเรียลไทม์ เลือกวันรับที่ร้าน ไม่พลาดของหมด</p>
  <a href="products.php" class="btn btn-primary btn-lg">🛒 ช้อปเลย</a>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
