<?php
session_start();
require_once('function.php');
$dbName = "crochet_andhooks";
$tbName = "customer_orders";
$tbCols = 'id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
pay_method VARCHAR(22) NOT NULL,
firstname VARCHAR(22) NOT NULL,
lastname VARCHAR(22) NOT NULL,
email VARCHAR(225) NOT NULL,
phone VARCHAR(22) NOT NULL,
order_id VARCHAR(22) NOT NULL,
product_code VARCHAR(22) NOT NULL,
product_name VARCHAR(22) NOT NULL,
product_price VARCHAR(22) NOT NULL,
product_qnty VARCHAR(22) NOT NULL,
total_price VARCHAR(22) NOT NULL,
total_amount VARCHAR(22) NOT NULL,
country VARCHAR(22) NOT NULL,
state VARCHAR(22) NOT NULL,
city VARCHAR(22) NOT NULL,
postcode VARCHAR(22) NOT NULL,
street VARCHAR(225) NOT NULL,
terms_of_service VARCHAR(22) NOT NULL,
order_date DATETIME NOT NULL,
trxRef VARCHAR(22) NOT NULL';
$db = new CreateDb($dbName, $tbName, $tbCols);
$validate = new validator();

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['status']) && isset($_GET['tx_ref'])){
    if($_GET['status'] == 'successful'){
    $trxRef = $validate->validateEncrypVal($_GET['tx_ref']);
    $txid = $validate->validateEncrypVal($_GET['transaction_id']);
    $amount = $_SESSION['customer']['amount'];
 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/".$txid."/verify",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer FLWSECK_TEST-664136bfd069685429df58e8e3b7cd94-X",
    "content-type: application/json"  
  ),
));

$response = curl_exec($curl);
curl_close($curl);

        $resp = json_decode($response, true);
        $paymentStatus = $resp['data']['status'];
        $chargeAmount = $resp['data']['charged_amount'];

        if (($chargeAmount == $amount) && ($paymentStatus == 'successful')) {
            if(!empty($_SESSION['cart'])){
              $encrpt = md5($trxRef.time());
              $orderid = substr($encrpt,0,3).substr($encrpt,-3,3);

                foreach($_SESSION['cart'] as $key => $value){
                  $pay_method = "flutterwave";
                  $amount = $_SESSION['customer']['amount'];
                  $firstname = $_SESSION['customer']['first_name'];
                  $lastname = $_SESSION['customer']['last_name'];
                  $email = $_SESSION['customer']['email'];
                  $phone = $_SESSION['customer']['phone'];
                  $country = $_SESSION['customer']['country'];
                  $state = $_SESSION['customer']['state'];
                  $city = $_SESSION['customer']['city'];
                  $postcode = $_SESSION['customer']['postcode'];
                  $street = $_SESSION['customer']['street'];
                  $termsOfservice = $_SESSION['customer']['termsOfService'];
                  $orderDate = date('d/m/y h:i:s');;
        
                $p_code =  $value["product_code"];
                $p_name =  $value["product_name"];
                $p_price =  $value["product_price"];
                $p_qnty =  $value["product_qty"];
                $t_price =  $value['product_qty'] * $value['product_price']; 
            $tbcols = "pay_method,firstname,lastname,email,phone,order_id,product_code,product_name,product_price,product_qnty,total_price,total_amount,country,state,city,postcode,street,terms_of_service,order_date,trxRef";
            $values = "'$pay_method','$firstname','$lastname','$email','$phone','$orderid','$p_code','$p_name','$p_price','$p_qnty','$t_price','$amount','$country','$state','$city','$postcode','$street','$termsOfservice','$orderDate','$trxRef'";
            $insert = $db->inSert($tbName, $tbcols, $values);
                 }
              }

            if ($insert) {
                unset($_SESSION['customer']);
                unset($_SESSION['cart']);
                echo '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link href="css/sweetalert.min.css" type="text/css" rel="stylesheet">
                <link href="css/sweetalert.css" type="text/css" rel="stylesheet">
                <script type="text/javascript" src="js/sweetalert.min.js"></script>
                <script type="text/javascript" src="js/sweetalert.js"></script>
                </head>
                <body>
                <script type="text/javascript">
                swal({
                    title: "Payment Completed",
                    text: "Your order has been received!.",
                    icon: "success",
                    button: "Ok!",
                  }).then(function(){
                      window.location.href="index.php";
                  });
                  </script>
                  </body>
                  </html>';
          }      
        }else{
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/sweetalert.min.css" type="text/css" rel="stylesheet">
            <link href="css/sweetalert.css" type="text/css" rel="stylesheet">
            <script type="text/javascript" src="js/sweetalert.min.js"></script>
            <script type="text/javascript" src="js/sweetalert.js"></script>
            </head>
            <body>
            <script type="text/javascript">
            swal({
                title: "Oops",
                text: "Something went wrong, please contact our help center!",
                icon: "error",
                button: "Ok",
              }).then(function(){
                  window.location.href="contact.php";
              });
              </script>
              </body>
              </html>';
        }
    }else{
        echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="css/sweetalert.min.css" type="text/css" rel="stylesheet">
            <link href="css/sweetalert.css" type="text/css" rel="stylesheet">
            <script type="text/javascript" src="js/sweetalert.min.js"></script>
            <script type="text/javascript" src="js/sweetalert.js"></script>
            </head>
            <body>
            <script type="text/javascript">
            swal({
                title: "Oops, Something went wrong",
                text: "Could not verify payment, Please try again!",
                icon: "error",
                button: "Ok",
              }).then(function(){
                  window.location.href="checkout.php";
              });
              </script>
              </body>
              </html>';
    }
}else{
    header("Location : pay.php");
}
?>

