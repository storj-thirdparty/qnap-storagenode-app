var createAddressval, createServerval, apival, satelliteval, encryptionPassphraseval;

var address_val, server_val, api_val, satelliteval_val, encryptionPassphrase_val;

var address_text;


  var createAddress = jQuery("#host_address").val();
  var createServer = jQuery("#wallet_address").val();
  var Api = jQuery("#storage_allocate").val();
  var Satellite= jQuery("#email_address").val();
  var Encryption_Passphrase = jQuery("#storage_directory").val();

  if( createAddress !== '' ){
      jQuery(".host_token_msg").hide();
      jQuery("#externalAddressbtn").hide();
      jQuery("#externalAddress .close").trigger("click");
      jQuery("#editexternalAddressbtn").show();
      createAddressval = 1;
      // address_text = "<span class='address_text'>domain.ddns.net: </span>";
      address_text = "<span class='address_text'></span>";
    } else {
      jQuery(".host_token_msg").show();
      jQuery("#editexternalAddressbtn").hide();
      createAddressval = 0;
      address_text = '';
    }
    jQuery("#externalAddressval").html('');
    address_val = createAddress;
    jQuery("#externalAddressval").html(address_text+createAddress);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);



 if(createServer !== '') {
      jQuery(".wallet_token_msg").hide();
      jQuery("#addwallettbtn").hide();
      jQuery("#walletAddress .close").trigger("click");
      jQuery("#editwallettbtn").show();
      createServerval = 1;
    } else {
      jQuery(".wallet_token_msg").show();
      jQuery("#editwallettbtn").hide();
      createServerval = 0;
    }
    jQuery("#wallettbtnval").html('');
    server_val = createServer;
    jQuery("#wallettbtnval").html(createServer);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);


    if(Api !== '') {
      jQuery(".storage_token_msg").hide();
      jQuery("#addstoragebtn").hide();
      jQuery("#storageAllocation .close").trigger('click');
      jQuery("#editstoragebtn").show();
      apival = 1;

    }  else  {
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      apival = 0;
    }
    jQuery("#storagebtnval").html('');
    api_val = Api;
    jQuery("#storagebtnval").html(Api);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);


     if(Satellite !== '') {
      jQuery(".email_token_msg").hide();
      jQuery("#emailAddressbtn").hide();
      jQuery("#emailAddress .close").trigger("click");
      jQuery("#editemailAddressbtn").show();
      satelliteval = 1;
    } else {
      jQuery(".email_token_msg").show();
      jQuery("#editemailAddressbtn").hide();
      satelliteval = 0;
    }
    jQuery("#emailAddressval").html('');
    satelliteval_val = Satellite;
    jQuery("#emailAddressval").html(Satellite);
   showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);



    if(Encryption_Passphrase !== '') {
      jQuery(".directory_token_msg").hide();
      jQuery("#adddirectorybtn").hide();
      jQuery("#directory .close").trigger('click');
      jQuery("#editdirectorybtn").show();
      encryptionPassphraseval = 1;
    } else {
      jQuery(".directory_token_msg").show();
      jQuery("#editdirectorybtn").hide();
      encryptionPassphraseval = 0;
    }
    jQuery("#directorybtnval").html('');
    encryptionPassphrase_val = Encryption_Passphrase;
    jQuery("#directorybtnval").html(Encryption_Passphrase);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);



    jQuery('#create_address').click(function(){
      createAddress = jQuery("#host_address").val();
       if( createAddress !== '' ){
        jQuery(".host_token_msg").hide();
        jQuery("#externalAddressbtn").hide();
        jQuery("#externalAddress .close").trigger("click");
        jQuery("#editexternalAddressbtn").show();
        createAddressval = 1;
        // address_text = "<span class='address_text'>domain.ddns.net: </span>";
        address_text = "<span class='address_text'></span>";
      } else {
        jQuery(".host_token_msg").show();
        jQuery("#editexternalAddressbtn").hide();
        createAddressval = 0;
        address_text = '';
      }
      jQuery("#externalAddressval").html('');
      address_val = createAddress;
      jQuery("#externalAddressval").html(address_text+createAddress);
      showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);

    });

    jQuery('#create_wallet').click(function(){
      createServer = jQuery("#wallet_address").val();
      if(createServer !== '') {
        jQuery(".wallet_token_msg").hide();
        jQuery("#addwallettbtn").hide();
        jQuery("#walletAddress .close").trigger("click");
        jQuery("#editwallettbtn").show();
        createServerval = 1;
      } else {
        jQuery(".wallet_token_msg").show();
        jQuery("#editwallettbtn").hide();
        createServerval = 0;
      }
      jQuery("#wallettbtnval").html('');
      server_val = createServer;
      jQuery("#wallettbtnval").html(createServer);
      showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);
  });

  jQuery('#allocate_storage').click(function(){
    Api = jQuery("#storage_allocate").val();
    if(Api !== '') {
      jQuery(".storage_token_msg").hide();
      jQuery("#addstoragebtn").hide();
      jQuery("#storageAllocation .close").trigger('click');
      jQuery("#editstoragebtn").show();
      apival = 1;

    }  else  {
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      apival = 0;
    }
    jQuery("#storagebtnval").html('');
    api_val = Api;
    jQuery("#storagebtnval").html(Api);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);
  });


  jQuery('#create_emailaddress').click(function(){
    Satellite = jQuery("#email_address").val();
    if(Satellite !== '') {
      jQuery(".email_token_msg").hide();
      jQuery("#emailAddressbtn").hide();
      jQuery("#emailAddress .close").trigger("click");
      jQuery("#editemailAddressbtn").show();
      satelliteval = 1;
    } else {
      jQuery(".email_token_msg").show();
      jQuery("#editemailAddressbtn").hide();
      satelliteval = 0;
    }
    jQuery("#emailAddressval").html('');
    satelliteval_val = Satellite;
    jQuery("#emailAddressval").html(Satellite);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);
    
  });


  jQuery('#create_directory').click(function(){
    Encryption_Passphrase = jQuery("#storage_directory").val();
    if(Encryption_Passphrase !== '') {
      jQuery(".directory_token_msg").hide();
      jQuery("#adddirectorybtn").hide();
      jQuery("#directory .close").trigger('click');
      jQuery("#editdirectorybtn").show();
      encryptionPassphraseval = 1;
    } else {
      jQuery(".directory_token_msg").show();
      jQuery("#editdirectorybtn").hide();
      encryptionPassphraseval = 0;
    }
    jQuery("#directorybtnval").html('');
    encryptionPassphrase_val = Encryption_Passphrase;
    jQuery("#directorybtnval").html(Encryption_Passphrase);
    showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval);
  });


 function showstartbutton(createAddressval,createServerval,apival,satelliteval,encryptionPassphraseval){
  if(createAddressval === 1 && createServerval === 1 && apival === 1 && satelliteval === 1  && encryptionPassphraseval == 1 ) {

    jQuery("#startbtn").removeAttr("disabled", true);
    jQuery("#startbtn").css("cursor", "pointer");

    jQuery("#stopbtn").removeAttr("disabled", true);
    jQuery("#stopbtn").css("cursor", "pointer");
  } else{
    jQuery("#startbtn").attr("disabled", true);
    // make button cursor not-allowed
    jQuery("#startbtn").css("cursor", "not-allowed");

    jQuery("#stopbtn").attr("disabled", true);
    // make button cursor not-allowed
    jQuery("#stopbtn").css("cursor", "not-allowed");
  }
}

jQuery("#startbtn").click(function(e) {

    jQuery.ajax({
      type: "POST",
      url: "gateway.php",
      data: {address : address_val, server : server_val, api : api_val, satellite : satelliteval_val, encryptionPassphrase: encryptionPassphrase_val, isajax : 1},
      success: function (result) {
        //console.log("I am here");
        window.location.reload();

        // // log message
         $('iframe').contents().find('body').html('<p>'+result+'</p>');

      },
      error: function () {
        // log message
         $('iframe').contents().find('body').html('<p>'+result+'</p>');
        console.log("In there wrong");
      }
    });
});

jQuery("#stopbtn").click(function(e) {

    jQuery.ajax({
      type: "POST",
      url: "gateway.php",
      data: {address : address_val, server : server_val, api : api_val, satellite : satelliteval_val, encryptionPassphrase: encryptionPassphrase_val, isConfig : 1},
      success: function (result) {
        //console.log("I am here");
        window.location.reload();

        // // log message
         $('iframe').contents().find('body').html('<p>'+result+'</p>');

      },
      error: function () {
        // log message
         $('iframe').contents().find('body').html('<p>'+result+'</p>');
        console.log("In there wrong");
      }
    });
});

jQuery("#updatebtn").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: "gateway.php",
      data: {address : address_val, server : server_val, api : api_val, satellite : satelliteval_val, encryptionPassphrase: encryptionPassphrase_val, isUpdateAjax : 1},
      success: function (result) {
        //console.log("I am here");
        window.location.reload();
      },
      error: function () {
        console.log("Something wrong with stop button");
      }
    });
});



jQuery.ajax({
    type: "POST",
    url: "gateway.php",
    data: { isrun : 1},
    success: function (resposnse) {
      if(resposnse) {
        // log message
        if(resposnse ==0){
          $(".editbtn").attr("disabled",true).css("cursor","not-allowed");

          $("#startbtn").attr("disabled",true).css("cursor","not-allowed");
          // $("#startbtn").removeClass("start-button");

          // $("#stopbtn").attr("disabled",false).css("cursor","pointer");
          // $("#stopbtn").addClass("stop-button");
        }else if(resposnse ==1){
          $(".editbtn").attr("disabled",false).css("cursor","pointer");

          // $("#stopbtn").attr("disabled",true).css("cursor","not-allowed");
          // $("#stopbtn").removeClass("stop-button");

          // $("#stopbtn").attr("disabled",false).css("cursor","pointer");

          $("#startbtn").attr("disabled",false).css("cursor","pointer");
          // $("#startbtn").addClass("start-button");
        }
      }
    },
    error: function () {
      console.log("error");
      // log message
      $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
    }
  });
