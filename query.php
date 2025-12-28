<!DOCTYPE HTML>
<HTML>
  <footer>
    <a href="adminfirst.php">
  <button>Back to Admin</button>
</a>

</footer>
</HTML>
<?php
include('include/db.php');
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
  role VARCHAR(9) , 
  date_registered DATETIME NOT NULL,
  profile_pic VARCHAR(255) ,
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
  description TEXT NOT NULL,
  is_active BOOLEAN , 
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

$email_reset="CREATE TABLE password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(15) NOT NULL,
  token_hash VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL
);
";
run($conn,$email_reset,"Create reset data");
run($conn,$users,"Create users table");
run($conn,$product,"Create product table");
run($conn,$cart,"Create cart table");
run($conn,$cart_item,"Create cart_item table");
run($conn,$payment,"Create payment table");
run($conn,$payment_item,"Create payment_item table");

$password = password_hash("12345678",PASSWORD_DEFAULT);
$password_admin = password_hash("admin123",PASSWORD_DEFAULT);

$i_users = "INSERT IGNORE INTO users
(user_id, name, username, gender, email, password, phone, address, role,date_registered,profile_pic)
VALUES
('A0000','Admin','Admin','-','admin@gmail.com','$password_admin','-','-','admin','2024-01-01 10:00:00',''),
('A0001','Emily Soo','Emily77','female','Emily77@gmail.com','$password','011-4571284','-','customer','2024-01-01 10:00:00',''),
('A0002','Urkanish Ismail','urkanishmail','male','urkanishm@gmail.com','$password' ,'012-48963751','Lot 12, Taman Bukit Indah, 93350 Kuching, Sarawak','customer','2024-01-01 10:00:00',''),
('A0003','Sarajohn Son','sarajohnson','male','smartboy@gmail.com','$password' ,NULL,'-','customer','2024-01-01 10:00:00',''),
('A0004','Mikael Yow','mikael456','male','mikael123@gmail.com','$password' ,NULL,'No. 7, Jalan Kempas 1, Taman Kempas Baru, 81200 Johor Bahru, Johor','customer','2024-01-01 10:00:00',''),
('A0005','May Historia','historiaMay','female','may1@gmail.com','$password' ,'016-7845554','24, Jalan Meranti 3/2, Taman Sri Muda, 40400 Shah Alam, Selangor','customer','2024-01-01 10:00:00','')";


$i_product = "INSERT IGNORE INTO product VALUES
('PRD-202504-1A7X','3-Wheel Electric Scooter','Vehicles',9998,99,'product_pic/scooter.png','A practical and stable 3-wheel electric scooter designed for daily commuting and leisure riding. Equipped with a powerful motor and a comfortable seat, this scooter offers excellent balance and safety, making it suitable for elderly users and those who prefer extra stability. Features include a sturdy steel frame, front basket for storage, smooth acceleration, and reliable braking system. Ideal for short-to-medium distance travel with minimal effort.','1'),
('PRD-202504-2F9K','4WD Lamborghini Sian','Hobby and Leisure',2999,50,'product_pic/lambogini.png','A high-performance 4WD remote-controlled Lamborghini Sian designed for speed, durability, and realistic driving experience. This model features precision steering, powerful motors, and a sleek aerodynamic body inspired by the real Lamborghini Sian. Suitable for hobbyists and collectors, it delivers smooth handling on multiple surfaces and long-lasting playtime.','1'),
('PRD-202504-5Y2M','Dinosaur Pleo','Hobby and Leisure',2999,24,'product_pic/dino.png','An interactive robotic dinosaur designed for entertainment and learning. Pleo responds to touch, sound, and movement, creating a lifelike companion experience. It can express emotions, learn behaviors over time, and interact with its environment. Perfect for children and technology enthusiasts who enjoy interactive robotic toys with personality.','1'),
('PRD-202504-6P7V','R2D2','Hobby and Leisure',2798,68,'product_pic/R2D2.png','A collectible R2-D2 robot inspired by the iconic Star Wars universe. This model features authentic design details, sound effects, and movement functions that bring the character to life. Ideal for Star Wars fans, collectors, and hobbyists looking for a detailed and interactive display piece.','1'),
('PRD-202504-7W5C','Star Wars Monopoly','Hobby and Leisure',798,34,'product_pic/monopoly.png','A special edition Monopoly board game themed around the Star Wars universe. Players can buy, trade, and conquer iconic locations from the franchise while enjoying classic Monopoly gameplay. Includes custom tokens, themed cards, and immersive artwork. Perfect for family game nights and Star Wars fans of all ages.','1')";

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
