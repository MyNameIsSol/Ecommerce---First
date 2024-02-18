<?php
session_start();
require_once('function.php');
$dbName = "crochet_andhooks";
$usertable = "users";
$admintable = "admin";
$resetPasstable = "password_reset";
$db = new CreateDb($dbName, $usertable);
$validate = new validator();

if ( isset($_GET['token']) && isset($_GET['action']) && $_GET['action'] == 'reset'){
    $token = $validate->validateEncrypVal($_GET['token']);
    if($token != false){
        $col = "*";
        $where = "token = '$token'";
        $limit = "1";
        $findUserToken = $db->checkIfExist($resetPasstable,$col,$where,$limit);
        if(mysqli_num_rows($findUserToken)>0){
            if($findUser = mysqli_fetch_array($findUserToken)){
                $email = $findUser['email'];
            }
        }else{
            header('Location:login.php');
        }
    }else{
        $error = "Sorry, token compromised";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetpassword'])) {
    $email = $validate->validateEmail($_POST['email']);
    $password = $validate->validatePassword($_POST['password']);
    $c_password = $validate->validatePassword($_POST['c_password']);
    $encryptpassword = sha1(md5($password));
    $encryptc_password = sha1(md5($c_password));
    if ($email != false) {
        if ($password != false) {
            if ($c_password != false) {
                if ($encryptpassword == $encryptc_password) {
                    $cols = '*';
                    $where = "email = '$email'";
                    $limit = '1';
                    $findUser = $db->checkIfExist($resetPasstable, $cols, $where, $limit);
                    if (mysqli_num_rows($findUser)>0) {
                        if($foundUser = mysqli_fetch_array($findUser)) {
                            $foundUserEmail = $foundUser['email'];
                            if(!empty($foundUser['userid'])){
                                $foundUserId = $foundUser['userid']; 
                            }
                            if ($email == $foundUserEmail) {
                                $where = "email = '$email'";
                                $values = "password ='$encryptpassword'";
                                $update = $db->upDate($usertable, $admintable, $cols, $values, $where,$limit);
                                if ($update) {
                                    $delete = $db->deL($resetPasstable, $where);
                                    if($delete){
                                    mysqli_close($db->condb);
                                    $_SESSION['user'] = array('email' => $foundUserEmail);
                                    header('location:login.php');
                                }
                                } else {
                                    $error = "Something went wrong, Could not reset your password";
                                }
                            } else {
                                    $error = 'incorrect email';
                                }
                        }
                    }else{
                        $error = "Sorry we could not process your request ";
                    }
                } else {
                    $error = 'Password does not match';
                }
             } else {
                $error = 'please confirm password';
            }
        } else {
            $error = 'invalid password';
        }
    } else {
        $error = 'Please provide a valid email address';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset-password</title>
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
                } elseif (!empty(  $success)) {
                    echo   $success;
                    echo "<script>$('.myalert').addClass('alert-info').fadeIn(1000)</script>";
                }
                ?>
            </p>
            <a href="" id="cancel">
                <span class="fa fa-times"></span>
            </a>
        </div>

        <div class="reset-pass-maindiv">
            <div class="reset-pass-div">
            <img src="img/icons/Crochet_andHooks.png" alt="Crochet_andHooks" class="image-responsive">
                <div class="login-form">
                    <h2>Reset your password</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="email" name="email" value="<?= !empty($email) ? $email: "";?>" placeholder="Email">
                        <input type="password" name="password" placeholder="Password">
                        <input type="password" name="c_password" placeholder="Comfirm Password">
                        <div class="signup-button">
                            <input id="signup-form-submit" type="submit" name="resetpassword" value="Reset Password">
                        </div>
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
