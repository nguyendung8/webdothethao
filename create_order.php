<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
   if(isset($_GET['product_id'])) {
      $product_id = $_GET['product_id'];
   }

   $sql = "SELECT * FROM products WHERE id = $product_id";
   $result = $conn->query($sql);
   $productItem = $result->fetch_assoc();

   if(isset($_POST['order_btn'])){//nhập thông tin đơn hàng từ form submit name='order_btn'
      
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $number = $_POST['number'];
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $method = mysqli_real_escape_string($conn, $_POST['method']);
      $address = mysqli_real_escape_string($conn,$_POST['street']);
      $note = mysqli_real_escape_string($conn, $_POST['note']);
      $total_products = $productItem['name'];
      $quantity_product = $_POST['quantity']; 
      $total_price = $productItem['newprice'] * $quantity_product;
      $placed_on = date('d-m-Y');
      $payment_status = "Chờ xác nhận";

      $remain_quantity = $productItem['quantity'] - $quantity_product;
      
      $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND note= '$note' AND total_products = '$total_products' AND total_price = '$total_price'") or die('query failed');
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'Đơn hàng đã tồn tại!'; 
      }else{
         mysqli_query($conn, "UPDATE `products` SET quantity = '$remain_quantity' WHERE id = $product_id") or die('query failed');
         mysqli_query($conn, "INSERT INTO `orders`(user_id, product_id, name, number, email, method, address, note, total_products, product_quantity, total_price, placed_on, payment_status) VALUES('$user_id', '$product_id', '$name', '$number', '$email', '$method', '$address', '$note', '$total_products', '$quantity_product', '$total_price', '$placed_on', '$payment_status')") or die('query failed');
         $message[] = 'Đặt hàng thành công!';
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đặt hàng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
       .modal{
         width: 500px;
         margin: auto;
         border: 2px solid #eee;
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
         border-radius: 5px;
         margin-bottom: 20px;
      }
      .modal-container{
         background-color:#fff;
         text-align: center;
      }
      .peoductdetail-title {
         font-size: 25px !important;
         padding-top: 10px;
         margin-bottom: 0px !important;
         color: red !important;
      }
      .peoductdetail-img {
         width: 230px;
         border-radius: 3px;
      }
      .peoductdetail-author {
         margin-top: 19px;
         font-size: 20px;
      }
      .peoductdetail-desc {
         margin-top: 20px;
         font-size: 16px;
      }
      .price {
         padding: 1rem;
         font-size: 20px;
         color: red;
      }
      .btn {
         display: flex;
         margin: auto;
         margin-top: 10px;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="checkout">
   <form action="" method="post">
      <div  class="modal">
         <div class="modal-container">
               <h3 class="peoductdetail-title"><?php echo($productItem['name']) ?></h3>
               <div>
                  <img class="peoductdetail-img" src="uploaded_img/<?php echo $productItem['image']; ?>" alt="">
               </div>
               <div class="price">Giá: <span style="text-decoration-line:line-through; text-decoration-thickness: 2px; text-decoration-color: grey"><?php echo number_format($productItem['price'],0,',','.' ); ?></span> <u style="text-decoration: underline !important;">đ</u> /<?php echo number_format($productItem['newprice'],0,',','.' );  ?> <u style="text-decoration: underline !important;">đ</u> (-<?php echo $productItem['discount']; ?>%)</div>
         </div>
      </div>
      <div class="flex">
         <div class="inputBox">
            <span>Họ tên:</span>
            <input type="text" name="name" required placeholder="Nguyễn Văn A">
         </div>
         <div class="inputBox">
            <span>Số điện thoại :</span>
            <input type="number" name="number" required placeholder="0123456789">
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" required placeholder="abc@gmail.com">
         </div>
         <div class="inputBox">
            <span>Số lượng :</span>
            <input type="number" name="quantity" min="<?=($productItem['quantity'] > 0) ? 1:0 ?>" max="<?php echo $productItem['quantity']; ?>" value="<?=($productItem['quantity']>0) ? 1:0 ?>" required placeholder="Nhập số lượng">
         </div>
         <div class="inputBox">
            <span>Phương thức thanh toán :</span>
            <select name="method">
               <option value="Tiền mặt khi nhận hàng">Tiền mặt khi nhận hàng</option>
               <option value="Thẻ ngân hàng">Thẻ ngân hàng</option>
               <option value="Paypal">Paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Địa chỉ :</span>
            <input type="text" name="street" required placeholder="Số nhà, số đường, phường/xã, huyện/thị xã">
         </div>
         <div class="inputBox">
            <span>Ghi chú:</span>
            <input type="text" name="note" required placeholder="Lời nhắn">
         </div>
      </div>
      <input type="submit" value="Đặt hàng" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>