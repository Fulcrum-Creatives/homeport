<?php
  $nonce = wp_create_nonce( NONCE_STRING );
?>
<div id="admin-reposition">
  <input type="hidden" name="wp_meta_box_nonce" value="<?php echo $nonce; ?>" />
  <div class="custom-admin">
    <div class="postarea postimages">
  		<h2>Development Images&nbsp;<img style="width:16px;height:16px;display:none;" 
  		  id="img-loading" src="<?php echo THEME_IMG_URL . '/loading.gif'; ?>" /></h2>
  		<div id="file-uploader">       
  		    <noscript>          
  		        <p>Please enable JavaScript to use file uploader.</p>
  		        <!-- or put a simple form for upload here -->
  		    </noscript>         
  			<div class="clearfix"></div>
  		</div>
  		<div class="clearfix"></div>
  		<div id="developments_images">
	
  			<div class="clearfix"></div>
  		</div>
  	</div>	
  	<div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>