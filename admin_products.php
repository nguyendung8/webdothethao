<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

   if(isset($_POST['add_product'])){//thêm sách mới từ submit form name='add_product'

      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $trademark = mysqli_real_escape_string($conn, $_POST['trademark']);
      $cate_id= $_POST['category'];
      $price = $_POST['price'];
      $discount = $_POST['discount'];
      $newprice= $price*(100-$discount)/100;
      $quantity = $_POST['quantity'];
      $initial_quantity = $_POST['quantity'];
      $describe = $_POST['describe'];
      $image = $_FILES['image']['name'];
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded_img/'.$image;

      $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');//truy vấn kiểm tra sách đã tồn tại chưa

      if(mysqli_num_rows($select_product_name) > 0){
         $message[] = 'Sản phẩm đã tồn tại.';
      }else{//chưa thì thêm mới
         $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, trademark, cate_id, price, discount, newprice,quantity,initial_quantity, describes, image) VALUES('$name', '$trademark', '$cate_id', '$price', '$discount', '$newprice', '$quantity', '$initial_quantity', '$describe', '$image')") or die('query failed');

         if($add_product_query){
            if($image_size > 2000000){//kiểm tra kích thước ảnh
               $message[] = 'Kích tước ảnh quá lớn, hãy cập nhật lại ảnh!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);//lưu file ảnh xuống
               $message[] = 'Thêm sản phẩm thành công!';
            }
         }else{
            $message[] = 'Thêm sản phẩm không thành công!';
         }
      }
   }

   if(isset($_GET['delete'])){//xóa sản phẩm từ onclick <a></a> href='delete'
      $delete_id = $_GET['delete'];
      $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
      $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
      unlink('uploaded_img/'.$fetch_delete_image['image']);//xóa file ảnh của sản phẩm cần xóa
      mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
      header('location:admin_products.php');
   }

   if(isset($_POST['update_product'])){//cập nhật sản phẩm từ form submit name='update_product'

      $update_p_id = $_POST['update_p_id'];
      $update_name = $_POST['update_name'];
      $update_trademark = $_POST['update_trademark'];
      $update_category = $_POST['update_category'];
      $update_price = $_POST['update_price'];
      $update_discount = $_POST['update_discount'];
      $update_newprice = $update_price*(100-$update_discount)/100;
      $update_quantity = $_POST['update_quantity'];
      $update_describe = $_POST['update_describe'];

      mysqli_query($conn, "UPDATE `products` SET name = '$update_name', trademark = '$update_trademark', cate_id='$update_category', price = '$update_price', newprice='$update_newprice', discount='$update_discount', quantity='$update_quantity', describes='$update_describe' WHERE id = '$update_p_id'") or die('query failed');

      $update_image = $_FILES['update_image']['name'];
      $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
      $update_image_size = $_FILES['update_image']['size'];
      $update_folder = 'uploaded_img/'.$update_image;
      $update_old_image = $_POST['update_old_image'];

      if(!empty($update_image)){//kiểm tra có file ảnh mới không
         if($update_image_size > 2000000){
            $message[] = 'image file size is too large';
         }else{
            mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);//lưu file ảnh mới
            unlink('uploaded_img/'.$update_old_image);//xóa file ảnh cũ
         }
      }

      header('location:admin_products.php');

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sản phẩm</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Sản  phẩm</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Thêm sản phẩm</h3>
      <input type="text" name="name" class="box" placeholder="Tên sản phẩm" required>
      <input type="text" name="trademark" class="box" placeholder="Thương hiệu" required>
      <select name="category" class="box">
         <?php
            $select_category= mysqli_query($conn, "SELECT * FROM `categorys`") or die('Query failed');
            if(mysqli_num_rows($select_category)>0){
               while($fetch_category=mysqli_fetch_assoc($select_category)){
                  echo "<option value='" . $fetch_category['id'] . "'>".$fetch_category['name']."</option>";
               }
            }
            else{
               echo "<option>Không có thể loại nào.</option>";
            }
         ?>
      </select>
      <input type="number" min="0" name="price" class="box" placeholder="Giá sản phẩm" required>
      <input type="number" min="0" name="discount" class="box" placeholder="% giảm giá" required>
      <input type="number" min="1" name="quantity" class="box" placeholder="Số lượng" required>
      <input type="text" name="describe" class="box" placeholder="Mô tả" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="Thêm" name="add_product" class="btn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
               <div style="height: -webkit-fill-available;" class="box">
                  <img style="height: 23rem !important" style="border-radius: 4px;" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="sub-name">Thương hiệu: <?php echo $fetch_products['trademark']; ?></div>
                  <?php
                  $cate_id =  $fetch_products['cate_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = $cate_id") or die('Query failed');
                      $cate_name = mysqli_fetch_assoc($result)
                   ?>
                  <div class="sub-name">Danh mục: <?php echo $cate_name['name']; ?></div>
                  <div class="sub-name">Mô tả: <?php echo $fetch_products['describes']; ?></div>
                  <div class="price"><span style="text-decoration-line: line-through"><?php echo number_format($fetch_products['price'],0,',','.'  ); ?></span> VND (Giảm giá: <?php echo $fetch_products['discount']; ?>%)</div>
                  <div class="price"><?php echo number_format($fetch_products['newprice'],0,',','.' );; ?> VND (SL: <?php echo $fetch_products['quantity']; ?>)</div>
                  <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Cập nhật</a>
                  <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
               </div>
      <?php
            }
      }else{
         echo '<p class="empty">Không có sản phẩm nào được thêm!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){//hiện form update từ onclick <a></a> href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                  <input type="hidden" name="update_trademark" value="<?php echo $fetch_update['trademark']; ?>">
                  <input type="hidden" name="update_discount" value="<?php echo $fetch_update['discount']; ?>">
                  <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Tên sản phẩm">
                  <select name="update_category" class="box">
                  <?php
                  $cate_id =  $fetch_update['cate_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = $cate_id") or die('Query failed');
                      $cate_name = mysqli_fetch_assoc($result)
                   ?>
                     <option value="<?php echo $cate_name['id']?>"><?=$cate_name['name']?></option>
                     <?php
                        $select_category= mysqli_query($conn, "SELECT * FROM `categorys`") or die('Truy vấn lỗi');
                        while($fetch_category=mysqli_fetch_assoc($select_category)){
                           if($fetch_category['name']!=$fetch_update['category']){
                              echo"<option  value='" . $fetch_category['id'] . "'>".$fetch_category['name']."</option>";
                           }
                        }
                     ?>
                  </select>
                  <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Giá sản phẩm">
                  <input type="number" name="update_quantity" value="<?php echo $fetch_update['quantity']; ?>" min="0" class="box" required placeholder="Số lượng sản phẩm">
                  <input type="text" name="update_describe" value="<?php echo $fetch_update['describes']; ?>" class="box" required placeholder="Mô tả">
                  <input type="submit" value="update" name="update_product" class="btn">
                  <input type="reset" value="cancel" id="close-update" class="option-btn">
               </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>