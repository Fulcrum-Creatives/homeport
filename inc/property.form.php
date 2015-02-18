<?php
	global $development_types;
	global $wpdb;
	$wpdb->flush();
	$all_types        = get_option('property_type');
	// ThemeUtil::mpuke($all_types);
	
	$parts_of_town     = get_option('part_of_town');
	
	$custom           = get_post_custom( $post->ID );
	// ThemeUtil::mpuke($custom);
	
	if( $custom ){
	  $part_of_town 		        = $custom["part_of_town"][0];

  	$address 		        = $custom["address"][0];
  	$lat 		            = $custom["lat"][0];
  	$lng     		        = $custom["lng"][0];
  	$city     		        = $custom["city"][0];
  	$state     		        = $custom["state"][0];
  	// $country     		        = $custom["country"][0];
  	$postal_code     		        = $custom["postal_code"][0];

  	$property_type 		        = $custom["property_type"][0];

  	if( 'Sale'== $property_type){
  	 $sales_only = "block";
  	 $rental_only = "none";
  	}
  	else{
  	  $sales_only = "none";
    	$rental_only = "block";
  	}


  	$all_occupancy_types = get_option('occupancy_type');
  	$occupancy_type   = $custom["occupancy_type"][0];

  	$neighborhood 		    = $custom["neighborhood"][0];

  	$featured 		    = $custom["featured"][0];
  	$square_footage   = $custom["square_footage"][0];
  	$stories          = $custom["stories"][0];
  	$age              = $custom["age"][0];

  	$num_beds         = $custom["num_beds"][0];
  	$num_baths        = $custom["num_baths"][0];

    $contact_number         = $custom["contact_number"][0];

  	$price            = $custom["price"][0];

  	$parking          = $custom["parking"][0];
  	$fridge           = $custom["fridge"][0];
  	$oven             = $custom["oven"][0];
  	$dwasher          = $custom["dwasher"][0];

  	$forced_air          = $custom["forced_air"][0];
  	$central_air          = $custom["central_air"][0];
  	$central_heat          = $custom["central_heat"][0];

  	$near_shopping          = $custom["near_shopping"][0];
  	$near_school          = $custom["near_school"][0];
  	$near_transit          = $custom["near_transit"][0];

  	$handicap_accessible          = $custom["handicap_accessible"][0];
  	$lease_option          = $custom["lease_option"][0];
  	$aware_compliant          = $custom["aware_compliant"][0];

  	$neighborhood_types        = get_option('neighborhood_type');
  	$neighborhood          = $custom["neighborhood"][0];

    // $development_args = array(
    //  'post_type'=>'developments',
    //  'orderby'=> 'post_title',
    //  'order'=> 'ASC'
    // );
    // $development_types = array();
    // $developments = get_posts( $development_args );
    // // 
    // // ThemeUtil::mpuke_ta($developments);
    // foreach( $developments as $development ){
    //     $development_types[] = $development->post_title;
    // }

  	// $development_types        = get_option('development_type');

  	$garage_types        = get_option('garage_type');
  	$garage          = $custom["garage"][0]; // 

	$development_arr = array();
	if(is_array($custom['development'])) {
		foreach($custom["development"] as $dev) {
			$development_arr[] = $dev;
		}
	}
	
  	$development          = $custom["development"][0]; // 

  	$status_types        = get_option('status_type');
  	$status               = $custom["status"][0]; // 

  	$project_types        = get_option('project_type');
  	$project_type          = $custom["project_type"][0];  // 

  	$wd_hookup       = $custom["wd_hookup"][0]; 
    $laundry       = $custom["laundry"][0];
    $balcony       = $custom["balcony"][0];
    $deck       = $custom["deck"][0];
    $patio       = $custom["patio"][0];
    $yard       = $custom["yard"][0];
    $hardwood_floors       = $custom["hardwood_floors"][0];


    $cable       = $custom["cable"][0];
    $resident_services       = $custom["resident_services"][0];
    $playground       = $custom["playground"][0];

    $management_company       = $custom["management_company"][0];
    $management_phone       = $custom["management_phone"][0];
    $management_web       = $custom["management_web"][0];

    $alarm_system       = $custom["alarm_system"][0];

    $tax_incentives       = $custom["tax_incentives"][0];
    $first_floor_master       = $custom["first_floor_master"][0];
    $dining_room       = $custom["dining_room"][0];
    $laundry_room       = $custom["laundry_room"][0];

    $pets_allowed       = $custom["pets_allowed"][0];
    $senior_community       = $custom["senior_community"][0];
    $paid_utilities       = $custom["paid_utilities"][0];
    $short_term_lease       = $custom["short_term_lease"][0];
    $on_site_maintenance       = $custom["on_site_maintenance"][0];
    $alarm_system       = $custom["alarm_system"][0];
    $alarm_system       = $custom["alarm_system"][0];

    $family_room       = $custom["family_room"][0];

    $den_office       = $custom["den_office"][0];
    $fireplace       = $custom["fireplace"][0];


    $cul_de_sac       = $custom["cul_de_sac"][0];
    $corner_lot       = $custom["corner_lot"][0];
    $lot_size       = $custom["lot_size"][0];
    $basement       = $custom["basement"][0];
	}
	
  
	$nonce = wp_create_nonce( NONCE_STRING );
	
  // $test_idx = ThemeAdmin::get_idx('forced_air', '1');
  // ThemeUtil::mpuke($test_idx);
?>
<div id="admin-reposition">
  <input type="hidden" name="wp_meta_box_nonce" value="<?php echo $nonce; ?>" />
  <div class="custom-admin">
    <!-- <div class="postarea" id="featured-on-homepage" style="float:left;width:50%;"> 
    
          <input type="checkbox" name="featured" id="featured" value="1" <?php
            if(1==$featured) echo " CHECKED ";
          ?>/>
          <label for="featured">Featured on Homepage</label>&nbsp;
        </div> -->
  	<div class="postarea" style="float:left;width:50%">
      <div class="titlediv"> <!-- style="margin-top:18px"> -->
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" for="price">Price</label>
    		<input type="text" id="price" name="price" class="prompt-text"
    			value="<?php echo $price; ?>" />
    	</div>
    </div>
  	<div class="clearfix"></div>
  	<div class="postarea" style="float:left;width:58%;margin-right:6%">
  	  <h2>Address</h2>
    	<div class="titlediv"> <!-- style="margin-top:18px"> -->
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden;font-size:10pt" for="address">Address</label>
    		<input type="text" id="address" name="address" class="prompt-text"
    			value="<?php echo $address; ?>" style="width:84%" />
    		<input type="button" name="address_update" id="address_update" value="update" style="width:14%"/>
    	</div>
    	<div id="mapCanvas-wrap">
    	  <div id="mapCanvas" class="mapCanvas"></div>
  	  </div>
  	  <div id="mapDebug"></div>
  	  <input type="hidden" name="lat" id="lat" value="<?php echo $lat; ?>"/>
  	  <input type="hidden" name="lng" id="lng" value="<?php echo $lng; ?>"/>
  	  <input type="hidden" name="city" id="city" value="<?php echo $city; ?>"/>
  	  <input type="hidden" name="state" id="state" value="<?php echo $state; ?>"/>
  	  <input type="hidden" name="postal_code" id="postal_code" value="<?php echo $postal_code; ?>"/>
  	</div>
  	<div class="postarea" style="float:left;width:36%;text-align:left">
  	  <h2>Details</h2>
  	  <div class="postarea">
      		<select name="neighborhood" id="neighborhood">
      		  <option value="">Neighborhood</option>
      		  <?php foreach( $neighborhood_types as $n_type ): ?>
      				<option value="<?php echo $n_type; ?>"
      					<?php
      						if( $n_type == $neighborhood )
      							echo ' SELECTED ';
      					?>>
      					<?php echo $n_type; ?></option>

      			<?php endforeach; ?>
      		</select>
    	</div>
  	  <div class="postarea">
      		<select multiple='multiple' name="development[]" id="development">
      		  <option value="">Development</option>
      		  <?php foreach( $development_types as $d_choice ): ?>
      				<option value="<?php echo $d_choice; ?>"
      					<?php
      						if(is_array($development_arr) && in_array($d_choice, $development_arr)) {
      							echo ' SELECTED="selected" ';
							}
      					?>>
      					<?php echo $d_choice; ?></option>

      			<?php endforeach; ?>
      		</select>
    	</div>
  	  <div class="postarea">
      		<select name="status" id="status">
      		  <option value="">Property Status</option>
      		  <?php foreach( $status_types as $status_type ): ?>
      				<option value="<?php echo $status_type; ?>"
      					<?php
      						if( $status_type == $status )
      							echo ' SELECTED ';
      					?>>
      					<?php echo $status_type; ?></option>

      			<?php endforeach; ?>
      		</select>
    	</div>
  	  <div class="postarea">
    		<select name="occupancy_type" id="occupancy_type">
    		  <option value="">Occupancy Type</option>
    		  <?php foreach( $all_occupancy_types as $type_choice ): ?>

    				<option value="<?php echo $type_choice; ?>"
    					<?php
    						if( $type_choice == $occupancy_type )
    							echo ' SELECTED ';
    					?>>
    					<?php echo $type_choice; ?></option>

    			<?php endforeach; ?>
    		</select>
    	</div>
    	<div class="postarea">
      		<select name="property_type" id="property_type">
      		  <option value="">Purchase Type</option>
      		  <?php foreach( $all_types as $type_choice ): ?>
      				<option value="<?php echo $type_choice; ?>"
      					<?php
      						if( $type_choice == $property_type )
      							echo ' SELECTED ';
      					?>>
      					<?php echo $type_choice; ?></option>

      			<?php endforeach; ?>
      		</select>
    	</div>
    	<div class="postarea">
    	  <select name="part_of_town" id="part_of_town">
    		  <option value="">Part of Town</option>
    		  <?php foreach( $parts_of_town as $type_choice ): ?>

    				<option value="<?php echo $type_choice; ?>"
    					<?php
    						if( $type_choice == $part_of_town )
    							echo ' SELECTED ';
    					?>>
    					<?php echo $type_choice; ?></option>

    			<?php endforeach; ?>
    		</select>
    	</div>
    	
    	<div class="postarea">
    	  <select name="garage" id="garage">
    		  <option value="">Garage</option>
    		  <?php foreach( $garage_types as $type_choice ): ?>

    				<option value="<?php echo $type_choice; ?>"
    					<?php
    						if( $type_choice == $garage )
    							echo ' SELECTED ';
    					?>>
    					<?php echo $type_choice; ?></option>

    			<?php endforeach; ?>
    		</select>
    	</div>
    	
  	</div>
  	
    <div id="rental_only" style="display:<?php echo $rental_only; ?>">
    	<h2>Rental Property Options</h2>
    	<div id="rental_options">
    	  
    	  <div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="wd_hookup" id="wd_hookup" value="1" <?php
      	    if(1==$wd_hookup) echo " CHECKED ";
      	  ?>/>
      	  <label for="wd_hookup">Washer/Dryer Hookup</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="laundry" id="laundry" value="1" <?php
      	    if(1==$laundry) echo " CHECKED ";
      	  ?>/>
      	  <label for="laundry">Laundry Facilities</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="balcony" id="balcony" value="1" <?php
      	    if(1==$balcony) echo " CHECKED ";
      	  ?>/>
      	  <label for="balcony">Balcony</label>&nbsp;
      	</div>
    	  
    	  <div class="clearfix"></div>
    	  
    	  <div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="deck" id="deck" value="1" <?php
      	    if(1==$deck) echo " CHECKED ";
      	  ?>/>
      	  <label for="deck">Deck</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="patio" id="patio" value="1" <?php
      	    if(1==$patio) echo " CHECKED ";
      	  ?>/>
      	  <label for="patio">Patio</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="yard" id="yard" value="1" <?php
      	    if(1==$yard) echo " CHECKED ";
      	  ?>/>
      	  <label for="yard">Yard</label>&nbsp;
      	</div>
      	
    	  <div class="clearfix"></div>
    	  
    	  <div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="cable" id="cable" value="1" <?php
      	    if(1==$cable) echo " CHECKED ";
      	  ?>/>
      	  <label for="cable">Cable	hook up</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="resident_services" id="resident_services" value="1" <?php
      	    if(1==$resident_services) echo " CHECKED ";
      	  ?>/>
      	  <label for="resident_services">Resident	services</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="playground" id="playground" value="1" <?php
      	    if(1==$playground) echo " CHECKED ";
      	  ?>/>
      	  <label for="playground">On site	playground</label>&nbsp;
      	</div>
      	
    	  <div class="clearfix"></div>
    	  
    	  <div class="postarea" style="float:left;width:50%">
          <div class="titlediv">
        		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" for="management_company">Management Company</label>
        		<input type="text" id="management_company" name="management_company" class="prompt-text"
        			value="<?php echo $management_company; ?>" />
        	</div>
        </div>
        
        <div class="postarea" style="float:left;width:50%">
          <div class="titlediv">
        		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" for="management_phone">Management Company Phone</label>
        		<input type="text" id="management_phone" name="management_phone" class="prompt-text"
        			value="<?php echo $management_phone; ?>" />
        	</div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="postarea">
          <div class="titlediv">
        		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" for="management_web">Management Company Website</label>
        		<input type="text" id="management_web" name="management_web" class="prompt-text"
        			value="<?php echo $management_web; ?>" />
        	</div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="alarm_system" id="alarm_system" value="1" <?php
      	    if(1==$alarm_system) echo " CHECKED ";
      	  ?>/>
      	  <label for="alarm_system">Alarm System</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="pets_allowed" id="pets_allowed" value="1" <?php
      	    if(1==$pets_allowed) echo " CHECKED ";
      	  ?>/>
      	  <label for="pets_allowed">Pets Allowed</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="senior_community" id="senior_community" value="1" <?php
      	    if(1==$senior_community) echo " CHECKED ";
      	  ?>/>
      	  <label for="senior_community">Senior Community</label>&nbsp;
      	</div>
      	
    	  <div class="clearfix"></div>
    	  
    	  <div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="paid_utilities" id="paid_utilities" value="1" <?php
      	    if(1==$paid_utilities) echo " CHECKED ";
      	  ?>/>
      	  <label for="paid_utilities">Paid Utilities</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="short_term_lease" id="short_term_lease" value="1" <?php
      	    if(1==$short_term_lease) echo " CHECKED ";
      	  ?>/>
      	  <label for="short_term_lease">Short term lease available</label>&nbsp;
      	</div>
      	<div class="postarea" style="float:left;width:31%;margin-right:2%">
      	  <input type="checkbox" name="on_site_maintenance" id="on_site_maintenance" value="1" <?php
      	    if(1==$on_site_maintenance) echo " CHECKED ";
      	  ?>/>
      	  <label for="on_site_maintenance">On site	maintenance</label>&nbsp;
      	</div>
      	
    	  <div class="clearfix"></div>
    	  
    	</div>
  	</div>
  	
  	<div id="sale_only" style="display:<?php echo $sales_only; ?>">
  	  <div id="sale_options">
    	  <h2>For-Sale Property Options</h2>
    	</div>
    	
    	<div class="postarea" style="float:left;width:50%;">
      		<select name="project_type" id="project_type">
      		  <option value="">Property Type</option>
      		  <?php foreach( $project_types as $p_type ): ?>
      				<option value="<?php echo $p_type; ?>"
      					<?php
      						if( $p_type == $project_type )
      							echo ' SELECTED ';
      					?>>
      					<?php echo $p_type; ?></option>

      			<?php endforeach; ?>
      		</select>
    	</div>
    	<div class="postarea" style="float:left;width:25%;">
    	  <input type="checkbox" name="tax_incentives" id="tax_incentives" value="1" <?php
    	    if(1==$tax_incentives) echo " CHECKED ";
    	  ?>/>
    	  <label for="tax_incentives">Tax Incentives</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:25%;">
        <div class="titlediv">
      		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="lot_size">Lot Size</label>
      		<input type="text" id="lot_size" name="lot_size" class="prompt-text"
      			value="<?php echo $lot_size; ?>" />
      	</div>
    	</div>
    	
    	<div class="clearfix"></div>
    	
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="first_floor_master" id="first_floor_master" value="1" <?php
    	    if(1==$first_floor_master) echo " CHECKED ";
    	  ?>/>
    	  <label for="first_floor_master">First	floor	master</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="dining_room" id="dining_room" value="1" <?php
    	    if(1==$oven) echo " CHECKED ";
    	  ?>/>
    	  <label for="dining_room">Dining Room</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="family_room" id="family_room" value="1" <?php
    	    if(1==$family_room) echo " CHECKED ";
    	  ?>/>
    	  <label for="family_room">Family Room</label>&nbsp;
    	</div>

    	<div class="clearfix"></div>
    	
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="den_office" id="den_office" value="1" <?php
    	    if(1==$den_office) echo " CHECKED ";
    	  ?>/>
    	  <label for="den_office">Den/Office</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="laundry_room" id="laundry_room" value="1" <?php
    	    if(1==$laundry_room) echo " CHECKED ";
    	  ?>/>
    	  <label for="laundry_room">Laundry Room</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="basement" id="basement" value="1" <?php
    	    if(1==$basement) echo " CHECKED ";
    	  ?>/>
    	  <label for="basement">Basement</label>&nbsp;
    	</div>

    	<div class="clearfix"></div>
    	
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="fireplace" id="fireplace" value="1" <?php
    	    if(1==$fireplace) echo " CHECKED ";
    	  ?>/>
    	  <label for="fireplace">Fireplace</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="corner_lot" id="corner_lot" value="1" <?php
    	    if(1==$corner_lot) echo " CHECKED ";
    	  ?>/>
    	  <label for="corner_lot">Corner Lot</label>&nbsp;
    	</div>
    	<div class="postarea" style="float:left;width:31%;margin-right:2%">
    	  <input type="checkbox" name="cul_de_sac" id="cul_de_sac" value="1" <?php
    	    if(1==$cul_de_sac) echo " CHECKED ";
    	  ?>/>
    	  <label for="cul_de_sac">Cul-de-sac</label>&nbsp;
    	</div>

    	<div class="clearfix"></div>
    	
  	</div>
  	
  	<div class="clearfix"></div><hr/>
  	<h2>General Property Options</h2>
  	<div class="postarea" style="float:left;width:30%;margin-right:3%">
  	  <div class="titlediv">
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="square_footage">Square Footage</label>
    		<input type="text" id="square_footage" name="square_footage" class="prompt-text"
    			value="<?php echo $square_footage; ?>" />
    	</div>
  	</div>
  	<div class="postarea" style="float:left;width:30%;margin-right:3%">
  	  <div class="titlediv">
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="stories"># of Stories</label>
    		<input type="text" id="stories" name="stories" class="prompt-text"
    			value="<?php echo $stories; ?>" />
    	</div>
  	</div>
  	<div class="postarea" style="float:left;width:30%;margin-right:3%">
  	  <div class="titlediv">
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="age">Age of Property</label>
    		<input type="text" id="age" name="age" class="prompt-text"
    			value="<?php echo $age; ?>" />
    	</div>
  	</div>
  	

  	<div class="clearfix"></div>
  	
  	<div class="postarea" style="float:left;width:30%;margin-right:3%">
  	  <div class="titlediv">
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="num_beds"># of Bedrooms</label>
    		<input type="text" id="num_beds" name="num_beds" class="prompt-text"
    			value="<?php echo $num_beds; ?>" />
    	</div>
  	</div>
  	<div class="postarea" style="float:left;width:30%;margin-right:3%">
  	  <div class="titlediv">
    		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="num_baths"># of Bathrooms</label>
    		<input type="text" id="num_baths" name="num_baths" class="prompt-text"
    			value="<?php echo $num_baths; ?>" />
    	</div>
  	</div>
  	<div class="postarea" style="float:left;width:30%;margin-right:3%">
  	  
  	</div>





    <div class="clearfix"></div>

    <div class="clearfix"></div><hr/>
    <h2>Management Contact</h2>

    <div class="postarea" style="float:left;width:30%;margin-right:3%">
      <div class="titlediv">
        <label class="hide-if-no-js fancy-label" style="visibility:hidden" for="contact_number">Contact Number</label>
        <input type="text" id="contact_number" name="contact_number" class="prompt-text"
          value="<?php echo $contact_number; ?>" />
      </div>
    </div>




  	
  	<div class="clearfix"></div><hr/>
  	
  	<h2>Amenities</h2>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="fridge" id="fridge" value="1" <?php
  	    if(1==$fridge) echo " CHECKED ";
  	  ?>/>
  	  <label for="fridge">Refrigerator</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="oven" id="oven" value="1" <?php
  	    if(1==$oven) echo " CHECKED ";
  	  ?>/>
  	  <label for="oven">Oven</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="dwasher" id="dwasher" value="1" <?php
  	    if(1==$dwasher) echo " CHECKED ";
  	  ?>/>
  	  <label for="dwasher">Dishwasher</label>&nbsp;
  	</div>
  	
  	<div class="clearfix"></div>
  	
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="forced_air" id="forced_air" value="1" <?php
  	    if(1==$forced_air) echo " CHECKED ";
  	  ?>/>
  	  <label for="forced_air">Forced Air</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="central_air" id="central_air" value="1" <?php
  	    if(1==$central_air) echo " CHECKED ";
  	  ?>/>
  	  <label for="central_air">Central Air</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="central_heat" id="central_heat" value="1" <?php
  	    if(1==$central_heat) echo " CHECKED ";
  	  ?>/>
  	  <label for="oven">Central Heat</label>&nbsp;
  	</div>
  	
  	<div class="clearfix"></div>
  	  
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="near_transit" id="near_transit" value="1" <?php
  	    if(1==$near_transit) echo " CHECKED ";
  	  ?>/>
  	  <label for="near_transit">Near Transit</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="near_school" id="near_school" value="1" <?php
  	    if(1==$near_school) echo " CHECKED ";
  	  ?>/>
  	  <label for="near_school">Near School</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="near_shopping" id="near_shopping" value="1" <?php
  	    if(1==$near_shopping) echo " CHECKED ";
  	  ?>/>
  	  <label for="near_shopping">Near Shopping</label>&nbsp;
  	</div>

  	
  	<div class="clearfix"></div>
  	  
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="handicap_accessible" id="handicap_accessible" value="1" <?php
  	    if(1==$handicap_accessible) echo " CHECKED ";
  	  ?>/>
  	  <label for="handicap_accessible">Handicap Accessible</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="lease_option" id="lease_option" value="1" <?php
  	    if(1==$lease_option) echo " CHECKED ";
  	  ?>/>
  	  <label for="lease_option">Lease Option</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="aware_compliant" id="aware_compliant" value="1" <?php
  	    if(1==$aware_compliant) echo " CHECKED ";
  	  ?>/>
  	  <label for="aware_compliant">Aware Compliant</label>&nbsp;
  	</div>
  	
  	<div class="clearfix"></div>
  	
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="parking" id="parking" value="1" <?php
  	    if(1==$parking) echo " CHECKED ";
  	  ?>/>
  	  <label for="parking">Parking</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  <input type="checkbox" name="hardwood_floors" id="hardwood_floors" value="1" <?php
  	    if(1==$hardwood_floors) echo " CHECKED ";
  	  ?>/>
  	  <label for="hardwood_floors">Hardwood Floors</label>&nbsp;
  	</div>
  	<div class="postarea" style="float:left;width:31%;margin-right:2%">
  	  &nbsp;
  	</div>
  	<div class="clearfix"></div><hr/>
  	
    <!-- <div class="titlediv">
      <label class="hide-if-no-js fancy-label" style="visibility:hidden" for="startdate">Show Dates</label>
      <input type="text" id="ex_showdates" name="ex_showdates" class="prompt-text"
        value="<?php echo $showdates; ?>" />
    </div> -->
  
  	<div class="postarea postimages">
  		<h2>Property Images&nbsp;<img style="width:16px;height:16px;display:none;" 
  		  id="img-loading" src="<?php echo THEME_IMG_URL . '/loading.gif'; ?>" /></h2>
  		<div id="file-uploader">       
  		    <noscript>          
  		        <p>Please enable JavaScript to use file uploader.</p>
  		        <!-- or put a simple form for upload here -->
  		    </noscript>         
  			<div class="clearfix"></div>
  		</div>
  		<div class="clearfix"></div>
  		<div id="property_images">
	
  			<div class="clearfix"></div>
  		</div>
  	</div>	
  	<div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>
