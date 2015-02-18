/** @description */
var maxZoom;
var mapZoom;
var initLat;
var initLng;
var mapCenter;
var googleMap;
var markers;
var bounds;
var infoBoxes;

var testLatLngs;

if ("undefined" === typeof CHP) {
	var CHP = {};
}


function CustomMarker( markerText, latlng, map ){ // markerText,
    google.maps.OverlayView.call(this);
    this.markerId = parseInt(markerText);
    this.text_ = parseInt(markerText)+1;
    this.latlng_ = latlng;

    // Once the LatLng and text are set, add the overlay to the map.  This will
    // trigger a call to panes_changed which should in turn call draw.
    this.setMap( map );
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.getMarkerId = function(){
  return parseInt( this.markerId );
}

CustomMarker.prototype.draw = function() {
  var me = this;

  // Check if the div has been created.
  var div = this.div_;
  if (!div) {
      // Create a overlay text DIV
      div = this.div_ = document.createElement( 'DIV' );
      // Create the DIV representing our CustomMarker
      // div.style.border = "0px solid none";
      // div.style.position = "absolute";
      // div.style.paddingLeft = "0px";
      // div.style.cursor = 'pointer';

      if(''!=this.text_)
        div.id = "marker-" + this.text_;
      else
        div.id = "marker-" + this.latlng_.lat(3) + "-" + this.latlng_.lng(3);

      div.className = "custom-marker";      

  	  div.innerHTML = this.text_;
  	  
  	  if(1===this.text_){
  		  div.style.zIndex = 2;
  	  }
      
      
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
  var point = projection.fromLatLngToDivPixel(this.latlng_);
  if ( point ) {
      div.style.left = point.x + 'px';
      div.style.top = point.y + 'px';
  }

};

CustomMarker.prototype.getLatLng = function() {
  return this.latlng_;
};

CustomMarker.prototype.getText = function() {
  return this.text_;
};

CustomMarker.prototype.goWhite = function() {
  $('#marker-' + this.text_).css({
    'background-position': "0 -33px", 
    'z-index': 2,
    color: "#155d99"
  });	
};

CustomMarker.prototype.goBlue = function() {
  $('#marker-' + this.text_).css({
    'background-position': "0 0", 
    'z-index': 1,
    color: "#fff"
  });	
  
};

CustomMarker.prototype.remove = function() {
  // Check if the overlay was on the map and needs to be removed.
  if ( this.div_ ) {
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
};


function CustomInfoBox( params ) {
  this.setValues( params );
  this.markerID = params.markerID;
  this.html = params.html;
  var div = this.div_ = document.createElement('div');
  div.id = "infobox-" + this.markerID; 
  div.style.cssText = 'position: absolute; display: none';
};

CustomInfoBox.prototype = new google.maps.OverlayView;

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
     
 if(this.get('text').toString() != ""){
    var projection = this.getProjection();
    var position = projection.fromLatLngToDivPixel(this.get('position'));
   
    div.innerHTML = this.get('text').toString();
    // div.style.top = position.y - ($('#infobox-' + this.markerID).height()-4) + 'px';
    //     div.style.left = position.x - 166 + 'px';
    //     
    div.style.display = 'block';
    div.style.zIndex = '100';
      
 }
 else{
   div.innerHTML = this.get('text').toString();
 }
  // if(""!==$("#mapCanvas div.abbreviated div.hours-block", sh).first().text())
  //      $("#mapCanvas div.abbreviated div.hours-block", sh).first().prepend('Hours: ');
};


CHP.Map = (function(){ 

  var init = function(){
    bounds = new google.maps.LatLngBounds();
    maxZoom = 17;
    mapZoom = 14;
    initLat = 39.962264;
    initLng = -83.000505;
    markers = [];
    infoBoxes = []
    
    testLatLngs = [];
    for(var i=0;i<15;i++){
      testLatLngs.push( new google.maps.LatLng(initLat+Math.random(), initLng+Math.random() ) );
    }
  };
  
  var initMap = function(){
    
    mapCenter = new google.maps.LatLng( initLat, initLng );
    
    var mapParams = {
      maxZoom: maxZoom,
      zoom: mapZoom, 
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false,
      overviewMapControl : true,
      disableDefaultUI: false,
      disableDoubleClickZoom: true,
    	draggable: true,
    	scrollwheel: true,
      center: mapCenter,
      mapTypeControlOptions: {mapTypeIds:[google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID]} 
    };

    googleMap = new google.maps.Map( document.getElementById("mapCanvas"), mapParams );  
    
    
  };
  
  var markerTest = function(){
    // var marker = new CustomMarker( new google.maps.LatLng( initLat, initLng ) , googleMap );
    
    for(var i=0;i<15;i++){
      var marker = new CustomMarker( i, testLatLngs[i], googleMap );
      storeMarker( marker );
    }
    
    googleMap.panTo( bounds.getCenter() );
    googleMap.fitBounds( bounds );
  };
  
  var storeMarker = function(marker){
    
    markers.push( marker );
    
    var infoBoxText = '<div class="infobox-wrap">' +
      marker.getText() +
    '</div>';
    
    var infoBox = new CustomInfoBox({
			map:      googleMap,
			markerID: markers.length,
			html:     infoBoxText
		});
		
		infoBox.bindTo('position', marker, 'position');
		infoBox.set("text", markers.length);
		
		infoBoxes.push( infoBox );
		
    bounds.extend( marker.getLatLng() );
    
    
    google.maps.event.addListener(marker, "click", function(e){  
      this.goWhite();
      // alert( this.getMarkerId() );
      openInfoBox( marker.getMarkerId() );
    });
    
    google.maps.event.addListener(marker, "mouseover", function(e){  
      this.goWhite();
    });
    
    google.maps.event.addListener(marker, "mouseout", function(e){  
      this.goBlue();
    });
    
    // google.maps.event.addListener(marker, "click touchstart", function(e){ 
    //   // alert( marker.getText() );
    // });
  };
  
  var openInfoBox = function( markerNumber ){
    
    googleMap.panTo( markers[markerNumber].getLatLng() );
    setTimeout(function(){
			infoBoxes[markerNumber].set("text", "foo");	// infoBoxes[markerNumber].html
		}, 100);
    
  };
  
  return {
    "init"          : init,
    "initMap"       : initMap,
    "markerTest"    : markerTest
  };
  
}());


$(document).ready(function(){
  CHP.Map.init();
  CHP.Map.initMap();
  CHP.Map.markerTest();
});