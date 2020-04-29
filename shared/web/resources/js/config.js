var identitydataval, createAddressval, createWalletval, storageallocateval, directoryAllocationval, emailiddataval ;
var identity_val,identity_path, address_val, wallet_val, storage_val, bandwidth_val, directory_val, emailiddata_val;
var identity_text,address_text, storage_text, bandwidth_text;
jQuery(function() {
  var identitydata = jQuery("#identity_token").val();
  var identitypath = jQuery("#identity_path").val();
  var createAddress = jQuery("#host_address").val();
  var createWallet = jQuery("#wallet_address").val();
  storageallocate = parseInt(jQuery("#storage_allocate").val());
  bandwidthAllocation = parseInt(jQuery("#bandwidth_allocation").val());
  var emailiddata = jQuery("#email_address").val();
  var directoryAllocation = jQuery("#storage_directory").val();

  // Get values
  // if(identitydata !== '') {
       if(identitypath !== '') {
         jQuery(".identity_path_msg").hide();
          jQuery("#identitybtn").hide();
          jQuery("#identity .close").trigger("click");
          jQuery("#editidentitybtn").show();
          var identityfile = $("#identityfile").text();
          var file_exists = $("#file_exists").text();
              if(identityfile =="false"){

                if(file_exists !=="0"){

                    jQuery.ajax({
                    type: "POST",
                    url: "identity.php",
                    data: {file_exist : "file_exist"},
                    success: function (result) {
                      if(result==1){
                        // createidentifyToken(identitydata,identitypath);
                        // readidentitystatus();
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
              }

              identitydataval = 1;
              identity_text = "<span class='identity_text'>Identity Generated: </span>";
        }else{
            jQuery(".identity_path_msg").show();
            jQuery("#editidentitybtn").hide();
            jQuery("#identitybtn").show();
            identitydataval = 0;
            identity_text = '';
        }
    // } else {
    //   jQuery(".identity_path_msg").show();
    //   jQuery("#editidentitybtn").hide();
    //   identitydataval = 0;
    //   identity_text = '';
    // }
    jQuery("#idetityval").html('');
    identity_val = identitydata;
    identity_path = identitypath;
    jQuery("#idetityval").html(identity_text+identitydata);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);


  // Fix -> Later on detailed check may be added
  if( createAddress !== '' ){
      jQuery(".host_token_msg").hide();
      jQuery("#externalAddressbtn").hide();
      jQuery("#externalAddress .close").trigger("click");
      jQuery("#editexternalAddressbtn").show();
      createAddressval = 1;
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
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);


 if(createWallet !== '') {
      jQuery(".wallet_token_msg").hide();
      jQuery("#addwallettbtn").hide();
      jQuery("#walletAddress .close").trigger("click");
      jQuery("#editwallettbtn").show();
      createWalletval = 1;
    } else {
      jQuery(".wallet_token_msg").show();
      jQuery("#editwallettbtn").hide();
      createWalletval = 0;
    }
    jQuery("#wallettbtnval").html('');
    wallet_val = createWallet;
    jQuery("#wallettbtnval").html(createWallet);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);


  if(jQuery.isNumeric(storageallocate) && Number.isInteger(storageallocate) &&  storageallocate >= 500){
      jQuery(".storage_token_msg").hide();
      jQuery("#addstoragebtn").hide();
      jQuery("#storageAllocation .close").trigger('click');
      jQuery("#editstoragebtn").show();
      storageallocateval = 1;
      storage_text = "GB";
    } else if(storageallocate !== '') {
      storageallocate = '';
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      storageallocateval = 0;
      storage_text = '';
    } else  {
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      storageallocateval = 0;
      storage_text = '';
    }
    jQuery("#storagebtnval").html('');
    storage_val = storageallocate;
    jQuery("#storagebtnval").html(storageallocate+storage_text);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);


  if(jQuery.isNumeric(bandwidthAllocation) && Number.isInteger(bandwidthAllocation) &&  bandwidthAllocation >= 1){
      jQuery(".bandwidth_token_msg").hide();
      jQuery("#addbandwidthbtn").hide();
      jQuery("#bandwidth .close").trigger('click');
      jQuery("#editbandwidthbtn").show();
      bandwidthAllocationval = 1;
      bandwidth_text = "TB";
    } else if(bandwidthAllocation !== '') {
      bandwidthAllocation = '';
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      bandwidthAllocationval = 0;
      bandwidth_text = '';
    } else  {
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      bandwidthAllocationval = 0;
      bandwidth_text = '';
    }
    jQuery("#bandwidthbtnval").html('');
    bandwidth_val = bandwidthAllocation;
    jQuery("#bandwidthbtnval").html(bandwidthAllocation+bandwidth_text);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);


  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
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
    jQuery("#emailAddressval").html('');
    emailiddata_val = emailiddata;
    jQuery("#emailAddressval").html(emailiddata);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);

  if(directoryAllocation !== '') {
      jQuery(".directory_token_msg").hide();
      jQuery("#adddirectorybtn").hide();
      jQuery("#directory .close").trigger('click');
      jQuery("#editdirectorybtn").show();
      directoryAllocationval = 1;
    } else {
      jQuery(".directory_token_msg").show();
      jQuery("#editdirectorybtn").hide();
      directoryAllocationval = 0;
    }
    jQuery("#directorybtnval").html('');
    directory_val = directoryAllocation;
    jQuery("#directorybtnval").html(directoryAllocation);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);




  if(identitydata !== '' &&  createAddress !== '' && createWallet !== '' &&  storageallocate !== '' && bandwidthAllocation !== '' && directoryAllocation !== '' && emailiddata !== ''){
    var identityfile = $("#identityfile").text();
    if(identityfile =="false"){
      jQuery("#startbtn").removeAttr("disabled", true);
      jQuery("#startbtn").css("cursor", "pointer");
    }
  } else {
     jQuery("#startbtn").attr("disabled", true);
     jQuery("#startbtn").css("cursor", "not-allowed");
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
    error: function () {
      console.log("In There check runing or not");
      // log message
      $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
    }
  });

  jQuery("#create_identity").click(function(){
    identitydata = jQuery("#identity_token").val();
    identitypath = jQuery("#identity_path").val();
    var identityfile = $("#identityfile").text();
    if(identitydata !== '') {
       if(identitypath !== '') {
         jQuery(".identity_path_msg").hide();
          jQuery("#identitybtn").hide();
          jQuery("#identity .close").trigger("click");
          jQuery("#editidentitybtn").show();


              var file_exists = $("#file_exists").text();
              if(identityfile =="false"){

                if(file_exists !=="0"){

                    jQuery.ajax({
                    type: "POST",
                    url: "identity.php",
                    data: {file_exist : "file_exist"},
                    success: function (result) {
                      if(result==1){
                        createidentifyToken(identitydata,identitypath);
                        readidentitystatus();
                      }else if(result==0){
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
              }

              identitydataval = 1;
              identity_text = "<span class='identity_text'>Identity Generated: </span>";
        }else{
            jQuery(".identity_path_msg").show();
            jQuery("#editidentitybtn").hide();
            jQuery("#identitybtn").show();
            identitydataval = 0;
            identity_text = '';
        }
    } else {
      jQuery(".identity_path_msg").show();
      jQuery("#editidentitybtn").hide();
      jQuery("#identitybtn").show();
      identitydataval = 0;
      identity_text = '';
    }
    jQuery("#idetityval").html('');
    identity_val = identitydata;
    identity_path = identitypath;
    jQuery("#idetityval").html(identity_text+identitydata);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  });
  jQuery('#create_address').click(function(){
    createAddress = jQuery("#host_address").val();
    // Later on provide detailed check as per need (host:port)
    if(createAddress !== '') {
      jQuery(".host_token_msg").hide();
      jQuery("#externalAddressbtn").hide();
      jQuery("#externalAddress .close").trigger("click");
      jQuery("#editexternalAddressbtn").show();
      createAddressval = 1;
      address_text = "<span class='address_text'></span>";
    } else {
      jQuery(".host_token_msg").show();
      jQuery("#editexternalAddressbtn").hide();
      jQuery("#externalAddressbtn").show();
      createAddressval = 0;
      address_text = '';
    }
    jQuery("#externalAddressval").html('');
    address_val = createAddress;
    jQuery("#externalAddressval").html(address_text+createAddress);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  });
  jQuery("#create_address").click();
  jQuery('#create_wallet').click(function(){
    createWallet = jQuery("#wallet_address").val();
    if(createWallet !== '') {
      jQuery(".wallet_token_msg").hide();
      jQuery("#addwallettbtn").hide();
      jQuery("#walletAddress .close").trigger("click");
      jQuery("#editwallettbtn").show();
      createWalletval = 1;
    } else {
      jQuery(".wallet_token_msg").show();
      jQuery("#editwallettbtn").hide();
      jQuery("#addwallettbtn").show();
      createWalletval = 0;
    }
    jQuery("#wallettbtnval").html('');
    wallet_val = createWallet;
    jQuery("#wallettbtnval").html(createWallet);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  });
  jQuery('#allocate_storage').click(function(){
    storageallocate = parseInt(jQuery("#storage_allocate").val());
    if(jQuery.isNumeric(storageallocate) && Number.isInteger(storageallocate) &&  storageallocate >= 500){
      jQuery(".storage_token_msg").hide();
      jQuery("#addstoragebtn").hide();
      jQuery("#storageAllocation .close").trigger('click');
      jQuery("#editstoragebtn").show();
      storageallocateval = 1;
      storage_text = "GB";
    } else if(storageallocate !== '') {
      storageallocate = '';
      jQuery(".storage_token_msg").show();
      jQuery("#addstoragebtn").show();
      jQuery("#editstoragebtn").hide();
      storageallocateval = 0;
      storage_text = '';
    } else  {
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      jQuery("#addstoragebtn").show();
      storageallocateval = 0;
      storage_text = '';
    }
    jQuery("#storagebtnval").html('');
    storage_val = storageallocate;
    jQuery("#storagebtnval").html(storageallocate+storage_text);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  })

  jQuery('#create_bandwidth').click(function(){
    bandwidthAllocation = parseInt(jQuery("#bandwidth_allocation").val());
    if(jQuery.isNumeric(bandwidthAllocation) && Number.isInteger(bandwidthAllocation) &&  bandwidthAllocation >= 1){
      jQuery(".bandwidth_token_msg").hide();
      jQuery("#addbandwidthbtn").hide();
      jQuery("#bandwidth .close").trigger('click');
      jQuery("#editbandwidthbtn").show();
      bandwidthAllocationval = 1;
      bandwidth_text = "TB";
    } else if(bandwidthAllocation !== '') {
      bandwidthAllocation = '';
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      jQuery("#addbandwidthbtn").show();
      bandwidthAllocationval = 0;
      bandwidth_text = '';
    } else  {
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      jQuery("#addbandwidthbtn").show();
      bandwidthAllocationval = 0;
      bandwidth_text = '';
    }
    jQuery("#bandwidthbtnval").html('');
    bandwidth_val = bandwidthAllocation;
    jQuery("#bandwidthbtnval").html(bandwidthAllocation+bandwidth_text);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  });


  jQuery('#create_emailaddress').click(function(){
    emailiddata = jQuery("#email_address").val();
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(regex.test(emailiddata)) {
      jQuery(".email_token_msg").hide();
      jQuery("#emailAddressbtn").hide();
      jQuery("#emailAddress .close").trigger("click");
      jQuery("#editemailAddressbtn").show();
      emailiddataval = 1;

      jQuery("#emailAddressval").html('');
      emailiddata_val = emailiddata;
      jQuery("#emailAddressval").html(emailiddata);
      showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
    } else {
      jQuery(".email_token_msg").show();
      jQuery("#editemailAddressbtn").hide();
      jQuery("#emailAddressbtn").show();
      emailiddataval = 0;
      jQuery("#emailAddressval").html('');
    }
    
  });
  jQuery('#create_directory').click(function(){
    directoryAllocation = jQuery("#storage_directory").val();
    if(directoryAllocation !== '') {
      jQuery(".directory_token_msg").hide();
      jQuery("#adddirectorybtn").hide();
      jQuery("#directory .close").trigger('click');
      jQuery("#editdirectorybtn").show();
      directoryAllocationval = 1;
    } else {
      jQuery(".directory_token_msg").show();
      jQuery("#editdirectorybtn").hide();
      jQuery("#adddirectorybtn").show();
      directoryAllocationval = 0;
    }
    jQuery("#directorybtnval").html('');
    directory_val = directoryAllocation;
    jQuery("#directorybtnval").html(directoryAllocation);
    showstartbutton(identitydataval,createAddressval,createWalletval,storageallocateval,emailiddataval,directoryAllocationval);
  });
  jQuery("#editidentitybtn button").click(function(){
      jQuery('#storjrows').hide();
  })
});

function showstartbutton(createidentitydataval,createAddressvaldata,createWalletvaldata,storageallocatevaldata,emailAddressvaldata,directoryAllocationvaldata,){
  if(createidentitydataval ===1 && createAddressvaldata === 1 && createWalletvaldata === 1 && storageallocatevaldata === 1  && directoryAllocationvaldata === 1) {
    
    // jQuery("#startbtn").removeAttr("disabled", true);
    // jQuery("#startbtn").css("cursor", "pointer");
    var identityfile = $("#identityfile").text();
     if(identityfile =="false"){
      jQuery("#startbtn").removeAttr("disabled", true);
      jQuery("#startbtn").css("cursor", "pointer");
    }
  } else{
    jQuery("#startbtn").attr("disabled", true);
    jQuery("#startbtn").css("cursor", "not-allowed");
  }
}

jQuery("#startbtn").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: "config.php",
      data: {identity : identity_path, authKey:identity_val, address : address_val, wallet : wallet_val, storage : storage_val, email_val : emailiddata_val, directory: directory_val, isajax : 1},
      success: function (result) {
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
      url: "config.php",
      data: {isstopAjax : 1},
      success: function (result) {
        window.location.reload();
      },
      error: function () {
        console.log("In There wrong on Stop Button");
      }
    });
});

jQuery("#updatebtn").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: "config.php",
      data: {identity : identity_path, authKey:identity_val, address : address_val, wallet : wallet_val, storage : storage_val, email_val : emailiddata_val, directory: directory_val, isUpdateAjax : 1},
      success: function (result) {
        window.location.reload();
      },
      error: function () {
        console.log("Something wrong with stop button");
      }
    });
});


if(jQuery("#identity_token").val() ==null || jQuery("#host_address").val() =="" || jQuery("#host_address").val() ==null || jQuery("#wallet_address").val() =="" || jQuery("#wallet_address").val() ==null || parseInt(jQuery("#storage_allocate").val()) =="" || parseInt(jQuery("#storage_allocate").val()) ==null  || jQuery("#email_address").val() ==null || jQuery("#storage_directory").val() =="" || jQuery("#storage_directory").val() ==null){

  }else{
    jQuery.ajax({
        type: "POST",
        url: "config.php",
        data: { isrun : 1},
        success: function (resposnse) {
          if(resposnse) {
            // log message
            if(resposnse ==1){
              $(".editbtn").attr("disabled",true).css("cursor","not-allowed");

              $("#startbtn").attr("disabled",true).css("cursor","not-allowed");

              $("#stopbtn").attr("disabled",false).css("cursor","pointer");
            }else if(resposnse ==0){
              $(".editbtn").attr("disabled",false).css("cursor","pointer");

              $("#stopbtn").attr("disabled",true).css("cursor","not-allowed");

              $("#startbtn").attr("disabled",false).css("cursor","pointer");
            }

          }
        },
        error: function () {
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
        if(result == "identity available at /root/.local/share/storj/identity"){
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
    jQuery.ajax({
      type: "POST",
      url: "identity.php",
      data: {status : "status",},
      success: function (result) {
        if(result == "identity available at /root/.local/share/storj/identity"){
            $("#identity_status").html("<b>Identity creation process is running.</b><br><p>"+result+"</p>");
            identitydataval = 1;
        }else{
          $("#identity_status").html("<b>"+result+"</b>");
        }
      },
      error: function () {
        console.log("In tehre wrong on Identitfy status");
      }
    });
  },60000);
}

function validateIdentity(){
  jQuery.ajax({
  type: "POST",
  url: "identity.php",
  data: {validateIdentity : "validateIdentity",},
  success: function (result) {
    $("#identity_status").html("<b>"+result+"</b>");
  },
  error: function () {
    console.log("In tehre wrong on create Identitfy");
  }
});
}


jQuery("#stop_identity").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: "identity.php",
      data: {isstopAjax : 1},
      success: function (result) {
        window.location.reload();
      },
      error: function () {
        console.log("In There wrong on Stop Button");
      }
    });
});

$("#identity_path").change(function(){
  identity_path = $(this).val();
  if(identity_path == "" || identity_path ==null){
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