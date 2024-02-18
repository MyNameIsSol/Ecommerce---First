<?php
session_start();
require_once('function.php');
$validate = new validator();
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout']) && !empty($_SESSION['cart'])){
    $_SESSION['amount'] = $_POST['total'];
}elseif(isset($_GET['error']) && $_GET['error'] == $validate->validateText('checkout/error') && isset($_SESSION['error'])){
    $error = $_SESSION['error'];
}else{
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>checkout | proceed with payment</title>
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
        <img src="img/icons/preloader.gif" alt="Loading...">
    </div>

    <div class="wrapper">
    <?php require_once('header.php'); ?>

    <div class="contain-er">
    <div class="myalert">
            <p>
                <?php if (!empty($error)) {
                    echo $error;
                    echo "<script>$('.myalert').addClass('alert-warning').fadeIn(1000)</script>";
                } elseif (!empty($success)) {
                    echo $success;
                    echo "<script>$('.myalert').addClass('alert-info').fadeIn(1000)</script>";
                }
                unset($_SESSION['error']);
                unset($error);
                ?>
            </p>
            <a href="" id="cancel">
                <span class="fa fa-times"></span>
            </a>
        </div>


        <div class="check-div">
            <div class="check-top1">
                <div class="check-top2">
                <img src="img/47.jpg" class="image-responsive">
                </div>
                <h5>Crochet<span>_andhooks</span></h5>
            </div>
            <div class="check-wrap">
                <div class="check-left-div">
                    <form action="<?php echo htmlspecialchars('pay.php'); ?>" method="POST">
                    <div class="info-text">
                    <h5>Address information</h5>
                    <p>Please enter the following information to checkout</p>
                    <div class="dividing"></div>
                    </div>
                    <div class="personal-info">
                        <h5>Personal information</h5>
                        <div class="c-names">
                           <div class="seperate">
                           <input type="text" name="firstname" value="<?= !empty($_SESSION['user']['name']) ? $_SESSION['user']['name']: '';?>" placeholder="First Name" >
                           <input type="text" name="lastname"  placeholder="Last Name" >
                            </div>
                              <div class="seperate">
                              <input type="email" name="email" value="<?= !empty($_SESSION['user']['email']) ? $_SESSION['user']['email']: '';?>" placeholder="Email address" >
                              <input type="text" name="phone" value="<?= !empty($_SESSION['user']['phone']) ? $_SESSION['user']['phone']: '';?>" placeholder="Phone number" >
                             </div>
                        </div>
                        
                    </div>
                    <div class="address-info">
                        <h5>Billing address</h5>
                        <div class="c-address">
                        <div class="seperate">
                           <input type="text" name="country" value="Nigeria" placeholder="Country" >
                           <input type="text" name="state" placeholder="State" >
                            </div>
                              <div class="seperate">
                              <input type="text" name="city" placeholder="City" >
                              <input type="text" name="postcode" placeholder="Postcode" >
                             </div>
                             <div class="seperate">
                             <input type="text" name="street" placeholder="Street Address" >
                             </div>
                        </div>
                    </div>
                    <div class="continue-pay">
                        <h5>Continue with payment process</h5>
                        <div class="agreement">
                        <input type="checkbox" name="agreed" value="i agree"><span> I have read and agree to the<a href=""> Terms of Service</a></span>
                        </div>
                        <input type="hidden" name="amount" value="<?= !empty($_SESSION['amount']) ? $_SESSION['amount']:"";?>">
                       <button type="submit" name="continue">Continue</button>
                      
                    </div>
                    </form>
                </div>
                <div class="check-right-div">
                    <div class="summary">
                        <h5>Order Summary</h5>
                    <table>
                <tr>
                    <th>Items</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                <?php
                if(!empty($_SESSION['cart'])){
                 foreach($_SESSION['cart'] as $key => $value):?>
                <tr>
                    <td><?php echo $value['product_name']; ?></td>
                    <td><?php echo $value['product_qty']; ?></td>
                    <td><?php echo $value['product_qty'] * $value['product_price']; ?></td>
                    
                </tr>
                <?php endforeach; 
                }
                ?>
        
                </table>
                <div class="check-total">
                    <h5 style="font-size:18px;">Delivery</h5>
                    <h5 style="font-size:16px; color:black; font-weight:400;"><?php echo 'NGN '. $_SESSION['delivery']. '.00'; ?></h5>
                </div>
                <div class="check-total">
                    <h5>Totals</h5>
                    <h5><?php echo 'NGN '. $_SESSION['amount']; ?></h5>
                </div>
                    </div>
                </div>
            </div>

        </div>
        <?php require_once('footer.php'); ?>
            </div>
    </div>



    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anton|Cabin|Lato|Fjalla+One|Montserrat|Roboto&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>