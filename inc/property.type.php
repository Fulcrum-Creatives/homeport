<?php
// global $wp_rewrite;
// $wp_rewrite->flush_rules();

class Property{
  
  // public static function get_all_properties(){
  //   global $wpdb;
  //   
  //   $all_props = array();
  //   // no query data in, fetch all (or a limited set)
  //   $sql = "
  //     SELECT * FROM $wpdb->posts p
  //     WHERE p.post_type='property'
  //     AND p.post_status='publish'
  //     ORDER BY p.ID DESC
  //   ";
  //   
  //   $results = $wpdb->get_results( $sql );
  //   foreach( $results as $result )
  //   {
  // 
  //     $result->custom = get_post_custom( $result->ID );
  // 
  //     // only show plottable properties, should move to JS
  //     if( empty($result->custom['lat'][0]) || empty($result->custom['lng'][0]) ) continue;
  // 
  //     $prop = self::genPropertyArray($result);
  // 
  //    $all_props[] = $prop;  
  //   }
  //   
  //   return $all_props;
  // }
  
  public static function get_all_properties_idx( ){
    // apc_delete('get_all_properties_idx');
    global $wpdb;

    if( !$all_props = apc_fetch('get_all_properties_idx') ){
      
      $sql = "
        SELECT ID FROM $wpdb->posts p
        WHERE p.post_type='property'
        AND p.post_status='publish'
        ORDER BY p.ID DESC
      ";
      
      $results = $wpdb->get_results( $sql );
    

      foreach( $results as $result )
      {


    		$all_props[$result->ID] = 1;  
      }
      
      apc_store('get_all_properties_idx', serialize($all_props), 3600);
    } 
    else{
      $all_props = unserialize($all_props);
    }

    return $all_props;
  }
  
  // public ajax
  public static function query_properties($function=false, $in=false){

    // ThemeUtil::log_something("start query_properties: " . get_execution_time() );
    global $wpdb;
    
    $response = array(
      'result'      => 0,
      'properties'  => array(),
      'debug'       => array()
    );
    
    if( !$in )
      $data = $_REQUEST['data'];
    else
      $data = $in;
    
    if( !is_array( $data ) ){
      $tmp_data = array();
      $parts = explode("&", $data);
      foreach($parts as $part) {
        $part_key_values = explode("=", $part);
        $key = urldecode($part_key_values[0]);
        $values = urldecode($part_key_values[1]);
        $tmp_data[$key] = $values;
      }
      $data = $tmp_data;
    }
    
    $response['_data'] = $data;

    $interval =  microtime(true);
    // ThemeUtil::log_something("query string parsed: " . get_execution_time());

    $working_arr = array();
    $ptype = $data['property_type'];
    
    if( !empty( $ptype ) ){
      // $response['debug']['property_type'] = $ptype;
      $property_type_idx = ThemeAdmin::get_idx( 'property_type', $ptype );
    }
    else{
      // $response['debug']['property_type'] = '';
      $property_type_idx = self::get_all_properties_idx();
    } 
    
    // ThemeUtil::log_something("post properties idx: " . get_execution_time() );
    
    // if( count( $pre_property ) > 0 ){
    //   $property_type_idx = array_intersect_key( $property_type_idx, $pre_property );
    // }
    
    ///////////////////////////////////////////////////////////////////////////
    // form variables in /////////////////////////
    $low = preg_replace( "/\D/", "", trim($data['property_price_low']));
    $response['debug']['low'] = $low;
    $high = preg_replace( "/\D/", "", trim($data['property_price_high']));
    $response['debug']['high'] = $high;
    $neighborhood = trim($data['property_neighborhood']);
    $status = trim($data['property_status']);
    $response['debug']['neighborhood'] = $neighborhood;
    $zip = trim($data['property_zip']);
    $response['debug']['zip'] = $zip;
    $baths = (int)trim($data['property_baths']);
    $beds = (int)trim($data['property_beds']);
    
    $sf_low = preg_replace( "/\D/", "", trim($data['square_footage_low']));
    $sf_high = preg_replace( "/\D/", "", trim($data['square_footage_high']));
    
    $stories = trim($data['stories']);
    $age = trim($data['age']);
    
    // end form variables in /////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    
    // special test: $ptype = 'Sale' or $ptype = 'Rent' where sensible
    foreach( $property_type_idx as $post_id=>$idx ){
      
      // ThemeUtil::log_something("post properties idx: " . get_execution_time() );
      

      // apc_delete( 'idx_post_' . $post_id );
      if( !$idx_post = apc_fetch( 'idx_post_' . $post_id ) ){
        $idx_post = get_post( $post_id );
        $idx_post->custom = get_post_custom( $post_id );
        apc_store( 'idx_post_' . $post_id, serialize($idx_post), 3600 );
      }
      else{
        $idx_post = unserialize( $idx_post );
      }
      
      // BASIC SEARCH OPTIONS /////////////////////////
      // ditch properties that can't be mapped
      if( empty($idx_post->custom['lat'][0]) || empty($idx_post->custom['lng'][0]) ) continue;
      // end lat/lng /////////////////////////
      
      // prices, throw out out of range properties 
      $idx_price = trim($idx_post->custom['price'][0]);
      
      if(!empty($low) && (int)$low > 0){
        if( preg_match("/\D/", $idx_price) ) continue;
        if( (int)$idx_price < $low ) continue;
      }
      
      if(!empty($high) && (int)$high > 0 ){
        if( preg_match("/\D/", $idx_price) ) continue;
        if( (int)$idx_price > $high ) continue;
      }
      // end prices //////////////////////////////
      
      // baths /////////////////////////
      $idx_baths = trim($idx_post->custom['num_baths'][0]);
      if( $baths > 0 && preg_match("/\D/", $idx_baths) ) continue;
      if( $baths > 0 && (int)$idx_baths < $baths ) continue;
      
      // end baths /////////////////////////
      
      // beds /////////////////////////
      $idx_beds = trim($idx_post->custom['num_beds'][0]);
      if( $beds > 0 && preg_match("/\D/", $idx_beds) ) continue;
      if( $beds > 0 && (int)$idx_beds < $beds ) continue;
      // end beds /////////////////////////

      // zipcode & neighborhoods /////////////////////////
      $idx_zip = trim($idx_post->custom['postal_code'][0]);
      // $response['debug']['idx_zip'] .= $idx_zip . " | ";
      $idx_neighborhood = trim($idx_post->custom['neighborhood'][0]);
      // $response['debug']['idx_neighborhood'] .= $idx_neighborhood . " | ";
      
      if( !empty( $zip ) ){
        // $response['debug']['zip_test'] = "yes";
        if( $zip != $idx_zip ) continue;
        // $response['debug']['zip_test_passed'] = "yes";
        // end zipcode /////////////////////////
      }
      else if( !empty( $neighborhood ) ){
        // $response['debug']['neighborhood_test'] = "yes";
        if( empty($idx_neighborhood) ) continue;
        if( $neighborhood != $idx_neighborhood ) continue;
        // $response['debug']['neighborhood_test_passed'] = "yes";
      }
      
      // end neighborhoods /////////////////////////
      
      // status /////////////////////////
      $idx_status = trim($idx_post->custom['status'][0]);
      if( !empty( $status ) ){
        if( $status != $idx_status ) continue;
      }
      // end status /////////////////////////
	  
	  
      /////////////////////////
      // END OF BASIC SEARCH, ADVANCED OPTIONS ////////////////////
      /////////////////////////
      
      $idx_sf = trim($idx_post->custom['square_footage'][0]);
      if( !empty($sf_low )  && (int)$sf_low>0 ){
        if( 0==(int)$idx_sf ) continue;
        if( (int)$idx_sf < (int)$sf_low ) continue;
      }
      
      if( !empty($sf_high ) && (int)$sf_high>0 ){
        if( 0==(int)$idx_sf ) continue;
        if( (int)$idx_sf > (int)$sf_high ) continue;
      }
      
      $idx_stories = trim($idx_post->custom['stories'][0]);
      if( !empty($stories) ){
        if( empty($idx_stories) ) continue;
        if( $idx_stories != $stories ) continue;
      }
      
      $idx_age = trim($idx_post->custom['age'][0]);
      if( !empty($age) ){
        if( empty($idx_age) ) continue;
        $idx_age = (int)$idx_age;
        if( $idx_age > $age ) continue;
      }
      
      // parking /////////////////////////
      $parking = (int)$data['parking'];
      $idx_parking = (int)$idx_post->custom['parking'][0];
      if( $parking > 0 && 0==$idx_parking ) continue;
      // end parking /////////////////////////
      
      
      $refrigerator = (int)$data['refrigerator'];
      $idx_refrigerator = (int)$idx_post->custom['fridge'][0];
      if( $refrigerator >0 && 0==$idx_refrigerator ){
        continue;
      } 
      
      $oven = (int)$data['oven'];
      $idx_oven = (int)$idx_post->custom['oven'][0];
      if( $oven >0 && 0==$idx_oven ) continue;
      
      $dishwasher = (int)$data['dishwasher'];
      $idx_dishwasher = (int)$idx_post->custom['dwasher'][0];
      if( $dishwasher >0 && 0==$idx_dishwasher ) continue;
      
      $central_air = (int)$data['central_air'];
      $idx_central_air = (int)$idx_post->custom['central_air'][0];
      if( $central_air >0 && 0==$idx_central_air ) continue;
      
      $forced_air = (int)$data['forced_air'];
      $idx_forced_air = (int)$idx_post->custom['forced_air'][0];
      if( $forced_air >0 && 0==$idx_forced_air ) continue;
      
      $central_heat = (int)$data['central_heat'];
      $idx_central_heat = (int)$idx_post->custom['central_heat'][0];
      if( $central_heat >0 && 0==$idx_central_heat ) continue;
      
      $handicap_accessible = (int)$data['handicap_accessible'];
      $idx_handicap_accessible = (int)$idx_post->custom['handicap_accessible'][0];
      if( $handicap_accessible >0 && 0==$idx_handicap_accessible ) continue;
      
      $near_transit = (int)$data['near_transit'];
      $idx_near_transit = (int)$idx_post->custom['near_transit'][0];
      if( $near_transit >0 && 0==$idx_near_transit ) continue;
      
      $near_school = (int)$data['near_school']; 
      $idx_near_school = (int)$idx_post->custom['near_school'][0];
      if( $near_school >0 && 0==$idx_near_school ) continue;
      
      $near_shopping = (int)$data['near_shopping']; 
      $idx_near_shopping = (int)$idx_post->custom['near_shopping'][0];
      if( $near_shopping >0 && 0==$idx_near_shopping ) continue;
      
      $lease_option = (int)$data['lease_option']; 
      $idx_lease_option = (int)$idx_post->custom['lease_option'][0];
      if( $lease_option >0 && 0==$idx_lease_option ) continue;
      
      $aware_compliant = (int)$data['aware_compliant']; 
      $idx_aware_compliant = (int)$idx_post->custom['aware_compliant'][0];
      if( $aware_compliant >0 && 0==$idx_aware_compliant ) continue;
      
      // TO DO
      $photos = (int)$data['photos'];
      
      $wd_hookup = (int)$data['wd_hookup'];
      $idx_wd_hookup = (int)$idx_post->custom['wd_hookup'][0];
      if( $wd_hookup >0 && 0==$idx_wd_hookup ) continue;
      
      $laundry = (int)$data['laundry'];
      $idx_laundry = (int)$idx_post->custom['laundry'][0];
      if( $laundry >0 && 0==$idx_laundry ) continue;
      
      $yard = (int)$data['yard'];
      $idx_yard = (int)$idx_post->custom['yard'][0];
      if( $yard >0 && 0==$idx_yard ) continue;
      
      $patio = (int)$data['patio'];
      $idx_patio = (int)$idx_post->custom['patio'][0];
      if( $patio >0 && 0==$idx_patio ) continue;
      
      $balcony = (int)$data['balcony'];
      $idx_balcony = (int)$idx_post->custom['balcony'][0];
      if( $balcony >0 && 0==$idx_balcony ) continue;
      
      $cable = (int)$data['cable'];
      $idx_cable = (int)$idx_post->custom['cable'][0];
      if( $cable >0 && 0==$idx_cable ) continue;
      
      $resident_services = (int)$data['resident_services'];
      $idx_resident_services = (int)$idx_post->custom['resident_services'][0];
      if( $resident_services >0 && 0==$idx_resident_services ) continue;
      
      $on_site_playground = (int)$data['playgrounds'];
      $idx_on_site_playground = (int)$idx_post->custom['on_site_playground'][0];
      if( $on_site_playground >0 && 0==$idx_on_site_playground ) continue;
      
      $alarm_system = (int)$data['alarm_system'];
      $idx_alarm_system = (int)$idx_post->custom['alarm_system'][0];
      if( $alarm_system >0 && 0==$idx_alarm_system ) continue;
      
      $pets_allowed = (int)$data['pets_allowed'];
      $idx_pets_allowed = (int)$idx_post->custom['pets_allowed'][0];
      if( $pets_allowed >0 && 0==$idx_pets_allowed ) continue;
      
      $senior_community = (int)$data['senior_community'];
      $idx_senior_community = (int)$idx_post->custom['senior_community'][0];
      if( $senior_community >0 && 0==$idx_senior_community ) continue;
      
      $paid_utilities = (int)$data['paid_utilities'];
      $idx_paid_utilities = (int)$idx_post->custom['paid_utilities'][0];
      if( $paid_utilities >0 && 0==$idx_paid_utilities ) continue;
      
      $short_term_lease = (int)$data['short_term_lease'];
      $idx_short_term_lease = (int)$idx_post->custom['short_term_lease'][0];
      if( $short_term_lease >0 && 0==$idx_short_term_lease ) continue;
      
      $on_site_maintenance = (int)$data['on_site_maintenance'];
      $idx_on_site_maintenance = (int)$idx_post->custom['on_site_maintenance'][0];
      if( $on_site_maintenance >0 && 0==$idx_on_site_maintenance ) continue;
      
      $tax_incentives = (int)$data['tax_incentives'];
      $idx_tax_incentives = (int)$idx_post->custom['tax_incentives'][0];
      if( $tax_incentives >0 && 0==$idx_tax_incentives ) continue;
      
      $first_floor_master = (int)$data['first_floor_master'];
      $idx_first_floor_master = (int)$idx_post->custom['first_floor_master'][0];
      if( $first_floor_master >0 && 0==$idx_first_floor_master ) continue;
      
      $dining_room = (int)$data['dining_room'];
      $idx_dining_room = (int)$idx_post->custom['dining_room'][0];
      if( $dining_room >0 && 0==$idx_dining_room ) continue;
      
      $family_room = (int)$data['family_room'];
      $idx_family_room = (int)$idx_post->custom['family_room'][0];
      if( $family_room >0 && 0==$idx_family_room ) continue;
      
      $den_office = (int)$data['den_office'];
      $idx_den_office = (int)$idx_post->custom['den_office'][0];
      if( $den_office >0 && 0==$idx_den_office ) continue;
      
      $laundry_room = (int)$data['laundry_room'];
      $idx_laundry_room = (int)$idx_post->custom['laundry_room'][0];
      if( $laundry_room >0 && 0==$idx_laundry_room ) continue;
      
      $basement = (int)$data['basement'];
      $idx_basement = (int)$idx_post->custom['basement'][0];
      if( $basement >0 && 0==$idx_basement ) continue;
      
      $hardwood_floors = (int)$data['hardwood_floors'];
      $idx_hardwood_floors = (int)$idx_post->custom['hardwood_floors'][0];
      if( $hardwood_floors >0 && 0==$idx_hardwood_floors ) continue;
      
      $fireplace  = (int)$data['fireplace'];
      $idx_fireplace = (int)$idx_post->custom['fireplace'][0];
      if( $fireplace >0 && 0==$idx_fireplace ) continue;
      
      $corner_lot  = (int)$data['corner_lot'];
      $idx_corner_lot = (int)$idx_post->custom['corner_lot'][0];
      if( $corner_lot >0 && 0==$idx_corner_lot ) continue;
      
      $cul_de_sac = (int)$data['cul_de_sac'];
      $idx_cul_de_sac = (int)$idx_post->custom['cul_de_sac'][0];
      if( $cul_de_sac >0 && 0==$idx_cul_de_sac ) continue;
      
      $deck = (int)$data['deck'];
      $idx_deck = (int)$idx_post->custom['deck'][0];
      if( $deck >0 && 0==$idx_deck ) continue;
      
      /////////////////////////
      // END OF ADVANCED OPTIONS /////////////////////////
      /////////////////////////
      $prop = self::small_genPropertyArray($idx_post);
      $working_arr[$post_id] = $prop;
      
    }
    
    // ThemeUtil::log_something("post properties loop: " . get_execution_time() );
    
    $development = trim($data['development']);
    if( !empty( $development ) ){
      $development_idx = ThemeAdmin::get_idx( 'development', $development );
      $working_arr = array_intersect_key( $working_arr, $development_idx );
    }
    
    $project_type = trim($data['project_type']);
    $response['debug']['project_type'] = $project_type;
    if( !empty( $project_type ) ){
      $project_type_idx = ThemeAdmin::get_idx( 'project_type', $project_type );
      $working_arr = array_intersect_key( $working_arr, $project_type_idx );
    }
    
    if( !empty( $data['occupancy_type'] ) ){
      $occupancy_type_idx = ThemeAdmin::get_idx( 'occupancy_type', $data['occupancy_type'] );
      $working_arr = array_intersect_key( $working_arr, $occupancy_type_idx );
    }
    
    if( !empty( $data['garage'] ) ){
      $garage_type_idx = ThemeAdmin::get_idx( 'garage', $data['garage'] );
      $working_arr = array_intersect_key( $working_arr, $garage_type_idx );
    }
    
    if( !empty( $data['part_of_town'] ) ){
      $part_of_town_idx = ThemeAdmin::get_idx( 'part_of_town', $data['part_of_town'] );
      $working_arr = array_intersect_key( $working_arr, $part_of_town_idx );
    }
    
    $response['properties'] = array_merge( $response['properties'], $working_arr );

    // ThemeUtil::log_something("post result array_merge: " . get_execution_time() . "\n\n" );
    
    // ThemeUtil::log_something( print_r($response, 1) );
    
    // we made it all the way... 
    $response['result'] = 1;
    if( !$function )
      die( json_encode( $response ) );
    else
      return $response;
  }
  

  
  // incomplete
  public static function sideBarImages($post_id){
    
    if( $attachments = get_children( 
			array(
				'post_parent' 		=> $post_id, 
				'post_status' 		=> 'inherit', 
				'post_type' 		  => 'attachment', 
				'post_mime_type'	=> 'image', 
				'order' 			    => 'ASC', 
				'orderby' 			  => 'menu_order ID',
				'numberposts' 		=> -1
			) 
		) ){
		  
		  $post_thumbnail_id = get_post_thumbnail_id( $post_id );
		  
		  $size = 'single-post';
		  foreach( $attachments as $k=>$att ){
  		  if( $post_thumbnail_id== $att_id) continue; // skip featured/thumbnail added images
  		  // $custom = get_post_custom( $att->ID );
  		  $src = wp_get_attachment_image_src( $att->ID, $size, false );
		  
			  $imgsrc = $src[0];
				$width = $src[1];
				$height = $src[2];
        $name = stripslashes($attachment->post_name);
        $caption = stripslashes($attachment->post_title);
        
	    ?>
		    <img title="<?php echo $caption; ?>" class="single-property-view" src="<?php echo $imgsrc; ?>" 
		      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" />
      <?php
	    }
		
		}
		else
		{
		  ?>
		  <img title="No Image Available" class="single-property-view" 
		    src="http://placehold.it/260&text=No+image+available" 
	      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" />
		  <?php
		}
		 
  }
  
  public static function singleViewFirstImage($post_id, $size='main-size', $caption_prefix='' ){
    
    if( $attachments = get_children( 
			array(
				'post_parent' 		=> $post_id, 
				'post_status' 		=> 'inherit', 
				'post_type' 		  => 'attachment', 
				'post_mime_type'	=> 'image', 
				'order' 			    => 'ASC', 
				'orderby' 			  => 'menu_order ID',
				'numberposts' 		=> 1
			) 
		) ){
      // ThemeUtil::mpuke($attachments); return;
		  
		  $post_thumbnail_id = get_post_thumbnail_id( $post_id );
		  
		  if( !empty($caption_prefix) ) $caption_prefix .= ": ";
		  
      // ThemeUtil::mpuke($attachments);
		  
		  if( count($attachments) ):
		    
		    ?>
  		  <div id="single-first-image">
  		    <?php
          // $size = 'mobile-size';
    		  foreach( $attachments as $k=>$att ){
            // ThemeUtil::mpuke($att);
            // if( $post_thumbnail_id== $att->id) continue; // skip featured/thumbnail added images
      		  // $custom = get_post_custom( $att->ID );
      		  $src = wp_get_attachment_image_src( $att->ID, $size, false );
        	  // ThemeUtil::mpuke($src);
    			  $imgsrc = $src[0];
    				$width = $src[1];
    				$height = $src[2];
            $name = stripslashes($attachment->post_name);
            $caption = stripslashes($attachment->post_title);
          
    	    ?>
    		    <img title="<?php echo $caption_prefix . " " . $caption; ?>" class="single-property-view" src="<?php echo $imgsrc; ?>" 
    		      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" />
          <?php
            
            // ThemeUtil::mpuke($imgsrc);
            // ThemeUtil::mpuke($att);
    		    // continue;
            break;
    	    }
    		  ?>
        </div>
      <?php
      endif;
    }
  }
  
  public static function singleViewImages($post_id, $size='main-size', $caption_prefix='' ){
    
    if( $attachments = get_children( 
			array(
				'post_parent' 		=> $post_id, 
				'post_status' 		=> 'inherit', 
				'post_type' 		  => 'attachment', 
				'post_mime_type'	=> 'image', 
				'order' 			    => 'ASC', 
				'orderby' 			  => 'menu_order ID',
				'numberposts' 		=> -1
			) 
		) ){
		  $post_thumbnail_id = get_post_thumbnail_id( $post_id );
		  
		  ?>
		  <div id="slider-wrapper">
  	    <div id="slider" class="nivoSlider">
    		  <?php
    		  // $size = 'main-size';
    		  foreach( $attachments as $k=>$att ){
    		    if( $post_thumbnail_id== $att->ID) continue; // skip featured/thumbnail added images
      		  // $custom = get_post_custom( $att->ID );
      		  $src = wp_get_attachment_image_src( $att->ID, $size, false );
		  
    			  $imgsrc = $src[0];
    				$width = $src[1];
    				$height = $src[2];
            $name = stripslashes($att->post_name);
            // $caption = '<a href="' . get_permalink($post_id) . '>' . stripslashes($att->post_title) . '</a>';
            $caption = stripslashes($att->post_title);
            if( !empty($caption_prefix) ) $caption_prefix .= ": ";
    	    ?>
    	      <a href="<?php echo get_permalink($post_id); ?>"
    		    ><img title="<?php echo $caption_prefix . "" . $caption; ?>" class="single-property-view" src="<?php echo $imgsrc; ?>" 
    		      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" /></a>
    		      
          <?php
    	    }
    		  ?>
		    </div>
		  </div>
		  <?php
		}
		else
		{
		  ?>
		  <!--img title="No Image Available" class="single-property-view" 
		    src="http://placehold.it/614x275&text=No+image+available" 
	      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" /-->
		  <?php
		}
	  
  }
  
  public static function custom_excerpt($str){
    
  }

  public static function loadPropertyArray($post_id){
    $property_post = get_post( $post_id );
    $property_post->custom = get_post_custom( $post_id );
    // print '<!-- property_post: ';
    // print_r($property_post);
    // print '-->';
    $prop = self::genPropertyArray($property_post);
    return $prop;
  }
  
  public static function small_genPropertyArray($result){
    // apc_delete('small_genPropertyArray_' . $result->ID);
    
    if( !$prop = apc_fetch('small_genPropertyArray_' . $result->ID) ){
      $prop = array(
        'permalink'       => get_permalink($result->ID),
        'id'              => $result->ID,
        'name'            => $result->post_title,
        'excerpt'         => empty($result->post_content) ? '' : $result->post_content,
        // 'excerpt'         => empty($result->post_content) ? '' : implode(' ',array_slice(str_word_count(strip_tags($result->post_content),1),0,40)) . '...',
        'address'         => $result->custom['address'][0],
        'num_beds'        => $result->custom['num_beds'][0],
        'num_baths'       => $result->custom['num_baths'][0],
        'price'           => $result->custom['price'][0],
        'int_price'       => (int)$result->custom['price'][0],
        'lat'             => $result->custom['lat'][0],
        'lng'             => $result->custom['lng'][0],
        'postal_code'     => $result->custom['postal_code'][0],
        'city'            => $result->custom['city'][0],
        'state'           => $result->custom['state'][0],
        'property_type'   => $result->custom['property_type'][0],
        'status'          => $result->custom['status'][0],
        'img_count'       => 0,
        'img_src'         => array()
      );

      $attach_args = array( 
          'post_parent' => $result->ID, 
          'post_type' 		  => 'attachment', 
    			'post_mime_type'	=> 'image'
      );
      $prop['img_count'] = 0;
  		$post_thumbnail_id = get_post_thumbnail_id( $result->ID );

  		// $size = 'sidebar-thumb'; // 'list-thumb';
  		$size = 'list-thumb';
  		
      if( $attachments = get_children( $attach_args ) ){

        // $prop['img_count']	= count( $attachments );
        foreach ( $attachments as $att_id => $attachment ):
          if( $post_thumbnail_id== $att_id) continue; // skip featured/thumbnail added images
          $prop['img_count']++;
          $img_src = wp_get_attachment_image_src( $attachment->ID, $size );
          $prop['img_src'] = $img_src[0];
          break;       
        endforeach;
      }
      
      apc_store('small_genPropertyArray_' . $result->ID, serialize($prop), 3600);
    }
    else{
      $prop = unserialize($prop);
    }

    return $prop;
  }
  
  public static function genPropertyArray($result){
    // apc_delete('genPropertyArray_' . $result->ID);
    
    if( !$prop = apc_fetch('genPropertyArray_' . $result->ID) ){
      $prop = array(
      
        'permalink'       => get_permalink($result->ID),
        'id'              => $result->ID,
        'slug'            => $result->post_name,
        'name'            => $result->post_title,
        // 'content'            => $result->post_content,
        'excerpt'         => empty($result->post_content) ? '' : $result->post_content,
        // 'excerpt'            => empty($result->post_content) ? '' : implode(' ',array_slice(str_word_count(strip_tags($result->post_content),1),0,40)) . '...',
        'part_of_town'    => $result->custom['part_of_town'][0],
        'address'         => $result->custom['address'][0],
        'occupancy_type'  => $result->custom['occupancy_type'][0],
        'featured'        => $result->custom['featured'][0],
        'square_footage'  => $result->custom['square_footage'][0],
        'neighborhood'  => $result->custom['neighborhood'][0],
        'stories'  => $result->custom['stories'][0],
        'age'  => $result->custom['age'][0],
        'num_beds'  => $result->custom['num_beds'][0],
        'num_baths'  => $result->custom['num_baths'][0],
        'price'  => $result->custom['price'][0],
        'parking'  => $result->custom['parking'][0],
        'fridge'  => $result->custom['fridge'][0],
        'oven'  => $result->custom['oven'][0],
        'dwasher'  => $result->custom['dwasher'][0],
        'forced_air'  => $result->custom['forced_air'][0],
        'central_air'  => $result->custom['central_air'][0],
        'central_heat'  => $result->custom['central_heat'][0],
        'near_shopping'  => $result->custom['near_shopping'][0],
        'near_school'  => $result->custom['near_school'][0],
        'near_transit'  => $result->custom['near_transit'][0],
        'handicap_accessible'  => $result->custom['handicap_accessible'][0],
        'lease_option'  => $result->custom['lease_option'][0],
        'aware_compliant'  => $result->custom['aware_compliant'][0],
        'development'     => $result->custom['development'][0],
        'property_type'     => $result->custom['property_type'][0],
        'status'     => $result->custom['status'][0],
        'project'         => $result->custom['project'][0],
        'lat'             => $result->custom['lat'][0],
        'lng'             => $result->custom['lng'][0],
        'postal_code'             => $result->custom['postal_code'][0],
        'city'             => $result->custom['city'][0],
        'state'             => $result->custom['state'][0],
      
        'garage'             => $result->custom['garage'][0],
        'project_type'             => $result->custom['project_type'][0],
        'wd_hookup'             => $result->custom['wd_hookup'][0],
        'laundry'             => $result->custom['laundry'][0],
        'balcony'             => $result->custom['balcony'][0],
        'deck'             => $result->custom['deck'][0],
        'patio'             => $result->custom['patio'][0],
        'yard'             => $result->custom['yard'][0],
        'hardwood_floors'             => $result->custom['hardwood_floors'][0],
        'cable'             => $result->custom['cable'][0],
        'resident_services'             => $result->custom['resident_services'][0],
        'playground'             => $result->custom['playground'][0],
        'management_company'             => $result->custom['management_company'][0],
        'management_phone'             => $result->custom['management_phone'][0],
        'management_web'             => $result->custom['management_web'][0],
        'alarm_system'             => $result->custom['alarm_system'][0],
      
        'tax_incentives'             => $result->custom['tax_incentives'][0],
        'first_floor_master'             => $result->custom['first_floor_master'][0],
        'dining_room'             => $result->custom['dining_room'][0],
        'laundry_room'             => $result->custom['laundry_room'][0],
      
        'pets_allowed'             => $result->custom['pets_allowed'][0],
        'senior_community'             => $result->custom['senior_community'][0],
        'paid_utilities'             => $result->custom['paid_utilities'][0],

        'short_term_lease'             => $result->custom['short_term_lease'][0],
        'on_site_maintenance'             => $result->custom['on_site_maintenance'][0],
        'alarm_system'             => $result->custom['alarm_system'][0],
      
        'fireplace'             => $result->custom['fireplace'][0],
        'den_office'             => $result->custom['den_office'][0],
        'family_room'             => $result->custom['family_room'][0],
      
        'cul_de_sac'             => $result->custom['cul_de_sac'][0],
        'lot_size'             => $result->custom['lot_size'][0],
        'corner_lot'             => $result->custom['corner_lot'][0],
        'basement'             => $result->custom['basement'][0],
      
      
        'img_count'     => 0,
        'img_src'          => array()
      );
    
      $attach_args = array( 
          'post_parent' => $result->ID, 
          'post_type' 		  => 'attachment', 
    			'post_mime_type'	=> 'image'
      );
      $prop['img_count'] = 0;
  		$post_thumbnail_id = get_post_thumbnail_id( $result->ID );
	  
  		$size = 'list-thumb';
  		// $size = 'sidebar-thumb'; // 'list-thumb';
  		
  		
      if( $attachments = get_children( $attach_args ) ){
      
        // $prop['img_count']	= count( $attachments );
        foreach ( $attachments as $att_id => $attachment ):
          if( $post_thumbnail_id== $att_id) continue; // skip featured/thumbnail added images
          $prop['img_count']++;
          $img_src = wp_get_attachment_image_src( $attachment->ID, $size );
          $prop['img_src'] = $img_src[0];
          break;       
        endforeach;
      }
      apc_store('genPropertyArray_' . $result->ID, serialize($prop), 3600);
    }
    else{
      $prop = unserialize($prop);
    }
    return $prop;
  }
  
  // bootstrapping the type
  public static function register_property_type(){
	
  	register_post_type( 
  		'property', 
  		array(
  			'labels' 				=> array(
  				'name' 					=> _x( 'View Properties', 'post type general name' ),
  				'singular_name' 		=> _x( 'Property', 'post type singular name' ),
  				'add_new' 				=> _x( 'New Property', 'Property' ),
  				'add_new_item' 			=> __( 'Add New ' . 'Property' ),
  				'edit_item' 			=> __( 'Edit ' . 'Property' ),
  				'new_item' 				=> __( 'New ' . 'Property' ),
  				'view_item' 			=> __( 'View ' . 'Property' ),
  				'search_items' 			=> __( 'Search ' . 'Properties' ),
  				'not_found' 			=>  __( 'Property' . ' not found'),
  				'not_found_in_trash' 	=> __( 'Property' . ' not found in Trash'),
  				'parent_item_colon' 	=> ''
  			),
  			'public' 				=> true,
  			'publicly_queryable' 	=> true,
  			'show_ui' 				=> true,
  			'query_var' 			=> true,
  			'rewrite' => array( 
  				'slug' => '/properties',
  				'with_front' => false  
  			),
  			'capability_type' 		=> 'post',
  			'hierarchical' 			=> false,
  			'menu_position' 		=> -30,
  			'supports' 				=> array( 'title', 'editor', 'author', 'excerpt', 'thumbnail' ),
  		)
  	);
	
  	flush_rewrite_rules( false );
	  
  }

  public static function property_define_type_ui(){
    
    
    if(isset($_GET['post'])){
      $post = get_post($_GET['post']);
      $post_type = $post->post_type;
    }
    else{
      $post_type = $_GET['post_type'];
    }
    
	  if( $post_type!="property") return; 
	  
  	add_meta_box( 
  		'property-info', 
  		'Property Details', 
  		array( 'Property', 'property_ui_form' ), 
  		'property', 
  		'normal', 
  		'high'
  	);
	
  }

  public static function property_ui_form( $post=false ){
	  
  	if( !$post )
  		global $post;
	
	  if( !$post )
	    $post = get_post( $_REQUEST['post'] );
	    
  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return false;

  	// move request vars and nonce to included file
	
  	$type_ui_file = dirname(__FILE__) . '/property.form.php';
  	require $type_ui_file;
	
  }

  function remove_all_media_buttons()
  {
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );
      
    if($post->post_type!="property") return;
    
    remove_all_actions('media_buttons');
  }
  
  /**
   * function change_title_text
   *
   * @return void
   * @author Michael Reed
   **/
  public static function change_title_text( $title ){
  	global $post;
  	if(!$post)
      $post = get_post( $_REQUEST['post'] );
      
  	if( "property"!=$post->post_type ) return $title;

  	return 'Enter property name';
  } // END function
  
  
  /**
   * function on_post_save
   *
   * @return void
   * @author Michael Reed
   **/
  public static function on_post_save( $post_id )
  {
    
    // don't auto save custom post items
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

  	$post = get_post( $post_id );
  	if( 'property' != $post->post_type ) return $post_id;

  	if ( !wp_verify_nonce($_POST['wp_meta_box_nonce'], NONCE_STRING ) ) return $post_id;

  	// can the user do this? if not, bail...
  	if ( !current_user_can( 'edit_posts', $post_id ) ) return $post_id;

  	if( count( $_REQUEST ) > 0 ){
  	  apc_delete('get_all_properties_idx');
  	  apc_delete('property_type');
  	  
  	  apc_delete('all_property_type_Rent_ids');
  	  apc_delete('all_property_type_Sale_ids');
  	  
  	  apc_delete( 'idx_post_' . $post_id);
  	  apc_delete('small_genPropertyArray_' . $post_id );
  	  apc_delete('genPropertyArray_' . $post_id );
  	  
  	  // ThemeUtil::mpuke_ta(print_r($_REQUEST, 1)); exit;
  	  // ThemeUtil::log_something(print_r($_REQUEST, 1));
  	  
  	  ThemeAdmin::idx_and_save( $post_id, 'garage', $_REQUEST['garage'] );
  	  ThemeAdmin::idx_and_save( $post_id, 'neighborhood', $_REQUEST['neighborhood'] );
  	  ThemeAdmin::idx_and_save( $post_id, 'part_of_town', $_REQUEST['part_of_town'] );
      
    	$address 		        = $_REQUEST['address']; 
    	update_post_meta( $post_id, 'address', $address );
    	
    	$lat 		        = $_REQUEST['lat']; 
    	update_post_meta( $post_id, 'lat', $lat );
    	
    	$lng 		        = $_REQUEST['lng']; 
    	update_post_meta( $post_id, 'lng', $lng );
    	
    	// update_post_meta( $post_id, 'city', $_REQUEST['city'] );
    	ThemeAdmin::idx_and_save( $post_id, 'city', $_REQUEST['city'] );
    	
     	update_post_meta( $post_id, 'state', $_REQUEST['state'] );
    	
    	ThemeAdmin::idx_and_save( $post_id, 'postal_code', $_REQUEST['postal_code'] );
    	
      // $occupancy_type    = $_REQUEST['occupancy_type']; 
      //       update_post_meta( $post_id, 'occupancy_type', $occupancy_type );
      ThemeAdmin::idx_and_save( $post_id, 'occupancy_type', $_REQUEST['occupancy_type'] );
      
    	$featured 		      = $_REQUEST['featured']; 
    	update_post_meta( $post_id, 'featured', $featured );
    	
      // $square_footage    = $_REQUEST['square_footage']; 
      // update_post_meta( $post_id, 'square_footage', $square_footage );
      ThemeAdmin::idx_and_save( $post_id, 'square_footage', $_REQUEST['square_footage'] );
    	
      // $stories     = $_REQUEST['stories']; 
      // update_post_meta( $post_id, 'stories', $stories );
      ThemeAdmin::idx_and_save( $post_id, 'stories', $_REQUEST['stories'] );
    	
    	ThemeAdmin::idx_and_save( $post_id, 'hardwood_floors', $_REQUEST['hardwood_floors'] );
    	
    	$age 		    = $_REQUEST['age']; 
    	update_post_meta( $post_id, 'age', $age );
    	// ThemeAdmin::idx_and_save( $post_id, 'stories', $_REQUEST['stories'] );
    	
    	$num_beds 		    = $_REQUEST['num_beds']; 
    	update_post_meta( $post_id, 'num_beds', $num_beds );
    	
    	$num_baths 		    = $_REQUEST['num_baths']; 
    	update_post_meta( $post_id, 'num_baths', $num_baths );
    	
    	$price 		    = $_REQUEST['price']; 
      update_post_meta( $post_id, 'price', $price );
      
      ThemeAdmin::idx_and_save( $post_id, 'parking', $_REQUEST['parking'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'fridge', $_REQUEST['fridge'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'oven', $_REQUEST['oven'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'dwasher', $_REQUEST['dwasher'] );
      
      ThemeAdmin::idx_and_save( $post_id, 'forced_air', $_REQUEST['forced_air'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'central_air', $_REQUEST['central_air'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'central_heat', $_REQUEST['central_heat'] );
      
      ThemeAdmin::idx_and_save( $post_id, 'near_shopping', $_REQUEST['near_shopping'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'near_school', $_REQUEST['near_school'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'near_transit', $_REQUEST['near_transit'] );
      
      ThemeAdmin::idx_and_save( $post_id, 'handicap_accessible', $_REQUEST['handicap_accessible'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'lease_option', $_REQUEST['lease_option'] );
    	
      ThemeAdmin::idx_and_save( $post_id, 'aware_compliant', $_REQUEST['aware_compliant'] );
      
     // ThemeAdmin::idx_and_save( $post_id, 'development', $_REQUEST['development'] );
	 
		delete_post_meta($post_id, 'development');
		if(is_array($_REQUEST['development'])) {
			foreach($_REQUEST['development'] as $d) {
				add_post_meta( $post_id, 'development', $d );
			}
		}
      
      ThemeAdmin::idx_and_save( $post_id, 'status', $_REQUEST['status'] );
    	
    	// ThemeAdmin::idx_and_save( $post_id, 'project', $_REQUEST['project'] );
    	
    	ThemeAdmin::idx_and_save( $post_id, 'property_type', $_REQUEST['property_type'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'project_type', $_REQUEST['project_type'] );
       
     	 
     	 ThemeAdmin::idx_and_save( $post_id, 'wd_hookup', $_REQUEST['wd_hookup'] );
     	
       ThemeAdmin::idx_and_save( $post_id, 'laundry', $_REQUEST['laundry'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'balcony', $_REQUEST['balcony'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'deck', $_REQUEST['deck'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'patio', $_REQUEST['patio'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'yard', $_REQUEST['yard'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'cable', $_REQUEST['cable'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'resident_services', $_REQUEST['resident_services'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'playground', $_REQUEST['playground'] );

       update_post_meta( $post_id, 'management_company', $_REQUEST['management_company'] );
       
       update_post_meta( $post_id, 'management_phone', $_REQUEST['management_phone'] );
       
       update_post_meta( $post_id, 'management_web', $_REQUEST['management_web'] );

       ThemeAdmin::idx_and_save( $post_id, 'pets_allowed', $_REQUEST['pets_allowed'] );

       ThemeAdmin::idx_and_save( $post_id, 'senior_community', $_REQUEST['senior_community'] );
      	 
       ThemeAdmin::idx_and_save( $post_id, 'paid_utilities', $_REQUEST['paid_utilities'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'short_term_lease', $_REQUEST['short_term_lease'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'on_site_maintenance', $_REQUEST['on_site_maintenance'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'alarm_system', $_REQUEST['alarm_system'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'tax_incentives', $_REQUEST['tax_incentives'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'first_floor_master', $_REQUEST['first_floor_master'] );

       ThemeAdmin::idx_and_save( $post_id, 'dining_room', $_REQUEST['dining_room'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'laundry_room', $_REQUEST['laundry_room'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'basement', $_REQUEST['basement'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'family_room', $_REQUEST['family_room'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'den_office', $_REQUEST['den_office'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'fireplace', $_REQUEST['fireplace'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'corner_lot', $_REQUEST['corner_lot'] );
       
       ThemeAdmin::idx_and_save( $post_id, 'cul_de_sac', $_REQUEST['cul_de_sac'] );
       
       update_post_meta( $post_id, 'lot_size', $_REQUEST['lot_size'] );
    
       // $all_props = 
  	}
  	
  }// END function
  
  public static function sc_available_properties($attr) {

  	extract(shortcode_atts(array(
  		
  	), $attr));
  
  
  }
}

/* actions */
add_action( 'init', 				                      array( 'Property', 'register_property_type' ) ); 
add_action( 'add_meta_boxes', 		                array( 'Property', 'property_define_type_ui' ) );
add_filter( 'enter_title_here',                   array( 'Property', 'change_title_text' ) );	
add_action( 'save_post', 			                    array( 'Property', 'on_post_save' ) );
add_action( 'add_meta_boxes',                     array( 'Property', 'remove_all_media_buttons') );
add_action( 'wp_ajax_queryprops', 		            array( 'Property', 'query_properties' ) );
add_action( 'wp_ajax_nopriv_queryprops', 		      array( 'Property', 'query_properties' ) );

// ThemeUtil::log_something( "property class loaded..." );

add_shortcode( 'available_properties', 	array( 'Property', 'sc_available_properties' ) );

// ThemeUtil::log_something("propert type file end: " . get_execution_time() );