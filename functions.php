<?php
require(get_template_directory() . '/inc/constant-variables.php');
  setlocale(LC_MONETARY, 'en_US');
  function get_execution_time()
  {
      static $microtime_start = null;
      if($microtime_start === null)
      {
          $microtime_start = microtime(true);
          return 0.0; 
      }    
      return microtime(true) - $microtime_start; 
  }
  
  // error_reporting(0);
  $current_user = wp_get_current_user();
  if(get_post_type() == 'volunteer' && $current_user->ID == 0) {
  	header("location: /");
  	exit();
  }
  
  // needed to choose primary image for property and developments
  //remove_theme_support('post-thumbnails');
  add_theme_support('post-thumbnails');
  
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
  
  define( 'THEME_NICE_NAME', 'chp' );
  define( 'THEME_VERSION', '0.1' );
  // THEME_UPLOAD_RECEIVER
  
  define( 'WP_JS_URL', home_url() . '/wp-includes/js' );
  
  define( 'THEME_URL',  get_bloginfo('stylesheet_directory') );
  define( 'THEME_IMG_URL',  get_bloginfo('stylesheet_directory') . '/images' );
  
  // echo THEME_IMG_URL;

  define( 'THEME_INC_PATH', THEME_URL . '/inc' );
  define( 'THEME_UPLOAD_RECEIVER', THEME_URL . '/inc/uploads/receiver.php' );

  if ( function_exists( 'register_nav_menu' ) ) {
    
    function chp_register_menus(){
      
      
      register_nav_menu( 'sidebar-post-menu', __( 'Sidebar Post Menu' ) );
      register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
      
      register_nav_menu( 'footer-rent-menu', __( 'Footer Rent Menu' ) );
      register_nav_menu( 'footer-live-menu', __( 'Footer Live Menu' ) );
      register_nav_menu( 'footer-learn-menu', __( 'Footer Learn Menu' ) );
      register_nav_menu( 'footer-buy-menu', __( 'Footer Buy Menu' ) );
      
      register_nav_menu( 'footer-programs-menu', __( 'Footer Programs Menu' ) );
      register_nav_menu( 'footer-involved-menu', __( 'Footer Involved Menu' ) );
	  
	  register_nav_menu( 'home-twitter-menu', __( 'Home Twitter Menu' ) );
      
    }
    
    add_action( 'init', 'chp_register_menus' );
    
  	
  }

  if( function_exists( 'add_theme_support' ) ) {

    add_image_size( 'home-logo', 135, 60, true );
    add_image_size( 'single-post', 250, 250, true );
    add_image_size( 'list-thumb', 150, 150, true );
    add_image_size( 'square-thumb', 100, 100, true );
    add_image_size( 'main-size', 614, 275, true ); // 530
    add_image_size( 'mobile-size', 280, 200, true );
    add_image_size( 'sidebar-thumb', 150, 90, true );
    add_image_size( 'masonry-size', 275, 155, true ); 

  }
  
  function chp_event_sort($a, $b) {
      if($a->custom['chp_year'] < $b->custom['chp_year']) {
          return -1;
      }
      else if ($a->custom['chp_year'] > $b->custom['chp_year']) {
          return 1;
      }
      else {
        if ($a->custom['chp_month_num'] < $b->custom['chp_month_num']) {
            return -1;
        }
        else if ($a->custom['chp_month_num'] > $b->custom['chp_month_num']) {
            return 1;
        }
        else{
          if ($a->custom['chp_day_num'] < $b->custom['chp_day_num']) {
              return -1;
          }
          else if ($a->custom['chp_day_num'] > $b->custom['chp_day_num']) {
              return 1;
          }
        }
      }
      return 0;
  }
  
	function chp_event_sort_asc($a, $b) {
		return 1 * chp_event_sort($a, $b);
	}
  
	function chp_event_sort_desc($a, $b) {
		return -1 * chp_event_sort($a, $b);
	} 
  
	function chp_excerpt( $the_contents = '', $read_more_tag = '...READ MORE', $perma_link_to = '', $all_words = 45 ) {
	// make the list of allowed tags
	$allowed_tags = array( 'a', 'abbr', 'b', 'blockquote', 'b', 'cite', 'code', 'div', 'em', 'fon', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i', 'img', 'label', 'i', 'p', 'pre', 'span', 'strong', 'title', 'ul', 'ol', 'li', 'object', 'embed', 'param' );
	if( $the_contents != '' && $all_words > 0 ) {
		// process allowed tags
		$allowed_tags = '<' . implode( '><', $allowed_tags ) . '>';
		$the_contents = str_replace( ' ]]>', ' ]]>', $the_contents );
		$the_contents = strip_tags( $the_contents, $allowed_tags );
		// exclude HTML from counting words
		if( $all_words > count( preg_split( '/[\s]+/', strip_tags( $the_contents ), -1 ) ) ) return $the_contents;
		// count all
		$all_chunks = preg_split( '/([\s]+)/', $the_contents, -1, PREG_SPLIT_DELIM_CAPTURE );
		$the_contents = '';
		$count_words = 0;
		$enclosed_by_tag = false;
		foreach( $all_chunks as $chunk ) {
			// is tag opened?
			if( 0 < preg_match( '/<[^>]*$/s', $chunk ) ) $enclosed_by_tag = true;
			elseif( 0 < preg_match( '/>[^<]*$/s', $chunk ) ) $enclosed_by_tag = false; 			// get entire word 
			if( !$enclosed_by_tag && '' != trim( $chunk ) && substr( $chunk, -1, 1 ) != '>' ) $count_words ++;
			$the_contents .= $chunk;
			if( $count_words >= $all_words && !$enclosed_by_tag ) break;
		}
        // note the class named 'more-link'. style it on your own
		if(strlen($perma_link_to)) {
			$the_contents = "{$the_contents}<a href='{$perma_link_to}'>&nbsp;&nbsp;&nbsp;&nbsp;{$read_more_tag}</a>";
		}
		else {
			$the_contents = $the_contents . '' . $read_more_tag . '';
		}
		// native WordPress check for unclosed tags
		$the_contents = force_balance_tags( $the_contents );
	}
	return $the_contents;
}

function chp_search_excerpt( $the_contents = '', $read_more_tag = '...READ MORE', $perma_link_to = '', $all_words = 45 ) {
	// make the list of allowed tags
	$allowed_tags = array( );
	if( $the_contents != '' && $all_words > 0 ) {
		// process allowed tags
		$allowed_tags = '<' . implode( '><', $allowed_tags ) . '>';
		$the_contents = str_replace( ' ]]>', ' ]]>', $the_contents );
		$the_contents = strip_tags( $the_contents, $allowed_tags );
		// exclude HTML from counting words
		if( $all_words > count( preg_split( '/[\s]+/', strip_tags( $the_contents ), -1 ) ) ) return $the_contents;
		// count all
		$all_chunks = preg_split( '/([\s]+)/', $the_contents, -1, PREG_SPLIT_DELIM_CAPTURE );
		$the_contents = '';
		$count_words = 0;
		$enclosed_by_tag = false;
		foreach( $all_chunks as $chunk ) {
			// is tag opened?
			if( 0 < preg_match( '/<[^>]*$/s', $chunk ) ) $enclosed_by_tag = true;
			elseif( 0 < preg_match( '/>[^<]*$/s', $chunk ) ) $enclosed_by_tag = false; 			// get entire word 
			if( !$enclosed_by_tag && '' != trim( $chunk ) && substr( $chunk, -1, 1 ) != '>' ) $count_words ++;
			$the_contents .= $chunk;
			if( $count_words >= $all_words && !$enclosed_by_tag ) break;
		}
        // note the class named 'more-link'. style it on your own
		if(strlen($perma_link_to)) {
			$the_contents = "{$the_contents}<a href='{$perma_link_to}'>&nbsp;&nbsp;&nbsp;&nbsp;{$read_more_tag}</a>";
		}
		else {
			$the_contents = $the_contents . '' . $read_more_tag . '';
		}
		// native WordPress check for unclosed tags
		$the_contents = force_balance_tags( $the_contents );
	}
	return $the_contents;
}
	
  function chp_excerpt_length( $length ) {
  	return 40;
  }
  add_filter( 'excerpt_length', 'chp_excerpt_length' );
  
  // delete_option('volunteer_slot_days');
  if( !(get_option('volunteer_slot_days', false) ) )
    update_option( 'volunteer_slot_days', array( 'Su', 'M', 'T', 'W', 'R', 'F', 'Sa' ) );
  
  if( !(get_option('volunteer_slot_blocks', false) ) )
    update_option( 'volunteer_slot_blocks', array( 
      '9am - 11am', '11am-1pm', '1pm-3pm', '3pm-5pm', '5pm-7pm'
    )  
  );
    
  // delete_option('volunteer_interests');
  if( !(get_option('volunteer_interests', false) ) )
    update_option( 'volunteer_interests', array( 
      'Interest #1', 'Interest #2', 'Interest #3', 'Interest #4'
    )  
  );
  

//     delete_option('event_type');
    if( !(get_option('event_type', false) ) )
      update_option( 'event_type', array( 
        'Class', 'Open House', 'Luncheon', 'Information Session', 'Property Tour'
      )  
    );
    
  if( !(get_option('garage_type', false) ) )
    update_option( 'garage_type', array( 
      '1 Car', '2 Car', '1 Car Detached', '2 Car Detached'
    )  
  );
  
  //delete_option('neighborhood_type');
  if( !(get_option('neighborhood_type', false) ) )
    update_option( 'neighborhood_type', array( 
      'Downtown Columbus', 'Driving Park', 'East Broad Street', 'East Columbus', 'King-Lincoln Bronzeville',
      'Weinland Park', 'Weinland Park'
      // 'Weinland Park', 'King Lincoln District', 'Short North', 'Whitehall', 'Clintonville'
    )  
  );
    
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
    
  if( !(get_option('status_type', false) ) )
    update_option( 'status_type', array( 
      'Available', 'Available - Call For Pricing', 'Available - New Price', 'Coming Soon!', 'Sold', 'In Contract', 
    ) );
    
  if( !(get_option('project_type', false) ) )
    update_option( 'project_type', array( 
      'Renovated', 'Single Family Home', 'New Build', 'Cape Cod' 
    ) );
    
  if( !(get_option('development_type', false) ) )
    update_option( 'development_type', array( 
      'North of Broad', 'Restore Columbus', 'American Addition', 'The Crossing at Joyce', 'Northland Homes', 'Rich Street Walk' 
    ) );
    
  
    	
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
  
  function _js_head_inject(){
    global $post;
    if(!$post->ID){
      $post = get_post($_REQUEST['post']);
    }
    // if(!$post->ID) return false;
    
    if(!empty($_REQUEST['property_type'])):?>
      <script type="text/javascript">
        var query_data = { property_type : "<?php echo $_REQUEST['property_type']; ?>", property_status: "<?php echo $_REQUEST['property_status']; ?>" };
      </script>
    <?php else: ?>
      <script type="text/javascript">
        var query_data = {};
      </script>
    <?php endif;
    if(!$post->ID): 
      ?>
      <script type="text/javascript">
        var the_post_type = '<?php echo $_REQUEST['post_type']; ?>';
        var the_post_title = '<?php echo $_REQUEST['post_title']; ?>';
        var saved_lat = '<?php echo $_REQUEST['lat']; ?>';
        var saved_lng = '<?php echo $_REQUEST['lng']; ?>';
        var is_front_page = <?php $front = is_front_page() ? "true" : "false"; echo $front; ?>;
        var is_single = false;
        var post_id = 0;
        var upload_receiver = '<?php echo THEME_UPLOAD_RECEIVER; ?>';
      </script>
    
    <?php else: 
      $custom = get_post_custom($post->ID);
      // echo '<!--';
      // print_r($custom);
      // echo '-->';
      ?>
      <script type="text/javascript">
        var post_id = parseInt('<?php echo $post->ID; ?>');
        var the_post_type = '<?php echo $post->post_type; ?>';
        var the_post_title = '<?php echo $post->post_title; ?>';
        <?php if( !empty($custom)): ?>
        var saved_lat = '<?php echo $custom['lat'][0]; ?>';
        var saved_lng = '<?php echo $custom['lng'][0]; ?>';
        var property_type = '<?php echo $custom["property_type"][0]; ?>';
        <?php else: ?>
        var saved_lat = '';
        var saved_lng = '';
        var property_type = '';
        <?php endif; ?>
        var is_front_page = <?php $front = is_front_page() ? "true" : "false"; echo $front; ?>;
        var is_single = <?php $single = is_single() ? "true" : "false"; echo $single; ?>;
        var upload_receiver = '<?php echo THEME_UPLOAD_RECEIVER; ?>';
      </script>
      
    <?php endif; ?>
    <script type="text/javascript">
    var rsvp_ok = '<?php echo get_option( THEME_NICE_NAME . "_rsvp_ok", '');?>';
    var rsvp_not_ok = '<?php echo get_option( THEME_NICE_NAME . "_rsvp_not_ok", '');?>';
    </script>
    <?php
  }
  if(is_admin())
    add_action( 'admin_print_scripts', 		'_js_head_inject' );
  else
    add_action( 'wp_head', 		'_js_head_inject', 999 );
  
  // require_once 'inc/theme.kses.php';  
  require_once 'inc/theme.util.php';
  // ThemeUtil::log_something("functions start: " . get_execution_time() );
  
  require_once 'inc/theme.shortcodes.php';
  require_once 'inc/theme.options.php';
  require_once 'inc/theme.admin.php';
  require_once 'inc/theme.php';
  require_once 'inc/development.type.php';
  // require_once 'inc/program.type.php';
  require_once 'inc/property.type.php';
  // require_once 'inc/event.type.php';
  require_once 'inc/volunteer.type.php';
  

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
   * @since Twenty Eleven 1.0  [cat_name] => News
   */
  function old_chp_posted_on() {
  	printf( __( '<div class="posted-on"><span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span></div>', 'chp' ),
  		esc_url( get_permalink() ),
  		esc_attr( get_the_time() ),
  		esc_attr( get_the_date( 'c' ) ),
  		esc_html( get_the_date() ),
  		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
  		sprintf( esc_attr__( 'View all posts by %s', 'chp' ), get_the_author() ),
  		esc_html( get_the_author() )
  	);
  }
  function chp_posted_on() {
  	printf( __( '<div class="posted-on"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></div>', 'chp' ),
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

  function _split_save_datetime( $date, $post_id=0, $prefix ){

		$date_from = date_parse_from_format("l, F j Y", $date );

  	$datetime = mktime( 
  		$date_from['hour'], $date_from['minute'], $date_from['second'], 
  		$date_from['month'], $date_from['day'], $date_from['year'], $date_from['is_dst'] 
  	);

  	$parts = array(
  		'month_num'				=> date( "n", $datetime ),
  		'month_name'			=> date( "F", $datetime ),
  		'month_name_short'		=> date( "M", $datetime ),
  		'day_num'				=> date( "j", $datetime ),
  		'day_name'				=> date( "l", $datetime ),
  		'day_name_short'		=> date( "D", $datetime ),
  		'year'					=> date( "Y", $datetime ),
  		'year_short'			=> date( "y", $datetime ),
  	);

  	// these just need to be used for past/future openings & exhibitions and display
  	if( $post_id ){
  		update_post_meta( $post_id, $prefix. '_month_num', $parts['month_num'] );
  		// ThemeAdmin::idx_and_save( $post_id, 'featured_post', $_REQUEST['featured_post'] );
  		update_post_meta( $post_id, $prefix. '_month_name', $parts['month_name'] );
  		update_post_meta( $post_id, $prefix. '_month_name_short', $parts['month_name_short'] );
  		update_post_meta( $post_id, $prefix. '_day_num', $parts['day_num'] );
  		update_post_meta( $post_id, $prefix. '_day_name', $parts['day_name'] );
  		update_post_meta( $post_id, $prefix. '_day_name_short', $parts['day_name_short'] );
  		update_post_meta( $post_id, $prefix. '_year', $parts['year'] );
  		update_post_meta( $post_id, $prefix. '_year_short', $parts['year_short'] );
  	}

  	return $parts;

  }
  
    $development_args = array(
   'post_type'=>'developments',
   'orderby'=> 'post_title',
   'order'=> 'ASC',
   'numberposts' => -1
  );
  $development_types = array();
  $developments = get_posts( $development_args );
  

  foreach( $developments as $development ){
    $development_types[] = $development->post_title;
  } 
	
  function _save_rsvp(){

    // check_ajax_referer( NONCE_STRING, '_wpnonce' );

    $response = array(
      'result' => 0,
      // '_data' => $_REQUEST['data']
    );

    $data = $_REQUEST['data'];
    if( !empty( $data ) ){

      $email = $data['email'];
      $name = $data['name'];
      $post_id = $data['post_id'];

      $custom = get_post_custom( $post_id );
      // $response['_custom'] = $custom;
      
      $rsvps = $custom['rsvps'][0]; // get_post_meta( $post_id, 'rsvps', true );
      if(!is_array($rsvps)) $rsvps = @unserialize($rsvps);
      if(!is_array($rsvps)) $rsvps = array();
      
      // $response['_rsvps'] = $rsvps;
      
      array_push( $rsvps, array(
        'email' => $email, 'name' => $name
      ) );
      $response['_after_rsvps'] = $rsvps;
      
      update_post_meta( $post_id, 'rsvps', $rsvps );
      
      $response['result'] = 1;
    }

    // delete_post_meta( $post_id, 'rsvps' );
    die( json_encode( $response ) );

  }
  add_action( 'wp_ajax_rsvp', 		            '_save_rsvp' );
  add_action( 'wp_ajax_nopriv_rsvp', 		      '_save_rsvp' );
  
  function chp_sidebar_pathflash(){
    ob_start();
    ?>
    <p> 
      <script type="text/javascript"> 
    AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','226','height','332','src','/wp-content/themes/chp/chp_learn','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','/wp-content/themes/chp/chp_learn' ); //end AC code
    </script> 
      <noscript> 
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="226" height="332">
        <param name="movie" value="/wp-content/themes/chp/chp_learn.swf" />
        <param name="quality" value="high" />
        <embed src="/wp-content/themes/chp/chp_learn.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="226" height="332"></embed>
      </object>
      </noscript> 
    </p>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
  
function chp_404_redirect(){
  if ( !is_404() )
		return;
		
	$redirection = array(
	  '/learn/credit_budget.php' => '/navigate_your_path/?nyp_step=credit_budget'
	 
	);
	
	$uri = $_SERVER["REQUEST_URI"];
	
	// layout.print $uri;
	if( in_array( $uri, $redirection ) ){
	  
	}
	// exit;
}
add_action( 'template_redirect', 'chp_404_redirect' );




// Replace nav titles with "Nav Title" meta field for that post in wp_list_pages
function chp_filter_nav_title($content) {
	$pattern = '@<li class="page_item page-item-(\d+)([^\"]*)"><a href=\"([^\"]+)" title="([^\"]+)">(.*?)</a>@is';
	return preg_replace_callback($pattern, "chp_filter_callback", $content);
}
function chp_filter_callback($matches) {
	global $wpdb;
	
	if ($matches[1] && !empty($matches[1])) $postID = $matches[1];
	
	if (empty($postID)) $postID = get_option("page_on_front");

	// now identifier is the post ID
	$title_attrib = stripslashes(get_post_meta($postID, 'title_attrib', true));
	$menulabel = stripslashes(get_post_meta($postID, 'Nav Title', true));
	
	// need to check for additional link before and after tags
	if (preg_match('@^<([^>]+)>([^<]+)<([^>]+)>$@is', $matches[5], $anchort)) :
		$link_before = "<".$anchort[1].">";
		$link_after = "<".$anchort[3].">";
		$anchortxt = $anchort[2];
	else :
		$anchortxt = $matches[5];
		$link_before = $link_after = "";
	endif;
	
	if (empty($menulabel))
		$menulabel = $anchortxt;
		
	
	if ($title_attrib == "%%pagetitle%%") $title_attrib = get_the_title($postID);
	elseif ($title_attrib == "%%menulabel%%") $title_attrib = $menulabel;

	if (!empty($title_attrib)) :
		$filtered = '<li class="page_item page-item-'.$postID.$matches[2].'"><a href="'.$matches[3].'" title="'.$title_attrib.'">'.$link_before.$menulabel.$link_after.'</a>';
	else :
		$filtered = '<li class="page_item page-item-'.$postID.$matches[2].'"><a href="'.$matches[3].'">'.$link_before.$menulabel.$link_after.'</a>';
	endif;
	
	return $filtered;
}
add_filter('wp_list_pages','chp_filter_nav_title', 10);

function postHasImages($post_id){
  if( $attachments = get_children( 
		array(
			'post_parent' 		=> $post_id, 
			'post_status' 		=> 'inherit', 
			'post_type' 		  => 'attachment', 
			'post_mime_type'	=> 'image', 
			'order' 			    => 'ASC', 
			'orderby' 			  => 'menu_order ID',
			'numberposts' 		=> -1
		) 
	) ){
	  
	  return count($attachments) > 0;
  }
  
  return false;
}

function postSingleViewImages($post_id, $size='main-size', $caption_prefix='' ){
  
  if( $attachments = get_children( 
		array(
			'post_parent' 		=> $post_id, 
			'post_status' 		=> 'inherit', 
			'post_type' 		  => 'attachment', 
			'post_mime_type'	=> 'image', 
			'order' 			    => 'ASC', 
			'orderby' 			  => 'menu_order ID',
			'numberposts' 		=> -1
		) 
	) ){
	  $post_thumbnail_id = get_post_thumbnail_id( $post_id );
	  
	  ?>
	  <div id="slider-wrapper">
	    <div id="slider" class="nivoSlider">
  		  <?php
  		  // $size = 'main-size';
  		  foreach( $attachments as $k=>$att ){
  		    if( $post_thumbnail_id== $att->ID) continue; // skip featured/thumbnail added images
    		  // $custom = get_post_custom( $att->ID );
    		  $src = wp_get_attachment_image_src( $att->ID, $size, false );
	  
  			  $imgsrc = $src[0];
  				$width = $src[1];
  				$height = $src[2];
          $name = stripslashes($att->post_name);
          $caption = stripslashes($att->post_title);
          if( !empty($caption_prefix) ) $caption_prefix .= ": ";
  	    ?>
  	      <a href="<?php echo get_permalink($post_id); ?>"
  		    ><img title="<?php echo $caption_prefix . "" . $caption; ?>" class="single-property-view" src="<?php echo $imgsrc; ?>" 
  		      style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>" border="0" /></a>
  		      
        <?php
  	    }
  		  ?>
	    </div>
	  </div>
	  <?php
	}
	else
	{
	  ?>

	  <?php
	}
  
}

// addition of "the_slug" to help with tag lists on Development pages

function the_slug($echo=true){
  $slug = basename(get_permalink());
  do_action('before_slug', $slug);
  $slug = apply_filters('slug_filter', $slug);
  if( $echo ) echo $slug;
  do_action('after_slug', $slug);
  return $slug;
}

// added 2/15/13 to add elemets to search results
 function filter_search($query) {
 if (!is_admin())	{
    if ($query->is_search) {
	    // $query->set('post_type', array('post', 'page', 'property', 'development', 'program', 'event'));
	    $query->set('post_type', array('property', 'post', 'page', 'development', 'program', 'event'));
    // $query->set('post_type', array('property', 'development'));
  };
    return $query;
   };
 };
add_filter('pre_get_posts', 'filter_search');
/* Custom Widgets
===============================================================================*/
include(get_template_directory() . '/inc/custom-widgets/widget-donate.php');
include(get_template_directory() . '/inc/custom-widgets/widget-newsletter.php');
include(get_template_directory() . '/inc/custom-widgets/widget-events.php');
include(get_template_directory() . '/inc/custom-widgets/widget-social-links.php');
include(get_template_directory() . '/inc/custom-widgets/widget-news.php');
include(get_template_directory() . '/inc/custom-widgets/widget-news-posts.php');
include(get_template_directory() . '/inc/custom-widgets/widget-property-map.php');
include(get_template_directory() . '/inc/custom-widgets/widget-rental-info.php');
/* Custom Post Types
===============================================================================*/
include(get_template_directory() . '/inc/cpt/cpt-gallery.php');
/* Custom Feilds
===============================================================================*/
//define( 'ACF_LITE' , true );
include_once(get_template_directory() . '/inc/acf/acf.php' );
/* Custom Taxonomy
===============================================================================*/
function add_property_type_taxonomies() {
  $labels = array(
    'name'              => _x( 'Property Type', 'taxonomy general name' ), 
    'singular_name'         => _x( 'Property Type', 'taxonomy singular name' ),
    'all_items'           => __( 'All Property Types' ),
    'edit_item'           => __( 'Edit Property Types' ), 
    'view_item'           => __( 'View Property Type' ),
    'update_item'         => __( 'Update Property Types' ),
    'add_new_item'          => __( 'Add New Property Types' ),
    'new_item_name'         => __( 'New Property Type' ),
    'parent_item'         => __( 'Parent Property Type' ),
    'parent_item_colon'       => __( 'Parent Property Type:' ),
    'search_items'          => __( 'Search Property Types' ),
    'popular_items'         => __( 'Popular Property Types'),
    'separate_items_with_commas'  => __( 'Separate Property Types with commas' ),
    'add_or_remove_items'       => __( 'Add or Remove Property Type' ),
    'choose_from_most_used'     => __( 'Choose from the most used Property Types' ),
    'not_found'           =>  __( 'No Property Types found.' ),
    'menu_name'           => __( 'Property Types' ),
  );

  $args = array(
    'labels'        => $labels,
    'public'        => true,
    'show_ui'       => true,
    'show_in_nav_menus'   => true,
    'show_tagcloud'     => true,
    'show_admin_column'   => false,
    'hierarchical'      => true,
    'update_count_callback' => '',
    'query_var'       => true,
    'sort'          => '',
    'rewrite'       => array( 
                    'slug'      => 'property_type',
                    'with_front'  => false,
                    'hierarchical'  => true
                ), 
    'capabilities'      => array(
                  'manage_terms',
                  'edit_terms' ,
                  'delete_terms',
                  'assign_terms'
                )
      
  );

  $post_types = array(
    'property'
  );

  register_taxonomy( 'property_type', $post_types,  $args ); 
} 

add_action( 'init', 'add_property_type_taxonomies', 0 );