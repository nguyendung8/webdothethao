<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

   if(isset($_POST['add_to_cart'])){//thêm sách vào giỏi hàng từ form submit name='add_to_cart'

      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $product_quantity = $_POST['product_quantity'];

      if($product_quantity==0){
         $message[] = 'Sản phẩm đã hết hàng!';
      }
      else{
         $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

         if(mysqli_num_rows($check_cart_numbers) > 0){//kiểm tra sách có trong giỏ hàng chưa và tăng số lượng
            $result=mysqli_fetch_assoc($check_cart_numbers);
            $num=$result['quantity']+$product_quantity;
            $select_quantity = mysqli_query($conn, "SELECT * FROM `products` WHERE name='$product_name'");
            $fetch_quantity = mysqli_fetch_assoc($select_quantity);
            if($num>$fetch_quantity['quantity']){
               $num=$fetch_quantity['quantity'];
            }
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$num', price = '$product_price' WHERE name = '$product_name' AND user_id = '$user_id'");
            $message[] = 'Sản phẩm đã có trong giỏ hàng và được thêm số lượng!';
         }else{
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'Sản phẩm đã được thêm vào giỏ hàng!';
         }
      }

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cửa hàng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .list-cate {
         font-size: 20px;
         display: flex;
         gap: 10px;
         justify-content: center;
         padding-bottom: 20px;
         border: 1px solid #ddd;
         border-radius: 5px;
         margin: auto;
         margin-bottom: 20px;
         padding: 10px;
         width: fit-content;
         align-items: center;
         box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
      }
      .list-cate a {
         color: #b40202;
         border-right: 1px solid #b40202;
         padding-right: 18px;
      }
      .list-cate a:hover {
         opacity: 0.7;
      }
      .list-cate a:first-child {
         padding-left: 18px;
      }
      .list-cate a:last-child {
         padding-right: 18px;
         border-right: none;
      }
      .view-product {
         margin-top: 5px;
         padding: 5px 20px;
         background-color: burlywood;
         font-size: 16px;
         color: #fff;
         border-radius: 6px;
      }
      .view-product:hover {
         opacity: 0.9;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="products">

   <h1 class="title">Tất cả sản phẩm</h1>

   <!-- <select class="sort-box" onchange="window.location = this.options[this.selectedIndex].value">
      <option>Sắp xếp</option>
      <option value="?field=id& sort=DESC">Sản phẩm mới nhất</option>
      <option value="?field=id& sort=ASC">Sản phẩm cũ nhất</option>
      <option value="?field=newprice& sort=ASC">Giá tăng dần</option>
      <option value="?field=newprice& sort=DESC">Giá giảm dần</option>
   </select> -->
   <div class="list-cate">
      <?php  
         $select_categoriess = mysqli_query($conn, "SELECT * FROM `categorys`") or die('query failed');
         if(mysqli_num_rows($select_categoriess) > 0){
            while($fetch_categoriess = mysqli_fetch_assoc($select_categoriess)){
      ?>
                  <a href="?cate_id=<?php echo $fetch_categoriess['id']; ?> "><?php echo $fetch_categoriess['name']; ?></a>
      <?php
            }
         }else{
            echo '<p class="empty">Chưa có danh mục nào!</p>';
         }
      ?>
   </div>
   <div style="clear:both"></div>

   <div class="box-container">

      <?php  
         $select_num= mysqli_query($conn, "SELECT id FROM `products`");
         $querry =  mysqli_query($conn, "SELECT * FROM `products` LIMIT 1");
         $first_catge_id = mysqli_fetch_assoc($querry);
         if(mysqli_num_rows($select_num) > 0){
            if(isset($_GET['cate_id'])) {
               $cate_id = $_GET['cate_id'];
            } else {
               $cate_id = $first_catge_id['cate_id'];
            }
            $select_products = mysqli_query($conn, "SELECT p.* FROM products p JOIN categorys c ON p.cate_id = c.id  WHERE cate_id = $cate_id AND p.quantity > 0") or die('query failed');
            while($fetch_products = mysqli_fetch_assoc($select_products)){
                  ?>
                     <form style="height: -webkit-fill-available;" action="" method="post" class="box">
                        <img width="207px" height="191px" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <div class="sub-name">Thương hiệu: <?php echo $fetch_products['trademark']; ?></div>
                        <div class="price"><span style="text-decoration-line:line-through; text-decoration-thickness: 2px; text-decoration-color: grey"><?php echo number_format($fetch_products['price'],0,',','.' ); ?></span> VND /<?php echo number_format($fetch_products['newprice'],0,',','.' ); ?>(-<?php echo $fetch_products['discount']; ?>%)</div>
                        <span style="font-size: 17px; display: flex;">Số lượng mua:</span>
                        <input type="number" min="<?=($fetch_products['quantity']>0) ? 1:0 ?>" max="<?php echo $fetch_products['quantity']; ?>" name="product_quantity" value="<?=($fetch_products['quantity']>0) ? 1:0 ?>" class="qty">
                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['newprice']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <a href="product_detail.php?product_id=<?php echo $fetch_products['id'] ?>" class="view-product" >Xem thông tin</a>
                        <input type="submit" value="Thêm vào giỏ hàng" name="add_to_cart" class="btn">
                     </form>
                  <?php
               }
         }else{
            echo '<p class="empty">Chưa có sản phẩm được bán!</p>';
         }
      ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>