<?php
/**
 * Template Name: Property Search Page
 */
// apc_store('foo', 'bar', 300);
// $memcache_obj = memcache_connect("localhost", 11211);
get_header(); ?>
<!-- property-search.php -->
  <div>
    <?php
      $property_type_idx = ThemeAdmin::get_idx( 'property_type', 'Sale' );
    ?>
  </div>
  <div id="page-wrap" class="property-search">
    <div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?> property-search">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
    	  <form id="search-form">
    	  <div id="property-search-form">
      	  
      	  <div id="property-search-type" class="property-search-line">
      	    <!-- <h3 class="avenir">Property Search</h3> -->
        	  <label>I'm looking for:</label>
        	  <select name="property_type" id="property-type">
        	    <?php
        	      if( $pts = get_option('property_type', false) ):
                  if( $_REQUEST['property_type'] == 'rent') {
                    ?>
                      <option value="Sale">Homes For Sale</option>
                      <option value="Rent" selected>Homes For Rent</option>
                    <?php
                  } else {
                    ?>
                    <option value="Sale" selected>Homes For Sale</option>
                    <option value="Rent">Homes For Rent</option>
                    <?php
                  }
        	        /*foreach( $pts as $pt ):?>
        	          <option value="<?php echo $pt;?>" 
        	            <?php if(!empty($_REQUEST['property_type']) &&  $pt==$_REQUEST['property_type']):
        	              echo ' SELECTED ';
        	            endif; ?>
        	            >Homes For <?php echo $pt;?>
        	            </option>
        	        <?php endforeach; */
        	      endif;
        	    ?>
        	  </select>
      	  </div>
      	  
      	  <div id="property-search-price" class="property-search-line">
        	  <label>Price Range:</label>
        	  <input type="text" id="property-price-low" name="property_price_low" onfocus="if(this.value == 'min') { this.value = ''; }" value="min" />
        	  <input type="text" id="property-price-high" name="property_price_high" onfocus="if(this.value == 'max') { this.value = ''; }" value="max" />
      	  </div>
      	  
      	  <div id="property-search-neighborhood" class="property-search-line">
      	    <label>In</label>
      	    <select name="property_neighborhood" id="property-neighborhood">
        	    <option value="">Neighborhood</option>
        	    <?php
        	      if( $nts = get_option('neighborhood_type', false) ):
        	        foreach( $nts as $nt ):?>
        	          <option value="<?php echo $nt;?>"><?php echo $nt;?></option>
        	        <?php endforeach;
        	      endif;
        	    ?>
        	  </select>
      	  </div>
      	  
      	  <div id="property-search-zip" class="property-search-line">
      	    <label>or Zipcode</label>
      	    <input type="text" id="property-zip" name="property_zip" value="" />
      	  </div>
      	    
      	  <div id="property-search-bed" class="property-search-line">
      	    <label>Bedrooms: </label>
      	    <input type="text" id="property-beds" name="property_beds" value="" />
      	  </div>
      	  
      	  <div id="property-search-bath" class="property-search-line">
      	    <label>Bathrooms: </label>
      	    <input type="text" id="property-baths" name="property_baths" value="" />
      	  </div>
      	  <?php $property_status_idx = $_GET['property_status']; ?>
		  <input type="hidden" name="property_status" id="property_status" value="<?php echo htmlentities($property_status_idx,ENT_QUOTES); ?>" />
		  <!---
      	  <div id="property-search-status" class="property-search-status">
      	    <label>Status: </label>
			    <select name="property_status" id="property-status">
        	    <option value="">&nbsp;</option>
        	    <?php
				$status_types        = get_option('status_type');
        	        foreach( $status_types as $nt ) { ?>
        	          <option value="<?php echo $nt;?>" <?php if($nt == $property_status_idx) { echo " SELECTED='SELECTED' "; } ?> ><?php echo $nt;?></option>
        	        <?php }
        	    ?>
        	  </select>
      	  </div>
		  -->
      	  
      	  <div id="property-search-submit">
      	    <input type="submit" value="Search" id="search-submit" />
      	  </div>
      	  </form>  
      	  <div id="property-search-adv">
      	    <a href="#" id="adv-search">Advanced Search</a>
      	  </div>
        </div>
        <div id="property-search-count">
          
        </div>
    	</div><!-- #content -->
    </div><!-- #primary --> 
    
    <div id="propertySearchCanvas">
      <div id="mapCanvas-wrap">
        <div id="mapCanvas"></div>
      </div>
    </div>
    <div class="clearfix" style="height:0px"></div>
    <div id="propertySearchScroll"></div>
    
  </div><!-- #page-wrap -->
  
  
  <div id="search-adv-dialog" style="display:none">
    <form id="adv-search-form">
    <div id="property-search-form-adv">
  	  <div id="search-form-adv-left">
    	  <div id="property-search-development" class="property-search-line">
  	      <select name="development" id="development">
  	        <option value="">Development</option>
      	    <?php
    	        foreach( $development_types as $dt ):?>
    	          <option value="<?php echo $dt;?>"><?php echo $dt;?></option>
    	        <?php endforeach;
      	    ?>
      	  </select>
    	  </div>
    	  
    	  <div class="clearfix"></div>
    	  
    	  <div id="property-search-footage" class="property-search-line">
      	  <label>Square Footage:</label>
      	  <input type="text" id="square-footage-low" name="square_footage_low" value="" />
      	  <input type="text" id="square-footage-high" name="square_footage_high" value="" />
    	  </div>
    	  
    	  <div class="clearfix"></div>
    	  
    	  <div>
      	  <div id="property-search-stories" class="property-search-line">
        	  <select name="stories" id="stories">
    	        <option value="">Stories</option>
    	        <option value="Bi-Level">Bi-Level</option>
    	        <option value="1">1</option>
    	        <option value="1.5">1.5</option>
    	        <option value="2">2</option>
    	        <option value="2.5">2.5</option>
    	        <option value="3">3</option>
    	        <option value="4">4</option>
    	      </select>
      	  </div>
    	  
      	  <div id="property-search-age" class="property-search-line">
        	  <select name="age" id="age">
    	        <option value="">Age</option>
    	        <option value="0">New</option>
    	        <option value="10">Under 10 Years</option>
    	        <option value="15">Under 15 Years</option>
    	        <option value="25">Under 25 Years</option>
    	        <option value="35">Under 35 Years</option>
    	        <option value="50">Under 50 Years</option>
    	        <option value="100">Under 100 Years</option>
    	      </select>
      	  </div>
          <div class="clearfix"></div>
    	  </div>
        
    	  <div id="property-search-project" class="property-search-line">
    	    <select name="project_type" id="project_type">
  	        <option value="">Property Type</option>
      	    <?php
      	      $prop_types  = get_option('project_type');
    	        foreach( $prop_types as $type ):?>
    	          <option value="<?php echo $type;?>"><?php echo $type;?></option>
    	        <?php endforeach;
      	    ?>
      	  </select>
    	  </div>
    	  
    	  <div id="property-search-occupancy" class="property-search-line">
    	    <select name="occupancy_type" id="occupancy_type">
  	        <option value="">Occupancy Type</option>
      	    <?php
      	      $all_occupancy_types = get_option('occupancy_type');
    	        foreach( $all_occupancy_types as $occupancy_types ):?>
    	          <option value="<?php echo $occupancy_types;?>"><?php echo $occupancy_types;?></option>
    	        <?php endforeach;
      	    ?>
      	  </select>
    	  </div>
    	  
    	  <div id="property-search-pot" class="property-search-line">
    	    <select name="part_of_town" id="part_of_town">
  	        <option value="">Part of Town</option>
      	    <?php
      	      $parts_of_town     = get_option('part_of_town');
    	        foreach( $parts_of_town as $part_of_town ):?>
    	          <option value="<?php echo $part_of_town;?>"><?php echo $part_of_town;?></option>
    	        <?php endforeach;
      	    ?>
      	  </select>
    	  </div>
    	
    	  <div id="property-search-garage" class="property-search-line">
      	  <select name="garage" id="garage">
      		  <option value="">Garage</option>
      		  
      		  <?php 
      		    $garage_types        = get_option('garage_type');
      		    foreach( $garage_types as $type_choice ): ?>

      				<option value="<?php echo $type_choice; ?>"
      					<?php
      						if( $type_choice == $garage )
      							echo ' SELECTED ';
      					?>>
      					<?php echo $type_choice; ?></option>

      			<?php endforeach; ?>
      		</select>
      	</div>
        <!--
        <div id="property-search-excerpt" class="property-search-line">
          <label>Listing Contains:</label>
          <input type="text" id="excerpt" name="excerpt" value="" />
        </div>
        -->
    	  
  	  </div>
  	  <div id="search-form-adv-right">
  	    <div style="width:50%;float:left">
    	    <ul>
    	      <li><input type="checkbox" name="parking" id="parking" value="1" />
        	  <label for="parking">Parking</label></li>
        	  <li><input type="checkbox" name="refrigerator" id="refrigerator" value="1" />
        	  <label for="refrigerator">Refrigerator</label></li>
        	  <li><input type="checkbox" name="oven" id="oven" value="1" />
        	  <label for="oven">Oven</label></li>
        	  <li><input type="checkbox" name="dishwasher" id="dishwasher" value="1" />
        	  <label for="dishwasher">Dishwasher</label></li>
        	  <li><input type="checkbox" name="central_air" id="central_air" value="1" />
        	  <label for="central_air">Central air</label></li>
        	  <li><input type="checkbox" name="forced_air" id="forced_air" value="1" />
        	  <label for="forced_air">Forced air</label></li>
        	  <li><input type="checkbox" name="central_heat" id="central_heat" value="1" />
        	  <label for="central_heat">Central heat</label></li>
        	  <li><input type="checkbox" name="handicap_accessible" id="handicap_accessible" value="1" />
        	  <label for="handicap_accessible">Handicap accessible</label></li>
        	  <li><input type="checkbox" name="near_transit" id="near_transit" value="1" />
        	  <label for="near_transit">Near transit</label></li>
        	  <li><input type="checkbox" name="near_school" id="near_school" value="1" />
        	  <label for="near_school">Near school</label></li>
        	  <li><input type="checkbox" name="near_shopping" id="near_shopping" value="1" />
        	  <label for="near_shopping">Near shopping</label></li>
        	  <li><input type="checkbox" name="lease_option" id="lease_option" value="1" />
        	  <label for="lease_option">Lease option</label></li>
        	  <li><input type="checkbox" name="aware_compliant" id="aware_compliant" value="1" />
        	  <label for="aware_compliant">AWARE compliant</label></li>
        	  <li><input type="checkbox" name="photos" id="photos" value="1" />
        	  <label for="photos">Photos</label></li>
        	  <li><input type="checkbox" name="wd_hookup" id="wd_hookup" value="1" />
        	  <label for="wd_hookup">W/D hook-up</label></li>
        	  <li><input type="checkbox" name="laundry" id="laundry" value="1" />
        	  <label for="laundry">Laundry Facilities</label></li>
        	  <li><input type="checkbox" name="yard" id="yard" value="1" />
        	  <label for="yard">Yard</label></li>
    	      <li><input type="checkbox" name="patio" id="patio" value="1" />
        	  <label for="yard">Patio</label></li>
        	  <li><input type="checkbox" name="balcony" id="balcony" value="1" />
        	  <label for="balcony">Balcony</label></li>
  	      </ul>
    	  </div>
  	    <div style="width:50%;float:left">
    	    <ul>
    	      <li><input type="checkbox" name="cable" id="cable" value="1" />
        	  <label for="cable">Cable hook-up</label></li>
            <li>
              <input type="checkbox" name="resident_services" id="resident_services" value="1" />
              <label for="resident_services">Resident services</label></li>
            <li>
              <input type="checkbox" name="on_site_playground" id="on_site_playground" value="1" />
              <label for="on_site_playground">On-site playground</label></li>
            <li>
              <input type="checkbox" name="alarm_system" id="alarm_system" value="1" />
              <label for="alarm_system">Alarm system</label></li>
            <li>
              <input type="checkbox" name="pets_allowed" id="pets_allowed" value="1" />
              <label for="pets_allowed">Pets allowed</label></li>
            <li>
              <input type="checkbox" name="senior_community" id="senior_community" value="1" />
              <label for="senior_community">Senior community</label></li>
            <li>
              <input type="checkbox" name="paid_utilities" id="paid_utilities" value="1" />
              <label for="paid_utilities">Paid utilities</label></li>
            <li>
              <input type="checkbox" name="short_term_lease" id="short_term_lease" value="1" />
              <label for="short_term_lease">Short-term lease available</label></li>
            <li>
              <input type="checkbox" name="on_site_maintenance" id="on_site_maintenance" value="1" />
              <label for="on_site_maintenance">On-site maintenance</label></li>
            <li>
              <input type="checkbox" name="tax_incentives" id="tax_incentives" value="1" />
              <label for="tax_incentives">Tax incentives</label></li>
            <li>
              <input type="checkbox" name="first_floor_master" id="first_floor_master" value="1" />
              <label for="first_floor_master">First floor master</label></li>
            <li>  <input type="checkbox" name="dining_room" id="dining_room" value="1" />
              <label for="dining_room">Dining room</label></li>
            <li>  <input type="checkbox" name="family_room" id="family_room" value="1" />
              <label for="family_room">Family room</label></li>
            <li>  <input type="checkbox" name="den_office" id="den_office" value="1" />
              <label for="den_office">Den/Office</label></li>
            <li>  <input type="checkbox" name="laundry_room" id="laundry_room" value="1" />
              <label for="laundry_room">Laundry room</label></li>
            <li>  <input type="checkbox" name="basement" id="basement" value="1" />
              <label for="basement">Basement</label></li>
            <li>  <input type="checkbox" name="hardwood_floors" id="hardwood_floors" value="1" />
              <label for="hardwood_floors">Hardwood floors</label></li>
            <li>  <input type="checkbox" name="fireplace" id="fireplace" value="1" />
              <label for="fireplace">fireplace</label></li>
            <li>  <input type="checkbox" name="corner_lot" id="corner_lot" value="1" />
              <label for="corner_lot">Corner lot</label></li>
            <li>  <input type="checkbox" name="cul_de_sac" id="cul_de_sac" value="1" />
              <label for="cul_de_sac">Cul-de-sac</label></li>
              <li>  <input type="checkbox" name="deck" id="deck" value="1" />
                <label for="deck">Deck</label></li>
    	    </ul>
    	  </div>
  	  </div>
  	</div>
  	</form>
  </div>
  
  <div id="searchDebug"></div>
<?php get_footer(); ?>