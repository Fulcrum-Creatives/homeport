<?php

  
  $custom = get_post_custom( $post->ID );
  $event_types = get_option('event_type');
  $event_type 		= $custom["event_type"][0];
  
  $event_current 		= $custom["event_current"][0];

  $nonce = wp_create_nonce( NONCE_STRING );

?>
<input type="hidden" name="wp_meta_box_nonce" value="<?php echo $nonce; ?>" />
<div id="admin-reposition">
  <div class="custom-admin">
    
    <!-- <div class="postarea">
      <input type="checkbox" name="featured_post" id="featured_post" value="1" <?php
        if(1==$featured_post) echo " CHECKED ";
      ?>/>
      <label for="current">Display In Homepage Slider</label>&nbsp;
    </div> -->
  	
  	<div class="clearfix"></div>
  	
  	<div class="titlediv">
  		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="startdate">Start Date</label>
  		<input type="text" id="startdate" name="startdate" class="datepick" 
  			value="<?php echo $startdate; ?>" />
  	</div>
  	
    <!-- <div class="titlediv">
      <label id="opening-label" class="hide-if-no-js fancy-label" 
        style="visibility:hidden" for="opening">Start Time</label>
      <input type="text" id="opening" name="opening" class="timepick" 
        value="<?php echo $opening; ?>" />
    </div> -->
  	
  	<div class="clearfix"></div>
  	
  	<div class="titlediv">
  		<label class="hide-if-no-js fancy-label" style="visibility:hidden" for="enddate">End Date</label>
  		<input type="text" id="enddate" name="enddate" class="datepick" 
  			value="<?php echo $enddate; ?>" />
  	</div>
  	
  	<!-- <div class="clearfix"></div> -->
  	
  	
  	
  	<div class="clearfix"></div>
  	
  	<div class="postarea">
  	  <label for="event_type">Event Type</label>&nbsp;
  		<select name="event_type" id="event_type">
  		  <?php foreach( $event_types as $the_event_type ): ?>

  				<option value="<?php echo $the_event_type; ?>"
  					<?php
  						if( $event_type == $the_event_type )
  							echo ' SELECTED ';
  					?>>
  					<?php echo $the_event_type; ?></option>

  			<?php endforeach; ?>
  		</select>
  	</div>
  	
  	<div class="clearfix"></div>
  	<hr/>
  	<div class="postarea">
  		<label for="file-uploader">Event Images
  		&nbsp;<img style="width:16px;height:16px;display:none;" 
  		  id="img-loading" src="<?php echo get_bloginfo('stylesheet_directory') . '/images/loading.gif'; ?>" /></label>
  		<br/>
  		<div id="file-uploader">       
  		    <noscript>          
  		        <p>Please enable JavaScript to use file uploader.</p>
  		        <!-- or put a simple form for upload here -->
  		    </noscript>         
  			<div class="clearfix"></div>
  		</div>
  		<div class="clearfix"></div>
  		<div id="event_images">

  			<div class="clearfix"></div>
  		</div>
  	</div>	
  	<div class="clearfix"></div>
  	
  </div>
</div>