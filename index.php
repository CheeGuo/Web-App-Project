<?php include("include/header.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Arngren</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <main class="index-main">
    <p style="text-align:center ;
              font-weight: bold ;
              font-size : 20px ; 
              ">Categories</p>
  <section id="feature" class="feature">

    <a href="vehicles.php" class="fe-link">
      <div class="fe-box">
        <img src="pic/category-car.png">
        <h6>Vehicles</h6>
      </div>
    </a>

    <a href="hobby.php" class="fe-link">
      <div class="fe-box">
        <img src="pic/category-hobby.png">
        <h6>Hobby and Leisure</h6>
      </div>
    </a>

    <a href="electronic.php" class="fe-link">
      <div class="fe-box">
        <img src="pic/category-electronic.png">
        <h6>Electronics</h6>
      </div>
    </a>

    <a href="electronics-devices.php" class="fe-link">
      <div class="fe-box">
        <img src="pic/category-electronics.png">
        <h6>Electronics Devices</h6>
      </div>
    </a>

    <a href="robot.php" class="fe-link">
      <div class="fe-box">
        <img src="pic/Category-robot.png">
        <h6>Robot</h6>
      </div>
    </a>

    <a href="home-living.php" class="fe-link">
      <div class="fe-box">
        <img src="pic/Category-homeliving.png">
        <h6>Home Living</h6>
      </div>
    </a>

  </section>

  <section class="promo-hot">
    <div class="promo-left">
      <img src="pic/CNY.png" alt="CNY Promotion">
    </div>

    <div class="promo-right">
      <h3>Hot Items</h3>

      <div class="hot-grid">
        <div class="hot-card">
          <img src="pic/dino.png">
          <p>Dinosaur Pleo</p>
          <span>2,999 kr</span>
        </div>

        <div class="hot-card">
          <img src="pic/dino.png">
          <p>4WD Land Rover Defender</p>
          <span>4,398 kr</span>
        </div>

        <div class="hot-card">
          <img src="pic/dino.png">
          <p>3-Wheel e-bike</p>
          <span>15,998 kr</span>
        </div>
      </div>
    </div>
  </section>


  </main>
  <?php include("include/footer-text.php"); ?>
</body>
</html>