<!DOCTYPE HTML>
<HTML>
  <footer>
    <a href="adminfirst.php">
  <button>Back to Admin</button>
</a>

</footer>
</HTML>
<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "webapp";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function run($conn, $sql, $label) {
  if (mysqli_query($conn, $sql)) {
    echo $label . " : SUCCESS<br>";
  } else {
    echo $label . " : FAILED → " . mysqli_error($conn) . "<br>";
  }
}

$users = "CREATE TABLE IF NOT EXISTS users (
  user_id VARCHAR(15) NOT NULL,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(100) NOT NULL,
  gender VARCHAR(7) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(15),
  address VARCHAR(255),
  date_registered DATETIME NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE (username),
  UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$product = "CREATE TABLE IF NOT EXISTS product (
  product_id VARCHAR(15) NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL,
  url VARCHAR(255) NOT NULL ,
  PRIMARY KEY (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$cart = "CREATE TABLE IF NOT EXISTS cart (
  cart_id VARCHAR(15) NOT NULL,
  user_id VARCHAR(15) NOT NULL,
  PRIMARY KEY (cart_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$cart_item = "CREATE TABLE IF NOT EXISTS cart_item (
  cartItem_id VARCHAR(15) NOT NULL,
  cart_id VARCHAR(15) NOT NULL,
  product_id VARCHAR(15) NOT NULL,
  quantity INT NOT NULL,
  PRIMARY KEY (cartItem_id),
  FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$payment = "CREATE TABLE IF NOT EXISTS payment (
  payment_id VARCHAR(15) NOT NULL,
  cart_id VARCHAR(15) NOT NULL,
  order_id VARCHAR(15) NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  payment_date DATETIME NOT NULL,
  payment_status TINYINT(1) NOT NULL,
  payment_method VARCHAR(20) NOT NULL,
  PRIMARY KEY (payment_id),
  FOREIGN KEY (cart_id) REFERENCES cart(cart_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$payment_item = "CREATE TABLE IF NOT EXISTS payment_item (
  paymentItem_id VARCHAR(15) NOT NULL,
  payment_id VARCHAR(15) NOT NULL,
  product_id VARCHAR(15) NOT NULL,
  quantity INT NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (paymentItem_id),
  FOREIGN KEY (payment_id) REFERENCES payment(payment_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

run($conn,$users,"Create users table");
run($conn,$product,"Create product table");
run($conn,$cart,"Create cart table");
run($conn,$cart_item,"Create cart_item table");
run($conn,$payment,"Create payment table");
run($conn,$payment_item,"Create payment_item table");

$i_users = "INSERT IGNORE INTO users
(user_id, name, username, gender, email, password, phone, address, date_registered)
VALUES
('A0001','Emily Soo','Emily77','female','Emily77@gmail.com','-','011-4571284','-','2024-01-01 10:00:00'),
('A0002','Urkanish Ismail','urkanishmail','male','urkanishm@gmail.com','-','012-48963751','Lot 12, Taman Bukit Indah, 93350 Kuching, Sarawak','2024-01-01 10:00:00'),
('A0003','Sarajohn Son','sarajohnson','male','smartboy@gmail.com','-',NULL,'-','2024-01-01 10:00:00'),
('A0004','Mikael Yow','mikael456','male','mikael123@gmail.com','-',NULL,'No. 7, Jalan Kempas 1, Taman Kempas Baru, 81200 Johor Bahru, Johor','2024-01-01 10:00:00'),
('A0005','May Historia','historiaMay','female','may1@gmail.com','-','016-7845554','24, Jalan Meranti 3/2, Taman Sri Muda, 40400 Shah Alam, Selangor','2024-01-01 10:00:00')";


$i_product = "INSERT IGNORE INTO product VALUES
('PRD-202504-1A7X','3-Wheel Electric Scooter','Vehicles',9998,99,NULL),
('PRD-202504-2F9K','4WD Lamborghini Sian','Hobby and Leisure',2999,50,NULL),
('PRD-202504-5Y2M','Dinosaur Pleo','Hobby and Leisure',2999,24,NULL),
('PRD-202504-6P7V','R2D2','Hobby and Leisure',2798,68,NULL),
('PRD-202504-7W5C','Star Wars Monopoly','Hobby and Leisure',798,34,NULL)";

$i_cart = "INSERT IGNORE INTO cart VALUES
('C0001','A0001'),
('C0002','A0002'),
('C0003','A0003'),
('C0004','A0004'),
('C0005','A0005')";

$i_payment = "INSERT IGNORE INTO payment VALUES
('P001','C0001','0001',9998,'2024-01-02 10:00:00',1,'Credit/Debit'),
('P002','C0002','0002',2999,'2024-01-02 10:00:00',1,'Credit/Debit'),
('P003','C0003','0003',2999,'2024-01-02 10:00:00',1,'Credit/Debit'),
('P004','C0004','0004',2798,'2024-01-02 10:00:00',1,'Online Banking'),
('P005','C0005','0005',798,'2024-01-02 10:00:00',1,'Paypal')";

$i_payment_items = "INSERT IGNORE INTO payment_item (paymentItem_id, payment_id, product_id, quantity, subtotal) VALUES
('PI0001','P001','PRD-202504-1A7X',1,9998),
('PI0002','P002','PRD-202504-2F9K',1,2999),
('PI0003','P003','PRD-202504-2F9K',1,2999),
('PI0004','P004','PRD-202504-6P7V',1,2798),
('PI0005','P005','PRD-202504-7W5C',1,798)";

$i_cart_items = "INSERT IGNORE INTO cart_item (cartItem_id, cart_id, product_id, quantity) VALUES
('CT0001','C0001','PRD-202504-1A7X',1),
('CT0002','C0002','PRD-202504-2F9K',1),
('CT0003','C0003','PRD-202504-2F9K',1),
('CT0004','C0004','PRD-202504-6P7V',1),
('CT0005','C0005','PRD-202504-7W5C',1)";

run($conn,$i_users,"Insert users");
run($conn,$i_product,"Insert products");
run($conn,$i_cart,"Insert carts");
run($conn,$i_payment,"Insert payments");
run($conn , $i_cart_items,"Insert cart items");
run($conn , $i_payment_items,"Insert payment items");
?>
