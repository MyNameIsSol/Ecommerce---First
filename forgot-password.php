<?php
session_start();
require_once('function.php');
$dbName = "crochet_andhooks";
$usertable = "users";
$admintable = "admin";
$resetPasstable = "password_reset";
$tbCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(225) NOT NULL,
                userid VARCHAR(11) NOT NULL,
                token VARCHAR(225) NOT NULL';
$db = new CreateDb($dbName, $resetPasstable, $tbCols);
$validate = new validator();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetlink'])) {
    $email = $validate->validateEmail($_POST['email']);
    if($email != false){
        $cols = '*';
        $where = "email = '$email'";
        $limit = '1';
        $findUser = $db->checkIfExist2table($usertable,$admintable, $cols, $where, $limit);
        if (mysqli_num_rows($findUser)>0) {
            if($foundUser = mysqli_fetch_array($findUser)) {
                $foundUserEmail = $foundUser['email'];
                if(!empty($foundUser['firstname'])){
                    $foundUserName = $foundUser['firstname']; 
                }elseif(!empty($foundUser['username'])){
                    $foundUserName = $foundUser['username'];
                }
                if(!empty($foundUser['userid'])){
                    $foundUserId = $foundUser['userid']; 
                }elseif(!empty($foundUser['adminid'])){
                    $foundUserId = $foundUser['adminid'];
                }
                if ($email === $foundUserEmail) {
                    $token = uniqid(md5(time()));//create random token...
                    $tbcols = "email,userid,token";
                    $values = "'$email','$foundUserId','$token'";
                    $insert = $db->inSert($resetPasstable, $tbcols, $values);
                    if ($insert) {

                    $site_name = 'Crochet_andhooks';
                    $sitesupport_email = 'ellasinspo@crochetandhooks.com.ng';
                    $receiver_name = $foundUserName;
                    $receiver_email = $foundUserEmail;
                    $token = $token;

        $title = 'Crochetandhooks.com.ng - Password Reset';
        $headers = "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "From: $site_name <$sitesupport_email>" . PHP_EOL;
        $headers .= "Organization: $site_name" . PHP_EOL;
        $headers .= "Reply-To: $site_name Support Team <$sitesupport_email>" . PHP_EOL;
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
      
                   <h5 style="margin:10px 0;text-align: center; font-size: 20px; color:#D81B5A; font-family: "Grandstander",cursive; ">Crochet<span style="color: #BF944E;">_andhooks</span></h5>
                  <div class="email-content" style="width:80%; margin:15px auto; padding:30px; background-color:white;color:#424242;">

              <h5 style="font-size:18px; margin:15px 0;">Dear ' . $receiver_name . ',</h5>
              <p style="font-family: "Heebo", sans-serif;font-size:15px;">We received a request to reset your password. Simply click the button below to reset your password.</p>
              <a href="https://crochetandhooks.com.ng/reset-password?token=' . $token . '&action=reset" style="padding:10px; color:white; background-color:#AC39AC; border-radius:5px; text-decoration:none;">Reset My Password</a>
              <p style="font-family: "Heebo", sans-serif;font-size:15px;">If you did not request this, no action will be performed. ignore </p>
              <p style="font-family: "Redressed", cursive;">Warm regards,</p>
              <p style="font-size:14px; font-weight:700;">' . $site_name . ' Support</p>
              
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
                    $_SESSION['success'] = "Your request has been sent, click on the link sent to your email to reset your password";
                    header('Location: forgot-password.php');
                    }else{
                        $error = 'Sorry! we could not process your request';
                    }
                    } else {
                    $error = 'Sorry! your request failed';
                  }
                } else {
                        $error = 'incorrect email';
                    }
            }
        }else{
            $error = "Sorry you are not a member, please signup";
        }
    } else {
        $error = 'Please provide a valid email address';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Password | request</title>
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
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Hi+Meody|Lato|Grandstander|Montserrat|Kalam|Roboto&display=swap" rel="stylesheet">
    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>

</head>

<body>
    <div class="loadr">
        <img src="img/icons/preloader.gif" alt="Loading..." />
    </div>
    <div class="wrapper">
        <div class="myalert">
            <p>
                <?php if (!empty($error)) {
                    echo $error;
                    echo "<script>$('.myalert').addClass('alert-warning').fadeIn(1000)</script>";
                } elseif (!empty($_SESSION['success'])) {
                    echo $_SESSION['success'];
                    echo "<script>$('.myalert').addClass('alert-info').fadeIn(1000)</script>";
                }
                ?>
            </p>
            <a href="" id="cancel">
                <span class="fa fa-times"></span>
            </a>
        </div>

        <div class="forgot-pass-maindiv">

            <div class="forgot-pass-div">
            <img src="img/icons/Crochet_andHooks.png" alt="Crochet_andHooks" class="image-responsive">

                <div class="login-form">
                    <h2>Forgot password</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="email" name="email" value="<?= !empty($_POST['email']) ? $email: "";?>" placeholder="Enter you email">
                        <span style="font-size:14px; color:blue; display:block; text-align:start; margin-left:5px;">NB: A recovery link will be sent to this email, please check your mail box.</span>
                        <input id="login-form-submit" type="submit" name="resetlink" value="Get reset link">
                    </form>
                </div>
            </div>
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