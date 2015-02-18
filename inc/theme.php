<?php

class ThemeTheme{
  
  public static $categories = array( 'please-select' => 'Please Select' );

  // public static $menus = array(  
  //  'primary' => 'Primary Navigation',  
  //  'secondary' => 'Secondary Navigation' 
  // );
	
	public static function init(){
		
		$ws_categories_helper = get_categories( 'hide_empty=0' );
		foreach ( $ws_categories_helper as $category ) {
		    self::$categories[$category->category_nicename] = $category->cat_ID;
		}
		
		// array_unshift( self::$categories, "Please Select" );
		
	}
  
  public static function register_admin_styles() {
    wp_enqueue_style( 'thickbox' );

  	$styles = array();
    
    $styles[] =	array(
  		'name'			  => 'jquery-ui-css',
  		'src'			    => get_bloginfo( 'stylesheet_directory' ) .'/inc/js/jquery-ui-1.8.15/css/ui-lightness/jquery-ui-1.8.15.custom.css',
  		'deregister'  => FALSE,
  		'deps'			  => FALSE,
  		'version'		  => THEME_VERSION,
  		'media'			  => 'all'
  	);
  	
  	
  	
    $styles[] =	array(
  		'name'			  => 'admin-css',
  		'src'			    => get_bloginfo( 'stylesheet_directory' ) . '/inc/css/admin.css',
  		'deregister'  => FALSE,
  		'deps'			  => FALSE,
  		'version'		  => THEME_VERSION,
  		'media'			  => 'all'
  	);

    self::_register_styles( $styles ); 
  }
  
  public static function register_styles() {
		// echo __FUNCTION__ . "<br/>";
		wp_enqueue_style( 'thickbox' );
		// wp_enqueue_style( 'jquery' );
    
    $styles = array();

  	
    $styles[] =	array(
  		'name'			  => 'jscrollpane',
  		'src'			    => get_bloginfo( 'stylesheet_directory' ) .'/inc/js/jScrollPane/style/jquery.jscrollpane.css',
  		'deregister'  => FALSE,
  		'deps'			  => FALSE,
  		'version'		  => THEME_VERSION,
  		'media'			  => 'all'
  	);
  	
  	$styles[] =	array(
  		'name'			  => 'layout-css',
  		'src'			    => get_bloginfo( 'stylesheet_directory' ) .'/layout.css',
  		'deregister'  => FALSE,
  		'deps'			  => FALSE,
  		'version'		  => THEME_VERSION,
  		'media'			  => 'all'
  	);
  	
    $styles[] =	array(
  		'name'			  => 'main-css',
  		'src'			    => get_bloginfo( 'stylesheet_directory' ) .'/style.css',
  		'deregister'  => FALSE,
  		'deps'			  => array('jquery-ui-css', 'layout-css'),
  		'version'		  => THEME_VERSION,
  		'media'			  => 'all'
  	);
  	
    $styles[] =	array(
  		'name'			  => 'jquery-ui-css',
  		'src'			    => get_bloginfo( 'stylesheet_directory' ) .'/inc/js/jquery-ui-1.8.15/css/ui-lightness/jquery-ui-1.8.15.custom.css',
  		'deregister'  => FALSE,
  		'deps'			  => array('avenir'),
  		'version'		  => THEME_VERSION,
  		'media'			  => 'all'
  	);
  	
    $styles[] = array(
       'name'      => 'avenir',
       'src'     => 'http://fast.fonts.com/cssapi/14030925-5d26-4bc0-ba43-311f095ec21f.css',
       'deregister'  => FALSE,
       'deps'      => FALSE,
       'version'   => THEME_VERSION,
       'media'     => 'all'
    );
		
		self::_register_styles( $styles );
		
		// kinda kludgy use of global wp_styles to get CSS conditionals into Wordpress head
    $conditionals = array(
    //  array(
    //    'name'      => 'ie-lt-8',
    //    'src'     => THEME_CSS_URL . '/ie.css',
    //    'deps'      => FALSE,
    //    'version'   => THEME_VERSION,
    //    'media'     => 'all',
    //    'condition'   => 'lte IE 8'
    //  ),
    //  array(
    //    'name'      => 'ie-8',
    //    'src'     => THEME_CSS_URL . '/ie8.css',
    //    'deps'      => FALSE,
    //    'version'   => THEME_VERSION,
    //    'media'     => 'all',
    //    'condition'   => 'IE 8'
    //  )
    );
		
		self::_register_conditional_styles( $conditionals );
		
		
	}
  
  public static function register_scripts() {
  	wp_enqueue_script( 'thickbox' );
  	wp_enqueue_script( 'jquery' );
  	wp_enqueue_script( 'jquery-ui-core' );
  	wp_enqueue_script( 'jquery-ui-dialog' );
  	
  	$scripts = array();
  	
    // $scripts[] = array(
    //  'name'      => 'flash-thing',
    //       'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/AC_RunActiveContent.js',
    //       'deregister'  => TRUE,
    //       'deps'      => false,
    //       'version'   => '1.0',
    //       'footer'    => TRUE
    // );
    // 
    
    $scripts[] = array(
        'name'      => 'jquery-isotope',
        'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jquery.isotope.min.js',
        'deregister'  => FALSE,
        'deps'      => array('jquery-masonry'),
        'version'   => '1.0',
        'footer'    => FALSE
      );
    
    $scripts[] = array(
        'name'      => 'jquery-masonry',
        'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jquery.masonry.min.js',
        'deregister'  => FALSE,
        'deps'      => FALSE,
        'version'   => '1.0',
        'footer'    => FALSE
      );
      
  	$scripts[] = array(
        'name'      => 'ac-run-active',
        'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/AC_RunActiveContent.js',
        'deregister'  => TRUE,
        'deps'      => false,
        'version'   => '1.0',
        'footer'    => FALSE
      );
      
  	
    // $scripts[] = array(
    //         'name'      => 'json',
    //         'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/json2.js',
    //         'deregister'  => TRUE,
    //         'deps'      => false,
    //         'version'   => '1.0',
    //         'footer'    => TRUE
    //       );
      
    // $scripts[] = array(
    //         'name'      => 'jquery-form',
    //         'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jquery.form.js',
    //         'deregister'  => TRUE,
    //         'deps'      => array('jquery'),
    //         'version'   => '1.0',
    //         'footer'    => TRUE
    //       );
      
    $scripts[] = array(
        'name'      => 'json',
        'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/json2.js',
        'deregister'  => TRUE,
        'deps'      => false,
        'version'   => '1.0',
        'footer'    => TRUE
      );
      $scripts[] = array(
        'name'      => 'client-js',
        'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/client.js',
        'deregister'  => TRUE,
        'deps'      => false,
        'version'   => '1.0',
        'footer'    => TRUE
      );
      $scripts[] = array(
        'name'      => 'main-js',
        'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/main.js',
        'deregister'  => TRUE,
        'deps'      => array('client-js', 'nivo-slider','jquery', 'google-maps', 'jquery-ui-dialog'),
        'version'   => time(), // '1.0',
        'footer'    => TRUE
      );
      $scripts[] = array(
    		'name'			  => 'nivo-slider',
    		'src'			    => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/nivo-slider/jquery.nivo.slider.js',
    		'deregister'	=> FALSE,
    		'deps'			  => array('jquery'),
    		'version'		  => THEME_VERSION,
    		'footer'    => TRUE
    	);
    	$scripts[] = array(
    		'name'			  => 'jscrollpane',
    		'src'			    => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jScrollPane/script/jquery.jscrollpane.js',
    		'deregister'	=> FALSE,
    		'deps'			  => array('jquery-mousewheel'),
    		'version'		  => THEME_VERSION,
    		'footer'    => TRUE
    	);
    	$scripts[] = array(
    		'name'			  => 'jquery-mousewheel',
    		'src'			    => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jScrollPane/script/jquery.mousewheel.js',
    		'deregister'	=> FALSE,
    		'deps'			  => array('jquery'),
    		'version'		  => THEME_VERSION,
    		'footer'    => TRUE
    	);
    	
    	$scripts[] = array(
       'name'      => 'google-maps',
       'src'     => 'http://maps.google.com/maps/api/js?sensor=false',
       'deregister'  => FALSE,
       'deps'      => FALSE,
       'footer'    => TRUE
      );
  	self::_register_scripts( $scripts );
  	wp_localize_script( 'main-js', 'ajaxEP', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
  }
  
  
  public static function register_admin_scripts() {
  	wp_enqueue_script( 'thickbox' );
  	wp_enqueue_script( 'jquery' );
  	wp_enqueue_script( 'jquery-ui-core' );
  	wp_enqueue_script( 'jquery-ui-dialog' );
  	wp_enqueue_script( 'jquery-ui-tabs' );

  	$scripts = array();

  	$scripts[] = array(
      'name'      => 'json',
      'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/json2.js',
      'deregister'  => TRUE,
      'deps'      => false,
      'version'   => '1.0',
      'footer'    => FALSE
    );
    
    $scripts[] = array(
      'name'      => 'file-uploader',
      'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/file-uploader/client/fileuploader.js',
      'deregister'  => FALSE,
      'deps'      => FALSE,
      'version'   => '1.0',
      'footer'    => FALSE
    );
    
    $scripts[] = array(
			'name'			=> 'jquery-ui-slider',
			'src'			=> get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jquery-ui-1.8.15/development-bundle/ui/jquery.ui.slider.js',
			'deregister'	=> true,
			'deps'			=> array( 'jquery-ui-core' ),
			'version'		=> '1.8.10',
			'media'			=> 'all',
			'footer'		=> TRUE
		);
		
    $scripts[] = array(
			'name'			=> 'jquery-ui-datepicker',
			'src'			=> get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jquery-ui-1.8.15/development-bundle/ui/jquery.ui.datepicker.js',
			'deregister'	=> true,
			'deps'			=> array( 'jquery-ui-slider' ),
			'version'		=> '1.8.10',
			'media'			=> 'all',
			'footer'		=> TRUE
		);
		
		/*
    $scripts[] = array(
      'name'      => 'jquery-multiselect-js',
      //'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/jquery.multiselect.min.js',
      'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/ui.multiselect.js',
      'deregister'  => FALSE,
      'deps'      => array('jquery'),
      'version'   => '1.12',
      'footer'    => TRUE
    );
	*/
	
    $scripts[] = array(
      'name'      => 'jquery-dropdownchecklist-js',
      'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/dropdownchecklist/js/ui.dropdownchecklist-1.4-min.js',
      'deregister'  => FALSE,
      'deps'      => array('jquery'),
      'version'   => '1.12',
      'footer'    => TRUE
    );
	
    $scripts[] = array(
      'name'      => 'admin-js',
      'src'     => get_bloginfo( 'stylesheet_directory' ) . '/inc/js/admin.js',
      'deregister'  => FALSE,
      'deps'      => FALSE, // array('file-uploader', 'jquery', 'jquery-ui-tabs'),
      'version'   => '1.0',
      'footer'    => TRUE
    );
    
    $scripts[] = array(
     'name'      => 'google-maps',
     'src'     => 'http://maps.google.com/maps/api/js?sensor=false',
     'deregister'  => FALSE,
     'deps'      => FALSE,
     'footer'    => FALSE
    );
    
  	self::_register_scripts( $scripts );
  }
  
  public static function _register_scripts( $scripts ){
		foreach( $scripts as $script ){
			if( $script['deregister'] )
				wp_deregister_script( $script['name'] );
			wp_register_script( $script['name'], $script['src'], $script['deps'], $script['version'], $script['footer'] );
			wp_enqueue_script( $script['name'] );
		}
	}
	
	public static function _register_styles( $styles ){
		foreach( $styles as $style ){
			if( $style['deregister'] )
				wp_deregister_style( $style['name'] );
			wp_enqueue_style( $style['name'], $style['src'], $style['deps'], $style['version'], $style['media'] );
		}
	}
	
	public static function _register_conditional_styles( $conditionals ){
		
		global $wp_styles;
		foreach( $conditionals as $conditional ){
			wp_register_style( 
				$conditional['name'], $conditional['src'], $conditional['deps'], $conditional['version'], $conditional['media'] 
			);
			$wp_styles->add_data( $conditional['name'], 'conditional', $conditional['condition'] );
			wp_enqueue_style( $conditional['name'] );
		}
		
	}
	
  public static function custom_login_logo() {
      echo '<style type="text/css">
          html{ background-color:#fff!important; }
          form{
            -webkit-box-shadow: none;
            background: white;
            border: 3px solid #DDD;
            border-bottom-left-radius: 8px 8px;
            border-bottom-right-radius: 8px 8px;
            border-top-left-radius: 8px 8px;
            border-top-right-radius: 8px 8px;
            font-weight: normal;
            margin-left: 8px;
            padding: 16px 16px 40px;
          }
          #login { 
              width: 389px!important;

            }
          h1 a { 
            background-image:none; /* url('.THEME_IMG_URL.'/FILE) !important; */
            width: 389px!important;
            margin-right:0!important;
          }
      </style>';
  }
  
  
  
  public static function chp_primary_class(){
    global $post;
    
    if( is_404() ){
      echo " primary-404 ";
      return;
    }
    
    if( is_search() ){
      echo " primary-search ";
      return;
    }
    
    if( !$post->ID ) return;
    
    $class = " primary-" . $post->post_type;
    $class .= " primary-" . $post->post_name . " ";
    
    
    echo $class;
    // switch( $post->post_type ){
    //   case "page":
    //   
    //   break;
    //   case "post":
    //   
    //   break;
    // }
    
  }
  
  public static function chp_content_class(){
    global $post;
    
    if( is_404() ){
      echo " content-404 ";
      return;
    }
    
    if( is_search() ){
      echo " content-search ";
      return;
    }
    
    if( !$post->ID ) return;
    
    $class = " content-" . $post->post_type;
    $class .= " content-" . $post->post_name . " ";
    
    
    echo $class;
    // switch( $post->post_type ){
    //   case "page":
    //   
    //   break;
    //   case "post":
    //   
    //   break;
    // }
    
  }
  
  public static function chp_sidebar_class(){
    global $post;
    
    if( is_404() ){
      echo " sidebar-404 ";
      return;
    }
    if( is_search() ){
      echo " sidebar-search ";
      return;
    }
    if( !$post->ID ) return;
    
    $class = " sidebar-" . $post->post_type;
    $class .= " sidebar-" . $post->post_name . " ";
    
    
    echo $class;
    // switch( $post->post_type ){
    //   case "page":
    //   
    //   break;
    //   case "post":
    //   
    //   break;
    // }
    
  }
  
  public static function register_sidebars(){
    register_sidebar( array(
  		'name' => __( 'Main Sidebar', 'chp' ),
  		'id' => 'sidebar-top',
  		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  		'after_widget' => "</aside>",
  		'before_title' => '<h3 class="widget-title">',
  		'after_title' => '</h3>',
  	) );
		register_sidebar( array(
  		'name' => __( 'Home Twitter Block', 'chp' ),
  		'id' => 'home-twitter',
  		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  		'after_widget' => "</aside>",
  		'before_title' => '<h3 class="widget-title">',
  		'after_title' => '</h3>',
  	) );
  }
  
  public static function debug_dump(){
    // global $post;
    // if( !$post->ID ) return;
    // ThemeUtil::mpuke( get_permalink( $post->ID ) );
    // ThemeUtil::mpuke_ta( $post );
    
    $saved_features = get_option( 'all_featured_post_ids', array() );
    ThemeUtil::mpuke( $saved_features );
  }
  
  public static function front_page_slider()
  {
    $term = get_term_by('name', 'News', 'category');
    
    $size = "main-size";
    // $saved_features = get_option( 'all_featured_post_ids', array() );
    $args = array(
  		'post_type'=>'post',
  		'meta_key'=>'featured_post',
  		'category' => $term->term_id,
  		'meta_value'=>'1',
  		'orderby'=> 'ID',
  		'order'=> 'DESC',
  		'numberposts' => 7
  	);
  	
  	$saved_features = get_posts($args);
  	
    if( is_array($saved_features) && count( $saved_features ) > 0 ): ?>
      <div id="slider-wrapper">
  	    <div id="slider" class="nivoSlider">
  	    <?php  
          foreach( $saved_features as $k=>$v ):
        
            // $post = get_post($k);
            $custom = get_post_custom( $v->ID );
            $attachments = get_children( 
          		array(
          			'post_parent' 		=> $v->ID, 
          			'post_status' 		=> 'inherit', 
          			'post_type' 		  => 'attachment', 
          			'post_mime_type'	=> 'image', 
          			'order' 		  	  => 'ASC', 
          			'orderby' 			  => 'menu_order ID',
          			'numberposts'     => 1
          		) 
          	);
      	
      	    if( count( $attachments ) ): ?>
      	    
        	    <?php foreach( $attachments as $att ):
        		    $src = wp_get_attachment_image_src( $att->ID );
        		    $img_args['id'] = $att->ID;
        			  $img = wp_get_attachment_image( $att->ID, $size, false, $img_args );
        		    $img_src = wp_get_attachment_image_src( $att->ID, $size ); ?>

        		    <a href="<?php echo get_permalink($v->ID); ?>"
        		    ><img src="<?php echo $img_src[0]; ?>" class="slider-img" title="<?php echo stripslashes($att->post_title); ?>"
        		      id="slider-img-<?php echo $att->ID; ?>" /></a>
                
                  <!--div id="cap_<?php echo $att->post_name; ?>" class="nivo-html-caption">
                    <?php echo $att->post_title; ?>
                  </div-->
                
                
        		  <?php endforeach; ?>
      	    
      	    <?php endif; ?>
          <?php endforeach;  ?>
          
        </div>
      </div>  
    <?php endif;    
  }
}


add_action('login_head', array('ThemeTheme', 'custom_login_logo'));

if( !is_admin() ){
	// add_action( 'after_setup_theme', 	array( 'ThemeTheme', 'init' ) );
	add_action( 'init', 				          array('ThemeTheme', 'register_scripts') );
	add_action( 'wp_print_styles', 	      array('ThemeTheme', 'register_styles') );
	
}
else{
  add_action( 'admin_init', 			array('ThemeTheme', 'register_admin_scripts') );
	add_action( 'admin_init', 	    array('ThemeTheme', 'register_admin_styles') );
}

// add_action( 'wp_footer',       array( 'ThemeTheme', 'debug_dump') );

add_action( 'widgets_init',       array( 'ThemeTheme', 'register_sidebars') );

