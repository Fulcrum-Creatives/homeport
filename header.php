<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
  
       
	<?php
		if($post->ID == 1394) { // include request-form js/css on request form page
			wp_enqueue_script('request-form-js',
							get_template_directory_uri() . '/inc/js/request-form.js',
							'jquery');
			wp_enqueue_style('request-form-css',
							get_template_directory_uri() . '/request-form.css',
							array('gforms_css'));
		}
	?>
  <title>
  
  <?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?>
  
  
  </title>
  <link href="/wp-content/themes/chp/dropdown/css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <!-- <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" /> -->
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="description" content="<?php echo get_option( THEME_NICE_NAME . "_seo_desc_text", '');?>" />
  <meta name="keywords" content="<?php echo get_option( THEME_NICE_NAME . "_seo_desc_keywords", '');?>" />
  
<?php
		if(is_page_template( 'front-page.php' ) ) { ?>
        
  <!--
  jQuery library
-->
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/inc/js/jcarousel/jquery-1.4.2.min.js"></script>
<!--
  jCarousel library
-->
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/inc/js/jcarousel/jquery.jcarousel.min.js"></script>
         
  
  <script type="text/javascript">

function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

$(document).ready(function() {
    $('#mycarousel').jcarousel({
        auto: 2,
        wrap: 'circular',
		animation: "slow",
        initCallback: mycarousel_initCallback
    });
});

</script>

<?php } ?> <!-- end homepage specific JS -->
  
  <?php wp_head(); ?>
</head>
<?php

	if (isset($post->post_parent) && $post->post_parent) {
		$ancestors=get_post_ancestors($post->ID);
		$root=count($ancestors)-1;
		$parent = $ancestors[$root];
	}
	else if(isset($post->ID)) {
		$parent = $post->ID;
	}
	else {
		$parent = '';
	}
	
	// combine rental and buy pages into 'buy' or 'rent' and give pretty names to known sections
	switch($parent) {
		case 238:  // 238 = live
			$page_class = 'live';
			break;
		case 241: // 241 = learn
			$page_class = 'learn';
			break;
		case 244: // 244 = give
			$page_class = 'give';
			break;
		case 247: // 247 = volunteer
			$page_class = 'volunteer';
			break;
		case 249: // 249 = about
			$page_class = 'about';
			break;
		case 2:  // 2 = about-rental
			$page_class = 'rent';
			break;
		case 6:  // 6 = search page
			if($_GET['property_type'] == 'Rent') {
				$page_class = 'rent';
			}
			else {
				$page_class = 'buy';
			}
			break;
		case 251: // 251 = non-search buy parent id
			$page_class = 'buy';
			break;
		default:
			$page_class = $parent;
	}
	
?>
<body id="section-<?php echo $page_class;?>">
  <div id="nopriv_meta_box_nonce" style="display:none!important"><?php echo wp_create_nonce( NONCE_STRING ); ?></div>
  <div id="wrap-outer">
  <div id="wrap">
    <div id="header">
      <a href="/" title="Home"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/logo.png" width="279" height="159" alt="Homeport" id="logo" /></a>
      <div id="header-inner-wrap">
         <div id="header-top" class="avenir green"><?php echo get_option( THEME_NICE_NAME . "_top_banner_text", '');?></div>
        <div class="clearfix"></div>
        <div id="header-bottom">
          <div id="nav-menu"><?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu dropdown' ) ); ?><div class="clearfix"></div></div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    