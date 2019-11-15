var identitydataval, createAddressval, createWalletval, storageallocateval, bandwidthAllocationval, directoryAllocationval, emailiddataval ;
var identity_val, address_val, wallet_val, storage_val, bandwidth_val, directory_val, emailiddata_val;
jQuery(function() {
  hideButton();
  var identitydata = jQuery("#identity_token").val();
  var createAddress = jQuery("#host_address").val();
  var createWallet = jQuery("#wallet_address").val();
  var storageallocate = jQuery("#storage_allocate").val();
  var bandwidthAllocation = jQuery("#bandwidth_allocation").val();
  var emailiddata = jQuery("#email_address").val();
  var directoryAllocation = jQuery("#storage_directory").val();
  if(identitydata !== '' &&  createAddress !== '' && createWallet !== '' &&  storageallocate !== '' && bandwidthAllocation !== '' && directoryAllocation !== '' && emailiddata !== ''){
    jQuery("#startbtn").removeAttr("disabled", true);
    jQuery("#stopbtn").removeClass("stopnodebtn");
  } else {
    jQuery("#startbtn").attr("disabled", true);
    jQuery("#startbtn").removeClass("start-button");
    jQuery("#stopbtn").removeClass("stop-button");
  }

  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: { isstartajax : 1},
    success: function (resposnse) {
      if(resposnse) {
        jQuery("#stopbtn").removeAttr("disabled", true);
        jQuery("#stopbtn").addClass("stop-button");
      }
    },
    error: function () {
      console.log("In There check runing or not");
    }
  });

  jQuery("#create_identity").click(function(){
    identitydata = jQuery("#identity_token").val();
    if(identitydata !== '') {
      showButton();
      jQuery(".identity_token_msg").hide();
      jQuery("#identitybtn").hide();
      jQuery("#identity .close").trigger("click");
      jQuery("#editidentitybtn").show();
      createidentifyToken(identitydata);
      identitydataval = 1;
    } else {
      jQuery(".identity_token_msg").show();
      jQuery("#editidentitybtn").hide();
      identitydataval = 0;
    }
    jQuery("#idetityval").html('');
    identity_val = identitydata;
    jQuery("#idetityval").html(identitydata);
  });
  jQuery('#create_address').click(function(){
    createAddress = parseInt(jQuery("#host_address").val());
    if(jQuery.isNumeric(createAddress) && Number.isInteger(createAddress)){
      jQuery(".host_token_msg").hide();
      jQuery("#externalAddressbtn").hide();
      jQuery("#externalAddress .close").trigger("click");
      jQuery("#editexternalAddressbtn").show();
      createAddressval = 1;
    } else if(createAddress !== ''){
      jQuery(".host_token_msg").show();
      jQuery("#addstoragebtn").show();
      createAddressval = 0;
    } else {
      jQuery(".host_token_msg").show();
      jQuery("#editexternalAddressbtn").hide();
      createAddressval = 0;
    }
    jQuery("#externalAddressval").html('');
    address_val = createAddress;
    jQuery("#externalAddressval").html(createAddress);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,emailiddataval,directoryAllocationval);
  });
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
      createWalletval = 0;
    }
    jQuery("#wallettbtnval").html('');
    wallet_val = createWallet;
    jQuery("#wallettbtnval").html(createWallet);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,emailiddataval,directoryAllocationval);
  });
  jQuery('#allocate_storage').click(function(){
    storageallocate = parseInt(jQuery("#storage_allocate").val());
    if(jQuery.isNumeric(storageallocate) && Number.isInteger(storageallocate) &&  storageallocate >= 1000){
      jQuery(".storage_token_msg").hide();
      jQuery("#addstoragebtn").hide();
      jQuery("#storageAllocation .close").trigger('click');
      jQuery("#editstoragebtn").show();
      storageallocateval = 1;
    } else if(storageallocate !== '') {
      storageallocate = '';
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      storageallocateval = 0;
    } else  {
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      storageallocateval = 0;
    }
    jQuery("#storagebtnval").html('');
    storage_val = storageallocate;
    jQuery("#storagebtnval").html(storageallocate);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,emailiddataval,directoryAllocationval);
  })
  jQuery('#create_bandwidth').click(function(){
    bandwidthAllocation = parseInt(jQuery("#bandwidth_allocation").val());
    if(jQuery.isNumeric(bandwidthAllocation) && Number.isInteger(bandwidthAllocation) &&  bandwidthAllocation >= 1){
      jQuery(".bandwidth_token_msg").hide();
      jQuery("#addbandwidthbtn").hide();
      jQuery("#bandwidth .close").trigger('click');
      jQuery("#editbandwidthbtn").show();
      bandwidthAllocationval = 1;
    } else if(bandwidthAllocation !== '') {
      bandwidthAllocation = '';
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      bandwidthAllocationval = 0;
    } else  {
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      bandwidthAllocationval = 0;
    }
    jQuery("#bandwidthbtnval").html('');
    bandwidth_val = bandwidthAllocation;
    jQuery("#bandwidthbtnval").html(bandwidthAllocation);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,emailiddataval,directoryAllocationval);
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
    } else {
      jQuery(".email_token_msg").show();
      jQuery("#editemailAddressbtn").hide();
      emailiddataval = 0;
    }
    jQuery("#emailAddressval").html('');
    emailiddata_val = emailiddata;
    jQuery("#emailAddressval").html(emailiddata);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,emailiddataval,directoryAllocationval);
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
      directoryAllocationval = 0;
    }
    jQuery("#directorybtnval").html('');
    directory_val = directoryAllocation;
    jQuery("#directorybtnval").html(directoryAllocation);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,emailiddataval,directoryAllocationval);
  });
  jQuery("#editidentitybtn button").click(function(){
      jQuery('#storjrows').hide();
  })
});
function showstartbutton(createAddressvaldata,createWalletvaldata,storageallocatevaldata,bandwidthAllocationvaldata,emailAddressvaldata,directoryAllocationvaldata,){
  if(createAddressvaldata === 1 && createWalletvaldata === 1 && storageallocatevaldata === 1 && bandwidthAllocationvaldata === 1 && emailAddressvaldata == 1 && directoryAllocationvaldata === 1) {
    jQuery("#startbtn").removeAttr("disabled", true);
    jQuery("#startbtn").addClass("start-button");
  } else{
    jQuery("#startbtn").attr("disabled", true);
  }
}

jQuery("#startbtn").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: "config.php",
      data: {address : address_val, wallet : wallet_val, storage : storage_val, bandwidth : bandwidth_val, email_val : emailiddata_val, directory: directory_val, isajax : 1},
      success: function (result) {
        window.location.reload();
      },
      error: function () {
        console.log("In tehre wrong");
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

function createidentifyToken(createidval) {
  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: {identity : createidval, identityajax : 1},
    success: function (result) {
      console.log(result);
      if(result) {
        jQuery('#storjrows').show();
      }
    },
    error: function () {
      console.log("In tehre wrong on create Identitfy");
    }
  });

}

function showButton(){
  jQuery("#externalAddressbtn").removeAttr("disabled", true);
  jQuery("#externalAddressbtn").addClass("segment-btn");
  jQuery("#addwallettbtn").removeAttr("disabled", true);
  jQuery("#addwallettbtn").addClass("segment-btn");
  jQuery("#addstoragebtn").removeAttr("disabled", true);
  jQuery("#addstoragebtn").addClass("segment-btn");
  jQuery("#addbandwidthbtn").removeAttr("disabled", true);
  jQuery("#addbandwidthbtn").addClass("segment-btn");
  jQuery("#emailAddressbtn").removeAttr("disabled", true);
  jQuery("#emailAddressbtn").addClass("segment-btn");
  jQuery("#adddirectorybtn").removeAttr("disabled", true);
  jQuery("#adddirectorybtn").addClass("segment-btn");
}
function hideButton(){
  jQuery("#stopbtn").addClass("stopnodebtn");
  jQuery("#externalAddressbtn").removeClass("segment-btn");
  jQuery("#addwallettbtn").removeClass("segment-btn");
  jQuery("#addstoragebtn").removeClass("segment-btn");
  jQuery("#addbandwidthbtn").removeClass("segment-btn");
  jQuery("#adddirectorybtn").removeClass("segment-btn");
  jQuery("#emailAddressbtn").removeClass("segment-btn");
  jQuery("#externalAddressbtn").attr("disabled", true);
  jQuery("#addwallettbtn").attr("disabled", true);
  jQuery("#addstoragebtn").attr("disabled", true);
  jQuery("#emailAddressbtn").attr("disabled", true);
  jQuery("#addbandwidthbtn").attr("disabled", true);
  jQuery("#adddirectorybtn").attr("disabled", true);
}
