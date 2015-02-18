<?php 
/* Custom Post Type
===============================================================================*/
function register_example_cpt() {
	$labels = array( 
		'name' 					=> 'Example',
		'singular_name'			=> 'Example',
		'add_new' 				=> 'Add New',
		'all_items' 			=> 'All Examples',
		'add_new_item' 			=> 'Add New Example',
		'edit_item'				=> 'Edit Example',
		'new_item' 				=> 'New Example',
		'view_item' 			=> 'View Example',
		'search_items' 			=> 'Search Examples',
		'not_found' 			=> 'No Examples Found',
		'not_found_in_trash' 	=> 'No Examples Found in Trash',
		'parent_item_colon' 	=> 'Parent Example Post:',
		'menu_name' 			=> 'Example',
	);
	$args = array( 
		'labels' 				=> $labels,
		'description' 			=> 'This is a Custom Post Type',
		'public' 				=> true,
		'exclude_from_search' 	=> false,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true,
		'show_in_nav_menus' 	=> true,
		'show_in_menu' 			=> true,
		'show_in_admin_bar' 	=> true,
		'menu_position' 		=> 25,
		/*
		  5 - below Posts
		 10 - below Media
		 15 - below Links
		 20 - below Pages
		 25 - below comments
		 60 - below first separator
		 65 - below Plugins
		 70 - below Users
		 75 - below Tools
		 80 - below Settings
		100 - below second separator
		*/
		'menu_icon' 			=> NULL, /*get_stylesheet_directory_uri().'/images/your-image.png', */
		'capability_type' 		=> 'post',
		'capabilities' 			=> array(	
										'edit_post', 
										'read_post', 
										'delete_post',
										'edit_posts',
										'edit_others_posts',
										'publish_posts',
										'read_private_posts'
									),
		'map_meta_cap' 			=> true,
		'hierarchical' 			=> false,
		'supports' 				=> array( 	
										'title',
										'editor',
										//'author',
										//'thumbnail',
										//'excerpt',
										//'trackbacks',
										//'custom-fields',
										//'comments' ,
										//'revisions',
										//'page-attributes',
										//'post-formats'
									),
		/* 
		'register_meta_box_cb' => 'add_custom_meta_box',
		 */
		'taxonomies' 			=> array( 'category', 'tag'),
		'has_archive'			=> true,
		'permalink_epmask' 		=> EP_PERMALINK,
		'rewrite' 				=> array( 	
										'slug' 			=> 'example', 
										'with_front' 	=> false,
										'feeds' 		=> true,
										'pages' 		=> true,
										'ep_mask' 		=> EP_PERMALINK
									),
		'query_var' 			=> true,
		'can_export' 			=> true,
	);
	register_post_type( 'example', $args );
}
add_action( 'init', 'register_example_cpt' );

/* Custom Taxonomy
===============================================================================*/

function add_Example_taxonomies() {
	$labels = array(
		'name'							=> _x( 'Example', 'taxonomy general name' ), 
		'singular_name'					=> _x( 'Example', 'taxonomy singular name' ),
		'all_items'						=> __( 'All Examples' ),
		'edit_item'						=> __( 'Edit Examples' ), 
		'view_item' 					=> __( 'View Example' ),
		'update_item'					=> __( 'Update Examples' ),
		'add_new_item'					=> __( 'Add New Examples' ),
		'new_item_name'					=> __( 'New Example' ),
		'parent_item'					=> __( 'Parent Example' ),
		'parent_item_colon'				=> __( 'Parent Example:' ),
		'search_items'					=> __( 'Search Examples' ),
		'popular_items'					=> __( 'Popular Examples'),
		'separate_items_with_commas'	=> __( 'Separate Examples with commas' ),
		'add_or_remove_items' 			=> __( 'Add or Remove Example' ),
		'choose_from_most_used'			=> __( 'Choose from the most used Examples' ),
		'not_found'						=>  __( 'No Examples found.' ),
		'menu_name' 					=> __( 'Examples' ),
	);

	$args = array(
		'labels'				=> $labels,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_nav_menus'		=> true,
		'show_tagcloud'			=> true,
		'show_admin_column'		=> false,
		'hierarchical'			=> true,
		'update_count_callback'	=> '',
		'query_var'				=> true,
		'sort'					=> '',
		'rewrite'				=> array( 
										'slug'			=> 'example',
										'with_front'	=> false,
										'hierarchical'	=> true
								), 
		'capabilities' 			=> array(
									'manage_terms',
									'edit_terms' ,
									'delete_terms',
									'assign_terms'
								)
			
	);

	$post_types = array(
		'example'
	);

	register_taxonomy( 'example', $post_types,  $args ); 
} 

add_action( 'init', 'add_Example_taxonomies', 0 );
?>