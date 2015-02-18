<?php
  
  // TO DO: turn off for production
  ini_set( 'display_errors', 1 );

  class ThemeOptions {

  	public static function get_blank_options(){

  		return array (
  			array( 
  				"name" => "General Theme Options",
  				"type" => "page_heading"
  			),

  			array( 
  				"type" => "page_footing",
  				"show_save"	=> FALSE
  			)

  		);
  	}
    
    public static function get_vol_data_options(){
      // $slots_days = get_option('volunteer_slot_days', false);
      $slot_blocks = get_option('volunteer_slot_blocks', false);
      $interests = get_option('volunteer_interests', false);
      
      $types = array(
        array(
          'name'  => 'Volunteer Time Blocks',
          'option_name'  => 'volunteer_slot_blocks',
        ),
        array(
          'name'  => 'Volunteer Interests',
          'option_name'  => 'volunteer_interests',
        ),
      );
      
      $the_options = array (
    		array( 
    			"name" => "Volunteer Manager",
    			"type" => "page_heading"
    		)
    	);
    	
    	foreach( $types as $type ){
        if( $the_type = get_option($type['option_name'], false ) ){
      	  $the_options[] = array(
           "name" => $type['name'],
           "type" => "start_section",
           "show_save" => FALSE,
           "desc"  => $type['desc']
          );

          $the_options[] =  array( 
      				"name" 			=> "",
      				"desc" 			=> "",
      				"id" 			  => $type['option_name'], // THEME_NICE_NAME . "_event_options", (could be cleaner/optimized)
      				"type" 			=> "text_addmore",
      				"std" 			=> ""
      		);

      		$the_options[] = array(
           "type" => "end_section",
           "show_save" => TRUE
          );

        }
      }
    	
      $the_options[] = array(
    			"type" => "page_footing",
    			"show_save"	=> FALSE
      );
      
      return $the_options;
    }
    
    public static function get_event_options(){
    	$types = array(
        array(
          'name'  => 'Event Types',
          'option_name'  => 'event_type',
        )
      );
      
      $the_options = array (
    		array( 
    			"name" => "Event Data Manager",
    			"type" => "page_heading"
    		)
    	);
    	
      foreach( $types as $type ){
        if( $the_type = get_option($type['option_name'], false ) ){
      	  $the_options[] = array(
           "name" => $type['name'],
           "type" => "start_section",
           "show_save" => FALSE,
           "desc"  => $type['desc']
          );

          $the_options[] =  array( 
      				"name" 			=> "",
      				"desc" 			=> "",
      				"id" 			  => $type['option_name'], // THEME_NICE_NAME . "_event_options", (could be cleaner/optimized)
      				"type" 			=> "text_addmore",
      				"std" 			=> ""
      		);

      		$the_options[] = array(
           "type" => "end_section",
           "show_save" => TRUE
          );

        }
      }
    	
    	
    	
      $the_options[] = array(
    			"type" => "page_footing",
    			"show_save"	=> FALSE
      );
      
      return $the_options;
    }
    
    
    public static function get_data_options(){
      
    	$types = array(
        array(
          'name'  => 'Occupancy Types',
          'option_name'  => 'occupancy_type',
        ),
        array(
          'name'  => 'Status Types',
          'option_name'  => 'status_type',
        ),
        array(
          'name'  => 'Project Types',
          'option_name'  => 'project_type',
        ),
        // array(
        //   'name'  => 'Development Types',
        //   'option_name'  => 'development_type',
        // ),
        array(
          'name'  => 'Parts Of Town',
          'option_name'  => 'part_of_town',
        ),
        array(
          'name'  => 'Property Types',
          'option_name'  => 'property_type',
        ),
        array(
          'name'  => 'Neighborhoods',
          'option_name'  => 'neighborhood_type',
        ),
      );
      
      $the_options = array (
    		array( 
    			"name" => "Property Data Manager",
    			"type" => "page_heading"
    		)
    	);
    	
      foreach( $types as $type ){
        if( $the_type = get_option($type['option_name'], false ) ){
      	  $the_options[] = array(
           "name" => $type['name'],
           "type" => "start_section",
           "show_save" => FALSE,
           "desc"  => $type['desc']
          );

          $the_options[] =  array( 
      				"name" 			=> "",
      				"desc" 			=> "",
      				"id" 			  => $type['option_name'], // THEME_NICE_NAME . "_event_options", (could be cleaner/optimized)
      				"type" 			=> "text_addmore",
      				"std" 			=> ""
      		);

      		$the_options[] = array(
           "type" => "end_section",
           "show_save" => TRUE
          );

        }
      }
    	
      $the_options[] = array(
    			"type" => "page_footing",
    			"show_save"	=> FALSE
      );
      
      return $the_options;
    }
    
  	public static function get_options(){
      $the_options = array (
    		array( 
    			"name" => "General Theme Options",
    			"type" => "page_heading"
    		),
        array(
         "name" => "Home Page",
         "type" => "start_section",
         "show_save" => FALSE,
         "desc"  => "home page desc"
        ),
        array( 
  			"name" => "Top Banner Text",
  				"desc" => "This text is displayed above the menu on each page, at top right.",
  				"id" => THEME_NICE_NAME . "_top_banner_text",
  				"type" => "text",
  				"style"	=> "",
  				"std" => "" // Building Vibrant Communities
  			),
  			array( 
  			"name" => "Home Sponsor Text",
  				"desc" => "This text is displayed above the sponsor logos on the home page.",
  				"id" => THEME_NICE_NAME . "_home_logo_text",
  				"type" => "text",
  				"style"	=> "",
  				"std" => "Our Sponsors" // Building Vibrant Communities
  			),
  			array( 
  			"name" => "Home Page Copy",
  				"desc" => "Home Page Copy Desc.",
  				"id" => THEME_NICE_NAME . "_home_page_copy",
  				"type" => "textarea",
  				"style"	=> "height:100px!important",
  				"std" => ""
  			),
        array( 
         "type" => "end_section",
         "show_save" => TRUE
        ),
        array(
         "name" => "Home Page Partner Logos",
         "type" => "start_section",
         "show_save" => FALSE,
         "desc"  => "home page desc"
        ),
  			array( 
  			"name" => "Upload Logos",
  				"desc" => "Upload logos for display on the Home page and partner logo page. <strong>It is important to size your images at 135w X 60h or some multiple of that aspect ratio. Partner logos on the bottom of the home page are strictly cropped.",
  				"id" => THEME_NICE_NAME . "_home_page_logo",
  				"type" => "file_upload",
  				"style"	=> "height:100px!important",
  				"std" => ""
  			),
        array( 
         "type" => "end_section",
         "show_save" => FALSE
        ),
        array( 
  				"name" => "Volunteer Signup Form Messages",
  				"type" => "start_section",
  				"show_save" => FALSE
  			),
  			array( 
  			"name" => "RSVP Signup Success",
  				"desc" => "Enter a message to display when event RSVP signup is successful.",
  				"id" => THEME_NICE_NAME . "_rsvp_ok",
  				"type" => "text",
  				"std" => "Thank you for your RSVP."
  			),
  			array( 
  			"name" => "RSVP Signup Failure",
  				"desc" => "Enter a message to display when event RSVP signup is NOT successful.",
  				"id" => THEME_NICE_NAME . "_rsvp_not_ok",
  				"type" => "text",
  				"std" => "Your RSVP was not successful. Please contact us to RSVP for this event."
  			),
  			array( 
         "type" => "end_section",
         "show_save" => FALSE
        ),
    		array( 
  				"name" => "Search Optimization",
  				"type" => "start_section",
  				"show_save" => FALSE
  			),
  			array( 
  			"name" => "META Page Description",
  				"desc" => "Enter META description text for your site.",
  				"id" => THEME_NICE_NAME . "_seo_desc_text",
  				"type" => "textarea",
  				"style"	=> "height:50px!important",
  				"std" => ""
  			),
  			array( 
  			"name" => "META Keywords",
  				"desc" => "Enter META keywords for your site.",
  				"id" => THEME_NICE_NAME . "_seo_desc_keywords",
  				"type" => "textarea",
  				"style" => "height:50px!important",
  				"std" => ""
  			),
  			array( 
  				"type" => "end_section",
  				"show_save"	=> FALSE
  			),
  			array(
  				"name" => "Google Analytics (UA-4435929-8)",
  				"type" => "start_section",
  				"show_save" => FALSE
  			),
  			array( 
  			"name" => "Google Analytics ID#",
  				"desc" => "Typically looks like UA-XXXXXXX-X.",
  				"id" => THEME_NICE_NAME . "_google_analytics_key",
  				"type" => "text",
  				"std" => ""
  			),
  			array( 
  				"type" => "end_section",
  				"show_save"	=> FALSE
  			),
    		array( 
    			"type" => "page_footing",
    			"show_save"	=> FALSE
    		)
  		);
  		return $the_options;
  	}


/* ****************************** */

  	public static function show_option_form( $args ){
  		extract( $args );
  		if( method_exists( 'ThemeOptions', $method ) ){ 
  		  $nonce = wp_create_nonce( NONCE_STRING );
        
        // fetches out the option data for form generation
  			$the_options = call_user_func( array( 'ThemeOptions', $method ) );
  			?>
  			<form action="" method="post">
  			  <input type="hidden" name="wp_meta_box_nonce" value="<?php echo $nonce; ?>" />
  				<input type="hidden" name="theme_save_action" value="<?php echo $action; ?>" />
  			<?php
  				// TO DO, check nonce
  				if( is_array( $the_options ) ){
    				foreach ( $the_options as $option ) {
    				  // determine the render function for the admin form element
    					$f = 'admin_' . $option['type'] . '_render';
    					// call the form render function based on the option type, if the function exists
    					if( method_exists( 'ThemeAdminForm', $f ) ){ 
    						call_user_func( array( 'ThemeAdminForm', $f ), $option );
    					}
    				} 
    			}
    			else{
    			  $option['error_msg'] ='Error generating option form.';
    			  call_user_func( array( 'ThemeAdminForm', 'admin_render_err' ), $option );
    			}
  			?>
  			</form>
  		<?php
  		}
  	}

  	public static function save_options( $args ){
  		extract( $args );

  		if( method_exists( 'ThemeOptions', $method ) ){ 

  			$the_options = call_user_func( array( 'ThemeOptions', $method ) ); 
  			// ThemeUtil::mpuke( $the_options ); return;
  			if( count( $_REQUEST ) > 0 ) {
  				// ThemeUtil::mpuke( $_REQUEST );
  				$saving_roles = FALSE;
  				if ( $_REQUEST['theme_save_action'] == $action ) {
  					$save_roles = array();

  					foreach ( $the_options as $option ) {
  						$posted_val = $_REQUEST[ $option['id'] ];
  						if( !empty( $_REQUEST[ $option['id'] ] ) ) { 
                
                // currently unused, for when role setting is setup/complete in the admin
  						  if( FALSE && $option['is_role'] ){
  						      ThemeUtil::mpuke( $posted_val );
  						      ThemeUtil::mpuke( $option );
                    global $wp_roles;
                    // if( uc_first($option['is_role']) == $wp_roles->role_names[$option['is_role']] ) continue;
                    $roles = array(); 
                    $default_roles = array(
                     'administrator', 'editor', 'author', 'contributor', 'subscriber'
                    );

                    foreach( $wp_roles->role_names as $role=>$name ){
                      if( !in_array( $role, $default_roles ) )
                        $roles[$role] = $name;
                    }
                   // $saving_roles = TRUE;   

                   // $val = $_REQUEST[ $option['id'] ];
     							//if( is_array( $val ) )
     								//foreach( $val as $k=>$v )
     									//if( !"" == $v )
     							 // echo $option['id'] . " = " . print_r( $val, 1 ) . "<br/>";

     							 // ThemeUtil::mpuke( $option );

     							 // ThemeUtil::mpuke( $_REQUEST[ $option['id'] ] );

  						  }
  						  else {
  						    // update the option name with a new value
    							$val = $_REQUEST[ $option['id'] ];
    							if( is_array( $val ) )
    								foreach( $val as $k=>$v )
    									if( "" == $v )
    										unset($val[$k]);
    							update_option( $option['id'], $val  ); 
  							}
  						} 
  						else{
  						  // clear out/delete and emoty option
  							delete_option( $option['id'] ); 
  						}
  					}

            if( $saving_roles ){
              // ThemeUtil::mpuke( $save_roles );
              //              ThemeUtil::mpuke( $roles );
              //              $uroles = array_intersect_key( $roles, $save_roles );
              //              ThemeUtil::mpuke( $uroles );
            }
  				}
  			}

  		}
  	}

  } // END class 

  // ==============================

  /**
   * ThemeAdminForm
   *
   * @package default
   * @author Michael Reed
   **/
  class ThemeAdminForm{

    public static function admin_render_err( $value ){
      ?>
    		<div class="admin-page-heading">
    			<?php if( !empty($value['name']) ): ?>
    				<h3><?php echo $value['name']; ?></h3>
    			<?php endif; ?>
    		</div>
    		<div class="custom-admin">
    		  <p class="alert"><?php echo $value['error_msg']; ?></p>
      		<div class="admin-page-footing">
      			<div class="clearfix"></div>
      		</div>
      		<div class="clearfix"></div>
    		</div> <!-- /.custom-admin -->
      <?php
    }
    
  	public static function admin_hidden_render( $value ){
  		?>

  		 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" 
  				type="<?php echo $value['type']; ?>" value="<?php echo $value['value']; ?>" />

  		<?php
  	}

  	public static function admin_text_render( $value ){
  		?>
  		<div class="admin-input admin-text">
  			<?php if( !empty( $value['name'] ) ): ?>
  			<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
  			<?php endif; ?>
  		 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
  				<?php 
    				if( !empty( $value['style'] ) ):
    					echo ' style="' . $value['style'] . '" ';
    				else:
    			?>
  				style="max-width:90%"
  				<?php endif; ?>
  				type="<?php echo $value['type']; ?>" value="<?php
  				 	if( !empty( $value['value'] ) ){
  						echo $value['value']; 
  					}
  					else{
  						if ( get_option( $value['id'] ) != "") { 
  								echo @stripslashes( get_option( $value['id'] )  ); 
  						} 
  						else { 
  							echo $value['std']; 
  						} 
  					}
  				?>" class="parent_<?php echo $value['parent_id']; ?>"
  			/>
  		 	<div class="admin-desc">
  				<?php echo $value['desc']; ?>
  			</div>
  			<div class="clearfix"></div>
  		 </div>
  		<?php
  	}

  	public static function admin_text_addmore_render( $value ){
    	$fields = get_option( $value['id'] );
    	?>
    	<div class="admin-input admin-text">
    	  
    		<?php if( !empty( $value['name'] ) ): ?>
    		<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
    		<?php endif; ?>

    		<?php if( is_array( $fields ) && count( $fields ) ): ?>

    			<?php foreach( $fields as $k=>$v ): ?>
    				<input name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>"
    					style="max-width:90%" class="add-more"
    					type="text" value="<?php echo @stripslashes( $v ); ?>"
    				/>
    			<?php endforeach; ?>

    		<?php else: ?>

    			<input name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>"
    				style="max-width:90%" class="add-more"
    				type="text" value="<?php
    					if ( get_option( $value['id'] ) != "") { 
    						echo @stripslashes( get_option( $value['id'] )  ); 
    					} 
    					else { 
    						echo $value['std']; 
    					}
    					// window.location.hash = ui.tab.hash;
    				?>"
    			/>
    		<?php endif; ?>
    		<a href="#" class="remove-one-button ui-state-default ui-corner-all">-</a>
    		<a href="#" class="add-more-button ui-state-default ui-corner-all">+</a>
    	 	<div class="admin-desc">
    			<?php echo $value['desc']; ?>
    		</div>
    		<div class="clearfix"></div>
    	</div>
    	<?php
    }

  	public static function admin_textarea_render( $value ){
  		?>
  		<div class="admin-input admin-textarea">
  			<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
  		 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>"
  			<?php 
  				if( !empty( $value['style'] ) )
  					echo ' style="' . $value['style'] . '" ';
  			?>
  			><?php 
  				if ( get_option( $value['id'] ) != "") {
  					echo @stripslashes(get_option( $value['id']) ); 
  				} 
  				else { 
  					echo $value['std']; 
  				} 
  			?></textarea>
  		 	<div class="admin-desc">
  				<?php echo $value['desc']; ?>
  			</div>
  			<div class="clearfix"></div>
  		 </div>
  		<?php
  	}

  	public static function admin_select_render( $value ){
  		?>
  		<div class="admin-input admin-select">
  			<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>

  			<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
  				<?php foreach ($value['options'] as $option) { ?>
  					<option <?php 
  						if (get_option( $value['id'] ) == $option) { 
  							echo 'selected="selected"'; 
  						} ?>
  					><?php echo $option; ?></option>
  				<?php } ?>
  			</select>
  			<div class="admin-desc">
  				<?php echo $value['desc']; ?>
  			</div>
  			<div class="clearfix"></div>
  		</div>
  		<?php
  	}

  	public static function admin_checkbox_render( $value ){
  		?>
  		<div class="admin-input admin-checkbox">
  			<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
  			<div class="admin-inner admin-radio">
  				<?php 
  					foreach( $value['options'] as $val=>$text ){
  						if (get_option( $value['id'] ) == $val) { 
  							$checked = "checked=\"checked\""; 
  						}
  						else {
  							$checked = "";
  						}
  						?>
  							<input type="checkbox" name="<?php echo $value['id']; ?>" 
  								id="<?php echo $value['id']; ?>" value="<?php echo $val; ?>" <?php echo $checked; ?> 
  							/> <?php echo $text; ?><br/>
  						<?php
  					}

  				?>
  			</div>
  			<div class="admin-desc">
  				<?php echo $value['desc']; ?>
  			</div>
  			<div class="clearfix"></div>
  		 </div>
  		<?php
  	}

  	public static function admin_radio_render( $value ){
  		?>

  		<div class="admin-input">
  			<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><br/>
  			<div class="admin-inner admin-radio">
  				<?php 
  					foreach( $value['options'] as $val=>$text ){
  						if (get_option( $value['id'] ) == $val) { 
  							$checked = "checked=\"checked\""; 
  						}
  						else {
  							$checked = "";
  						}
  						?>
  							<input type="radio" name="<?php echo $value['id']; ?>" 
  								id="<?php echo $value['id']; ?>" value="<?php echo $val; ?>" <?php echo $checked; ?> 
  							/> <?php echo $text; ?><br/>
  						<?php
  					}

  				?>
  			</div>
  			<div class="admin-desc">
  				<?php echo $value['desc']; ?>
  			</div>
  			<div class="clearfix"></div>
  			<?php if( !empty( $value['show_hide'] ) ): ?>
  			<script>
  				//(function($) {
  					jQuery("input[name='<?php echo $value['id']; ?>']").click(function(){
  						if( parseInt(jQuery(this).val() ) )
  							jQuery( "#<?php echo $value['show_hide']; ?>" ).fadeIn();
  						else
  							jQuery( "#<?php echo $value['show_hide']; ?>" ).fadeOut();
  					});
  				// })(jQuery);
  			</script>
  			<?php endif; ?>
  		 </div>
  		<?php
  	}

  	
	   public static function admin_file_upload_render( $value ){
	     ?>
       <div class="admin-input">
       
	         <div class="postarea postimages">
         		<h2><?php echo $value['name']; ?>&nbsp;<img style="width:16px;height:16px;display:none;" 
         		  id="img-loading" src="<?php echo THEME_IMG_URL . '/loading.gif'; ?>" /></h2>
         		<div id="file-uploader">       
         		    <noscript>          
         		        <p>Please enable JavaScript to use file uploader.</p>
         		        <!-- or put a simple form for upload here -->
         		    </noscript>         
         			<div class="clearfix"></div>
         		</div>
         		<div class="clearfix"></div>
         		<div id="<?php echo $value['id']; ?>_images">

         			<div class="clearfix"></div>
         		</div>
         	</div>	
         	<div class="admin-desc">
    				<?php echo $value['desc']; ?>
    			</div>
      </div>   	
     	<div class="clearfix"></div>
	     <?php // call_user_func( array( SYS_PREFIX . 'ImageMgr', 'gen_uploader_js' ),  $value ); ?>

	     <?php
	   }
	   

  	public static function admin_page_heading_render( $value ){
  		?>
  		<div class="admin-page-heading">
  			<?php if( !empty($value['name']) ): ?>
  				<h3><?php echo $value['name']; ?></h3>
  			<?php endif; ?>
  		</div>
  		<div class="custom-admin">
  		<?php
  	}

  	public static function admin_page_footing_render( $value ){
  		?>
  			<div class="admin-page-footing">
  				<?php if( empty($value['show_save']) ): ?>
  					<span class="submit">
  						<input name="save" type="submit" value="Save All" class="ui-state-default ui-corner-all" />
  					</span>
  				<?php endif; ?>
  				<div class="clearfix"></div>
  			</div>
  		</div> <!-- /.custom-admin -->
  		<?php
  	}

  	public static function admin_start_section_render( $value ){
  		?>
  		<div class="custom-section">
  			<div class="admin-title">
  				<?php if( !empty($value['name']) ): ?>
  					<strong><?php echo $value['name']; ?></strong>
  				<?php endif; ?>	

  				<?php if( $value['show_save'] ): ?>
  					<span class="submit">
  					<input name="<?php echo $value['id']; ?>_save" id="<?php echo $value['id']; ?>_save" 
  						type="submit" value="Save changes"  class="ui-state-default ui-corner-all" />
  					</span>
  				<?php endif; ?>

  				<div class="clearfix"></div>
  			</div>

  		<?php
  	}

  	public static function admin_start_subsection_render( $value ){
  		?>
  		<div class="admin-subtitle" style="display:<?php echo $value['display']; ?>"
  			id="<?php echo $value['id']; ?>"
  		>
  			<?php if( !empty($value['name']) ): ?>
  				<strong><?php echo $value['name']; ?></strong>
  			<?php endif; ?>	
  			<div class="clearfix"></div>
  		<?php
  	}

  	public static function admin_end_subsection_render( $value ){
  		?>
  		</div>
  		<?php
  	}

  	public static function admin_start_subnote_render( $value ){
  		?>
  		<!-- <div class="custom-section"> -->
  			<div class="admin-subnote">
  				<?php if( !empty($value['desc']) ): ?>
  					<div class="admin-desc">
  						<?php echo $value['desc']; ?>
  					</div>
  					<div class="clearfix"></div>
  				<?php endif; ?>	
  				<div class="clearfix"></div>
  			</div>

  		<?php
  	}

  	public static function admin_end_section_render( $value ){
  		?>
  			<div class="custom-section-end">
  				<?php if( $value['show_save'] ): ?>
  					<span class="submit">
  					<input name="<?php echo $value['id']; ?>_save" id="<?php echo $value['id']; ?>_save" 
  						type="submit" value="Save changes" class="ui-state-default ui-corner-all" />
  					</span>
  				<?php endif; ?>
  				<div class="clearfix"></div>
  			</div><!-- /.custom-section-end -->
  		</div><!--  custom-section -->
  		<?php
  	}

  } // END class