<?php
require_once('function.php');
$dbName = "crochet_andhooks";
$tbSub = "subscribed_users";
$tbSendmsg = "users_message";
$tbSubCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(225) NOT NULL,
                sub_date DATETIME NOT NULL,
                userid VARCHAR(22) NOT NULL';

$tbSendmsgCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                firstname VARCHAR(22) NOT NULL,
                surname VARCHAR(22) NOT NULL,
                email VARCHAR(225) NOT NULL,
                message VARCHAR(22) NOT NULL,
                msg_date DATETIME NOT NULL,
                userid VARCHAR(22) NOT NULL';

$db = new CreateDb($dbName, $tbSub, $tbSubCols);
$db = new CreateDb($dbName, $tbSendmsg, $tbSendmsgCols);
$validate = new validator(); 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sub'])) {
    $email = $validate->validateEmail($_POST['email']);
    $date = date('y/m/d h:i:s');
    if ($email != false) {
        $tbCols = '*';
        $where = "email = '$email'";
        $limit = '1';
        $findUser = $db->checkIfExist($tbSub, $tbCols, $where, $limit);
        if(mysqli_num_rows($findUser)>0) {
            $error = 'Sorry! this email already exist';
            echo '<div class="alert alert-warning" style"display">'.$error.'</div>';
        } else {
            $encrpt = md5($email.time());
            $userid = substr($encrpt,0,3).substr($encrpt,-3,3);
            $tbcols = "email,sub_date,userid";
            $values = "'$email','$date','$userid'";
            $insert = $db->inSert($tbSub, $tbcols, $values);
            if ($insert) {

                $site_name = 'Crochet_andhooks';
                $sitesupport_email = 'ellasinspo@crochetandhooks.com.ng';
                $receiver_email = $foundUserEmail;

    $title = 'Thanks for subscribing to crochetandhooks.com.ng';
    $headers = "MIME-Version: 1.0" . PHP_EOL;
    $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
    $headers .= "From: $site_name <$sitesupport_email>" . PHP_EOL;
    $headers .= "Organization: $site_name" . PHP_EOL;
    // $headers .= "Reply-To: $site_name Support Team <$sitesupport_email>" . PHP_EOL;
    $headers .= "Return-Path: <$sitesupport_email>" . PHP_EOL;
    $headers .= "X-Priority: 3" . PHP_EOL;
    $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;
    $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;font-family:helvetica,\'helvetica neue\',arial,verdana,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;margin:0"><head><meta charset="UTF-8"><meta content="width=device-width,initial-scale=1" name="viewport">
                <head>
                <meta name="x-apple-disable-message-reformatting">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta content="telephone=no" name="format-detection">
                <title>'.$title. '</title>
                <script src="https://kit.fontawesome.com/637381c909.js" crossorigin="anonymous"></script>
                <link href="https://fonts.googleapis.com/css?family=Anton|Heebo|Redressed&display=swap" rel="stylesheet">
                </head>
                <body>
                <div class="email-div" style="width:100%; height:auto; margin:0; padding:10px 0px 15px; background-color:#E4E5E4;">
  
               <h5 style="margin:10px 0;text-align: center; font-size: 20px;font-family: "Grandstander",cursive;color:#D81B5A;">Crochet<span style="color: #BF944E;">_andhooks</span></h5>
              <div class="email-content" style="width:80%; margin:15px auto; padding:30px; background-color:white;color:#424242;">

          <h5 style="font-size:18px; margin:15px 0;">Hello there,</h5>
          <p style="margin:5px 0; font-family: "Heebo", sans-serif;font-size:14px;">We'."'".'d like to extend a warm welcome and thank you for subscribing to our website.</p>
        
          <p style="margin-buttom:5px;font-family: "Heebo", sans-serif;font-size:14px;">Crochectandhooks endeavors to send you only the best contents regarding
           our new products and services that are relevant to your needs.If we ever deviate from that, just  send us an email and we'."'".'ll do our best to get it straightened out</p>
          <p style="font-family: "Redressed", cursive;">Warm regards,</p>
          <p style="font-size:14px; font-weight:700;">' . $site_name . ' Support</p>

          <span style="font-family: "Heebo", sans-serif;font-size:12px;">Crochetandhooks.com.ng</span><br>
              <span style="font-family: "Heebo", sans-serif;font-size:12px;">Email: ellasinspo@crochetandhooks.com.ng</span><br>
              <span style="font-family: "Heebo", sans-serif;font-size:12px;">Website: crochetandhooks.com.ng</span><br>
              <span style="font-family: "Heebo", sans-serif;font-size:12px;">Lagos, Nigeria.</span><br>
          
            </div>
          <h5 style="text-align:center; margin:10px 0; font-size:13px;">Connect With:</h5>
            <div class="connect" style="display:flex; justify-content:center;">
                <a href=""><span style="font-size:18px;" class="fa fa-facebook-square"></span></a>
                <a href=""><span style="font-size:18px; margin:0 10px;" class="fa fa-twitter"></span></a>
                <a href=""><span style="font-size:18px" class="fab fa-instagram"></span></a>
                <a href="" style="font-size:18px; margin-left:10px;"><span  class="fab fa-whatsapp"></span></a>
            </div>
            
           <div class="copy-write" style="width:100%; display:flex; justify-content:center; align-items:center;">
                <p style="margin-right:10px; font-size:12px; color:#AC39AC;"> &copy; 2020 </p>
                <a href="crochetandhooks.com.ng" style="text-decoration:none; color:#AC39AC;"><h6 style="font-size:14px; margin:12px 0;"> Crochetandhooks.com.ng</h6></a>
            </div>
      
         </div>
                <script src="https://kit.fontawesome.com/637381c909.js" crossorigin="anonymous"></script>
                </body>
                </html>';
                if(@mail($receiver_email,$title,$message,$headers)){   
                    $success = 'You have successfully Subscribed';
                    echo '<div class="alert alert-success" style"display">'.$success.'</div>';
                    }else{
                        $error = 'Sorry! an error occured';
                        echo '<div class="alert alert-warning" style"display">'.$error.'</div>';
                    }
            } else {
                $error = 'Sorry! An error occured';
                echo '<div class="alert alert-warning" style"display">'.$error.'</div>';
            }}
    } else {
        $error = 'Please provide a valid email address';
        echo '<div class="alert alert-warning" style"display">'.$error.'</div>';
    }}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usermessage'])) {
    $firstname = $validate->validateName($_POST['firstname']);
    $surname = $validate->validateName($_POST['surname']);
    $email = $validate->validateEmail($_POST['email']);
    $text_message = $validate->validateText($_POST['message']);
    $date = date('y/m/d h:i:s');
    if ($firstname != false) {
        if ($surname != false) {
            if ($email != false) {
                if ($text_message != false) {
                    $tbCols = '*';
                    $where = "email = '$email' && message = '$text_message'";
                    $limit = '1';
                    $findMsg = $db->checkIfExist($tbSendmsg, $tbCols, $where, $limit);
                    if (mysqli_num_rows($findMsg)>0) {
                        $error = 'Sorry! This message have been submitted previously';
                    } else {
                    $encrpt = md5($email.time());
                    $userid = substr($encrpt,0,3).substr($encrpt,-3,3);
                    $tbcols = "firstname,surname,email,message,msg_date,userid";
                    $values = "'$firstname','$surname','$email','$text_message','$date','$userid'";
                    $insert = $db->inSert($tbSendmsg, $tbcols, $values);
                    if ($insert) {

                        $sender_email = $email;
                        $sender_name = $firstname.' '.$surname;
                        $receiver_email = 'ellasinspo@crochetandhooks.com.ng';
    
            $title = 'Customers request';
            $headers = "MIME-Version: 1.0" . PHP_EOL;
            $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
            $headers .= "From: $sender_name  <$sender_email>" . PHP_EOL;
            // $headers .= "Organization: $site_name" . PHP_EOL;
            $headers .= "Reply-To: $firstname <$sender_email>" . PHP_EOL;
            $headers .= "Return-Path: <$sender_email>" . PHP_EOL;
            $headers .= "X-Priority: 3" . PHP_EOL;
            $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;font-family:helvetica,\'helvetica neue\',arial,verdana,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;margin:0"><head><meta charset="UTF-8"><meta content="width=device-width,initial-scale=1" name="viewport">
                        <head>
                        <meta name="x-apple-disable-message-reformatting">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta content="telephone=no" name="format-detection">
                        <title>'.$title. '</title>
                        <script src="https://kit.fontawesome.com/637381c909.js" crossorigin="anonymous"></script>
                        <link href="https://fonts.googleapis.com/css?family=Anton|Heebo|Redressed&display=swap" rel="stylesheet">
                        </head>
                     <body>
                        <div class="email-div" style="width:100%; height:auto; margin:0; padding:10px 0px 15px; background-color:#E4E5E4;">
          
                       <h5 style="margin:10px 0;text-align: center; font-size: 20px;font-family: "Grandstander",cursive;color:#D81B5A;">Crochet<span style="color: #BF944E;">_andhooks</span></h5>
                      <div class="email-content" style="width:80%; margin:15px auto; padding:30px; background-color:white;color:#424242;">
    
                  <h5 style="font-size:18px; margin:15px 0;">Dear Crochet_andhooks,</h5>
                  
                  <p style="font-family: "Heebo", sans-serif;font-size:14px;">'.$text_message.' </p>
                  <p style="font-family: "Redressed", cursive;">kind regards,</p>
                  <p style="font-size:14px; font-weight:700;">' . $firstname . '</p>
                  
                    </div>
                  <h5 style="text-align:center; margin:10px 0; font-size:13px;">Connect With:</h5>
                    <div class="connect" style="display:flex; justify-content:center;">
                        <a href=""><span style="font-size:18px;" class="fa fa-facebook-square"></span></a>
                        <a href=""><span style="font-size:18px; margin:0 10px;" class="fa fa-twitter"></span></a>
                        <a href=""><span style="font-size:18px" class="fab fa-instagram"></span></a>
                        <a href="" style="font-size:18px; margin-left:10px;"><span  class="fab fa-whatsapp"></span></a>
                    </div>
                    
                   <div class="copy-write" style="width:100%; display:flex; justify-content:center; align-items:center;">
                        <p style="margin-right:10px; font-size:12px; color:#AC39AC;"> &copy; 2020 </p>
                        <a href="crochetandhooks.com.ng" style="text-decoration:none; color:#AC39AC;"><h6 style="font-size:14px; margin:12px 0;"> Crochetandhooks.com.ng</h6></a>
                    </div>
              
                 </div>
                        <script src="https://kit.fontawesome.com/637381c909.js" crossorigin="anonymous"></script>
                        </body>
                        </html>';
                        if(@mail($receiver_email,$title,$message,$headers)){   
                            $success = "Message sent";
                            }else{
                                $error = 'Sorry! your message was not  delivered';
                            }
                    } else {
                        $error = 'Sorry! Message sending failed';
                    }
                }
                } else {
                    $error = 'Please provide a valid message';
                }
            } else {
                $error = 'Please provide a valid email address';
            }
        } else {
            $error = 'Please provide a valid name';
        }
    } else {
        $error = 'Please provide a valid name';
    }
}


session_start();
if(isset($_SESSION['user'])){
    $username = $_SESSION['user']['name'];
}elseif(isset($_SESSION['admin'])){
    $adminname = $_SESSION['admin']['name'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Us | crochet_andhooks</title>
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
    <link href="https://fonts.googleapis.com/css?family=Anton|DM+Serif+Text|Josefin+Sans|Lato|Roboto&display=swap" rel="stylesheet">
</head>

<body>
    <div class="loadr">
        <img src="img/icons/preloader.gif" alt="Loading..." />
    </div>
    <div class="wrapper">
        <?php require_once('header.php'); ?>
        <?php require_once('alert.php'); ?>
        
        <div class="contain-er">
        <div class="myalert alert-info">
            <p>
                <?php if (!empty($error)) {
                    echo $error;
                    echo "<script>$('.myalert').addClass('alert-warning').fadeIn(1000)</script>";
                } elseif (!empty($success)) {
                    echo $success;
                    echo "<script>$('.myalert').addClass('alert-info').fadeIn(1000)</script>";
                }
                ?>
            </p>
            <a href="" id="cancel">
                <span class="fa fa-times"></span>
            </a>
        </div>

        <div class="nav-img">
            <img src="img/51.jpg" alt="about" class="image-responsive">
            <div class="contact-top">
                <span class="fa fa-address-book-o mb-3"></span>
                <h1>Contact Us</h1>

                <a href="index.php"><span class="fa fa-home"></span> Home <span class="fa fa-angle-right"></span></a> <em>Contact</em>
            </div>
        </div>
        <div class="send-message">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h3>Feel free <br>to send us a message</h3>
                <input type="text" name="firstname" value="<?= !empty($_POST['firstname']) ? $firstname: "";?>" placeholder="First name">
                <input type="text" name="surname" value="<?= !empty($_POST['surname']) ? $surname: "";?>" placeholder="Surname">
                <input type="email" name="email" value="<?= !empty($_POST['email']) ? $email: "";?>" placeholder="Email">
                <textarea type="text" name="message" placeholder="Type in your message" rows="5" cols="50"></textarea>
                <input type="submit" id="send" name="usermessage" value="Send">
            </form>
        </div>

        <div class="address">
            <h4>Get in touch with us.</h4>
            <div class="contact-address">
                <div class="phone">
                    <span class=" fa fa-phone"></span>
                    <h4>Phone</h4>
                    <p>+2347059195916, +2349061994020</p>
                    
                </div>
                <div class="location">
                    <span class="fa fa-location-arrow "></span>
                    <h4>Address</h4>
                    <p>No.8 off Transformer tedi,muwo opp ojo barracks lagos.</p>
                </div>
                <div class="email">
                    <span class="fa fa-envelope-o"></span>
                    <h4>Email</h4>
                    <p>ellasinspo@crochetandhooks.com.ng</p>
                </div>
            </div>
        </div>




        <div class="footer">
            <h6 style="color:#808080; font-size: 14px;">Follow us</h6>

            <ul class="contact">
                    <li><a href="https://www.facebook.com/crochetandhooks"><img src="img/icons/facebook.png" class="image-responsive" alt="facebook-icon"></a></li>
                    <!-- <li><a href=""><img src="img/icons/twitter.png" class="image-responsive" alt="twitter-icon"></a></li> -->
                    <li><a href="https://instagram.com/crochet_andh00ks"><img src="img/icons/instagram.png" class="image-responsive" alt="instagram-icon"></a></li>
                    <li><a href="https://wa.me/2347059195916"><img src="img/icons/whatsapp.png" class="image-responsive" alt="whatsapp-icon"></a></li>
                    <!-- <li><a href=""><img src="img/icons/pinterest.png" class="image-responsive" alt="pinterest-icon"></a></li> -->
                </ul>

            <form  class="cont-form" action="" method="post">
                <input type="email" name="email" placeholder="Enter your email">
                <a href="" class="sub-btn"><span class="fa fa-telegram"></span> subscribe</a>
            </form>

            <?php require_once('footer.php'); ?>
        </div>
        </div>
    </div>

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