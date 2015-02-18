var markers = new Array();
var infoBoxes = new Array();
var pane;  
var pane_api;
var maxZoom;
var mapZoom;
var initLat;
var initLng;
var mapCenter;
var googleMap;
var marker;
// var markers = [];
var bounds;
var geocoder;
// var query_data;
var openIDX = -1;

(function($) { 

if ("undefined" === typeof CHP) {
	var CHP = {};
}

Number.prototype.formatMoney = function(c, d, t){
var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
 return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
 
function _e(obj){
  if(undefined!==obj && '...'!=obj && ''!=obj && -1!=obj && 0!=obj) return true;
  return false;
}

function addGreenArrow(map) {
  var greenArrowDiv = document.createElement('DIV');
  greenArrowDiv.id = "green-arrow-overlay";
  greenArrowDiv.style.marginLeft = '10px';

  greenArrowDiv.index = 1;
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(greenArrowDiv);
}

function CustomMarker( params ){ 
  google.maps.OverlayView.call(this);
  this.setValues( params );
  this.setMap( this.map );
}

if( "undefined"!==typeof google)
  CustomMarker.prototype = new google.maps.OverlayView();
else
  CustomMarker.prototype = new Object();
  

CustomMarker.prototype.draw = function() {

  var me = this;

  // Check if the div has been created.
  var div = this.div_;
  if (!div) {

      // Create a overlay text DIV
      div = this.div_ = document.createElement( 'DIV' );
      div.id = this.markerId;
      div.className = "custom-marker";      
      
      if( parseInt(this.text_) < 10 ){
        div.style.padding = "6.5px 0 0 5.4px"
      }
      else{
        div.style.padding = "6.5px 0 0 3.5px"
      }
      
      if( undefined!==this.property.property_type){
        // set up the base class and add others for property types
        var property_type_safe = this.property.property_type.replace(/\W/, '');
        property_type_safe = this.property.property_type.replace(/\s/, '-');
        div.className += ' ' + property_type_safe.toLowerCase();

        var status_safe = this.property.status.replace(/\W/, '');
        status_safe = this.property.status.replace(/\s/, '-');
        div.className += ' ' + status_safe.toLowerCase();
        // div.className += ' ' + this.property.property_type;
      }
      else if(undefined!==property_type && ''!==property_type){
        var status_safe = property_type.replace(/\s/, '-');
        div.className += ' ' + status_safe.toLowerCase();
      }
      
      // if( this.showLabel )
      //        div.innerHTML = this.text_;

        
      // position subsequent items over others
      div.style.zIndex = parseInt(this.text_) + 1;
      
      
      google.maps.event.addDomListener(div, "touchstart", function(event) {
       google.maps.event.trigger(me, "click");
      });
      
      google.maps.event.addDomListener(div, "click", function(event) {
       google.maps.event.trigger(me, "click");
      });
       
      google.maps.event.addDomListener(div, "mouseover", function(event) {
       google.maps.event.trigger(me, "mouseover");
      });
       
      google.maps.event.addDomListener(div, "mouseout", function(event) {
       google.maps.event.trigger(me, "mouseout");
      });
       
      google.maps.event.addDomListener(div, "focus", function(event) {
       google.maps.event.trigger(me, "mouseover");
      });
       
      google.maps.event.addDomListener(div, "blur", function(event) {
       google.maps.event.trigger(me, "mouseout");
      });

      // Then add the overlay to the DOM
      var panes = this.getPanes();
      panes.overlayImage.appendChild(div);
  }

  // Position the overlay
  var projection = this.getProjection();
  var point = projection.fromLatLngToDivPixel( this.latlng_ );
  
  if ( point ) {
    if( undefined!==this.property.status && "Sold"===this.property.status ){
      div.style.left = (point.x - 37.5) + 'px';
      div.style.top = (point.y - 25) + 'px';
    }
    else{
      div.style.left = (point.x - 8) + 'px';
      div.style.top = (point.y) + 'px';
    }
  }
  // alert( div.style.top + ',' + div.style.left);
};

CustomMarker.prototype.getLatLng = function() {
  return this.latlng_;
};

CustomMarker.prototype.getText = function() {
  return this.text_;
};


CustomMarker.prototype.remove = function() {
  // Check if the overlay was on the map and needs to be removed.
  if (this.div_) {
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
    this.setMap(null);			
  }
};


function CustomInfoBox( params ) {
  this.setValues( params );
  this.text = '';
  var div = this.div_ = document.createElement('div');
  div.id = "infobox-" + params.markerID; 
  div.style.cssText = 'position: absolute; display: none';
};

if( "undefined"!==typeof google)
  CustomInfoBox.prototype = new google.maps.OverlayView;
else
  CustomInfoBox.prototype = new Object();
  
/* Implement onAdd */
CustomInfoBox.prototype.onAdd = function() {
  var pane = this.getPanes().overlayImage;
  pane.appendChild(this.div_);

  /* Ensures the InfoBox is redrawn if the text or position is changed. */
  var me = this;
  this.listeners_ = [
    google.maps.event.addListener(this, 'text_changed',
      function() {         
        me.draw();
    })
  ];
};

CustomInfoBox.prototype.onRemove = function() {
 this.div_.parentNode.removeChild(this.div_);

  /* InfoBox is removed from the map, stop updating its position/text. */
  for (var i = 0, I = this.listeners_.length; i < I; ++i) {
    google.maps.event.removeListener(this.listeners_[i]);
  }
};

CustomInfoBox.prototype.draw = function() {  
 var div = this.div_;
   
 // added 2/16/13 - not the most performant strategy javascript-wise
 // but trying to .toString on undefined causes a storm of errors
 // and these prevent proper zooming of the map, as the errors occur
 // lots when the map zoom in or out is tried. 
 try{ 
   if(this.get('text').toString() != ""){
   
     var projection = this.getProjection();
     var position = projection.fromLatLngToDivPixel( this.marker.latlng_ );
   
     div.innerHTML = this.get('text').toString();
    
     div.style.display = 'block';
     div.style.zIndex = '2500';
   
     if ( position ) {

       var ibHeight = ($('#overlay-' + this.marker.id).height());
       if( this.property.status==='Sold' ){
         div.style.top  = (position.y - ibHeight - 13) + 'px';
          div.style.left = (position.x - 153) + 'px';
       }
       else{
         div.style.top  = (position.y - ibHeight) + 'px';
          div.style.left = (position.x - 150) + 'px';
       }
     
     
     }
   
     // var panPoint = new google.maps.Point( (position.y + (ibHeight/2)), (position.x - 75) );
     var panPoint = new google.maps.Point( position.x + (142/2), position.y - (ibHeight/2) );
     var panTo = projection.fromDivPixelToLatLng( panPoint );
     googleMap.panTo( panTo );
   }
   else{
     div.innerHTML = this.get('text').toString();
   }
  } catch(ex){}
  // if(""!==$("#mapCanvas div.abbreviated div.hours-block", sh).first().text())
  //      $("#mapCanvas div.abbreviated div.hours-block", sh).first().prepend('Hours: ');
};
  
CHP.Map = (function(){ 

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
    
    return true;
  };
  
  var markSiteLatLng = function(latLng){
    googleMap.panTo(latLng);

    if( undefined!==marker ) marker.setMap(null);

    var params = {
      property  : {},
      markerId  : "marker-" + (markers.length+1),
      id        : markers.length,
      text_     : markers.length+1,
      latlng_   : latLng,
      showLabel : false,
      map       : googleMap
    };
    
    // var tmp = new google.maps.Marker({
    //       position: cMarkerLatLng, 
    //       map: googleMap, 
    //       title:"Marker!"
    //   });
      
    if( mapOK ){
      var cMarker = new CustomMarker( params ); // v, cMarkerLatLng, googleMap );
    }
    
  }
  
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
      overviewMapControl : false,
      disableDefaultUI: false, /* is_single || is_front_page, */
      disableDoubleClickZoom: true,
    	draggable: !is_single,
    	scrollwheel: !is_single,
      center: mapCenter
    };

    if( !is_single && !is_front_page )
      mapParams.mapTypeControlOptions = {mapTypeIds:[google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID]};

    googleMap = new google.maps.Map( document.getElementById("mapCanvas"), mapParams );  
    
      
    if(''!==saved_lat && ''!==saved_lng){  
      markSiteLatLng( mapCenter );
    }
    
  };
  
  var loadSearchProperties = function(){
    
    // var hash = location.hash;
    
    var _wpnonce = jQuery("div[id=\'nopriv_meta_box_nonce\']").text();
    
    // fetch query data from form, if not first load
    if(undefined===query_data){
      // must be first load, set qd to empty
      query_data = {};
    }
    
    $.post(
       ajaxEP.ajaxurl,
       {
           action: 'queryprops', 
           _wpnonce: _wpnonce, 
           data: query_data
       },
       function( response ){
        
        if( response.result == 1 ){
          if( response.properties && response.properties.length > 0 ){
            
            var prop_count_display = (response.properties.length==1) ? 'property' : 'properties';
            $("#property-search-count").html( response.properties.length + ' '+ prop_count_display + ' ' + ' found' ); // .fadeIn();
            
           // plot the returned properties
           plotSearchProperties( response.properties );
           
           var preFetch = window.location.hash.replace(/#/, '');

           if(''!==preFetch){
             // we have a property to open after load
             openIDX = preFetch-1;
             infoBoxes[openIDX].set( "text", infoBoxes[openIDX].html ); // infoBoxText );
             pane_api.scrollToElement('#property-'+openIDX, true, true);
           }
           
          }
          else{
           $("#property-search-count").html( '' );
           var scrollDiv = '<div class="property-listing">';
           scrollDiv += '' +
              '<div class="property-body none-found">' +
                'No properties matched your search.' +
              '</div>' +
           '</div>';
            
            pane_api.getContentPane().html(scrollDiv);
            pane_api.reinitialise();
            
          }
        }

       },
       "json"
     );
    
    
  };
  
  var formatPrice = function(price, property_type){
    // 
    var ret = new Number(price).formatMoney(2, '.', ',');
    if( "0.00"==ret ) return 'Call For Price';
    
    switch(property_type){
      case "Rent":
        ret = "$"+ret+' / month';
      break;
      case "Sale":
        ret = "$"+ret
      break;
    }
    return ret;
  };
  
  var plotSearchProperties = function( properties ){
    $("#propertySearchCanvas #mapCanvas").css({display: "block"});
    google.maps.event.trigger(googleMap, 'resize');

    var scrollDiv = '';
    $.each( properties, function(i, v){
      
      if( mapOK ){
        var cMarkerLatLng = new google.maps.LatLng( v.lat, v.lng );
        bounds.extend( cMarkerLatLng );
      }
      
      var params = {
        property  : v,
        markerId  : "marker-" + (markers.length+1),
        id        : markers.length,
        text_     : markers.length+1,
        latlng_   : cMarkerLatLng,
        showLabel : true,
        map       : googleMap
      };
      var conNumber = '';
      if(typeof v.contact_number != 'undefined'){
         conNumber = '<div class="property-baths"><b>Contact Number:</b> ' + v.contact_number + '</div>';
      }
      
      
      // var tmp = new google.maps.Marker({
      //       position: cMarkerLatLng, 
      //       map: googleMap, 
      //       title:"Hello World!"
      //   });
        
      if( mapOK ){
        var cMarker = new CustomMarker( params ); // v, cMarkerLatLng, googleMap );
        v.markerId = cMarker.id;
        storeSearchMarker( cMarker, v );
      }
      
      scrollDiv += '<div class="property-listing">';
      scrollDiv += '' +
        '<div class="property-body">' +
          // '<a name="' + i + '">' + 
          
          // '&nbsp;&nbsp;|&nbsp;&nbsp;' + v.address + 
          '<div class="property-details">' +
            '<div class="property-title"><a href="#" class="property-name" rel="' + i + '" id="property-' + i + '">' + v.name + '</a></div>' +
            '<div class="property-address">' + v.address + '</div>' +
            '<div class="property-beds"><b>Bedrooms:</b> ' + v.num_beds + '</div>' +
            '<div class="property-baths"><b>Baths:</b> ' + v.num_baths + '</div>' +
            conNumber +
            '<div class="property-more"><a href="' + v.permalink  + '"><u>More Info &raquo;</u></a></div>' +
            '' + 
            '</div>' +
            '<div class="property-desc">' +
              (_e(v.price) ? '<div class="property-excerpt">' + formatPrice(v.price, v.property_type) + '</div>' : 'Price currently unavailable. Please call.' ) + '<br/>' +
              (_e(v.excerpt) ? '<div class="property-excerpt">' + v.excerpt + '</div>' : 'No description available.' ) +
            '</div>' +
          '</div>' +
          // ('Sold'==v.status) ? '<img src="/wp-content/themes/chp/sold.png" />' : '' + 
          '<div class="property-img"><a href="' + v.permalink  + '">' +
          // (_e(v.img_src) ? '<img src="' + v.img_src  + '" />' : '<img src="/wp-content/themes/chp/images/no-image.scroll.gif" />' )+
          (_e(v.img_src) ? '<img src="' + v.img_src  + '" />' : '' )+
          '</a></div>' +
        '</div>' +
        '<div class="clearfix"></div>' +
      '</div>' +
      '<div class="clearfix"></div>';
      
    });
    
    CHP.ScrollPane.add(scrollDiv);
    
    // if(undefined===pane_api){
    //   $("#propertySearchScroll").html(scrollDiv);
    //   pane = $('#propertySearchScroll').jScrollPane({
    //     showArrows: true
    //   });
    //   $('#propertySearchScroll').addClass('bordered');
    //   pane_api = pane.data('jsp');
    // }
    // else{
    //   //pane_api.getContentPane().html(scrollDiv).fadeIn('fast', function(){
    //     pane_api.getContentPane().html(scrollDiv);
    //     pane_api.reinitialise();
    //   // });
    // }
    
    if( mapOK )
      googleMap.fitBounds( bounds );
  };
  
  var storeSearchMarker = function(marker, propertyData ){
    markers.push( marker );
    // var safe_price = propertyData.price.replace(/\W/, '');
    var safe_price = formatPrice(propertyData.price, propertyData.property_type)
    // (_e(v.price) ? '<div class="property-excerpt">' + formatPrice(v.price, v.property_type) + '</div>' : 'Price currently unavailable. Please call.' ) + '<br/>' +
    // (_e(v.excerpt) ? '<div class="property-excerpt">' + v.excerpt + '</div>' : 'No description available.' ) +
    
    // if( safe_price == '0.00' ) safe_price = 'Please Call';
    var pic_label = 'Picture';
    if( 0==propertyData.img_count || propertyData.img_count > 1 ){
      pic_label += 's';
    } 
    var property_type_safe = propertyData.property_type.replace(/\W/, '');
    property_type_safe = propertyData.property_type.replace(/\s/, '-');
    var overlayClassName = property_type_safe.toLowerCase();
    
    var infoBoxText = '<div class="custom-overlay ' + overlayClassName + '" id="overlay-' + marker.id + '">' +
      '<div class="custom-overlay-top"><!-- ie6 hack --></div>' +
      '<div class="custom-overlay-close" title="Close this window">x</div>' +
        '<div class="custom-overlay-inner">' +
          '<div class="custom-overlay-inner-img">' +
            (_e(propertyData.img_src) ? '<img src="' + propertyData.img_src  + '" /><br/>' : '<a href="' + propertyData.permalink + '"><img src="/wp-content/themes/chp/images/no-image.overlay.gif" /></a>') + '' +
            (_e(propertyData.permalink && propertyData.img_count > 0) ? '<div class="custom-overlay-inner-morepics"><a href="' + propertyData.permalink + '">' + propertyData.img_count + ' ' +  pic_label + '</a></div>' : '' ) +
          '</div>' +
          '<div class="custom-overlay-inner-right">' +
            (_e(propertyData.name) ? '<p><strong><a href="' + propertyData.permalink + '">' + propertyData.name + '</a></strong></p>' : '') +
            (_e(safe_price) ? '<p>' + safe_price + '</p>' : 'Call For Price') +
            (_e(propertyData.address) ? '<p class="address">' + propertyData.address.replace(/,\s+USA/, '') + '</p>' : '') +
            (_e(propertyData.num_beds) ? '<p class="half"><b>Bedrooms:</b> ' + propertyData.num_beds + '</p>' : '') +
            (_e(propertyData.num_baths) ? '<p class="half last"><b>Baths:</b> ' + propertyData.num_baths + '</p>' : '') +
            (_e(propertyData.development) ? '<p>' + propertyData.development + '</p>' : '') +
          '</div>' +
        '</div>' +
      '<div class="custom-overlay-bottom"></div>' +
    '</div>';
    
    // propertyData.
    var params = {
			map       : googleMap,
			marker    : marker,
			//markerID  :       markers.length, // propertyData.id, // 
			property  : propertyData,
			html      : infoBoxText
		};
		
    var infoBox = new CustomInfoBox( params );
		
		// infoBox.bindTo('position', marker, 'position');
		infoBoxes.push( infoBox );
    
    bounds.extend( marker.getLatLng() );
    
    // if( marker.property.status!=='Sold' ){
      google.maps.event.addListener(marker, "click", function(e){  
        closeOpenOverlays();
        openIDX = marker.id;
        infoBoxes[marker.id].set( "text", infoBoxes[marker.id].html ); // infoBoxText );
        pane_api.scrollToElement('#property-'+openIDX, true, true);
      });
    // }
    
  };
  
  var clearMap = function(){
    if(!mapOK) return;
    
    for(var i = 0; i < markers.length; i++) {
      // markers[i].setMap(null);
      markers[i].remove();
    }
    markers = new Array();
    infoBoxes = new Array();

    bounds = new google.maps.LatLngBounds();
    openIDX = -1;
    
  };
  
  var closeOpenOverlays = function(){
    if(openIDX!==-1 && undefined!==infoBoxes[openIDX]){
      infoBoxes[openIDX].set("text", "");
      openIDX=-1;
    }
  };
  
  var loadDevelopmentProperties = function(){
    // alert( 'loadDevelopmentProperties' );
    
    if(!_e(the_post_title) || "developments" != the_post_type) return;
    
    var _wpnonce = jQuery("div[id=\'nopriv_meta_box_nonce\']").text();
    query_data = {
      development : the_post_title
    };
    
    $.post(
       ajaxEP.ajaxurl,
       {
           action: 'queryprops', 
           _wpnonce: _wpnonce, 
           data: query_data
       },
       function( response ){
        
        if( response.result == 1 ){
          if( response.properties.length > 0 ){
           // plot the returned properties
           plotFrontProperties( response.properties );
          }
        }
       },
       "json"
     );
  };
  
  var loadFrontProperties = function(){
    var _wpnonce = jQuery("div[id=\'nopriv_meta_box_nonce\']").text();
    
    query_data = {};
    
    $.post(
       ajaxEP.ajaxurl,
       {
           action: 'queryprops', 
           _wpnonce: _wpnonce, 
           data: query_data
       },
       function( response ){
        
        if( response.result == 1 ){
          if( response.properties.length > 0 ){
           // plot the returned properties
           plotFrontProperties( response.properties );
          }
        }
       },
       "json"
     );
    
  };
  
  var plotFrontProperties = function( properties ){
    $("#fp-property-map-map #mapCanvas").css({display: "block"});
    google.maps.event.trigger(googleMap, 'resize');
    
    $.each( properties, function(i, v){
      var cMarkerLatLng = new google.maps.LatLng( v.lat, v.lng );
      bounds.extend( cMarkerLatLng );
      
      var params = {
        property  : v,
        markerId  : "marker-" + markers.length+1,
        id        : i, // markers.length,
        text_     : i+1, // markers.length+1,
        latlng_   : cMarkerLatLng,
        showLabel : false,
        map       : googleMap
      };
      
      var cMarker = new CustomMarker( params ); // v, cMarkerLatLng, googleMap );
      
      storeFrontMarker( cMarker );
      
      googleMap.fitBounds( bounds );
    });
  };
  
  
  var storeFrontMarker = function(marker){
    
    markers.push( marker );
    		
    bounds.extend( marker.getLatLng() );
    
    if( marker.property.status!=='Sold' ){
      google.maps.event.addListener(marker, "click", function(e){  
        var preFetch = marker.id+1;
        document.location.href='/property-search/#' + preFetch;
      });
    }
  };
  
  var openInfoBox = function( markerNumber ){
    
    googleMap.panTo( markers[markerNumber].getLatLng() );
    setTimeout(function(){
			infoBoxes[markerNumber].set("text", "foo");	// infoBoxes[markerNumber].html
		}, 100);
    
  };

  
  return {
    "init"                      : init,
    "loadMap"                   : loadMap,
    "loadFrontProperties"       : loadFrontProperties, 
    "loadSearchProperties"      : loadSearchProperties,
    "loadDevelopmentProperties" : loadDevelopmentProperties,
    "plotSearchProperties"      : plotSearchProperties,
    "storeFrontMarker"          : storeFrontMarker,
    "storeSearchMarker"         : storeSearchMarker,
    "closeOpenOverlays"         : closeOpenOverlays,
    "clearMap"                  : clearMap
  };
  
}());

CHP.Form = (function(){
  
  var submitSearch = function(){
    
    window.location.hash = ''; // clear any preFetch id out
    CHP.Map.closeOpenOverlays();
    CHP.Map.clearMap();
    CHP.ScrollPane.clear();
        
    var qs = $("#search-form").serialize();
    
    var adv_qs = $("#adv-search-form").serialize(); 
    
    // window.location.hash = qs + "&" + adv_qs;
    
    // alert( qs + "&" + adv_qs );
    
    query_data = qs + "&" + adv_qs;
  
    
    CHP.Map.loadSearchProperties();
    return false;
  };
    
  var init = function(){
    
    $('#rsvp-submit').click(function(){
      if(""===$("#email").val()){
        $("#email").css({backgroundColor:"#ffffcc"}).focus().bind('blur', function(){
          if(""!==$(this).val())
            $(this).css({backgroundColor:"#ffffff"});
        });
        return false;
      }

      if(""===$("#name").val()){
        $("#name").css({backgroundColor:"#ffffcc"}).focus().bind('blur', function(){
          if(""!==$(this).val())
            $(this).css({backgroundColor:"#ffffff"});
        });
        return false;
      }
      
      
      $("img.left-of-ajaxer", $("#event-rsvp")).fadeIn();
      var _wpnonce = jQuery("div[id=\'nopriv_meta_box_nonce\']").text();
      var data = {
        email : $("#email").val(),
        name : $("#name").val(),
        post_id: post_id
      };
      $("#event-rsvp-done").removeClass('err');
      
      $.post(
         ajaxEP.ajaxurl,
         {
           action: 'rsvp', 
           _wpnonce: _wpnonce, 
           data: data
         },
         function( response ){
           $("img.left-of-ajaxer", $("#event-rsvp")).fadeOut();
           
           $("#event-rsvp").slideUp();
           $("#event-rsvp-done").slideDown();
           
           if( response.result == 1 ){
              // signup OK
              $("#event-rsvp-done").html(rsvp_ok);
              $("#email").val('');
              $("#name").val('');
            }
            else{
              // signup not so OK
              $("#event-rsvp-done").addClass('err').html(rsvp_not_ok);
            }
         },
         "json"
       );
       
      
    });
    
    $('#search-form').submit(function() { 
        // submit the form 
        // $(this).ajaxSubmit(); 
        // return false to prevent normal browser submit and page navigation 
        return false; 
    });

    $("#property-price-low #property-price-high, #property-zip, #property-beds, #property-baths").bind('keydown', function( event ) {
  	    if (event.keyCode == '13') {
      	  CHP.Form.submitSearch();
      	  return false;
      	}
  	});
  	
    $("#vol-form-submit").click(function(){
      
      if(""===$("#email").val()){
        $("#email").focus();
        return false;
      }
      
      if(""===$("#vol-name").val()){
        $("#vol-name").focus();
        return false;
      }
      
      
      $("#pub-volunteer-form").submit();
      return;
      var vol_qs = $("#pub-volunteer-form").serialize();
      
      var _wpnonce = jQuery("div[id=\'nopriv_meta_box_nonce\']").text();
      var data = {
        email : $("#email").val(),
        name : $("#vol-name").val()
      };
       
      $("#img-loading").fadeIn();
      $.post(
         ajaxEP.ajaxurl,
         {
            action: 'volunteer_signup', 
            _wpnonce: _wpnonce, 
            data: vol_qs
          },
         function( response ){
           $("#img-loading").fadeOut();
           if( response.result == 1 ){
              // signup OK
              
              // $("#email").val('');
              // $("#name").val('');
              $("#vol-reposnse").html('Thank you for your interest in volunteering.');
            }
            else{
              // signup not so OK
              $("#vol-reposnse").addClass('err').html('An error occurred. Please contact us to sign up as a volunteer.');
            }
         },
         "json"
       );
    });
  
    // $(".prompt-text").each(function() {
    //   $(this).attr("title", $(this).siblings("label").text());
    //     if ($(this).val() == "") {
    //         $(this).siblings("label").each(function() {
    //             $(this).css("visibility", "");
    //         })
    //     }
    // });
    //   
    // $(".fancy-label").each(function() {
    //     $(this).click(function() {
    //         $(this).css("visibility", "hidden").siblings("input").each(function() {
    //            $(this).focus();
    //         });
    //     });
    // });
    //   
    // $(".prompt-text").each(function() {
    //     $(this).blur(function() {
    //   
    //         if (this.value == "") {
    //             $(this).siblings(".fancy-label").first().css("visibility", "")
    //         }
    //   
    //     }).focus(function() {
    //         $(this).siblings(".fancy-label").first().css("visibility", "hidden")
    //     }).keydown(function(g) {
    //         $(this).siblings(".fancy-label").first().css("visibility", "hidden");
    //         // $(this).unbind(g)
    //     });
    // });
    
    
    
    return true;
  };
  
  return {
    "init"                  : init,
    "submitSearch"          : submitSearch
  };
  
}());


CHP.Site = (function(){ 
  
  var init = function(){
    
     if( WPS.Client.isIPad() ){
        $(".flash-navigator").css('display', 'none');
        
     }
   
    setTimeout(function(){
      $('#masonry-container').masonry({
        itemSelector: '.masonry-box'
      });
    }, 150);
    
    $("a#adv-search").bind('click',function(){   
      $("#search-adv-dialog").dialog('open');
      return false;
    });
    
    $('#search-submit').bind('click', CHP.Form.submitSearch);
    
    $("#search-adv-dialog").dialog({
      resizable: false,
      autoOpen: false,
			height:400,
			width:700,
			modal: true,
			title: 'Advanced Search',
			buttons: {
				"Cancel": function(){
				  // TO DO: clear form
				  $( this ).dialog( "close" );
				},
				"Search": function() {
				  CHP.Form.submitSearch();
					$( this ).dialog( "close" );
				}
			}
    });
      
    $(".custom-overlay-close").live('touchstart click', function() {
      CHP.Map.closeOpenOverlays();
    });
    
    $('a.property-name', "#propertySearchScroll").live('touchstart click', function() {
      CHP.Map.closeOpenOverlays();
      openIDX = $(this).attr('rel');
      infoBoxes[openIDX].set( "text", infoBoxes[openIDX].html ); 
      return false;
    });
    
    return true;
  };
  
  
  var initSlider = function(){
    
      $(window).load(function() {
          var cnThumbs = ($('#slider img').length > 1);
          var running = ($('#slider img').length > 1);
          var ptime = $('#slider img').length > 1 ? 5000 : 5000000;

          // if( $('#slider img').length > 1 )
            $('#slider').nivoSlider({
             effect:'fade',
             running:running,
             animSpeed:1000,
             pauseTime:ptime,
             pauseOnHover:true,
             controlNavThumbs:cnThumbs,
             controlNavThumbsFromRel:false,
             controlNav:true,
             captionOpacity:1,
             beforeChange: function(){
               var total = $('#slider img').length;
                // alert(total);
                if(total<2){
                  $('#slider').data('nivo:vars').stop = true;
                  // $(".nivo-directionNav").unbind();
                  // $('#slider').hover(function(){ return false; });
                  return false;
                }
             },
             afterChange: function(){
                if ($('#slider .nivo-caption p').length <= 0){
                  $('.nivo-caption').css("opacity", "0");
                }
             },
             afterLoad: function(){
               var total = $('#slider img').length;
               // alert(total);
               if(total<2){
                 $('#slider').data('nivo:vars').stop = true;
                 // $(".nivo-directionNav").unbind();
                 // $('#slider').hover(function(){ return false; });
               } 
             }
            });
          // }
          
          // post-thumb-wrap
          $("#exhibition-thumb-wrap").append( $(".nivo-controlNav").first() )
          
          });
      
    };
    
    return {
      "init"          : init,
      "initSlider"    : initSlider
  };
  

}());

CHP.ScrollPane = (function(){ 

  var init = function(){
    pane = $('#propertySearchScroll').jScrollPane({
      showArrows: true
    });
    
    $('#propertySearchScroll').addClass('bordered');
    pane_api = pane.data('jsp');
  };
  
  var add = function(html){
    pane_api.getContentPane().html( html );
    pane_api.reinitialise();
  };
  
  var clear = function(){
    $("#propertySearchScroll").removeClass('bordered');
    pane_api.getContentPane().html('');
  };
  
  
  return {
    "init"    : init,
    "clear"   : clear, 
    "add"     : add
  };
}());


$(document).ready(function(){
  
  CHP.Site.init();
  CHP.Site.initSlider();
  CHP.Form.init();
  CHP.ScrollPane.init();
  if($("#mapCanvas").length>0){
    mapOK = CHP.Map.init();
    if( mapOK ){
      // loads the map, also handles single instance markers
      CHP.Map.loadMap();
      // home page map, we're on the home page. get the properties for that map
      
    } // mapOK
    if($("#fp-property-map-map").length>0){
      addGreenArrow( googleMap );
      CHP.Map.loadFrontProperties();
    }
    if($("#dev-property-map-map").length>0){
      addGreenArrow( googleMap );
      CHP.Map.loadFrontProperties();
    }
    else if($("#development-property-map-map").length>0){
      addGreenArrow( googleMap );
      CHP.Map.loadDevelopmentProperties();
    }
    else if($("#propertySearchCanvas").length>0){ 
      CHP.Map.loadSearchProperties();
    }
  }

});

})(jQuery);

function clickclear(thisfield, defaulttext) {
	if (thisfield.value == defaulttext) {
		thisfield.value = "";
	}
}
function clickrecall(thisfield, defaulttext) {
	if (thisfield.value == "") {
		thisfield.value = defaulttext;
	}
}