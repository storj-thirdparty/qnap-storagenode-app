
var Access_Key,Source, Destination;
jQuery(function() {
  var name = jQuery("#current_name").val();
   // Get values
  if(name !== '') {
      jQuery(".name_msg").hide();
      jQuery("#namebtn").hide();
      jQuery("#name .close").trigger("click");
      jQuery("#editnamebtn").show();
      name_text = "<span class='name_text'></span>";
    } else {
      jQuery(".name_msg").show();
      jQuery("#editnamebtn").hide();
      name_text = '';
    }
    jQuery("#nameval").html('');
    Access_Key = name;
    showrunrclonebutton(Access_Key,Source, Destination);
    // jQuery("#nameval").html(name_text+name);
     // jQuery(".currentname").html(name);

  var scope = jQuery("#scope_val").val();
  if(scope !== '') {
      jQuery("#scopebtn").hide();
      jQuery("#scope .close").trigger("click");
      jQuery("#editscopebtn").show();
      scope_text = "<span class='scope_text'></span>";
    } else {
      jQuery(".scope_msg").show();
      jQuery("#editscopebtn").hide();
      scope_text = '';
    }
    Source = scope;
    showrunrclonebutton(Access_Key,Source, Destination);

  var viewaccess = jQuery("#viewaccess_val").val();
  if(viewaccess !== '') {
      jQuery("#viewaccessbtn").hide();
      jQuery("#name .close").trigger("click");
      jQuery("#editviewaccessbtn").show();
      viewaccess_text = "<span class='viewaccess_text'></span>";
    } else {
      jQuery(".viewaccess_msg").show();
      jQuery("#editviewaccessbtn").hide();
      viewaccess_text = '';
    }
    jQuery("#viewaccessval").html('');
   	jQuery("#viewaccessval").html(viewaccess_text+viewaccess);
    Destination = viewaccess;
    showrunrclonebutton(Access_Key,Source, Destination);

  // var defaults = jQuery("#defaults_val").val();
  //  if(defaults !== '') {
  //     jQuery("#defaultsbtn").hide();
  //     jQuery("#defaults .close").trigger("click");
  //     jQuery("#editdefaultsbtn").show();
  //     defaults_text = "<span class='defaults_text'></span>";
  //   } else {
  //     jQuery(".defaults_msg").show();
  //     jQuery("#editdefaultsbtn").hide();
  //     defaults_text = '';
  //   }
  //   jQuery("#defaultsval").html('');
  //  	jQuery("#defaultsval").html(defaults_text+defaults);
});

$("#create_name").click(function(){
	var name = jQuery("#current_name").val();
  if(name !== '') {
      jQuery(".name_msg").hide();
      jQuery("#namebtn").hide();
      jQuery("#name .close").trigger("click");
      jQuery("#editnamebtn").show();
      name_text = "<span class='name_text'></span>";

      jQuery.ajax({
      type: "POST",
      url: "rclone.php",
      data: {Access_Key : name, accesskey : 1},
      success: function (resposnse) {
        if(resposnse) {
          // log message
         console.log("success");
         // $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
        }
      },
      error: function () {
        console.log("error");
        // $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
      }
    });
  } else {
      jQuery(".name_msg").show();
      jQuery("#editnamebtn").hide();
      name_text = '';
    }
     jQuery("#nameval").html('');
	   Access_Key = name;
     showrunrclonebutton(Access_Key,Source, Destination);
});

$("#creat_scope").click(function(){
  var source_val = jQuery("#scope_val").val();
  if(source_val !== '') {
      jQuery(".scope_msg").hide();
      jQuery("#scopebtn").hide();
      jQuery("#scope .close").trigger("click");
      jQuery("#editscopebtn").show();
      source_text = "<span class='scope_text'></span>";
    } else {
       jQuery(".scope_msg").show();
      jQuery("#editscopebtn").hide();
      source_text = '';
    }
     jQuery("#scopeval").html('');
     Source = source_val;
     showrunrclonebutton(Access_Key,Source, Destination);
});

$("#create_viewaccess").click(function(){
  var viewaccess = jQuery("#viewaccess_val").val();
  if(viewaccess !== '') {
      jQuery(".viewaccess_msg").hide();
      jQuery("#viewaccessbtn").hide();
      jQuery("#viewaccess .close").trigger("click");
      jQuery("#editviewaccessbtn").show();
      viewaccess_text = "<span class='viewaccess_text'></span>";
    } else {
       jQuery(".viewaccess_msg").show();
      jQuery("#editviewaccessbtn").hide();
      viewaccess_text = '';
    }
     jQuery("#viewaccessval").html('');
    jQuery("#viewaccessval").html(viewaccess_text+viewaccess);
     Destination = viewaccess;
     showrunrclonebutton(Access_Key,Source, Destination);
});

$("#rclonebtn").click(function(){
	jQuery.ajax({
    type: "POST",
    url: "rclone.php",
    data: { rcloneconfig : "rcloneconfig"},
    success: function (resposnse) {
      if(resposnse) {
        // log message
       console.log("success");
       $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
      }
    },
    error: function () {
      console.log("error");
      $('iframe').contents().find('body').html('<p>'+resposnse+'</p>');
    }
  });
});

$("#runrclonebtn").click(function(){
    jQuery.ajax({
      type: "POST",
      url: "rclone.php",
      data: {Access_Key:Access_Key, source : Source, destination : Destination, runrclone : "runrclone"},
      success: function (result) {
        //console.log("I am here");
        // window.location.reload();

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

function showrunrclonebutton(Access_Key,Source,Destination){
   console.log("Access_Key",Access_Key);
     console.log("Source",Source);
     console.log("Destination",Destination);
  if(Access_Key !=="" && Access_Key && Source !=="" && Source && Destination !=="" && Destination) {
    jQuery("#runrclonebtn").removeAttr("disabled", true);
    jQuery("#runrclonebtn").css("cursor", "pointer");
  } else{
    jQuery("#runrclonebtn").attr("disabled", true);
    // make button cursor not-allowed
    jQuery("#runrclonebtn").css("cursor", "not-allowed");
  }
}