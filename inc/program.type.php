<?php

class Programs{
  
  public static function register_type(){
    
    register_post_type('program', array(	'label' => 'Community Programs','description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => 'live/programs'),'query_var' => true,'supports' => array('title','editor','excerpt','thumbnail'),
    'menu_position' 		=> -50,
    'labels' => array (
      'name' => 'Community Programs',
      'singular_name' => 'Program',
      'menu_name' => 'Community Programs',
      'add_new' => 'Add Program',
      'add_new_item' => 'Add New Program',
      'edit' => 'Edit',
      'edit_item' => 'Edit Program',
      'new_item' => 'New Program',
      'view' => 'View Program',
      'view_item' => 'View Program',
      'search_items' => 'Search Programs',
      'not_found' => 'No Programs Found',
      'not_found_in_trash' => 'No Programs Found in Trash',
      'parent' => 'Parent Program',
    ),) );
 
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
    
	  if( $post_type!="program") return; 
	  
  	add_meta_box( 
  		'program-info', 
  		'Program Media', 
  		array( 'Programs', '_ui_form' ), 
  		'program', 
  		'normal', 
  		'high'
  	);
	
  }

  public static function _ui_form( $post=false ){
    // ThemeUtil::mpuke(__FUNCTION__);
  	if( !$post )
  		global $post;
	
	  if( !$post )
	    $post = get_post( $_REQUEST['post'] );
	    
  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return false;

  	// move request vars and nonce to included file
	
  	$type_ui_file = dirname(__FILE__) . '/program.form.php';
  	require $type_ui_file;
    // ThemeUtil::mpuke($type_ui_file);
  }
  
  function remove_all_media_buttons()
  {
    
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );
    // ThemeUtil::mpuke($post->post_type);
      
    if($post->post_type!="program") return;
    
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
      
  	if( "program"!=$post->post_type ) return $title;

  	return 'Enter development name';
  } // END function
}


add_action( 'init', 				                      array( 'Programs', 'register_type' ) ); 
add_action( 'add_meta_boxes', 		                array( 'Programs', '_define_type_ui' ) );
add_filter( 'enter_title_here',                   array( 'Programs', 'change_title_text' ) );	
// add_action( 'save_post',                           array( 'Programs', 'on_post_save' ) );
add_action( 'add_meta_boxes',                     array( 'Programs', 'remove_all_media_buttons') );

