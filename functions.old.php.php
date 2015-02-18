<?php
  
  ini_set('post_max_size', 1048576); // 1 MB max
  ini_set('upload_max_filesize', 1048576);
  ini_set('max_execution_time',180);
  
  define( 'NONCE_STRING', 'Homeport' );
  
  define( 'ADMIN_PAGE_TITLE', 'Homeport Admin' );
  define( 'ADMIN_MENU_TITLE', 'Homeport Admin' );


  define( 'ADMIN_CAPABILITY', 'administrator');
  define( 'EDITOR_CAPABILITY', 'editor');

  define( 'ADMIN_SLUG', 'chp-admin' );
  define( 'ADMIN_ICON_URL', false );
  define( 'ADMIN_MENU_POSITION', 2 );
  
  define( 'THEME_VERSION', '0.1' );
  // THEME_UPLOAD_RECEIVER
  
  define( 'WP_JS_URL', home_url() . '/wp-includes/js' );
  
  define( 'THEME_URL',  get_bloginfo('stylesheet_directory') );
  define( 'THEME_IMG_URL',  get_bloginfo('stylesheet_directory') . '/images' );
  
  // echo THEME_IMG_URL;

  define( 'THEME_INC_PATH', THEME_URL . '/inc' );
  define( 'THEME_UPLOAD_RECEIVER', THEME_URL . '/inc/uploads/receiver.php' );
  
  if( !(get_option('property_type', false) ) )
    update_option( 'property_type', array( 'Sale', 'Rent' ) );
  if( !(get_option('part_of_town', false) ) )
    update_option( 'part_of_town', array( 
      'Northwest', 'North', 'NorthEast', 'East', 'SouthEast', 'South', 'SouthWest', 'West'
    ) );
  if( !(get_option('occupancy_type', false) ) )
    update_option( 'occupancy_type', array( 
      'Single Family Home', 'Condo/Townhome', 'Apartment', 'Cottage'
    ) );
    	
  
  function _js_head_inject(){
    global $post;
    if(!$post->ID){
      $post = get_post($_REQUEST['post']);
    }
    
    if(!$post->ID): 
      ?>
      <script type="text/javascript">
        var the_post_type = '<?php echo $_REQUEST['post_type']; ?>';
        var saved_lat = '<?php echo $_REQUEST['lat']; ?>';
        var saved_lng = '<?php echo $_REQUEST['lng']; ?>';
        var is_single = false;
        var upload_receiver = '<?php echo THEME_UPLOAD_RECEIVER; ?>';
      </script>
    
    <?php else: 
      $custom = get_post_custom($post->ID);
      ?>
      <script type="text/javascript">
        var the_post_type = '<?php echo $post->post_type; ?>';
        var saved_lat = '<?php echo $custom['lat'][0]; ?>';
        var saved_lng = '<?php echo $custom['lng'][0]; ?>';
        var is_single = <?php $single = is_single() ? "true" : "false"; echo $single; ?>;
        var upload_receiver = '<?php echo THEME_UPLOAD_RECEIVER; ?>';
      </script>
      
    <?php endif;
    
  }
  if(is_admin())
    add_action( 'admin_print_scripts', 		'_js_head_inject' );
  else
    add_action( 'wp_head', 		'_js_head_inject', 999 );
    
  require_once 'inc/theme.shortcodes.php';
  require_once 'inc/theme.options.php';
  require_once 'inc/theme.admin.php';
  require_once 'inc/theme.php';
  require_once 'inc/property.type.php';
  
  /*
   * Set the content width based on the theme's design and stylesheet.
   */
  if ( ! isset( $content_width ) )
  	$content_width = 584;

  /**
   * Tell WordPress to run chp_setup() when the 'after_setup_theme' hook is run.
   */
  // add_action( 'after_setup_theme', 'chp_setup' );

  if ( ! function_exists( 'chp_setup' ) ):
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which runs
   * before the init hook. The init hook is too late for some features, such as indicating
   * support post thumbnails.
   *
   * To override chp_setup() in a child theme, add your own chp_setup to your child theme's
   * functions.php file.
   *
   * @uses load_theme_textdomain() For translation/localization support.
   * @uses add_editor_style() To style the visual editor.
   * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
   * @uses register_nav_menus() To add support for navigation menus.
   * @uses add_custom_background() To add support for a custom background.
   * @uses add_custom_image_header() To add support for a custom header.
   * @uses register_default_headers() To register the default custom header images provided with the theme.
   * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
   *
   * @since Twenty Eleven 1.0
   */
  function chp_setup() {

  	/* Make Twenty Eleven available for translation.
  	 * Translations can be added to the /languages/ directory.
  	 * If you're building a theme based on Twenty Eleven, use a find and replace
  	 * to change 'chp' to the name of your theme in all the template files.
  	 */
  	load_theme_textdomain( 'chp', TEMPLATEPATH . '/languages' );

  	$locale = get_locale();
  	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
  	if ( is_readable( $locale_file ) )
  		require_once( $locale_file );

  	// This theme styles the visual editor with editor-style.css to match the theme style.
  	add_editor_style();

    // // Load up our theme options page and related code.
    // require( dirname( __FILE__ ) . '/inc/theme-options.php' );
    // 
    // // Grab Twenty Eleven's Ephemera widget.
    // require( dirname( __FILE__ ) . '/inc/widgets.php' );

  	// Add default posts and comments RSS feed links to <head>.
  	add_theme_support( 'automatic-feed-links' );

  	// This theme uses wp_nav_menu() in one location.
  	register_nav_menu( 'primary', __( 'Primary Menu', 'chp' ) );

  	// Add support for a variety of post formats
  	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

  	// Add support for custom backgrounds
  	add_custom_background();

  	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
  	add_theme_support( 'post-thumbnails' );

  	// The next four constants set how Twenty Eleven supports custom headers.

  	// The default header text color
  	define( 'HEADER_TEXTCOLOR', '000' );

  	// By leaving empty, we allow for random image rotation.
  	define( 'HEADER_IMAGE', '' );

  	// The height and width of your custom header.
  	// Add a filter to chp_header_image_width and chp_header_image_height to change these values.
  	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'chp_header_image_width', 1000 ) );
  	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'chp_header_image_height', 288 ) );

  	// We'll be using post thumbnails for custom header images on posts and pages.
  	// We want them to be the size of the header image that we just defined
  	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
  	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

  	// Add Twenty Eleven's custom image sizes
  	add_image_size( 'large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true ); // Used for large feature (header) images
  	add_image_size( 'small-feature', 500, 300 ); // Used for featured posts if a large-feature doesn't exist

  	// Turn on random header image rotation by default.
  	add_theme_support( 'custom-header', array( 'random-default' => true ) );

  	// Add a way for the custom header to be styled in the admin panel that controls
  	// custom headers. See chp_admin_header_style(), below.
  	add_custom_image_header( 'chp_header_style', 'chp_admin_header_style', 'chp_admin_header_image' );

  	// ... and thus ends the changeable header business.

  	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
  	register_default_headers( array(
  		'wheel' => array(
  			'url' => '%s/images/headers/wheel.jpg',
  			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Wheel', 'chp' )
  		),
  		'shore' => array(
  			'url' => '%s/images/headers/shore.jpg',
  			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Shore', 'chp' )
  		),
  		'trolley' => array(
  			'url' => '%s/images/headers/trolley.jpg',
  			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Trolley', 'chp' )
  		),
  		'pine-cone' => array(
  			'url' => '%s/images/headers/pine-cone.jpg',
  			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Pine Cone', 'chp' )
  		),
  		'chessboard' => array(
  			'url' => '%s/images/headers/chessboard.jpg',
  			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Chessboard', 'chp' )
  		),
  		'lanterns' => array(
  			'url' => '%s/images/headers/lanterns.jpg',
  			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Lanterns', 'chp' )
  		),
  		'willow' => array(
  			'url' => '%s/images/headers/willow.jpg',
  			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Willow', 'chp' )
  		),
  		'hanoi' => array(
  			'url' => '%s/images/headers/hanoi.jpg',
  			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
  			/* translators: header image description */
  			'description' => __( 'Hanoi Plant', 'chp' )
  		)
  	) );
  }
  endif; // chp_setup

  if ( ! function_exists( 'chp_header_style' ) ) :
  /**
   * Styles the header image and text displayed on the blog
   *
   * @since Twenty Eleven 1.0
   */
  function chp_header_style() {

  	// If no custom options for text are set, let's bail
  	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
  	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
  		return;
  	// If we get this far, we have custom styles. Let's do this.
  	?>
  	<style type="text/css">
  	<?php
  		// Has the text been hidden?
  		if ( 'blank' == get_header_textcolor() ) :
  	?>
  		#site-title,
  		#site-description {
  			position: absolute !important;
  			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
  			clip: rect(1px, 1px, 1px, 1px);
  		}
  	<?php
  		// If the user has set a custom color for the text use that
  		else :
  	?>
  		#site-title a,
  		#site-description {
  			color: #<?php echo get_header_textcolor(); ?> !important;
  		}
  	<?php endif; ?>
  	</style>
  	<?php
  }
  endif; // chp_header_style

  if ( ! function_exists( 'chp_admin_header_style' ) ) :
  /**
   * Styles the header image displayed on the Appearance > Header admin panel.
   *
   * Referenced via add_custom_image_header() in chp_setup().
   *
   * @since Twenty Eleven 1.0
   */
  function chp_admin_header_style() {
  ?>
  	<style type="text/css">
  	.appearance_page_custom-header #headimg {
  		border: none;
  	}
  	#headimg h1,
  	#desc {
  		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
  	}
  	#headimg h1 {
  		margin: 0;
  	}
  	#headimg h1 a {
  		font-size: 32px;
  		line-height: 36px;
  		text-decoration: none;
  	}
  	#desc {
  		font-size: 14px;
  		line-height: 23px;
  		padding: 0 0 3em;
  	}
  	<?php
  		// If the user has set a custom color for the text use that
  		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
  	?>
  		#site-title a,
  		#site-description {
  			color: #<?php echo get_header_textcolor(); ?>;
  		}
  	<?php endif; ?>
  	#headimg img {
  		max-width: 1000px;
  		height: auto;
  		width: 100%;
  	}
  	</style>
  <?php
  }
  endif; // chp_admin_header_style

  if ( ! function_exists( 'chp_admin_header_image' ) ) :
  /**
   * Custom header image markup displayed on the Appearance > Header admin panel.
   *
   * Referenced via add_custom_image_header() in chp_setup().
   *
   * @since Twenty Eleven 1.0
   */
  function chp_admin_header_image() { ?>
  	<div id="headimg">
  		<?php
  		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
  			$style = ' style="display:none;"';
  		else
  			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
  		?>
  		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
  		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
  		<?php $header_image = get_header_image();
  		if ( ! empty( $header_image ) ) : ?>
  			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
  		<?php endif; ?>
  	</div>
  <?php }
  endif; // chp_admin_header_image

  /**
   * Sets the post excerpt length to 40 words.
   *
   * To override this length in a child theme, remove the filter and add your own
   * function tied to the excerpt_length filter hook.
   */
  function chp_excerpt_length( $length ) {
  	return 40;
  }
  add_filter( 'excerpt_length', 'chp_excerpt_length' );

  /**
   * Returns a "Continue Reading" link for excerpts
   */
  function chp_continue_reading_link() {
  	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'chp' ) . '</a>';
  }

  /**
   * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and chp_continue_reading_link().
   *
   * To override this in a child theme, remove the filter and add your own
   * function tied to the excerpt_more filter hook.
   */
  function chp_auto_excerpt_more( $more ) {
  	return ' &hellip;' . chp_continue_reading_link();
  }
  add_filter( 'excerpt_more', 'chp_auto_excerpt_more' );

  /**
   * Adds a pretty "Continue Reading" link to custom post excerpts.
   *
   * To override this link in a child theme, remove the filter and add your own
   * function tied to the get_the_excerpt filter hook.
   */
  function chp_custom_excerpt_more( $output ) {
  	if ( has_excerpt() && ! is_attachment() ) {
  		$output .= chp_continue_reading_link();
  	}
  	return $output;
  }
  add_filter( 'get_the_excerpt', 'chp_custom_excerpt_more' );

  /**
   * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
   */
  function chp_page_menu_args( $args ) {
  	$args['show_home'] = true;
  	return $args;
  }
  add_filter( 'wp_page_menu_args', 'chp_page_menu_args' );

  /**
   * Register our sidebars and widgetized areas. Also register the default Epherma widget.
   *
   * @since Twenty Eleven 1.0
   */
  function chp_widgets_init() {
  
   // register_widget( 'Twenty_Eleven_Ephemera_Widget' );
  
   register_sidebar( array(
     'name' => __( 'Main Sidebar', 'chp' ),
     'id' => 'sidebar-1',
     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
     'after_widget' => "</aside>",
     'before_title' => '<h3 class="widget-title">',
     'after_title' => '</h3>',
   ) );
  
   register_sidebar( array(
     'name' => __( 'Showcase Sidebar', 'chp' ),
     'id' => 'sidebar-2',
     'description' => __( 'The sidebar for the optional Showcase Template', 'chp' ),
     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
     'after_widget' => "</aside>",
     'before_title' => '<h3 class="widget-title">',
     'after_title' => '</h3>',
   ) );
  
   register_sidebar( array(
     'name' => __( 'Footer Area One', 'chp' ),
     'id' => 'sidebar-3',
     'description' => __( 'An optional widget area for your site footer', 'chp' ),
     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
     'after_widget' => "</aside>",
     'before_title' => '<h3 class="widget-title">',
     'after_title' => '</h3>',
   ) );
  
   register_sidebar( array(
     'name' => __( 'Footer Area Two', 'chp' ),
     'id' => 'sidebar-4',
     'description' => __( 'An optional widget area for your site footer', 'chp' ),
     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
     'after_widget' => "</aside>",
     'before_title' => '<h3 class="widget-title">',
     'after_title' => '</h3>',
   ) );
  
   register_sidebar( array(
     'name' => __( 'Footer Area Three', 'chp' ),
     'id' => 'sidebar-5',
     'description' => __( 'An optional widget area for your site footer', 'chp' ),
     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
     'after_widget' => "</aside>",
     'before_title' => '<h3 class="widget-title">',
     'after_title' => '</h3>',
   ) );
  }
  add_action( 'widgets_init', 'chp_widgets_init' );

  /**
   * Display navigation to next/previous pages when applicable
   */
  function chp_content_nav( $nav_id ) {
  	global $wp_query;

  	if ( $wp_query->max_num_pages > 1 ) : ?>
  		<nav id="<?php echo $nav_id; ?>">
  			<h3 class="assistive-text"><?php _e( 'Post navigation', 'chp' ); ?></h3>
  			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'chp' ) ); ?></div>
  			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'chp' ) ); ?></div>
  		</nav><!-- #nav-above -->
  	<?php endif;
  }

  /**
   * Return the URL for the first link found in the post content.
   *
   * @since Twenty Eleven 1.0
   * @return string|bool URL or false when no link is present.
   */
  function chp_url_grabber() {
  	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
  		return false;

  	return esc_url_raw( $matches[1] );
  }

  /**
   * Count the number of footer sidebars to enable dynamic classes for the footer
   */
  function chp_footer_sidebar_class() {
  	$count = 0;

  	if ( is_active_sidebar( 'sidebar-3' ) )
  		$count++;

  	if ( is_active_sidebar( 'sidebar-4' ) )
  		$count++;

  	if ( is_active_sidebar( 'sidebar-5' ) )
  		$count++;

  	$class = '';

  	switch ( $count ) {
  		case '1':
  			$class = 'one';
  			break;
  		case '2':
  			$class = 'two';
  			break;
  		case '3':
  			$class = 'three';
  			break;
  	}

  	if ( $class )
  		echo 'class="' . $class . '"';
  }

  if ( ! function_exists( 'chp_comment' ) ) :
  /**
   * Template for comments and pingbacks.
   *
   * To override this walker in a child theme without modifying the comments template
   * simply create your own chp_comment(), and that function will be used instead.
   *
   * Used as a callback by wp_list_comments() for displaying the comments.
   *
   * @since Twenty Eleven 1.0
   */
  function chp_comment( $comment, $args, $depth ) {
  	$GLOBALS['comment'] = $comment;
  	switch ( $comment->comment_type ) :
  		case 'pingback' :
  		case 'trackback' :
  	?>
  	<li class="post pingback">
  		<p><?php _e( 'Pingback:', 'chp' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?></p>
  	<?php
  			break;
  		default :
  	?>
  	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  		<article id="comment-<?php comment_ID(); ?>" class="comment">
  			<footer class="comment-meta">
  				<div class="comment-author vcard">
  					<?php
  						$avatar_size = 68;
  						if ( '0' != $comment->comment_parent )
  							$avatar_size = 39;

  						echo get_avatar( $comment, $avatar_size );

  						/* translators: 1: comment author, 2: date and time */
  						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'chp' ),
  							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
  							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
  								esc_url( get_comment_link( $comment->comment_ID ) ),
  								get_comment_time( 'c' ),
  								/* translators: 1: date, 2: time */
  								sprintf( __( '%1$s at %2$s', 'chp' ), get_comment_date(), get_comment_time() )
  							)
  						);
  					?>

  					<?php edit_comment_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?>
  				</div><!-- .comment-author .vcard -->

  				<?php if ( $comment->comment_approved == '0' ) : ?>
  					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'chp' ); ?></em>
  					<br />
  				<?php endif; ?>

  			</footer>

  			<div class="comment-content"><?php comment_text(); ?></div>

  			<div class="reply">
  				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'chp' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
  			</div><!-- .reply -->
  		</article><!-- #comment-## -->

  	<?php
  			break;
  	endswitch;
  }
  endif; // ends check for chp_comment()

  if ( ! function_exists( 'chp_posted_on' ) ) :
  /**
   * Prints HTML with meta information for the current post-date/time and author.
   * Create your own chp_posted_on to override in a child theme
   *
   * @since Twenty Eleven 1.0
   */
  function chp_posted_on() {
  	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'chp' ),
  		esc_url( get_permalink() ),
  		esc_attr( get_the_time() ),
  		esc_attr( get_the_date( 'c' ) ),
  		esc_html( get_the_date() ),
  		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
  		sprintf( esc_attr__( 'View all posts by %s', 'chp' ), get_the_author() ),
  		esc_html( get_the_author() )
  	);
  }
  endif;

  /**
   * Adds two classes to the array of body classes.
   * The first is if the site has only had one author with published posts.
   * The second is if a singular post being displayed
   *
   * @since Twenty Eleven 1.0
   */
  function chp_body_classes( $classes ) {

  	if ( ! is_multi_author() ) {
  		$classes[] = 'single-author';
  	}

  	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
  		$classes[] = 'singular';

  	return $classes;
  }
  add_filter( 'body_class', 'chp_body_classes' );

