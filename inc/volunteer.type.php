<?php

/*
  
title:
desc:

type: text?

location:
start date: (datepicker)
start time: (timepicker)

end date: (datepicker)
end time: (timepicker)

repeat: daily, weekly, monthly
*/

class CHP_Volunteer{

  public static function volunteer_signup(){
    
    check_ajax_referer( NONCE_STRING, '_wpnonce' );
    
    // ThemeUtil::log_something( print_r( $_REQUEST, 1 ) );
    
    
    $response = array(
      'result' => 0,
      // '_request' => $_REQUEST
    );
   
   $data = $_REQUEST['data'];
   
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
    
   // $data = $_REQUEST['data'];
    if( !empty( $data ) ){
      
      $email = $data['email'];
      $name = $data['vol_name'];
      
      $post = array();
      $post['post_type']    = 'volunteer';
      $post['post_content'] = '';
      $post['post_date'] = date('Y-m-d H:i:s');
      $post['post_author']  = 1;
      $post['post_status']  = 'publish';
      $post['post_title']   = 'Volunteer: ' ;

      // $response['_post'] = $post;
      
      
      if( $post_id = wp_insert_post ( $post ) ){
        update_post_meta( $post_id, 'email', $email );
        update_post_meta( $post_id, 'name', $name );
        
        // foreach($data as $k=>$d){
        //   if(preg_match()){
        //     
        //   }
        // }
        
        $response['result'] = 1;
        $response['message'] = "success";
      }
      else{
        $response['message'] = "fail";
      }  
      
      $response['result'] = 1;
    }
    
    die( json_encode( $response ) );
  }
  
  public static function register_type(){

  	register_post_type( 
  		'volunteer', 
  		array(
  			'labels' 				=> array(
  				'name' 					=> _x( 'View Volunteers', 'post type general name' ),
  				'singular_name' 		=> _x( 'Volunteer', 'post type singular name' ),
  				'add_new' 				=> _x( 'New Volunteer', 'Volunteer' ),
  				'add_new_item' 			=> __( 'Add New ' . 'Volunteer' ),
  				'edit_item' 			=> __( 'Edit ' . 'Volunteer' ),
  				'new_item' 				=> __( 'New ' . 'Volunteer' ),
  				'view_item' 			=> __( 'View ' . 'Volunteer' ),
  				'search_items' 			=> __( 'Search ' . 'Volunteers' ),
  				'not_found' 			=>  __( 'Volunteer' . ' not found'),
  				'not_found_in_trash' 	=> __( 'Volunteer' . ' not found in Trash'),
  				'parent_item_colon' 	=> ''
  			),
  			'public' 				=> true,
  			'publicly_queryable' 	=> true,
  			'show_ui' 				=> true,
  			'query_var' 			=> true,
  			'rewrite' => array( 
  				'slug' => '/volunteers',
  				'with_front' => false  
  			),
  			'capability_type' 		=> 'post',
  			'hierarchical' 			=> false,
  			'menu_position' 		=> -40,
  			'supports' 				=> array( 'title', 'editor' ),
  		)
  	);

  	flush_rewrite_rules( false );

  }

  public static function _define_type_ui(){
    
    if(isset($_GET['post'])){
      $post = get_post($_GET['post']);
      $post_type = $post->post_type;
    }
    else{
      $post_type = $_GET['post_type'];
    }

	  if( $post_type!="volunteer") return; 
    
  	add_meta_box( 
  		'volunteer-info', 
  		'Volunteer Information', 
  		array( 'CHP_Volunteer', '_ui_form' ), 
  		'volunteer', 
  		'normal', 
  		'high'
  	);

  }

  public static function _ui_form( $post=false ){

  	if( !$post )
  		global $post;

	  if( !$post )
	    $post = get_post( $_REQUEST['post'] );

  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return false;

  	// move request vars and nonce to included file
    // ThemeUtil::mpuke($type_ui_file);
  	$type_ui_file = dirname(__FILE__) . '/volunteer.form.php';
  	require $type_ui_file;

  }

  function remove_all_media_buttons()
  {
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );

    if($post->post_type!="volunteer") return;

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
      
  	if( "volunteer"!=$post->post_type ) return $title;

  	return 'Volunteer name';
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
  	
  	if( 'volunteer' != $post->post_type ) return $post_id;
    
  	if ( !wp_verify_nonce($_POST['wp_meta_box_nonce'], NONCE_STRING ) ) return $post_id;

  	// can the user do this? if not, bail...
  	if ( !current_user_can( 'edit_posts', $post_id ) ) return $post_id;

  	if( count( $_REQUEST ) > 0 ){
      $slots_days_inner = $slot_days = get_option('volunteer_slot_days', false);
      $slot_blocks = get_option('volunteer_slot_blocks', false);
      $interests = get_option('volunteer_interests', false);
      
      $vol_avail = array();
      foreach( $slot_blocks as $slot_block ): 
        $slot_block_safe = preg_replace("/\W|\s/", "_", trim(strtolower($slot_block)));
        $vol_avail[$slot_block_safe] = $_REQUEST[$slot_block_safe];
      endforeach;

      update_post_meta( $post_id, 'volunteer_avail', $vol_avail );
      update_post_meta( $post_id, 'volunteer_interests', $_REQUEST['volunteer_interests'] );

      update_post_meta( $post_id, 'phone', $_REQUEST['phone'] );
      update_post_meta( $post_id, 'email', $_REQUEST['email'] );
      update_post_meta( $post_id, 'name',  $_REQUEST['vol_name'] );
      
     }
      
  }
  
  public static function sc_volunteer_form($attr) {
    
    ob_start();
  	extract(shortcode_atts(array(

  	), $attr));
  	
  	if(count($_POST)): ?>
  	  <div id="vol-reposnse">
  	    <?php
  	      // TO DO: check nonce
      	  $email = $_REQUEST['email'];
          $first_name = $_REQUEST['first_name'];
          $last_name = $_REQUEST['last_name'];

          $post = array();
          $post['post_type']    = 'volunteer';
          $post['post_content'] = '';
          $post['post_date'] = date('Y-m-d H:i:s');
          $post['post_author']  = 1;
          $post['post_status']  = 'publish';
          $post['post_title']   = $first_name . " " . $last_name . ' <' . $email . '>';

          if( $post_id = wp_insert_post ( $post ) ):
      	    // ThemeUtil::mpuke($_POST); 
            $slots_days_inner = $slot_days = get_option('volunteer_slot_days', false);
            $slot_blocks = get_option('volunteer_slot_blocks', false);
            $interests = get_option('volunteer_interests', false);

            $vol_avail = array();
            foreach( $slot_blocks as $slot_block ): 
              $slot_block_safe = preg_replace("/\W|\s/", "_", trim(strtolower($slot_block)));
              $vol_avail[$slot_block_safe] = $_REQUEST[$slot_block_safe];
            endforeach;

            update_post_meta( $post_id, 'volunteer_avail', $vol_avail );
            update_post_meta( $post_id, 'volunteer_interests', $_REQUEST['volunteer_interests'] );

            update_post_meta( $post_id, 'phone', $_REQUEST['phone'] );
            update_post_meta( $post_id, 'email', $_REQUEST['email'] );
            update_post_meta( $post_id, 'first_name',  $_REQUEST['first_name'] );
            update_post_meta( $post_id, 'last_name',  $_REQUEST['last_name'] );
            update_post_meta( $post_id, 'other',  $_REQUEST['other'] );
          endif;
  	    ?>
  	    Thank you for your interest in volunteering.
  	  </div>
  	<?php else: ?>
      <div class="custom-form">
        <form id="pub-volunteer-form" method="POST">
          <?php 
            $front_end = true;
            require 'volunteer.form.php'; 
          ?>
          <div class="form-submit">
            <input type="button" id="vol-form-submit" value="Volunteer"/>
          </div>
        </form>
      <!-- </div> -->
    <?php endif; 
    
    $form = ob_get_contents();
  	ob_end_clean();
  	return $form;
  	
  }

} // end class
   
/* actions */
add_action( 'init', 				      array( 'CHP_Volunteer', 'register_type' ) ); 
add_action( 'add_meta_boxes', 		array( 'CHP_Volunteer', '_define_type_ui' ) );
add_filter( 'enter_title_here',   array( 'CHP_Volunteer', 'change_title_text' ) );	
add_action( 'save_post', 			    array( 'CHP_Volunteer', 'on_post_save' ) );
add_action( 'add_meta_boxes',     array( 'CHP_Volunteer', 'remove_all_media_buttons') );

add_action( 'wp_ajax_volunteer_signup', 		            array( 'CHP_Volunteer', 'volunteer_signup' ) );
add_action( 'wp_ajax_nopriv_volunteer_signup', 		      array( 'CHP_Volunteer', 'volunteer_signup' ) );    
    
add_shortcode( 'volunteer_form', 	array( 'CHP_Volunteer', 'sc_volunteer_form' ) );