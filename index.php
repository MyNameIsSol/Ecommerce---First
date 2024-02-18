<?php
session_start();
require_once ('function.php');
$dbName = "crochet_andhooks";
$newPtable = "new_products";
$featuredPtable = "feautured_product";
$gallerytable = "product_gallery";
$db = new CreateDb($dbName, $newPtable);
$db = new CreateDb($dbName, $featuredPtable);
$db = new CreateDb($dbName, $gallerytable);
$cols = "*";
$fetchG = $db->reTrieve($gallerytable,$cols);
$fetchN = $db->reTrieve($newPtable,$cols);
$fetchF = $db->reTrieve($featuredPtable,$cols);

if(isset($_POST['addTocart'])){
    if(isset($_SESSION['cart'])){

     $item_array_id = array_column($_SESSION['cart'],'product_code');
      
       if(in_array($_POST['product_id'],$item_array_id)){
           echo"<script>alert('Product is already added in the cart...!')</script>";
           echo "<script>window.location='index.php'</script>";
       }else{
           $count = count($_SESSION['cart']);
           $item_array = array('product_name' => $_POST['product_name'],'product_price' => $_POST['product_price'],'product_code' => $_POST['product_id'],'product_qty' => $_POST['product_qnty']);

           $_SESSION['cart'][$count] = $item_array;    
       }
    }else{
        $item_array = array('product_name' => $_POST['product_name'],'product_price' => $_POST['product_price'],'product_code' => $_POST['product_id'],'product_qty' => $_POST['product_qnty']);
        $_SESSION['cart'][0] = $item_array;
    }
}

if(!empty($_SESSION['user'])){
    $username = $_SESSION['user']['name'];
}else if(!empty($_SESSION['admin'])){
    $adminname = $_SESSION['admin']['name'];
}

// session_unset();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Crochets | Hooks</title>
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
        <script type="text/javascript" src="js/index.js"></script>
        <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Anton|Syne|Cabin|Lato|Fjalia+one|Montserrat|Kalam|Roboto&display=swap" rel="stylesheet">
        
    </head>
    <body>
        
            <div class="loadr">
            <img src="img/icons/preloader.gif"  alt="Loading..." >
            </div>
        
        <div class="wrapper">
        <?php require_once('header.php'); ?>
        <?php require_once('alert.php'); ?>
        
        <div class="contain-er">
        <div class="myalert alert-info">
            <a href="" id="cancel">
                <span class="fa fa-times"></span>
            </a>
        </div>
        
            <div id="owl-demo" class="owl-carousel owl-theme">
            <?php 
                 while($rows = mysqli_fetch_array($fetchG)){ 
              ?>
                <div class="item"><img src="<?= $rows['product_image']; ?>" alt=""></div>
                <?php }?>
            </div>

            <div class="N-item">
                <div class="text">
                    <h5>New items</h5>
                    <div class="dropdown-divider"></div>

                </div>
                <div class="item">
                    <?php 
                    while($rows = mysqli_fetch_array($fetchN)){ 
                    ?>
                    <div class="product">
                        <form action="" method="post">
                        <img src="<?= $rows['product_image']; ?>" class="image-rasponsive" alt="image-name">
                        <h2><?= $rows['product_name']; ?></h2>
                        <p><?= $rows['product_info']; ?></p>
                        <h5 ><span style="color:green;">&#x20A6; </span><?= $rows['product_price'];?>.00</h5>
                        <button type="submit" name="addTocart"> <span>Add</span><span> to</span><span> cart</span> <span class="fa fa-cart-plus"></span></button>
                        <input type="hidden" name="product_id" value="<?= $rows['product_code']; ?>">
                        <input type="hidden" name="product_qnty" value="<?= $rows['product_qty']; ?>">
                        <input type="hidden" name="product_name" value="<?= $rows['product_name']; ?>">
                        <input type="hidden" name="product_price" value="<?= $rows['product_price']; ?>">
                        </form>
                    </div>
                    <?php }?>
                    <!-- <div class="img">
                    <form action="" method="post">
                        <img src="img/14.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#400.00</h5>
                        <button type="submit" name="cart">Add to cart <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div>
                    <div class="img">
                    <form action="" method="post">
                        <img src="img/29.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#400.00</h5>
                        <button type="submit" name="cart">Add to cart <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div>
                    <div class="img">
                    <form action="" method="post">
                        <img src="img/30.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#400.00</h5>
                        <button type="submit" name="cart">Add to cart <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div> -->
                </div>
            </div>
        
            <div class="N-item">
                <div class="text">
                    <h5>Featured items</h5>
                    <div class="dropdown-divider"></div>
                </div>
                <div class="item">
                    <?php 
                    while($rows = mysqli_fetch_array($fetchF)){ 
                        
                    ?>
                    <div class="product">
                        <form action="" method="post">
                        <img src="<?= $rows['product_image']; ?>" class="image-rasponsive" alt="image-name">
                        <h2><?= $rows['product_name']; ?></h2>
                        <p><?= $rows['product_info']; ?></p>
                        <h5 ><span style="color:green;">&#x20A6; </span><?= $rows['product_price'];?>.00</h5>
                        <button type="submit" name="addTocart"> <span>Add</span><span> to</span><span> cart</span> <span class="fa fa-cart-plus"></span></button>
                        <input type="hidden" name="product_id" value="<?= $rows['product_code']; ?>">
                        <input type="hidden" name="product_qnty" value="<?= $rows['product_qty']; ?>">
                        <input type="hidden" name="product_name" value="<?= $rows['product_name']; ?>">
                        <input type="hidden" name="product_price" value="<?= $rows['product_price']; ?>">
                        </form>
                    </div>
                    <?php  }  mysqli_close($db->condb); ?>
                    <!-- <div class="img">
                    <form action="" method="post">
                        <img src="img/21.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#1200.00</h5>
                        <button type="submit" name="cart"><span>Add</span><span> to</span><span> cart</span> <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div>
                    <div class="img">
                    <form action="" method="post">
                        <img src="img/42.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#1200.00</h5>
                        <button type="submit" name="cart"><span>Add</span><span> to</span><span> cart</span>  <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div>
                    <div class="img">
                    <form action="" method="post">
                        <img src="img/44.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#1200.00</h5>
                        <button type="submit" name="cart"><span>Add</span><span> to</span><span> cart</span> <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div>
                    <div class="img">
                    <form action="" method="post">
                        <img src="img/45.jpg" class="image-rasponsive" alt="image-name">
                        <h6>Product name</h6>
                        <p>Lorem ipsum dolor sit amet.</p>
                        <h5>#1200.00</h5>
                        <button type="submit" name="cart"><span>Add</span><span> to</span><span> cart</span> <span class="fa fa-cart-plus"></span></button>
                        </form>
                    </div> -->
                </div>
            </div>
        
            <div class="footer">
                
                <div class="footer-sec1">
                <div class="site_info" style="padding:10px 20px;">
                <h3 style="font-size:24px; font-weight:400; margin:10px 0;color:#F5F5F5;text-align:start;">Online Shopping and Training on crochetandhooks.com.ng</h3>
                <p style="font-size:12px;color:#F5F5F5;text-align:start;">Crochet_andhooks is an one online shopping and training site in Nigeria.
                     We are an online store where you can purchase all your crochet yarn, hooks, wools, needles,acrylic plate/board as well as ready-made crochet dress, crochet top, crochet beanie, crochet scarf, crochet hats and more on the go. What more? You can have them delivered directly to you.
                     We also offer online tutorial videos on knitting patterns and crochet patterns. You will learn how to create designs, make crochet wears and so on.
                     Kindly follow us on our social media platform. Also to be notified when an online video is published on our website, kindly subscribe below with your email.
                     Shop online with great ease as you pay with your debit card using paystack or flutterwave gateway which guarantees you the safest online shopping payment method, allowing you to make stress free payments.
                      Whatever it is you wish to buy related to crocheting, crochect_andhooks offers you all and lots more at prices which you can trust.
                      crochet_andhooks has payment options for everyone irrespective of taste, class, and preferences.
                     We provide you with a wide range of products associated with crocheting you can trust.
                     Take part in the deals of the day and discover the best prices on our products.</p>
            </div>
                <h6 style="color:#808080; font-size: 16px;" >Follow us</h6>
        
                <ul class="contact">
                    <li><a href="https://www.facebook.com/crochetandhooks"><img src="img/icons/facebook.png" class="image-responsive" alt="facebook-icon"></a></li>
                    <!-- <li><a href=""><img src="img/icons/twitter.png" class="image-responsive" alt="twitter-icon"></a></li> -->
                    <li><a href="https://instagram.com/crochet_andh00ks"><img src="img/icons/instagram.png" class="image-responsive" alt="instagram-icon"></a></li>
                    <li><a href="https://wa.me/2347059195916"><img src="img/icons/whatsapp.png" class="image-responsive" alt="whatsapp-icon"></a></li>
                    <!-- <li><a href=""><img src="img/icons/pinterest.png" class="image-responsive" alt="pinterest-icon"></a></li> -->
                </ul>
            
         <form class="home-form" action="" method="post">
             <input type="email" id="sub"  name="email" placeholder="Enter your email">
             <a href="" class="sub-btn"><span class="fa fa-telegram"></span> subscribe</a>
         </form>
         </div>

        <div class="footer-sec">
            
                <h6 style="color:#A2A2A2;">Payment methods</h6>
                <ul class="pays">
                    <li><a href=""><img src="img/icons/mastercard.png"></a></li>
                    <li><a href=""><img src="img/icons/Paystack-lo-removebg-preview.png"></a></li>
                    <li><a href=""><img src="img/icons/visa.png"></a></li>
                    <li><a href=""><img src="img/icons/flutterwa-removebg-preview.png"></a></li>
                    <li><a href=""><img src="img/icons/verve-removebg-preview.png"></a></li>
                </ul>
                <div class="services">
                <h4>Services</h4>
                <div class="service-container">
                <div class="service-content">
                    <span class="fa fa-shopping-bag"></span>
                        <h4>Sales</h4>
                        <p>Our company receives order from customers on a daily basis including weekends. this orders are processed daily but delivery is done only on working days.</p>
                    </div>
                    <div class="service-content">
                    <span class="fa fa-truck"></span>
                        <h4>Delivery</h4>
                        <p>We offer fast local delivery of our products to customers. Product is delivered 2-3 days from the time of payment. During this estimated period, the customer is expected to receive the purchased goods.</p>
                    </div>
                    <div class="service-content">
                        <span class="fa fa-handshake"></span>
                        <h4>Deals</h4>
                        <p>We create various crochet wears that suit our customers need. Customers can also make a request for a particule wear design by contacting us using our customer care lines. lastly we offer discounted prices on all products avaliable on our website.</p>
                    </div>
                    <div class="service-content">
                    <span class="fas fa-chalkboard-teacher"></span>
                        <h4>Tutorials</h4>
                        <p>Our online tutorial videos is for customers who has interest in learning to crochet. This videos consist of instructions demonstrating the process and step by step insruction on how crochet patterns is created and turned in a complete wear.</p>
                    </div>
                </div>
            </div>
                <ul class="footer-link">
                    <li class="stories"><a href="">STORIES</a></li>
                    <li><a href="contact.php">CONTACT</a></li>
                    <li><a href="">FAQ</a></li>
                    <li><a href="">VIDEOS</a></li>
                </ul>
                <div class="copyright">
                    <p class="mr-1"> &copy; 2020 </p>
                    <h6> Crochet_andhooks.com</h6>
                </div>
            </div>
            </div>
        
            </div>
        </div>

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