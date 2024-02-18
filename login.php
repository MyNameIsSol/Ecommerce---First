<?php
session_start();
require_once('function.php');
$dbName = "crochet_andhooks";
$tbName = "users";
$admintable = "admin";
$tbCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                firstname VARCHAR(22) NOT NULL,
                lastname VARCHAR(22) NOT NULL,
                email VARCHAR(225) NOT NULL,
                phone VARCHAR(22) NOT NULL,
                country VARCHAR(22) NOT NULL,
                state VARCHAR(22) NOT NULL,
                password VARCHAR(22) NOT NULL,
                privacy_and_policy VARCHAR(22) NOT NULL,
                reg_date DATETIME NOT NULL,
                userid VARCHAR(22) NOT NULL';

$db = new CreateDb($dbName, $tbName, $tbCols);
$validate = new validator();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $firstname = $validate->validateName($_POST['firstname']);
    $lastname = $validate->validateName($_POST['lastname']);
    $email = $validate->validateEmail($_POST['email']);
    $phone = $validate->validateNumber($_POST['phone']);
    $country = $validate->validateString($_POST['country']);
    $state = $validate->validateString($_POST['state']);
    $password = $validate->validatePassword($_POST['password']);
    $c_password = $validate->validatePassword($_POST['c_password']);
    if(!empty($_POST['agreed'])){ $privacyPolicy = $validate->validateString($_POST['agreed']); }else{ $privacyPolicy = "";}
    $date = date('d/m/y h:i:s');
    $encryptpassword = sha1(md5($password));
    $encryptc_password = sha1(md5($c_password));
    if ($firstname != false) {
        if ($lastname != false) {
            if ($email != false) {
                if ($phone != false) {
                    if ($country != false) {
                        if ($state != false) {
                            if ($password != false) {
                                if ($c_password != false) {
                                    if ($privacyPolicy != false && $privacyPolicy != "") {
                                if ($encryptpassword == $encryptc_password) {
                                        $tbCols = '*';
                                        $where = "email = '$email'";
                                        $limit = '1';
                                        $findUser = $db->checkIfExist($tbName, $tbCols, $where, $limit);
                                        if (mysqli_num_rows($findUser)>0) {
                                            $error = 'Sorry! user already exist';
                                        } else {
                                            $encrpt = md5($email.time());
                                            $userid = substr($encrpt,0,3).substr($encrpt,-3,3);
                                            $tbcols = "firstname,lastname,email,phone,country,state,password,privacy_and_policy,reg_date,userid";
                                            $values = "'$firstname','$lastname','$email','$phone','$country','$state','$encryptpassword','$privacyPolicy','$date','$userid'";
                                            $insert = $db->inSert($tbName, $tbcols, $values);
                                            if ($insert) {
                                                $_SESSION['user'] = array('name' => $firstname,'id' => $userid);
                                                header('Location: index.php');
                                            } else {
                                                $error = 'Sorry! Your registraion failed';
                                            }
                                        }
                                } else {
                                    $error = 'Password does not match';
                                }
                            } else {
                                $error = 'check the box to agree with our privacy and policy';
                            }
                            } else {
                                $error = 'please confirm password';
                            }
                            } else {
                                $error = 'invalid password format';
                            }
                        } else {
                            $error = 'Please recheck the state provided';
                        }
                    } else {
                        $error = 'Country is not recognized';
                    }
                } else {
                    $error = 'Please provide a valid phone number';
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $validate->validateEmail($_POST['email']);
    $password = $validate->validatePassword($_POST['password']); 
    $encryptpassword = sha1(md5($password));
    if ($email != false) {
        if ($password != false) {
            $tbCols = '*';
            $where = "email = '$email'";
            $limit = '1';
            $findUser = $db->checkIfExist2table($tbName,$admintable, $tbCols, $where, $limit);
            if (mysqli_num_rows($findUser)>0) {
                while ($foundUser = mysqli_fetch_array($findUser)) {
                    
                    $foundUserEmail = $foundUser['email'];
                    $foundUserPassword = $foundUser['password'];
                    if(!empty($foundUser['firstname'])){
                        $foundUserName = $foundUser['firstname']; 
                    }elseif(!empty($foundUser['username'])){
                        $foundUserName = $foundUser['username'];
                    }

                    if(!empty($foundUser['usertype'])){
                        $foundUserType = $foundUser['usertype'];
                    }
                    if(!empty($foundUser['phone'])){
                        $foundUserPhone = $foundUser['phone'];
                    }

                    if(!empty($foundUser['userid'])){
                        $foundUserId = $foundUser['userid']; 
                    }elseif(!empty($foundUser['adminid'])){
                        $foundUserId = $foundUser['adminid'];
                    }
                }if ($email === $foundUserEmail) {
                    if ($encryptpassword === $foundUserPassword) {
                        if($foundUserType == "admin"){
                        $_SESSION['admin'] = array('name' => $foundUserName,'email'=> $foundUserEmail,'phone'=>$foundUserPhone,'id' => $foundUserId);
                        header('Location: dashboard.php');
                        }else{
                        $_SESSION['user'] = array('name' => $foundUserName,'email'=> $foundUserEmail,'phone'=>$foundUserPhone,'id' => $foundUserId);
                        header('Location: index.php');
                        }
                    } else {
                        $error = 'incorrect password';
                    }
                } else {
                    $error = 'incorrect email';
                }
            } else {
                $error = 'User does not exist';
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
    <title>Login | Crochet_andhooks</title>
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
        <div class="login-top1">
            
            <div class="login-top">
            <a class="backhome" href="index.php">Go Back</a>
                        <a class="top-signin" id="show-log" href="">Sign in</a>
                    </div>
                <!-- <div class="login-top2">
                <img src="img/47.jpg" alt="about" class="image-responsive">
                </div> -->
                <h5>Crochet<span>_andhooks</span></h5>
                
            </div>
        <div class="login-maindiv">

            <div class="login-div">
                <a href="" id="hide-login"><span class="fa fa-times"></span></a>

                <div class="login-form">
                    <h2>Log In</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="email" name="email" value="<?= isset($email) ? $email: "";?>" placeholder="Email">
                        <input type="password" name="password" placeholder="Password">
                        <div class="login-form-mid">
                            <div><input type="checkbox" name="user"> <span>Remember me</span></div>
                            <a href="forgot-password.php">Forgot Password</a>
                        </div>
                        <input id="login-form-submit" type="submit" name="login" value="Log in">
                    </form>
                </div>
                <div class="login-form-signup">
                    <span class="mr-2">Don't have an accout?</span><a href="login.php">Sign up</a>
                </div>
            </div>
        </div>


        <div class="signup-div">
            <div class="signup-content">
                <div class="signup-brand">
                    <div class="brand-text">
                        <h2>Sign Up</h2>
                        <h5>Become a member,<span> Fill in the form to register.</span></h5>
                    </div>
                </div>
                <div class="signup-form">
                    <h4 text-align-start>Please fill in your correct details.</h4>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="signup-usernames">
                            <input type="text" name="firstname" value="<?= !empty($_POST['firstname']) ? $firstname: "";?>" placeholder="First Name"><span class="ml-1 mr-1"></span>
                            <input type="text" name="lastname" value="<?= !empty($_POST['lastname']) ? $lastname: "";?>" placeholder="Last Name">
                        </div>
                        <input type="email" name="email" value="<?= !empty($_POST['email']) ? $email: "";?>" placeholder="Email">
                        <input type="tel" name="phone" value="<?= !empty($_POST['phone']) ? $phone: "";?>" placeholder="Phone Number">
                        <div class="signup-usernames">
                            <input type="text" name="country" value="<?= !empty($_POST['country']) ? $country: "";?>" placeholder="Country"><span class="ml-1 mr-1"></span>
                            <input type="text" name="state" value="<?= !empty($_POST['state']) ? $state: "";?>" placeholder="State/City">
                        </div>
                        <input type="password" name="password" placeholder="Password">
                        <input type="password" name="c_password" placeholder="Comfirm Password">
                        <span style="font-size:12px; color:blue; display:block; text-align:start; margin-left:15px;">tip: password requires 8 characters minimum, numbers, lowercase letters, uppercase letters.</span>
                        <div class="signup-policy">
                            <input type="checkbox" name="agreed" value="i agree"><span> I Agree with <a href="">privacy</a> and <a href="">policy</a> </span>
                        </div>
                        <div class="signup-button">
                            <input id="signup-form-submit" type="submit" name="signup" value="Sign up">
                        </div>

                    </form>

                    <div class="signup-form-login">
                        <span>Already have an account?</span><a id="show-login" href="">Sign in</a>
                    </div>
                </div>


            </div>
        </div>
        <?php require_once('footer.php'); ?>

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