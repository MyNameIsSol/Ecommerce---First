$(document).ready(function() {
  
  const loader = $('body').find('.loadr');
  $(window).on('load', function () {
    loader.parent().find(loader).fadeOut(1000);
    $('.wrapper').fadeIn(300);
    var lo = location.pathname.split('/').pop();
    console.log(lo);
    $('ul.nav-center a[href="' + lo + '"]').parent().addClass('active').siblings().removeClass('active');
  });

  $("#owl-demo").owlCarousel({
    items: 1,
    loop: true,
    nav: false,
    dots: true,
    autoplay: true,
    autoplaySpeed: 1000,
    smartSpeed: 1500,
    autoplayHoverPause: true
  });

  
  var sidebar = $('div.add-product-maindiv').find('.side-bar');
  $('div.add-product-maindiv').find('.sidebar-icon').click(function(){
  sidebar.toggleClass('togglSide');
});

    var cartBarg = $('.cart-barg');
    var navMenu =  $('.nav-menu');
    var navCancel = $('.nav-cancel');
    navMenu.click(function (e) {
    e.preventDefault();
    $('.nav-center,.nav-end').slideToggle();
    $(this).toggle(500);
    navCancel.toggleClass('show-cancel');
    cartBarg.addClass('hide-cart-barg');
  });
   
  var targetWidth = 786;
if($(window).width() < targetWidth){
  $('.contain-er').click(function(e){
      $('.nav-center,.nav-end').slideUp();
      navCancel.removeClass('show-cancel');
      navMenu.show(500);
      sidebar.removeClass('togglSide');
      cartBarg.removeClass('hide-cart-barg');
  });
}else{
  $('.contain-er').click(function(e){
    sidebar.removeClass('togglSide');
});
}
  
    navCancel.click(function (e) {
    e.preventDefault();
    $('.nav-center,.nav-end').slideToggle();
    $(this).toggleClass('show-cancel');
    navMenu.toggle(500);
    cartBarg.removeClass('hide-cart-barg');
  });

  $('nav.navi').addClass('mynav');
  $(window).on('scroll', function () {
    if ($(window).scrollTop()) {
      $('nav.navi').removeClass('mynav').addClass('myna');
    } else {
      $('nav.navi').addClass('mynav').removeClass('myna');
    };
  });

  $('.about-btn,.angle-down').click(function(e){
    e.preventDefault();
    $('html,body').animate({
      scrollTop: $('.about-div').offset().top
    },1000);
  })

  $('.more').find('.more-info,.goal,.customer').mouseenter(function(){
    $(this).addClass('color',3000);
  }).mouseleave(function(){
    $(this).stop().removeClass('color',3000);
  })

// $('.wrapper').find('.message')
var myalert = $('.myalert').find('#cancel');
myalert.parent().delay(5000).fadeOut(1000);
myalert.click(function(e){
  e.preventDefault();
  $(this).parent('.myalert').hide();
})

var loginDiv = $('.wrapper').find('.login-maindiv');
$('.signup-form').find('a#show-login').click(function(e){
  e.preventDefault();
  loginDiv.toggleClass('slide');
})

var loginDiv = $('.wrapper').find('.login-maindiv');
$('.login-top').find('a#show-log').click(function(e){
  e.preventDefault();
  loginDiv.toggleClass('slide');
})

$('.login-div').find('a#hide-login').click(function(e){
  e.preventDefault();
  loginDiv.toggleClass('slide');
})



$('a.sub-btn').click(function(e){
  e.preventDefault();
  var email = $(this).closest('form').find('input').val(); 
  $.ajax({
    url:"contact.php",
    type:"post",
    data: {email : email,sub : 'subscribe'},
    success: function(res){
      var mes = $(res).filter('div.alert').text();
      console.log(mes);
      $('div.myalert').html(mes);
      // $('div.myalert').html('<p>You have successfully subscribed!</p>');
      $('div.myalert').fadeIn().delay(5000).fadeOut(300);
    }
  })
})


// var mainItem = $('div.add-product-maindiv').find('.add-items');
// $('div.add-product-maindiv').find('.sidebar-icon').click(function(){
//   mainItem.toggleClass('togglMain');
// })

$('div#newItem').fadeIn(0);
var sidebarlink = $('div.sidebar-links li');
sidebarlink.click(function(e){
    var link = $(this).data('href');
    $('div.add-items').find('div'+link).fadeIn(0).siblings().fadeOut(0);
    $('html,body').animate({
      scrollTop: $('div.add-items').find('div'+link).offset(300).top
    },1000);   
})

var navlink = $('.navi').find('.nav-end li a');
navlink.click(function(e){
  if($(this).attr('href') == "logout.php"){
    e.preventDefault();
    comfirm.show("Are you sure you want to perform this action...",
    "logout anyways? click Ok to continue.",logOut);
  }
});

var alertBtn = $('#alertCont').find('.alert-btn a');
alertBtn.click(function(e){
  if($(this).data('href') == "cancel"){
    comfirm.close();
  }else{
    if($(this).data('href')=="logout"){
      comfirm.okay();
    }
  }
});

function logOut(){
  window.location = 'logout.php';
}

var comfirm = new function(){
  this.show = function(headermsg,bodymsg,callback){
    var alertDiv =$('body').find('#alertCont');
    var alertheader = alertDiv.find('.alert-header');
    var alertBody = alertDiv.find('#alert-body');
    alertDiv.css('top','15%');
    alertDiv.css('opacity','1');
    alertheader.text(headermsg);
    alertBody.text(bodymsg);
    this.callback = callback;
    $('#freezeBackground').css('display','block');
  };

  this.okay = function(){
    this.callback();
    this.close();
  };

  this.close = function(){
    var alertDiv =$('body').find('div#alertCont');
    alertDiv.css('top','-15%');
    alertDiv.css('opacity','0'); 
    $('#freezeBackground').css('display','none');
  }
}

var paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener('submit', payWithPaystack, false);
function payWithPaystack(e) {
  e.preventDefault();
  var first_name = document.getElementById('first-name').value;
  var last_name = document.getElementById('last-name').value;
  var phone_number = document.getElementById('phone').value;
  const api = "pk_test_022df8dd7010cf868962cb14ee3f268d249fa29f";
  var handler = PaystackPop.setup({
    key: api, // Replace with your public key
    email: document.getElementById('email-address').value,
    amount: document.getElementById('amount').value * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
    currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
    ref: 'MARCHANT' + Math.floor((Math.random() * 1000000000) + 1), // Replace with a reference you generated
    firstname:first_name,
    lastname:last_name,
    metadata: {
      custom_fields:[
        {
          display_name:first_name,
          variable_name:last_name,
          value:phone_number
        }
      ]
    },
    callback: function(response) {
      //this happens after the payment is completed successfully
      var reference = response.reference;
      window.location.href="paystack-verify.php?reference=" + reference;     
      // Make an AJAX call to your server with the reference to verify the transaction
    },
    onClose: function() {
      window.location.href="checkout.php";
    },
  });
  handler.openIframe();
}


});