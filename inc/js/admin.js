// sorry, quick hack
function toggleSpecial(selector){
  // '#special-pricing-<?php echo $prop_nice_name; ?>'
  jQuery('#special-pricing-'+selector).toggle();
  if(jQuery('#special-pricing-'+selector).is(":visible")){
    jQuery('#_show_'+selector).attr('value', "Hide Locations");
  }
  else{
    jQuery('#_show_'+selector).attr('value', "Show Locations");
  }
  
}

(function($) {
  var maxZoom;
  var mapZoom;
  var initLat;
  var initLng;
  var mapCenter;
  var googleMap;
  var marker;
  var markers;
  var bounds;
  var geocoder;

  /* CHP admin functions */
  if ("undefined" === typeof CHP) {
  	var CHP = {};
  }

  CHP.Admin = (function(){
  
    var init = function(){
    
      $(".remove-featured").click(function(){
        $this = $(this);
        var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
        var rel = $(this).attr('rel');
        $.post(
          ajaxurl,
    			{
    			    action: 'unfeature_post_by_id', 
    			    wp_meta_box_nonce: _wpnonce, 
    			    data: { 
    			      post_id : rel
    		      }
    			},
    			function(response){
     				if( response && 1 == parseInt(response.result) ){

     					$this.parent().parent().fadeOut( function(){
     					    $this.remove();
     					});
   					
     				}
    			},
    			"json"
          );
        return false;
      });
    
      $("#property_type").change(function(){
        // alert('changed');
        switch($(this).val()){
          case 'Sale':
            $("#sale_only").show();
            $("#rental_only").hide();
          break;
          case 'Rent':
            $("#sale_only").hide();
            $("#rental_only").show();
          break;
        }
      });
    
      if($("#poststuff").length>0){
        $("#poststuff").fadeIn(function(){
          if('program'===the_post_type){
            $("h3.hndle", $("#postimagediv")).html('<span>Program Logo</span>');
            if(0===$("a#set-post-thumbnail").find('img').length)
              $("a#set-post-thumbnail").text('Upload/Set Logo');
            $("a#remove-post-thumbnail").text('Remove Logo');
            $("h3.hndle", $("#postexcerpt")).html('<span>Short Program Description</span>');
          }
          else if('developments'===the_post_type){
            $("h3.hndle", $("#postimagediv")).html('<span>Development Logo</span>');
            if(0===$("a#set-post-thumbnail").find('img').length)
              $("a#set-post-thumbnail").text('Upload/Set Logo');
            $("a#remove-post-thumbnail").text('Remove Logo');
            $("h3.hndle", $("#postexcerpt")).html('<span>Short Development Description</span>');
          }
          else if('property'===the_post_type){
            $("h3.hndle", $("#postimagediv")).html('<span>Development/Rental Co. Logo</span>');
            if(0===$("a#set-post-thumbnail").find('img').length)
              $("a#set-post-thumbnail").text('Upload/Set Logo');
            $("a#remove-post-thumbnail").text('Remove Logo');
          
            $("h3.hndle", $("#postexcerpt")).html('<span>Short Property Description</span>');
            $("#postexcerpt .inside p").html('Displayed in previews of this property and in map overlays.');
            if($("#postdivrich").length>0){  
              $("#postdivrich").before($("#property-info"), function(){ showTabs(); });
              $("#postdivrich").before('<div class="titlediv-after"><h2>Property Description</h2></div>');
            }
          
          }
          else if('volunteer'===the_post_type){
            if($("#postdivrich").length>0){  
              $("#postdivrich").before($("#volunteer-info"), function(){ showTabs(); });
              $("#postdivrich").before('<div class="titlediv-after"><h2>Volunteer Comments</h2></div>');
            }
          
          }
          else if('event'===the_post_type){
            $("h3.hndle", $("#postexcerpt")).html('<span>Short Event Description</span>');
            $("#postexcerpt .inside p").html('Displayed in previews of this event.');
          
            if($("#postdivrich").length>0){  
              $("#postdivrich").before($("#event-info"), function(){ showTabs(); });
              $("#postdivrich").before('<div class="titlediv-after"><h2>Event Description</h2></div>');
            }
          
          }
          else if('post'===the_post_type){
            if($("#postdivrich").length>0){
              $("#postdivrich").before($("#post-addons"), function(){ showTabs(); });
              // $("#postdivrich").before('<div class="titlediv-after"><h2>Post B</h2></div>');
            }
          }
          // showTabs();
        });
      } else{
        showTabs();
      }
    
      $(".datepick, .timepick").each(function() {
          if ($(this).val() == "") {
              $(this).siblings("label").each(function() {
                  $(this).css("visibility", "");
              })
          }
      });
    
      $(".datepick").datepicker({
          numberOfMonths: 2,
          // minDate: 0,
          dateFormat: 'DD, MM d yy', 
          onClose: function(dateText, inst){
              if (dateText == "") {
                  $(this).siblings(".fancy-label").first().css("visibility", "")
              }
          },
          onSelect: function(dateText, inst){
              $(this).siblings(".fancy-label").first().css("visibility", "hidden");
          },
          beforeShow: function(input, inst){
              $(this).siblings(".fancy-label").first().css("visibility", "hidden");
              if( "enddate" == input.id ){
             
              }
          }
      });
    
      $(".remove").click(function() {
    		$(this).parent().remove();
    	});

    	$(".add-more").live('keydown', function( event ) {
    	    if (event.keyCode == '13') {
        	    $(this).clone().insertAfter( $(this) ).val('').show().focus(); //  $(".add-more-button")
        	    return false;
        	}
    	});
      
      $("#price").live('keydown', function( event ) {
        if ((event.keyCode < 48 || event.keyCode > 57) 
            && event.keyCode != 8 && event.keyCode != 37 && event.keyCode != 38 && event.keyCode != 46 
            && event.keyCode != 27 && event.keyCode != 9) {
            return false;
        }
    	});
    	
    	$(".remove-one-button").click(function() {
  	    $(this).siblings(".add-more").last().remove();
      });

    	$(".add-more-button").click(function() {
    	    var first_more = $(this).siblings(".add-more").first();
    	    var last_more = $(this).siblings(".add-more").last();
    	    first_more.clone().insertAfter( last_more ).val('').show().focus(); // last_more
    		// $("#newimages p:first-child").clone().insertAfter("#newimages p:last").show();
    		return false;
    	});
  	
      $(".prompt-text").each(function() {
        $(this).attr("title", $(this).siblings("label").text());
          if ($(this).val() == "") {
              $(this).siblings("label").each(function() {
                  $(this).css("visibility", "");
              })
          }
      });
  
      $(".fancy-label").each(function() {
          $(this).click(function() {
              $(this).css("visibility", "hidden").siblings("input").each(function() {
                 $(this).focus();
              });
          });
      });
  
      $(".prompt-text").each(function() {
          $(this).blur(function() {
  
              if (this.value == "") {
                  $(this).siblings(".fancy-label").first().css("visibility", "")
              }
  
          }).focus(function() {
              $(this).siblings(".fancy-label").first().css("visibility", "hidden")
          }).keydown(function(g) {
              $(this).siblings(".fancy-label").first().css("visibility", "hidden");
              // $(this).unbind(g)
          });
      });
    
      $('#ex_type').change(function(){
        if( $(this).val()==='Rent'){
          $('label[for="price"]').text('Price per month');
        }
        else{
          $('label[for="price"]').text('Price');
        }
      });
    
      $('input.delete-admin-img').live('click', function(){
       var delete_id = $(this).attr("id");
       var $this = $(this);

       var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
         $.post(
          ajaxurl,
    			{
    			    action: 'delete_img_for_post', 
    			    wp_meta_box_nonce: _wpnonce, 
    			    data: { 
    			    delete_id : delete_id
    		    }
    			},
    			function(response){
     				if( response && 1 == parseInt(response.result) ){

     					$this.parent().parent().fadeOut( function(){
     					    $this.remove();
     					});
     				}
    			},
    			"json"
          );

     });
   
      $('input.admin-thumb-caption-button').live('click', function(){
    	   var updatecap_id = $(this).attr("id");
    	   var caption = $('#for-'+updatecap_id).val();

    	   var $this = $(this);
    	   $('img#loader-'+updatecap_id).fadeIn();
    	   var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
           $.post(
    	    ajaxurl,
      			{
      			    action: 'update_img_caption', 
      			    wp_meta_box_nonce: _wpnonce, 
      			    data: { 
    				    updatecap_id    : updatecap_id,
    				    caption         : caption
    			    }
      			},
      			function(response){
      			    $('img#loader-'+updatecap_id).fadeOut();
       				if( response && 1 == parseInt(response.result) ){

       				}
      			},
      			"json"
            );

    	});
    };
  
    var showTabs = function(){
      setTimeout(function(){
        $("#tabs").tabs({
          select:function(event, ui){
            window.location.hash = ui.tab.hash;
          }
        });
        $("#tabs").fadeIn( function(){    
        });
      }, 250 );
    };
  
    var removeFeatured = function(post_id){
      alert( post_id );
    };
    
    // var toggleSpecial = function(selector){
    //   // '#special-pricing-<?php echo $prop_nice_name; ?>'
    //   jQuery('#special-pricing-'+selector).toggle();
    //   
    //   jQuery('#_show_'+selector).attr('value', "asdf");
    // };
    
    return {
      "init"            : init,
      "showTabs"        : showTabs,
      "removeFeatured"  : removeFeatured
      // "toggleSpecial"   : toggleSpecial
    };
  
  }());



  CHP.PostAdmin = (function(){ 
    var uploader;
  
    // alert(upload_receiver);
    // alert(the_post_type);
  
    var init = function(){
    
      $("#is_event").change(function(){
        if($(this).is(':checked')) {
			$("#event_fields").show();
			$("#event_fields_both").show();
			$("#is_event_multiday").removeAttr('checked');
			$("#is_event_multiday").trigger('change');
			$("#event_fields_both").show();
		}
		else {
			$("#event_fields").hide();
			$("#event_fields_both").hide();
		}
      });
	  
      $("#is_event_multiday").change(function(){
        if($("#is_event_multiday").is(':checked')) {
			$("#is_event").removeAttr('checked');
			$("#is_event").trigger('change');
			$("#event_fields_multiday").show();
			$("#event_fields_both").show();
		}
		else {
			$("#event_fields_multiday").hide();
			$("#event_fields_both").hide();
          // clear post event fields
          //$("#event_date_multiday").val('');
          //$("#event_hours_multiday").val('');
		}
      });
    
      // uploader with posts
      if( $("#program_images").length > 0 )
        getPostImgs( 'program_images' );
      
      if( $("#developments_images").length > 0 )
        getPostImgs( 'developments_images' );
      
      if( $("#property_images").length > 0 )
        getPostImgs( 'property_images' );

      if( $("#post_images").length > 0 )
        getPostImgs( 'post_images' );
    
      // if( $("#event_images").length > 0 )
      //   getPostImgs( 'event_images' );
    
      if($('#file-uploader').length>0 && $("#" + the_post_type + '_images').length > 0){
        // alert(upload_receiver);
        uploader = new qq.FileUploader({
      	  element: document.getElementById('file-uploader'),
      	  // path to server-side upload script
      	  action: upload_receiver,
      		params: {
      			post_id : parseInt( $("#post_ID").val() ),
      			post_type : the_post_type
      		},
      		multiple: false,
      		allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
      		onSubmit:function(id, fileName){
      		  // alert(fileName);
      	    $("#img-loading").fadeIn();
      	    $("#file-uploader").hide(50);
      	  },
      		onComplete: function(id, fileName, result){
      		  
            // $("#property_images").append(JSON.stringify(result));
      		  
      		  $("#img-loading").fadeOut(function(){
      		    
      		    if(result.success){
      			    getLastPostImg( the_post_type + '_images' );
      			  }
      			  
      		  });
      		  $("#file-uploader").show(50);
      		},
          onProgress: function(id, fileName, loaded, total){
            // $("#property_images").append(JSON.stringify(loaded));
            // $("#property_images").append(JSON.stringify(total));
          }
      	});
      };
    
      //post-less uploader
      if( $("#chp_home_page_logo_images").length >0 ){
          getPostLessImgs( 'chp_home_page_logo_images', 0, 'home-logo', 'logo' );

        if ($('#file-uploader').length>0 ){
          uploader = new qq.FileUploader({
        	  element: document.getElementById('file-uploader'),
        	  // path to server-side upload script
        	  action: upload_receiver,
        		params: {
        			post_id : 0 , // faux post_id to manage postless logos, this could be cleaner
        			post_type : 'logo'
        		},
        		allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        		onSubmit:function(id, fileName){
        	    $("#img-loading").fadeIn();
        	  },
        		onComplete: function(id, fileName, result){
        		  $("#img-loading").fadeOut(function(){
        		    if(result.success){
        		      getLastPostlessImg( 'chp_home_page_logo_images', 0, 'home-logo', 'logo' );
        			  }
        		  });
        		}
        	});
        }
      
      }
    };
  
    var getPostLessImgs = function( selector, faux_id, size, metakey ){
      var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
        $.post(
    	    ajaxurl,
     			{
     			  action: 'post_imgs_by_meta', 
     			  wp_meta_box_nonce: _wpnonce, 
     			  data: { 
    				post_id : faux_id,
    				metakey : metakey,
    				size	: size 
    			}
   			},
   			function(response){
  		    if( response ){
  		        if( response.imgs && response.imgs.length > 0 ){
  		        var is_load = $(".admin-thumb-div").length == 0;
    					$.each( response.imgs, function( i, item ){
    						addAnAdminThumb( item, selector, is_load );
    					});
    				}
    				else {
    				  $('#'+selector).html('<div class="admin-no-images">No images have been uploaded</div>');
  			    }
  			  } 
   			},
   			"json"
      );
    
    };
  
  	var getPostImgs = function( selector ){
      var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
        $.post(
  	    ajaxurl,
   			{
   			  action: 'post_imgs', 
   			  wp_meta_box_nonce: _wpnonce, 
   			  data: { 
  				post_id : parseInt( $("#post_ID").val() ),
  				size	: 'thumbnail' 
  			}
   			},
   			function(response){
  		    if( response ){
  		        if( response.imgs && response.imgs.length > 0 ){
  		        var is_load = $(".admin-thumb-div").length == 0;
    					$.each( response.imgs, function( i, item ){
    						addAnAdminThumb( item, selector, is_load );
    					});
    				}
    				else {
    				  $('#'+selector).html('<div class="admin-no-images">No images have been uploaded</div>');
  			    }
  			  } 
   			},
   			"json"
      );
    };
  
  	var addAnAdminThumb = function ( item, selector, is_load ){
      $(".admin-no-images").fadeOut();
    
      var the_img = "<div class='admin-thumb-div'>"
        + "<div class='admin-thumb-img-wrap'>"
        + '<img class="admin-thumb" src="' + item.data[0] + '" width="' + item.data[1] + '"/>'
        + "</div>"
        + '<div class="clearfix"></div>'
        + '<div class="clearfix"></div>'
        + '<textarea id="for-updatecap-' + item.for_post + '-' + item.id + '" type="text" '
        + ' class="admin-thumb-caption">' + item.caption  + '</textarea>'
        + '<div class="clearfix"></div>'
        + '<div class="admin-thumb-controls">'
        + '<img id="loader-updatecap-' + item.for_post + '-' + item.id + '" src="/wp-admin/images/loading.gif" style="display:none" />'
        + '<input type="button" class="admin-thumb-caption-button" value="update caption" '
        + ' id="updatecap-' + item.for_post + '-' + item.id + '" />'
        + '<input type="button" class="delete-admin-img" id="delete-' + item.for_post + '-' + item.id + '" value="delete" />'
        + '<div class="clearfix"></div>'
        + '</div>'
        + '</div>';

  		$('#'+selector).append( the_img );
    };
  
    var getLastPostlessImg = function( selector, faux_id, size ){
      var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
        $.post(
    	    ajaxurl,
     			{
     			  action: 'last_post_img', 
     			  wp_meta_box_nonce: _wpnonce, 
     			  data: { 
    				post_id : faux_id,
    				size	: size  
    			}
   			},
   			function(response){
    			if( response ){
  			    if( response.imgs && response.imgs.length > 0 ){
  				    $.each( response.imgs, function( i, item ){
    						addAnAdminThumb( item, selector );
    					});
    				}
    				else
    				{
			   
    				}
    			}
   			},
   			"json"
      );
    };
  
  	var getLastPostImg = function( selector ){
  	  var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
        $.post(
    	    ajaxurl,
     			{
     			  action: 'last_post_img', 
     			  wp_meta_box_nonce: _wpnonce, 
     			  data: { 
    				post_id : parseInt( $("#post_ID").val() ),
    				size	: 'thumbnail'  
    			}
   			},
   			function(response){
    			if( response ){
  			    if( response.imgs && response.imgs.length > 0 ){
  				    $.each( response.imgs, function( i, item ){
    						addAnAdminThumb( item, selector );
    					});
    				}
    				else
    				{
			   
    				}
    			}
   			},
   			"json"
      );
  	};
	
  	var updateMap = function(){
      var address = $("#address").val();
      if(''!==address){
        CHP.AdminMap.geocodeAddr(address);
      }
    }
  
  	var geocodeFailed = function(status){
      switch(status){
        case google.maps.GeocoderStatus.ZERO_RESULTS:
          reportError('We could not find a location based on the search text entered.');
        break;
        case google.maps.GeocoderStatus.OVER_QUERY_LIMIT:
            reportError('Please try your search again later.');
        break;
        case google.maps.GeocoderStatus.REQUEST_DENIED:
          reportError('An error occurred while trying to find a location based on the search text entered.');
        break;
        case google.maps.GeocoderStatus.INVALID_REQUEST:
        default:
          reportError('An error occurred while trying to find a location based on the search text entered.');
      }
    };
  
    var reportError = function( string ){
      alert( string ); 
    };
  
    return {
      "init"            : init,
      "getLastPostImg"  : getLastPostImg,
      "addAnAdminThumb" : addAnAdminThumb,
      "geocodeFailed"   : geocodeFailed,
      "getPostImgs"     : getPostImgs,
      "addAnAdminThumb" : addAnAdminThumb,
      "updateMap"       : updateMap
    };
  
  }());

  CHP.AdminMap = (function(){ 
    var bounds, geocoder, maxZoom, mapZoom, initLat, initLng, markers, infoBoxes;
  
    var init = function(){
    
      if( "undefined"=== typeof google) return false;
    
      bounds = new google.maps.LatLngBounds();
      geocoder = new google.maps.Geocoder();
    
      maxZoom = 17;
      mapZoom = 14;
      initLat = 39.962264;
      initLng = -83.000505;
      markers = [];
      infoBoxes = []
    
      $("#address").bind('keydown', function( event ) {
    	    if (event.keyCode == '13') {
        	    return false;
        	}
    	});
  	
      $("#address_update").click(CHP.PostAdmin.updateMap);
      // $("#address").change(CHP.PostAdmin.updateMap);
    
      $('input.delete-admin-img').live('click', function(){
       var delete_id = $(this).attr("id");
       var $this = $(this);

       var _wpnonce = $("input[name=\'wp_meta_box_nonce\']").val();
         $.post(
        ajaxurl,
    			{
    			    action: 'delete_img_for_post', 
    			    wp_meta_box_nonce: _wpnonce, 
    			    data: { 
    			    delete_id : delete_id
    		    }
    			},
    			function(response){
     				if( response && 1 == parseInt(response.result) ){

     					$this.parent().parent().fadeOut( function(){
     					    $this.remove();
     					});
     				}
    			},
    			"json"
          );

     });
     return true;
    };
  
    var loadMap = function(){
    
      if(''!==saved_lat && ''!==saved_lng){  
        mapCenter = new google.maps.LatLng( saved_lat, saved_lng );
      }
      else{
        mapCenter = new google.maps.LatLng( initLat, initLng );
      }
    
      var mapParams = {
        maxZoom: maxZoom,
        zoom: mapZoom, 
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        overviewMapControl : true,
        disableDefaultUI: false,
        disableDoubleClickZoom: true,
      	draggable: !is_single,
      	scrollwheel: !is_single,
        center: mapCenter,
        mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID]} 
      };

      googleMap = new google.maps.Map( document.getElementById("mapCanvas"), mapParams );  
    
      if(''!==saved_lat && ''!==saved_lng){  
        markAdminLatLng( mapCenter );
      }
    
    };
  
    var geocodeAddr = function(address){
    
      if(''!==address && 'Address'!==address){
        geocoder.geocode( {'address': address }, function(results, status) {
  		    if(undefined!==results){
            // $("#mapDebug").append('<p>' + JSON.stringify(results) + '</p>');
		      
  		      var item = results[0];
  		      latLng = item.geometry.location;
  		      $("#lat").val(latLng.lat());
  		      $("#lng").val(latLng.lng());
		      
  		      // $("#mapDebug").append('<p>' + JSON.stringify(item_v) + '</p>');
		      
  		      $.each(item.address_components, function(item_i, item_v){
  		        // $("#mapDebug").append('<p>' + JSON.stringify(item_v) + '</p>');
		        
  	          $.each(item_v.types, function(type_i, type_v){
	            
  	            switch(type_v){
  	              case "locality": case "administrative_area_level_3":
  	                $("#city").val(item_v.long_name);
  	              break;
                  case "administrative_area_level_2":
                  break;
                  case "street_number":
                  break;
                  case "route":
                  break;
  	              case "administrative_area_level_1":
  	                $("#state").val(item_v.long_name);
  	              break;
  	              case "country":
  	                $("#country").val(item_v.long_name);
                    // country = item_v.long_name;
  	              break;
  	              case "postal_code":
  	                $("#postal_code").val(item_v.long_name);
                    // postal_code = item_v.long_name;
  	              break;
  	            }
              });
  	        });
		      
  		      markAdminLatLng(latLng);
  		    }
  		  });
      }
      else{
        $("#mapDebug").html('<p class="alert">Address should not be blank</p>');    
      }
    };
  
    var markAdminLatLng = function(latLng){
      googleMap.panTo(latLng);

      if( undefined!==marker ) marker.setMap(null);

      marker = new google.maps.Marker({
          position: latLng, 
          map: googleMap, 
          title: $("#title").val()
      });
    }
  
    return {
      "init"            : init,
      "loadMap"         : loadMap, 
      "markAdminLatLng" : markAdminLatLng,
      "geocodeAddr"     : geocodeAddr
    };
  
  }());

  $(document).ready(function(){
    CHP.Admin.init();
    CHP.PostAdmin.init();
    if( 'property'===the_post_type ){
      var mapOK = CHP.AdminMap.init();
      if( mapOK )
        CHP.AdminMap.loadMap();
    }
	
	jQuery('.postarea select#development').dropdownchecklist(
  { width:200,
  textFormatFunction: function(options) {
        var selectedOptions = options.filter(":selected");
        var countOfSelected = selectedOptions.size();
        var size = options.size();
        switch(countOfSelected) {
           case 0: return "<i>Select a Development<i>";
           case 1: return selectedOptions.text();
           case options.size(): return "<i>All Developments</i>";
           default: return countOfSelected + " Developments Selected";
        }
    } }
	);
	

  });
  
})(jQuery);
