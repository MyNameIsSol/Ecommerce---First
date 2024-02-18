<?php
session_start();
require_once('function.php');
$validate = new validator();
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['continue'])) {
    $amount = $validate->validateNumber($_POST['amount']);
    $firstname = $validate->validateName($_POST['firstname']);
    $lastname = $validate->validateName($_POST['lastname']);
    $email = $validate->validateEmail($_POST['email']);
    $phone = $validate->validateNumber($_POST['phone']);
    $country = $validate->validateString($_POST['country']);
    $state = $validate->validateString($_POST['state']);
    $city = $validate->validateString($_POST['city']);
    $postcode = $validate->validateNumber($_POST['postcode']);
    $street = $validate->validateText($_POST['street']);
    if(!empty($_POST['agreed'])){ $termOfService = $validate->validateString($_POST['agreed']); }else{ $termOfService = "";}
    $date = date('d/m/y h:i:s');
    if ($firstname != false) {
        if ($lastname != false) {
            if ($email != false) {
                if ($phone != false) {
                    if ($country != false) {
                        if ($state != false) {
                            if ($city != false) {
                                if ($postcode != false) {
                                    if ($street != false) {
                                    if ($termOfService != false && $termOfService != "") {
                         $_SESSION['customer'] = array('amount' => $amount,'first_name' => $firstname,'last_name'=>$lastname,
                         'email'=> $email,'phone'=>$phone,'country' => $country,'state'=>$state,'city'=>$city,
                         'postcode'=>$postcode,'street'=>$street,'termsOfService'=>$termOfService);
                            } else {
                                $error = 'check the box to agree with our Terms of Service';
                            }
                        } else {
                            $error = 'Enter a valid street address';
                        }
                            } else {
                                $error = 'Postcode must contain numbers only';
                            }
                            } else {
                                $error = 'Please recheck the city provided';
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
}elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flutter-pay'])){
    $R_amount = $_SESSION['customer']['amount'];
    $R_firstname = $_SESSION['customer']['first_name'];
    $R_lastname = $_SESSION['customer']['last_name'];
    $R_email = $_SESSION['customer']['email'];
    $R_phone = $_SESSION['customer']['phone'];

    //prepare flutterwave request
    $request = [
        "tx_ref" => time(),
        "amount" => $R_amount,
        "currency" => "NGN",
        "PBFPubKey" => "FLWPUBK_TEST-10ebf3a7e537c8a3e025384466e5be63-X",
        "redirect_url" => "http://localhost/ify's/flutterwave-verify.php",
        "payment_options" => "card",
        "meta" => [
           "price" => $R_amount
        ],
        "customer" => [
           "email" => $R_email,
           "phonenumber" => $R_phone,
           "name" => $R_firstname." ".$R_lastname
        ],
        "customizations" => [
           "title" => "crochet_andhooks",
           "description" => "item list"
        ]
     ];


//send the array info to flutterwave endpoint
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 60,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer FLWSECK_TEST-664136bfd069685429df58e8e3b7cd94-X",
    "content-type: application/json"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if($err){
  // there was an error contacting the rave API
  die('Curl returned error: ' . $err);
}

$transaction = json_decode($response);

if(!$transaction->data && !$transaction->data->link){
  // there was an error from the API
  print_r('API returned error: ' . $transaction->message);
}

// redirect to page so User can pay
// uncomment this line to allow the user redirect to the payment page
header('Location: ' . $transaction->data->link);

}else{
    header('Location: checkout.php');
    $error = "Sorry we could not process your payment";
}

if(!empty($error)){
    $_SESSION['error'] = $error;
    header('Location: checkout.php?error=checkout/error');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>proceed with payment</title>
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
    <script src="https://js.paystack.co/v1/inline.js"></script> 
    <script type="text/javascript" src="js/index.js"></script>
    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Anton|Syne|Cabin|Lato|Fjalia+one|Montserrat|Karla|Kalam|Oxygen|Roboto&display=swap" rel="stylesheet">

</head>

<body>
    <div class="loadr">
        <img src="img/icons/preloader.gif" alt="Loading...">
    </div>

    <div class="wrapper">
    <?php require_once('header.php'); ?>

    <div class="contain-er">
    <div class="pay-wrap">
        <div class="check-top1">
                <div class="check-top2">
                <img src="img/47.jpg" alt="about" class="image-responsive">
                </div>
                <h5>Crochet<span>_andhooks</span></h5>
            </div>
         <div class="pay-div">
             <h2>Proceed With Payment</h2>
             <div class="pay-name">
             <label>payer name:</label>
             <h6><?= !empty($firstname) && !empty($lastname) ? $firstname.' '.$lastname: "";?></h6>
             </div>
             <div class="pay-email">
             <label>payer email:</label>
             <h6><?= !empty($email) ? $email: "";?></h6>
             </div>
             <div class="pay-amount">
             <p>Amount</p>
             <h3>NGN <span><?= !empty($amount) ? $amount: "";?></span></h3>
             </div>
             <div class="pay-method">
                 <h5>Choose a Payment Method</h5>
                 <div class="paystack">
                     <form id="paymentForm">    
                 <input type="hidden" value="<?= !empty($firstname) ? $firstname: "";?>" id="first-name" />
                 <input type="hidden" value="<?= !empty($lastname) ? $lastname: "";?>" id="last-name" />        
                 <input type="hidden" value="<?= !empty($email) ? $email: "";?>" id="email-address" required />
                 <input type="hidden" value="<?= !empty($phone) ? $phone: "";?>" id="phone" required />
                 <input type="hidden" value="<?= !empty($amount) ? $amount: "";?>" id="amount" required />
                 
                 <button type="submit"  onclick="payWithPaystack()"><img src="img/icons/Paystack-lo-removebg-preview.png" alt="paystack">Pay With Paystack</button>
                 </form>
                 </div>
                 <div class="flutterwave-rave">
                     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                     <input type="hidden" value="<?= !empty($firstname) ? $firstname: "";?>" name="first-name" />
                 <input type="hidden" value="<?= !empty($lastname) ? $lastname: "";?>" name="last-name" />        
                 <input type="hidden" value="<?= !empty($email) ? $email: "";?>" name="email-address" required />
                 <input type="hidden" value="<?= !empty($phone) ? $phone: "";?>" name="phone" required />
                 <input type="hidden" value="<?= !empty($amount) ? $amount: "";?>" name="amount" required />

                 <button type="submit" name="flutter-pay"><img src="img/icons/flutterwa.png" alt="frutter-wave">Pay With FlutterWave</button>
                 </form>
                 </div>
             </div>
             <div class="secured">
                 <h5><span class="fa fa-lock"> Secured by</span> paystack and rave</h5>
             </div>
             
        </div>
        <div class="web-support">
        <p>All transactions are encripted and secured.</p>
        <div class="pay-method-img">
                <img src="img/icons/Paystack-lo-removebg-preview.png" alt="paystack">
                <img src="img/icons/flutterwa.png" alt="frutter-wave">
                <img src="img/icons/master-c.jpg" alt="master-card">
                <img src="img/icons/visa.png" alt="visa-card">
                <img src="img/icons/verve.jpg" alt="verve-card">
        </div>
              <div class="site-name">
                    <p class="mr-1"> &copy; 2020 </p>
                    <h6> Crochet_andhooks.com</h6>
              </div>
        </div>
    </div>
    </div>
    </div>

    <script src='https://kit.fontawesome.com/637381c909.js' crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anton|Cabin|Lato|Fjalla+One|Montserrat|Roboto&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://js.paystack.co/v1/inline.js"></script> 
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>