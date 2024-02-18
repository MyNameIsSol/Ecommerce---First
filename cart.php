<?php
session_start();
require_once('function.php');
$dbName = "crochet_andhooks";
$newPtable = "new_products";
$featuredPtable = "feautured_product";
$db = new CreateDb($dbName, $newPtable);
$db = new CreateDb($dbName, $featuredPtable);
$cols = "*";
$validate = new validator();

if (isset($_GET['action']) && $_GET['action'] == $validate->validateName('delete')) {
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['product_code'] == $validate->validateEncrypVal($_GET['id'])) {
            unset($_SESSION['cart'][$key]);
            echo "<script>alert('product has been removed from cart...!'</script>";
            echo "<script>window.location = 'cart.php';</script>";
        }
    }
}


if(!empty($_SESSION['user'])){
    $username = $_SESSION['user']['name'];
}else if(!empty($_SESSION['admin'])){
    $adminname = $_SESSION['admin']['name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cart | checkout</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Anton|Cabin|Lato|Fjalia+one|Montserrat|Kalam|Roboto&display=swap" rel="stylesheet">

</head>

<body>
    <div class="loadr">
        <img src="img/icons/preloader.gif" alt="Loading..." />
    </div>
    <div class="wrapper">
        <?php require_once('header.php'); ?>
        <?php require_once('alert.php'); ?>

        <div class="contain-er">
        <div class="cart">
        <div class="check-top1">
                <div class="check-top2">
                <img src="img/47.jpg"  class="image-responsive">
                </div>
                <h5 class="cart-top-txt">Crochet<span>_andhooks</span></h5>
            </div>
            <div class="cart-body">
                <h3>Cart Items</h3>

                <div class="cart-items">
                    <div class="cart-items-div">
                        <table>
                            <tr class="table-head">
                                <th>Products/Options</th>
                                <th>Unit Price</th>
                                <th class="p-qty">Quantity</th>
                                <th>Final Price</th>
                                <th>Remove</th>
                            </tr>
                            <?php
                            $total = 0;
                            if (!empty($_SESSION['cart'])) {
                                $fetch = $db->reTrieveTwo($newPtable, $featuredPtable, $cols);
                                while ($rows = mysqli_fetch_array($fetch)) {
                                    foreach ($_SESSION['cart'] as $key => $value) {
                                        if ($rows['product_code'] == $value['product_code']) {
                                            if (isset($_GET['code']) && $value['product_code'] == $validate->validateEncrypVal($_GET['code'])) {
                                                if (isset($_GET['quantity']) && $validate->validateName($_GET['action']) == 'add') {
                                                    $_SESSION['cart'][$key]['product_qty'] = (int) $validate->validateNumber($_GET['quantity']) + 1;
                                                } elseif (isset($_GET['quantity']) && $validate->validateName($_GET['action']) == 'minus') {
                                                    if ((int) $validate->validateNumber($_GET['quantity']) > 1) {
                                                        $_SESSION['cart'][$key]['product_qty'] = (int) $validate->validateNumber($_GET['quantity']) - 1;
                                                    } else {
                                                        $_SESSION['cart'][$key]['product_qty'] = 1;
                                                    }
                                                }
                                            }


                                            ?>
                                            <form action="<?php echo htmlspecialchars('checkout.php'); ?>" method="POST">
                                                <tr class="table-body">
                                                    <td class="items">
                                                        <img src="<?= $rows['product_image']; ?>" class="image-responsive" alt="product-image">
                                                        <h5 class="ml-3"><?= $rows['product_name']; ?></h5>
                                                    </td>

                                                    <td class="price">
                                                        <h5><?= $rows['product_price']; ?></h5>
                                                    </td>

                                                    <td class="quantity">
                                                        <a href="cart.php?action=minus&quantity=<?= $_SESSION['cart'][$key]['product_qty']; ?>&code=<?= $rows['product_code']; ?>" class="minus"><span class="fa fa-minus"></span></a>
                                                        <input type="text" name="qnty" value="<?= $_SESSION['cart'][$key]['product_qty']; ?>" class="qnty ml-1 mr-1">
                                                        <a href="cart.php?action=add&quantity=<?= $_SESSION['cart'][$key]['product_qty']; ?>&code=<?= $rows['product_code']; ?>" class="add"><span class="fa fa-plus"></span></a>
                                                    </td>

                                                    <td class="f-price">
                                                        <h5><?= (int) $_SESSION['cart'][$key]['product_qty'] * (int) $rows['product_price']; ?></h5>
                                                    </td>
                                                    <td class="remove"><a href="cart.php?action=delete&id=<?php echo $rows['product_code']; ?>"><span class="fa fa-times"></span></a></td>
                                                </tr>

                                <?php
                                                $total = $total + ((int) $_SESSION['cart'][$key]['product_qty'] * (int) $rows['product_price']);
                                            }
                                        }
                                    }
                                } else {
                                    echo "<h3 class='empty-cart'>NO ITEM IN CART</h3>";
                                    echo '<tr class="table-body">
                            <td class="items">
                                
                                <h5 class="ml-3"> None </h5>
                            </td>
                            <td>
                                <h5>0</h5>
                            </td>
                            <td><a href="" disabled="disabled"><span class="fa fa-minus"></span></a>
                                <!-- <h5 class="d-inline ml-3 mr-3"> 1 </h5> -->
                                <input type="text" name="qnty" value="0" disabled class="qnty ml-3 mr-3"> 
                                <a href="" disabled ><span class="fa fa-plus"></span></a>
                            </td>
                            <td>
                                <h5>0</h5>
                            </td>
                            <td><a href="" disabled ><span class="fa fa-times"></span></a></td>
                            <!-- </div> -->
                        </tr>';
                                }
                                ?>
                        </table>
                        <div class="shopping">
                            <a href="index.php"><span class="fa fa-angle-left"></span> Continue shopping</a>
                        </div>
                    </div>
                    <div class="checkout-div">
                        <div class="order-summary">
                        <h4>Summary</h4>
                        <div class="checkout-tota-div"></div>
                       
                                <div class="sub-total-amount">
                                <div><h6>Subtotal</h6></div>
                                    <div><h5><?php echo 'NGN ' . $total . '.00'; ?></h5></div>
                                </div>
                        
                                <div class="delivery">
                                <div><h6>Delivery Fee</h6></div>
                                <div><h5>NGN <?php $delivery = 2000; echo $delivery. '.00'; ?></h5></div>
                                <div style="display:none;"><?php $_SESSION['delivery'] = $delivery; ?></div>

                                </div>
                              
                            <div class="checkout-tota-div"></div>
                        
                                <div class="checkout-total">
                                    <h6>Total Price</h6>
                                    <h5><?php echo 'NGN ' . $total = $total + $delivery . '.00'; ?></h5>
                                    <input type="hidden" name="total" value="<?= !empty($total) ? $total: '';?>">
                                </div>
                           
                            <div class="checkout-btn-sec">
                                <div class="checkout-btn pt-3"><button type="submit" name="checkout">Proceed to checkout</button></div>
                            </div>
                       
                        </form>
                    </div>
                    </div>
                </div>

            </div>
           
        </div>
        <div class="footer-sec" style="margin-top: 60px;background-color: #38324E;">
        <!-- <ul class="footer-link">
                    <li class="stories"><a href="">STORIES</a></li>
                    <li><a href="">CONTACT</a></li>
                    <li><a href="">FAQ</a></li>
                    <li><a href="">VIDEOS</a></li>
                </ul> -->
                <div class="copyright" >
                    <p class="mr-1" style="line-height:20px;"> &copy; 2020 </p>
                    <h6> Crochet_andhooks.com</h6>
                </div>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>