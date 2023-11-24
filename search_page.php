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
   <title>Trang tìm kiếm</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
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
      .sub-name {
         text-align: center !important;
         margin-bottom: 10px;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="Tìm sản phẩm..." class="box" value=" <?php if(isset($_POST['submit'])) echo($_POST['search'])?>">
      <input type="submit" name="submit" value="Tìm kiếm" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">
      <?php
         if(isset($_POST['submit'])){
            $search_item = $_POST['search'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
                  <form style="height: -webkit-fill-available;" action="" method="post" class="box">
                     <img  width="207px" height="191px" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                     <div class="name"><?php echo $fetch_products['name']; ?></div>
                     <div class="sub-name">Thương hiệu: <?php echo $fetch_products['trademark']; ?></div>
                     <div class="price"><span style="text-decoration-line:line-through; text-decoration-thickness: 2px; text-decoration-color: grey"><?php echo number_format($fetch_products['price'],0,',','.' ); ?></span> <u style="text-decoration: underline !important;">đ</u> /<?php echo number_format($fetch_products['newprice'],0,',','.' ); ?> <u style="text-decoration: underline !important;">đ</u> (-<?php echo $fetch_products['discount']; ?>%)</div>
                     <!-- <input type="number" min="<?=($fetch_products['quantity']>0) ? 1:0 ?>" max="<?php echo $fetch_products['quantity']; ?>" name="product_quantity" value="<?=($fetch_products['quantity']>0) ? 1:0 ?>" class="qty"> -->
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                     <input type="hidden" name="product_price" value="<?php echo $fetch_products['newprice']; ?>">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                     <a href="product_detail.php?product_id=<?php echo $fetch_products['id'] ?>" class="view_product" >Xem thông tin</a>
                     <a href="create_order.php?product_id=<?php echo $fetch_products['id'] ?>" class="create_order" >Đặt hàng</a>
                  </form>
      <?php
               }
            }else{
               echo '<p class="empty">Không tìm thấy!</p>';
            }
         }else{
            echo '<p class="empty"">Hãy tìm kiếm gì đó!</p>';
         }
      ?>
   </div>
  

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>