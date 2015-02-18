<div class="custom-admin">
	<div id="tabs">
		<ul>
			<li><a href="#tabs-settings">Settings</a></li>
      <li><a href="#tabs-data-manager">Property Settings</a></li>
      <li><a href="#tabs-vol-data-manager">Volunteer Settings</a></li>
      <li><a href="#tabs-vol-tools-export">Volunteer Tools/Export</a></li>
			<li><a href="#tabs-featured-posts">Featured Posts</a></li>
			<li><a href="#tabs-event-manager">Event Settings</a></li>
			<li><a href="#tabs-rental-manager">Rental Price Manager</a></li>
			<!-- <li><a href="#tabs-extras">Extras</a></li> -->
		</ul>
		<div id="tabs-settings">
			<?php
  			$args = array( 
  				'method'		=> 'get_options', // fetches options data to know what to save, is function name that returns options array
  				'action'		=> 'save_settings' /*
  				  add hidden form input:
  				  <input type="hidden" name="theme_save_action" value="<?php echo $action; ?>" />
  				*/
  			);
  			call_user_func( array( 'ThemeOptions', 'save_options'), $args );
  			call_user_func( array( 'ThemeOptions', 'show_option_form'), $args );
			?>
		</div>
		<div id="tabs-data-manager">
		    <?php
    			$args = array( 
    				'method'		=> 'get_data_options',
    				'action'		=> 'save_data_manager'
    			);
    			call_user_func( array( 'ThemeOptions', 'save_options'), $args );
    			call_user_func( array( 'ThemeOptions', 'show_option_form'), $args );
  			?>
		</div>
		<div id="tabs-vol-data-manager">
		    <?php
    			$args = array( 
    				'method'		=> 'get_vol_data_options',
    				'action'		=> 'save_vol_data_manager'
    			);
    			call_user_func( array( 'ThemeOptions', 'save_options'), $args );
    			call_user_func( array( 'ThemeOptions', 'show_option_form'), $args );
  			?>
		</div>
		<div id="tabs-vol-tools-export">
      VOL. TOOLS / EXPORT
    </div>
		<div id="tabs-featured-posts">
      <?php
        ThemeAdmin::featured_post_ui();
      ?>
    </div>
    <div id="tabs-event-manager">
      <?php
  			$args = array( 
  				'method'		=> 'get_event_options',
  				'action'		=> 'save_event_manager'
  			);
  			call_user_func( array( 'ThemeOptions', 'save_options'), $args );
  			call_user_func( array( 'ThemeOptions', 'show_option_form'), $args );
			?>
    </div>
    <div id="tabs-rental-manager">
      <?php
        ThemeAdmin::rental_manager_ui();
      ?>
    </div>
    <!-- <div id="tabs-extras">
      EXTRAS
    </div> -->
	</div>
</div>