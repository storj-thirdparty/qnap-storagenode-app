var identitydataval, createAddressval, createWalletval, storageallocateval, bandwidthAllocationval, directoryAllocationval;
var identity_val, address_val, wallet_val, storage_val, bandwidth_val, directory_val;
jQuery(function() {
  var identitydata = jQuery("#identity_token").val();
  var createAddress = jQuery("#host_address").val();
  var createWallet = jQuery("#wallet_address").val();
  var storageallocate = jQuery("#storage_allocate").val();
  var bandwidthAllocation = jQuery("#bandwidth_allocation").val();
  var directoryAllocation = jQuery("#storage_directory").val();
  if(identitydata !== '' &&  createAddress !== '' && createWallet !== '' &&  storageallocate !== '' && bandwidthAllocation !== '' && directoryAllocation !== ''){
    jQuery("#startbtn").removeAttr("disabled", true);
  } else {
    jQuery("#startbtn").attr("disabled", true);
  }
  jQuery("#create_identity").click(function(){
    identitydata = jQuery("#identity_token").val();
    if(identitydata !== '') {
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
    //showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,directoryAllocationval);
  });
  jQuery('#create_address').click(function(){
    createAddress = jQuery("#host_address").val();
    if(createAddress !== '') {
      jQuery(".host_token_msg").hide();
      jQuery("#externalAddressbtn").hide();
      jQuery("#externalAddress .close").trigger("click");
      jQuery("#editexternalAddressbtn").show();
      createAddressval = 1;
    } else {
      jQuery(".host_token_msg").show();
      jQuery("#editexternalAddressbtn").hide();
      createAddressval = 0;
    }
    jQuery("#externalAddressval").html('');
    address_val = createAddress;
    jQuery("#externalAddressval").html(createAddress);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,directoryAllocationval);
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
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,directoryAllocationval);
  });
  jQuery('#allocate_storage').click(function(){
    storageallocate = jQuery("#storage_allocate").val();
    if(storageallocate !== '') {
      jQuery(".storage_token_msg").hide();
      jQuery("#addstoragebtn").hide();
      jQuery("#storageAllocation .close").trigger('click');
      jQuery("#editstoragebtn").show();
      storageallocateval = 1;
    } else {
      jQuery(".storage_token_msg").show();
      jQuery("#editstoragebtn").hide();
      storageallocateval = 0;
    }
    jQuery("#storagebtnval").html('');
    storage_val = storageallocate;
    jQuery("#storagebtnval").html(storageallocate);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,directoryAllocationval);
  })
  jQuery('#create_bandwidth').click(function(){
    bandwidthAllocation = jQuery("#bandwidth_allocation").val();
    if(bandwidthAllocation !== '') {
      jQuery(".bandwidth_token_msg").hide();
      jQuery("#addbandwidthbtn").hide();
      jQuery("#bandwidth .close").trigger('click');
      jQuery("#editbandwidthbtn").show();
      bandwidthAllocationval = 1;
    } else {
      jQuery(".bandwidth_token_msg").show();
      jQuery("#editbandwidthbtn").hide();
      bandwidthAllocationval = 0;
    }
    jQuery("#bandwidthbtnval").html('');
    bandwidth_val = bandwidthAllocation;
    jQuery("#bandwidthbtnval").html(bandwidthAllocation);
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,directoryAllocationval);
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
    showstartbutton(createAddressval,createWalletval,storageallocateval,bandwidthAllocationval,directoryAllocationval);
  });
  jQuery("#editidentitybtn button").click(function(){
      jQuery('#storjrows').hide();
  })
});
function showstartbutton(createAddressvaldata,createWalletvaldata,storageallocatevaldata,bandwidthAllocationvaldata,directoryAllocationvaldata){
  if(createAddressvaldata === 1 && createWalletvaldata === 1 && storageallocatevaldata === 1 && bandwidthAllocationvaldata === 1 && directoryAllocationvaldata === 1) {
    jQuery("#startbtn").removeAttr("disabled", true);
  } else{
    jQuery("#startbtn").attr("disabled", true);
  }
}

jQuery("#startbtn").click(function(e) {
    jQuery.ajax({
      type: "POST",
      url: "config.php",
      data: {address : address_val, wallet : wallet_val, storage : storage_val, bandwidth : bandwidth_val,  directory: directory_val, isajax : 1},
      success: function (result) {
        window.location.reload();
      },
      error: function () {
        console.log("In tehre wrong");
      }
    });
});

function createidentifyToken(createidval) {
  jQuery.ajax({
    type: "POST",
    url: "config.php",
    data: {identity : createidval, identityajax : 1},
    success: function (result) {
      if(result) {
        jQuery('#storjrows').show();
      }
    },
    error: function () {
      console.log("In tehre wrong on create Identitfy");
    }
  });

}
