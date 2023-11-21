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
   <title>Thông tin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="flex">

      <div class="image">
         <img style="border-radius: 4px;" src="images/about_img.jpg" alt="">
      </div>

      <div class="content">
         <h3>Tại sao lại có Sport.</h3>
         <p>Từ những bạn trẻ đam mê chơi thể thao, chúng mình đã xây dựng nên website này để giúp mọi người có thể dễ dàng tìm kiếm và mua được những sản phẩm thể thao yêu thích của mình.</p>
         <p>Qua một thời gian phát triển, Sport mong muốn mang đến những sản phẩm thật ưng ý cho người dùng.</p>
         <a href="contact.php" class="btn">Liên hệ</a>
      </div>

   </div>

</section>


<section class="authors">

   <h1 class="title">Thành viên của Sport</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/anhcanhan.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-instagram"></a>
         </div>
         <h3>Trần Văn Trà</h3>
      </div>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>