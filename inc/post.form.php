<?php
	
	$event_types = get_option('event_type');
	$custom = get_post_custom( $post->ID );
	
	if(!empty($custom)){
		// ThemeUtil::mpuke($custom);
		$featured_post 		= $custom["featured_post"][0];
		$is_event_multiday 		    = $custom["is_event_multiday"][0] == 2;
		$is_event 		    = $custom["is_event"][0] && !$is_event_multiday;
		$has_rsvp 		    = $custom["has_rsvp"][0];
		$event_home_sticky 		= $custom["event_home_sticky"][0];
		$event_type 		    = $custom["event_type"][0];
		$event_type_multiday    = $custom["event_type"][0];
		$event_date       = $custom["event_date"][0];
		$event_date_multiday    = $custom["event_date_multiday"][0];
		$event_date_to = $custom["event_date_to"][0];
		
		$event_hours      = $custom["event_hours"][0];
		if(!empty($custom["event_from_time"][0])) {
			$event_from_time      = $custom["event_from_time"][0];
			if(!empty($custom["event_to_time"][0])) {
				$event_to_time      = $custom["event_to_time"][0];
				$event_hours = "From {$event_from_time} to {$event_to_time}";
			}
			else {
				$event_hours = "{$event_from_time}";
			}
		}
		
	}
	$is_checked =     ($is_event==1) ? "block" : "none";
	$is_checked_multiday =     ($is_event_multiday==1) ? "block" : "none";
	
	$nonce = wp_create_nonce( NONCE_STRING );
?>
<div id="admin-reposition">
  <input type="hidden" name="wp_meta_box_nonce" value="<?php echo $nonce; ?>" />
  
  <div class="custom-admin">
    <div class="postarea" style="float:left;width:33%">
  	  <input type="checkbox" name="featured_post" id="featured_post" value="1" <?php
  	    if(1==$featured_post) echo " CHECKED ";
  	  ?>/>
  	  <label for="current">Featured</label>&nbsp;
  	</div>
  
    <div class="postarea" style="float:left;width:33%">
  	  <input type="checkbox" name="is_event" id="is_event" value="1" <?php
  	    if($is_event) echo " CHECKED ";
  	  ?>/>
  	  <label for="is_event">Post is single day event</label>&nbsp;
  	</div>
    <div class="postarea" style="float:left;width:33%">
  	  <input type="checkbox" name="is_event_multiday" id="is_event_multiday" value="2" <?php
  	    if($is_event_multiday) echo " CHECKED ";
  	  ?>/>
  	  <label for="is_event_multiday">Post is multi-day event</label>&nbsp;
  	</div>
  	<div class="clearfix"></div>
 
	
  	<div style="display:<?php echo $is_checked; ?>" id="event_fields">
	 <div class="postarea" style="float:left;width:50%">
        <div class="titlediv">
      		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" 
      		  for="event_date">Event Date</label>
      		<input type="text" id="event_date" name="event_date" class="prompt-text datepick"
      			value="<?php echo $event_date; ?>" />
      	</div>
      </div>
    	<div class="postarea" style="float:left;width:50%">
        <div class="titlediv">
      		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" for="event_hours">Event Time</label>
      		<input type="text" id="event_hours" name="event_hours" class="prompt-text"
      			value="<?php echo $event_hours; ?>" />
			<div>
			From
			<select name="event_from_time" id="event_from_time">
	<option value=""></option>
			<?php
				for($hour = 5; $hour < 24; $hour++) {
					for($minute = 0; $minute < 60; $minute += 15) {
						$print_hour = $hour % 12 == 0 ? "12" : $hour % 12;
						$am_pm = $hour > $print_hour || $print_hour == 12 ? "PM" : "AM";
						$print_minute = $minute == 0 ? "00" : $minute;
						$time = "{$print_hour}:{$print_minute} {$am_pm}";
						$current = $event_from_time == $time ? "selected='selected'" : "";
						echo "<option value='{$time}' {$current} >{$time}</option>";
					}
				}
			?>
</select>
</div><div>
			To
			<select name="event_to_time" id="event_to_time">
	<option value=""></option>
			<?php
				for($hour = 5; $hour < 24; $hour++) {
					for($minute = 0; $minute < 60; $minute += 15) {
						$print_hour = $hour % 12 == 0 ? "12" : $hour % 12;
						$am_pm = $hour > $print_hour || $print_hour == 12 ? "PM" : "AM";
						$print_minute = $minute == 0 ? "00" : $minute;
						$time = "{$print_hour}:{$print_minute} {$am_pm}";
						$current = $event_to_time == $time ? "selected='selected'" : "";
						echo "<option value='{$time}' {$current} >{$time}</option>";
					}
				}
			?>
</select>
				</div>
      	</div>
      </div>
    	<div class="clearfix"></div>
      
      
      <!-- <div class="postarea" style="float:left;width:50%">
        <input type="checkbox" name="has_rsvp" id="has_rsvp" value="1" <?php
          if(1==$has_rsvp) echo " CHECKED ";
        ?>/>
        <label for="current">Allows RSVP/Signup</label>&nbsp;
      </div> -->
    	<div class="clearfix"></div>  
    </div>	
    
  	<div style="display:<?php echo $is_checked_multiday; ?>" id="event_fields_multiday">
	 <div class="postarea" style="float:left;width:50%">
        <div class="titlediv">
      		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" 
      		  for="event_date_multiday">Event Date Start</label>
      		<input type="text" id="event_date_multiday" name="event_date_multiday" class="prompt-text datepick"
      			value="<?php echo $event_date_multiday; ?>" />
      	</div>
      </div>
    	<div class="postarea" style="float:left;width:50%">
        <div class="titlediv">
      		<label class="hide-if-no-js fancy-label" style="visibility:hidden;padding-left:20px;" 
      		  for="event_date_to">Event Date End</label>
      		<input type="text" id="event_date_to" name="event_date_to" class="prompt-text datepick"
      			value="<?php echo $event_date_to; ?>" />
      	</div>
      </div>
    	<div class="clearfix"></div>  
    </div>	
    
	<div style="display:<?php echo "block" ?>" id="event_fields_both">	
				
			<div class="postarea" style="float:right;width:50%">
        <select name="event_type" id="event_type">
    		  <option value="">Event Type</option>
    		  <?php foreach( $event_types as $e_type ): ?>
    				<option value="<?php echo $e_type; ?>"
    					<?php
    						if( $e_type == $event_type )
    							echo ' SELECTED ';
    					?>>
    					<?php echo $e_type; ?></option>

    			<?php endforeach; ?>
    		</select>
				</div>
				
			<div class="postarea" style="float:left;width:50%">
   

	  <div><input type='checkbox' <?php if($event_home_sticky) echo "checked='checked'"; ?> value='event_home_sticky' name='event_home_sticky' id='event_home_sticky' /><label for='event_home_sticky' style='font-color:black !important;'>Sticky on home page</label></div>
		   </div>	
	</div>
	
    <div class="clearfix"></div>
      
  	<hr/>
    <div class="postarea postimages">
  		<h2>Post Images&nbsp;<img style="width:16px;height:16px;display:none;" 
  		  id="img-loading" src="<?php echo THEME_IMG_URL . '/loading.gif'; ?>" /></h2>
  		<div id="file-uploader">       
  		    <noscript>          
  		        <p>Please enable JavaScript to use file uploader.</p>
  		        <!-- or put a simple form for upload here -->
  		    </noscript>         
  			<div class="clearfix"></div>
  		</div>
  		<div class="clearfix"></div>
  		<div id="post_images">

  			<div class="clearfix"></div>
  		</div>
  	</div>	
  	<div class="clearfix"></div>
	</div>

</div>