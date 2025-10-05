<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';
?>

<!-- 
    CSS ชั่วคราว: เพื่อให้สีเหลืองทองปรากฏบน Card และ CTA
    โดยอ้างอิงจากสีหลักของ มมส. (เทา: #6C757D, เหลืองทอง: #FFD700)
-->
<style>
    .msu-yellow-text {
        color: #FFD700 !important;
    }
    .msu-gray-bg {
        background-color: #6C757D !important;
        color: white;
    }
    .btn-msu-gray {
        background-color: #6C757D;
        border-color: #6C757D;
        color: white;
    }
    .btn-msu-gray:hover {
        background-color: #495057;
        border-color: #495057;
    }
</style>

<!-- 🌟 MSU Header/Banner (ตักศิลาแห่งอีสาน) -->
<div class="p-4 mb-5 msu-gray-bg text-center shadow-lg rounded-3">
    <h1 class="display-5 fw-bold msu-yellow-text">Welcome to MSU Nisit Shop!</h1> 
    <p class="lead mb-0">ศูนย์รวมเครื่องแบบและของที่ระลึกสำหรับนิสิต "ตักศิลาแห่งอีสาน"</p>
</div>

<!-- 🎉 Hero Carousel -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner text-center rounded-3 shadow-lg">

        <!-- Item 1: เสื้อนิสิตหญิง -->
        <div class="carousel-item active">
            <img src="1.png" 
                 class="d-block mx-auto" 
                 alt="เสื้อนิสิตหญิง"
                 style="height:50vh; max-width:100%; object-fit:contain;">
            <div class="carousel-caption d-none d-md-block msu-gray-bg bg-opacity-75 rounded-3 p-3 animate__animated animate__fadeInUp">
                <h5 class="fw-bold display-6 msu-yellow-text">เสื้อนิสิตหญิง (ทรงสุภาพ)</h5>    
                <p class="lead">เนื้อผ้าคุณภาพดี ใส่สบาย พร้อมส่งทันที</p>
            </div>
        </div>

        <!-- Item 2: ชุดพละ -->
        <div class="carousel-item">
            <img src="1.png" 
                 class="d-block mx-auto" 
                 alt="ชุดพละ"
                 style="height:50vh; max-width:100%; object-fit:contain;">
            <div class="carousel-caption d-none d-md-block msu-gray-bg bg-opacity-75 rounded-3 p-3 animate__animated animate__fadeInUp">
                <h5 class="fw-bold display-6 msu-yellow-text">ชุดพละ มมส. (สีเหลือง-เทา)</h5>
                <p class="lead">สั่งล่วงหน้าได้ ลดการรอคิวที่ ม.ใหม่</p>
            </div>
        </div>
    </div>

    <!-- ปุ่มเลื่อน -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon btn-msu-gray rounded-circle p-3"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon btn-msu-gray rounded-circle p-3"></span>
    </button>
</div>

<!-- 🔥 Section แนะนำสินค้า -->
<section class="album py-5" style="background-color: #F0F0F0;"> 
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-secondary">✨ NEW ARRIVAL for MSU! ✨</h2>
            <p class="text-muted">สินค้าใหม่ล่าสุดที่นิสิต มมส. ต้องมี</p>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

    <!-- สินค้า 1 -->
    <div class="col">
        <div class="card shadow-lg h-100 rounded-4 border-0">
            <img src="1.png" class="card-img-top rounded-top-4" alt="สินค้า 1" style="height:250px; object-fit:cover;">
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-secondary">สินค้าใหม่ 1</h5>
                <p class="card-text fw-bold fs-5 msu-yellow-text">฿200</p>
                <a href="products.php" class="btn btn-msu-gray w-100 rounded-pill">ดูสินค้าเพิ่มเติม</a>
            </div>
        </div>
    </div>

    <!-- สินค้า 2 -->
    <div class="col">
        <div class="card shadow-lg h-100 rounded-4 border-0">
            <img src="2.png" class="card-img-top rounded-top-4" alt="สินค้า 2" style="height:250px; object-fit:cover;">
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-secondary">สินค้าใหม่ 2</h5>
                <p class="card-text fw-bold fs-5 msu-yellow-text">฿450</p>
                <a href="products.php" class="btn btn-msu-gray w-100 rounded-pill">ดูสินค้าเพิ่มเติม</a>
            </div>
        </div>
    </div>

    <!-- สินค้า 3 -->
    <div class="col">
        <div class="card shadow-lg h-100 rounded-4 border-0">
            <img src="3.png" class="card-img-top rounded-top-4" alt="สินค้า 3" style="height:250px; object-fit:cover;">
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-secondary">สินค้าใหม่ 3</h5>
                <p class="card-text fw-bold fs-5 msu-yellow-text">฿350</p>
                <a href="products.php" class="btn btn-msu-gray w-100 rounded-pill">ดูสินค้าเพิ่มเติม</a>
            </div>
        </div>
    </div>

</div>

    </div>
</section>

<!-- 🚀 CTA Section -->
<div class="my-5 p-5 text-center shadow-lg rounded-4" style="background-color: #FFD700;"> 
    <h3 class="fw-bold text-secondary">ตามคำขวัญ: "ผู้มีปัญญา พึงเป็นอยู่เพื่อมหาชน"</h3>
    <p class="text-dark">เช็คสต๊อกแบบเรียลไทม์ เลือกวันรับที่ร้าน ไม่พลาดของหมด</p>
    <a href="products.php" class="btn btn-msu-gray btn-lg rounded-pill">🛒 ช้อปสินค้า มมส. ทั้งหมด</a>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
