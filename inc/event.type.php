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

class CHP_Event{

  public static function register_type(){

  	register_post_type( 
  		'event', 
  		array(
  			'labels' 				=> array(
  				'name' 					=> _x( 'View Events', 'post type general name' ),
  				'singular_name' 		=> _x( 'Event', 'post type singular name' ),
  				'add_new' 				=> _x( 'New Event', 'Event' ),
  				'add_new_item' 			=> __( 'Add New ' . 'Event' ),
  				'edit_item' 			=> __( 'Edit ' . 'Event' ),
  				'new_item' 				=> __( 'New ' . 'Event' ),
  				'view_item' 			=> __( 'View ' . 'Event' ),
  				'search_items' 			=> __( 'Search ' . 'Events' ),
  				'not_found' 			=>  __( 'Event' . ' not found'),
  				'not_found_in_trash' 	=> __( 'Event' . ' not found in Trash'),
  				'parent_item_colon' 	=> ''
  			),
  			'public' 				=> true,
  			'publicly_queryable' 	=> true,
  			'show_ui' 				=> true,
  			'query_var' 			=> true,
  			'rewrite' => array( 
  				'slug' => '/events',
  				'with_front' => false  
  			),
  			'capability_type' 		=> 'post',
  			'hierarchical' 			=> false,
  			'menu_position' 		=> -40,
  			'supports' 				=> array( 'title', 'editor', 'author', 'excerpt' ),
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

	  if( $post_type!="event") return; 

  	add_meta_box( 
  		'event-info', 
  		'Event Details', 
  		array( 'CHP_Event', '_ui_form' ), 
  		'event', 
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
  	$type_ui_file = dirname(__FILE__) . '/event.form.php';
  	require $type_ui_file;

  }

  function remove_all_media_buttons()
  {
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );

    if($post->post_type!="event") return;

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
      
  	if( "event"!=$post->post_type ) return $title;

  	return 'Enter event name';
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
  	if( 'Event' != $post->post_type ) return $post_id;

  	if ( !wp_verify_nonce($_POST['wp_meta_box_nonce'], NONCE_STRING ) ) return $post_id;

  	// can the user do this? if not, bail...
  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return $post_id;

  	if( count( $_REQUEST ) > 0 ){

      ThemeAdmin::idx_and_save( $post_id, 'featured_post', $_REQUEST['featured_post'] );

     }
      
  }
  
}
   
/* actions */
add_action( 'init', 				      array( 'CHP_Event', 'register_type' ) ); 
add_action( 'add_meta_boxes', 		array( 'CHP_Event', '_define_type_ui' ) );
add_filter( 'enter_title_here',   array( 'CHP_Event', 'change_title_text' ) );	
add_action( 'save_post', 			    array( 'CHP_Event', 'on_post_save' ) );
add_action( 'add_meta_boxes',     array( 'CHP_Event', 'remove_all_media_buttons') );
    
    