<?php
session_start();
// if(!isset($_SESSION['admin']['id'])){
//     header('Location: index.php');
// }
require_once('function.php');
$dbName = "crochet_andhooks";
$newPtable = "new_products";
$featuredPtable = "feautured_product";
$gallerytable = "product_gallery";
$admintable = "admin";
$orderTable = "customer_orders";
$tbCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                product_image VARCHAR(225) NOT NULL,
                product_name VARCHAR(225) NOT NULL,
                product_info VARCHAR(225) NOT NULL,
                product_price FLOAT(22) NOT NULL,
                product_code VARCHAR(22) NOT NULL,
                product_qty VARCHAR(22) NOT NULL';

$tbgalleryCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                product_image VARCHAR(225) NOT NULL,
                image_id VARCHAR(22) NOT NULL';

$tbadminCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(22) NOT NULL,
                email VARCHAR(225) NOT NULL,
                phone VARCHAR(22) NOT NULL,
                usertype VARCHAR(22) NOT NULL,
                password VARCHAR(225) NOT NULL,
                adminid VARCHAR(22) NOT NULL';
$db = new CreateDb($dbName, $newPtable, $tbCols);
$db = new CreateDb($dbName, $featuredPtable, $tbCols);
$db = new CreateDb($dbName, $admintable, $tbadminCols);
$db = new CreateDb($dbName, $gallerytable, $tbgalleryCols);
$db = new CreateDb($dbName, $orderTable);
$validate = new validator();



//check if we are receiving a post method and if we av click submit button with 
//post name of addNewproduct

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addNewproduct'])) {
    if (isset($_POST['Pname']) && isset($_POST['Pprice']) && !empty($_FILES['Pimage']['name'])) {

        $pName = $validate->validateAllname($_POST['Pname']);
        $pPrice = $validate->validateNumber($_POST['Pprice']);
        $pInfo = $validate->validateText($_POST['Pinfo']);
        $pQty = $validate->validateNumber($_POST['Pqty']);
        if($pName != false){
            if($pInfo != false){
                if($pPrice != false){
                    if($pQty != false){


        $target_dir = "img/Newproduct/";  //directory were uploaded file will be stored in our server 
        //or folder(image folder)
        $target_file = $target_dir . basename($_FILES['Pimage']['name']); //the post file 
        //concatenated with directory
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //get the file extention
        $fileSize = $_FILES['Pimage']['size']; //get the file size
        
        $returned_val = $validate->validateImageUpload($target_file, $fileType, $fileSize); //the function
        //which receive the fNAME,fTYPE and fSIZE for valiation then 
        //return the file that was concatenated with d 
        //directory($target_file) asigning it to returned_file.

        if ($target_file == $returned_val) {  //check if returned_val equal target_file
           if(move_uploaded_file($_FILES['Pimage']['tmp_name'],$target_file)){ //moves
            //the post file using its temporary name, storing it in image dir(target_dir)
            $message = "uploaded!";
            $encrpt = md5($pName . time());
            $pCode = substr($encrpt, 0, 3) . substr($encrpt, -3, 3);

            $cols = "product_image,product_name,product_info,product_price,product_code,product_qty";
            $vals = "'$target_file','$pName','$pInfo','$pPrice','$pCode','$pQty'";

            $insert = $db->inSert($newPtable, $cols, $vals);
            if ($insert) {
                mysqli_close($db->condb);
                $_SESSION['success'] = "Product Added!";
                header('location:dashboard.php');
            } else {
                $error = "Something went wrong!,Could not add this product";
            }
        }else{
            $error = "Unable to upload file...";
        }
        } else {
            $error = $returned_val; //returned file will be error message here, check d function
        }
    }else{ $error = 'product quantity should take an integer value'; }
    }else{ $error = 'price section should take an integer values only'; }
}else{ $error = 'Please check for product information error';}
}else{ $error = 'Enter a valid product name!';}
    } else {
        $error = "All field must be filled";
    }
} 

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addFeaturedproduct'])) {
    if (isset($_POST['Pname']) && isset($_POST['Pprice']) && isset($_FILES['Pimage']['name'])) {

        $pName = $validate->validateAllname($_POST['Pname']);
        $pPrice = $validate->validateNumber($_POST['Pprice']);
        $pInfo = $validate->validateText($_POST['Pinfo']);
        $pQty = $validate->validateNumber($_POST['Pqty']);
        if($pName != false){
            if($pInfo != false){
                if($pPrice != false){
                    if($pQty != false){

        $target_dir = "img/Feauturedproduct/";  //directory were uploaded file will be stored in our server 
        //or folder(image folder)
        $target_file = $target_dir . basename($_FILES['Pimage']['name']); //the post file 
        //concatenated with directory
       
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //get the file extention
        $fileSize = $_FILES['Pimage']['size']; //get the file size
        
        $returned_val = $validate->validateImageUpload($target_file, $fileType, $fileSize); //the function
        //which receive the fNAME,fTYPE and fSIZE for valiation then 
        //return the file that was concatenated with d 
        //directory($target_file) asigning it to returned_file.
        
        if ($target_file == $returned_val) {  //check if returned_val equal target_file
            
            move_uploaded_file($_FILES['Pimage']['tmp_name'],$target_file); //moves
            //the post file using its temporary name, storing it in image dir(target_dir)
            $encrpt = md5($pName . time());
            $pCode = substr($encrpt, 0, 3) . substr($encrpt, -3, 3);
           
            $cols = "product_image,product_name,product_info,product_price,product_code,product_qty";
            $vals = "'$target_file','$pName','$pInfo','$pPrice','$pCode','$pQty'";

            $insert = $db->inSert($featuredPtable, $cols, $vals);
            if ($insert) {
                mysqli_close($db->condb);
                $_SESSION['success'] = "Product Added!";
                header('location:dashboard.php');
            } else {
                $error = "Something went wrong!,Could not add this product";
            }
        } else {
            $error = $returned_val; //returned file will be error message here, check d function
        }
    }else{ $error = 'product quantity should take an integer value'; }
}else{ $error = 'price section should take an integer values only'; }
}else{ $error = 'Please check for product information error';}
}else{ $error = 'Enter a valid product name!';}
    }else {
        $error = "All field must be filled";
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addGallery'])) {

        $target_dir = "img/Gallery/";  //directory were uploaded file will be stored in our server 
        //or folder(image folder)
        $target_file = $target_dir . basename($_FILES['Pimage']['name']); //the post file 
        //concatenated with directory
       
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); //get the file extention
        $fileSize = $_FILES['Pimage']['size']; //get the file size
        
        $returned_val = $validate->validateImageUpload($target_file, $fileType, $fileSize); //the function
        //which receive the fNAME,fTYPE and fSIZE for valiation then 
        //return the file that was concatenated with d 
        //directory($target_file) asigning it to returned_file.
        
        if ($target_file == $returned_val) {  //check if returned_val equal target_file
            
            move_uploaded_file($_FILES['Pimage']['tmp_name'],$target_file); //moves
            //the post file using its temporary name, storing it in image dir(target_dir)
            $encrpt = md5('success' . time());
            $pCode = substr($encrpt, 0, 3) . substr($encrpt, -3, 3);
           
            $cols = "product_image,image_id";
            $vals = "'$target_file','$pCode'";

            $insert = $db->inSert($gallerytable, $cols, $vals);
            if ($insert) {
                mysqli_close($db->condb);
                $_SESSION['success'] = "New image added to Gallery!";
                header('location:dashboard.php');
            } else {
                $error = "Something went wrong, Could not add this image to gallery";
            }
        } else {
            $error = $returned_val; //returned file will be error message here, check d function
        } 
}


// $target_dir1 = "img/Newproduct/"; 
// if(!empty($_FILES['Pimage']['name'])) { $target_file1 = $target_dir1 . basename($_FILES['Pimage']['name']); }else{ }

// $target_dir2 = "img/Feauturedproduct/";
// if(!empty($_FILES['Pimage']['name'])) { $target_file2 = $target_dir2 . basename($_FILES['Pimage']['name']); }else{ } 

// if(!empty($target_file1)) { $fileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION)); }
// if(!empty($target_file2)) { $fileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION)); }

// $fileSize = $_FILES['Pimage']['size']; 
// $col = "*";
// $where = "product_code = '$pCode'";
// $limit = "1";
// if(!empty($target_file1) || !empty($target_file2)){           
// $fetch = $db->checkIfExist2table($newPtable,$featuredPtable,$col,$where,$limit);
// if(mysqli_num_rows($fetch)>0){ 
// while($rows = mysqli_fetch_array($fetch)) {
//     $Pimage = $rows['product_image'];
// }
// if(file_exists($Pimage)){
//    if($target_dir1 == dirname($Pimage).'/'){
//     $returned_val = $validate->validateImageUpload($target_file1, $fileType1, $fileSize);
//     if ($target_file1 == $returned_val) {
//         unlink($Pimage);
//         move_uploaded_file($_FILES['Pimage']['tmp_name'],$target_file1);
//         $values = "product_image = '$target_file1',
//            product_name ='$pName',
//            product_info ='$pInfo',
//            product_price ='$pPrice'";
//         $update = $db->upGate($newPtable, $values, $where);
//         if ($update) {
//             $_SESSION['success'] = "Product Updated!";
//             mysqli_close($db->condb);
            // header('location:add-product.php');
//         } else {
//             $error = "Something went wrong!,Could not update this product";
//         }
//     }
//    }else{
//        if($target_dir2 == dirname($Pimage).'/'){
//         $returned_val = $validate->validateImageUpload($target_file2, $fileType2, $fileSize);
//         if ($target_file2 == $returned_val) {
//             unlink($Pimage);
//             move_uploaded_file($_FILES['Pimage']['tmp_name'],$target_file2);
//             $values = "product_image = $target_file2,
//            product_name =$pName,
//            product_info =$pInfo,
//            product_price =$pPrice";
//             $update = $db->upGate($featuredPtable, $values, $where);
//             if ($update) {
//                 $_SESSION['success'] = "Product Updated!";
//                 mysqli_close($db->condb);
                // header('location:add-product.php');
//             } else {
//                 $error = "Something went wrong!,Could not update this product";
//             }
//         }
//        }else{
//            $error = "no image found from database";
//        }
//    }
// }else{
//     $error = "image not found in folder";
// }
// }else{
// $error = "could not find previous image to update";
// }
// }else{
// $values = "
// product_name ='$pName',
// product_info ='$pInfo',
// product_price ='$pPrice'";
// $update = $db->upDate($newPtable,$featuredPtable,$col,$values,$where,$limit);
// if ($update) {
//     $_SESSION['success'] = "Product Updated!  yyy";
    // mysqli_close($db->condb);
    // header('location:add-product.php');
// } else {
//     $error = "Something went wrong!,Could not update this product yyy";
// }
// }

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updateproduct'])) {
    if (isset($_POST['Pcode']) && !empty($_POST['Pcode'])) {
        $pCode = $validate->validateEncrypVal($_POST['Pcode']);
        if($pCode != false){
            $col = "*";
            $where = "product_code = '$pCode'";
            $limit = "1";
            $fetch = $db->checkIfExist2table($newPtable,$featuredPtable,$col,$where,$limit);
            if (mysqli_num_rows($fetch) > 0) {
                while ($rows = mysqli_fetch_array($fetch)) {
                    $Pimage = $rows['product_image'];
                    $Pname = $rows['product_name'];
                    $Pinfo = $rows['product_info'];
                    $Pprice = $rows['product_price'];
                }
                if(empty($_POST['Pname'])){$pName = $Pname;}else{$pName=$validate->validateAllname($_POST['Pname']);}
                if(empty($_POST['Pprice'])){$pPrice = $Pprice;}else{$pPrice=$validate->validateNumber($_POST['Pprice']);}
                if(empty($_POST['Pinfo'])){$pInfo = $Pinfo;}else{$pInfo=$validate->validateText($_POST['Pinfo']);}
                if($pName != false){
                    if($pInfo != false){
                        if($pPrice != false){

                            $target_dir1 = "img/Newproduct/";
                            if (!empty($_FILES['Pimage']['name'])) {
                                $target_file1 = $target_dir1 . basename($_FILES['Pimage']['name']);
                            } else { }

                            $target_dir2 = "img/Feauturedproduct/";
                            if (!empty($_FILES['Pimage']['name'])) {
                                $target_file2 = $target_dir2 . basename($_FILES['Pimage']['name']);
                            } else { }

                            if (!empty($target_file1)) {
                                $fileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));
                            }
                            if (!empty($target_file2)) {
                                $fileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
                            }
                            $fileSize = $_FILES['Pimage']['size'];

                            if (!empty($target_file1) || !empty($target_file2)) {
                                if (file_exists($Pimage)) {
                                    if ($target_dir1 == dirname($Pimage) . '/') {
                                        $returned_val = $validate->validateImageUpload($target_file1, $fileType1, $fileSize);
                                        if ($target_file1 == $returned_val) {
                                            unlink($Pimage);
                                            move_uploaded_file($_FILES['Pimage']['tmp_name'], $target_file1);
                                            $values = "product_image = '$target_file1',
                                            product_name ='$pName',
                                            product_info ='$pInfo',
                                            product_price ='$pPrice'";
                                            $update = $db->upGate($newPtable, $values, $where);
                                            if ($update) {
                                                mysqli_close($db->condb);
                                                $_SESSION['success'] = "Product Updated!";
                                                header('location:dashboard.php');
                                            } else {
                                                $error = "Something went wrong!,Could not update this product";
                                            }
                                        }
                                    } else {
                                        if ($target_dir2 == dirname($Pimage) . '/') {
                                            $returned_val = $validate->validateImageUpload($target_file2, $fileType2, $fileSize);
                                            if ($target_file2 == $returned_val) {
                                                unlink($Pimage);
                                                move_uploaded_file($_FILES['Pimage']['tmp_name'], $target_file2);
                                                $values = "product_image = '$target_file2',
                                                product_name = '$pName',
                                                product_info = '$pInfo',
                                                product_price = '$pPrice'";
                                                $update = $db->upGate($featuredPtable, $values, $where);
                                                if ($update) {
                                                    mysqli_close($db->condb);
                                                    $_SESSION['success'] = "Product Updated!";
                                                    header('location:dashboard.php');
                                                } else {
                                                    $error = "Something went wrong!,Could not update this product";
                                                }
                                            }
                                        } else {
                                            $error = "no image found from database";
                                        }
                                    }
                                } else {
                                    $error = "Product image not found. delete product and add it again";
                                }
                            } else {
                                $values = "
                                product_name ='$pName',
                                product_info ='$pInfo',
                                product_price ='$pPrice'";
                                $update = $db->upDate($newPtable, $featuredPtable, $col, $values, $where,$limit);
                                if ($update) {
                                    mysqli_close($db->condb);
                                    $_SESSION['success'] = "Product Updated!";
                                    header('location:dashboard.php');
                                } else {
                                    $error = "Something went wrong!,Could not update this product";
                                }
                            }

                        }else{ $error = 'price section should take an integer vaules only'; }
                    }else{ $error = 'Please check for product information error';}
                }else{ $error = 'Enter a valid product name!';}
            }else{
                $error = 'Sorry! can\'t find product to update';
            }
        }else{ $error = 'Enter a valid product code';}
    } else {
        $error = "Product code is required!";
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addadmin'])){
    $username = $validate->validateAllname($_POST['username']);
    $email = $validate->validateEmail($_POST['email']);
    $phone = $validate->validateNumber($_POST['phone']);
    $usertype = $validate->validateName($_POST['usertype']);
    $password = $validate->validatePassword($_POST['password']);
    $c_password = $validate->validatePassword($_POST['c_password']);
    $encryptpassword = sha1(md5($password));
    $encryptc_password = sha1(md5($c_password));

    if ($username != false) {
            if ($email != false) {
                if ($phone != false) {
                    if ($usertype != false) {
                        if ($password != false) {
                            if ($c_password != false) {
                                if ($encryptpassword == $encryptc_password) {
                                        $tbCols = '*';
                                        $where = "email = '$email'";
                                        $limit = '1';
                                        $findUser = $db->checkIfExist($admintable, $tbCols, $where, $limit);
                                        if (mysqli_num_rows($findUser)>0) {
                                            $error = 'Sorry! admin already exist';
                                        } else {
                                            $encrpt = md5($email.time());
                                            $adminid = substr($encrpt,0,3).substr($encrpt,-3,3);
                                            $tbcols = "username,email,phone,usertype,password,adminid";
                                            $values = "'$username','$email','$phone','$usertype','$encryptpassword','$adminid'";
                                            $insert = $db->inSert($admintable, $tbcols, $values);
                                            if ($insert) {
                                                $_SESSION['admin'] = array('name' => $username,'id' => $adminid);
                                                $_SESSION['success'] = 'Admin added successfully';
                                                header('Location: dashboard.php');
                                            } else {
                                                $error = 'Sorry! Your registraion failed';
                                            }
                                        }
                                } else {
                                    $error = 'Password does not match';
                                }
                            } else {
                                $error = 'please confirm password';
                            }
                            } else {
                                $error = 'invalid password format';
                            }
                        } else {
                            $error = 'User type is invalid';
                        }
                } else {
                    $error = 'Please provide a valid phone number';
                }
            } else {
                $error = 'Please provide a valid email address';
            }
    } else {
        $error = 'Please provide a valid username';
    }
}

if(isset($_SESSION['admin'])){
    $adminname = $_SESSION['admin']['name'];
}

if(isset($_GET['action']) && $_GET['action'] == 'delete' ){
    $product =  $validate->validateString($_GET['product']);
    $where = "product_code = '$product'";
    $limit = "1";
    $cols = "*";
    $fetch = $db->checkIfExist2table($newPtable,$featuredPtable,$cols,$where,$limit);
    if (mysqli_num_rows($fetch) > 0) {
        while ($rows = mysqli_fetch_array($fetch)) {
            $Pimage = $rows['product_image'];
        }
        if (file_exists($Pimage)) {
            unlink($Pimage);
    $delete = $db->deLete($newPtable,$featuredPtable,$cols,$where,$limit);
    if($delete){
        $_SESSION['success'] = "Item deleted!";
        header('location: dashboard.php');
    }else{
        $error = "Error performing action";
    }
}else{ $error = "Product does not exist"; }
    }
}

if(isset($_GET['action']) && $_GET['action'] == 'delete_gal' ){
    $product =  $validate->validateString($_GET['product']);
    $where = "image_id = '$product'";
        $limit = "1";
        $cols = "*";
        $fetch = $db->checkIfExist($gallerytable, $cols, $where, $limit);
        if (mysqli_num_rows($fetch) > 0) {
            while ($rows = mysqli_fetch_array($fetch)) {
                $Pimage = $rows['product_image'];
            }
            if (file_exists($Pimage)) {
                unlink($Pimage);
    $delete = $db->deL($gallerytable,$where);
    if($delete){
        $_SESSION['success'] = "Image deleted!";
        header('location:dashboard.php');
    }else{
        $error = "Error performing action";
    }
}else{ $error = "This image does not exist"; }
}
}

if(isset($_GET['action']) && $_GET['action'] == 'del' ){
    $product =  $validate->validateString($_GET['product']);
    $where = "product_code = '$product'";
    $delete = $db->deL($orderTable,$where);
    if($delete){
        $_SESSION['success'] = "Item deleted!";
        header('location:dashboard.php');
    }else{
        $error = "Error performing action";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/owl.carousel.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/font-awesome.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Anton|Cabin|Lato|Fjalia+one|Montserrat|Kalam|Roboto&display=swap" rel="stylesheet">

</head>

<body>


    <div style="width: 100%; height: 50px;" class="navi2"></div>

    <div class="wrapper">
        <?php require_once('header.php'); ?>
        <?php require_once('alert.php'); ?>

        
        <div class="add-product-maindiv">
        <div class="addpd-top1">
                <div class="addpd-top2">
                <img src="img/47.jpg" alt="about" class="image-responsive">
                </div>
                <h1>Welcome<span><?php if(!empty($adminname)){echo $adminname;}else{echo "Admin";} ?></span></h1>
            </div>
        <div class="sidebar-icon">
                    <span class="fa fa-ellipsis-v"></span>
                </div>
                
            <div class="side-bar">
                <div class="admin-profile">
                    <!-- <h2>Welcome</h2> -->
                    <h3>
                        <!-- <span class="fa fa-user-circle-o pr-2"></span> -->
                        <?php if(!empty($adminname)){echo $adminname;}else{echo "Admin";} ?>
                    </h3>
                </div>
                <div class="sidebar-header">
                    <h4><span class="fa fa-home mr-2"></span>Dashboard</h4>
                </div>
                <div class="sidebar-links">
                    <ul>
                        <li data-href="#newItem"><span class="fa fa-plus pr-2"></span>Add new products</li>
                        <li data-href="#featItem"><span class="fa fa-plus pr-2"></span>Add feautured product</li>
                        <li data-href="#galleryImages"><span class="fa fa-plus pr-2"></span>View gallery Images</li>
                        <li data-href="#itemList"><span class="fa fa-eye pr-2"></span>View all product</li>
                        <li data-href="#updateProduct"><span class="fa fa-edit pr-2"></span>Update a product</li>
                        <li data-href="#addadmin"><span class="fa fa-user-plus pr-2"></span>Add an admin</li>
                        <li data-href="#productGallery"><span class="fa fa-user-plus pr-2"></span>Add image to Gallery</li>
                    </ul>
                </div>
            </div>
            <div class="contain-er">
            <div class="add-items">
            <div class="myalert alert-info" >
            <p>
                <?php if (!empty($error)) {
                    echo $error;
                    echo "<script>$('.myalert').addClass('alert-warning').fadeIn(1000)</script>";
                } elseif (!empty( $_SESSION['success'])) {
                    echo  $_SESSION['success'];
                    echo "<script>$('.myalert').addClass('alert-info').fadeIn(1000)</script>";

                }
               
                ?>
            </p>
                    <a href="#" id="cancel">
                        <span class="fa fa-times"></span>
                    </a>
                </div>
                <div class="database-info">
                    <div class="info1">
                        <div class="info1-inDiv"></div>
                        <h1>3564</h1>
                        <span>Registered Users</span>
                    </div>
                    <div class="info2">
                    <div class="info2-inDiv"></div>
                    <h1>2061</h1>
                        <span>Subscribed Users</span>
                    </div>
                    <div class="info3">
                    <div class="info3-inDiv"></div>
                    <h1>1312</h1>
                        <span>Total Product</span>
                    </div>
                    <div class="info4">
                    <div class="info4-inDiv"></div>
                    <h1>45</h1>
                        <span>Customers Order</span>
                    </div>
                </div>

                <div class="dashboard-wrap">
                <div class="dash-left">
                <div class="prod-item" id="newItem">
                    <h2>Add New Products</h2>
                    <form action="" method="post" id="product-form" enctype="multipart/form-data">
                        <label class="float-left mb-0" for="Pimage">Choose product image:</label>
                        <input type="file" name="Pimage" id="Pimage" placeholder="Product Image">
                        <input type="text" name="Pname" value="<?= !empty($_POST['Pname']) ? $_POST['Pname'] : '';?>" placeholder="Product Name">
                        <input type="text" name="Pinfo" value="<?= !empty($_POST['Pinfo']) ? $_POST['Pinfo']: '';?>" placeholder="Product info">
                        <input type="text" name="Pprice" value="<?= !empty($_POST['Pprice']) ? $_POST['Pprice']: '';?>" placeholder="Product Price">
                        <input type="hidden" name="Pqty" value="1" placeholder="Product quantity">
                        <input id="item-btn" type="submit" name="addNewproduct" value="CREATE PRODUCT">
                    </form>
                </div>


                <div class="prod-item" id="featItem">
                    <h2>Add feautured products</h2>
                    <form action="" method="post" id="product-form" enctype="multipart/form-data">
                        <label class="float-left mb-0" for="Pimage">Choose product image:</label>
                        <input type="file" name="Pimage" id="Pimage" placeholder="Product Image">
                        <input type="text" name="Pname" value="<?= !empty($_POST['Pname']) ? $_POST['Pname']: '';?>" placeholder="Product Name">
                        <input type="text" name="Pinfo" value="<?= !empty($_POST['Pinfo']) ? $_POST['Pinfo']: '';?>" placeholder="Product info">
                        <input type="text" name="Pprice" value="<?= !empty($_POST['Pprice']) ? $_POST['Pprice']: '';?>" placeholder="Product Price">
                        <input type="hidden" name="Pqty" value="1" placeholder="Product quantity">
                        <input id="item-btn" type="submit" name="addFeaturedproduct" value="CREATE PRODUCT">
                    </form>
                </div>

                <div class="prod-item" id="productGallery">
                    <h2>Add Image to the gellery</h2>
                    <form action="" method="post" id="product-form" enctype="multipart/form-data">
                        <label class="float-left mb-0" for="Pimage">Choose product image:</label>
                        <input type="file" name="Pimage" id="Pimage" placeholder="Product Image">
                        <input id="item-btn" type="submit" name="addGallery" value="CREATE GALLERY">
                    </form>
                </div>

                <div class="prod-item" id="itemList">
                        <h2>All product</h2>
                        <table>
                            <tr class="table-head">
                                <th>Id</th>
                                <th>Item</th>
                                <th>information</th>
                                <th>price</th>
                                <th>product code</th>
                                <th>delete</th>
                            </tr>
                            <?php 
                            $cols = '*';
                            $fetch = $db->reTrieveTwo($newPtable, $featuredPtable, $cols);
                            if($fetch){
                            while ($rows = mysqli_fetch_array($fetch)) {
                            ?>
                            <tr class="table-body">
                                            <td><?= $rows['id']; ?></td>
                                            <td class="items">
                                                <img src="<?= $rows['product_image']; ?>" class="image-responsive" alt="product-image">
                                                <h5 class="ml-3"><?= $rows['product_name']; ?></h5>
                                            </td>
                                            <td>
                                                <h5><?= $rows['product_info']; ?></h5>
                                            </td>
                                            <td>
                                                <h5><?= $rows['product_price']; ?></h5>
                                            </td>
                                            <td>
                                                <h5><?= $rows['product_code']; ?></h5>
                                            </td>
                            
                                            <td class="remove"><a href="dashboard.php?action=delete&product=<?php echo $rows['product_code']; ?>" onclick="return confirm('Are you sure you want to remove this item?');"><span class="fa fa-times"></span></a></td>
                                        </tr>
                            <?php 
                            }
                        }else{
                            echo "<h3 style='font-style:roboto;'>Sorry! no item has been stored.</h3>";
                        }
                            ?>
                        </table>
                </div>

                <div class="prod-item" id="galleryImages">
                        <h2>Gallery Images</h2>
                        <table>
                            <tr class="table-head">
                            <th>id</th>
                                <th>Image</th>
                                <th>Image code</th>
                                <th>delete Image</th>
                            </tr>
                            <?php 
                            $cols = '*';
                            $fetch = $db->reTrieve($gallerytable,$cols);
                            $i = 1;
                            if($fetch){
                            while ($rows = mysqli_fetch_array($fetch)) {
                            ?>
                            <tr class="table-body">
                                            <td>
                                                <?= $i ?>
                                            </td>
                                            <td class="items">
                                                <img src="<?= $rows['product_image']; ?>" class="image-responsive" alt="image_gallery">
                                            </td>
                                            <td>
                                                <?= $rows['image_id']; ?>
                                            </td>
                            
                                            <td class="remove"><a href="dashboard.php?action=delete_gal&product=<?php echo $rows['image_id']; ?>" onclick="return confirm('Are you sure you want to remove this item?');"><span class="fa fa-times"></span></a></td>
                                        </tr>
                            <?php 
                             $i = $i + 1;
                            }
                        }else{
                            echo "<h3 style='font-style:roboto;'>Sorry! no image has been stored.</h3>";
                        }
                            ?>
                        </table>
                </div>

                <div class="prod-item" id="updateProduct">
                    <h2>Update a product</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="product-form" enctype="multipart/form-data">
                        <label class="float-left mb-0" for="Pimage">Choose product image:</label>
                        <input type="file" name="Pimage" id="Pimage" placeholder="Product Image">
                        <input type="text" name="Pname" value="<?= !empty($_POST['Pname']) ? $_POST['Pname']: '';?>" placeholder="Product Name">
                        <input type="text" name="Pinfo" value="<?= !empty($_POST['Pinfo']) ? $_POST['Pinfo']: '';?>"  placeholder="Product info">
                        <input type="text" name="Pprice" value="<?= !empty($_POST['Pprice']) ? $_POST['Pprice']: '';?>" placeholder="Product Price">
                        <input type="text" name="Pcode" value="<?= !empty($_POST['Pcode']) ? $_POST['Pcode']: '';?>" placeholder="Product Code">
                        <span style="font-size:14px; color:blue; display:block; text-align:start; margin-left:15px;">Tips: Product code is required while other fields are optional.</span>
                        <input id="item-btn" type="submit" name="updateproduct" value="Update product">
                    </form>
                </div>

                <div class="prod-item" id="addadmin">
                    <h2>Add admin</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="product-form">
                    <input type="text" name="username" placeholder="Username">
                    <input type="email" name="email" placeholder="Email">
                    <input type="tel" name="phone" placeholder="Phone">
                    <input type="password" name="password" placeholder="Password">
                    <span style="font-size:12px; color:blue; display:block; text-align:start; margin-left:15px;">tip: password requires 8 characters minimum, numbers, lowercase letters, uppercase letters.</span>
                    <input type="password" name="c_password" placeholder="Confirm password">
                    <input type="submit" id="item-btn" name="addadmin" value="Add new admin">
                    <input type="hidden" name="usertype" value="admin">

                </form>
                </div>       
            </div>

                <div class="dash-right">
                    <div class="dash-right-content">
                    <div class="dash-right-top">
                        <h6>Users by device</h6>
                        <span class="fas fa-sync"></span>
                    </div>
                    <div class="dash-right-mid">
                        <div class="dash-mid-image">
                            <img src="img/icons/user-stat.png" alt="" >
                        </div>
                        <div class="dash-mid-device">
                            <div class="mid-desktop">
                                <span class="fa fa-desktop"></span>
                                <p>Desktop</p>
                                <h5>65%</h5>
                            </div>
                            <div class="mid-tablet">
                            <span class="fa fa-tablet"></span>
                                <p>Tablet</p>
                                <h5>50%</h5>
                            </div>
                            <div class="mid-mobile">
                            <span class="fa fa-mobile"></span>
                                <p>Mobile</p>
                                <h5>85%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="dash-right-foot">
                        <h6>Last 7 days</h6>
                        <h6>Updated</h6>
                    </div>
                </div>
                </div>
           </div>
           <div class="dash-mid">

                    <div class="info5">
                    <div class="facebook-follow">
                        <div class="facebook-progress">
                            <img src="img/icons/purple-progress.png" alt="progress icon">
                             <div class="extimated-fig">
                                <h6>Facebook Followers</h6>
                                <span>22.14k + Since Last Week</span>
                            </div>
                        </div>
                        <img src="img/icons/facebook-logo.png" alt="facebook-logo">
                    </div>
                    </div>

                    <div class="info6">
                    <div class="insta-follow">
                        <div class="insta-progress">
                        <img src="img/icons/red-progress.png" alt="progress icon">
                        <div class="extimated-fig">
                             <h6>Instagram Followers</h6>
                              <span>44.12k + Since Last Week</span>
                        </div>
                        </div>
                        <img src="img/icons/instagram-logo.png" alt="insta-logo">
                    </div>
                    </div>

                    <div class="info7">
                    <div class="whatsapp-follow">
                        <div class="whatsapp-progress">
                        <img src="img/icons/green-progress.png" alt="progress icon">
                        <div class="extimated-fig">
                             <h6>Whatsapp Contacts</h6>
                             <span>10.41k + Since Last Week</span>    
                        </div>
                        </div>
                        <img src="img/icons/whatsapp-logo.png" alt="whatsapp-logo">
                    </div>
                    </div>
           </div>
           <div class="dash-foot">
                    <div class="info8">
                        <img src="img/icons/line-chart.png" alt="statistics">
                        
                        <div class="sales-stat">
                        <h6>Daily Sales</h6>
                        <p><strong>55%</strong> increase in todays sales</p>
                        <div class="time-update">
                            <span class="fa fa-clock-o"> updated 5minutes ago</span>
                            </div>
                        </div>
                    </div>

                    <div class="info9">
                    <img src="img/icons/bar-chart.png" alt="statistics">
                        <div class="email-sub-stat">
                        <h6>Email Subscriptions</h6>
                        <p><strong>35%</strong> increase in Email Subscription</p>
                        <div class="time-update">
                            <span class="fa fa-clock-o"> updated 5minutes ago</span>
                            </div>
                        </div>
                    </div>

                    <div class="info10">
                    <img src="img/icons/point-chart.png" alt="statistics">
                        <div class="task-stat">
                        <h6>Completed Task</h6>
                        <p>Last Campaign Performance</p>
                        <div class="time-update">
                            <span class="fa fa-clock-o"> campaign sent 2 days ago</span>
                            </div>
                        </div>     
                    </div>
           </div>
           <div id="viewOrders">
                        <h2>Customers Orders</h2>
                        <table>
                            <tr>
                            <th>id</th>
                                <th>Pay_method</th>
                                <th>Name</th>
                                
                                <th>phone</th>
                                <th>product_name</th>
                                <th>product_price</th>
                                <th>product_qnty</th>
                                <th>total_price</th>
                                <th>country</th>
                                <th>state</th>
                                <th>city</th>
                                <th>street</th>
                                <th>order_date</th>
                                <th>delete_order</th>
                                <th>total_amount</th>
                            </tr>
                            <?php 
                            $cols = '*';
    
                            $where = "order_id IN ( SELECT order_id FROM $orderTable GROUP BY order_id HAVING COUNT('*') > 1)";
                            $limit = 1000;
                        
                            $fetchOrder = $db->checkIfExist($orderTable,$cols,$where,$limit);
                           $num = 1;
                            while ($Orows = mysqli_fetch_array($fetchOrder)) {
                               
                            ?>
                            <tr>
                            <td><?= $num ?></td>
                            <td><?= $Orows['pay_method']; ?></td>
                            <td><?= $Orows['firstname']; ?></td>
                            
                                            <td><?= $Orows['phone']; ?></td>
                                            <td class="items">
                                            
                                                <!-- <img src="<?= $rows['product_image']; ?>" class="image-responsive" alt="product-image"> -->
                                            <?= $Orows['product_name']; ?>    
                                            </td>
                                            <td>
                                                <?= $Orows['product_price']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['product_qnty']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['total_price']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['country']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['state']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['city']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['street']; ?>
                                            </td>
                                            <td>
                                                <?= $Orows['order_date']; ?>
                                            </td>
                            
                                            <td class="remove"><a href="dashboard.php?action=del&product=<?php echo $Orows['product_code']; ?>" onclick="return confirm('Are you sure you want to remove this item?');"><span class="fa fa-times"></span></a></td>
                                            </tr> 
                            <?php 
                            $num = $num + 1;
                            }
                            ?>
                        </table>
                </div>
     
            </div> 
             
        </div>  
        </div>
    </div>
    <?php
    unset($_SESSION['success']);
    unset($error);
    ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anton|Cabin|Lato|Fjalla+One|Montserrat|Roboto&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
</body>

</html>