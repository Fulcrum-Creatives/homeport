<?php

add_filter( 'show_admin_bar', '__return_false' );

class ThemeAdmin{
  
  
  public static function post_imgs_callback_by_meta($numberposts=-1, $_request=false){
    
    if(!$_request)
  	  check_ajax_referer( NONCE_STRING, 'wp_meta_box_nonce' );
  	
  	
  	if( $numberposts==-1){
  		$orderby = 'menu_order ID'; // menu_order
  		$order = 'ASC';
  	}
  	else{
  		$orderby = 'ID';
  		$order = 'DESC'; // DESC
  	}

  	$imgs = array();
  	$response = array(
  		'result'	=> 0
  	);

    $data = $_request ? $_request : $_REQUEST['data'];
    
  	if( $data ){
  		
  		$post_id 				= $data['post_id'];
  		$size 					= $data['size'];
  		$metakey        = $data['metakey'];

  		$response['result']		= 1;
  		$response['for_post']	= $post_id;
      
      $post_thumbnail_id = get_post_thumbnail_id( $post_id );
      $response['post_thumbnail_id']			= $post_thumbnail_id;
      
  		$attach_args = array(
  			'post_parent' 		=> $post_id, 
  			'post_status' 		=> 'inherit', 
  			'post_type' 		=> 'attachment', 
  			'post_mime_type' 	=> 'image', 
  			'order' 			=> $order, 
  			'orderby' 			=> $orderby,
  			'numberposts'		=> $numberposts,
  			// 'exclude'     => $post_thumbnail_id
  		);


  	  $attachments =& get_children( $attach_args );

  		if ( empty($attachments) ){
  			$response['result']			= 0;
  			$response['message']		= 'No attachments';
  			$response['attach_args']	= $attach_args;
  		}
  		else {
  			foreach ( $attachments as $att_id => $attachment ) {
  			  if( $post_thumbnail_id== $att_id) continue; // skip featured/thumbnail added images
          $attachment_metadata = wp_get_attachment_metadata( $att_id, true );
            // if( !$attachment_metadata['post_type'] ) continue;
            if( !empty( $metakey ) && $metakey != $attachment_metadata['post_type']) continue;
            
  				$custom = get_post_custom( $att_id, 'false' );

  				$src = wp_get_attachment_image_src( $att_id, $size, false );

  				$imgs[] = array(
  				  'featured'  => $custom["featured"][0],
  				  'custom'    => $custom,
  					'id'		    => $att_id,
  					'name'		  => stripslashes($attachment->post_name),
  					'for_post'	=> $post_id,
  					'size'		  => $size,
  					'data'		  => $src, 
  					'caption'	  => stripslashes($attachment->post_title),
  					'post_type' => $attachment_metadata['post_type'],
  					'attachment_metadata'      => $attachment_metadata,
  					'metakey'   => $metakey
  				);
  			}
  		}
  	}
  	$response['imgs'] = $imgs;
  	if( !$_request )
  	  die( json_encode( $response ) );
  	else
  	  return $response;
  }
  
  public static function post_imgs_callback($numberposts=-1){
  	check_ajax_referer( NONCE_STRING, 'wp_meta_box_nonce' );
  	if( $numberposts==-1){
  		$orderby = 'menu_order ID'; // menu_order
  		$order = 'ASC';
  	}
  	else{
  		$orderby = 'ID';
  		$order = 'DESC'; // DESC
  	}

  	$imgs = array();
  	$response = array(
  		'result'	=> 0
  	);
    
    
    
  	if( $_POST['data'] ){
  		$data 					= $_POST['data'];
  		$post_id 				= $data['post_id'];
  		$size 					= $data['size'];
      
      $post_thumbnail_id = get_post_thumbnail_id( $post_id );
      $response['post_thumbnail_id']			= $post_thumbnail_id;
      
  		$response['result']		= 1;
  		$response['for_post']	= $post_id;

  		$attach_args = array(
  			'post_parent' 		=> $post_id, 
  			'post_status' 		=> 'inherit', 
  			'post_type' 		=> 'attachment', 
  			'post_mime_type' 	=> 'image', 
  			'order' 			=> $order, 
  			'orderby' 			=> $orderby,
  			'numberposts'		=> $numberposts
  		);


  	  $attachments =& get_children( $attach_args );

  		if ( empty($attachments) ){
  			$response['result']			= 0;
  			$response['message']		= 'No attachments';
  			$response['attach_args']	= $attach_args;
  		}
  		else {
  			foreach ( $attachments as $att_id => $attachment ) {
          if( $post_thumbnail_id== $att_id) continue; // skip featured/thumbnail added images
          
  				$custom = get_post_custom( $att_id, 'false' );

  				$src = wp_get_attachment_image_src( $att_id, $size, false );

  				$imgs[] = array(
  				  'featured'  => $custom["featured"][0],
  				  'custom'    => $custom,
  					'id'		=> $att_id,
  					'name'		=> stripslashes($attachment->post_name),
  					'for_post'	=> $post_id,
  					'size'		=> $size,
  					'data'		=> $src, 
  					'caption'	=> stripslashes($attachment->post_title)
  				);
  			}
  		}
  	}
  	$response['imgs'] = $imgs;
  	die( json_encode( $response ) );
  }

  public static function delete_img_for_post(){
  	check_ajax_referer( NONCE_STRING, 'wp_meta_box_nonce' );
  	// global $wpdb;
  	$img = array();
  	$response = array(
  		'result'	=> 0
  	);

  	if( $_POST['data'] ){
  		$data 					= $_POST['data'];
  		$post_n_att_id 			= $data['delete_id'];
  		list($discard, $post_id, $att_id) = preg_split("/\-/", $post_n_att_id);

  		// $post = get_post( $att_id );
  		// wp_delete_post( $post->ID );
  		wp_delete_attachment( $att_id );

  		$response['result']		= 1;
  		$response['for_post']	= $post_id;
  		$response['for_att']	= $att_id;


  	}
  	die( json_encode( $response ) );
  }

  public static function unfeature_post_by_id(){
    $response = array(
  		'result'	=> 0
  	);
    $data = $_REQUEST['data'];
    if( !empty( $data ) && !empty( $data['post_id'] ) ){
      delete_post_meta( $data['post_id'], 'featured_post' );
      $response['result'] = 1;
    }
    die( json_encode( $response ) );
  }
  
  public static function update_img_caption() {
  	global $wpdb;
  	$response = array(
  		'result'	=> 0
  	);

  	if( $_POST['data'] ){
  		$data 					= $_POST['data'];
  		$post_n_att_id 			= $data['updatecap_id'];
  		list($discard, $post_id, $att_id) = preg_split("/\-/", $post_n_att_id);
  		$caption = $_POST['data']['caption'];

  		$response['result']		= 1;
  		$response['for_post']	= $post_id;
  		$response['for_att']	= $att_id;

  		$wpdb->update( 
  			$wpdb->posts,
  			array( 'post_title' => $caption ),
  			array( 'ID' => $att_id ),
  			array( '%s' ),
  			array( '%d' )
  		);

  	}


  	die( json_encode( $response ) );
  }

  // to do, check nonce
  public static function post_img_by_id(){
  	check_ajax_referer( NONCE_STRING, 'wp_meta_box_nonce' );
  	$img = array();
  	$response = array(
  		'result'	=> 0,
  		'img'		=> array()
  	);

  	if( $_POST['data'] ){
  		$data 					= $_POST['data'];
  		$att_id 				= $data['att_id'];
  		$size 					= $data['size'];

  		$post = get_post( $att_id );

  		$response['result']		= 1;
  		$response['for_post']	= $att_id;

  		$src = wp_get_attachment_image_src( $att_id, $size, false );

  		if( !empty( $src ) ){
  			$response['img'][] = array(
  				'id'		=> $att_id,
  				'for_post'	=> $post->post_parent,
  				'size'	=> $size,
  				'data'	=> $src
  			);
  		}
  	}
  	die( json_encode( $response ) );
  }

  public static function last_post_img_callback(){
  	self::post_imgs_callback(1);
  }

  public static function img_meta_callback() {
  	$response = array(
  		'result'	=> 0,

  	);

  	if( $_POST['data'] ){
  		$response['result']		= 1;
  		$data = $_POST['data'];
  		$att_id = $data['att_id'];

  		$post = get_post( $att_id );

  		$meta = wp_get_attachment_metadata( $att_id, true );

  		$response['for_post'] 	= $post->post_parent;
  		$response['data'] 		= $meta;
  	}

  	die( json_encode( $response ) );
  }

  public static function current_post_has_images(){
    global $post;
    if(!$post->ID) return false;

    $attachments = get_children( 
      array( 
        'post_parent' => $post->ID, 
        'post_type' 		  => 'attachment', 
  			'post_mime_type'	=> 'image'
      ) 
    );

    return count($attachments);
  }

  public static function create_admin_menus(){
  	if( current_user_can('add_users') )
  	  $the_cap = 'administrator';
  	else
  	  $the_cap = 'editor';

  	add_menu_page( 
  		ADMIN_PAGE_TITLE, 
  		ADMIN_MENU_TITLE, 
  		$the_cap, 
  		ADMIN_SLUG,
  		array( __CLASS__, 'theme_admin_page' ), 
  		ADMIN_ICON_URL, 
  		ADMIN_MENU_POSITION 
  	);
  
    return; 
    
  	$subpages = array(
  		array(
  			'parent_page'	=> ADMIN_SLUG,
  			'page_title'	=> 'Theme Settings',
  			'menu_title'	=> 'Theme Settings',
  			'capability'	=> $the_cap,
  			'menu_slug'		=> ADMIN_SLUG . '-settings',
  			'callback'		=> array( __CLASS__, 'theme_admin_subpage' )
  		)
  	);

  	$args = array(
  		'menu_slug' 	=> ADMIN_SLUG,
  		'capability'	=> $the_cap
  	);

    // foreach( GenericType::$loaded_types as $type ){
    //  if( $type::$uses_custom_menu )
    //    array_splice( $subpages, count( $subpages ), 0, $type::get_menu_items( $args ) );
    // }

  	// Roy_Util::mpuke( $subpages );
  	foreach( $subpages as $subpage ){
  		add_submenu_page( 
  			$subpage['parent_page'],
  			$subpage['page_title'], 
  			$subpage['menu_title'], 
  			$subpage['capability'], 
  			$subpage['menu_slug'], 
  			$subpage['callback']
  		);
  	}
  }
  
  public static function theme_admin_page(){
    if( isset( $_REQUEST['page'] ) ){

  		$ui_page = "" . preg_replace( "/\-/", "_", $_REQUEST['page'] ) . ".php";
  		// ThemeUtil::mpuke( dirname(__FILE__) . '/' . $ui_page );
  		require dirname(__FILE__) . '/' . $ui_page;

  	}
  }
  
  public static function theme_admin_subpage(){
    if( isset( $_REQUEST['page'] ) ){

  		$ui_page = "" . preg_replace( "/\-/", "_", $_REQUEST['page'] ) . ".php";
  		require $ui_page;

  	}
  }
  
  public static function _post_ui_form( $post=false )
  {
    if( !$post ) global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );
      
    if( $post->post_type!="post" ) return false;

  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return false;

  	$post_ui_file = dirname(__FILE__) . '/post.form.php';
  	require $post_ui_file;
  } // END function
  
  public static function _post_define_type_ui()
  {
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );
      
	  if( $post->post_type!="post") return;
	  
  	add_meta_box( 
  		'post-addons', 
  		'Feature Post', 
  		array( 'ThemeAdmin', '_post_ui_form' ),
  		'post', 
  		'normal', 
  		'default'
  	);
  } // END function
  
  public static function remove_all_media_buttons()
  {
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );
      
    if($post->post_type!="post") return;
    
    remove_all_actions('media_buttons');
  }
  
  public static function post_is_event_by_id( $post_id=false ){
    
  }
  
  public static function post_is_event( $post=false ){
    
  }
  
  public static function debug_dump(){ 

  }
  
  public static function rental_manager_ui(){
    
    $args = array(
  		'post_type'=>'property',
  		'meta_key'=>'property_type',
  		'meta_value'=>'Rent',
  		'orderby'=> 'ID',
  		'order'=> 'DESC',
  		'numberposts'=>-1
  	);
  	$rental_props = get_posts($args); 
    $was_updated = false;
    
    if("true" == $_REQUEST['do_update']){
      if ( current_user_can( 'edit_posts' ) ){
        // we are doing an update and the current user has permission to update posts
        
        $new_price = $_REQUEST['price'];
        $props_to_update = $_REQUEST['props_to_update'];
        
        // update all of these to the new price...
        foreach($props_to_update as $post_id){
          // print_r($post_id);
          // $post = get_post($post_id);
          update_post_meta( $post_id, 'price',  $new_price);
        }
        $was_updated = true;
        
      }
    }
  	?>
  	<div class="admin-page-heading">
				<h3>Rental Price Manager</h3>
		</div>
		
		<?php
		if($was_updated): ?>
		  <div class="special-pricing-widget">
		  <div class="ui-widget">
      	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
      		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
      		<strong>Price updated.</strong></p>
      	</div>
      </div>
      </div>
		<?php endif;
		$props_by_development = array();
  	foreach( $rental_props as $rental_prop ):
  	  $rental_prop->custom = get_post_custom( $rental_prop->ID ); 
  	  $development = $rental_prop->custom['development'][0];
  	  if("" != $development){
    	  $development_nice = preg_replace("/\s/", "_", strtolower($development));
    	  // ThemeUtil::mpuke( $development_nice );
  	  
        if(!is_array($props_by_development[$development])){
          $props_by_development[$development] = array();
        }
        $props_by_development[$development][$rental_prop->ID] = $rental_prop;

      }
  	?>
		<?php endforeach; 
		foreach( $props_by_development as $prop_name=>$prop ):
		  $prop_nice_name = preg_replace("/\s/", "_", strtolower($prop_name));
		?>
		  <div class="custom-section">
		    <div class="admin-title">
          <strong><?php echo $prop_name;?></strong>
          <div class="clearfix"></div>
        </div>
        <form name="special_form" method="post">
          <input type="hidden" name="do_update" value="true">
        <div class="admin-input admin-text">
  			  <label for="price" class="special-label">Update <span class="red"><u>ALL</u></span> Rental Prices: </label>
  			  <!--input name="chp_rsvp_ok" id="chp_rsvp_ok" style="max-width:90%" type="text" value="Thank you for your RSVP." class="parent_"-->
          <input type="text" id="price" name="price" class="prompt-text special-pricing" value="" title="Price">
  		 	<div class="admin-desc">
  				Update all pricing data for units in the <span class="red"><?php echo $prop_name;?></span> development in bulk.</div>
  			<div class="clearfix"></div>
  		 </div>
  		 <div class="custom-section-end">
  		      <input name="_show_<?php echo $prop_nice_name; ?>" id="_show_<?php echo $prop_nice_name; ?>" 
  		        type="button" value="Show Locations" class="ui-state-default ui-corner-all" onclick="toggleSpecial('<?php echo $prop_nice_name; ?>');">
 				  <span class="submit">
 					  <input name="_save" id="_save" type="submit" value="Save updates" class="ui-state-default ui-corner-all"
 					    onclick="return confirm('You are about to update the price for EVERY location in the <?php echo $prop_name; ?> development. Are you sure you want to do this?')">
 					</span>
          <div class="clearfix"></div>
 			</div>
 			<div style="display:none" id="special-pricing-<?php echo $prop_nice_name; ?>">
  		   <ul class="special-price-listing">
  		   <?php foreach( $prop as $pid=>$p ): 
  		     
  		   ?>
  		    <li>
  		      <input type="hidden" name="props_to_update[]" value="<?php echo $pid; ?>">
  		      Current Price: <?php echo $p->custom['price'][0]; ?>, Address: <?php echo $p->post_title; ?>
  		    </li>
  		   <?php endforeach; ?>
  		   </ul>
  		 </div>
 			</form>
        <?php /* ?>
		    <div class="admin-input admin-text">
          <div>
              <h4>The admin section</h4>
              <textarea style="width:100%;margin:10px 0;height:250px"><?php print_r($prop);?></textarea>
          </div>
          <div class="admin-desc">description</div>
      		<div class="clearfix"></div>
      	</div>
      	<?php */ ?>
		  </div>
		<?php endforeach; 
    // ThemeUtil::mpuke( $props_by_development );
		?>
		
		
		<?php
  }
  
  public static function featured_post_ui(){
    
    $args = array(
  		'post_type'=>'post',
  		'meta_key'=>'featured_post',
  		'meta_value'=>'1',
  		'orderby'=> 'ID',
  		'order'=> 'DESC',
  		'numberposts'=>-1
  	);
  	
  	$featured_posts = get_posts($args); ?>
  	<div class="admin-page-heading">
				<h3>Featured Posts</h3>
		</div>
  	<ul id="admin-featured-posts" class="icons">
  	<?php
  	foreach( $featured_posts as $featured_post ):
  	  $featured_post->custom = get_post_custom( $featured_post->ID ); 
  	  // ThemeUtil::mpuke( $featured_post ); 
  	  $is_event = $featured_post->custom['is_event'][0]==1;
  	  
  	  if( TRUE || $is_event ):
  	  ?>
  	    <li>
  	      <h3 class="ui-widget-content .ui-state-active ui-corner-all">
  	        <a style="float:left!important" target="_blank" href="<?php echo get_permalink($featured_post->ID); ?>"><?php 
  	          echo $featured_post->post_title; 
  	        ?></a>
  	        <a class="ui-state-default ui-corner-all remove-featured" href="#"
  	          rel="<?php echo $featured_post->ID ; ?>">
  	          <span class="ui-icon ui-icon-circle-close"></span>
  	        </a>
  	      </h3>
  	    </li>
  	  <?php else : ?>
  	    
  	  <?php endif; ?>
  	  
  	  <?php // ehco $featured_post->post_title; ?>
  	  
    <?php endforeach; ?>
    </ul>
    <?php
  }
  
  public static function fetch_community_programs(){
    global $wpdb;
    
    $list = array();
    
    $post_sql = "
      SELECT ID FROM $wpdb->posts p
      WHERE p.post_type='program' 
      AND p.post_status='publish'
  		ORDER BY `menu_order` ASC, `ID` ASC
    ";
    $post_ids = $wpdb->get_results( $post_sql );
    // ThemeUtil::mpuke_ta( $post_ids );
    
    foreach( $post_ids as $pid ){
      $custom = get_post_custom( $pid->ID );
      $n = get_post( $pid->ID );
      $n->custom = $custom;
      $list[] = $n;
    }
    
    return ($list);
  }
  
  public static function fetch_nonevent_posts( $limit=2 ){
    global $wpdb;
    
    $news_list = array();
    // $news_cnt = 0;
	
	$tp = $wpdb->prefix;  //-- the table prefix "wp_" etc.

	$cat = 1;
    
    $post_sql = "
      SELECT ID FROM $wpdb->posts p
      WHERE p.post_type='post' 
      AND p.post_status='publish'
	  AND ID IN (Select object_id FROM {$tp}term_relationships, {$tp}terms WHERE {$tp}term_relationships.term_taxonomy_id =$cat)
  		ORDER BY p.post_date DESC
  		LIMIT 20
    ";
    $post_ids = $wpdb->get_results( $post_sql );
    // ThemeUtil::mpuke_ta( $post_ids );
    
    foreach( $post_ids as $pid ){
      $custom = get_post_custom( $pid->ID );
      // ThemeUtil::mpuke_ta( $custom );
      // skip event posts
      if( 1==$custom['is_event'][0] ) continue;
      $n = get_post( $pid->ID );
      $n->custom = $custom;
      $news_list[] = $n;
      
      // $news_cnt++;
      if( count($news_list) >= $limit ) break;
    }
    
    return ($news_list);
  }
  
  public static function fetch_rentals( $limit=0 ){
  	$args = array(
  		'post_type'=>'property',
  		'meta_key'=>'property_type',
  		'meta_value'=>'rent',
  		'orderby'=> 'post_title',
  		'order'=> 'ASC'
  	);
  	$results = get_posts($args);
  	return $results;
  }
  
  public static function debug($msg, $object=null) {
	if(get_current_user_id() == 2) {
		echo "<div>";
		echo $msg . " <br>";
		if($object !== null) {
			var_dump($object);
		}
		echo "</div>";
	}
  }
  
  public static function fetch_home_event_posts( $limit=2, $days_ahead=null, $days_behind=0, $order='asc'){
    global $wpdb;
    
    $upcoming_events = array();
    $current_year = date("Y");
    $current_month = date("n");
    $current_day = date("j");
    
    $args = array(
     'post_type'=>'post',
	 'post_status'=>'publish',
     'meta_key'=>'is_event',
     'meta_value'=>'1',
     'orderby'=> 'ID',
     'order'=> 'DESC',
	 'numberposts' => 999,
    );
    $events = get_posts($args);
	//ThemeAdmin::debug("events",$events);
	
    $args = array(
     'post_type'=>'post',
	 'post_status'=>'publish',
     'meta_key'=>'event_home_sticky',
     'meta_value'=>'event_home_sticky',
     'orderby'=> 'ID',
     'order'=> 'DESC',
	 'numberposts' => 999,
    );
    $events2 = get_posts($args);
	//ThemeAdmin::debug("events2",$events);
	
	foreach($events2 as $event) {
		$event->custom =  get_post_custom($event->ID);
		
		// if it's more than 1 days behind, skip it
		$month = $event->custom['chp_month_num'][0];
		$day = $event->custom['chp_day_num'][0];
		$year = $event->custom['chp_year'][0];
		$date = date('U',mktime(1,1,1,$month,$day,$year));
		$date_now = time();
		$seconds_diff = (24 * 60 * 60); // 24 hours in seconds
		if( (int)$date < (int)$date_now - $seconds_diff) {
			continue;
		}
		
		$upcoming_events[$event->ID] = $event;
	}
	
    foreach( $events as $event ){
		// if we're at our limit, stop
		if( count($upcoming_events) >= $limit ) break;
	  
		// skip unpublished posts
		if( !"publish"==$event->post_status ) continue; 
		
		// get custom fields
		$event->custom =  get_post_custom($event->ID);

		// if it's more than $days_behind days behind, skip it
		$month = $event->custom['chp_month_num'][0];
		$day = $event->custom['chp_day_num'][0];
		$year = $event->custom['chp_year'][0];
		$date = date('U',mktime(1,1,1,$month,$day,$year));
		$date_now = time();
		$seconds_diff = ($days_behind * 24 * 60 * 60);
		if( (int)$date < (int)$date_now - $seconds_diff) {
			continue;
		}
	   
	  // if it's more than $days_ahead days ahead, skip it
	  if($days_ahead !== null) {
		  $month = $event->custom['chp_month_num'][0];
          $day = $event->custom['chp_day_num'][0];
          $year = $event->custom['chp_year'][0];
		  // if days_ahead == 0, it means we want to skip the current day
			if($days_ahead == 0) {
				$this_month = date('n');
				$this_day = date('j');
				$this_year = date('Y');
				if($month == $this_month && $year == $this_year && $day == $this_day) {
					continue;
				}
			}
		
		  $date = date('U',mktime(1,1,1,$month,$day,$year));
		  $date_now = time();
		  $seconds_ahead = ($days_ahead * 24 * 60 * 60);
		  if( (int)$date > (int)$date_now + $seconds_ahead) {
			continue;
		  }
	   }
	   
      $upcoming_events[$event->ID] = $event;
    }
	
	if(strtolower(trim($order))=='asc') {
		usort( $upcoming_events, "chp_event_sort" );
	}
	else {
		//usort( $upcoming_events, "chp_event_sort_desc" );
		usort( $upcoming_events, "chp_event_sort" );
	}

    return ($upcoming_events);
  }
  
  public static function fetch_event_posts( $limit=2, $days_ahead=null, $days_behind=0, $order='asc'){
    global $wpdb;
    
    $upcoming_events = array();
    $current_year = date("Y");
    $current_month = date("n");
    $current_day = date("j");
    
    $args = array(
     'post_type'=>'post',
	 'post_status'=>'publish',
	 'numberposts' => 999,
     'meta_key'=>'is_event',
     'meta_value'=>'1',
     'orderby'=> 'ID',
     'order'=> 'DESC'
    );
    $events = get_posts($args);
	//ThemeAdmin::debug("events",$events);
    
    foreach( $events as $event ):
      $event->custom = get_post_custom($event->ID);
    endforeach;
	
    // for the last 365 posts that are set as events (assuming there won't be more than
    // 365 posts as events in the future at any given time- that's an event a day for a year)
    foreach( $events as $event ){
      // $custom = get_post_custom( $event->post_id );
      $event_day = $custom['chp_month_num'][0];
      $event_month = $custom['chp_day_num'][0];
      $event_year = $custom['chp_year'][0];
      
	  /*
      // skip if before this year
      if( $event->custom['chp_year'][0] < $current_year ) continue; 
      
      // skip if before this month of the current year
      if( $event->custom['chp_year'][0] == $current_year 
          && $event->custom['chp_month_num'][0] < $current_month 
      ) continue; 
      
      // skip if before this day of this month of the current year
      if( $event->custom['chp_year'][0] == $current_year 
          && $event->custom['chp_month_num'][0] == $current_month 
          && $event->custom['chp_day_num'][0] < $current_day
      ) continue;
      */
	  
		// if it's more than $days_behind days behind, skip it
		$month = $event->custom['chp_month_num'][0];
		$day = $event->custom['chp_day_num'][0];
		$year = $event->custom['chp_year'][0];
		$date = date('U',mktime(1,1,1,$month,$day,$year));
		$date_now = time();
		$seconds_diff = ($days_behind * 24 * 60 * 60);
		if( (int)$date < (int)$date_now - $seconds_diff) {
			continue;
		}
	   
	   
	  // if it's more than $days_ahead days ahead, skip it
	  if($days_ahead !== null) {
		  $month = $event->custom['chp_month_num'][0];
          $day = $event->custom['chp_day_num'][0];
          $year = $event->custom['chp_year'][0];
		  // if days_ahead == 0, it means we want to skip the current day
			if($days_ahead == 0) {
				$this_month = date('n');
				$this_day = date('j');
				$this_year = date('Y');
				if($month == $this_month && $year == $this_year && $day == $this_day) {
					continue;
				}
			}
		
		  $date = date('U',mktime(1,1,1,$month,$day,$year));
		  $date_now = time();
		  $seconds_ahead = ($days_ahead * 24 * 60 * 60);
		  if( (int)$date-(23*60*60) > (int)$date_now + $seconds_ahead) {
			continue;
		  }
	   }
	   
      // ok on date, load the post
      // $event_post = get_post( $event->post_id );
      // $event_post->custom = $custom;
      
      // skip over unpublished post events
      if( !"publish"==$event->post_status ) continue; 
      
      // ThemeUtil::mpuke( $current_month . "/" . $current_month . "/" .$current_year );
      // ThemeUtil::mpuke( ">> " . $event_day . "/" . $event_month . "/" .$event_year );
      
      // ThemeUtil::mpuke( $event_post );
      
      $upcoming_events[] = $event;
      
      // $event_cnt++;
      if( count($upcoming_events) >= $limit ) break;
    }
    
    
	if(strtolower(trim($order))=='asc') {
		usort( $upcoming_events, "chp_event_sort" );
	}
	else {
		//usort( $upcoming_events, "chp_event_sort_desc" );
		usort( $upcoming_events, "chp_event_sort_desc" );
	}

    return ($upcoming_events);
  }
  
  public static function on_post_save( $post_id ){
    
    // don't auto save custom post items
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

  	$post = get_post( $post_id );
  	
  	if( 'post' != $post->post_type ) return $post_id;

  	if ( !wp_verify_nonce($_POST['wp_meta_box_nonce'], NONCE_STRING ) ) return $post_id;

  	// can the user do this? if not, bail...
  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return $post_id;

  	if( count( $_REQUEST ) > 0 ){
  	  // ThemeUtil::log_something(print_r($_REQUEST, 1));

  	  ThemeAdmin::idx_and_save( $post_id, 'featured_post', $_REQUEST['featured_post'] );
		
		$has_rsvp = $_REQUEST['has_rsvp'];
		$event_type = $_REQUEST['event_type'];
		$is_event_multiday = $_REQUEST['is_event_multiday'];
		if($is_event_multiday) {
			$is_event = ($_REQUEST['is_event'] || $is_event_multiday) ? '1' : '0';
			$event_date = $event_date_multiday = $_REQUEST['event_date_multiday'];
			$event_date_to = $_REQUEST['event_date_to'];
			$event_home_sticky = $_REQUEST['event_home_sticky'];
		}
		else {
			$is_event = $_REQUEST['is_event'];
			$event_date = $_REQUEST['event_date'];
			$event_date_multiday = $_REQUEST['event_date_multiday'];
			$event_date_to = $_REQUEST['event_date_to'];
			$event_home_sticky = $_REQUEST['event_home_sticky'];
		}
      update_post_meta( $post_id, 'has_rsvp',  $has_rsvp);
      update_post_meta( $post_id, 'event_type', $event_type );
      update_post_meta( $post_id, 'is_event', $is_event );
      update_post_meta( $post_id, 'is_event_multiday', $is_event_multiday );
      update_post_meta( $post_id, 'event_date', $event_date );
      update_post_meta( $post_id, 'event_date_multiday', $event_date_multiday );
      update_post_meta( $post_id, 'event_date_to', $event_date_to );
      update_post_meta( $post_id, 'event_home_sticky', $event_home_sticky );
	  $date = strtotime($event_date);
		$chp_day_num = date("j",$date);
		$chp_month_name = date("F",$date);
		$chp_month_num = date("n",$date);
		$chp_year = date("Y",$date);
      update_post_meta( $post_id, 'chp_day_num', $chp_day_num );
      update_post_meta( $post_id, 'chp_month_name', $chp_month_name );
      update_post_meta( $post_id, 'chp_month_num', $chp_month_num );
      update_post_meta( $post_id, 'chp_year', $chp_year );
	  
	  
	  
      // if( !is_empty($_REQUEST['event_datet']) )
    //$date_parts = _split_save_datetime( $event_date, $post_id, 'chp' );
    
      // $_REQUEST['is_event']
      
      update_post_meta( $post_id, 'event_hours', $_REQUEST['event_hours'] );
      update_post_meta( $post_id, 'event_from_time', $_REQUEST['event_from_time'] );
      update_post_meta( $post_id, 'event_to_time', $_REQUEST['event_to_time'] );
  	
      // if( !empty($date_parts) ){
      //   ThemeAdmin::idx_and_save( $post_id, 'events_' . $parts['year'] . , $_REQUEST['featured_post'] );
  		  
		  // }
      
  		
      // ThemeAdmin::idx_and_save( $post_id, 'month_num', $date_parts['month_num'] );
      // ThemeAdmin::idx_and_save( $post_id, 'year', $date_parts['year'] );
      

    }
    
    
  }
  
  public static function re_idx(){
    $k = 'featured_post';
    $args = array(
  		'post_type'=>'post',
  		'meta_key'=>$k,
  		'meta_value'=>'1',
  		'orderby'=> 'post_title',
  		'order'=> 'ASC'
  	);
  	$featured_posts = get_posts($args);
  	$v_safe = 1;
    $idx_option = 'all_' . $k . '_' . $v_safe . '_ids';
    
  	foreach( $featured_posts as $featured_post ){
      $this_idx = get_option( $idx_option, array() );
      $this_idx[$featured_post->ID] = 1;
      // update_option( $idx_option, $this_idx );
  	}
  	
  	// ThemeUtil::mpuke_ta( $this_idx );
  	// ThemeUtil::mpuke_ta( $featured_posts );
  }
  
  public static function get_idx($k, $v){
    
    $v_safe = preg_replace("/\s/", "_", trim($v));
    $v_safe = preg_replace("/\-/", "_", $v_safe);
    $idx_option = 'all_' . $k . '_' . $v_safe . '_ids';
    
    // apc_delete($idx_option);
    
    if( !$this_idx = apc_fetch($idx_option) ){
      
      $this_idx = get_option( $idx_option, array() );
      foreach($this_idx as $k=>$idx){
        $idx_post = get_post($k);

        if("publish"!=$idx_post->post_status){
          unset($this_idx[$k]);
        }

      }
      apc_store($idx_option, serialize($this_idx), 600);
    }
    else{
      if( !is_array($this_idx) )
        $this_idx = unserialize($this_idx);
    }
    
    return $this_idx;
  }
  
  public static function idx_and_save( $post_id, $k, $v ){
    $v_safe = preg_replace("/\s/", "_", trim($v));
    $v_safe = preg_replace("/\-/", "_", $v_safe);
    $idx_option = 'all_' . $k . '_' . $v_safe . '_ids';
    update_post_meta( $post_id, $k, $v );
    
    $this_idx = get_option( $idx_option, array() );
    
    if( !empty($v) ){
      $this_idx[$post_id] = 1;
    }
    else{
      unset($this_idx[$post_id]);
    }
    update_option( $idx_option, $this_idx );
    
  }
  
  public static function munge_menu(){
  
   global $menu, $submenu;
   if( !is_array( $menu ) || !is_array( $submenu ) ) return;
   // ThemeUtil::mpuke_ta( $menu );
   // ThemeUtil::mpuke_ta( $submenu );
   // return;
   
   // unset( $submenu[ADMIN_SLUG][0] ); 
  
   $development = false;
   $cpt = false;
   $snapsot = false;
   $wp_touchpro = false;
   $contact = false;
   $site_notes = false;
   
   $extras_menu = array();
   
   foreach( $menu as $index => $item ){
      
     // if( "Developments" == $item[0] ){
     //   $development = $menu[$index];
     //   unset( $menu[$index] );
     //   // $menu = array_splice($menu, 2, 0, $development);
     //   $menu[-60] = $development;
     // }
     
     // if( "Custom Post Types" == $item[0] ){
     //   $cpt = $menu[$index];
     //   if( !current_user_can( 'manage_options' ) ){
     //     unset( $menu[$index] );
     //    }
     //    $extras_menu[] = $cpt;
     // }
     
     
     
     // if( "Snapshot Backup" == $item[0] ){
     //    $snapshot = $menu[$index];
     //    if( !current_user_can( 'manage_options' ) ){
     //      unset( $menu[$index] );
     //    }
     //    $extras_menu[] = $snapshot;
     //  }
      
      
      
      // if( "WPtouch Pro" == $item[0] ){
      //   $wp_touchpro = $menu[$index];
      //   if( !current_user_can( 'manage_options' ) ){
      //     unset( $menu[$index] );
      //   }
      //   $extras_menu[] = $wp_touchpro;
      // }
     
      
      
      // if( "Contact" == $item[0] ){
      //   $contact = $menu[$index];
      //   if( !current_user_can( 'manage_options' ) ){
      //     unset( $menu[$index] );
      //   }
      //   $extras_menu[] = $contact;
      // }
      
      // if( "Site Notes" == $item[0] ){
      //   $site_notes = $menu[$index];
      //   if( !current_user_can( 'manage_options' ) ){
      //     unset( $menu[$index] );
      //   }
      //   // $menu[-15] = $site_notes;
      //   $extras_menu[] = $site_notes; 
      // }
      
   } 
  
   ksort( $menu );
   
   foreach( $submenu as $index => $item ){
      // ThemeUtil::mpuke( $submenu );
      // switch($index){
      //   case "tools.php":
      //     // ThemeUtil::mpuke( $item );
      //     foreach( $item as $key => $menuitem ){
      //       
      //     }
      //   break;
      //   case "plugins.php":
      //     // ThemeUtil::mpuke( $item );
      //     foreach( $item as $key => $menuitem ){
      //       if( "Regen. Thumbnails"== $menuitem[0]){
      //         unset( $submenu[$index][$key] );
      //       }
      //     }
      //   break;
      // }
      
     // if( "plugins.php" == $index ){
     //   // ThemeUtil::mpuke( $item );
     //   foreach( $item as $key => $menuitem ){
     //     
     //   }
     //   
     // }

   }
    
   // ThemeUtil::mpuke_ta( $menu );
    
  }
  
  public static function remunge_menu(){
    global $menu, $submenu; 
    ThemeUtil::mpuke_ta( $menu );
    ThemeUtil::mpuke_ta( $submenu );
  }
}

add_action( 'wp_ajax_update_img_caption',	  array( 'ThemeAdmin', 'update_img_caption') );
add_action( 'wp_ajax_delete_img_for_post',	array( 'ThemeAdmin', 'delete_img_for_post') );
add_action( 'wp_ajax_post_img_by_id',		    array( 'ThemeAdmin', 'post_img_by_id') );
add_action( 'wp_ajax_last_post_img', 		    array( 'ThemeAdmin', 'last_post_img_callback') );
add_action( 'wp_ajax_post_imgs', 			      array( 'ThemeAdmin', 'post_imgs_callback') );
add_action( 'wp_ajax_post_imgs_by_meta', 		array( 'ThemeAdmin', 'post_imgs_callback_by_meta') );
add_action( 'wp_ajax_img_meta', 			      array( 'ThemeAdmin', 'img_meta_callback') );
add_action( 'wp_ajax_unfeature_post_by_id', array( 'ThemeAdmin', 'unfeature_post_by_id') );

add_action( 'admin_menu',                   array( 'ThemeAdmin', 'create_admin_menus') );

add_action( 'add_meta_boxes', 		          array( 'ThemeAdmin', '_post_define_type_ui'), 1 );
//add_action( 'add_meta_boxes',               array( 'ThemeAdmin', 'remove_all_media_buttons') );
add_action( 'save_post', 			              array( 'ThemeAdmin', 'on_post_save' ) );

// add_action( 'admin_init', 		              array( 'ThemeAdmin', 'remunge_menu'), 100 );
// add_action( 'admin_init',                   array( 'ThemeAdmin', 'munge_menu'), 100 );
// add_action( 'admin_menu',                 array( 'ThemeAdmin', 'remunge_menu'), 1000 );