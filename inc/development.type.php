<?php

class Development{
  
  public static function register_type(){
    
    register_post_type('developments', array(	'label' => 'Developments','description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true, 'menu_position' 		=> -60, 'supports' => array('title','editor','excerpt','thumbnail'),'labels' => array (
      'name' => 'Developments',
      'singular_name' => 'Development',
      'menu_name' => 'Developments',
      'add_new' => 'Add Development',
      'add_new_item' => 'Add New Development',
      'edit' => 'Edit',
      'edit_item' => 'Edit Development',
      'new_item' => 'New Development',
      'view' => 'View Development',
      'view_item' => 'View Development',
      'search_items' => 'Search Developments',
      'not_found' => 'No Developments Found',
      'not_found_in_trash' => 'No Developments Found in Trash',
      'parent' => 'Parent Development',
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
    
	  if( $post_type!="developments") return; 
	  
  	add_meta_box( 
  		'development-info', 
  		'Development Media', 
  		array( 'Development', '_ui_form' ), 
  		'developments', 
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
	
  	$type_ui_file = dirname(__FILE__) . '/development.form.php';
  	require $type_ui_file;
	
  }

  function remove_all_media_buttons()
  {
    global $post;
    if(!$post)
      $post = get_post( $_REQUEST['post'] );
      
    if($post->post_type!="developments") return;
    
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
      
  	if( "developments"!=$post->post_type ) return $title;

  	return 'Enter development name';
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
  	if( 'property' != $post->post_type ) return $post_id;

  	if ( !wp_verify_nonce($_POST['wp_meta_box_nonce'], NONCE_STRING ) ) return $post_id;

  	// can the user do this? if not, bail...
  	if ( !current_user_can( 'edit_posts', $post->ID ) ) return $post_id;

  	if( count( $_REQUEST ) > 0 ){
  	  
  	  
	  }
    
  }
  
  // gets $$ range for develpment properties.
  public static function propertyPricesRange( $post_id ){
    
    $development = get_post( $post_id );
    $properties_for = ThemeAdmin::get_idx( 'development', $development->post_title );
    
    $low = 1000000;
    $high = 0;
    
    if( !empty( $properties_for )){
      foreach( $properties_for as $prop_post_id=>$junk){
        $custom = get_post_custom( $prop_post_id ); 
        // ThemeUtil::mpuke( $custom );
        if( $low > 0 && $custom->price[0] < $low ) $low = $custom->price[0];
        if( $high > 0 && $custom->price[0] > $high ) $high = $custom->price[0];
      }
    }
    else{
      $low = 0;
    }

    return array(
      'low'   => $low,
      'high'  => $high
    );
  }
  
  public static function sliderFromPropertyImages( $post_id, $size ){
    
    $development = get_post( $post_id );
    
    $args = array(
  		'post_type'=>'property',
  		'meta_key'=>'development',
  		'meta_value'=>$development->post_title,
  		'orderby'=> 'ID',
  		'order'=> 'DESC',
  		'numberposts' => -1
  	);
  	
  	$properties_for = get_posts($args);
  	
    // $properties_for = ThemeAdmin::get_idx( 'development', $development->post_title );
    // ThemeUtil::mpuke( $properties_for ); return;
    
    if( !empty( $properties_for )): $images = ''; ?>
      
          <?php foreach( $properties_for as $id=>$prop):
            // ThemeUtil::mpuke( $prop );
            
            $prop->custom = get_post_custom( $prop->ID ); 
            
            // Property::singleViewFirstImage( $prop->ID, $size, $development->post_title );
            
            $post_thumbnail_id = get_post_thumbnail_id( $prop->ID );
            
            if( $attachments = get_children( 
        			array(
        				'post_parent' 		=> $prop->ID, 
        				'post_status' 		=> 'inherit', 
        				'post_type' 		  => 'attachment', 
        				'post_mime_type'	=> 'image', 
        				'order' 			    => 'ASC', 
        				'orderby' 			  => 'menu_order ID',
        				'numberposts' 		=> -1
        			) 
        		) ){
        		  
              // echo '<br/><br/><br/>';
              //               ThemeUtil::mpuke( $attachments );
              //               ThemeUtil::mpuke( count($attachments) );
        		  
        		  if( count( $attachments ) > 0 ):
        		  
                // ThemeUtil::mpuke( $attachements );
        		    foreach( $attachments as $id=>$att ):
                  // if($post_thumbnail_id==$att->ID) continue;
                  // ThemeUtil::mpuke( $att );
        		      $src = wp_get_attachment_image_src( $att->ID, $size, false );
          			  $imgsrc = $src[0];
          				$width = $src[1];
          				$height = $src[2];
                  $name = $development->post_title . ": " . stripslashes($attachment->post_name);
                  // $caption = empty(stripslashes($attachment->post_title)) ? $development->post_title : (stripslashes($attachment->post_title) . ": " . $development->post_title);
                  $caption = stripslashes($attachment->post_title);
                  $images .= '<img title="' . $caption . '" class="single-property-view" src="' . $imgsrc . '" ';
                  $images .= 'style="width:' . $width . 'px;height:' . $height . '" border="0" />';
                  /*
                  ?>
            		    <img title="<?php echo $caption; ?>" class="single-property-view" src="<?php echo $imgsrc; ?>" 
            		      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" />
                  <?php
        		      */
        		      break;
        		     endforeach;
        		  endif;
      		  }
            ?>
            
          <?php endforeach; ?>
      
      <?php if(""==$images):?>
        <!--img title="No Image Available" class="single-property-view" 
  		    src="http://placehold.it/614x275&text=No+images+available" 
  	       border="0" /-->
        <!-- <img src="/wp-content/themes/chp/images/no-image.development.gif" /> -->
      <?php else: ?>
        <div id="slider-wrapper">
    	    <div id="slider" class="xnivoSlider">
    	      <?php echo $images; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <!-- <img src="/wp-content/themes/chp/images/no-image.development.gif" /> -->
      <!--img title="No Image Available" class="single-property-view" 
		    src="http://placehold.it/614x275&text=No+images+available" 
	       border="0" /-->
    <?php endif;
  }
  
  public static function developments_page($atts = null, $inner_content = null) {
    ob_start(); 
    
    $development_args = array(
  		'post_type'=>'developments',
  		'orderby'=> 'post_title',
  		'order'=> 'ASC',
  		'numberposts'=>-1
  	);
  	
  	$developments = get_posts( $development_args ); ?>
  	<div id="masonry-container">
  	  <?php 
  	    // ThemeUtil::mpuke( $developments ); 
  	    $cnt=0; 
  	    foreach( $developments as $development ): 
  	      if( "" == $development->post_content ) continue;
          // ThemeUtil::mpuke($development);
    	    ?>
    	    <div class="masonry-box <?php if ($cnt%2==0){ echo " add-rm "; }?>">
    	      <h2 class="avenir">
    	       <?php echo $development->post_title; ?>
    	      </h2>
    	      <div style="clear:both"></div>
            <div>
              <?php
                $attachments = get_children( 
              		array(
              			'post_parent' 		=> $development->ID, 
              			'post_status' 		=> 'inherit', 
              			'post_type' 		  => 'attachment', 
              			'post_mime_type'	=> 'image', 
              			'order' 		  	  => 'ASC', 
              			'orderby' 			  => 'menu_order ID',
              			'numberposts'     => 1
              		) 
              	);

              	$size = 'masonry-size';
            	
              	if( count( $attachments ) ): ?>
            	  
              	  <?php foreach( $attachments as $att ):
            		    $src = wp_get_attachment_image_src( $att->ID );
            		    $img_args['id'] = $att->ID;
            			  $img = wp_get_attachment_image( $att->ID, $size, false, $img_args );
            		    $img_src = wp_get_attachment_image_src( $att->ID, $size ); ?>

            		    <a href="<?php echo get_permalink($development->ID); ?>"><img 
            		      src="<?php echo $img_src[0]; ?>" class="masonry-img" title="<?php echo $att->post_title; ?>"
            		      id="masonry-img-<?php echo $att->ID; ?>" /></a>

            		  <?php endforeach; ?>
          		  
              	<?php endif;
              ?>
              
              <?php
              $excerpt = trim(chp_excerpt( $development->post_content, '<div class="more-right">more</div>', get_permalink( $development->ID ), 20 )); 
    	        if(''!=$excerpt){
    	          echo $excerpt;
    	        }
    	        else{
    	          echo 'No description is available.';
    	        }
              ?>
              
              <div style="clear:both"></div>
            </div>
            <div style="clear:both"></div>
    	    </div>
  	    
    	    <?php 
  	      $cnt++; 
  	    endforeach; ?>

  	</div>
  	<?php
  	$contents = ob_get_contents();
  	ob_end_clean();
    return $contents;
  }
  
  public static function old_developments_page($atts = null, $inner_content = null) {
  	
    ob_start(); 
    
    $development_args = array(
  		'post_type'=>'developments',
  		'orderby'=> 'post_title',
  		'order'=> 'ASC'
  	);
  	$developments = get_posts( $development_args );
    ?>
  	<div class="developments">
  	  <?php foreach( $developments as $development ): ?>
  	    <div class="development">
  	      <?php if(has_post_thumbnail($development->ID)) {
			$image = get_the_post_thumbnail( $development->ID, 'medium');
			echo "<div class='image'>{$image}</div>";
		  } ?><h2><?php echo $development->post_title; ?></h2>
		  <div class="description"><?php echo $development->post_content; ?></div>
		  
		  <div class=''>Properties in <?php echo $development->post_title; ?>:</div>
  	        <ul>
  	      <?php 
            $properties_for = ThemeAdmin::get_idx( 'development', $development->post_title );
            if( !empty( $properties_for )):
              foreach( $properties_for as $post_id=>$junk):
                $prop = get_post( $post_id );
                $prop->custom = get_post_custom( $post_id ); ?>
                <li><?php echo $prop->post_title; ?></li>
              <?php endforeach;
              endif;
    	      ?>
    	        <ul>
    	    </div>
    	  <?php endforeach; ?>
            
  	</div>
  	<?php
  	$contents = ob_get_contents();
  	ob_end_clean();
    return $contents;
  }
}

add_action( 'init', 				              array( 'Development', 'register_type' ) ); 
add_action( 'add_meta_boxes', 		        array( 'Development', '_define_type_ui' ) );
add_filter( 'enter_title_here',           array( 'Development', 'change_title_text' ) );	
add_action( 'save_post', 			            array( 'Development', 'on_post_save' ) );
add_action( 'add_meta_boxes',             array( 'Development', 'remove_all_media_buttons') );
add_shortcode( 'developments',            array( 'Development', 'developments_page' ) );
?>