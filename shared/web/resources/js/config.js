var identitydataval, createAddressval, createWalletval, storageallocateval, directoryAllocationval, emailiddataval ;

var identityVal,identityPath, addressVal, walletVal, storageVal, directoryVal, emailiddataVal;

var identityText,addressText, storageText;

jQuery(function() {
  var identitydata = jQuery("#identity_token").val();
  var identitypath = jQuery("#identity_path").val();
  var createAddress = jQuery("#host_address").val();
  var createWallet = jQuery("#wallet_address").val();
  var storageallocate = Number(jQuery("#storage_allocate").val());
  var emailiddata = jQuery("#email_address").val();
  var directoryAllocation = jQuery("#storage_directory").val();
  var regex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  var createVal = 0;


  if(identitypath === "") {
    jQuery(".identity_path_msg").show();
    jQuery("#editidentitybtn").hide();
    jQuery("#identitybtn").show();
    identitydataval = 0;
    identityText = "";
  }else{
    jQuery(".identity_path_msg").hide();
    jQuery("#identitybtn").hide();
    jQuery("#identity .close").trigger("click");
    jQuery("#editidentitybtn").show();
    var identityfile = $("#identityfile").text();
    var fileexists = $("#file_exists").text();
    if(identityfile ==="false"){
      if(fileexists ==="1"){
        jQuery.ajax({
          type: "POST",
          url: "identity.php",
          data: {file_exist : "file_exist"},
          success: function (result) {
            if(result==="1"){
              $("#identity_status").html("<b>The identity files don't exist at the path selected. Please create identity or copy the identity folder at the given path.</b>");
            }else{
              $("#identity_status").html("<b>Identity files exist.</b>");
            }
          },
          error: function () {
            console.log("In tehre wrong on create Identitfy");
          }
        });

      }else{
        $("#identity_status").html("<b>Identity files exist.</b>");
      }
    }else{
      readidentitystatus();

      $("#create_identity").attr("disabled",true);
      $("#create_identity").css("cursor","not-allowed")
      $("#stop_identity").removeAttr("disabled");
      $("#stop_identity").css("cursor","pointer");
    }

    identitydataval = 1;
    identityText = "<span class='identity_text'>Identity Generated: </span>";
  }

  jQuery("#idetityval").html("");
  identityVal = identitydata;
  identityPath = identitypath;
  jQuery("#idetityval").html(identityText+identitydata);
  showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);

function Address(){
  if( createAddress === "" ){
    jQuery(".host_token_msg").show();
    jQuery("#editexternalAddressbtn").hide();
    createAddressval = 0;
    addressText = "";
  } else {
    jQuery(".host_token_msg").hide();
    jQuery("#externalAddressbtn").hide();
    jQuery("#externalAddress .close").trigger("click");
    jQuery("#editexternalAddressbtn").show();
    createAddressval = 1;
    addressText = "<span class='address_text'></span>";
  }


  jQuery("#externalAddressval").html("");
  addressVal = createAddress;
  jQuery("#externalAddressval").html(addressText+createAddress);
  showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
}


function Wallet(){
    if(createWallet === "") {
      jQuery(".wallet_token_msg").show();
      jQuery("#editwallettbtn").hide();
      createWalletval = 0;
    } else {
      jQuery(".wallet_token_msg").hide();
      jQuery("#addwallettbtn").hide();
      jQuery("#walletAddress .close").trigger("click");
      jQuery("#editwallettbtn").show();
      createWalletval = 1;
    }


    jQuery("#wallettbtnval").html("");
    walletVal = createWallet;
    jQuery("#wallettbtnval").html(createWallet);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
}


function Storage(){
  if(jQuery.isNumeric(storageallocate) && Number.isInteger(storageallocate) &&  storageallocate >= 500){
    jQuery(".storage_token_msg").hide();
    jQuery("#addstoragebtn").hide();
    jQuery("#storageAllocation .close").trigger('click');
    jQuery("#editstoragebtn").show();
    storageallocateval = 1;
    storageText = "GB";
  } else if(storageallocate === "") {
    jQuery(".storage_token_msg").show();
    jQuery("#editstoragebtn").hide();
    storageallocateval = 0;
    storageText = "";
  } else  {
    storageallocate = "";
    jQuery(".storage_token_msg").show();
    jQuery("#editstoragebtn").hide();
    storageallocateval = 0;
    storageText = "";
  }

  jQuery("#storagebtnval").html("");
  storageVal = storageallocate;
  jQuery("#storagebtnval").html(storageallocate+storageText);
  showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);

}


function Email(){
  if(regex.test(emailiddata)) {
    jQuery(".email_token_msg").hide();
    jQuery("#emailAddressbtn").hide();
    jQuery("#emailAddress .close").trigger("click");
    jQuery("#editemailAddressbtn").show();
    emailiddataval = 1;
  } else {
    jQuery(".email_token_msg").show();
    jQuery("#editemailAddressbtn").hide();
    emailiddataval = 0;
  }
  jQuery("#emailAddressval").html("");
  emailiddataVal = emailiddata;
  jQuery("#emailAddressval").html(emailiddata);
  showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);

}

function Directory(){
  if(directoryAllocation === "") {
    jQuery(".directory_token_msg").show();
    jQuery("#editdirectorybtn").hide();
    directoryAllocationval = 0;
  } else {
    jQuery(".directory_token_msg").hide();
    jQuery("#adddirectorybtn").hide();
    jQuery("#directory .close").trigger('click');
    jQuery("#editdirectorybtn").show();
    directoryAllocationval = 1;
  }


  jQuery("#directorybtnval").html("");
  directoryVal = directoryAllocation;
  jQuery("#directorybtnval").html(directoryAllocation);
  showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);

}

Address();
Wallet();
Storage();
Email();
Directory();


  if(identitydata === "" &&  createAddress === "" && createWallet === "" &&  storageallocate === ""  && directoryAllocation === "" && emailiddata === ""){
    jQuery("#startbtn").attr("disabled", true);
    jQuery("#startbtn").css("cursor", "not-allowed");
  }else {
    identityfile = $("#identityfile").text();
      if(identityfile ==="false"){
        jQuery("#startbtn").removeAttr("disabled", true);
        jQuery("#startbtn").css("cursor", "pointer");
      }
  }

  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: { isstartajax : 1},
    success: function (resposnse) {
      if(resposnse) {
        // log message
        $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
      }
    },
    error: function (resposnse) {
      console.log("In There check runing or not");
      // log message
      $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
    }
  });

  jQuery("#create_identity").click(function(){
    identitydata = jQuery("#identity_token").val();
    identitypath = jQuery("#identity_path").val();
    var identityfile = $("#identityfile").text();

    if(identitydata === "") {
      jQuery(".identity_path_msg").show();
      jQuery("#editidentitybtn").hide();
      jQuery("#identitybtn").show();
      identitydataval = 0;
      identityText = "";
    } else {
      if(identitypath === "") {
        jQuery(".identity_path_msg").show();
        jQuery("#editidentitybtn").hide();
        jQuery("#identitybtn").show();
        identitydataval = 0;
        identityText = "";
      }else{
        jQuery(".identity_path_msg").hide();
        jQuery("#identitybtn").hide();
        jQuery("#identity .close").trigger("click");
        jQuery("#editidentitybtn").show();
        var fileexists = $("#file_exists").text();

        if(identityfile ==="false"){
          if(fileexists ==="1"){

            jQuery.ajax({
              type: "POST",
              url: "identity.php",
              data: {file_exist : "file_exist"},
              success: function (result) {
                if(result==="1"){
                  createidentifyToken(identitydata,identitypath);
                  readidentitystatus();

                  $("#create_identity").attr("disabled",true);
                  $("#create_identity").css("cursor","not-allowed")
                  $("#stop_identity").removeAttr("disabled");
                  $("#stop_identity").css("cursor","pointer");

                }else if(result==="0"){
                  $("#identity_status").html("<b>Identity files exist.</b>");
                }else{
                  $("#identity_status").html("<p>"+result+"</p>");
                }
              },
              error: function () {
                console.log("In tehre wrong on create Identitfy");
              }
            });

          }else{
            $("#identity_status").html("<b>Identity files exist.</b>");
          }
        }else{
          readidentitystatus();

          $("#create_identity").attr("disabled").css("cursor","not-allowed");
          $("#stop_identity").removeAttr("disabled").css("cursor","pointer");
        }

        identitydataval = 1;
        identityText = "<span class='identity_text'>Identity Generated: </span>";
      }
    }


    jQuery("#idetityval").html("");
    identityVal = identitydata;
    identityPath = identitypath;
    jQuery("#idetityval").html(identityText+identitydata);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  });


  jQuery('#create_address').click(function(){
    createAddress = jQuery("#host_address").val();
    Address();
  });

  jQuery("#create_address").click();

  jQuery('#create_wallet').click(function(){
    createWallet = jQuery("#wallet_address").val();
    Wallet();
  });

  jQuery('#allocate_storage').click(function(){
    storageallocate = Number(jQuery("#storage_allocate").val());
    Storage();
  })


  jQuery('#create_emailaddress').click(function(){
    emailiddata = jQuery("#email_address").val();
    Email();

  });

  jQuery('#create_directory').click(function(){
    directoryAllocation = jQuery("#storage_directory").val();
    Directory();
  });

  jQuery("#editidentitybtn button").click(function(){
    jQuery('#storjrows').hide();
  })
});


function showstartbutton(createidentitydataval,createAddressvaldata,createWalletvaldata,storageallocatevaldata,emailAddressvaldata,directoryAllocationvaldata,){
  if(createidentitydataval ===1 && createAddressvaldata === 1 && createWalletvaldata === 1 && storageallocatevaldata === 1  && directoryAllocationvaldata === 1) {
    var identityfile = $("#identityfile").text();
    if(identityfile ==="false"){
      jQuery("#startbtn").removeAttr("disabled", true);
      jQuery("#startbtn").css("cursor", "pointer");
    }
  } else{
    jQuery("#startbtn").attr("disabled", true);
    jQuery("#startbtn").css("cursor", "not-allowed");
  }
}

jQuery("#startbtn").click(function() {
  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: {identity : identityPath, authKey:identityVal, address : addressVal, wallet : walletVal, storage : storageVal, email_val : emailiddataVal, directory: directoryVal, isajax : 1},
    success: function (result) {
      window.location.reload();
      // // log message
      $('iframe').contents().find('body').html('<p>'+result+'</p>');
    },
    error: function (result) {
      // log message
      $('iframe').contents().find('body').html('<p>'+result+'</p>');
      console.log("In there wrong");
    }
  });
});

jQuery("#stopbtn").click(function() {
  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: {isstopAjax : 1},
    success: function () {
      window.location.reload();
    },
    error: function () {
      console.log("In There wrong on Stop Button");
    }
  });
});

jQuery("#updatebtn").click(function() {
  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: {identity : identityPath, authKey:identityVal, address : addressVal, wallet : walletVal, storage : storageVal, email_val : emailiddataVal, directory: directoryVal, isUpdateAjax : 1},
      success: function () {
        window.location.reload();
      },
      error: function () {
        console.log("Something wrong with stop button");
      }
    });
});


if(jQuery("#identity_token").val() ===null || jQuery("#host_address").val() ==="" || jQuery("#host_address").val() ===null || jQuery("#wallet_address").val() ==="" || jQuery("#wallet_address").val() ===null || Number(jQuery("#storage_allocate").val()) ==="" || Number(jQuery("#storage_allocate").val()) ===null  || jQuery("#email_address").val() ===null || jQuery("#storage_directory").val() ==="" || jQuery("#storage_directory").val() ===null){
  console.log("All parameters is null");
}else{
  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: { isrun : 1},
    success: function (resposnse) {
      if(resposnse) {
        // log message
        if(resposnse ==="1"){
          $(".editbtn").attr("disabled",true).css("cursor","not-allowed");
          $("#startbtn").attr("disabled",true).css("cursor","not-allowed");
          $("#stopbtn").attr("disabled",false).css("cursor","pointer");
        }else if(resposnse ==="0"){
          $(".editbtn").attr("disabled",false).css("cursor","pointer");
          $("#stopbtn").attr("disabled",true).css("cursor","not-allowed");
          $("#startbtn").attr("disabled",false).css("cursor","pointer");
        }

      }
    },
    error: function (resposnse) {
      console.log("error");
      // log message
      $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
    }
  });
}


// Create identity.
function createidentifyToken(createidval,identitypath){

  jQuery.ajax({
    type: "POST",
    url: "identity.php",
    data: {
      createidval : createidval,
      identitypath : identitypath,
      identityString: createidval 
    },
    success: function (result) {
      $("#identity_status").html("<b>Identity creation process is starting.</b><br><p>"+result+"</p>");
    },
    error: function () {
      console.log("Error during create Identitfy operation");
    }
  });

}


// Read status from identity.php file.
function readidentitystatus(){
  jQuery.ajax({
    type: "POST",
    url: "identity.php",
    data: {status : "status",},
    success: function (result) {
      if(result === "identity available at /root/.local/share/storj/identity"){
        $("#identity_status").html("<b>"+result+"</b>");
        identitydataval = 1;
      }else{
        $("#identity_status").html("<b>Identity creation process is running.</b><br><p>"+result+"</p>");
      }
    },
    error: function () {
      console.log("In tehre wrong on create Identitfy");
    }
  });

  setInterval(function(){
    readidentitystatus();
  },60000);
  
}



jQuery("#stop_identity").click(function() {
  jQuery.ajax({
    type: "POST",
    url: "identity.php",
    data: {isstopAjax : 1},
    success: function () {
      window.location.reload();
    },
    error: function () {
      console.log("In There wrong on Stop Button");
    }
  });
});

$("#identity_path").change(function(){
  identityPath = $(this).val();
  if(identityPath === "" || identityPath ===null){
  identitydataval = 0;
    jQuery(".identity_path_msg").show();
    jQuery("#editidentitybtn").hide();
    jQuery("#identitybtn").show();
  }else{
    jQuery(".identity_path_msg").hide();
    jQuery("#editidentitybtn").hide();
    jQuery("#identitybtn").show();
    identitydataval = 1;
  }
  showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
});