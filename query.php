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
('PRD-202504-1A7Y','Bicycle','Vehicles',7998,99,'product_pic/category-vehicles(1).jpg','The Mobility-friendly E-VéLO Step Thru eBike is a mix of stylish sophistication and practicality. As seen in the image, it features a mint-green/teal step-through frame which allows for easy mounting and dismounting.','1'),
('PRD-202504-1A7Z','Roller Skate','Vehicles',998,99,'product_pic/rollerskate.png','Step into a timeless classic with these Roces High-Top Roller Skates, designed to combine the iconic aesthetic of vintage canvas sneakers with the fun of quad skating. Featuring a durable black canvas upper with contrasting white stitching and laces, these skates offer a stylish, retro look that stands out at the rink or on the street. The high-top boot design ensures essential ankle support for better balance, while the robust black wheels and reliable front toe stop provide smooth handling and safety. Finished with the authentic Roces logo on the tongue, these skates are the perfect choice for anyone looking to enjoy a comfortable, recreational ride with a touch of old-school cool.','1'),
('PRD-202504-1A8A','HDMI Cable','Electronics',998,99,'product_pic/hdmi.png','Experience crystal-clear visuals and immersive audio with this Premium High-Speed HDMI Cable. Designed for modern home entertainment and gaming setups, this cable connects your devices effortlessly while delivering stunning 4K Ultra HD resolution. Whether you are streaming movies, gaming on a console, or presenting from a laptop, this cable ensures a stable, lag-free connection with rich, vibrant colors and deep contrast. Built for durability, it features gold-plated connectors to resist corrosion and a flexible, tangle-free braided nylon jacket that withstands daily wear and tear.','1'),
('PRD-202504-1A8B','Type C Cable','Electronics',898,99,'product_pic/typeC.png','Power up your devices in record time with this high-performance USB-C cable. Engineered for efficiency, it supports fast charging protocols to quickly replenish your smartphone, tablet, or laptop battery. Beyond charging, it offers reliable high-speed data transfer, allowing you to sync photos, videos, and large files in seconds. Designed with a premium nylon braided exterior, this cable is built to last. It resists tangling and fraying, making it the perfect everyday companion for home, office, or travel use.','1'),
('PRD-202504-1A8C','Display Port Cable','Electronics',898,99,'product_pic/DP.png','Unlock the full potential of your high-performance monitor with this professional-grade DisplayPort 1.4 cable. Engineered specifically for PC gaming and creative workstations, this cable delivers bandwidth up to 32.4Gbps, supporting incredibly high resolutions and blazing-fast refresh rates that standard cables cant match. It is the ideal choice for gamers seeking a competitive edge with smooth, tear-free visuals, or designers who need perfect color accuracy on 8K or multi-monitor setups. The secure latching mechanism ensures your connection never slips loose during intense use.','1'),
('PRD-202504-8W5A','Mouse','Electronic Devices',2938,34,'product_pic/mouse.png','Enhance your productivity with this sleek and reliable Wireless Optical Mouse. Designed for all-day comfort, it features a contoured grip that fits naturally in your hand, helping to reduce wrist strain during long work sessions. Whether you are in a busy office or a quiet library, the Silent Click buttons allow you to work without disturbing others. With a stable 2.4GHz wireless connection, you can enjoy complete freedom of movement and a clutter-free desk, free from tangled wires.','1'),
('PRD-202504-8W5B','Keyboard','Electronic Devices',1998,34,'product_pic/keyboard.png','Designed for the modern workplace, this reliable Membrane Keyboard offers a comfortable and quiet typing experience. Unlike loud mechanical keyboards, the soft-touch membrane keys provide a smooth, cushioned feel that minimizes noise, making it ideal for open offices, libraries, or shared workspaces. Its slim, low-profile design keeps your hands in a natural position to reduce fatigue, while the durable construction is built to withstand daily heavy usage. Simple and efficient, it is the perfect tool for data entry, document writing, and everyday computer tasks.','1'),
('PRD-202504-8W5C','Monitor','Electronic Devices',1798,34,'product_pic/monitor.jpg','Upgrade your workspace with this crisp and clear 24-inch Full HD monitor. Built with an IPS (In-Plane Switching) panel, it delivers vivid colors and sharp details from virtually any angle, making it perfect for collaborating with colleagues or streaming your favorite shows. The sleek, 3-sided frameless design not only looks modern but also creates a seamless viewing experience if you are using a dual-monitor setup. Engineered for long hours of use, it features advanced eye-care technology to reduce flickering and blue light emission, helping to prevent eye strain during marathon work sessions.','1'),
('PRD-202504-2F9K','4WD Lamborghini Sian','Hobby and Leisure',2999,50,'product_pic/lambogini.png','A high-performance 4WD remote-controlled Lamborghini Sian designed for speed, durability, and realistic driving experience. This model features precision steering, powerful motors, and a sleek aerodynamic body inspired by the real Lamborghini Sian. Suitable for hobbyists and collectors, it delivers smooth handling on multiple surfaces and long-lasting playtime.','1'),
('PRD-202504-5Y2M','Dinosaur Pleo','Hobby and Leisure',2999,24,'product_pic/dino.png','An interactive robotic dinosaur designed for entertainment and learning. Pleo responds to touch, sound, and movement, creating a lifelike companion experience. It can express emotions, learn behaviors over time, and interact with its environment. Perfect for children and technology enthusiasts who enjoy interactive robotic toys with personality.','1'),
('PRD-202504-6P7V','R2D2','Hobby and Leisure',2798,68,'product_pic/R2D2.png','A collectible R2-D2 robot inspired by the iconic Star Wars universe. This model features authentic design details, sound effects, and movement functions that bring the character to life. Ideal for Star Wars fans, collectors, and hobbyists looking for a detailed and interactive display piece.','1'),
('PRD-202504-7W5C','Star Wars Monopoly','Hobby and Leisure',999,34,'product_pic/monopoly.png','A special edition Monopoly board game themed around the Star Wars universe. Players can buy, trade, and conquer iconic locations from the franchise while enjoying classic Monopoly gameplay. Includes custom tokens, themed cards, and immersive artwork. Perfect for family game nights and Star Wars fans of all ages.','1'),
('PRD-202509-1R1W','Robot, MiP Robot (WowWee)','Robot',798,34,'product_pic/robot-mib-6.png','A two-wheeled smart robot with a sleek white design and LED eyes. It can moves autonomously, controlled using hand gestures or mobile app, as an intelligent companion robot.','1'),
('PRD-202508-1R4T','i-Sobot','Robot',2998,34,'product_pic/i-sobot.png','World smallest humanoid robot with 17cm tall. It features 17 servo motors, 19 sensors, voice recognition and advanced balance control, allowed to walk, dance, speak and perform over 200 programmed actions. Controlled by remote control or voice commands, offers high-tech entertainment.','1'),
('PRD-202504-7W5D','Remote-Controlled Robot Arm Kit','Robot',498,34,'product_pic/robot-arm.png','Hands-on DIY robotics kit that lets users build and control their own robot arm. It features 5 degrees of freedom, LED lighting and can be remote controlled or via PC with USB connection. Easy to ensemble without soldering, act as an educational fun introduction to basic robotucs for all ages.','1'),
('PRD-202509-1R1Y','Sofa','Home Living',798,34,'product_pic/sofa.jpg','Transform your living room into a cozy sanctuary with this elegant Modern Nordic 3-Seater Sofa. Designed with a perfect balance of style and comfort, it features a minimalist silhouette that complements both contemporary and traditional interiors. Upholstered in breathable, high-quality linen fabric, this sofa is soft to the touch yet durable enough for everyday family use. The seat cushions are filled with high-density sponge that retains its shape over time, providing superior support whether you are sitting up for a conversation or lounging back for a movie night. Supported by sturdy solid wood legs, it offers long-lasting stability and a touch of natural warmth to your space.','1'),
('PRD-202509-1R1X','Bulb','Home Living',98,34,'product_pic/bulb.png','Transform the atmosphere of any room instantly with this versatile Smart Wi-Fi LED Bulb. Whether you want a cozy warm white for reading, a bright cool white for working, or vibrant colors for a party, this bulb does it all. Control it directly from your smartphone via the app or use voice commands to adjust brightness and color without lifting a finger. Energy-efficient and long-lasting, it replaces standard 60W incandescent bulbs while using significantly less power. With easy setup and no hub required, it is the simplest way to upgrade to a smart home lighting system.','1'),
('PRD-202509-1R1Z','Bed','Home Living',1198,34,'product_pic/bed.png','Create a restful retreat in your bedroom with this sophisticated Upholstered Platform Bed. Combining modern style with durable support, this bed frame features a soft, button-tufted headboard that adds a touch of luxury and provides comfortable back support for late-night reading or watching TV. Constructed with a robust interior steel framework and reinforced wooden slats, it offers exceptional stability and prevents your mattress from sagging without the need for a box spring. The fabric finish is neutral and elegant, designed to blend seamlessly with various decor styles, from contemporary to classic.','1');";
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
