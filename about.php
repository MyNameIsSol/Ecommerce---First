<?php
session_start();
if(!empty($_SESSION['user'])){
    $username = $_SESSION['user']['name'];
}else if(!empty($_SESSION['admin'])){
    $adminname = $_SESSION['admin']['name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>About Us | Crochet_andHooks</title>
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
        <div class="nav-img">
            <img src="img/47.jpg" alt="about" class="image-responsive">
            <div class="bg-text">
                <h1>Crochet_AndHooks.</h1>
                <div class="dropdown-divider mb-3"></div>
                <p class="mb-5">We try as much as we can to offer the best to our customers.</p>
                <a href="#" class="about-btn"><span class="fa fa-info-circle "></span>Read More</a>

                <a href="#" class="angle-down"><span class="fa fa-angle-down"></span></a>
            </div>
        </div>
        <div class="about-div">
            <div class="about-text">
                <h1>About Us</h1>
                <div class="dropdown-divider"></div>
                <h3>What Crochet_andHooks is all about.</h3>
                <p>Crochet_andhooks is a self-owned business. Crochet_andhooks is passionate about helping people enjoy the pleasure of various crocheting materials and tools to create simple and complex crochet patterns with ease. Here you can buy different types of crochet yarn, hooks, wools, needles etc. and pay with ease using your debit card through our website. We deliver our products to virtually every state in nigeria for a fee as low as NGN 2000. Crochect_andhooks also offer free and paid online tutorial videos on crocheting to curious, driven doers who believe in the power of creating giving you a step by step instructions and lessons on how to make things you're proud of over and over again. By so doing, we strive to promote crochet and foster connections between crocheters. For more detailed enquiry about our products, kindly send us a message through our contact page or give us a call using our customer care line in the contact page.</p>
            </div>
            <div class="about-image">
                <img src="img/14.jpg" alt="about-img" class="image-responsive">
            </div>
        </div>
        <div class="read-more">
            <h4>Learn More</h4>
            <div class="dropdown-divider mb-2"></div>
            <div class="more">
                <div class="more-info">
                    <img src="img/41.jpg" alt="more" class="image-responsive">
                    <h5>Why we are different</h5>
                    <p>We believe crochet is a fulfilling hobby that stimulate creativity and hope to use our love of hook and yern to access a deeper level of artistic creativity.</p>
                </div>
                <div class="goal">
                    <img src="img/2.jpg" alt="goal" class="image-responsive">
                    <h5>Our goal</h5>
                    <p>To create an active and supportive community with common interest and to create a unique artistic crochet pattern that will break through. </p>
                </div>
                <div class="customer">
                    <img src="img/21.jpg" alt="custormer" class="image-responsive">
                    <h5>Better for our customers</h5>
                    <p>Our customer needs are taken as priority. We offer 24/7 service and support to customers to enable them get in touch with us and get assistance whenever they need.</p>
                </div>
            </div>
        </div>
        <?php require_once('footer.php'); ?>
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