<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
   $product_id = $_GET['product_id'];

   $sql = "SELECT * FROM products WHERE id = $product_id";
   $result = $conn->query($sql);
   $productItem = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Xem thông tin sản phẩm</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .view-product {
         padding: 15px;
      }
      .modal{
         width: 500px;
         margin: auto;
         border: 2px solid #eee;
         padding-bottom: 27px;
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
         border-radius: 5px;
      }
      .modal-container{
         background-color:#fff;
         text-align: center;
      }
      .peoductdetail-title {
         font-size: 21px;
         padding-top: 10px;
         color: #9e1ed4;
      }
      .peoductdetail-img {
         margin-top: 18px;
         width: 230px;
      }
      .peoductdetail-author {
         margin-top: 19px;
         font-size: 20px;
      }
      .peoductdetail-desc {
         margin-top: 20px;
         font-size: 16px;
      }
      .add_btn {
        display: flex;
        margin: auto;
        margin-top: 17px;
      }
      .create_order {
         width: fit-content;
         display: flex;
         margin: auto;
         margin-top: 17px;
         padding: 5px 34px;
         background-color: purple;
         font-size: 16px;
         color: #fff;
         border-radius: 4px;
      }
      .create_order:hover {
         opacity: 0.8;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Xem thông tin sản phẩm</h3>
   <p> <a href="home.php">Trang chủ</a> / Xem thông tin sản phẩm </p>
</div>

<section class="view-product">
   <?php if ($productItem) : ?>
        <div  class="modal">
            <div class="modal-container">
                <h3 class="peoductdetail-title">Xem sản phẩm <?php echo($productItem['name']) ?></h3>
                <div>
                    <img class="peoductdetail-img" src="uploaded_img/<?php echo $productItem['image']; ?>" alt="">
                </div>
                <p class="peoductdetail-author">
                    Thương hiệu: 
                    <?php echo ($productItem['trademark']) ?>
                </p>
                <p class="peoductdetail-author">
                    Số lượng còn: 
                    <?php echo ($productItem['quantity']) ?>
                </p>
                <p class="peoductdetail-desc">
                    Mô tả: 
                    <?php echo($productItem['describes'])  ?>
                </p>
            </div>
            <a href="create_order.php?product_id=<?php echo $productItem['id'] ?>" class="create_order" >Đặt hàng</a>
        </div>
   <?php else : ?>
      <p style="font-size: 20px; text-align: center;">Không xem được chi tiết sản phẩm này</p>
   <?php endif; ?>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>