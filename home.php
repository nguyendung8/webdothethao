<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .box p {
         font-size: 17px;
         padding-bottom: 5px;
      }
      .action {
         display: flex;
         align-items: center;
      }
      .view_product {
         margin-top: 5px;
         padding: 5px;
         background-color: burlywood;
         font-size: 16px;
         color: #fff;
         border-radius: 6px;
      }
      .view_product:hover {
         opacity: 0.9;
      }
      .create_order {
         margin-top: 5px;
         padding: 5px 34px;
         background-color: purple;
         font-size: 16px;
         color: #fff;
         border-radius: 6px;
      }
      .create_order:hover {
         opacity: 0.8;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="products">

   <h1 class="title">Sản phẩm mới nhất</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC  LIMIT 8") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
               <form style="height: -webkit-fill-available;" action="" method="post" class="box">
                  <img width="207px" height="191px" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <?php
                     $cate_id =  $fetch_products['cate_id'];
                     $result= mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = $cate_id") or die('Query failed');
                     $cate_name = mysqli_fetch_assoc($result)
                   ?>
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <p style="padding-bottom: 16px;">Thương hiệu: <?php echo $fetch_products['trademark']; ?></p>
                  <div class="price"><span style="text-decoration-line:line-through; text-decoration-thickness: 2px; text-decoration-color: grey"><?php echo number_format($fetch_products['price'],0,',','.' ); ?></span> <u style="text-decoration: underline !important;">đ</u> /<?php echo number_format($fetch_products['newprice'],0,',','.' ); ?> <u style="text-decoration: underline !important;">đ</u> (-<?php echo $fetch_products['discount']; ?>%)</div>
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['newprice']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <a href="product_detail.php?product_id=<?php echo $fetch_products['id'] ?>" class="view_product">Xem thông tin</a>
                  <a href="create_order.php?product_id=<?php echo $fetch_products['id'] ?>" class="create_order" >Đặt hàng</a>
               </form>
      <?php
            }
         }else{
            echo '<p class="empty">Chưa có sản phẩm được bán!</p>';
         }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">Xem thêm sản phẩm</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img style="height: 348px; border-radius: 4px;" src="images/home-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>Sport</h3>
         <p>Thể dục thể thao là một phần không thể thiếu trong cuộc sống con người. Không chỉ giúp chúng ta rèn luyện sức khỏe mà còn giải tỏa được sự căng thẳng, mệt mỏi sau ngày dài làm việc.</p>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>Bạn có thắc mắc?</h3>
      <p>Hãy để lại những điều bạn còn thắc mắc, băn khoăn hay muốn chia sẻ thêm về những sản phẩm cho chúng mình tại đây để chúng mình có thể giải đáp giúp bạn</p>
      <a href="contact.php" class="white-btn">Liên hệ</a>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>